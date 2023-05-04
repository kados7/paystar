<?php

namespace App\Http\Controllers\Api\Home;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(){
        return Product::latest()->get();
    }

    public function show(Product $product){
        return ['product' =>$product];
    }

    public function isPurchased(Request $request , Product $product){
        $user= Auth()->user();
        $userProducts=$user->products->pluck('id')->toArray();
        $result = in_array($product->id , $userProducts);
        return $result ;

    }
}
