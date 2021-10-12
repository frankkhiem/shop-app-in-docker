<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class FileUploadController extends Controller
{
    //
    public function createImagesProduct($product_id) 
    {
        $product = Product::where('id', $product_id)
                                ->get()->first();
        return view('admin.product.createImages',
                    [
                        'product' => $product,
                    ]);
    }

    public function fileUpload(Request $request, $product_id){
        $request->validate([
          'imageFile' => 'required',
          'imageFile.*' => 'mimes:jpeg,jpg,png,gif,csv,txt,pdf|max:4096'
        ]);
        return (count($request->file('imageFile')));
    
        if($request->hasFile('imageFile')) {
            $i = 0;
            foreach($request->file('imageFile') as $file)
            {
                $i++;
                $name = $file->getClientOriginalName();
                $file->move(public_path().'/uploads/', $name);  
                $imgData[] = $name;  
            }
    
            // $fileModal = new Image();
            // $fileModal->name = json_encode($imgData);
            // $fileModal->image_path = json_encode($imgData);
            
            $product = Product::findOrFail($product_id);
            $product->image = $imgData;
           
            $product->save();
    
           return $i;
        }
      }
}
