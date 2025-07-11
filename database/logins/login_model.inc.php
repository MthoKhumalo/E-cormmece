<?php

    declare (strict_types= 1);

    function get_user(object $pdo, string $email, string $table){

        $query = "SELECT * FROM $table WHERE email = :email;";
        $stmt = $pdo->prepare($query);
        $stmt -> bindParam(":email", $email);
        $stmt -> execute();

        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        return $results;
    } 