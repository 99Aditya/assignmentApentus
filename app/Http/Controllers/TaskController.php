<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    // all tasks get with filter function start
    public function index(Request $request){
        $user_id =Auth::id();
        $data = Task::with('category');
        if($request->search){
            $search = $request->search;
            $data = $data->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            });
        }
        if($request->status){
            $data = $data->where('status',$request->status);
        }
        if($request->category_id){
            $data = $data->where('category_id',$request->category_id);
        }
        if ($request->from_date && $request->to_date) {
            $data = $data->whereBetween('due_date', [$request->from_date, $request->to_date]);
        }
        $data = $data->where('user_id',$user_id)->paginate(10);
        if($data){
            return response()->json([
                'status' => 'success',
                'message' => 'Task data get successfully!',
                'data' => $data,
            ], 200);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Task data not found!',
            ], 422);
        }
    }
    // all tasks get with filter function end

    // task create function start
    public function save(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'due_date' => 'required|date',
            'category_id'=>'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }
        $user_id =Auth::id();

        $task = new Task();
        $task->title = $request->title;
        $task->description = $request->description;
        $task->due_date = $request->due_date;
        $task->user_id = $user_id;
        $task->category_id = $request->category_id;

        if($task->save()){
            return response()->json([
                'status' => 'success',
                'message' => 'Task saved successfully!',
            ], 200);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!',
            ], 422);
        }
    }
    // task create function end

    // task update function start
    public function update(Request $request,$id=0){
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'due_date' => 'required|date',
            'category_id'=>'nullable|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }
        $user_id =Auth::id();

        $task = Task::find($id);
        $task->title = $request->title;
        $task->description = $request->description;
        $task->due_date = $request->due_date;
        $task->status = $request->status;
        $task->user_id = $user_id;
        $task->category_id = $request->category_id;

        if($task->save()){
            return response()->json([
                'status' => 'success',
                'message' => 'Task updated successfully!',
            ], 200);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!',
            ], 422);
        }
    }
    // task update function end

    // single task details get function start
    public function detail($id =0) {
        $data = Task::with('category')->find($id);
        if($data){
            return response()->json([
                'status' => 'success',
                'message' => 'Task detail get successfully!',
                'data' => $data
            ], 200);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Task not found!',
            ], 422);
        }
    }
    // single task details get function end
    
    // task delete function start
    public function delete($id =0) {
        $data = Task::where('id',$id)->first();
        if($data){
            if($data->delete()){
                return response()->json([
                    'status' => 'success',
                    'message' => 'Task deleted successfully!',
                ], 200);
            }else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Something went wrong!',
                ], 422);
            }
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Task not found!',
            ], 422);
        }
    }
    // task delete function end

}
