<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SubtaskController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AttachmentController;

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

/* ************************* Admin profile, login, logout********************************* */
Route::prefix('Admin')->group( function(){
    Route::post('login',[AdminAuthController::class ,'login']);
    Route::get('profile',[AdminAuthController::class ,'userProfile']);
    Route::post('logout',[AdminAuthController::class ,'logout']);
});


/* *************************create user, login, logout********************************* */

Route::post("/register", [AuthController::class, "register"]);
Route::post("/login", [AuthController::class, "login"]);
Route::post("/logout", [AuthController::class, "logout"])->middleware('auth:api');


/* *************************create user, delete user, update user , show users********************************* */

    Route::get('users',[UserController::class ,'index']);
    Route::get('users/{id}',[UserController::class ,'show']);
    Route::post('users',[UserController::class ,'store']);
    Route::put('users/{id}',[UserController::class ,'update']);
    Route::delete('users/{id}',[UserController::class ,'destroy']);

    /* *************************create project, delete project, update project , show project********************************* */

    Route::get('projects',[ProjectController::class ,'index']);
    Route::post('projects',[ProjectController::class ,'store']);
    Route::put('projects/{id}',[ProjectController::class ,'update']);
    Route::delete('projects/{id}',[ProjectController::class ,'destroy']);


/* *************************Task********************************* */

// return all tasks for all projects.
Route::get("tasks", [TaskController::class, "tasks"]);

// return task related to specific "id".
Route::get("show_task/{id}", [TaskController::class, "show_task"]);

//update specific task.
Route::put("update_task/{id}", [TaskController::class, "update_task"]);

//delete specific task.
Route::delete("delete_task/{id}", [TaskController::class, "delete_task"]);

// this route to show tasks related to specific project.
Route::get("/project/{id}", [TaskController::class, "project_task"]);

// this route to show all users (full name) in order to choose one of them as assignee..
Route::get("getUsers", [TaskController::class, "GetUsers"]);

//this route to store a task related to specific project by passing the project_id..
Route::post("store_task/{id}", [TaskController::class, "store_task"]);


/* *************************Subtask********************************* */

// return all subtasks for all projects.
Route::get("subtasks", [SubtaskController::class, "subtasks"]);

// return subtask related to specific "id".
Route::get("show_subtask/{id}", [SubtaskController::class, "show_subtask"]);

//update specific subtask.
Route::put("update_subtask/{id}", [SubtaskController::class, "update_subtask"]);

//delete specific subtask.
Route::delete("delete_subtask/{id}", [SubtaskController::class, "delete_subtask"]);

// this route to show tasks related to specific project.
Route::get("/task/{id}", [SubtaskController::class, "task_subtask"]);

// this route to show all users (full name) in order to choose one of them as assignee..
Route::get("GetUsers", [SubtaskController::class, "GetUsers"]);

//this route to store a task related to specific project by passing the project_id..
Route::post("store_subtask/{id}", [SubtaskController::class, "store_subtask"]);


/* *************************comment********************************* */

// return all comments for all projects.
Route::get("comments", [CommentController::class, "comments"]);

// return comment related to specific "id".
Route::get("show_comment/{id}", [CommentController::class, "show_comment"]);

//update specific comment.
Route::put("update_comment/{id}", [CommentController::class, "update_comment"]);

//delete specific comment.
Route::delete("delete_comment/{id}", [CommentController::class, "delete_comment"]);

// this route to show comments related to specific subtask.
Route::get("/comment/{id}", [CommentController::class, "subtask_comment"]);


//this route to store a comment related to specific subtask by passing the subtask_id..
Route::post("store_comment/{id}", [CommentController::class, "store_comment"]);


/* *************************attachment********************************* */

// return all attachments for all projects.
Route::get("attachments", [AttachmentController::class, "attachments"]);

// return attachment related to specific "id".
Route::get("show_attachment/{id}", [AttachmentController::class, "show_attachment"]);

//update specific attachment.
Route::put("update_attachment/{id}", [AttachmentController::class, "update_attachment"]);

//delete specific attachment.
Route::delete("delete_attachment/{id}", [AttachmentController::class, "delete_attachment"]);

// this route to show attachments related to specific subtask.
Route::get("/attachment/{id}", [AttachmentController::class, "subtask_attachment"]);


//this route to store a attachment related to specific subtask by passing the subtask_id..
Route::post("store_attachment/{id}", [AttachmentController::class, "store_attachment"]);
