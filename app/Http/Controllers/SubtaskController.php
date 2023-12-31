<?php

namespace App\Http\Controllers;

use App\Models\{Subtask,Attachment};
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class SubtaskController extends Controller
{
    public function __construct()
    {
       $this->middleware('auth');
    }

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
                    'status' => 'required|in:Pending,Canceled,Completed,OnProgress,OnHold',
                    'priority' => 'required|in:low,high',
                    'start_date' => 'required|date|date_format:Y-m-d',
                    'end_date' => 'required|date|date_format:Y-m-d',
                    //'time_spent' => 'required|date',
                    'attachment_links.*' => 'nullable|file|mimes:jpg,jpeg,bmp,png,doc,docx,pdf,xlx,csv',
                    'description' => 'required|string|max:255',
                    'user_id' => 'required|numeric|gt:0|integer|exists:users,id',
                    'comment' => 'required|string|max:255',

                ]);

                $subtask = new Subtask();
                $subtask->title = $validatedData['title'];
                $subtask->start_date = $validatedData['start_date'];
                $subtask->end_date = $validatedData['end_date'];
                $subtask->desc = $validatedData['description'];
                $subtask->priority = $validatedData['priority'];
                $subtask->status = $validatedData['status'];
                $subtask->owner_id = $request->user()->id;
                $subtask->task_id = $id;
                $subtask->user_id = $validatedData['user_id'];

                $subtask->save();

                if($request->hasFile('attachment_links')){
                    foreach($request->file('attachment_links') as $attachment_links) {
                        $file_name = $attachment_links->getClientOriginalName();
                        $file_to_store = 'subtask_file' . '_' . time().$file_name;
                        $attachment_links->storeAs('public/' . 'subtask_file', $file_to_store);
                        $path ='subtask_file/'.$file_to_store;

                        $attachment = Attachment::create([
                            'attach_link' => $path,
                        ]);

                        $subtask->attachments()->attach($attachment->id);

                    }

                }


                if($request->comment){
                    foreach($request->file('attachment_links') as $attachment_links) {
                        $file_name = $attachment_links->getClientOriginalName();
                        $file_to_store = 'subtask_file' . '_' . time().$file_name;
                        $attachment_links->storeAs('public/' . 'subtask_file', $file_to_store);
                        $path ='subtask_file/'.$file_to_store;

                        $attachment = Attachment::create([
                            'attach_link' => $path,
                        ]);

                        $subtask->attachments()->attach($attachment->id);

                    }

                }k-

                $subtask = Subtask::find($subtask->id)->orderBy('id', 'Desc')->with('attachments')->first();
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
                'status' => 'required|in:Pending,Canceled,Completed,OnProgress,OnHold',
                'priority' => 'required|in:low,high',
                "start_date" => 'required|date|date_format:Y-m-d',
                'end_date' => 'required|date|date_format:Y-m-d',
                'description' => 'required|string|max:255',
                'attachment_links.*' => 'nullable|file|mimes:jpg,jpeg,bmp,png,doc,docx,pdf,xlx,csv',
                'user_id' => 'required|numeric|gt:0|integer|exists:users,id',
                'task_id' => 'required|numeric|gt:0|integer|exists:tasks,id',

            ]);


            $subtask = Subtask::findOrFail($id);

            $subtask->title = $validatedData['title'];
            $subtask->start_date = $validatedData['start_date'];
            $subtask->end_date = $validatedData['end_date'];
            $subtask->desc = $validatedData['description'];
            $subtask->priority = $validatedData['priority'];
            $subtask->status = $validatedData['status'];
            $subtask->task_id = $validatedData['task_id'];
            $subtask->user_id = $validatedData['user_id'];


            $subtask->save();

            if($request->hasFile('attachment_links')){
                foreach($request->file('attachment_links') as $attachment_links){

                    $file_name = $attachment_links->getClientOriginalName();
                    $file_to_store = 'subtask_file' . '_' . time().$file_name;
                    $attachment_links->storeAs('public/' . 'subtask_file', $file_to_store);
                    $path ='subtask_file/'.$file_to_store;

                    $attachment = Attachment::create([
                        'attach_link' => $path,
                    ]);

                    $subtask->attachments()->sync($attachment->id);

                }

            }


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
