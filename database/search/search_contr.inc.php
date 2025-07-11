<?php

    declare (strict_types= 1);

    function product_not_found(object $pdo, string $productSearch){

        if(!get_products($pdo, $productSearch)){

            return true;

        }else{
            
            return false;
        }
    }