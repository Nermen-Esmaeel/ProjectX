<?php

namespace App\Http\Controllers;

use App\Models\Subtask;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SubtaskController extends Controller
{
    public function subtasks()
    {
        $subtasks = Subtask::all();
        return response()->json($subtasks);
    }

    public function task_subtask($id)
    {
        $task = Task::where("id", $id)->first();

        $subtasks = $task->subtask;
        return response()->json($subtasks);
    }

    public function GetUsers()

    {
        $users = User::all();

        foreach ($users as $user) {
            $users_name[] = $user->first_name . " " . $user->last_name;
        }

        return response()->json($users_name);
    }


    public function store_subtask(Request $request, $id)


    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|numeric|gt:0|integer|exists:tasks,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        } else {
            try {
                $validatedData = $request->validate([
                    'title' => 'required|string|max:255',
                    //"type" => 'required|string|max:255',
                    'status' => 'required|in:Pending,Canceled,Completed,OnProgress,OnHold',
                    'priority' => 'required|in:low,high',
                    "start_date" => 'required|date|date_format:Y-m-d',
                    'end_date' => 'required|date|date_format:Y-m-d',
                    //'time_spent' => 'required|date',
                    'description' => 'required|string|max:255',
                    'sub_task_attch_link' => 'required|string|max:255',

                    //'project_id' => 'required|numeric|gt:0|integer|exists:projects,id',
                    'user_id' => 'required|numeric|gt:0|integer|exists:users,id',
                    //'task_id' => 'required|numeric|gt:0|integer|exists:tasks,id',
                ]);

                $subtask = new Subtask();
                $subtask->title = $validatedData['title'];
                //$task->type = $validatedData['type'];
                $subtask->start_date = $validatedData['start_date'];
                $subtask->end_date = $validatedData['end_date'];
                $subtask->description = $validatedData['description'];
                $subtask->priority = $validatedData['priority'];
                $subtask->status = $validatedData['status'];
                //$task->project_id = $validatedData['project_id'];
                $subtask->owner_id = $request->user()->id;
                $subtask->sub_task_attch_link = $validatedData['sub_task_attch_link'];
                $subtask->task_id = $id;
                $subtask->user_id = $validatedData['user_id'];


                $subtask->save();

                return response()->json(['message' => 'Record created successfully', $subtask], 200);
            } catch (ValidationException $e) {
                return response()->json(['message' => 'Validation error', 'errors' => $e->errors()], 422);
            }
        }
    }

    public function show_subtask($id)
    {
        $subtask = Subtask::find($id);
        return response()->json($subtask);
    }

    public function update_subtask(Request $request, $id)
    {

        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                //"type" => 'required|string|max:255',
                'status' => 'required|in:Pending,Canceled,Completed,OnProgress,OnHold',
                'priority' => 'required|in:low,high',
                "start_date" => 'required|date|date_format:Y-m-d',
                'end_date' => 'required|date|date_format:Y-m-d',
                //'time_spent' => 'required|date',
                'description' => 'required|string|max:255',
                'sub_task_attch_link' => 'required|string|max:255',

                //'project_id' => 'required|numeric|gt:0|integer|exists:projects,id',
                'user_id' => 'required|numeric|gt:0|integer|exists:users,id',
                'task_id' => 'required|numeric|gt:0|integer|exists:tasks,id',
            ]);


            $subtask = Subtask::findOrFail($id);

            $subtask->title = $validatedData['title'];
            //$task->type = $validatedData['type'];
            $subtask->start_date = $validatedData['start_date'];
            $subtask->end_date = $validatedData['end_date'];
            $subtask->description = $validatedData['description'];
            $subtask->priority = $validatedData['priority'];
            $subtask->status = $validatedData['status'];
            //$task->project_id = $validatedData['project_id'];
            $subtask->sub_task_attch_link = $validatedData['sub_task_attch_link'];
            $subtask->task_id = $validatedData['task_id'];
            $subtask->user_id = $validatedData['user_id'];


            $subtask->save();

            return response()->json(['message' => 'Record updated successfully', $subtask], 200);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation error', 'errors' => $e->errors()], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Record not found'], 404);
        }
    }



    public function delete_subtask($id)
    {

        $subtask = Subtask::find($id);
        if (!$subtask) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        if ($subtask->delete()) {
            return response()->json(['message' => 'Record deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'Failed to delete record'], 500);
        }
    }
}
