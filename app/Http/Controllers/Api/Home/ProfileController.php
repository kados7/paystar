<?php

namespace App\Http\Controllers\Api\Home;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function getUserCart(Request $request){
        if($request->user()->carts->first()){
            return [
                'code' => 200,
                'carts'=> $request->user()->carts,
            ];
        }
        else{
            return [
                'code' => 401,
                'message'=> 'تا کنون کارتی ثبت نکرده اید',
            ];
        }

    }

    public function addUserCart(Request $request){

        // return 'sss';
        if($request->user()->id){

            $validator= Validator($request->all(),[
                'name'=>'required',
                'number'=>'required|min:16|max:16',
                'bank'=>'required',
            ]);
            if ($validator->fails()){
                return [
                    'code' => 401,
                    'message' => $validator->getMessageBag()->first(),
                ];
            }

            $cart=Cart::create([
                'user_id'=> $request->user()->id,
                'name'=> $request->name,
                'number'=> $request->number,
                'bank'=> $request->bank,
            ]);
            return [
                'code' => 200,
                'cart'=> $cart,
                'carts'=> $request->user()->carts,
            ];
        }
    }
    public function getUserProducts(Request $request){
        if($request->user()->products->first()){
            return [
                'code' => 200,
                'products'=> $request->user()->products,
            ];
        }
        else{
            return [
                'code' => 401,
                'message'=> 'تا کنون محصولی خریداری نکرده اید',
            ];
        }
    }
}
