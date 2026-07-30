<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * GET /api/articles
     * Mendapatkan daftar artikel yang sudah dipublikasikan (dengan pagination & search).
     */
    public function index(Request $request)
    {
        $query = Article::with(['category', 'author'])
            ->published()
            ->orderBy('published_at', 'desc');

        // Search by keyword
        if ($search = $request->query('q')) {
            $query->where(function ($sub) use ($search) {
                $sub->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        // Filter by category slug
        if ($categorySlug = $request->query('category')) {
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        $articles = $query->paginate(10);

        return ArticleResource::collection($articles)->additional([
            'meta' => [
                'app'     => 'ContentImpact CMS API',
                'version' => 'v1.0',
            ]
        ]);
    }

    /**
     * GET /api/articles/{slug}
     * Mendapatkan detail satu artikel berdasarkan slug.
     */
    public function show(string $slug)
    {
        $article = Article::with(['category', 'author'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (!$article) {
            return response()->json([
                'success' => false,
                'message' => 'Artikel tidak ditemukan.',
            ], 404);
        }

        // Increment views
        $article->increment('views');

        return (new ArticleResource($article))->additional([
            'success' => true,
        ]);
    }

    /**
     * GET /api/categories
     * Mendapatkan daftar semua kategori.
     */
    public function categories()
    {
        $categories = Category::withCount([
            'articles' => fn($q) => $q->where('status', 'published')
        ])->get();

        return response()->json([
            'success' => true,
            'data'    => $categories->map(fn($cat) => [
                'id'             => $cat->id,
                'name'           => $cat->name,
                'slug'           => $cat->slug,
                'articles_count' => $cat->articles_count,
            ]),
        ]);
    }
}
