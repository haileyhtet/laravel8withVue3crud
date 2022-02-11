<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // if(request('search')){
        //    return Product::where('name','like','%' . $request->search .'%')
        //    ->orderBy('id','desc')->paginate(5);
        // }
        // else{
        //     return Product::orderBy('id','desc')->paginate(5);

        // }
        // $products = Product::query();
        // if(request('search')){
        //     return $products->where('name','like','%' . $request->search .'%')
        //     ->orderBy('id','desc')->paginate(5);
        //  
        //  else{
        //      return $products->orderBy('id','desc')->paginate(5);
 
        //  }
         return Product::when(request('search'),function($query){
        $query->where('name','like','%' . request('search') .'%');
         })->orderBy('id','desc')->paginate(5);

    }
    public function store(ProductStoreRequest $request)
    {
        $product = Product::create($request->only('name','price'));
        return $product;
    }
    public function show(Product $product)
    {
        
        return $product;
    }
    public function update(ProductUpdateRequest $request, Product $product)
    {
 
        $product->update($request->only('name','price'));
        return $product;
    }

    
    public function destroy(Product $product)
    {
       
        $product->delete();
        return $product;
    }
}
