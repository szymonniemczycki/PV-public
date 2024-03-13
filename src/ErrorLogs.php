<?php
declare(strict_types=1);

namespace App;

require_once("src/ErrorLogs.php");
require_once("src/View.php");

class ErrorLogs 
{
    private View $view;
    private const PAGE_SIZE = 10;

    
    public function __construct(string $date = NULL) 
    {
        $this->view = new View();
        if (empty(file_exists("logs/errors.txt"))) {
            fopen("logs/errors.txt", 'www') or die("Can't create file");
            $this->view->render("404", []);
        }
    }


    public function saveErrorLog(string $log, string $type, string $msg): void
    {
        error_log(
            date("Y-m-d") . ";" .
            date("H:i:s") . ";" .
            $type . ";" .
            $msg .
            "\n",
            3, 
            "logs/errors.txt"
            );
        $this->view->render("404", []);
    }


    public function getErrors(array $filterParams): array
    {            
        $filterParams = $this->view->escape($filterParams);
        $dataFromFile = $this->getErrorData();
        $filterParams['pageNr'] = $this->validatePageNr($filterParams['pageNr'], sizeof($dataFromFile));
        
        $dataFromFile = $this->sortErrorData($dataFromFile, $filterParams['sort']);
        $dataFromFile = $this->filterByDate($dataFromFile, $filterParams['date']);
        $dataFromFile = $this->filterBySearch($dataFromFile, $filterParams['phrase']);
        $dataFromFilePag = $this->paginationErrorData($dataFromFile, self::PAGE_SIZE, $filterParams['pageNr']);
        
        $errorData['filters'] = $filterParams;
        $errorData['data'] = $dataFromFilePag;
        
        return $errorData;
    }


    private function validatePageNr(?string $pageNr, int $countData): int
    {
        $pageNr = (int) $pageNr;
        if ($pageNr > $countData || $pageNr < 0) {
            $pageNr = 1;
        }
        return $pageNr;
    }


    private function getErrorData(): array
    {
        $rowFile = [];
        $handle = fopen("logs/errors.txt", "rb");
        while(!feof($handle)) {
            $rowData = fgets($handle);
            if ($rowData) {
                $row = explode(";", $rowData);
                if(strpos($row[0], "\n") !== FALSE || sizeof($row) != 4) {
                    continue;
                }
                array_push($rowFile, $row);
            }
        }
        fclose($handle);
        return $rowFile;
    }


    private function getAllTypes(array $data): array
    {
        $types = [];
            foreach ($data as $key => $value) {    
                array_push($types, $data[$key][0]);
            }
        return array_values(array_unique($types));
    }


    private function filterByType(array $data, ?string $filterType): array
    {
        if ($filterType === "all" || $filterType === NULL || empty($filterType)) {
            return $data;
        }
        $filteredData = [];
        foreach ($data as $key => $value) {    
            if($data[$key][0] === $filterType) {
                array_push($filteredData, $data[$key]);
            }
        }
        return $filteredData;
    }


    private function filterByDate(array $data, ?string $filterDate): array
    {
        if ($filterDate === "" || $filterDate === NULL) {
            return $data;
        }
        $filteredData = [];
        foreach ($data as $key => $value) {
            if($data[$key][0] === $filterDate) {
                array_push($filteredData, $data[$key]);
            }
        }
        return $filteredData;
    }


    private function filterBySearch(array $data, ?string $filterSearch): array
    {
        if ($filterSearch === "" || $filterSearch === NULL) {
            return $data;
        }
        $searchData = [];
        foreach ($data as $key => $value) {    
            if(str_contains(strtolower($data[$key][2]), strtolower($filterSearch))) {
                array_push($searchData, $data[$key]);
            } elseif (str_contains(strtolower($data[$key][3]), strtolower($filterSearch))) {
                array_push($searchData, $data[$key]);
            }
        }
        return $searchData;
    }


    private function sortErrorData(array $data, ?string $sortOrder): array
    {
        if (!in_array($sortOrder, ["asc", "desc"])) {
            $sortOrder = "desc";
        }
        $sortOrder = empty($sortOrder) ? "desc" : $sortOrder;
        if ($sortOrder == "asc") {
            return $data;
        } elseif ($sortOrder == "desc") {
            return array_reverse($data);
        }
    }
    
    
    private function paginationErrorData(array $data, int $pageSize, int $pageNrUrl): array
    {
        if (empty($data)) {
            return $data;
        }
        $pageNr = 0;
        
        for ($i=0; $i < sizeof($data); $i++) {
            array_unshift($data[$i], $i);
            if($i % $pageSize == 0) {
                $pageNr++;
                $paginationData[$pageNr] = [];
            }
            array_push($paginationData[$pageNr], $data[$i]);
        }
        return $paginationData;
    }


}
