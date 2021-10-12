<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Route đăng nhập vào Google
Route::get('/login/google', 'Auth\SocialAuthController@loginGoogle')->name('loginGoogle');
Route::get('/login/google/callback', 'Auth\SocialAuthController@loginGoogleCallback')->name('googleCallback');

// Xử lý sau khi đăng nhập
Route::get('/admin', 'Admin\AdminController@index')
        ->middleware('admin')
        ->name('adminPage');
Route::get('/profile', function () {
    return view('welcome');
})->middleware('user')->name('profilePage');

// Route xử lý chức năng của admin
Route::prefix('admin')->middleware('admin')->group(function () {
    // Route quản trị danh mục sản phẩm
    Route::resource(
        '/category',
        'Admin\AdminCategoryController',
        [
            'names' => 'adminCategory'
        ]
    );
    // Route quản trị sản phẩm
    Route::resource(
        '/product',
        'Admin\AdminProductController',
        [
            'names' => 'adminProduct'
        ]
    );
    // Các route quản trị thông số kỹ thuật của sản phẩm
    Route::get('/product/{product_id}/info', 'Admin\AdminInfoProductController@listInfo')->name('listInfoProduct');
    Route::post('/product/{product_id}/info', 'Admin\AdminInfoProductController@createInfo')->name('createInfoProduct');
    Route::get('/product/{product_id}/info/{info_id}', 'Admin\AdminInfoProductController@editInfo')->name('editInfoProduct');
    Route::put('/product/{product_id}/info/{info_id}', 'Admin\AdminInfoProductController@updateInfo')->name('updateInfoProduct');
    Route::delete('/product/{product_id}/info/{info_id}', 'Admin\AdminInfoProductController@deleteInfo')->name('deleteInfoProduct');

    // Các route quản trị các đơn đặt hàng
    Route::get('order/confirm', 'Admin\AdminOrderController@listConfirmOrder')->name('listConfirmOrder');
    Route::get('order/{order_id}/confirm', 'Admin\AdminOrderController@confirmOrder')->name('confirmOrder');
    Route::get('order/shipped', 'Admin\AdminOrderController@listShippedOrder')->name('listShippedOrder');
    Route::get('order/{order_id}/completed', 'Admin\AdminOrderController@completedOrder')->name('completedOrder');
    Route::get('order/completed', 'Admin\AdminOrderController@listCompletedOrder')->name('listCompletedOrder');
    Route::get('order/{order_id}/detail', 'Admin\AdminOrderController@detailOrder')->name('detailOrder');

    // Các route quản trị tài khoản khách hàng
    Route::get('customer', 'Admin\AdminCustomerController@listCustomer')->name('listCustomer');
    Route::get('customer/banned', 'Admin\AdminCustomerController@listCustomerBanned')->name('listCustomerBanned');
    Route::get('customer/{customer_id}/ban', 'Admin\AdminCustomerController@banCustomer')->name('banCustomer');
    Route::get('customer/{customer_id}/unban', 'Admin\AdminCustomerController@unbanCustomer')->name('unbanCustomer');

    // Các route xử lý ảnh
    Route::get('/product/{product_id}/images', 'Admin\FileUploadController@createImagesProduct')->name('createImagesProduct');
    Route::post('/product/{product_id}/images', 'Admin\FileUploadController@fileUpload')->name('imageUploadProduct');

    // Các route quản trị thông báo tới user
    Route::post('/notification', 'Admin\AdminNotificationController@createNewNotification')->name('createNewNotification');

    // Các route quản trị thêm không nằm trong resource tạo sẵn
    // Với category
    Route::post('/categories/file-import', 'Admin\AdminCategoryController@fileImport')->name('importCategoriesByFile');
    Route::post('/download/log-categories-import', 'Admin\AdminCategoryController@downloadLogImport')->name('downloadCategoriesLogFile');
    // Với product
    Route::get('/products/file-import', 'Admin\AdminProductController@viewFileImport')->name('viewFileImportProducts');
    Route::post('/products/file-import', 'Admin\AdminProductController@fileImport')->name('importProductsByFile');
    Route::post('/download/log-products-import', 'Admin\AdminProductController@downloadLogImport')->name('downloadProductsLogFile');
    // Với thông số kỹ thuật của product
    Route::post('info-products/file-import', 'Admin\AdminInfoProductController@fileImport')->name('importInfoProductsByFile');
});

// Các route chuyển sang VueJs       
Route::get('/logout', 'HomeController@index')->name('home');
Route::get('test/profile', function(){
    return view('testProfile');
});

Route::get('/{url}', function($name)
{
    return view('welcome');
})
->where('url', '[A-Za-z0-9/-]+');
