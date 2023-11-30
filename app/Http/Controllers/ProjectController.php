<?php

namespace App\Http\Controllers;

use App\Models\{Project,User};
use Illuminate\Http\Request;
use App\Http\Requests\StoreProject;
use App\Http\Resources\ProjectResource;

class ProjectController extends Controller
{
    public function __construct()
    {
    $this->middleware('auth');
    }

    public function index()
    {
        $projects = Project::with('users')->get();
        return response()->json([
            'status' => 'success',
            'projects' => ProjectResource::collection($projects),
        ], 401);
    }

    public function store(StoreProject $request)
    {

         if ($request->hasFile('image')) {

            $file_name = $request->file('image')->getClientOriginalName();
            $file_to_store = 'project_images' . '_' . time().$file_name;
            $request->file('image')->storeAs('public/' . 'project_images', $file_to_store);
            $path ='storage/project_images/'.$file_to_store;
        }

        $project = Project::create([
            'title' =>  $request->title,
            'type' =>  $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' =>  $request->description,
            'status' =>  $request->status,
            'image' =>  $path,
        ]);
        $project->save();

        //add member for project table
        if ($users = $request->users) {

             $project->users()->attach($users);

        }
        return response()->json([
            'status' => 'success',
            'message' => 'Project created successfully'
        ], 201);

    }


}
