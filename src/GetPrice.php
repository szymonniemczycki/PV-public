<?php

declare(strict_types=1);

namespace App;

use PDO;
use PDOException;
use Throwable;
use Exception;
use Error;

require_once("src/ErrorLogs.php");

class GetPrice 
{
    private string $url;
    private int $day; 
    private ErrorLogs $errorLogs;


    public function __construct($date = NULL) 
    {
        $this->errorLogs = new ErrorLogs();
    }


    public function checkIsCsvExist($day): bool
    {
        if (empty(file_exists("resources/prices/" . $day . ".csv"))) {
            return false; 
        }
        return true;
    }


    public function downloadCSV(int $day): string
    {
        try {
            $url = "https://www.pse.pl/getcsv/-/export/csv/PL_CENY_RYN_EN/data/" . $day;
            
            $context = stream_context_create(
                array(
                    "http" => array(
                        "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                    )
                )
            );
            $dayData = file_get_contents($url, false, $context);
            $path = "resources/prices/" . $day . ".csv";
            (bool) file_put_contents($path, $dayData);
        } catch (Throwable $e) {
            $this->errorLogs->saveErrorLog(
                "error",
                $e->getFile() . " <br />line: " . $e->getLine(),
                $e->getMessage()
            );
           exit;
        }
        return $dayData;
    }


    //get Prices from CSV:
    public function getPriceFromCSV($day): array
    {
        $filePath = "resources/prices/" . $day . ".csv";
        $filePath = fopen($filePath, "r");
        
        if ($filePath !== false) {            
            $pricesCollection[$day] = [];
            $firstRow = true;
            while (!feof($filePath)) {
                $data = fgetcsv($filePath, 0 , ";");
                    if (!empty($data)) {
                        if($firstRow) {
                            $firstRow = false;
                            continue;
                        }
                        $price = (float) (str_replace(',', '.', $data[2]));
                        $pricesCollection[$data[0]] += [
                            (int)$data[1] => $price,
                        ];
                    }
            }
        }
        fclose($filePath);
        return $pricesCollection;
    }


    public function deleteCSV($day): bool
    {
        $isFile = $this->checkIsCsvExist($day);
        if($isFile) {
            $path = "resources/prices/" . $day . ".csv";
            unlink($path);
            return true;
        } else {
            return false;
        }
    }

    
}
