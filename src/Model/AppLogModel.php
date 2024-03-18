<?php

declare(strict_types=1);

namespace App\Model;

use Exception;
use PDO;
use Throwable;
use PDOException;
use App\ErrorLogs;
use App\Model\AppLogModel;

class AppLogModel extends AbstractModel 
{
    private const PAGE_SIZE = 10;
    
    //method for saving requested event in application
    public function saveLogOut(string $type, string $what, string $info): void 
    {
        try {
            $sqlQuery = "
                INSERT INTO app_logs (type, what, info) 
                VALUES ('$type', '$what', '$info')
                ";
            $result = $this->conn->query($sqlQuery);
        } catch (Throwable $e) {            
            $this->errorLogs->saveErrorLog(
                $e->getFile() . " <br />line: " . $e->getLine(),
                $e->getMessage()
            );
            exit;
        }
    }

    //method for isting occured app event - along with filters
    public function getListLogs(array $params): array 
    {
        $pageNr = $params['pageNr'] ?: 1;
        $pageSize = self::PAGE_SIZE;
        $offset = ($pageNr * $pageSize) - $pageSize;
        try {
            $sqlQuery = "
                SELECT * FROM app_logs 
                WHERE log LIKE ('%$params[log]%')
                AND created LIKE ('%$params[date]%')
                AND (status LIKE ('%$params[phrase]%') OR info LIKE ('%$params[phrase]%'))
                ORDER BY created $params[sort]
                LIMIT $offset, $pageSize
                ";
            $result = $this->conn->query($sqlQuery);
            $isExistAnyData = $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            $this->errorLogs->saveErrorLog(
                $e->getFile() . " <br />line: " . $e->getLine(),
                $e->getMessage()
            );
            exit;
        }
        return $isExistAnyData;
    }

    //method for reach how many log is saved on database - needed for get count of pagination
    public function getUniqueLog(): array
    {
        try {
            $sqlQuery = "SELECT DISTINCT log FROM app_logs";
            $result = $this->conn->query($sqlQuery);
            $isExistAnyData = $result->fetchAll(PDO::FETCH_ASSOC);
        } catch (Throwable $e) {
            $this->errorLogs->saveErrorLog(
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

    //method calculated how many pages of logs exist on database
    public function getCountPage(array $params): int 
    {
        try {
            $sqlQuery = "
                SELECT COUNT(log) FROM app_logs
                WHERE log LIKE ('%$params[log]%')
                AND created LIKE ('%$params[date]%')
                AND (status LIKE ('%$params[phrase]%') OR info LIKE ('%$params[phrase]%'))
                ORDER BY created $params[sort]
                ";
            $result = $this->conn->query($sqlQuery);
            $isExistAnyData = $result->fetch(PDO::FETCH_ASSOC);  
        } catch (Throwable $e) {
            $this->errorLogs->saveErrorLog(
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
