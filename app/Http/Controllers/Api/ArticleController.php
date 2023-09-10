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
    public function index()
    {
        return ArticleResource::collection(Article::orderBy('order')->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleStoreRequest $request)
    {
        $validatedData = $request->validated();

        // Создание статьи
        $createArticle = Article::create($validatedData);

        // Проверка и сохранение изображения, если оно было загружено
        if ($request->hasFile('image')) {
            $uploadFile = $request->file('image');
            $file_name = $uploadFile->hashName();
            $uploadFile->storeAs('images', $file_name);

            // Обновление пути изображения в созданной статье
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
