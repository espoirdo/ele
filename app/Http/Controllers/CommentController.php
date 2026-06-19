<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, Event $event)
    {
        Comment::create([
            'event_id' => $event->id,
            'user_id' => Auth::id(),
            'contenu' => $request->contenu,
            'note' => $request->note ?? 5,
            'approuve' => false,
            'signale' => false,
        ]);

        return back()->with('success', 'Commentaire ajouté! Il sera visible après modération.');
    }

    public function report(Comment $comment)
    {
        $comment->update(['signale' => true]);

        return back()->with('success', 'Commentaire signalé.');
    }
}