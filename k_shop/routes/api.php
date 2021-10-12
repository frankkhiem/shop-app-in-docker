<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route API chinh sua thong tin nguoi dung
Route::get('/profile', 'Api\UserController@profile')->middleware('user');
Route::put('/profile', 'Api\UserController@update')->name('apiPostProfile')->middleware('user');
Route::post('/profile/avatar', 'Api\UserController@updateAvatar')->middleware('user');

// Route API dang xuat nguoi dung
Route::post('/logout', 'Api\UserController@logout')->middleware('user');

//API sản phẩm
Route::get('/products', 'Api\ProductController@index')->name('products.index');
Route::get('product/{id}', 'Api\ProductController@show')->name('products.show');
Route::get('products/category/{category_id}', 'Api\ProductController@productsInCategori');

//API gợi ý sản phẩm liên quan
Route::get('product/{id}/recommend', 'Api\ProductController@recommendProduct');

//API top 4 sản phẩm được mua nhiều nhất
Route::get('topproduct', 'Api\ProductController@topSell')->name('products.topSell');

//API top 4 sản phẩm nổi bật mới nhất
Route::get('hotproduct', 'Api\ProductController@hotProduct');

////API top 5 sản phẩm giá cao nhất theo từng danh mục
Route::get('toppricebycategory/{id}', 'Api\ProductController@topPrice')->name('products.topPrice');

//API top 5 sản phẩm được mua nhiều nhất theo từng danh mục
Route::get('topsellingtbycategory/{id}', 'Api\ProductController@topSellingByCategory')->name('products.topsellingtbycategory');

//API danh muc san pham
Route::get('/category', 'Api\CategoryController@index')->name('categorys.index');
Route::get('/category/{id}', 'Api\CategoryController@show')->name('categorys.show');
Route::get('/download/log-categories-import/{filePath}', 'Api\CategoryController@downloadLogImport');

// API danh sach don hang da dat
Route::get('/listorders','Api\OrderController@index')->middleware('user');

//API thong tin ve tung don hang
Route::get('/order/{id}','Api\OrderController@show');

// API tao don dat hang moi
Route::post('/order', 'Api\OrderController@store');

//API get danh sach comments trong san phamr
Route::get('/product/{id}/comments', 'Api\CommentController@commentsInProduct');

// API tao comment moi
Route::post('/comment', 'Api\CommentController@store');

// API giỏ hàng
Route::post('/cart', 'Api\CartController@store');
Route::get('/cart', 'Api\CartController@getCartByUserId');
Route::delete('/cart/{id}', 'Api\CartController@destroy');
Route::delete('/cart/delete/all', 'Api\CartController@destroy_all');

// API login cho SPA
Route::post('/login', 'Api\AuthSPAController@login');
Route::middleware('auth:sanctum')->get('/logout', 'Api\AuthSPAController@logout');

// API register cho SPA
Route::post('/register', 'Api\AuthSPAController@register');

// API lien quan toi xu ly thong bao cho nguoi dung
Route::get('/list-notifications', 'Api\NotificationController@getListNotifications');
Route::get('/notification/{id}', 'Api\NotificationController@getDetailNotificationById');
Route::get('/number-of-new-notifications', 'Api\NotificationController@getNumberNewNotifications');
Route::get('/watched-new-notifications', 'Api\NotificationController@watchedNewNotifications');