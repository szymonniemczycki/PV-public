<?php

declare(strict_types=1);

namespace App\Model;

//used classes
use PDO;
use Throwable;
use App\ErrorLogs;

class UserModel extends AbstractModel 
{
    //method for checking credentials for auth users
    public function checkCredential(string $name, string $pass): bool
    {        
        try {
            $sqlQuery = "SELECT `password` FROM `users` WHERE `name` = '" . $name . "'";
            $result = $this->conn->query($sqlQuery);
            $isExistAnyData = $result->fetch(PDO::FETCH_ASSOC);
            if (empty($isExistAnyData)) {
                return false;
            }
            $passVerifed = password_verify($pass, $isExistAnyData['password']);
            return (bool) $passVerifed;
        } catch (Throwable $e) {
            $this->errorLogs->saveErrorLog(
                $e->getFile() . " <br />line: " . $e->getLine(),
                $e->getMessage()
            );
        }
    }

    //method for get user ID
    public function getUserId(string $name): int
    {
        try {
            $sqlQuery = "SELECT `id` FROM `users` WHERE `name` = '" . $name . "'";
            $result = $this->conn->query($sqlQuery);
            $isExistAnyData = $result->fetch(PDO::FETCH_ASSOC);
            if (empty($isExistAnyData)) {
                return 0;
            }
            return $isExistAnyData['id'];
        } catch (Throwable $e) {
            $this->errorLogs->saveErrorLog(
                $e->getFile() . " <br />line: " . $e->getLine(),
                $e->getMessage()
            );
        }
    }


    //method update last login of users
    public function updateLastLogin(string $name): bool
    {
        try {
            $now = date("Y-m-d H:i:s");
            $sqlQuery = "UPDATE `users` SET `last_login` = '" . $now . "' WHERE name = '" . $name . "'";
            $result = $this->conn->query($sqlQuery);
            $userData = $result->fetch(PDO::FETCH_ASSOC);
            if (empty($userData)) {
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

    //method keeping activity of users
    public function userLoginLog(?string $name, string $status): bool
    {
        try {
            $sqlQuery = "
                INSERT INTO `login_log` (`user_form`, `status`) 
                VALUES ('" . $name . "', '" . $status . "')
                ";
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
 
}
