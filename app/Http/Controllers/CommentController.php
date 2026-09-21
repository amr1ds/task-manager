<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    public function store(StoreCommentRequest $request, Task $task): RedirectResponse
    {
        // Право комментировать проверяется через TaskPolicy::comment
        $this->authorize('comment', $task);

        $task->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $request->validated('body'),
        ]);

        return back()->with('success', 'Комментарий добавлен.');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $this->authorize('delete', $comment);

        $taskId = $comment->task_id;
        $comment->delete();

        return redirect()
            ->route('tasks.show', $taskId)
            ->with('success', 'Комментарий удалён.');
    }
}
