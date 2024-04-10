<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Products\StoreProductRequest;
use App\Http\Requests\Products\UpdateProductRequest;
use App\Http\Resources\Products\ProductResource;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    private ProductRepository $productRepository; // Instance of ProductRepository for accessing product data

    // Constructor to inject ProductRepository instance
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository; // Assign ProductRepository instance to $productRepository property
    }

    // Retrieve a list of all active products
    public function index()
    {
        // Get the authenticated user
        $user = Auth::user();

        // Get all active products from ProductRepository
        $products = $this->productRepository->getAllActiveProducts();

        // Transform each product by calculating price based on user type
        $products->transform(function ($product) use ($user) {
            $price = $this->calculatePriceForUserType($product->price, $user->type);
            $product->setAttribute('price', $price);
            return $product;
        });

        // Return a success response with the list of products and their prices
        return ApiResponse::success([
            'products' => ProductResource::collection($products) // Format the list of products using ProductResource
        ]);
    }

    // Store a new product
    public function store(StoreProductRequest $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validated();

        // Add the authenticated user's ID to the validated data
        $validatedData['user_id'] = Auth::id();

        // Create the product using ProductRepository
        $product = $this->productRepository->createProduct($validatedData);

        // If an image is uploaded, store it and get its URL
        $imageUrl = $request->file('image') ? $product->addMediaFromRequest('image')->toMediaCollection('images')->getUrl() : null;
        $product->image_url = $imageUrl;

        // Return a success response with the created product data
        return ApiResponse::success([
            'message' => 'Product created successfully',
            'product' => ProductResource::make($product) // Format the product data using ProductResource
        ]);
    }

    // Retrieve a single product by ID
    public function show(string $id)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Get the product by ID from ProductRepository
        $product = $this->productRepository->getProductById($id);

        // Calculate the price based on user type and add it as an attribute to the product
        $product->setAttribute('price', $this->calculatePriceForUserType($product->price, $user->type));

        // Return a success response with the product data
        return ApiResponse::success([
            'product' => ProductResource::make($product) // Format the product data using ProductResource
        ]);
    }

    // Update an existing product
    public function update(UpdateProductRequest $request, string $id)
    {
        // Validate the incoming request data
        $validatedData = $request->validated();

        // Get the authenticated user
        $user = Auth::user();

        // Get the product by ID from ProductRepository
        $product = $this->productRepository->getProductById($id);

        // Check if the product exists and belongs to the authenticated user
        if (!$product || $product->user_id !== $user->id) {
            return ApiResponse::error(['message' => 'Unauthorized'], 403); // Return an error response if unauthorized
        }

        // Update the product data using ProductRepository
        $this->productRepository->updateProduct($id, $validatedData);

        // If an image is uploaded, store it and get its URL
        if ($request->hasFile('image')) {
            $imageUrl = $product->addMediaFromRequest('image')->toMediaCollection('images')->getUrl();
            $product->image_url = $imageUrl;
        }

        // Return a success response with the updated product data
        return ApiResponse::success([
            'message' => 'Product updated successfully',
            'product' => ProductResource::make($product) // Format the product data using ProductResource
        ]);
    }

    // Delete a product by ID
    public function destroy(string $id)
    {
        // Delete the product by ID using ProductRepository
        $this->productRepository->deleteProduct($id);

        // Return a success response with a message
        return ApiResponse::success([
            'message' => 'Product deleted successfully'
        ]);
    }

    // Calculate the price for a product based on user type
    private function calculatePriceForUserType($price, $userType)
    {
        switch ($userType) {
            case 'gold':
                return $price * 0.9; // Apply a 10% discount for gold users
            case 'silver':
                return $price * 0.95; // Apply a 5% discount for silver users
            default:
                return $price; // Return the original price for other user types
        }
    }
}
