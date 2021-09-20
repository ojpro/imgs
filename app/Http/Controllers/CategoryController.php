<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // If it is a search request
        if ($request->has('search') && !empty($request->search)) {
            // Search in category table
            $categories = Category::select('id','name')->where('name','like', '%'.$request->search.'%')->get();
        } else {
            // Otherwise Select all records
            $categories = Category::select('id','name')->get();
        }
        // Then return them as json response
        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate Creation Request
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|unique:categories,name|max:150|min:3'
        ]);
    
        // If validation fails return json error
        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()]);
        }

        // Store new Category Record
        Category::create($request->all());

        // Return a json response if the new category added
        return response()->json(['success' => 'Category added successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        // Look for the category if is exists
        $category = Category::find($id);

        // if not return an error response
        if (!$category) {
            return response()->json(['error' => 'This category does not exists.']);
        }
        // Otherwise return the category
        return $category;
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
        // Look for the category if is exists
        $category = Category::find($id);

        // if not return an error response
        if (!$category) {
            return response()->json(['error' => 'This category does not exists.']);
        }

        // validate the records
        $validation = Validator::make($request->all(), [
            'name' => 'required|string|unique:categories,name,' . $id . ',id|max:150|min:3'
        ]);
    
        // If validation fails return json error
        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()]);
        }

        // Update the category
        $category->update($request->all());

        // Then return a success response
        return response()->json(['success' => 'Category updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Look for the category if is exists
        $category = Category::find($id);

        // if not return an error response
        if (!$category) {
            return response()->json(['error' => 'This category does not exists.']);
        }

        // Finlay delete it.
        $category->delete();

        //  Return a success response
        return response()->json(['success' => 'Category deleted successfully.']);
    }
}
