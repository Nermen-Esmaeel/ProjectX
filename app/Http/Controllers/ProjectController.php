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
        $projects = Project::with('users')->latest()->paginate(6);
        return response()->json([
            'status' => 'success',
            'projects' => ProjectResource::collection($projects)->response()->getData(true),
        ], 200);
    }

    public function store(StoreProject $request)
    {
        $project = Project::create([
            'title' =>  $request->title,
            'type' =>  $request->type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' =>  $request->description,
            'status' =>  $request->status,
        ]);
        $project->save();

        if ($request->hasFile('image')) {

            $file_name = $request->file('image')->getClientOriginalName();
            $file_to_store = 'project_images' . '_' . time().$file_name;
            $request->file('image')->storeAs('public/' . 'project_images', $file_to_store);
            $path ='storage/project_images/'.$file_to_store;
            $project->update([
                'image' =>  $path,
            ]);
        }
        //add member for project table
        if ($users = $request->users) {

             $project->users()->attach($users);

        }
        return response()->json([
            'status' => 'success',
             'project' => new ProjectResource($project),
            'message' => 'Project created successfully'
        ], 201);

    }

    public function update(StoreProject $request, $id)
    {

        $project = Project::findOrFail($id);

        $input = $request->input();

        $project->update($input);

         if ($request->hasFile('image')) {

            $file_name = $request->file('image')->getClientOriginalName();
            $file_to_store = 'project_images' . '_' . time().$file_name;
            $request->file('image')->storeAs('public/' . 'project_images', $file_to_store);
            $path ='storage/project_images/'.$file_to_store;

            $project->update([
                'image' => $path,
            ]);
        }

        //add member for project table
        if ($users = $request->users) {

             $project->users()->sync($users);

        }
        return response()->json([
            'status' => 'success',
            'project' => new ProjectResource($project),
            'message' => 'Project updated successfully'
        ], 201);

    }

        //delete an project
        public function destroy($id)
        {
            $project = Project::findOrFail($id);
            $project->delete();
            return response()->json(['status' => 'success','message' => 'the Project deleted successfully'], 200);

        }




}
