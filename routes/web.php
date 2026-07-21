<?php

use Illuminate\Support\Facades\Route;
use App\Models\Settings;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TradeController;
use App\Http\Controllers\CryptoPaymentController;
use Illuminate\Http\Request;
use App\Http\Controllers\TradingPairsController;
use Illuminate\Support\Facades\Mail;

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
require __DIR__.'/home.php';
require __DIR__.'/admin.php';
require __DIR__.'/user.php';


// Route::group(['prefix' => 'payment'], function () {
// Route::get('/', [PaymentController::class, 'showPaymentForm'])->name('payment.form');
//     Route::post('/create', [PaymentController::class, 'createPayment'])->name('payment.create');
//     Route::get('/success', [PaymentController::class, 'paymentSuccess'])->name('payment.success');
//     Route::get('/cancel', [PaymentController::class, 'paymentCancel'])->name('payment.cancel');
//     Route::get('/partial/{order_id}', [PaymentController::class, 'paymentPartial'])->name('payment.partial');
//     Route::post('/webhook', [PaymentController::class, 'handleWebhook'])->name('payment.webhook');
// });


Route::group(['middleware' => ['auth:admin', 'issuperadmin']], function () {
    Route::get('/admin/user-trades/{user}', [TradingPairsController::class, 'viewUserTrades'])->name('admin.user-trades');
    Route::delete('/admin/user-trades/{investment}', [TradingPairsController::class, 'deleteUserTrade'])->name('admin.user-trades.delete');
});

Route::get('/payment', function () {
    return view('payment.form');
})->middleware('auth')->name('payment.form');

Route::post('/crypto-pay', [CryptoPaymentController::class, 'create'])->name('crypto.create');
Route::post('/crypto-callback', [CryptoPaymentController::class, 'callback'])->name('crypto.callback');

// Route::get('/trade', [TradeController::class, 'index'])->name('trade');
// Route::post('/trade/execute', [TradeController::class, 'executomoyemi760e'])->name('trade.execute');

// Route::get('/trade', [TradeController::class, 'index'])->name('trade');
// Route::post('/trade/execute', [TradeController::class, 'executeTrade'])->name('trade.execute');
// Route::get('/api/price/{symbol}', [TradeController::class, 'getPrice']);

Route::middleware(['isadmin'])->prefix('admin')->name('admin.')->group(function () {
    // Route::match(['get', 'post'], '/trading-pairs', [TradeController::class, 'managePairs'])->name('trading-pairs');

    // Route::get('/trading-pairs', [TradeController::class, 'managePairs'])->name('trading-pairs');
    // Route::post('/trading-pairs', [TradeController::class, 'storeTradingPair'])->name('store-trading-pairs');

    // Route::patch('/trading-pairs/toggle/{id}', [TradeController::class, 'togglePair'])->name('trading-pairs.toggle');
});
Route::get('/api/price/{symbol}', [TradeController::class, 'getApiPrice'])->name('api.price');
Route::get('/api/test-pairs', [TradeController::class, 'testPairs'])->name('api.test-pairs');

Route::patch('/trade/{id}/close', [TradeController::class, 'closeTrade'])->name('trade.close')->middleware('auth');


// In routes/web.php
Route::get('/test-email', function () {
    try {
        Mail::raw('This is a test email from Laravel', function ($message) {
            $message->to('test@example.com')
                    ->subject('Laravel Mail Test');
        });
        return 'Email sent successfully!';
    } catch (Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});

// Route::middleware(['auth'])->group(function () {
Route::get('/trading-pairs/{tradingPair}', [TradingPairsController::class, 'showPair'])->name('trading.pair.show');

Route::get('/trading-pairs/{tradingPair}/chart-feed', [TradingPairsController::class, 'chartFeed'])
    ->name('user.trading-pairs.chart-feed');

Route::get('trading-pairs/{tradingPair}/invest', [TradingPairsController::class, 'invest'])->name('user.trading-pairs.invest');


Route::post('trading-pairs/{tradingPair}/invest', [TradingPairsController::class, 'storeInvestment'])->name('user.trading-pairs.store-investment');




// });

//activate and deactivate Online Trader
Route::any('/activate', function () {
    return view('activate.index', [
        'settings' => Settings::where('id', '1')->first(),
    ]);
});

Route::any('/revoke', function () {
    return view('revoke.index');
});



// Route::get('/login', function () {
//     return redirect('home', 301);
// });
