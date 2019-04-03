<?php
/**
 * Created by PhpStorm.
 * User: z_hexiao
 * Date: 2019-02-06
 * Time: 6:43 PM
 */

class TemplateManager
{
    protected $htmlOut;
    protected $data = [];
    protected $dataLength;
    public function loadTemplate(){
        $this->htmlOut = '<!DOCTYPE html>';
        $this->htmlOut .= '<html>';
        $this->htmlOut .= '<head>';
        $this->htmlOut .= '<link rel="stylesheet" href="styles.css">';
        $this->htmlOut .= '</head>';
        $this->htmlOut .= '<body>';
        $this->htmlOut .= '<table border="1">';
        $this->htmlOut .= '<tr>'.'<th>'.'ID'.'</th>'.'<th>'.'First Name'.'</th>'.'<th>'.'Last Name'.'</th>'.'</tr>';
        for($i=0;$i<count($this->data);$i++)
        {
            $this->htmlOut .= '<tr>';
            for($j=0;$j<sizeOf($this->data{$i});$j++){
                $this->htmlOut .= '<td>';
                $this->htmlOut .= $this->data[$i][$j];
                $this->htmlOut .= '</td>';

            }
            $this->htmlOut .= '</tr>';
            $this->htmlOut .= '<br>';



        }


        $this->htmlOut .= '</table>';
        $this->htmlOut .= '</form>';
        $this->htmlOut .= '</body>';
        $this->htmlOut .= '</html>';
     //
    }

    public function render()
    {
        echo $this->htmlOut;
    }

    public function getData(){
         return $this->data;
    }
    public function setData($data){
          $this->data = $data;
    }

}