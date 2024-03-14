<?php

declare(strict_types=1);

namespace App\Model;

use App\Exception\AppException;
use App\Exception\NotFoundException;
use App\Exception\StorageException;
use App\Exception;
use Throwable;
use PDO;
use PDOException;
use App\ErrorLogs;

class AbstractModel 
{
    protected ErrorLogs $errorLogs;
    protected PDO $conn;
    protected $configuration = [];


    public function __construct($config) 
    {
        $this->errorLogs = new ErrorLogs();
        try {
            $this->validateConfig($config);
            $this->createConnection($config); 
        } catch (PDOException $e) {
            $this->errorLogs->saveErrorLog(
                $e->getFile() . " <br />line: " . $e->getLine(), 
                $e->getMessage()
            );  
            exit();
        }
    }


    private function createConnection(array $config): void
    {  
        try {
            $dsn = "mysql:dbname=" . $config['database'] . ";host=" . $config['host'];
            $this->conn = new PDO(
                $dsn,
                $config['user'],
                $config['password'],
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
            );
        } catch (Throwable $e) {
            $this->errorLogs->saveErrorLog(
                $e->getFile() . " <br />line: " . $e->getLine(), 
                $e->getMessage()
            );
            exit;
        }
    }


    private function validateConfig (array $config): void
    {
        try {
            if (
                empty($config['database'])
                || empty($config['host'])
                || empty($config['user'])
                || empty($config['password'])
                );
        } catch (Throwable $e) {
            dump($e);
            $this->errorLogs->saveErrorLog(
                $e->getFile() . " <br />line: " . $e->getLine(), 
                $e->getMessage()
            );
            exit;
        }
    }


    public function saveLog(string $log, string $status, string $info, int $show): bool 
    {
        try {
            $date = date("Y-m-d");
            $hour = date("H:i:s");
            $sqlQuery = "
                INSERT INTO appLogs (log, date, hour, status, info) 
                VALUES ('$log', '$date', '$hour', '$status', '$info')
                ";
            $result = $this->conn->exec($sqlQuery);
            if ($result) {
                if ($show) {
                    dump($status . " - " . $info);
                }
                return true; 
            } else {
                return "";
            }
        } catch (Throwable $e) {               
            $this->errorLogs->saveErrorLog(
                $e->getFile() . " <br />line: " . $e->getLine(),
                $e->getMessage()
            );
            exit;
            }
    }

    
}
