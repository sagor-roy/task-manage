<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Project::with('task')->get();
        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:projects',
            'description' => 'required',
            'img' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->getMessageBag(),
            ]);
        }

        if($request->hasFile('img')) {
            $file = $request->file('img');
            $filename = substr(md5(time()), 0, 15).'.'.$file->getClientOriginalExtension();
            $file->move('upload/',$filename);
        }

        try {
            Project::create([
                'name' => $request->input('name'),
                'desc' => $request->input('description'),
                'img' =>  $filename,
            ]);
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
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Project::find($id);
        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->getMessageBag(),
            ]);
        }

        $input = [
            'name' => $request->input('name'),
            'desc' => $request->input('description')
        ];

        try {
            Project::find($id)->update($input);
            return response()->json([
                'status' => 200,
                'message' => 'Data Updated Successful'
            ]);
        } catch (Exception $error) {
            return response()->json([
                'status' => 500,
                'message' => $error->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Project::find($id)->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Data Deleted Successful'
            ]);
        } catch (Exception $error) {
            return response()->json([
                'status' => 500,
                'message' => $error->getMessage()
            ]);
        }
    }
}
