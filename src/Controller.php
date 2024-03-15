<?php
declare(strict_types=1);

namespace App;

use Exception;
use PDO;
use Throwable;
use PDOException;
use App\Model\AppLogModel;
use App\Model\PriceModel;


class Controller 
{
    private static array $configuration = [];
    private const DEFAULT_ACTION = 'main';

    private AppLogModel $appLogsModel;
    private PriceModel $priceModel;
    private Request $request;
    private View $view;
    private GetPrice $getPrice;
    private ErrorLogs $errorLogs;

    private int $day;
    private string $msg = "";

    
    public static function initConfiguration(array $configuration): void
    {
        self::$configuration = $configuration;
    }


    public function __construct($request)
    {
        if (empty(self::$configuration['db'])) {
            exit('błędna konfiguracja');
        }
        $this->priceModel = new PriceModel(self::$configuration['db']);
        $this->appLogsModel = new AppLogModel(self::$configuration['db']);
        $this->request = $request;
        $this->view = new View();
        $this->errorLogs = new ErrorLogs();
    }


    public function run(): void
    {
        if (!empty($this->request->postParam('page'))) {
            $page = $this->request->postParam('page');
        } else {
            $page = $this->request->getParam('page', self::DEFAULT_ACTION);
        }
        $niceDate = $this->request->postParam('niceDate'); 
        
        switch($page) {
            case "prices" :
                $day = $this->validDate($page, $niceDate); 
                try {
                    $viewParams = ($this->priceModel->listPrice($day));
                    $niceDate = $this->getDateFormat($day);
                } catch (Throwable $e) {
                    $this->errorLogs->saveErrorLog(
                        $e->getFile() . " <br />line: " . $e->getLine(),
                        $e->getMessage()
                    );
                    exit;
                }

                if (!empty($this->msg) && !empty($usedForm)) {
                    $this->view->showInfo($this->msg);
                }
                
                $this->view->render(
                    $page,
                    [
                        'date' => $day,
                        'niceDate' => $niceDate,
                        'listPrices' => $viewParams
                    ]
                );

                break;

            case "import":
                $day = $this->validDate($page, $niceDate);
                $this->getPrice = new GetPrice((int) $day);
                $dataExist = (int) $this->priceModel->checkIsDataExist($day);
                $csvExist = (int) $this->getPrice->checkIsCsvExist($day);

                if ($dataExist) {
                    $viewParams['error'] = "dataExist";
                } else {
                    if ($csvExist) {
                        $pricesCollection = $this->getPrice->getPriceFromCSV($day);
                        $pricesImported = (int) $this->priceModel->savePrice($pricesCollection);
                        $viewParams['error'] = "dataimportedFromCsv";
                    } else {
                        $importedPrices = $this->getPrice->downloadCSV((int) $day);
                        $pricesFromCsv=$this->getPrice->getPriceFromCSV($day);                        
                            if ((!empty($importedPrices)) && (!empty($pricesFromCsv))) {
                                $pricesFromCsv = (int) $this->priceModel->savePrice($pricesFromCsv);
                                $viewParams['error'] = "imported";
                                $this->appLogsModel->saveLog("manual", "imported", "correctly", 0);
                            }
                    }
                }
                $this->view->render(
                    $page,
                    [
                        'date' => $day,
                        'niceDate' => $niceDate,
                        'listPrices' => $viewParams
                    ]
                );
                break;

            case "forceImport":
                try {
                    $day = $this->validDate($page, $niceDate);
                    $this->getPrice = new GetPrice((int) $day);
                    $importedPrices = $this->getPrice->downloadCSV((int) $day);
                    $pricesFromCsv = $this->getPrice->getPriceFromCSV($day);                    
                    $pricesFromCsv = (int) $this->priceModel->savePrice($pricesFromCsv);
                
                    $this->msg = "imported";
                    $page = $this->request->setPostParam('page', 'prices');
                    $this->appLogsModel->saveLog("manual", "forcedDownload", "correctly", 0);
                    $this->run();
                } catch (Throwable $e) {
                    $this->errorLogs->saveErrorLog(
                        $e->getFile() . " <br />line: " . $e->getLine(),
                        $e->getMessage()
                    );
                    exit;
                }
                break;

            case "forceDownload":
                try {
                    $day = $this->validDate($page, $niceDate);
                    $this->getPrice = new GetPrice((int) $day);

                    $dataExist = (int) $this->priceModel->checkIsDataExist($day);
                    $deletePriceFromDB = $dataExist ? (int) $this->priceModel->deletePrice($day) : 0;
                    
                    $csvExist = (int) $this->getPrice->checkIsCsvExist($day);
                    $deleteCsvFile = $csvExist ? $this->getPrice->deleteCSV($day) : 0;

                    $importedPrices = $this->getPrice->downloadCSV((int) $day);
                    $pricesFromCsv = $this->getPrice->getPriceFromCSV($day);  

                    if ((!empty($importedPrices)) && (!empty($pricesFromCsv))) {
                        $pricesFromCsv = (int) $this->priceModel->savePrice($pricesFromCsv);
                    }

                    $this->msg = "imported";
                    $page = $this->request->setPostParam('page', 'prices');
                    $this->appLogsModel->saveLog("manual", "updated", "correctly", 0);
                    $this->run();

                } catch (Throwable $e) {
                    $this->errorLogs->saveErrorLog(
                        $e->getFile() . " <br />line: " . $e->getLine(),
                        $e->getMessage()
                    );
                    exit;
                }
                break;

            case "logs":
                $sortOrder = $this->request->getParam('sort');
                if (!in_array ($sortOrder, ["asc", "desc"])) {
                    $sortOrder = "desc";
                }

                $params['logTypes'] = $this->appLogsModel->getUniqueLog();            
                $params['filters']['log'] = $this->request->getParam('log');  
                $params['filters']['date'] = $this->request->getParam('date');
                $params['filters']['phrase'] = $this->request->getParam('phrase');
                $params['filters']['sort'] = $sortOrder;
                $params['filters']['pageNr'] = $this->request->getParam('pageNr');
                $params['countPage'] = (int) $this->appLogsModel->getCountPage($params['filters']);
                
                $params['filters']['pageNr'] = $this->validatePageNr($params['filters']['pageNr'], $params['countPage']);
                $params['logs'] = $this->appLogsModel->getListLogs($params['filters']);
                
                $this->view->render("logs", $params);
                break;  

            case "errors":
                $params['filters']['date'] = $this->request->getParam('date');
                $params['filters']['phrase'] = $this->request->getParam('phrase');
                $params['filters']['sort'] = $this->request->getParam('sort');
                $params['filters']['pageNr'] = $this->request->getParam('pageNr');
        
                $this->errorLogs = new ErrorLogs();
                $errors = $this->errorLogs->getErrors($params['filters']);
                
                $this->view->render("errors", $errors);
                break;

            default:
                $this->view->render("main", []);
                break;
            }
    }

    private function validatePageNr(?string $pageNr, int $countData): int
    {
        $pageNr = (int) $pageNr;
        if ($pageNr > $countData || $pageNr <= 0){
            $pageNr = 1;
        }
        return $pageNr;
    }


    protected function redirect(string $to, array $params): void 
    {
      $location = "." . $to;

      if (count($params)) {
        $queryParams = [];
        foreach ($params as $key => $value) {
          $queryParams[] = urlencode($key) . "=" . urlencode($value);
        }
        $queryParams = implode('&', $queryParams);
        $location .= '?' . $queryParams;
      }
      header("Location: $location");
      exit();
    }


    protected function action(): string
    {
        return $this->request->getParam('action', self::DEFAULT_ACTION);
    }


    private function validDate($page, $niceDate): string
    {
        if (!empty($niceDate)) {
            $day = str_replace("-", "", $niceDate);

            if ((int) $day == 0) {
                $viewParams['error'] = "wrongData";
            } else if (strlen((string) $day) != 8) {
                $viewParams['error'] = "dateToShort";
            } else if (strlen((string) (int) $day) != 8) {
                $viewParams['error'] = "dateToShort";
            } else if ((int) $day !=0 ) {
                $viewParams['error'] = "";
            }
        } else {
            $this->view->render(
                $page,
                ['niceDate' => $niceDate,]
                );
            exit();
        }
        return (string) ($day);  
    }


    private function getDateFormat($day): string
    {
        if ((int) $day != 0 && strlen($day) == 8) {
            $niceDate =
            str_split($day, 4)[0] . "-" .
            str_split(str_split($day, 4)[1], 2)[0] . "-" .
            str_split(str_split($day, 4)[1], 2)[1];
            return $niceDate;
        } else {
            return "";
        }
    }


    public function showPrice($day = null) 
    {
        $this->priceModel->listPrice($day);
    }


    public function catchPrice() 
    {
        if(!$this->getPrice->checkIsCsvExist($this->day)) {
            $this->getPrice->downloadCSV($this->day);
        } else {
            echo "file " . $this->day . ".csv already exist";
        }

        if($this->priceModel->checkIsDataExist($this->day)) {
            $prices = $this->getPrice->getPriceFromCSV();
            $this->priceModel->savePrice($prices);
        } else {
            echo "data for data " . $this->day . " already exist in database<br /><br />";            
        }
    }


}
