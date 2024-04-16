<?php
declare(strict_types=1);

namespace App;

//used classes
use PDO;
use Throwable;
use PDOException;
use App\Model\AppLogModel;
use App\Model\PriceModel;
use App\Model\UserModel;

//general controler - core of application
class Controller 
{
    private static array $configuration = [];
    private const DEFAULT_ACTION = 'main';

    private AppLogModel $appLogsModel;
    private PriceModel $priceModel;
    private UserModel $userModel;
    private Request $request;
    private View $view;
    private GetPrice $getPrice;
    private ErrorLogs $errorLogs;

    private int $day;
    private string $msg = "";
    private array $userPerm = [];


    //check configuration data and create new objects
    public function __construct(Request $request)
    {
        //set Class properties
        try {
            $this->priceModel = new PriceModel(self::$configuration['db']);
            $this->appLogsModel = new AppLogModel(self::$configuration['db']);
            $this->userModel = new UserModel(self::$configuration['db']);
            $this->request = $request;
            $this->view = new View();
            $this->errorLogs = new ErrorLogs();
        } catch (Throwable $e) {
            $errorLogs->saveErrorLog(
                $e->getFile() . " <br />line: " . $e->getLine(),
                $e->getMessage()
            );
        }
    }


    //method for getting configuration data
    public static function initConfiguration(array $configuration): void
    {
        self::$configuration = $configuration;
    }


    //mainly method running web-page
    public function run(): void 
    {   
        //checking params - depended of paramams, requested page will be loaded
        if (!empty($this->request->postParam('page'))) {
            $page = $this->request->postParam('page');
        } else {
            $page = $this->request->getParam('page', self::DEFAULT_ACTION);
        }
        
        //"formatedDate" - it's transformed date catched from csv (pse.pl) - there is without separators
        $formatedDate = $this->request->postParam('formatedDate');
        if (!empty($formatedDate)) {
            $day = str_replace("-", "", $formatedDate);
        } else {
            $day = null;
        }

        //get permissions for User
        $this->userPerm = $this->userModel->getUserPermissions($_SESSION['userId']);

        //restrict permisions = don't allow to open page without permission
        if(!(in_array($page, $this->userPerm))) {
            header('Location: ./');
        }

        //depended of 'page' param - page will display
        switch($page) {
            //operations for page "prices"
            case "prices" :
                if (!empty($this->msg)) {
                    $this->view->showInfo($this->msg);
                }
                $this->validDate($page, $formatedDate); 
                $viewParams = ($this->priceModel->listPrice($formatedDate));

                $this->view->render(
                    $page, [
                        'formatedDate' => $formatedDate, 
                        'listPrices' => $viewParams
                    ],
                    $this->userPerm
                );

                break;
                
            //operations for page "import"
            case "import":
                $this->validDate($page, $formatedDate);
                $this->importPrices($page, $day, $formatedDate);
                break;

            //operations for "forceDownload"
            case "forceDownload":
                $this->validDate($page, $formatedDate);
                $this->forceDownload($page, $day, $formatedDate);
                break;

            //preparing details for listing logs (from db-log)
            case "logs":
                $params = $this->getLogsParams();
                $this->view->render("logs", $params, $this->userPerm);
                break; 


            //preparing details for listing errors (from file-log)
            case "errors":
                $params = $this->getErrorsParams();

                $this->errorLogs = new ErrorLogs();
                $errors = $this->errorLogs->getErrors($params['filters']);
                
                $this->view->render("errors", $errors, $this->userPerm);
                break;  

            //show 404 page
            case "404":
                $this->view->render("404", [], $this->userPerm);
                break;

            //for other, unknow parametr of "page"
            default:
                $this->view->render("main", [], $this->userPerm);
                break;
        }
    }


    private function importPrices(string $page, string $day, string $formatedDate) : void 
    {
        $this->getPrice = new GetPrice($day);
        $dataExist = (int) $this->priceModel->checkIsDataExist($formatedDate);

        if ($dataExist) {
            $viewParams['error'] = "dataExist";
        } else {
            $importedPrices = $this->getPrice->downloadCSV($day);
            $pricesFromCsv = $this->getPrice->getPriceFromCSV($day);                        
            $pricesDataBase =  $this->priceModel->savePrice($pricesFromCsv);
                if ((!empty($importedPrices)) && (!empty($pricesFromCsv)) && (!empty($pricesDataBase)) ) {
                    $viewParams['error'] = "imported";
                    $this->appLogsModel->saveLog("manual", "imported ($formatedDate)", "correctly", 0);
                    $this->request->setPostParam('formatedDate', $formatedDate);
                    $this->request->setPostParam('page', 'prices');
                    $this->msg = "imported";
                    $this->run();
                } else {
                    $viewParams['error'] = "noImported";
                    $this->appLogsModel->saveLog("manual", "NOT imported ($formatedDate)", "problem occurred", 0);
                }
        }
    
        $this->view->render(
            $page, 
            [
                'formatedDate' => $formatedDate,
                'listPrices' => $viewParams
            ],
            $this->userPerm
        );
    }


    private function forceDownload(string $page, string $day, string $formatedDate): void
    {
        $this->getPrice = new GetPrice($day);

        $dataExist = (int) $this->priceModel->checkIsDataExist($day);
        $deletePriceFromDB = $dataExist ? (int) $this->priceModel->deletePrice($day) : 0;
        
        $csvExist = (int) $this->getPrice->checkIsCsvExist($day);
        $deleteCsvFile = $csvExist ? $this->getPrice->deleteCSV($day) : 0;

        $importedPrices = $this->getPrice->downloadCSV($day);
        $pricesFromCsv = $this->getPrice->getPriceFromCSV($day);  

        if ((!empty($importedPrices)) && (!empty($pricesFromCsv))) {
            $pricesFromCsv = (int) $this->priceModel->savePrice($pricesFromCsv);
        }

        $this->msg = "imported";
        $page = $this->request->setPostParam('page', 'prices');
        $this->appLogsModel->saveLog("manual", "updated ($formatedDate)", "correctly", 0);
        $this->run();

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
    private function validDate(string $page, string $formatedDate = null): string
    {
        $today = date("Y-m-d");
        if (!empty($formatedDate)) {
            if (
                strtotime($formatedDate) < strtotime("2018-01-01") 
                || strtotime($formatedDate) > strtotime($today)
            ) {
                $page = ($page == "forceDownload") ? "import" : $page;
                $page = ($page == "forceImport") ? "prices" : $page;
                $viewParams['error'] = "wrongData";
                $this->view->render(
                    $page,
                    [
                        'formatedDate' => $formatedDate,
                        'listPrices' => $viewParams
                    ],
                    $this->userPerm
                );
            } else {
                $day = str_replace("-", "", $formatedDate);
            }
        } else {
            $this->view->render(
                $page,
                [
                    'formatedDate' => $formatedDate,
                ],
                $this->userPerm
            );
            exit();
        }
        
        return (string) ($day);  
    }


    //create "nice" date fotrmat - with separators
    private function getDateFormat(string $day): string
    {
        if ((int) $day != 0 && strlen($day) == 8) {
            $formatedDate =
            str_split($day, 4)[0] . "-" .
            str_split(str_split($day, 4)[1], 2)[0] . "-" .
            str_split(str_split($day, 4)[1], 2)[1];
            
            return $formatedDate;
        } else {
            return "";
        }
    }


    private function getLogsParams(): array
    {
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

        return $params;
    }


    private function getErrorsParams(): array
    {
        $params['filters']['date'] = $this->request->getParam('date');
        $params['filters']['phrase'] = $this->request->getParam('phrase');
        $params['filters']['sort'] = $this->request->getParam('sort');
        $params['filters']['pageNr'] = $this->request->getParam('pageNr');

        return $params;

    }


}
