<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\user\Auth\UserController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SiteController;
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
/*
Route::middleware('auth')->group(function(){
    Route::resource('todo', TodoController::class);
    Route::put('todos/complete/{todo}', [TodoController::class, 'complete'])->name('todo.complete');
    Route::delete('todos/incomplete/{todo}', [TodoController::class, 'incomplete'])->name('todo.incomplete');
    });
*/


Route::namespace ('App\Http\Controllers')->controller('LangController')->group(function () {
    //Route::get('cron', 'cron')->name('cron');
    Route::get('lang/home','index');
    Route::get('lang/change', 'change')->name('changeLang');
});

Route::get('/', function () {
    return view('welcome');
});



Route::get('/testphp', function () {
    return view('testphp');
});


//Route::get('user', [UserController::class, 'index']);

//Auth::routes();

//Route::get('home', [HomeController::class, 'index'])->name('home');

/*
Route::middleware('auth')->group(function(){
    Route::resource('product', ProductController::class);
    });
    


Route::namespace ('App\Http\Controllers\User\Auth')->name('user.')->group(function () {

    Route::controller('LoginController')->group(function () {
        Route::get('/login', 'showLoginForm')->name('login');
        Route::post('/login', 'login');
        Route::get('logout', 'logout')->name('logout');
    });

    Route::controller('RegisterController')->group(function () {
        Route::get('register', 'showRegistrationForm')->name('register');
        Route::post('register', 'register')->middleware('registration.status');
        Route::post('check-mail', 'checkUser')->name('checkUser');
    });

    Route::controller('ForgotPasswordController')->prefix('password')->name('password.')->group(function () {
        Route::get('reset', 'showLinkRequestForm')->name('request');
        Route::post('email', 'sendResetCodeEmail')->name('email');
        Route::get('code-verify', 'codeVerify')->name('code.verify');
        Route::post('verify-code', 'verifyCode')->name('verify.code');
    });
    Route::controller('ResetPasswordController')->group(function () {
        Route::post('password/reset', 'reset')->name('password.update');
        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');
    });
});

*/

Route::get('/clear', function () {
    \Illuminate\Support\Facades\Artisan::call('optimize:clear');
});

Route::namespace ('App\Http\Controllers')->controller('CronController')->group(function () {
    Route::get('cron', 'cron')->name('cron');
});

// User Support Ticket
Route::namespace ('App\Http\Controllers')->controller('TicketController')->prefix('ticket')->name('ticket.')->group(function () {
    Route::get('/', 'supportTicket')->name('index');
    Route::get('new', 'openSupportTicket')->name('open');
    Route::post('create', 'storeSupportTicket')->name('store');
    Route::get('view/{ticket}', 'viewTicket')->name('view');
    Route::post('reply/{ticket}', 'replyTicket')->name('reply');
    Route::post('close/{ticket}', 'closeTicket')->name('close');
    Route::get('download/{ticket}', 'ticketDownload')->name('download');
});





Route::namespace ('App\Http\Controllers')->group(function () {
        Route::controller('SiteController')->group(function () {
            Route::get('contact', 'contact')->name('contact');
            Route::post('/contact', 'contactSubmit');

            Route::post('/subscribe', 'subscribe')->name('subscribe.post');

            Route::get('/change/{lang?}', 'changeLanguage')->name('lang');

            Route::get('cookie-policy', 'cookiePolicy')->name('cookie.policy');

            Route::get('/cookie/accept', 'cookieAccept')->name('cookie.accept');

            Route::get('games', 'games')->name('games');

            Route::get('blog', 'blog')->name('blog');
            Route::get('blog/{slug}/{id}', 'blogDetails')->name('blog.details');

            Route::get('policy/{slug}/{id}', 'policyPages')->name('policy.pages');

            Route::get('placeholder-image/{size}', 'placeholderImage')->name('placeholder.image');

            Route::get('/{slug}', 'pages')->name('pages');
            Route::get('/', 'index')->name('home');
        });
});


