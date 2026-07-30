<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Services\CommentService;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ManageCommentController extends Controller
{
    protected $commentService;

    public function __construct(CommentService $commentService)
    {
        $this->commentService = $commentService;
    }

    public function index()
    {
        $this->authorizeModerator();
        $pendingComments = $this->commentService->getPendingComments();
        $allComments = $this->commentService->getAllComments();
        return view('dashboard.comments.index', compact('pendingComments', 'allComments'));
    }



    public function destroy(int $id)
    {
        $this->authorizeModerator();
        $this->commentService->deleteComment($id);
        return redirect()->route('dashboard.comments.index')->with('success', 'Komentar berhasil dihapus.');
    }

    private function authorizeModerator()
    {
        if (Gate::denies('moderate', Comment::class)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki wewenang memoderasi komentar.');
        }
    }
}
