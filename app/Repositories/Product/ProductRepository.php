<?php

namespace App\Repositories\Product;


use App\Repositories\Product\ProductRepositoryInterface;
use App\Models\Product;
use App\Models\CategoryProduct;
use App\Models\Brand;
use Illuminate\Support\Facades\DB;

class ProductRepository implements ProductRepositoryInterface
{
    private Product $product;
    private CategoryProduct $cat;
    private Brand $brand;
    protected $data = [];
    private $price;
    public function __construct(Product $product, Brand $brand)
    {
        $this->product = $product;
        $this->cat = new CategoryProduct();
        $this->brand = $brand;
        $this->price = DB::raw("(CASE WHEN products.new_price = 0 THEN products.price ELSE products.new_price END) as price");
    }

    public function count()
    {
        $count = [];
        $count['all_pro'] = $this->product->withTrashed()->count();
        $count['pro_active'] = $this->product->all()->count();
        $count['pro_remove'] = $this->product->onlyTrashed()->count();
        return $count;
    }

    public function find($id)
    {
        $result = $this->product->find($id);

        return $result;
    }
    public function findByCode($code)
    {
        $result = $this->product->where('product_code', $code)->first();

        return $result;
    }
    public function getProduct($where = "", $search)
    {
        $status = DB::raw("(CASE WHEN products.quantity = 0 THEN 'Hết hàng' ELSE 'Còn hàng' END) as status");
        $products = DB::table('products')
            ->join('category_products', 'category_products.id', 'products.cat_id')
            ->select(
                'products.id',
                'products.name',
                'products.image',
                $this->price,
                'category_products.name as cat_name',
                $status,
                'products.created_at',
                'products.deleted_at'
            );
        if ($where == "active") {
            $products->where('products.deleted_at', null);
        } elseif ($where == "remove") {
            $products->where('products.deleted_at', '!=', null);
        }
        // dd($products);
        return $products->where('products.name', 'LIKE', "%{$search}%")->paginate(15);
    }
    public function getProductByCatId($id)
    {
        $cat = DB::table('category_products')
            ->select('id', 'name')
            ->where('id_parent', $id)
            ->get();
        if (count($cat) > 0) {
            foreach ($cat as $item) {
                $this->data = array_merge($this->data, $this->getProductByCatId($item->id));
            }
        } else {
            $this->data = DB::table('products')
                ->select(
                    'id',
                    'product_code',
                    'name',
                    'price',
                    'new_price',
                    'image'
                )
                ->where('cat_id', $id)
                ->where('deleted_at', null)
                ->inRandomOrder()
                ->limit(12)
                ->get()->toArray();
        }
        return $this->data;
    }
    public function getProductByCategory()
    {
        $cat = DB::table('category_products')
            ->select('id', 'name')
            ->where('id_parent', 0)
            ->where('id', '!=', 1)
            ->get();
        $data = [];
        foreach ($cat as $item) {
            $data[$item->id] = [
                "cat_name" => $item->name,
                "product" => $this->getProductByCatId($item->id)
            ];
            $this->data = [];
        }
        return $data;
    }

    public function getSellingProducts()
    {
        return DB::table('products')
            ->select(
                'id',
                'product_code',
                'name',
                'price',
                'new_price',
                'image'
            )
            ->where('quantity_sold', '>=', 100)
            ->limit(5)
            ->get();
    }

    public function createProduct($product)
    {
        return $this->product->create($product);
    }
    public function updateProduct($id, $attributes = [])
    {
        $result = $this->find($id);
        if ($result) {
            $result->update($attributes);
            return $result;
        }
        return false;
    }

    public function removeProduct($id)
    {
        $result = $this->find($id);
        if ($result) {
            $result->delete();

            return true;
        }

        return false;
    }
    public function restoreProduct($id)
    {
        $result = $this->product->withTrashed()->find($id);;
        if ($result) {
            $result->restore();

            return true;
        }

        return false;
    }


    public function deleteProduct($id)
    {
        $result = $this->product->withTrashed()->find($id);;
        if ($result) {
            $result->forceDelete();
            return true;
        }

        return false;
    }
    public function total()
    {
        return $this->product->total();
    }
    public function getCatProductName()
    {
        return $this->cat->getParent();
    }
    public function getBrandName()
    {
        return DB::table('brands')->select('id', 'name')->get();
    }
    public function getnoibat()
    {
        return DB::table('products')->limit(5)->get();
    }
    public function getlist()
    {
        $list = [];
        $cats = DB::table('category_products')->select('id', 'name')->get();
        foreach ($cats as $item) {
            $list[$item->name] = DB::table('products')->where('cat_id', $item->id)->limit(5)->get();
        }
        return $list;
    }

    //API
    public function getProductFilter($filters)
    {
        $products = DB::table('products')
            ->select('id','product_code', 'name', 'image', 'price', 'new_price', 'cat_id');
        if (!empty($filters['nganh_hang'])) {
            $products->where('cat_id', $filters['nganh_hang']);
        };
        if (!empty($filters['thuong_hieu'])) {
            $products->where('brand_id', $filters['thuong_hieu']);
        };
        if (!empty($filters['gia'])) {
            switch ($filters['gia']) {
                case "1":
                    $products->whereRaw('IF(new_price = 0, price, new_price)< 1000000');
                    break;
                case "1-3":
                    $products->whereRaw('IF(new_price = 0, price, new_price) BETWEEN 1000000 AND 3000000');
                    break;
                case "3-5":
                    $products->whereRaw('IF(new_price = 0, price, new_price) BETWEEN 3000000 AND 5000000');
                    break;
                case "5-10":
                    $products->whereRaw('IF(new_price = 0, price, new_price) BETWEEN 5000000 AND 10000000');
                    break;
                case "10":
                    $products->whereRaw('IF(new_price = 0, price, new_price) > 10000000');
                    break;
                default:
                    break;
            }
        };
        if (!empty($filters['sap_xep'])) {
            switch ($filters['sap_xep']) {
                case "1":
                    $products->orderByDesc('created_at');
                    break;
                case "2":
                    $products->orderBy('created_at');
                    break;
                case "3":
                    $products->orderBy('price');
                    break;
                case "4":
                    $products->orderByDesc('price');
                    break;
                default:
                    break;
            }
        }
        return $products->paginate(16);
    }
}
