<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Traits\SaveImage;
use AshAllenDesign\ShortURL\Classes\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Check if it is a search request
        if ($request->has('search') && !empty($request->search)) {
            // Then search in images's label
            $images = Image::where('label', 'like', '%' . $request->search . '%')
                ->orWhere('category_name', 'like', '%' . $request->search . '%')->limit(100)->latest()->get();
        } else {
            // Otherwise fetch all images
            $images = Image::latest()->get();
        }
        // Return as json response
        return response()->json($images);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //TODO: Add max file size after create upload progress bar

        // validate the records
        $validation = Validator::make($request->all(), [
            'label' => 'required|string|max:200|min:3',
            'image' => 'required|image|mimes:png,jpg,jpeg',
            'category' => 'required|string|exists:categories,name|min:3|max:150'
        ]);

        // If validation fails return json error
        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()]);
        }

        // Save the image to the disk
        $path = SaveImage::save($request->file('image'));

        /**
         * Create Short URL for each image
         */
        $short_url = new Builder();
        $shortURLObject = $short_url->destinationUrl(url($path))->make();

        Image::create([
            'label' => $request->label,
            'url' => $shortURLObject->default_short_url,
            'category_name' => $request->category
        ]);

        return response()->json(['success' => 'Image uploaded successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Look for the image if it is exists
        $image = Image::find($id);

        // if not return an error response
        if (!$image) {
            return response()->json(['error' => 'This image does not exists.']);
        }
        // Otherwise return the image
        return $image;
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
        // Look for the image if is exists
        $image = Image::find($id);

        // if not return an error response
        if (!$image) {
            return response()->json(['error' => 'This image does not exists.']);
        }
        // Otherwise validate the records
        $validation = Validator::make($request->all(), [
            'label' => 'required|string|max:200|min:3',
            'category_name' => 'required|string|exists:categories,name|min:3|max:150'
        ]);

        // If validation fails return json error
        if ($validation->fails()) {
            return response()->json(['errors' => $validation->errors()]);
        }

        // Update the image
        $image->update($request->all());

        // Then return a success response
        return response()->json(['success' => 'Image updated successfully.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Look for the image if is exists
        $image = Image::find($id);

        // if not return an error response
        if (!$image) {
            return response()->json(['error' => 'This image does not exists.']);
        }

        // Finlay delete it.
        $image->delete();

        // Return a success response
        return response()->json(['success' => 'Image deleted successfully.']);
    }
}
