<?php
declare(strict_types=1);

//used classes
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
        try {
            $this->errorLogs = new ErrorLogs();
            $this->priceModel = new PriceModel(self::$configuration['db']);
            $this->appLogsModel = new AppLogModel(self::$configuration['db']);
        } catch (Throwable $e) {
            $errorLogs->saveErrorLogNoDirect(
                $e->getFile() . " <br />line: " . $e->getLine(),
                $e->getMessage()
            );
            dump($e->getMessage());
        }
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
        $formatedDay = date("Y-m-d");
        $this->getPrice = new GetPrice($day);
        $dataExist = (int) $this->priceModel->checkIsDataExist($day);
        $csvExist = (int) $this->getPrice->checkIsCsvExist($day);
        
        if ($dataExist) {
            $this->appLogsModel->saveLog("crone", "noImported ($formatedDay)", "dataExist", 1);
        } else {
            if ($csvExist) {
                $pricesCollection = $this->getPrice->getPriceFromCSV($day);
                $pricesImported = (int) $this->priceModel->savePrice($pricesCollection);
                $this->appLogsModel->saveLog("crone", "imported ($formatedDay)", "fromCsv", 1);
            } else {
                $importedPrices = $this->getPrice->downloadCSV($day);
                $pricesFromCsv = $this->getPrice->getPriceFromCSV($day);                        
                    if ((!empty($importedPrices)) && (!empty($pricesFromCsv))) {
                        $pricesFromCsv = (int) $this->priceModel->savePrice($pricesFromCsv);
                        $this->appLogsModel->saveLog("crone", "imported ($formatedDay)", "correctly", 1);
                    }
            }       
        }
    }

    
}
