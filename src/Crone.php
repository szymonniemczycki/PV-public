<?php
declare(strict_types=1);

use App\ErrorLogs;
use App\Model\PriceModel;
use App\Model\AppLogModel;
use App\GetPrice;

//for service automatically download prices
class Crone 
{
    private static array $configuration = [];
    private const DEFAULT_ACTION = 'main';

    private PriceModel $priceModel;
    private AppLogModel $appLogsModel;
    private Request $request;
    private GetPrice $getPrice;
    private ErrorLogs $errorLogs;
    
    //set and connect with database
    public function __construct()
    {
        if (empty(self::$configuration['db'])) {
            exit('błędna konfiguracja');
        }
        $this->errorLogs = new ErrorLogs();
        $this->priceModel = new PriceModel(self::$configuration['db']);
        $this->appLogsModel = new AppLogModel(self::$configuration['db']);
    }
    
    //get configuration data
    public static function initConfiguration(array $configuration): void
    {
        self::$configuration = $configuration;
    }
    
    //method starting download prices
    public function startImportCrone(): void
    {
        $day = date("Ymd");
        $this->getPrice = new GetPrice((int) $day);
        $dataExist = (int) $this->priceModel->checkIsDataExist($day);
        $csvExist = (int) $this->getPrice->checkIsCsvExist($day);
        
        if ($dataExist) {
            $this->appLogsModel->saveLog("crone", "noImported ($day)", "dataExist", 1);
        } else {
            if ($csvExist) {
                $pricesCollection = $this->getPrice->getPriceFromCSV($day);
                $pricesImported = (int) $this->priceModel->savePrice($pricesCollection);
                $this->appLogsModel->saveLog("crone", "imported ($day)", "fromCsv", 1);
            } else {
                $importedPrices = $this->getPrice->downloadCSV((int) $day);
                $pricesFromCsv = $this->getPrice->getPriceFromCSV($day);                        
                    if ((!empty($importedPrices)) && (!empty($pricesFromCsv))) {
                        $pricesFromCsv = (int) $this->priceModel->savePrice($pricesFromCsv);
                        $this->appLogsModel->saveLog("crone", "imported ($day)", "correctly", 1);
                    }
            }       
        }
    }

    
}
