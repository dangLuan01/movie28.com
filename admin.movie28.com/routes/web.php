<?php

use App\Http\Controllers\Collection\CollectionController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Elasticsearch\ElasticsearchController;
use App\Http\Controllers\Movie\EpisodeController;
use App\Http\Controllers\Movie\ProductController;
use App\Http\Controllers\Movie\GenreController;
use App\Http\Controllers\Theme\ThemeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;




//Auth::routes(['verify' => true]);

Route::middleware(['auth:web'])->group(function () {
    // Route::resource('movie/article', 'Flight\ArticleController', ['as' => 'flight']);
    // Route::get('flight/article/status/{status}/{id}', [ArticleController::class, 'status'])->name('flight.article.status');
    // Route::post('flight/article/confirm-delete', [ArticleController::class, 'confirmDelete'])->name('flight.article.confirm-delete');
});
//Movie
Route::get('movie/product/crawler',[ProductController::class, 'crawler'])->name('movie.product.crawler');
Route::post('movie/product/store-crawler', [ProductController::class, 'storeCrawler'])->name('movie.product.store-crawler');
Route::post('movie/product/crawler-data', [ProductController::class, 'crawlerData'])->name('movie.product.crawler-data');
Route::resource('movie/product', ProductController::class, ['as' => 'movie']);
Route::get('movie/product/status/{status}/{id}', [ProductController::class, 'status'])->name('movie.product.status');
//Episode
Route::post('movie/episode/crawler-data', [EpisodeController::class, 'crawlerData'])->name('movie.episode.crawler-data');
Route::get('movie/episode/crawler',[EpisodeController::class, 'crawler'])->name('movie.episode.crawler');
Route::post('movie/episode/get-episode', [EpisodeController::class, 'getEpisode'])->name('movie.episode.get-episode');
Route::post('movie/episode/store-crawler', [EpisodeController::class, 'storeCrawler'])->name('movie.episode.store-crawler');
Route::resource('movie/episode',EpisodeController::class,['as' => 'movie']);
//Genre
Route::resource('movie/genre', GenreController::class, ['as' => 'movie']);
Route::get('movie/genre/status/{status}/{id}', [GenreController::class, 'status'])->name('movie.genre.status');

//Collection
Route::resource('movie/collection', CollectionController::class, ['as' => 'movie']);
Route::get('movie/collection/status/{status}/{id}', [CollectionController::class, 'status'])->name('movie.collection.status');
// Auth::routes();
//Theme
Route::resource('movie/theme', ThemeController::class, ['as' => 'movie']);
Route::get('movie/theme/status/{status}/{id}', [ThemeController::class, 'status'])->name('movie.theme.status');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
//Route::post('/get-movie-tmdb', [DashboardController::class, 'getMovieTmdb'])->name('get-movie-tmdb');
//Route::post('/save-auto-movie', [DashboardController::class, 'saveAutoMovie'])->name('save-auto-movie');
// TEST
Route::get('/test', [DashboardController::class, 'test'])->name('test');


Route::get('/es', [ElasticsearchController::class, 'search']);