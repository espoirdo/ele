<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class AdminCommentController extends Controller
{
    public function index(Request $request)
    {
        $query = Comment::with('user', 'event');

        if ($request->tab === 'pending') {
            $query->where('approuve', false)->where('signale', false);
        } elseif ($request->tab === 'signaled') {
            $query->where('signale', true);
        }

        $comments = $query->latest()->paginate(20);

        return view('admin.comments.index', compact('comments'));
    }

    public function approve(Comment $comment)
    {
        $comment->update(['approuve' => true]);
        return back()->with('success', 'Commentaire approuvé');
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return back()->with('success', 'Commentaire supprimé');
    }
}