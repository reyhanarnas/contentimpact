<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * Toggle like pada artikel.
     * Menggunakan session untuk mencegah satu pengunjung like lebih dari sekali.
     * Bekerja via AJAX — mengembalikan JSON.
     */
    public function toggle(Request $request, string $slug)
    {
        $article = Article::where('slug', $slug)->where('status', 'published')->firstOrFail();

        // Key session unik per artikel
        $sessionKey = 'liked_article_' . $article->id;

        if (session()->has($sessionKey)) {
            // Sudah like → UNLIKE
            $article->decrement('likes');
            session()->forget($sessionKey);
            $liked = false;
        } else {
            // Belum like → LIKE
            $article->increment('likes');
            session()->put($sessionKey, true);
            $liked = true;
        }

        return response()->json([
            'success' => true,
            'liked'   => $liked,
            'likes'   => $article->fresh()->likes,
        ]);
    }
}
