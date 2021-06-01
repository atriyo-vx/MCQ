<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MCQ;
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

Route::get('/', [MCQ::class, 'index'])->name('index');

Route::get('list', [MCQ::class, 'list'])->name('list');

Route::any('add', [MCQ::class, 'add'])->name('add');

Route::any('edit/{id?}', [MCQ::class, 'edit'])->name('edit');

Route::any('addoptions/{id?}', [MCQ::class, 'addoptions'])->name('addoptions');

Route::get('delete/{id}', [MCQ::class, 'delete'])->name('delete');

Route::post('quiz', [MCQ::class, 'quiz'])->name('quiz');
