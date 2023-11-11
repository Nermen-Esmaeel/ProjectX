<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Subtask;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CommentController extends Controller
{
    public function comments()
    {
        $comments = Comment::all();
        return response()->json($comments);
    }

    public function subtask_comment($id)
    {
        $subtask = Subtask::where("id", $id)->first();

        $comment = $subtask->comment;
        return response()->json($comment);
    }


    public function store_comment(Request $request, $id)


    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|numeric|gt:0|integer|exists:subtasks,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        } else {
            try {
                $validatedData = $request->validate([
                    'comment' => 'required|string|max:255',
                    'user_id' => 'required|numeric|gt:0|integer|exists:users,id',
                    //'task_id' => 'required|numeric|gt:0|integer|exists:tasks,id',
                ]);

                $comment = new Comment();
                $comment->comment = $validatedData['comment'];
                $comment->subtask_id = $id;
                $comment->user_id = $validatedData['user_id'];


                $comment->save();

                return response()->json(['message' => 'Record created successfully', $comment], 200);
            } catch (ValidationException $e) {
                return response()->json(['message' => 'Validation error', 'errors' => $e->errors()], 422);
            }
        }
    }

    public function show_comment($id)
    {
        $comment = Comment::find($id);
        return response()->json($comment);
    }



    public function update_comment(Request $request, $id)
    {

        try {
            $validatedData = $request->validate([
                'comment' => 'required|string|max:255',
                'user_id' => 'required|numeric|gt:0|integer|exists:users,id',
                'subtask_id' => 'required|numeric|gt:0|integer|exists:subtasks,id',
            ]);


            $comment = Comment::findOrFail($id);

            $comment->comment = $validatedData['comment'];
            $comment->subtask_id = $validatedData['subtask_id'];
            $comment->user_id = $validatedData['user_id'];


            $comment->save();

            return response()->json(['message' => 'Record updated successfully', $comment], 200);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation error', 'errors' => $e->errors()], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Record not found'], 404);
        }
    }


    public function delete_comment($id)
    {

        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        if ($comment->delete()) {
            return response()->json(['message' => 'Record deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'Failed to delete record'], 500);
        }
    }
}
