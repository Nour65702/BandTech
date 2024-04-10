<?php

namespace App\Repositories;

use App\Models\Product;

class EloquentProductRepository implements ProductRepository
{
    // Retrieve all active products
    public function getAllActiveProducts()
    {
        return Product::where('is_active', true)->get();
    }

    // Retrieve a product by its ID or throw an exception if not found
    public function getProductById($id)
    {
        return Product::findOrFail($id);
    }

    // Create a new product with the provided data
    public function createProduct(array $data)
    {
        return Product::create($data);
    }

    // Update an existing product with the provided data
    public function updateProduct($id, array $data)
    {
        // Retrieve the product by its ID
        $product = $this->getProductById($id);

        // Update the product's data and return the result
        return $product->update($data);
    }

    // Delete a product by its ID
    public function deleteProduct($id)
    {
        // Retrieve the product by its ID
        $product = $this->getProductById($id);

        // Delete the product and return the result
        return $product->delete();
    }
}
