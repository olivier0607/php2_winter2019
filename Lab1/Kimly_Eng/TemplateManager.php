<?php

class TemplateManager
{
    protected $htmlOut;

    protected $data = [];

    public function loadTemplate()
    {
        $this->htmlOut = '<!DOCTYPE html>';
        $this->htmlOut .= '<html>';
        $this->htmlOut .= '<head>';
        $this->htmlOut .= '</head>';
        $this->htmlOut .= '<body>';
        $this->htmlOut .= '<form>';
        $this->htmlOut .= '<p>Hello '
            . $this->data['firstName']
            . ' '
            . $this->data['lastName']
            . '<br>';
        $this->htmlOut .= 'You are ' . $this->data['age'] . ' years old!</p>';

        $this->htmlOut .= '</body>';
        $this->htmlOut .= '</html>';
    }

    public function render()
    {
        echo $this->htmlOut;
    }

    /**
     * @return mixed
     */
    public function getHtmlOut()
    {
        return $this->htmlOut;
    }

    /**
     * @param mixed $htmlOut
     * @return TemplateManager
     */
    public function setHtmlOut($htmlOut)
    {
        $this->htmlOut = $htmlOut;
        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return TemplateManager
     */
    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }


}



