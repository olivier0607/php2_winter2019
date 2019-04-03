<?php

namespace Application\Services;

trait CrudServiceTrait
{

    protected $crudService;

    /**
     * @return mixed
     */
    public function getCrudService()
    {
        return $this->crudService;
    }

    /**
     * @param mixed $crudProducts
     */
    public function setCrudService($crudService)
    {
        $this->crudService = $crudService;
    }
}
