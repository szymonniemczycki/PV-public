<?php

declare(strict_types=1);

namespace App\Model;

use App\Exception\AppException;
use App\Exception\NotFoundException;
use App\Exception\StorageException;
use Exception;
use PDO;
use Throwable;
use App\ErrorLogs;

class UsersModel extends AbstractModel 
{
    
    public function checkCredential(string $name, string $pass): bool
    {        
        try {
            $sqlQuery = "SELECT pass FROM users WHERE name = '$name'";
            $result = $this->conn->query($sqlQuery);
            $isExistAnyData = $result->fetch(PDO::FETCH_ASSOC);
            if (!empty($isExistAnyData)) {
                $passVerifed = password_verify($pass, $isExistAnyData['pass']);
                    return (bool) $passVerifed;
                } else {
                    return false;
                }
        } catch (Throwable $e) {
            $this->errorLogs->saveErrorLog(
                "error",
                $e->getFile() . " <br />line: " . $e->getLine(),
                $e->getMessage()
            );
        }
    }


    public function getUserDetail(string $name, string $data): string
    {
        try {
            $sqlQuery = "SELECT $data FROM users WHERE name = '$name'";
            $result = $this->conn->query($sqlQuery);
            $userDetail = $result->fetch(PDO::FETCH_ASSOC);
                if (!empty($userDetail)) {
                    return $userDetail[$data];
                } else {
                    return "---";
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


    public function updateLastLogin(string $name): bool
    {
        try {
            $now = date("Y-m-d H:i:s");
            $sqlQuery = "UPDATE users SET lastLogin = '$now' WHERE name = '$name'";
            $result = $this->conn->query($sqlQuery);
            $userData = $result->fetch(PDO::FETCH_ASSOC);
                if (!empty($userData)) {
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


    public function userLoginLog(?string $name, string $status): bool
    {
        try {
            echo $name."  ";
            echo $status;
            $sqlQuery = "
                INSERT INTO loginLog (userForm, status) 
                VALUES ('$name', '$status')
                ";
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

    
}
