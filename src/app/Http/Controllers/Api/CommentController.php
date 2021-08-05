<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    //

    public function commentsInProduct($product_id) {
        $comments = DB::table('products')
                    ->join('comments', 'products.id','=', 'comments.product_id')
                    ->join('users', 'users.id', '=', 'comments.user_id')
                    ->select('comments.user_id', 'users.name as user_name', 'comments.content')
                    ->where('products.id','=', $product_id)
                    ->orderByDesc('comments.created_at')
                    ->paginate(5);
        return $comments;
    }

    //
    public function store(Request $request)
    {
        $comment = new Comment;
        $comment->fill([
            'user_id' => $request->user_id,
            'product_id' => $request->product_id,
            'content' => $request->content,
        ])->save();
        return 'Comment thanh cong';
    }
}
