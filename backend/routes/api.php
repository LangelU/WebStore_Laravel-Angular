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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', 'App\Http\Controllers\UserController@register');
Route::post('login', 'App\Http\Controllers\UserController@authenticate');

Route::group(['middleware' => ['jwt.verify']], function() {

    /*** Business Endpoints ***/
    // Create a new business
    Route::post('newBusiness','App\Http\Controllers\BusinessController@createBusiness');
    // Edit business
    Route::post('editBusiness','App\Http\Controllers\BusinessController@updateBusiness');

    /*** Categories Endpoints ***/
    //Create a new category
    Route::post('newCategory','App\Http\Controllers\CategoryController@createCategory');
    // Edit a category
    Route::put('editCategory','App\Http\Controllers\CategoryController@updateCategory');
    // Show all categories
    Route::get('showCategories','App\Http\Controllers\CategoryController@showCategories');
    // Delete a category
    Route::post('deleteCategory','App\Http\Controllers\CategoryController@deleteCategory');

    /*** Customers Endpoints ***/
    //Create a new customer
    Route::post('newCustomer','App\Http\Controllers\CustomerController@createCustomer');
    // Edit a customer
    Route::put('editCustomer','App\Http\Controllers\CustomerController@updateCustomer');
    // Show all customers
    Route::get('showCustomers','App\Http\Controllers\CustomerController@showCustomers');
    // Delete a customer
    Route::post('deleteCategory','App\Http\Controllers\CustomerController@deleteCustomer');

    /*** Favorites Endpoints ***/
    //Create a new favorite
    Route::post('newFavorite','App\Http\Controllers\FavoriteController@createFavorite');
    // Show all favorites
    Route::get('showFavorites','App\Http\Controllers\FavoriteController@showFavorites');
    // Delete a favorite
    Route::post('deleteFavorite','App\Http\Controllers\FavoriteController@deleteFavorite');

    /*** Invoices Endpoints ***/
    //Create a new invoice
    Route::post('newInvoice','App\Http\Controllers\InvoiceController@createInvoice');
    // Show all invoices
    Route::get('showInvoices','App\Http\Controllers\InvoiceController@showInvoices');
    // Delete a invoice
    Route::post('deleteInvoice','App\Http\Controllers\InvoiceController@deleteInvoice');

    /*** Pictures Endpoints ***/
    //Create a new picture
    Route::post('newPicture','App\Http\Controllers\PictureController@createPicture');
    // Edit a picture
    Route::put('editPicture','App\Http\Controllers\PictureController@updatePicture');
    // Show all pictures
    Route::get('showPictures','App\Http\Controllers\PictureController@showPictures');
    // Delete a picture
    Route::post('deletePicture','App\Http\Controllers\PictureController@deletePicture');

    /*** Products Endpoints ***/
    //Create a new product
    Route::post('newProduct','App\Http\Controllers\ProductController@createProduct');
    // Edit a product
    Route::put('editProduct','App\Http\Controllers\ProductController@updateProduct');
    // Show all products
    Route::get('showProducts','App\Http\Controllers\ProductController@showProducts');
    // Delete a product
    Route::post('deleteProduct','App\Http\Controllers\ProductController@deleteProduct');

    /*** ProductsTags Endpoints ***/
    //Create a new productTag
    Route::post('newProductTag','App\Http\Controllers\ProductTagController@createProductTag');
    // Edit a productTags
    Route::put('editProductTag','App\Http\Controllers\ProductTagController@updateProductTag');
    // Show all productsTag
    Route::get('showProductsTags','App\Http\Controllers\ProductTagController@showProductsTags');
    // Delete a productTag
    Route::post('deleteProductTag','App\Http\Controllers\ProductTagController@deleteProductTag');

    /*** Ratings Endpoints ***/
    //Create a new rating
    Route::post('newRating','App\Http\Controllers\RatingController@createRating');
    // Edit a rating
    Route::put('editRating','App\Http\Controllers\RatingController@updateRating');
    // Show all ratings
    Route::get('showRatings','App\Http\Controllers\RatingController@showRatings');
    // Delete a rating
    Route::post('deleteRating','App\Http\Controllers\RatingController@deleteRating');

    /*** Requests Endpoints ***/
    //Create a new request
    Route::post('newRequest','App\Http\Controllers\RequestController@createRequest');
    // Edit a request
    Route::put('editRequest','App\Http\Controllers\RequestController@updateRequest');
    // Show all requests
    Route::get('showRequests','App\Http\Controllers\RequestController@showRequests');
    // Delete a request
    Route::post('deleteRequest','App\Http\Controllers\RequestController@deleteRequest');

    /*** Roles Endpoints ***/
    //Create a new role
    Route::post('newRole','App\Http\Controllers\RoleController@createRole');
    // Edit a role
    Route::put('editRole','App\Http\Controllers\RoleController@updateRole');
    // Show all roles
    Route::get('showRoles','App\Http\Controllers\RoleController@showRoles');
    // Delete a role
    Route::post('deleteRole','App\Http\Controllers\RoleController@deleteRole');

    /*** SaleHistory Endpoints ***/
    //Create a new entry
    Route::post('newEntry','App\Http\Controllers\SaleHistoryController@createEntry');
    // Show all entries
    Route::get('showEntries','App\Http\Controllers\SaleHistoryController@showEntries');
    // Delete a entry
    Route::post('deleteEntry','App\Http\Controllers\SaleHistoryController@deleteEntry');

    /*** Tags Endpoints ***/
    //Create a new tag
    Route::post('newTag','App\Http\Controllers\TagController@createTag');
    // Edit a tag
    Route::put('editTag','App\Http\Controllers\TagController@updateTag');
    // Show all tags
    Route::get('showTags','App\Http\Controllers\TagController@showTags');
    // Delete a tag
    Route::post('deleteTag','App\Http\Controllers\TagController@deleteTag');

    //Rutas de Usuario
    Route::post('user','App\Http\Controllers\UserController@getAuthenticatedUser');

});
