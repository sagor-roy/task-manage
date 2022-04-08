<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Exception;
use Illuminate\Http\Request;

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
        $request->validate([
            'name' => 'required|unique:projects',
            'description' => 'required'
        ]);

        $input = [
            'name' => $request->input('name'),
            'desc' => $request->input('description')
        ];

        try {
            Project::create($input);
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
        $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);

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
