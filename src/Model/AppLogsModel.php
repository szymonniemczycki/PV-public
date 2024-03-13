<?php

declare(strict_types=1);

namespace App\Model;

use Exception;
use PDO;
use Throwable;
use PDOException;
use App\ErrorLogs;
use App\Model\AppLogsModel;

class AppLogsModel extends AbstractModel 
{
    private const PAGE_SIZE = 10;
    

    public function saveLogOut(string $type, string $what, string $info): void 
    {
        try {
            $date = date("Y-m-d");
            $hour = date("H:i:s");
            $sqlQuery = "
                INSERT INTO appLogs (type, date, hour, what, info) 
                VALUES ('$type', '$date', '$hour', '$what', '$info')
                ";
            $result = $this->conn->query($sqlQuery);
        } catch (Throwable $e) {            
            $this->errorLogs->saveErrorLog(
                "error",
                $e->getFile() . " <br />line: " . $e->getLine(),
                $e->getMessage()
            );
            exit;
            }
    }


    public function getListLogs(array $params): array 
    {
        $pageNr = $params['pageNr'] ?: 1;
        $pageSize = self::PAGE_SIZE;
        $offset = ($pageNr * $pageSize) - $pageSize;
        try {
            $sqlQuery = "
                SELECT * FROM appLogs 
                WHERE log LIKE ('%$params[log]%')
                AND date LIKE ('%$params[date]%')
                AND (status LIKE ('%$params[phrase]%') OR info LIKE ('%$params[phrase]%'))
                ORDER BY created $params[sort]
                LIMIT $offset, $pageSize
                ";
            $result = $this->conn->query($sqlQuery);
            $isExistAnyData = $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            $this->errorLogs->saveErrorLog(
                "error",
                $e->getFile() . " <br />line: " . $e->getLine(),
                $e->getMessage()
            );
            exit;
        }
        return $isExistAnyData;
    }


    public function getUniqueLog(): array
    {
        try {
            $sqlQuery = "SELECT DISTINCT log FROM appLogs";
            $result = $this->conn->query($sqlQuery);
            $isExistAnyData = $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            $this->errorLogs->saveErrorLog(
                "error",
                $e->getFile() . " <br />line: " . $e->getLine(),
                $e->getMessage()
            );
            exit;
        }

        $uniqueLogs = [];
        $uniqueLogs["all"] = "";

        for ($i = 0; $i < sizeof($isExistAnyData); $i++) {
            $key = (string) $isExistAnyData[$i]['log'];
            $uniqueLogs[$key] = $isExistAnyData[$i]['log'];
        }
        return $uniqueLogs;
    }


    public function getCountPage(array $params): int 
    {
        try {
            $sqlQuery = "
                SELECT COUNT(log) FROM appLogs
                WHERE log LIKE ('%$params[log]%')
                AND date LIKE ('%$params[date]%')
                AND (status LIKE ('%$params[phrase]%') OR info LIKE ('%$params[phrase]%'))
                ORDER BY created $params[sort]
                ";
            $result = $this->conn->query($sqlQuery);
            $isExistAnyData = $result->fetch(PDO::FETCH_ASSOC);  
        } catch (Throwable $e) {
            $this->errorLogs->saveErrorLog(
                "error",
                $e->getFile() . " <br />line: " . $e->getLine(),
                $e->getMessage()
            );
            exit;
        }
            
        $pageSize = self::PAGE_SIZE;
        $counLogs = $isExistAnyData['COUNT(log)'];
        $countPage = (int) ceil($counLogs / $pageSize);

        return $countPage;
    }

    
}
