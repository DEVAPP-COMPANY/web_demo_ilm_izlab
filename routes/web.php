<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Backend\OfferController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ImageController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\ScienceController;
use App\Http\Controllers\Backend\CenterImageController;
use App\Http\Controllers\Backend\CourseController;
use App\Http\Controllers\Backend\TeacherController;
use App\Http\Controllers\Backend\TrainingCenterController;
use App\Http\Controllers\Backend\RatingController;
use App\Http\Controllers\FcmMessageController;
use App\Http\Controllers\Backend\AppUserController;
use App\Http\Controllers\Backend\NewsController;
use App\Http\Controllers\Bot\BotCenterController;
use App\Http\Controllers\DeveloperController;

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


// Bot

Route::match(['get', 'post'], '/bot',[BotCenterController::class, 'bot']);

Route::get('/center/{center_id}', [TrainingCenterController::class, 'centerDetail']);
Route::get('/news/{news_id}', [TrainingCenterController::class, 'newsDetail'])->name('news_detail');

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [AdminController::class, 'loginForm'])->name('index');


Route::group(['middleware' => ['admin:admin']], function(){
    Route::post('/', [AdminController::class, 'store'])->name('admin.login');
});

Route::middleware(['auth:sanctum,admin', 'verified'])->get('/admin/dashboard', function () {
    return view('admin.index');
})->name('dashboard');


// Route::middleware(['auth:sanctum,web', 'verified'])->get('/dashboard', function () {
//     return view('dashboard');
// });

// Admin All Routes 
Route::get('/admin/logout', [AdminController::class, 'destroy'])->name('admin.logout');


Route::middleware(['auth:admin'])->group(function () {

    Route::prefix('offer')->group(function (){
        Route::get('/view', [OfferController::class, 'offerView'])->name('all.offer');
        Route::get('/add', [OfferController::class, 'offerAdd'])->name('offer.add');
        Route::post('/store', [OfferController::class, 'offerStore'])->name('offer.store');
        Route::get('/edit/{id}', [OfferController::class, 'offerEdit'])->name('offer.edit');
        Route::post('/update/{id}', [OfferController::class, 'offerUpdate'])->name('offer.update');
        Route::get('/delete/{id}', [OfferController::class, 'offerDelete'])->name('offer.delete');
    });

    Route::prefix('images')->group(function () {
        Route::get('/view', [ImageController::class, 'viewImage'])->name('show.images');
        Route::post('/store', [ImageController::class, 'imageStore'])->name('image.store');
        Route::get('/delete/{id}', [ImageController::class, 'imageDelete'])->name('image.delete');
    });

    Route::prefix('category')->group(function (){
        Route::get('/view', [CategoryController::class, 'categoryView'])->name('all.category');
        Route::post('/store', [CategoryController::class, 'categoryStore'])->name('category.store');
        Route::get('/edit/{id}', [CategoryController::class, 'categoryEdit'])->name('category.edit');
        Route::post('/update/{id}', [CategoryController::class, 'categoryUpdate'])->name('category.update');
        Route::get('/delete/{id}', [CategoryController::class, 'categoryDelete'])->name('category.delete');

        Route::prefix('science')->group(function (){
            Route::get('/view/{category_id}', [ScienceController::class, 'scienceView'])->name('all.science');
            Route::post('/store/{category_id}', [ScienceController::class, 'scienceStore'])->name('science.store');
            Route::get('/edit/{id}', [ScienceController::class, 'scienceEdit'])->name('science.edit');
            Route::post('/update/{id}', [ScienceController::class, 'scienceUpdate'])->name('science.update');
            Route::get('/delete/{id}', [ScienceController::class, 'scienceDelete'])->name('science.delete');
        });
    });


    Route::prefix('training/center')->group(function (){
        Route::get('/view', [TrainingCenterController::class, 'trainingCenterView'])->name('all.training_center');
        Route::get('/add', [TrainingCenterController::class, 'trainingCenterAdd'])->name('training_center.add');
        Route::get('/region/district/ajax/{region_id}', [TrainingCenterController::class, 'getDistrict']);
        Route::post('/store', [TrainingCenterController::class, 'trainingCenterStore'])->name('training_center.store');
        Route::get('/edit/{id}', [TrainingCenterController::class, 'trainingCenterEdit'])->name('training_center.edit');
        Route::get('/detail/{id}', [TrainingCenterController::class, 'trainingCenterDetail'])->name('training_center.detail');
        Route::post('/update/{id}', [TrainingCenterController::class, 'trainingCenterUpdate'])->name('training_center.update');
        Route::get('/delete/{id}', [TrainingCenterController::class, 'trainingCenterDelete'])->name('training_center.delete');
        Route::get('/accepted/{id}', [TrainingCenterController::class, 'trainingCenterAccepted'])->name('training_center.accepted');
        Route::get('/waiting/{id}', [TrainingCenterController::class, 'trainingCenterWaiting'])->name('training_center.waiting');
        Route::get('/rejected/{id}', [TrainingCenterController::class, 'trainingCenterRejected'])->name('training_center.rejected');
        Route::get('/blocked/{id}', [TrainingCenterController::class, 'trainingCenterBlocked'])->name('training_center.blocked');

        Route::get('/waiting', [TrainingCenterController::class, 'trainingCenterWaitings'])->name('all.training_center_waiting');

        Route::prefix('image')->group(function (){
            Route::get('/view/{center_id}', [TrainingCenterController::class, 'centerImageView'])->name('all.center_image');
            Route::post('/store/{center_id}', [TrainingCenterController::class, 'centerImageStore'])->name('center_image.store');
            Route::get('/delete/{id}', [TrainingCenterController::class, 'centerImageDelete'])->name('center_image.delete');
        });

        Route::prefix('course')->group(function (){
            Route::post('/store/{center_id}', [CourseController::class, 'courseStore'])->name('course.store');
            Route::post('/edit/{id}', [CourseController::class, 'courseEdit'])->name('course.edit');
            Route::post('/update/{id}', [CourseController::class, 'courseUpdate'])->name('course.update');
            Route::get('/delete/{id}', [CourseController::class, 'courseDelete'])->name('course.delete');
            Route::post('/teacher/connect', [CourseController::class, 'connect']);
        });

        Route::prefix('teacher')->group(function (){
            Route::get('/add/{center_id}', [TeacherController::class, 'teacherAdd'])->name('add.teacher');
            Route::post('/store/{center_id}', [TeacherController::class, 'teacherStore'])->name('teacher.store');
            Route::get('/edit/{id}', [TeacherController::class, 'teacherEdit'])->name('teacher.edit');
            Route::post('/update/{id}', [TeacherController::class, 'teacherUpdate'])->name('teacher.update');
            Route::get('/delete/{id}', [TeacherController::class, 'teacherDelete'])->name('teacher.delete');
        });

        Route::prefix('news')->group(function (){
            Route::get('/add/{center_id}', [NewsController::class, 'addNews'])->name('add.news');
            Route::post('/store/{center_id}', [NewsController::class, 'store'])->name('store.news');
            Route::get('/edit/{id}', [NewsController::class, 'edit'])->name('news.edit');
            Route::post('/update/{id}', [NewsController::class, 'update'])->name('news.update');
            Route::get('/delete/{id}', [NewsController::class, 'delete'])->name('news.delete');
            Route::get('/accept/{id}', [NewsController::class, 'newsAccept'])->name('news.accept');
            Route::get('/reject/{id}', [NewsController::class, 'newsReject'])->name('news.reject');

            Route::get('/waiting', [NewsController::class, 'ratingNews'])->name('all.news_waiting');

        });
    });

    Route::prefix('rating')->group(function (){
        Route::get('/view', [RatingController::class, 'ratingView'])->name('all.rating');
        Route::get('/accepted/{id}', [RatingController::class, 'ratingAccept'])->name('rating.accepted');
        Route::get('/waiting/{id}', [RatingController::class, 'ratingWaiting'])->name('rating.waiting');
        Route::get('/rejected/{id}', [RatingController::class, 'ratingReject'])->name('rating.rejected');
        Route::get('/delete/{id}', [RatingController::class, 'ratingDelete'])->name('rating.delete');

        Route::get('/waiting', [RatingController::class, 'ratingWaitings'])->name('all.rating_waiting');
        Route::get('/delete_subscriber/{id}', [RatingController::class, 'subscriberDelete'])->name('subscriber.delete');

        // Route::prefix('science')->group(function (){
        //     Route::get('/view/{category_id}', [ScienceController::class, 'scienceView'])->name('all.science');
        //     Route::post('/store/{category_id}', [ScienceController::class, 'scienceStore'])->name('science.store');
        //     Route::get('/edit/{id}', [ScienceController::class, 'scienceEdit'])->name('science.edit');
        //     Route::post('/update/{id}', [ScienceController::class, 'scienceUpdate'])->name('science.update');
        //     Route::get('/delete/{id}', [ScienceController::class, 'scienceDelete'])->name('science.delete');
        // });
    });

    Route::prefix('filter/center')->group(function (){
        // Route::get('/status/ajax/{status}', [FilterStudentController::class, 'filterStatuscenter']);
        // Route::get('/course/ajax/{course}', [FilterStudentController::class, 'filterCoursecenter']);
        Route::get('/ajax', [TrainingCenterController::class, 'filterCenter']);
       
    });

    Route::get('/fcm/clear', [FcmMessageController::class, 'clearMessage'])->name('fcm.clear');
    Route::resource('fcm', 'FcmMessageController');
    Route::get('/app_user', [AppUserController::class, 'index'])->name('app_user.index');
    Route::get('/app_user/show/{id}', [AppUserController::class, 'show'])->name('app_user.show');
    Route::post('/app_user/send_fcm/{id}', [AppUserController::class, 'sendFcm'])->name('fcm_user.store');
    Route::get('/app_user/status/{id}/{status}', [AppUserController::class, 'status'])->name('app_user.status');

    // developers

    Route::get('/developers', [DeveloperController::class, 'index'])->name('developers.index');
    Route::get('/dev/view/{id}', [DeveloperController::class, 'show'])->name('developers.show');
    Route::post('/dev/store', [DeveloperController::class, 'store'])->name('developers.store');
    Route::get('/dev/accept/{id}', [DeveloperController::class, 'accept'])->name('developer.accept');
    Route::get('/dev/block/{id}', [DeveloperController::class, 'block'])->name('developer.block');


    
});
