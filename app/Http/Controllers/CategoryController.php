<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Task;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    // all categories get function start
    public function index() {
        $data = Category::paginate(10);
        if($data){
            
            return response()->json([
                'status' => 'success',
                'message' => 'Category data get successfully!',
                'data' => $data,
            ], 200);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Category data not found!',
            ], 422);
        }
    }
    // all categories get function end

    // category create function start
    public function save(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $category = new Category();
        $category->name = $request->name;
        if($category->save()){
            return response()->json([
                'status' => 'success',
                'message' => 'Category saved successfully!',
            ], 200);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!',
            ], 422);
        }
    }
    // category create function end

    // category update function start
    public function update(Request $request,$id =0){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $category = Category::find($id);
        $category->name = $request->name;
        if($category->save()){
            return response()->json([
                'status' => 'success',
                'message' => 'Category updated successfully!',
            ], 200);
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong!',
            ], 422);
        }
    }
    // category update function end

    // category delete function start
    public function delete($id =0) {

        $category = Category::where('id',$id)->first();
        if($category){
            $category_exists =Task::where("category_id",$id)->exists();
            if($category_exists){
                return response()->json([
                    'status' => 'error',
                    'message' => 'This category assign to the task!',
                ], 422);
            }else{
                if($category->delete()){
                    return response()->json([
                        'status' => 'success',
                        'message' => 'Category deleted successfully!',
                    ], 200);
                }else{
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Something went wrong!',
                    ], 422);
                }
            }
        }else{
            return response()->json([
                'status' => 'error',
                'message' => 'Category not found!',
            ], 422);
        }
    }
    // category delete function end

}
