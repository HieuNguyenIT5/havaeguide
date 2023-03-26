<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Repositories\Slider\SliderRepository;
use App\Repositories\CategoryProduct\CategoryRepository;
use App\Repositories\Brand\BrandRepositoryInterface;
use Exception;

class ApiController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $productRepo;
    private $sliderRepo;
    private $catRepo;
    private $brandRepo;
    public function __construct(ProductRepositoryInterface $productRepo, SliderRepository $sliderRepo, CategoryRepository $catRepo, BrandRepositoryInterface $brandRepo)
    {
        $this->productRepo = $productRepo;
        $this->sliderRepo = $sliderRepo;
        $this->catRepo = $catRepo;
        $this->brandRepo = $brandRepo;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function index(){
        try{
            $sliders = $this->sliderRepo->getSliderShow();
            $sellingProducts = $this->productRepo->getSellingProducts();
            $products = $this->productRepo->getProductByCategory();
            return response()->json(
                [
                    "sliders" => $sliders,
                    "selling_products" => $sellingProducts,
                    "products" => $products,
                    "status" => 200,
                    "message" => "OK"
                ]
            );
        }catch(Exception $e){
            return response()->json(
                [
                    "status" => 500,
                    "message" => $e->getMessage()
                ],500
            );
        }
    }

    public function getCatMenu(){
        try{
            $data = $this->catRepo->getCatMenu();
            return response()->json(
                [
                    "CatMenu" => $data
                ]
            );
        }catch(Exception $e){
            if($e->getCode() == '2002'){
                return response()->json(
                    [
                        "status" => 500,
                        "message" => "Không kết nối được với server!"
                    ],500
                );
            }elseif($e->getCode() == '42S02')
            return response()->json(
                [
                    "status" => $e->getCode(),
                    "message" => $e->getMessage()
                ],500
            );
        }
    }

    public function productDetail($code){
        $product = $this->productRepo->findByCode($code);
        return response()->json([
            "product"=> $product,
        ]);
    }

    public function sameCategory($cat_id){
        $products = $this->productRepo->getProductByCatId($cat_id);
        return response()->json([
            'products' => $products
        ]);
    }
    public function listProduct(Request $request){
        $products = $this->productRepo->getProductFilter($request->all());
        $categorys = $this->catRepo->getCatName();
        $brands = $this->brandRepo->getBrandName();
        return response()->json([
            'categorys' => $categorys,
            'brands' => $brands,
            'products' => $products
        ]);
    }
}
