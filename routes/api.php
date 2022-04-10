<?php

use App\Http\Controllers\API\ProjectController;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

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

Route::post('access', function (Request $request) {
    $validator = Validator::make($request->all(), [
        'email' => 'required',
        'password' => 'required',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 422,
            'errors' => $validator->getMessageBag(),
        ]);
    }

    $creadential = $request->only(['email','password']);
    
    if(Auth::attempt($creadential)){ 
        $user = Auth::user();
        $token =  $user->createToken('authToken')->accessToken; 
        return response()->json([
            'status' => 200,
            'message' => 'Login Successful',
            'access_token' => $token,
            'user' => $user
        ]);
    } else{ 
        return response()->json([
            'status' => 422,
            'message' => 'Your creadential doesn\'t match our record',
        ]);
    }
});


Route::middleware('auth:api')->group( function () {

    Route::resource('project',ProjectController::class);

    Route::get('task/view/{id}',function($id){
        $data = Task::where('project_id',$id)->get();
        $pro = Project::find($id);
        return response()->json([
            'status' => 200,
            'data' => $data,
            'project' => $pro
        ]);
    });

    Route::get('task/edit/{id}',function($id){
        $data = Task::find($id);
        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    });

    Route::delete('task/{id}',function($id){
        Task::find($id)->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Data Deleted Successful'
        ]);
    });

    Route::post('task/store',function(Request $request){
        $validator = Validator::make($request->all(), [
            'project' => 'required',
            'title' => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->getMessageBag(),
            ]);
        }

        $input = [
            'project_id' => $request->input('project'),
            'title' => $request->input('title'),
            'desc' => $request->input('description')
        ];

        try {
            Task::create($input);
            return response()->json([
                'status' => 200,
                'message' => 'Data Created Successful'
            ]);
        } catch (Exception $error) {
            return response()->json([
                'status' => 500,
                'message' => $error->getMessage()
            ]);
        }
    });

});
