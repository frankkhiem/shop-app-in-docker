<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\InfoProductsImport;
use Illuminate\Http\Request;
use App\Models\InfoProduct;
use App\Models\Product;
use Maatwebsite\Excel\Facades\Excel;

class AdminInfoProductController extends Controller
{
    //
    public function listInfo(Request $request, $product_id) {
        $listInfo = InfoProduct::where('product_id', $product_id)
                                    ->get();
        $product = Product::where('id', $product_id)
                                ->get()->first();
        return view('admin.infoProduct.adminInfoProduct',
                    [
                        'listInfo' => $listInfo,
                        'product' => $product
                    ]);
    }

    //
    public function createInfo(Request $request, $product_id) {
        $infoProduct = new InfoProduct();
        $infoProduct->fill([
            'product_id' => $product_id,
            'attribute' => $request->attribute,
            'information' => $request->information,
        ])->save();
        return redirect()->route('listInfoProduct', $product_id);
    }

    //
    public function deleteInfo($product_id, $info_id){
        $info = InfoProduct::where('id', $info_id)
                                ->delete();        
        return redirect()->route('listInfoProduct', $product_id);
    }

    //
    public function editInfo($product_id, $info_id) {
        return view('admin.infoProduct.editInfoProduct',
                    [
                        'product' => Product::where('id', $product_id)->get()->first(),
                        'info' => InfoProduct::where('id', $info_id)->get()->first()
                    ]);
    }
    
    //
    public function updateInfo(Request $request, $product_id, $info_id) {
        InfoProduct::where('id', $info_id)
                        ->update([
                            'attribute' => $request->attribute,
                            'information' => $request->imformation
                        ]);
        return redirect()->route('listInfoProduct', $product_id);
    }

    public function fileImport(Request $request) {
        $request->validate([
            'file-import-info-products' => 'required',
            'file-import-info-products.*' => 'mimes:xlsx,xls,csv'
        ]);
        Excel::import(new InfoProductsImport, request()->file('file-import-info-products'));
        return redirect()->route('adminProduct.index');
    }
}
