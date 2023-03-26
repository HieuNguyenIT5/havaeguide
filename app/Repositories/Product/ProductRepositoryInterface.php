<?php

namespace App\Repositories\Product;

interface ProductRepositoryInterface 
{
    public function getProduct($where, $search);
    public function createProduct($search);
    public function updateProduct($search);
    public function deleteProduct($search);
    public function count();
    public function total();
}