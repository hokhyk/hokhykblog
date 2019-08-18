<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Entities\Blog\Article;

class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::all();

        return $articles;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $article = $request->all();
        return Article::create($article);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return  Article::find($id);
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
        if(Article::where('_id', $id)->exists()) {
            $article = Article::find($id);
            $article->title = is_null($request->title) ? $article->title : $request->title;
            $article->article_content = is_null($request->article_content) ? $article->article_content : $request->article_content;
            $article->save();
//            return $article;
            return Article::find($id);
        }
        return response()->json(['code' => Response::HTTP_EXPECTATION_FAILED, 'message' => 'Not a Record found.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Article::where('_id', $id)->exists()) {
            $article = Article::find($id);
            $article->delete();

            return response()->json(['code' => Response::HTTP_OK, 'message' => 'Article is deleted.']);
        }
        return response()->json(['code' => Response::HTTP_EXPECTATION_FAILED, 'message' => 'Not an article found.']);
    }
}
