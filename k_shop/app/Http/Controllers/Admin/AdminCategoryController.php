<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\CategoriesImport;
use App\Jobs\CategoriesImport as JobsCategoriesImport;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AdminCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        //
        $keywork = $request->q;
        if( $keywork ) {
            $categories = Category::where('name', 'like', '%'.$keywork.'%')
                                    ->orWhere('short_desc', 'like', '%'.$keywork.'%')
                                    ->orWhere('full_desc', 'like', '%'.$keywork.'%')
                                    ->orderBy('id', 'desc')
                                    ->paginate(10);
        }
        else {
            $categories = Category::orderBy('id', 'desc')->paginate(10);
        }
        return view('admin.category.adminCategory', 
                    [
                        'categories' => $categories,
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
        return view('admin.category.createCategory');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        Category::create( $request->all() );
        return redirect()->route('adminCategory.index');
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
        return view('admin.category.editCategory',
                    [
                        'category' => Category::findOrFail($id),
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
        Category::where('id', $id)->update([
                                        'name' => $request->name,
                                        'short_desc' => $request->short_desc,
                                        'full_desc' => $request->full_desc
                                    ]);
        return redirect()->route('adminCategory.index');
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
        $productsOnCategory = Product::where('category_id', $id)->get();
        foreach ($productsOnCategory as $product) {
            $product->category_id = null;
            $product->save();
        }
        Category::where('id', $id)->delete();
        return redirect()->route('adminCategory.index');
    }

    public function fileImport(Request $request) {
        $request->validate([
            'file-import-categories' => 'required',
            'file-import-categories.*' => 'mimes:xlsx,xls,csv'
        ]);
        $fileName = $request->file('file-import-categories')->getClientOriginalName();
        $fileNameWithoutExtension = File::name($fileName);
        if ( $fileNameWithoutExtension !== 'categories_data' ) {
            return redirect()->back()->with('error', "Tên file không hợp lệ: Yêu cầu 'categories_data' không phải '$fileNameWithoutExtension'");
        }
        $filePath = $request->file('file-import-categories')->storeAs('temp', $fileName);
        JobsCategoriesImport::dispatch( $filePath );
        
        return redirect()->back()->with('message', "Tệp dữ liệu đang được xử lý");
    }

    public function downloadLogImport(Request $request) {
        $filePath = 'app/'. $request->filePath;
        // return Storage::download($filePath);
        return response()->download(storage_path($filePath));
    }
}
