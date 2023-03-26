<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Repositories\Cart\CartRepository;
use App\Http\Controllers\Controller;


class ApiCartController extends Controller
{
    protected $cartRepo;
    public  function __construct(CartRepository $cartRepo)
    {
        $this->cartRepo = $cartRepo;
    }
    public function addToCart(Request $request){
        return $this->cartRepo->addToCart($request);
    }
    public function getCart(Request $request){
        return $this->cartRepo->getCart($request);
    }

}
