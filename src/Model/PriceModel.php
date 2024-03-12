<?php

declare(strict_types=1);

namespace App\Model;

use Exception;
use PDO;
use Throwable;
use PDOException;
use App\ErrorLogs;

require_once("src/ErrorLogs.php");

class PriceModel extends AbstractModel 
{

    public function listPrice(string $day): array
    {   
        try {
            $sqlQuery = "SELECT * FROM prices WHERE day = $day ORDER BY hour ASC";
            $result = $this->conn->query($sqlQuery);
            $isExistAnyData = $result->fetchAll(PDO::FETCH_ASSOC);
                if (count($isExistAnyData) == 0) {
                    $listPrices['error'] = "noDataInDB";
                } else {
                    $listPrices['prices'] = $isExistAnyData;
                }
        } catch (Throwable $e) {
            $this->errorLogs->saveErrorLog(
                "error",
                $e->getFile() . " <br />line: " . $e->getLine(),
                $e->getMessage()
            );
            exit;
        }
        return $listPrices;
    }


    public function savePrice(array $pricesToSave): bool 
    {   
        try {
            foreach($pricesToSave as $data => $days) {
                foreach($days as $day => $price) {
                    $sqlQuery = "
                        INSERT INTO prices (day, hour, price) 
                        VALUES ($data, $day, $price)
                        ";
                    $result = $this->conn->exec($sqlQuery);
                    }
                }
        } catch (Throwable $e) {
            $this->errorLogs->saveErrorLog(
                "error",
                $e->getFile() . " <br />line: " . $e->getLine(),
                $e->getMessage()
            );
            exit;
        }
        return (bool) $result; 
    }


    public function deletePrice(string $day): bool 
    {   
        try {
            $sqlQuery = "DELETE FROM prices WHERE day = $day";
            $result = $this->conn->exec($sqlQuery);
        } catch (Throwable $e) {
            $this->errorLogs->saveErrorLog(
                "error",
                $e->getFile() . " <br />line: " . $e->getLine(),
                $e->getMessage()
            );
            exit;
        }
        return (bool) $result; 
    }
    

    public function checkIsDataExist(string $day): bool
    {
        try {
            $sqlQuery = "
                SELECT * FROM prices
                WHERE day = $day
                ";
            $result = $this->conn->query($sqlQuery);
            $isExistAnyData = $result->fetchAll(PDO::FETCH_ASSOC);
            if (count($isExistAnyData) != 0) {
                return true;
            } else {
                return false;
            }
        } catch (Throwable $e) {
            $this->errorLogs->saveErrorLog(
                "error",
                $e->getFile() . " <br />line: " . $e->getLine(),
                $e->getMessage()
            );
            exit;
        }            
    }

    
}
