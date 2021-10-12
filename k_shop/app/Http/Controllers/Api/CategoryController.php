<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    //
    public function index()
    {
        return Category::all();
    }
    
    public function show($id)
    {
        $category = Category::find($id);
        $totalProducts = DB::table('categories')
                            ->selectRaw('count(*) as total')
                            ->join('products', 'categories.id', '=', 'products.category_id')
                            ->where('categories.id', $id)
                            ->whereNull('deleted_at')
                            ->get();
         
        return $category;
    }

    public function downloadLogImport($filePath) {
        return Storage::download($filePath);
    }
}
