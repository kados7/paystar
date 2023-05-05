<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Home\ProductController;
use App\Http\Controllers\Api\Home\ProfileController;
use App\Http\Controllers\Api\Payment\PaymentController;
use App\Http\Controllers\Api\TransactionController;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:passport')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class , 'register']);
Route::post('/login', [AuthController::class , 'login'])->name('login');
Route::post('/logout', [AuthController::class , 'logout'])->middleware('auth:api');
Route::post('/check', [AuthController::class , 'check'])->middleware('auth:api');

Route::prefix('v1')->name('v1.')->group(function(){


    Route::get('/products', [ProductController::class , 'index'])->name('home.post.index');
    Route::get('/products/{product:id}', [ProductController::class , 'show'])->name('home.post.show');
    Route::post('/products/{product:id}/isPurchased', [ProductController::class , 'isPurchased'])->middleware('auth:api');

    Route::post('/getUserCart', [ProfileController::class , 'getUserCart'])->middleware('auth:api');
    Route::post('/addUserCart', [ProfileController::class , 'addUserCart'])->middleware('auth:api');
    Route::post('/getUserProducts', [ProfileController::class , 'getUserProducts'])->middleware('auth:api');

    Route::post('/goToPayment', [PaymentController::class , 'startPayment'])->middleware('auth:api');
    Route::post('/paystare/callback', [PaymentController::class , 'paymentCallback'])->name('paystar.callback');
    Route::get('/transaction/{transaction:transaction_id}', [TransactionController::class , 'show'])->name('transaction.show');

    // Route::get('/courses/{course:id}', [CourseController::class , 'show']);
    // Route::get('/courses/{course:id}/tags', [CourseController::class , 'showCourseTags']);
    // Route::get('/courses/{course:id}/episodes', [CourseController::class , 'showCourseEpisodes']);
    // Route::get('/courses/{course:id}/seasons', [CourseController::class , 'showCourseSeasons']);

    // Route::get('/seasons/{season:id}', [SeasonController::class , 'show']);
    // Route::get('/seasons/{season:id}/episodes', [SeasonController::class , 'showSeasonEpisodes']);

    // Route::get('/episodes/{episode:id}/contentType', [EpisodeController::class , 'getContentType']);
    // Route::get('/episodes/{episode:id}/duration', [EpisodeController::class , 'showDuration']);
    // Route::get('/episodes/{episode:id}/tags', [EpisodeController::class , 'showEpisodeTags']);
    // Route::get('/episodes/{episode:id}/comments', [EpisodeController::class , 'showEpisodeComments']);
    // Route::post('/episodes/{episode:id}/addToWichlist', [EpisodeController::class , 'addToWishlist'])->middleware('auth:sanctum');
    // Route::post('/episodes/{episode:id}/deleteFromWichlist', [EpisodeController::class , 'deleteFromWichlist'])->middleware('auth:sanctum');
    // Route::get('/episodes/{episode:id}/wishlistCount', [EpisodeController::class , 'getEpisodeWishCount']);
    // Route::get('/episodes/{episode:id}/commentscount', [CommentController::class , 'getEpisodeCommentsCount']);


    // Route::get('/comments/{comment:id}', [CommentController::class , 'show']);
    // Route::get('/comments/{comment:id}/replays', [CommentController::class , 'showCommentReplies']);

    // Route::get('/episodes/{episode:id}/video', [VideoController::class , 'show']);

    // Route::get('/episodes/{episode:id}/attachments', [AttachmentController::class , 'showEpisodeAttachments']);




});

Route::get('/test', function(Request $request){

});
