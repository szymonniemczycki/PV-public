<?php

declare(strict_types=1);

namespace App;

//used classes
use Throwable;

require_once("src/ErrorLogs.php");

//core for service download prices
class GetPrice 
{
    private const PSE_URL = "https://www.pse.pl/getcsv/-/export/csv/PL_CENY_RYN_EN/data/";
    private const RESOURCES_PATH = "resources/prices/";
    
    private string $url;
    private int $day; 
    private ErrorLogs $errorLogs;
    

    public function __construct($date = NULL) {
        $this->errorLogs = new ErrorLogs();
    }

    //check is csv file exist
    public function checkIsCsvExist($day): bool
    {
        if (empty(file_exists(self::RESOURCES_PATH . $day . ".csv"))) {
            return false; 
        }
        return true;
    }

    //method for download csv file from external server
    public function downloadCSV(int $day): string
    {
        try {
            $url = self::PSE_URL . $day;
            $context = stream_context_create(
                array(
                    "http" => array(
                        "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
                    )
                )
            );
            $dayData = file_get_contents($url, false, $context);
            $path = self::RESOURCES_PATH . $day . ".csv";
            (bool) file_put_contents($path, $dayData);
        } catch (Throwable $e) {
            $this->errorLogs->saveErrorLog(
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
        $filePath = self::RESOURCES_PATH . $day . ".csv";
        $filePath = fopen($filePath, "r");
        
        if ($filePath !== false) {            
            $pricesCollection[$day] = [];
            $firstRow = true;
            while (!feof($filePath)) {
                $data = fgetcsv($filePath, 0 , ";");
                    if (!empty($data)) {
                        if ($firstRow) {
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

    //delete price from CSV - needed for force download
    public function deleteCSV($day): bool
    {
        $isFile = $this->checkIsCsvExist($day);
        if ($isFile) {
            $path = self::RESOURCES_PATH . $day . ".csv";
            unlink($path);
            return true;
        } else {
            return false;
        }
    }

    
}
