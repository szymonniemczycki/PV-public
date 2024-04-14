<?php

declare(strict_types=1);

namespace App\Model;

//used classes
use Throwable;
use PDO;
use PDOException;
use App\ErrorLogs;

/**
 * @method __construct($config)
 * @method bool validateConfig(array $config)
 * @method bool createConnection(array $config)
 * @method bool saveLog(string $log, string $status, string $info, int $show)
 * 
 */
class AbstractModel 
{
    protected ErrorLogs $errorLogs;
    protected PDO $conn;
    public bool $status;


    public function __construct(array $config)
    {
        $this->errorLogs = new ErrorLogs();
        try {
            //validate config file with access to DB
            $this->status = $validateConfig = $this->validateConfig($config);
            if ($validateConfig) {
                //if config file is correctly and not missing data, then create connection
                $this->status = $this->createConnection($config);
            }
        } catch (PDOException $e) {
            $this->errorLogs->saveErrorLog(
                $e->getFile() . " <br />line: " . $e->getLine(), 
                $e->getMessage()
            );
        }
    }

    //method for validate confgi file - results saved in public property
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
            }

            return true;
        } catch (Throwable $e) {
            $this->errorLogs->saveErrorLog(
                $e->getFile() . " <br />line: " . $e->getLine(), 
                $e->getMessage()
            );
        }
    }

    //method for creating connection
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

    //method for saving logs - all issues occured will be saved in error file log
    public function saveLog(string $log, string $status, string $info, int $show): bool 
    {
        $usrId = (!empty($_SESSION['userId'])) ? $_SESSION['userId'] : 1;
        try {
            $sqlQuery = "
                INSERT INTO `app_logs` (`log`, `status`, `info`, `user_id`) 
                VALUES ('" . $log . "', '" . $status . "', '" . $info . "', '" . $usrId . "')
            ";
            $result = $this->conn->exec($sqlQuery);
            if (!$result) {
                return false; 
            }
            if ($show) {
                dump($status . " - " . $info);
            }
            
            return true;
        } catch (Throwable $e) {               
            $this->errorLogs->saveErrorLog(
                $e->getFile() . " <br />line: " . $e->getLine(),
                $e->getMessage()
            );
        }
    }


}
