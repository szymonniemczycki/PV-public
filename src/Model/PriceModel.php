<?php

declare(strict_types=1);

namespace App\Model;

//used classes
use PDO;
use Throwable;
use PDOException;
use App\ErrorLogs;

require_once("src/ErrorLogs.php");

class PriceModel extends AbstractModel 
{
    //method listing prices saved in database
    public function listPrice(string $day): array
    {   
        try {
            $sqlQuery = "SELECT `created`, `date`, DATE_FORMAT(`hour`, \"%H:%i\") as `hour`, `price` 
                FROM `prices` 
                WHERE date = " . $day . " 
                ORDER BY hour
                ";
            $result = $this->conn->query($sqlQuery);
            $isExistAnyData = $result->fetchAll(PDO::FETCH_ASSOC);
                if (count($isExistAnyData) == 0) {
                    $listPrices['error'] = "noDataInDB";
                } else {
                    $listPrices['prices'] = $isExistAnyData;
                }
        } catch (Throwable $e) {
            $this->errorLogs->saveErrorLog(
                $e->getFile() . " <br />line: " . $e->getLine(),
                $e->getMessage()
            );
            exit;
        }
        return $listPrices;
    }

    //method saving imported data with prices
    public function savePrice(array $pricesToSave): bool 
    {   
        try {
            foreach($pricesToSave as $data => $days) {
                foreach($days as $hour => $price) {
                    $sqlQuery = "
                        INSERT INTO `prices` (`date`, `hour`, `price`) 
                        VALUES (" . $data . ", " . $hour*10000 . ", " . $price . ")
                        ";
                    $result = $this->conn->exec($sqlQuery);
                    }
                }
        } catch (Throwable $e) {
            $this->errorLogs->saveErrorLog(
                $e->getFile() . " <br />line: " . $e->getLine(),
                $e->getMessage()
            );
            exit;
        }
        return (bool) $result; 
    }

    //method for clean data with price in requested day - need to download again new prices
    public function deletePrice(string $day): bool 
    {   
        try {
            $sqlQuery = "DELETE FROM `prices` WHERE `date` = " . $day . "";
            $result = $this->conn->exec($sqlQuery);
        } catch (Throwable $e) {
            $this->errorLogs->saveErrorLog(
                $e->getFile() . " <br />line: " . $e->getLine(),
                $e->getMessage()
            );
            exit;
        }
        return (bool) $result; 
    }
    
    //checking is data with prices exist - need this information before import
    public function checkIsDataExist(string $day): bool
    {
        try {
            $sqlQuery = "
                SELECT * FROM `prices`
                WHERE `date` = " . $day . "
                ";
            $result = $this->conn->query($sqlQuery);
            $isExistAnyData = $result->fetchAll(PDO::FETCH_ASSOC);
            if (count($isExistAnyData) == 0) {
                return false;
            }
            return true;
        } catch (Throwable $e) {
            $this->errorLogs->saveErrorLog(
                $e->getFile() . " <br />line: " . $e->getLine(),
                $e->getMessage()
            );
            exit;
        }            
    }

    
}
