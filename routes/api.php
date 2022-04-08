<?php

use App\Http\Controllers\API\ProjectController;
use App\Models\Task;
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

Route::resource('project',ProjectController::class);

Route::get('task/view/{id}',function($id){
    $data = Task::where('project_id',$id)->get();
    return response()->json([
        'status' => 200,
        'data' => $data
    ]);
});
