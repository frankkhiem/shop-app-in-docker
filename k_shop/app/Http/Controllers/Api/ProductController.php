<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    //
    public function index(Request $request)
    {   
        if ($request->key) {
            $products = DB::table('products')
                            ->join('categories', 'products.category_id', '=', 'categories.id')
                            ->select('categories.name as category_name', 'products.*')
                            ->whereNull('products.deleted_at')
                            ->where(function ($query) {
                                global $request;
                                $query->where('products.name', 'like', "%$request->key%")
                                        ->orWhere('products.short_desc', 'like', "%$request->key%")
                                        ->orWhere('products.full_desc', 'like', "%$request->key%")
                                        ->orWhere('categories.name', 'like', "%$request->key%")
                                        ->orWhere('categories.short_desc', 'like', "%$request->key%")
                                        ->orWhere('categories.full_desc', 'like', "%$request->key%");
                            })
                            ->orderByDesc('products.updated_at')
                            ->paginate(20);
            return $products;
        }
        // return Product::orderBy('updated_at', 'desc')->paginate(4);
        return Product::orderBy('id', 'desc')->paginate(20);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        return response()->json([
            $product,
            $product->status_product,
            $product->category,
            $product->info_products,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    //hàm xử lý những sản phẩm bán chạy nhất
    public function topSell()
    {
        $topProduct=DB::table('products')
                    ->selectRaw('products.id, sum(orders.quantity) as totalsell')
                    ->join('orders','products.id','=','orders.product_id')
                    ->whereNull('products.deleted_at')
                    ->groupBy('products.id')
                    ->orderByDesc('totalsell')
                    ->take(4)
                    ->get();
        $count = $topProduct->count();
        
        if( $count == 0 ) {
            return response()->json([]);
        }
        $ids = $topProduct->pluck('id')->toArray();
        $ids_ordered = implode(',', $ids);

        $infoProduct=DB::table('products')
                    ->whereIn('id',$topProduct->pluck('id'))
                    ->orderByRaw("FIELD(id, $ids_ordered)")
                    ->get();
        return $infoProduct;
    }

    //hàm xử lý top sản phẩm bán chạy theo từng danh mục
    public function topSellingByCategory($id)
    {
        $topProduct=DB::table('products')
                    ->selectRaw('products.id,sum(orders.quantity) as totalproduct')
                    ->join('orders','orders.product_id','=','products.id')
                    ->where('products.category_id',$id)
                    ->groupBy('products.id')
                    ->orderByDesc('totalproduct')
                    ->take(4)
                    ->get();

        $infoProduct=DB::table('products')
                    ->whereIn('id',$topProduct->pluck('id'))
                    ->get();            
        return $infoProduct;            
    }

    public function topPrice($id)
    {
        $topPrice=DB::table('products')
                  ->where('category_id',$id)
                  ->orderBy('price','desc')
                  ->get();
        return $topPrice;
    }

    public function productsInCategori($category_id) 
    {
        return Product::where('category_id', $category_id)
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);
    }

    public function hotProduct() 
    {
        $hotProducts = Product::where('star', '=', true)
                            ->orderBy('created_at', 'desc')
                            ->limit(4)
                            ->get();
        return $hotProducts;
    }

    public function recommendProduct($product_id) {
        $product = Product::findOrFail($product_id);
        $price = $product->price;
        $recommend_Products = Product::where('status_product_id', 1)
                                    ->whereBetween('price', [$price - $price * 0.3, $price + $price * 0.3])
                                    ->where('id', '!=', $product_id)
                                    ->orderByDesc('created_at')
                                    ->limit(4)
                                    ->get();
        return $recommend_Products;
    }
}
