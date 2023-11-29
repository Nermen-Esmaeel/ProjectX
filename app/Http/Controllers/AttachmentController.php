<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Subtask;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AttachmentController extends Controller
{
    public function attachments()
    {
        $attachments = Attachment::all();
        return response()->json($attachments);
    }

    public function subtask_attachment($id)
    {
        $subtask = Subtask::where("id", $id)->first();

        $attachment = $subtask->attachment;
        return response()->json($attachment);
    }


    public function store_attachment(Request $request, $id)


    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|numeric|gt:0|integer|exists:subtasks,id',
            //'file' => 'required|mimes:doc,docx,pdf,txt,png,jpg,jpeg,gif',

        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        } else {
            try {
                if ($file = $request->file('file')) {
                    $path = $file->store('public/files');
                    //$name = $file->getClientOriginalName();

                    //store your file into directory and db
                    $attachment = new Attachment();
                    //$attachment->name = $file;
                    $attachment->attach_link = $path;
                    $attachment->subtask_id = $id;
                    $attachment->save();

                    return response()->json([
                        "success" => true,
                        "message" => "File successfully uploaded",
                        "file" => $path
                    ]);
                }

                // $result = $request->file("file")->store("files_1");
                // return response(["res" => $result]);
                // $validatedData = $request->validate([
                //     'attach_link' => 'required|string|max:255',

                // ]);


                //$attachment->attach_link = $validatedData['attach_link'];




                //$attachment->save();

                //return response()->json(['message' => 'attachment uploaded successfully', $attachment], 200);
            } catch (ValidationException $e) {
                return response()->json(['message' => 'Validation error', 'errors' => $e->errors()], 422);
            }
        }
    }

    public function show_attachment($id)
    {
        $attachment = Attachment::find($id);
        return response()->json($attachment);
    }



    public function update_attachment(Request $request, $id)
    {

        try {
            $validatedData = $request->validate([
                'attach_link' => 'required|string|max:255',

                'subtask_id' => 'required|numeric|gt:0|integer|exists:subtasks,id',
            ]);


            $attachment = Attachment::findOrFail($id);

            $attachment->attach_link = $validatedData['attach_link'];
            $attachment->subtask_id = $validatedData['subtask_id'];



            $attachment->save();

            return response()->json(['message' => 'Record updated successfully', $attachment], 200);
        } catch (ValidationException $e) {
            return response()->json(['message' => 'Validation error', 'errors' => $e->errors()], 422);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Record not found'], 404);
        }
    }


    public function delete_attachment($id)
    {

        $attachment = Attachment::find($id);
        if (!$attachment) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        if ($attachment->delete()) {
            return response()->json(['message' => 'Record deleted successfully'], 200);
        } else {
            return response()->json(['message' => 'Failed to delete record'], 500);
        }
    }
}
