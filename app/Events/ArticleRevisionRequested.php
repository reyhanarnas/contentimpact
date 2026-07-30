<?php

namespace App\Events;

use App\Models\Article;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ArticleRevisionRequested implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Article $article;
    public User $editor;
    public string $note;

    /**
     * Create a new event instance.
     */
    public function __construct(Article $article, User $editor, string $note)
    {
        $this->article = $article;
        $this->editor  = $editor;
        $this->note    = $note;
    }

    /**
     * Get the channels the event should broadcast on.
     * Setiap Jurnalis hanya mendengarkan notifikasi milik mereka sendiri.
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('journalist.' . $this->article->author_id),
        ];
    }

    /**
     * Data yang dikirim bersama event ke frontend.
     */
    public function broadcastWith(): array
    {
        return [
            'article_id'    => $this->article->id,
            'article_title' => $this->article->title,
            'editor_name'   => $this->editor->name,
            'note'          => $this->note,
            'message'       => "Artikel '{$this->article->title}' dikembalikan oleh Editor {$this->editor->name} untuk diperbaiki.",
        ];
    }

    /**
     * Nama event yang didengar di frontend.
     */
    public function broadcastAs(): string
    {
        return 'revision.requested';
    }
}
