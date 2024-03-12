<?php
declare(strict_types=1);

namespace App;

class View 
{

    public function render(string $page, array $viewParams = []): void 
    {
        include_once("templates/layout.php");
    }


    public function showInfo(string $msg){
        include_once("templates/pages/showInfo.php");
    }


    public function escape(array $params): array 
    {
        $clearParams = [];
        
        foreach ($params as $key => $param) {
            switch (true) {
                case is_array($param):
                    $clearParams[$key] = $this->escape($param);
                    break;
                case is_int($param):
                    $clearParams[$key] = $param;
                    break;
                case $param:
                    $clearParams[$key] = htmlentities((string) $param);
                    break;
                default:
                    $clearParams[$key] = $param;
                    break;
            }

        }
        return $clearParams;
    }


}
