<?php

namespace App\Jobs;

use App\Models\Post;
use App\Models\User;
use App\Mail\PostNotificationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPostNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function handle(): void
    {
        // Gửi email cho tất cả user có role khách hàng hoặc admin đều được.
        // Ở đây lấy toàn bộ active user.
        User::chunk(100, function ($users) {
            foreach ($users as $user) {
                Mail::to($user->email)->send(new PostNotificationMail($this->post));
            }
        });
    }
}
