<?php

namespace App\Repositories;

use App\Models\Product;

interface ProductRepository
{
    public function getAllActiveProducts();

    public function getProductById($id);

    public function createProduct(array $data);

    public function updateProduct($id, array $data);

    public function deleteProduct($id);
}
