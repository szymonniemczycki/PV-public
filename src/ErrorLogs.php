<?php
declare(strict_types=1);

namespace App;

require_once("src/ErrorLogs.php");
require_once("src/View.php");

//for save error logs in file
class ErrorLogs 
{
    private const ERROR_PATH = "logs/errors.txt";
    private const PAGE_SIZE = 10;
    private View $view;

    //create handle to save logs
    public function __construct(string $date = NULL) 
    {
        $this->view = new View();
        if (empty(file_exists(self::ERROR_PATH))) {
            fopen(self::ERROR_PATH, 'www') or die("Can't create file");
            $this->view->render("404", []);
        }
    }

    //method for save error-log
    public function saveErrorLog(string $type, string $msg): void
    {
        error_log(
            date("Y-m-d") . ";" .
            date("H:i:s") . ";" .
            $type . ";" .
            $msg .
            "\n",
            3, 
            self::ERROR_PATH
        );
        header('Location: ./404.php');
    }

    //get errors items for listing
    public function getErrors(array $filterParams): array
    {            
        $filterParams = $this->view->escape($filterParams);
        $dataFromFile = $this->getErrorData();
        $filterParams['pageNr'] = $this->validatePageNr($filterParams['pageNr'], count($dataFromFile));
        
        $dataFromFile = $this->sortErrorData($dataFromFile, $filterParams['sort']);
        $dataFromFile = $this->filterByDate($dataFromFile, $filterParams['date']);
        $dataFromFile = $this->filterBySearch($dataFromFile, $filterParams['phrase']);
        $dataFromFilePag = $this->paginationErrorData($dataFromFile, self::PAGE_SIZE, $filterParams['pageNr']);
        
        $errorData['filters'] = $filterParams;
        $errorData['errors'] = $dataFromFilePag;
        
        return $errorData;
    }

    //validate page number from URL
    private function validatePageNr(?string $pageNr, int $countData): int
    {
        $pageNr = (int) $pageNr;
        if ($pageNr > $countData || $pageNr < 0) {
            $pageNr = 1;
        }
        return $pageNr;
    }

    //get all items form file
    private function getErrorData(): array
    {
        $rowFile = [];
        $handle = fopen(self::ERROR_PATH, "rb");
        while(!feof($handle)) {
            $rowData = fgets($handle);
            if ($rowData) {
                $row = explode(";", $rowData);
                if (strpos($row[0], "\n") !== FALSE || count($row) != 4) {
                    continue;
                }
                array_push($rowFile, $row);
            }
        }
        fclose($handle);
        return $rowFile;
    }

    //get error items filtered by Date
    private function filterByDate(array $data, ?string $filterDate): array
    {
        if ($filterDate === "" || $filterDate === NULL) {
            return $data;
        }
        $filteredData = [];
        foreach ($data as $key => $value) {
            if ($data[$key][0] === $filterDate) {
                array_push($filteredData, $data[$key]);
            }
        }
        return $filteredData;
    }

    //get error items filtered by search
    private function filterBySearch(array $data, ?string $filterSearch): array
    {
        if ($filterSearch === "" || $filterSearch === NULL) {
            return $data;
        }
        $searchData = [];
        foreach ($data as $key => $value) {    
            if (str_contains(strtolower($data[$key][2]), strtolower($filterSearch))) {
                array_push($searchData, $data[$key]);
            } elseif (str_contains(strtolower($data[$key][3]), strtolower($filterSearch))) {
                array_push($searchData, $data[$key]);
            }
        }
        return $searchData;
    }

    //sort error items - order by lines in file
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
    
    //method for creating array with pagination data (2-level array - 1st level it's number of page, 2nd level it's items for page) 
    private function paginationErrorData(array $data, int $pageSize, int $pageNrUrl): array
    {
        if (empty($data)) {
            return $data;
        }
        $pageNr = 0;
        
        for ($i=0; $i < count($data); $i++) {
            array_unshift($data[$i], $i);
            if ($i % $pageSize == 0) {
                $pageNr++;
                $paginationData[$pageNr] = [];
            }
            array_push($paginationData[$pageNr], $data[$i]);
        }
        return $paginationData;
    }


}
