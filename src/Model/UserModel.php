<?php

declare(strict_types=1);

namespace App\Model;

//used classes
use PDO;
use Throwable;
use App\ErrorLogs;

class UserModel extends AbstractModel 
{
    public int $userId;

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

    //method for get user permissions
    public function getUserPermissions(int $id): array
    {
        $userId = (int)$id;
        try {
            $sqlQuery = "SELECT `role_id` FROM `users` WHERE `id` = '" . $userId . "'";
            $result = $this->conn->query($sqlQuery);
            $isExistAnyData = $result->fetch(PDO::FETCH_ASSOC);
            if (empty($isExistAnyData)) {
                return 0;
            }
            $userRoleId = $isExistAnyData['role_id'];

            $sqlQuery = "
                SELECT `mode` 
                FROM `permissions` 
                INNER JOIN `roles_permissions` ON `permissions`.`id`=`roles_permissions`.`perm_id`
                WHERE `roles_permissions`.`role_id` = $userRoleId
            ";

            $result = $this->conn->query($sqlQuery);
            $isExistAnyData = $result->fetchAll(PDO::FETCH_ASSOC);
            $userPermissions = [];

            for ($i=0; $i<count($isExistAnyData); $i++) {
                array_push($userPermissions, $isExistAnyData[$i]['mode']);  
            }
   
            return $userPermissions;
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
            $sqlQuery = "
                UPDATE `users` 
                SET `last_login` = '" . $now . "' 
                WHERE name = '" . $name . "'
            ";
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
        }
        
        return (bool) $result; 
    }
 
}
