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
    public $status;


    public function __construct($config)
    {
        $this->errorLogs = new ErrorLogs();
        try {
            $this->status = $validateConfig = $this->validateConfig($config);
            if ($validateConfig) {
                $this->status = $createConnection = $this->createConnection($config);
            }
        } catch (PDOException $e) {
            $this->errorLogs->saveErrorLog(
                $e->getFile() . " <br />line: " . $e->getLine(), 
                $e->getMessage()
            );
        }
    }


    private function validateConfig (array $config): bool
    {
        try {
            if (
                empty($config['database'])
                || empty($config['host'])
                || empty($config['user'])
                || empty($config['password'])
            ) {
                return false;
                throw new Throwable('Division by zero.');
            } else {
                return true;
            }
        } catch (Throwable $e) {
            $this->errorLogs->saveErrorLog(
                $e->getFile() . " <br />line: " . $e->getLine(), 
                $e->getMessage()
            );
        }
    }


    private function createConnection(array $config): bool
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
            return false;
        }
        return true;
    }


    public function saveLog(string $log, string $status, string $info, int $show): bool 
    {
        try {
            $sqlQuery = "
                INSERT INTO app_logs (log, status, info) 
                VALUES ('$log', '$status', '$info')
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
        }
    }

    
}
