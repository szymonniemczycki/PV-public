<?php
declare(strict_types=1);

namespace App;

//class for generate view page in application
class View 
{

    //method to render layout of web-page
    public function render(string $page, array $viewParams, array $userPerm): void 
    {
        include_once("templates/layout.php");
    }

    //method to show info (alert on top)
    public function showInfo(string $msg)
    {
        include_once("templates/pages/showInfo.php");
    }

    //escaping value from get param - e.g. injection
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
