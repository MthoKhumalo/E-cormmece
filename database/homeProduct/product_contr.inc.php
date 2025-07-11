<?php

    require_once('product_model.inc.php');

    class ProductController {

        private $model;

        public function __construct($pdo) {

            $this->model = new ProductModel($pdo);
        }

        public function getProductsByPriceRange($range) {

            if ($range === 'lessThan5000') {

                return $this->model->getProducts('lessThan5000');

            } elseif ($range === 'greaterThan10000') {

                return $this->model->getProducts('greaterThan10000');

            } else {

                return $this->model->getProducts();
            }
        }
        
    }