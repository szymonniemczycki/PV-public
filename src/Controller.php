<?php
declare(strict_types=1);

namespace App;

use Exception;
use PDO;
use Throwable;
use PDOException;
use App\Model\AppLogModel;
use App\Model\PriceModel;

//general controler - core of application
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


    //check configuration data and create new objects
    public function __construct(Request $request)
    { 
        if (empty(self::$configuration['db'])) {
            exit('błędna konfiguracja');
        } //tutaj pytanie, czy nie zrobić try-catch i przekierowanie na 404?

        $this->priceModel = new PriceModel(self::$configuration['db']);
        $this->appLogsModel = new AppLogModel(self::$configuration['db']);
        $this->request = $request;
        $this->view = new View();
        $this->errorLogs = new ErrorLogs();
    }


    //method for getting configuration data
    public static function initConfiguration(array $configuration): void
    {
        self::$configuration = $configuration;
    }


    //mainly method running web-page
    public function run(): void {   
        //checking params - depended of paramams, requested page will be loaded
        if (!empty($this->request->postParam('page'))) {
            $page = $this->request->postParam('page');
        } else {
            $page = $this->request->getParam('page', self::DEFAULT_ACTION);
        }
        //"nice date" - it's transformed date catched from csv (pse.pl) - there is without separators
        $niceDate = $this->request->postParam('niceDate'); 
        
        //depended of ?page param - souch page will display
        switch($page) {
            //operations for page "prices"
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

                if (!empty($this->msg)) {
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

            //operations for page "import"
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
                        $pricesFromCsv = $this->getPrice->getPriceFromCSV($day);                        
                            if ((!empty($importedPrices)) && (!empty($pricesFromCsv))) {
                                $pricesFromCsv = (int) $this->priceModel->savePrice($pricesFromCsv);
                                $viewParams['error'] = "imported";
                                $this->appLogsModel->saveLog("manual", "imported ($niceDate)", "correctly", 0);
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

            //operations for "forceImport"
            case "forceImport":
                try {
                    $day = $this->validDate($page, $niceDate);
                    $this->getPrice = new GetPrice((int) $day);
                    $importedPrices = $this->getPrice->downloadCSV((int) $day);
                    $pricesFromCsv = $this->getPrice->getPriceFromCSV($day);                    
                    $pricesFromCsv = (int) $this->priceModel->savePrice($pricesFromCsv);
                
                    $this->msg = "imported";
                    $page = $this->request->setPostParam('page', 'prices');
                    $this->appLogsModel->saveLog("manual", "forcedDownload ($niceDate)", "correctly", 0);
                    $this->run();
                } catch (Throwable $e) {
                    $this->errorLogs->saveErrorLog(
                        $e->getFile() . " <br />line: " . $e->getLine(),
                        $e->getMessage()
                    );
                    exit;
                }
                break;

            //operations for "forceDownload"
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
                    $this->appLogsModel->saveLog("manual", "updated ($niceDate)", "correctly", 0);
                    $this->run();

                } catch (Throwable $e) {
                    $this->errorLogs->saveErrorLog(
                        $e->getFile() . " <br />line: " . $e->getLine(),
                        $e->getMessage()
                    );
                    exit;
                }
                break;

            //preparing details for listing logs (from db-log)
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

                //dump($params['logs']);
                
                $this->view->render("logs", $params);
                break;  

            //preparing details for listing errors (from file-log)
            case "errors":
                $params['filters']['date'] = $this->request->getParam('date');
                $params['filters']['phrase'] = $this->request->getParam('phrase');
                $params['filters']['sort'] = $this->request->getParam('sort');
                $params['filters']['pageNr'] = $this->request->getParam('pageNr');
        
                $this->errorLogs = new ErrorLogs();
                $errors = $this->errorLogs->getErrors($params['filters']);
                
                $this->view->render("errors", $errors);
                break;  

            //show 404 page
            case "404":
                $this->view->render("404", []);
                break;

            //for other, unknow parametr of "page"
            default:
                $this->view->render("main", []);
                break;
            }
    }


    //method sets page-nr as first for unknow value od pageNr param
    private function validatePageNr(?string $pageNr, int $countData): int
    {
        $pageNr = (int) $pageNr;
        if ($pageNr > $countData || $pageNr <= 0) {
            $pageNr = 1;
        }
        return $pageNr;
    }


    //validate date format - must be between 2018-01-01 and today 
    private function validDate($page, $niceDate): string
    {
        $today = date("Y-m-d");
        if (!empty($niceDate)) {
            if (
                strtotime($niceDate) < strtotime("2018-01-01") 
                || strtotime($niceDate) > strtotime($today)
                ) {
                    $page = ($page == "forceDownload") ? "import": $page;
                    $page = ($page == "forceImport") ? "prices" : $page;
                    var_dump(strtotime($page));
                $viewParams['error'] = "wrongData";
                $this->view->render(
                    $page,
                    [
                        'niceDate' => $niceDate,
                        'listPrices' => $viewParams
                    ]
                );
                exit();
            } else {
                $day = str_replace("-", "", $niceDate);
            }
        } else {
            $this->view->render(
                $page,
                [
                    'niceDate' => $niceDate,
                ]
            );
            exit();
        }
        return (string) ($day);  
    }


    //create "nice" date fotrmat - with separators
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


}
