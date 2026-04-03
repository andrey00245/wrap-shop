<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBlogCommentRequest;
use App\Models\BlogComment;
use App\Models\BlogPost;
use Illuminate\Http\RedirectResponse;

class BlogCommentController extends Controller
{
    public function store(StoreBlogCommentRequest $request, BlogPost $blog_post): RedirectResponse
    {
        if (! $blog_post->isPublic()) {
            abort(404);
        }

        if (! $blog_post->comments_enabled) {
            return back()->withErrors(['comment' => __('blog.comments_disabled')]);
        }

        BlogComment::query()->create([
            'blog_post_id' => $blog_post->id,
            'guest_name' => $request->validated('guest_name'),
            'guest_email' => $request->validated('guest_email'),
            'body' => $request->validated('body'),
            'is_approved' => false,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('status', __('blog.comment_sent'));
    }
}
