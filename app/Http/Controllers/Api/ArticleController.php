<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ArticleStoreRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Article::query();

        if ($request->has('sort')) {
            $sortParams = explode(',', $request->input('sort'));

            foreach ($sortParams as $sortParam) {
                list($field, $order) = explode(':', $sortParam);

                if (in_array($field, ['id', 'name', 'slug', 'category_id', 'text', 'active', 'order', 'updated_at', 'created_at'])) {
                    $query->orderBy($field, $order);
                }
            }
        } else {
            $query->orderBy('order', 'asc');
        }

        $articles = $query->paginate(10);

        return ArticleResource::collection($articles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleStoreRequest $request)
    {
        $validatedData = $request->validated();

        $createArticle = Article::create($validatedData);

        if ($request->hasFile('image')) {
            $uploadFile = $request->file('image');
            $file_name = $uploadFile->hashName();
            $uploadFile->storeAs('images', $file_name);

            $createArticle->update(['image' => 'storage/images/' . $file_name]);
        }

        return new ArticleResource($createArticle);
    }

    /**
     * Display the specified resource.
     */
    public function show( $article)
    {

        return new ArticleResource(Article::where('id', $article)->orWhere( 'slug' , $article)->firstOrFail());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArticleStoreRequest $request, Article $article)
    {
        $article->update($request->validated());

        if ($request->hasFile('image')) {
            $uploadFile = $request->file('image');
            $file_name = $uploadFile->hashName();
            $uploadFile->storeAs('images', $file_name);
            $article->find($article->id)->update(['image' => 'storage/images/' . $file_name]);
        }

        return new ArticleResource($article);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $article->delete();
        return response()->noContent();
    }
}
