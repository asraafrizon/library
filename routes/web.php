<?php

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
Auth::routes();

Route::get('/', function () {
    return view('welcome');
});
Route::get('/home', 'HomeController@index')->name('home');

Route::middleware('auth')->group(function() {

Route::get('/data_inventori', function () {
    return view('inventori');
})->name('data.inventori');
Route::get('/data_koleksi', function () {
    return view('koleksi');
})->name('data.koleksi')->middleware('auth');
Route::get('/data_layanan', function () {
    return view('layanan');
})->name('data.layanan');
Route::resource('koleksis', 'KoleksiController');
Route::resource('inventori', 'InventoriController');
Route::resource('layanan', 'LayananController');
Route::get('koleks', 'dashboardController@koleksi');
Route::get('inven', 'dashboardController@inventori');
Route::get('layan', 'dashboardController@layanan');
Route::get('exportInventori', 'InventoriController@inventoriExport')->name('inventori.export');
Route::post('importInventori', 'InventoriController@inventoriImport')->name('inventori.import');
Route::get('exportKoleksi', 'KoleksiController@koleksiExport')->name('koleksi.export');
Route::post('importKoleksi', 'KoleksiController@koleksiImport')->name('koleksi.import');
Route::get('exportLayanan', 'LayananController@layananExport')->name('layanan.export');
Route::post('importLayanan', 'LayananController@layananImport')->name('layanan.import');
Route::get('/data', function() {return view('export');})->name('data');
Route::get('/dashboard', function () {
    return view('dashboard');
});
});