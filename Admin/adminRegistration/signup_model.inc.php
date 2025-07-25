<?php

    declare (strict_types= 1); // type declaration set true

    function get_username_email(object $pdo, string $email){

        $query = "SELECT email FROM admins WHERE email = :email;";
        $stmt = $pdo->prepare($query);
        $stmt -> bindParam(":email", $email);
        $stmt -> execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $results;
    }

    function set_customer(object $pdo, string $fName, string $lName, string $email, int $phone, string $addr, string $dep, string $pwd){

        $query = "INSERT INTO admins (firstName, lastName, email, phone, address, dep, pwrd)
                  VALUES (:firstName, :lastName, :email, :phone, :address, :dep, :pwrd);";
            
            $stmt = $pdo->prepare($query);

            // security measure : time it take to validate a password.
            $options = [
                
                'cost' => 12
            ];
            // hash the input value for password.
            $hashedPwd = password_hash($pwd, PASSWORD_BCRYPT, $options);

            $stmt->bindParam(":firstName", $fName);
            $stmt->bindParam(":lastName", $lName);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":phone", $phone);
            $stmt->bindParam(":address", $addr);
            $stmt->bindParam(":dep", $dep);
            $stmt->bindParam(":pwrd", $hashedPwd);
            
            $stmt->execute();
    }