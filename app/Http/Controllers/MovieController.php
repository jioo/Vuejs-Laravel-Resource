<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Movie;
use App\Events\MovieEvent;
use App\Http\Resources\Movie as MovieResource;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get list of movies with filter & pagination config
        $movies =  Movie::query();
        $movies->orderBy('created_at', 'desc');

        if(!empty($request->search)) {
            $movies->where('title', 'LIKE', '%'.$request->search.'%');
        }

        if (!empty($request->category) && $request->category !== 0) {
            $movies->where('category_id', $request->category);
        }

        $result = $movies->paginate(8);
        return MovieResource::collection($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $upload_file = FALSE;
        // Check if request is a put / post
        $movie = $request->isMethod('put')
            ? Movie::findOrFail($request->id)
            : new Movie;

        // Populate Movie with requests
        $movie->id = $request->input('id');
        $movie->title = $request->input('title');
        $movie->category_id = $request->input('category_id');
        $movie->year = $request->input('year');
        $movie->youtubeId = (!empty($request->input('youtubeId')))
            ? $request->input('youtubeId')
            : '';

        // Handle file request
        // Check if input file has value
        if($request->hasFile('file')) {
            $image = $request->file('file');
            $image_file_name = time().'.'.$image->getClientOriginalExtension();

            // Delete the previous image on update
            if($request->isMethod('put')) {
                $this->unlinkImage($movie->image);
            }

            // Save the new image file name
            $movie->image = $image_file_name;
            $upload_file = TRUE;

        } else if ($request->isMethod('post')) {
            // Set default image if file is empty
            $movie->image = 'default.jpg';
        }

        // Return the response if query has been successful
        if($movie->save()) {
            // Save image to public/files
            if($upload_file) {
                $destinationPath = public_path('/files');
                $image->move($destinationPath, $image_file_name);
            }

            // Trigger an event for notification
            $message = ($request->isMethod('post'))
                ? 'A new Movie has been added: ' . $request->input('title')
                : $request->input('title') . ' has been updated';
            $action = ($request->isMethod('post')) ? 'create': 'update';
            $notification = [
                'action' => $action,
                'type' => 'success',
                'message' => $message
            ];
            event(new MovieEvent($movie, $notification));

            return new MovieResource($movie);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Get single movies
        $movie = Movie::findOrFail($id);
        return new MovieResource($movie);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get singe Movies
        $movie = Movie::findOrFail($id);

        $this->unlinkImage($movie->image);

        // Trigger an event for notification
        $notification = [
            'action' => 'delete',
            'type' => 'error',
            'message' => 'Movie: ' . $movie->title . ' has been removed'
        ];
        event(new MovieEvent($movie, $notification));

        // If delete query has been successful
        if($movie->delete()) {
            return new MovieResource($movie);
        }
    }

    /* ========================================================================= *\
     * Helpers
    \* ========================================================================= */

    /**
     * Delete a specific file in public files path
     * except for default image
     *
     * @param  string  $file_name
     */
    private function unlinkImage($file_name) {
        $path = public_path('/files').'/';
        if($file_name != 'default.jpg') {
            unlink($path . $file_name);
        }
    }
}
