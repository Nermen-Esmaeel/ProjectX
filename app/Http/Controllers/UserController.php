<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Requests\{UserStore,UserUpdate};

class UserController extends Controller
{

    public function __construct()
    {
    $this->middleware('auth');
    }


   public function index(){

    $users = User::all();
    return response()->json([
        'status' => 'success',
        'users' => UserResource::collection($users),
    ], 200);

   }

   // store user
   public function store(UserStore $request){


        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => $request->password,
            'address' => $request->address,
            'nationality' => $request->nationality,
            'department' => $request->department,
            'designation' => $request->designation,
            'phone' => $request->phone,
            'country' => $request->country,
            'onteak' => $request->onteak,

        ]);

        $user->save();

        if ($request->hasFile('image')) {

            $file_name = $request->file('image')->getClientOriginalName();
            $file_to_store = 'user_images' . '_' . time().$file_name;
            $request->file('image')->storeAs('public/' . 'user_images', $file_to_store);
            $path ='storage/user_images/'.$file_to_store;
            $user->update([
                'image' =>  $path,
            ]);

        }

        return response()->json([
            'status' => 'success',
            'user' => new UserResource($user),
            'message' => 'User created successfully'
        ], 201);

   }

     // update user
     public function update(UserUpdate $request, $id){

        $input = $request->input();

        $user = User::findOrFail($id);

        if($user){

            $user->update($input);

            if ($request->hasFile('image')) {

                $file_name = $request->file('image')->getClientOriginalName();
                $file_to_store = 'user_images' . '_' . time().$file_name;
                $request->file('image')->storeAs('public/' . 'user_images', $file_to_store);
                $path ='storage/user_images/'.$file_to_store;

                $user->update([
                    'image' => $path,
                ]);
            }

            return response()->json(['status' => 'success','user' => new UserResource($user),'message' => 'User Updated successfully'], 201);
        }
        return response()->json(['status' => 'faild','message' => 'the User not found'], 404);
   }


    //delete an User
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user) {

            $user->delete();
            return response()->json(['status' => 'success','message' => 'the User deleted successfully'], 200);
        }

        return response()->json(['status' => 'faild','message' => 'the User not found'], 404);
    }


}
