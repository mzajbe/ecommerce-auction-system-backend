<?php

namespace App\Http\Controllers;
use App\Models\Product; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Get all products  
    public function index()  
    {  
        return Product::all(); // Returns all products in JSON format  
    }  

    // Store a new product  
    public function store(Request $request)  
    {  
        
        try {  
            $validatedData = $request->validate([  
                'name' => 'required|string|max:255',  
                'description' => 'nullable|string',  
                'price' => 'required|numeric',  
                'image_url' => 'nullable|string',  
                'stock' => 'required|integer',  
            ]);  
    
            $product = Product::create($validatedData);  
            return response()->json($product, 201);  
        } catch (\Exception $e) {  
            return response()->json(['error' => $e->getMessage()], 500);  
        }  
    }  
}
