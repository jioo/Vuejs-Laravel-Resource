<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Article;
use App\Events\ArticleEvent;
use App\Http\Resources\Article as ArticleResource;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Get list of articles with filter & pagination config
        $articles = new Article();
        $articles->orderBy('created_at', 'desc');
        if($request->has('search')) {
            $result = $articles->where('title', 'LIKE', '%'.$request->search.'%')->paginate(6);
        } else {
            $result = $articles->paginate(6);
        }

        // Return a collection with resource
        return ArticleResource::collection($result);
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
        $article = $request->isMethod('put')
            ? Article::findOrFail($request->id)
            : new Article;

        // Populate article with requests
        $article->id = $request->input('id');
        $article->title = $request->input('title');
        $article->body = $request->input('body');

        // Handle file request
        // Check if input file has value
        if($request->hasFile('file')) {
            $image = $request->file('file');
            $image_file_name = time().'.'.$image->getClientOriginalExtension();

            // Delete the previous image on update
            if($request->isMethod('put')) {
                $this->unlinkImage($article->image);
            }

            // Save the new image file name
            $article->image = $image_file_name;
            $upload_file = TRUE;

        } else if ($request->isMethod('post')) {
            // Set default image if file is empty
            $article->image = 'default.jpg';
        }

        // Return the response if query has been successful
        if($article->save()) {
            // Save image to public/files
            if($upload_file) {
                $destinationPath = public_path('/files');
                $image->move($destinationPath, $image_file_name);
            }

            // Trigger an event for notification
            $message = ($request->isMethod('post'))
                ? 'A new article has been added: ' . $request->input('title')
                : 'Article: ' . $request->input('title') . ' has been updated';
            $notification = [
                'type' => 'success',
                'message' => $message
            ];
            event(new ArticleEvent($article, $notification));

            return new ArticleResource($article);
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
        // Get singe articles
        $article = Article::findOrFail($id);

        // Return response
        return new ArticleResource($article);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Get singe articles
        $article = Article::findOrFail($id);

        $this->unlinkImage($article->image);

        // Trigger an event for notification
        $notification = [
            'type' => 'error',
            'message' => 'Article: ' . $article->title . ' has been removed'
        ];
        event(new ArticleEvent($article, $notification));

        // If delete query has been successful
        if($article->delete()) {
            // Return response
            return new ArticleResource($article);
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
