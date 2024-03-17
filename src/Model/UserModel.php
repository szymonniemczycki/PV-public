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

class UserModel extends AbstractModel 
{
    
    public function checkCredential(string $name, string $pass): bool
    {        
        try {
            $sqlQuery = "SELECT password FROM users WHERE name = '$name'";
            $result = $this->conn->query($sqlQuery);
            $isExistAnyData = $result->fetch(PDO::FETCH_ASSOC);
            if (!empty($isExistAnyData)) {
                $passVerifed = password_verify($pass, $isExistAnyData['password']);
                    return (bool) $passVerifed;
                } else {
                    return false;
                    //throw new Throwable('Wrong credentials');
                }
        } catch (Throwable $e) {
            $this->errorLogs->saveErrorLog(
                $e->getFile() . " <br />line: " . $e->getLine(),
                $e->getMessage()
            );
            //header('Location: ./login.php?info=4');
        }
    }


    public function updateLastLogin(string $name): bool
    {
        try {
            $now = date("Y-m-d H:i:s");
            $sqlQuery = "UPDATE users SET last_login = '$now' WHERE name = '$name'";
            $result = $this->conn->query($sqlQuery);
            $userData = $result->fetch(PDO::FETCH_ASSOC);
                if (!empty($userData)) {
                    return true;
                } else {
                    return false;
                }
        } catch (Throwable $e) {
            $this->errorLogs->saveErrorLog(
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
                INSERT INTO login_log (user_form, status) 
                VALUES ('$name', '$status')
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
