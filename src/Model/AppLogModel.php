<?php

declare(strict_types=1);

namespace App\Model;

//used classes
use PDO;
use Throwable;
use PDOException;
use App\ErrorLogs;
use App\Model\AppLogModel;

class AppLogModel extends AbstractModel 
{
    private const PAGE_SIZE = 10;

    //method for isting occured app event - along with filters
    public function getListLogs(array $params): array 
    {
        $pageNr = $params['pageNr'] ?: 1;
        $pageSize = self::PAGE_SIZE;
        $offset = ($pageNr * $pageSize) - $pageSize;
        try {
            $sqlQuery = "
                SELECT `app_logs`.`log`, `app_logs`.`created`, `app_logs`.`status`, `app_logs`.`info`, `users`.`name`  
                FROM `app_logs` 
                INNER JOIN `users` ON `app_logs`.`user_id`=`users`.`id`
                WHERE `app_logs`.`log` LIKE '%" . $params['log'] . "%'
                AND `app_logs`.`created` LIKE '%" . $params['date'] . "%'
                AND (
                    `app_logs`.`status` LIKE '%" . $params['phrase'] . "%' OR 
                    `app_logs`.`info` LIKE '%" . $params['phrase'] .  "%' OR 
                    `users`.`name` LIKE '%" . $params['phrase'] .  "%'
                )
                ORDER BY `app_logs`.`created` " . $params['sort'] . "
                LIMIT " . $offset . ", " . $pageSize . "
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
            $sqlQuery = "SELECT DISTINCT `log` FROM `app_logs`";
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

        for ($i = 0; $i < count($isExistAnyData); $i++) {
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
                SELECT COUNT(log) 
                FROM `app_logs` 
                INNER JOIN `users` ON `app_logs`.`user_id`=`users`.`id`
                WHERE `app_logs`.`log` LIKE '%" . $params['log'] . "%'
                AND `app_logs`.`created` LIKE '%" . $params['date'] . "%'
                AND (
                    `app_logs`.`status` LIKE '%" . $params['phrase'] . "%' OR 
                    `app_logs`.`info` LIKE '%" . $params['phrase'] .  "%' OR 
                    `users`.`name` LIKE '%" . $params['phrase'] .  "%'
                )
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
