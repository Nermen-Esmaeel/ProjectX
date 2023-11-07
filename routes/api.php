<?php

use App\Http\Controllers\TaskController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


/* this resource has functions :

1- index() will return all tasks for all projects.
2- show($id) will return task related to specific "id".
3- update(Request $request, $id) will update specific task.
4- destroy($id) will delete specific task. 

*/
Route::apiResource('tasks', TaskController::class);

// this route to show tasks related to specific project.
Route::get("/project/{id}", [TaskController::class, "project_task"]);

// this route to show all users (full name) in order to choose one of them as assignee..
Route::get("create_task", [TaskController::class, "create_task"]);

//this route to store a task related to specific project by passing the project_id..
Route::post("store_task/{id}", [TaskController::class, "store_task"]);
