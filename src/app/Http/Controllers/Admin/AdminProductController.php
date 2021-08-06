<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\ProductsImport;
use App\Jobs\ProductsImport as JobsProductsImport;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\StatusProduct;

class AdminProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        // Đưa ra danh sách sản phẩm theo từng loại danh mục
        $category = 0;
        if( $request->category ) {
            $category = $request->category;
        }        
        $keywork = $request->q;
        if( $category == 0 ) {
            if( $keywork ) {
                $products = Product::where('name', 'like', '%'.$keywork.'%')                                    
                                        ->orWhere('short_desc', 'like', '%'.$keywork.'%')
                                        ->orWhere('full_desc', 'like', '%'.$keywork.'%')
                                        ->orderBy('id', 'desc')
                                        ->paginate(10);
            }
            else {
                $products = Product::orderBy('id', 'desc')->paginate(10);
            }
        }
        else {
            if( $keywork ) {
                $products = Product::where('name', 'like', '%'.$keywork.'%')
                                        ->orWhere('category_id', $category)                                    
                                        ->orWhere('short_desc', 'like', '%'.$keywork.'%')
                                        ->orWhere('full_desc', 'like', '%'.$keywork.'%')
                                        ->orderBy('id', 'desc')
                                        ->paginate(10);
            }
            else {
                $products = Product::where('category_id', $category)
                                        ->orderBy('id', 'desc')->paginate(10);
            }
        }

        return view('admin.product.adminProduct',
                    [
                        'products' => $products,
                        'categories' => Category::all(),
                        'current_category' => $category,
                    ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('admin.product.createProduct',
                [
                    'categories' => Category::all(),
                    'status_products' => StatusProduct::all(),
                ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'filenames' => 'required',
            'filenames.*' => 'mimes:jpeg,jpg,png,gif,csv,txt,pdf|max:2048'
          ]);
        //
        if ( $request->star ) {
            $star = true;
        }
        else {
            $star = false;
        }

        // xử lý nhận ảnh sản phẩm
        if ($request->hasfile('filenames')) {
            foreach ($request->file('filenames') as $key => $file) {
                if ($key === array_key_first($request->file('filenames'))) {
                    $thumbnail = $file->getClientOriginalName();
                }
                $name = $file->getClientOriginalName();
                $file->move(public_path() . '/uploads/imagesProduct/', $name);
                $imgData[] = $name;                
            }
        }

        $product = new Product;
        $product->fill([
            'category_id' => $request->category_id, 
            'name' => $request->name, 
            'image' => json_encode($imgData), 
            'short_desc' => $request->short_desc, 
            'full_desc' => $request->full_desc, 
            'price' => $request->price, 
            'status_product_id' => $request->status_product_id,
            'star' => $star,
            'thumbnail' => $thumbnail,
        ])->save();

        return redirect()->route('listInfoProduct', $product->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        return view('admin.product.editProduct',
                [
                    'product' => Product::findOrFail($id),
                    'images' => json_decode(Product::findOrFail($id)->image),
                    'categories' => Category::all(),
                    'status_products' => StatusProduct::all(),
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
        if ( $request->star ) {
            $star = true;
        }
        else {
            $star = false;
        }
        Product::findOrFail($id)
                    ->update([
                        'category_id' => $request->category_id, 
                        'name' => $request->name,  
                        'short_desc' => $request->short_desc, 
                        'full_desc' => $request->full_desc, 
                        'price' => $request->price, 
                        'status_product_id' => $request->status_product_id,
                        'star' => $star,
                    ]);
        return redirect()->route('adminProduct.index');
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
        Product::where('id', $id)->delete();
        return redirect()->route('adminProduct.index');
    }

    // Hàm xử lý import các product thông qua file zip
    public function viewFileImport() {
        return view('admin.product.fileImportProducts');
    }

    public function fileImport(Request $request) {
        $request->validate([
            'file-import-products' => 'required|mimes:zip,rar',
        ]);
        $fileName = $request->file('file-import-products')->getClientOriginalName();
        $pathZipFile = $request->file('file-import-products')->storeAs('temp', $fileName);

        JobsProductsImport::dispatch($pathZipFile)->delay(now()->addSeconds(15));
        
        return redirect()->back()->with('message', "Tệp dữ liệu đang được xử lý");
    }

    public function downloadLogImport(Request $request) {
        $filePath = 'app/'. $request->filePath;
        return response()->download(storage_path($filePath));
    }
}
