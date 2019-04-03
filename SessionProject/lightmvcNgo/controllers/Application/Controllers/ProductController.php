<?php

namespace Application\Controllers;
session_start();

use Application\Models\Entity\Products;
use Application\Services\CrudProductsService;
use Application\Services\CrudServiceTrait;
use Ascmvc\AscmvcControllerFactoryInterface;
use Ascmvc\Mvc\AscmvcEventManager;
use Ascmvc\Mvc\Controller;
use Ascmvc\Mvc\AscmvcEvent;
use Pimple\Container;

class ProductController extends Controller implements AscmvcControllerFactoryInterface
{


    use CrudServiceTrait;

    public static function factory(array &$baseConfig, &$viewObject, Container &$serviceManager, AscmvcEventManager &$eventManager)
    {
        $serviceManager[ProductController::class] = $serviceManager->factory(function ($serviceManager) use ($baseConfig) {
            $em = $serviceManager['dem1'];

            $products = new Products();

            $crudService = new CrudProductsService($products, $em);

            $controller = new ProductController($baseConfig);

            $controller->setCrudService($crudService);

            return $controller;
        });
    }

    /*public function onDispatch(AscmvcEvent $event)
    {
        $array = [
            'firstname' => 'Andrew',
            'lastname' => 'Caya',
            'age' => 42,
        ];

        $response = new Response();
        $response->getBody()->write(json_encode($array));
        $response = $response
            ->withStatus(200)
            ->withHeader('Content-Type', 'application/json')
            ->withAddedHeader('X-Custom-Header', 'it works');

        return $response;
    }*/

    public function onDispatch(AscmvcEvent $event)
    {
        $this->view['saved'] = 0;

        $this->view['error'] = 0;
    }

    protected function readProducts($id = null)
    {
        if ($id == null) {
            return $this->crudService->read();
        } else {
            return $this->crudService->read($id);
        }
    }

    protected function hydrateArray(Products $object)
    {
        $array['id'] = $object->getId();
        $array['name'] = $object->getName();
        $array['price'] = $object->getPrice();
        $array['description'] = $object->getDescription();
        $array['image'] = $object->getImage();

        return $array;
    }


    public function indexAction($vars = null)
    {
        $results = $this->readProducts();

        if (is_object($results)) {
            $results = [$this->hydrateArray($results)];
        } elseif (is_array($results)) {
            for ($i = 0; $i < count($results); $i++) {
                $results[$i] = $this->hydrateArray($results[$i]);
            }
        } else {
            $results['nodata'] = 'No results';
        }

        $this->view['bodyjs'] = 1;

        $this->view['results'] = $results;

        $this->view['templatefile'] = 'product_index';

        return $this->view;
    }

    public function addAction($vars)
    {
        if (!empty($vars['post'])) {
            $productArray['name'] = $this->crudService->sanitizeString((string)$vars['post']['name']);
            $productArray['price'] = $this->crudService->sanitizeWithNumber((string)$vars['post']['price']);
            $productArray['description'] = $this->crudService->sanitizeWithNumber((string)$vars['post']['description']);
            $productArray['image'] = $this->crudService->sanitizeString((string)$vars['files']['image']->getClientFilename());

            if ($this->crudService->create($productArray)) {
                $this->view['saved'] = 1;
            } else {
                $this->view['error'] = 1;
            }
        }

        if (!isset($_SESSION['LOGGEDIN'])) {
            $this->view['templatefile'] = 'c405_index';
            return $this->view;
        } else {


            $this->view['bodyjs'] = 1;

            $this->view['templatefile'] = 'product_add_form';

            return $this->view;
        }
    }

    public function editAction($vars)
    {
        if (!empty($vars['post'])) {
            $productArray['id'] = $this->crudService->sanitizeWithNumber((string)$vars['post']['id']);
            $productArray['name'] = $this->crudService->sanitizeString((string)$vars['post']['name']);
            $productArray['price'] = $this->crudService->sanitizeWithNumber((string)$vars['post']['price']);
            $productArray['description'] = $this->crudService->sanitizeWithNumber((string)$vars['post']['description']);


            if (!empty($vars['files']['image']->getClientFilename())) {
                $productArray['image'] = (string)$vars['files']['image']->getClientFilename();
            } else {
                $productArray['image'] = (string)$vars['post']['imageoriginal'];
            }
            $productArray['image'] = $this->crudService->sanitizeString($productArray['image']);

            if ($this->crudService->update($productArray)) {
                $this->view['saved'] = 1;
            } else {
                $this->view['error'] = 1;
            }
        }

        $results = $this->readProducts($vars['get']['id']);

        if (is_object($results)) {
            $results = [$this->hydrateArray($results)];
        } else {
            for ($i = 0; $i < count($results); $i++) {
                $results[$i] = $this->hydrateArray($results[$i]);
            }
        }

        if (!isset($_SESSION['LOGGEDIN'])) {
            $this->view['templatefile'] = 'c405_index';
            return $this->view;
        } else {

            $this->view['results'] = $results;

            $this->view['bodyjs'] = 1;

            $this->view['templatefile'] = 'product_edit_form';

            return $this->view;
        }


    }

    public function deleteAction($vars)
    {
        // Sanitize and filter the $_GET array.
        $id = $this->crudService->sanitizeWithNumber((int)$vars['get']['id']);

        if ($this->crudService->delete($id)) {
            $this->view['saved'] = 1;
        } else {
            $this->view['error'] = 1;
        }

        if (!isset($_SESSION['LOGGEDIN'])) {
            $this->view['templatefile'] = 'c405_index';
            return $this->view;
        } else {

            $this->view['templatefile'] = 'product_delete';

            return $this->view;
        }

    }
}
