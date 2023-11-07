<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::all();
        return response()->json($tasks);
    }

    public function project_task($id)
    {
        $project = Project::where("id", $id)->first();

        $tasks = $project->tasks;
        return response()->json($tasks);
    }


    public function create_task()

    {
        $users = User::all();

        foreach ($users as $user) {
            $users_name[] = $user->first_name . " " . $user->last_name;
        }

        return response()->json($users_name);
    }

    public function store_task(Request $request, $id)


    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|numeric|gt:0|integer|exists:projects,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        } else {
            try {
                $validatedData = $request->validate([
                    'title' => 'required|string|max:255',
                    "type" => 'required|string|max:255',
                    "start_date" => 'required|date',
                    'end_date' => 'required|date',
                    //'time_spent' => 'required|date',
                    'description' => 'required|string|max:255',
                    'priority' => 'required|in:low,high',
                    'status' => 'required|in:Pending,Canceled,Completed,OnProgress,OnHold',
                    //'project_id' => 'required|numeric|gt:0|integer|exists:projects,id',
                    'assign_to' => 'required|numeric|gt:0|integer|exists:projects,id',
                ]);

                $task = new Task;
                $task->title = $validatedData['title'];
                $task->type = $validatedData['type'];
                $task->start_date = $validatedData['start_date'];
                $task->end_date = $validatedData['end_date'];
                $task->description = $validatedData['description'];
                $task->priority = $validatedData['priority'];
                $task->status = $validatedData['status'];
                //$task->project_id = $validatedData['project_id'];
                $task->project_id = $id;
                $task->assign_to = $validatedData['assign_to'];


                $task->save();

                return response()->json(['message' => 'Record created successfully', $task], 200);
            } catch (ValidationException $e) {
                return response()->json(['message' => 'Validation error', 'errors' => $e->errors()], 422);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::find($id);
        return response()->json($task);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        try {
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                "type" => 'required|string|max:255',
                "start_date" => 'required|date|date_format:Y-m-d',
                'end_date' => 'required|date|date_format:Y-m-d',
                //'time_spent' => 'required|date',
                'description' => 'required|string|max:255',
                'priority' => 'required|in:low,high',
                'status' => 'required|in:Pending,Canceled,Completed,OnProgress,OnHold',
                'project_id' => 'required|numeric|gt:0|integer|exists:projects,id',
                'assign_to' => 'required|numeric|gt:0|integer|exists:users,id',
            ]);


            $task = Task::findOrFail($id);

            $task->title = $validatedData['title'];
            $task->type = $validatedData['type'];
            $task->start_date = $validatedData['start_date'];
            $task->end_date = $validatedData['end_date'];
            $task->description = $validatedData['description'];
            $task->priority = $validatedData['priority'];
            $task->status = $validatedData['status'];
            $task->project_id = $validatedData['project_id'];
            $task->assign_to = $validatedData['assign_to'];


            $task->save();

            return response()->json(['message' => 'Record updated successfully', $task], 200);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation error', 'errors' => $e->errors()], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Record not found'], 404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Task $task
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $task = Task::find($id);
        if (!$task) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        if ($task->delete()) {
            return response()->json(['message' => 'Record deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'Failed to delete record'], 500);
        }
    }
}
