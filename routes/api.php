<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\ApiOfferController;
use App\Http\Controllers\Api\ApiCategoryController;
use App\Http\Controllers\Api\ApiControlCenterController;
use App\Http\Controllers\Api\ApiCourseController;
use App\Http\Controllers\Api\ApiRegionController;
use App\Http\Controllers\Api\ApiTrainingCentersController;
use App\Http\Controllers\Api\ApiUserController;
use App\Http\Controllers\Api\ApiNewsController;
use App\Http\Controllers\Api\ApiScienceController;

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

Route::get('offers', [ApiOfferController::class, 'offers']);
Route::get('offer/{offer_id}/content', [ApiOfferController::class, 'offerContent']);

Route::get('regions', [ApiRegionController::class, 'regions']);

Route::post("training_centers", [ApiTrainingCentersController::class, 'trainingCetersFilter']);
Route::post("make_rating", [ApiTrainingCentersController::class, 'makeRating']);
Route::post("set_subscriber", [ApiTrainingCentersController::class, 'setSubscriber']);
Route::get("get_my_centers", [ApiTrainingCentersController::class, 'getMyCenters']);
Route::get("get_ratings/{center_id}", [ApiTrainingCentersController::class, 'getRating']);
Route::get("get_courses/{center_id}", [ApiTrainingCentersController::class, 'getCourses']);
Route::get("check_subscriber/{center_id}", [ApiTrainingCentersController::class, 'checkSubscriber']);
Route::get("get_my_own_centers", [ApiTrainingCentersController::class, 'getMyOwnCenters']);
Route::get("subscriber/{center_id}/all", [ApiTrainingCentersController::class, 'subscriberCenterAll']);


// User
Route::get("user", [ApiUserController::class, 'getUser']);
Route::post("send_confirm_code", [ApiUserController::class, 'sendConfirmCode']);
Route::post("check_phone", [ApiUserController::class, 'checkPhone']);
Route::post("registration", [ApiUserController::class, 'registration']);
Route::post("login", [ApiUserController::class, 'login']);
Route::post("update_profile", [ApiUserController::class, 'updateProfile']);
Route::get("update_sms_token", [ApiUserController::class, 'updateSmsToken']);

Route::post("update_avatar", [ApiUserController::class, 'updateAvatar']);
Route::post("reset_password", [ApiUserController::class, 'resetPassword']);

// News
Route::post("add_news", [ApiNewsController::class, 'addNews']);
Route::get("get_news", [ApiNewsController::class, 'getNewsAll']);
Route::get("get_news/{center_id}", [ApiNewsController::class, 'getNews']);
Route::get("news/{news_id}/content", [ApiNewsController::class, 'getNewsContent']);
Route::get('news_delete/{news_id}', [ApiNewsController::class, 'deleteNews']);

Route::get("course_teachers/{course_id}", [ApiTrainingCentersController::class, 'courseTeachers']);

// Control Center
Route::post("add_center_request", [ApiControlCenterController::class, 'addCenterRequest']);
Route::post("add_center_images/{center_id}", [ApiControlCenterController::class, 'addCenterImage']);
Route::post("delete_lc_image", [ApiControlCenterController::class, 'deleteCenterImage']);
Route::post("main_image_update/{center_id}", [ApiControlCenterController::class, 'updateMainImage']);

// Category
Route::post("add_category", [ApiCategoryController::class, 'addCategory']);
Route::get('categories', [ApiCategoryController::class, 'categories']);

// Science
Route::post("add_science/{category_id}", [ApiScienceController::class, 'addScience']);
Route::get('get_sciences', [ApiScienceController::class, 'getScience']);

// Course
Route::post("add_course/{center_id}", [ApiCourseController::class, 'addCenterCourse']);
Route::get('course_delete/{course_id}', [ApiCourseController::class, 'deleteCourse']);

// Teacher
Route::get('get_teachers', [ApiControlCenterController::class, 'getTeachers']);
Route::get('get_teachers/{center_id}', [ApiControlCenterController::class, 'getCenterTeachers']);
Route::post("add_teacher/{center_id}", [ApiControlCenterController::class, 'addCenterTeacher']);
Route::get('teacher_delete/{teacher_id}', [ApiControlCenterController::class, 'deleteTeacher']);

Route::post("connect_course_teacher", [ApiControlCenterController::class, 'connectCourseTeacher']);

