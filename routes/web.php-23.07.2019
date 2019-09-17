<?php
 use App\Events\EventTrigger;
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



Route::group(['prefix' => 'admin'], function () {
    Auth::routes();
});

Route::get('app/', 'Users\HomeController@index')->name('home');

Route::get('app/{vue_capture?}', function() {
    return view('home');
})->where('vue_capture', '[\/\w\.-]*');


Route::group(['middleware' => 'auth:admin'], function () {


    Route::get('/administrator', 'Admin\DashboardController@index')->name('dashboard');

    // Check permission
    Route::get('/api/admin/check/permission', 'Admin\CheckAdminPermission@checkPermission');

    // Dashboard analysis
    Route::get('/api/admin/get/dashboard/analysis', 'Admin\DashboardController@usersAnalysisByDay');

    // Check services
    Route::get('/api/admin/get/checkservices', 'Admin\DashboardController@checkServices');

    // Movies manage
    Route::get('/api/admin/get/movies', 'Admin\MovieController@getAllMovies');
    Route::post('/api/admin/delete/movie', 'Admin\MovieController@deleteMovie');
    Route::post('/api/admin/get/movie/search', 'Admin\MovieController@searchMovie');
    Route::post('/api/admin/new/movie/movieapi', 'Admin\MovieController@movieTmdbAPI');
    Route::post('/api/admin/new/movie/movievideo', 'Admin\MovieController@movieUpload');
    Route::post('/api/admin/new/movie/moviesubtitle', 'Admin\MovieController@subtitleUpload');
    Route::post('/api/admin/new/movie/customupload', 'Admin\MovieController@customUploadMovieDetails');
    Route::get('/api/admin/get/movie/{id}', 'Admin\MovieController@getMovieDetails');
    Route::post('/api/admin/update/movie', 'Admin\MovieController@update');
    Route::post('/api/admin/update/movie/available', 'Admin\MovieController@availableMovie');
    Route::get('/api/admin/analysis/movie/{id}', 'Admin\MovieController@analysisMovie');

    // Series manage
    Route::get('/api/admin/get/series', 'Admin\SeriesController@getAllSeries');
    Route::get('/api/admin/get/series/{id}', 'Admin\SeriesController@getSeries');
    Route::post('/api/admin/get/series/search', 'Admin\SeriesController@searchSeries');
    Route::delete('/api/admin/delete/series/{id}', 'Admin\SeriesController@deleteSeries');
    Route::post('/api/admin/new/series/moviedbapi', 'Admin\SeriesController@uploadSeriesInfoByTmdb');
    Route::post('/api/admin/new/series/customupload', 'Admin\SeriesController@uploadSeriesInfoByCustom');
    Route::get('/api/admin/get/series/season/{id}', 'Admin\SeriesController@getAllSeason');
    Route::post('/api/admin/new/series/episode/details', 'Admin\SeriesController@uploadEpisodeInfoByTmdb');
    Route::post('/api/admin/new/series/episode/video', 'Admin\SeriesController@UploadEpisodeVideos');
    Route::post('/api/admin/new/series/episode/subtitle', 'Admin\SeriesController@UploadSubtitleTolocal');
    Route::get('/api/admin/get/series/info/{id}', 'Admin\SeriesController@getSeriesInfo');
    Route::post('/api/admin/delete/series/episode', 'Admin\SeriesController@deleteEpisode');
    Route::post('/api/admin/update/series', 'Admin\SeriesController@update');
    Route::post('/api/admin/new/series/episode/details/custom', 'Admin\SeriesController@uploadEpisodeInfoByCustom');
    Route::post('/api/admin/update/series/episode/available', 'Admin\SeriesController@availableEpisode');
    Route::get('/api/admin/get/series/episode/{id}', 'Admin\SeriesController@getEpisodeDetails');
    Route::post('/api/admin/update/series/episode', 'Admin\SeriesController@updateEpisodeDetails');
    Route::get('/api/admin/analysis/series/{id}', 'Admin\SeriesController@analysisSeries');


    // Tv manage
    Route::get('/api/admin/get/channels', 'Admin\TvController@getAllChannels');
    Route::post('/api/admin/new/channel/details', 'Admin\TvController@createChannelDetails');
    Route::post('/api/admin/new/channel/video', 'Admin\TvController@uploadChannelVideo');
    Route::delete('/api/admin/delete/channel/{id}', 'Admin\TvController@deleteChannel');
    Route::get('/api/admin/get/channel/{id}', 'Admin\TvController@getChannelDetails');
    Route::post('/api/admin/update/channel', 'Admin\TvController@updateChannel');
    Route::put('/api/admin/streaming/channel/{id}', 'Admin\TvController@startStreaming');

    // Tops manage
    Route::get('/api/admin/get/top', 'Admin\TopsController@getTopMas');
    Route::post('/api/admin/new/movie/top', 'Admin\TopsController@addMovieToTop');
    Route::post('/api/admin/new/series/top', 'Admin\TopsController@addSeriesToTop');
    Route::delete('/api/admin/delete/top/{id}', 'Admin\TopsController@deleteTop');


    // Actors manage
    Route::get('/api/admin/get/actors', 'Admin\ActorsController@getAllActors');
    Route::post('/api/admin/update/actors/', 'Admin\ActorsController@update');
    Route::post('/api/admin/new/actor', 'Admin\ActorsController@create');
    Route::delete('/api/admin/delete/actor/{id}', 'Admin\ActorsController@delete');
    Route::post('/api/admin/get/actors/search', 'Admin\ActorsController@search');

    // Reports manage
    Route::get('/api/admin/get/reports', 'Admin\ReportsController@getAllReports');
    Route::get('/api/admin/get/report/{id}', 'Admin\ReportsController@getReport');
    Route::delete('/api/admin/delete/report/{id}', 'Admin\ReportsController@deleteOneReport');
    Route::delete('/api/admin/delete/all/reports/{id}', 'Admin\ReportsController@deleteAllReports');
    Route::match(['put', 'patch'], '/api/admin/new/report/readit/{id}', 'Admin\ReportsController@readIt');

    // Subtitles manage
    Route::get('/api/admin/get/subtitles/movie/{id}', 'Admin\SubtitlesController@getMovieSubtitle');
    Route::get('/api/admin/get/subtitles/episode/{id}', 'Admin\SubtitlesController@getEpisodeSubtitle');
    Route::delete('/api/admin/delete/subtitle/{id}', 'Admin\SubtitlesController@deleteSubtitle');

    // Users manage
    Route::get('/api/admin/get/users', 'Admin\UsersController@getAllUsers');
    Route::get('/api/admin/get/users/inactivity', 'Admin\UsersController@getInactivityUsers');
    Route::get('/api/admin/get/users/activity', 'Admin\UsersController@getActivityUsers');
    Route::post('/api/admin/get/users/search', 'Admin\UsersController@getUsersBySearch');
    Route::post('/api/admin/get/users/invoice', 'Admin\UsersController@getUserInvoice');
    Route::delete('/api/admin/delete/users/{id}', 'Admin\UsersController@delete');
    Route::get('/api/admin/get/user/details/{id}', 'Admin\UsersController@getUserDetails');
    Route::post('/api/admin/update/user', 'Admin\UsersController@update');
    Route::post('/api/admin/create/user', 'Admin\UsersController@create');

    // Admins users manage
    Route::get('/api/admin/get/settings/admins/users', 'Admin\AdminsUsersController@getAllAdmins');
    Route::delete('/api/admin/setting/delete/admin/user/{id}', 'Admin\AdminsUsersController@delete');
    Route::post('/api/admin/new/settings/admin/user', 'Admin\AdminsUsersController@create');
    Route::get('/api/admin/get/settings/user/details/{id}', 'Admin\AdminsUsersController@getAdminDetails');
    Route::post('/api/admin/settings/users/image', 'Admin\AdminsUsersController@updateImage');
    Route::post('/api/admin/update/settings/user', 'Admin\AdminsUsersController@updateDetails');


    // Theme manage
    Route::get('/api/admin/settings/theme', 'Admin\PluginController@get');
    Route::post('/api/admin/settings/theme/upload', 'Admin\PluginController@upload');
    Route::post('/api/admin/settings/theme/set', 'Admin\PluginController@set');
    Route::delete('/api/admin/settings/theme/delete/{id}', 'Admin\PluginController@delete')->where('id', '^[0-9]');

    // Footer manage
    Route::get('/api/admin/get/settings/appearance/footer', 'Admin\AppearancesController@get');
    Route::post('/api/admin/update/settings/appearance/footer', 'Admin\AppearancesController@update');

    // Tmdb manage
    Route::get('/api/admin/get/settings/tmdb', 'Admin\TmdbController@get');
    Route::post('/api/admin/update/settings/tmdb', 'Admin\TmdbController@update');

    // Transcoder manage
    Route::get('/api/admin/get/settings/transcoder', 'Admin\TranscoderController@get');
    Route::post('/api/admin/update/settings/transcoder', 'Admin\TranscoderController@update');
    Route::post('/api/admin/update/settings/transcoder/watermark', 'Admin\TranscoderController@uploadWatermark');
    Route::get('/api/admin/remove/settings/transcoder/watermark', 'Admin\TranscoderController@removeWatermark');

    // Profile manage
    Route::get('/api/admin/profile', 'Admin\ProfileController@getUser');
    Route::post('/api/admin/profile/image', 'Admin\ProfileController@updateImage');
    Route::post('/api/admin/profile/details', 'Admin\ProfileController@updateDetails');
    Route::post('/api/admin/profile/password', 'Admin\ProfileController@passwordUpdate');

    // Braintree manage
    Route::get('/api/admin/get/braintree/plans', 'Admin\BraintreeController@getAllPlans');
    Route::get('/api/admin/get/braintree/coupons', 'Admin\BraintreeController@getCoupons');
    Route::delete('/api/admin/delete/braintree/coupon/{id}', 'Admin\BraintreeController@deleteCoupon');
    Route::post('/api/admin/create/braintree/coupon', 'Admin\BraintreeController@createCoupon');
    Route::post('/api/admin/update/braintree/plans', 'Admin\BraintreeController@activePlan');
    Route::post('/api/admin/create/braintree/plan', 'Admin\BraintreeController@createPlan');
    Route::get('/api/admin/get/braintree/plan/{id}', 'Admin\BraintreeController@retrievePlan');
    Route::post('/api/admin/update/braintree/plan', 'Admin\BraintreeController@updatePlan');
    Route::get('/api/admin/update/braintree/payment/status', 'Admin\BraintreeController@DisabledPayment');

    // Support manage
    Route::get('/api/admin/get/support', 'Admin\SupportController@getAllRequests');
    Route::get('/api/admin/get/support/request/{id}', 'Admin\SupportController@getRequest');
    Route::get('/api/admin/get/support/open', 'Admin\SupportController@getOpenRequests');
    Route::get('/api/admin/get/support/closed', 'Admin\SupportController@getClosedRequests');
    Route::post('/api/admin/create/support/request/reply', 'Admin\SupportController@submitReply');
    Route::put('/api/admin/update/support/request/status/{id}', 'Admin\SupportController@updateStatus');
    Route::delete('/api/admin/delete/support/request/{id}', 'Admin\SupportController@deleteRequest');
    Route::post('/api/admin/get/support/search', 'Admin\SupportController@search');

    // File Manager
    Route::get('/api/admin/get/filemanager', 'Admin\FileManagerController@getAllFolder');
    Route::post('/api/admin/get/filemanager/folder', 'Admin\FileManagerController@getInsideFolder');
    Route::post('/api/admin/delete/filemanager', 'Admin\FileManagerController@deleteFileAndFolder');
    Route::post('/api/admin/download/filemanager', 'Admin\FileManagerController@downloadFile');
    Route::post('/api/admin/get/filemanager/info', 'Admin\FileManagerController@getFileInfo');
    Route::post('/api/admin/create/filemanager/folder', 'Admin\FileManagerController@createNewFolder');
    Route::post('/api/admin/delete/filemanager/folder', 'Admin\FileManagerController@deleteFolder');
    Route::post('/api/admin/rename/filemanager/any', 'Admin\FileManagerController@renameFolderAndFile');
    Route::post('/api/admin/upload/filemanager/files', 'Admin\FileManagerController@uploadFiles');

    // Categories
    Route::get('/api/admin/get/categories', 'Admin\CategoriesController@getAllCategories');
    Route::get('/api/admin/get/category/{id}', 'Admin\CategoriesController@getCategory');
    Route::post('/api/admin/create/category', 'Admin\CategoriesController@createCategory');
    Route::post('/api/admin/update/category/{id}', 'Admin\CategoriesController@updateCategory');
    Route::post('/api/admin/update/active/category', 'Admin\CategoriesController@activeCategory');
    Route::delete('/api/admin/delete/category/{id}', 'Admin\CategoriesController@deleteCategory');
    Route::get('/api/admin/get/categories/sort/{id}', 'Admin\CategoriesController@getCategoryBySort');

});
