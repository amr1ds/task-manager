<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        // Базовая статистика для карточек на дашборде
        $stats = [
            'projects' => Project::count(),
            'tasks' => Task::count(),
            'tasks_done' => Task::where('status', 'done')->count(),
        ];

        // Исполнителю показываем его задачи, персоналу — последние задачи в системе
        if ($user->isExecutor()) {
            $myTasks = Task::with('project')
                ->where('assignee_id', $user->id)
                ->latest()
                ->take(5)
                ->get();
        } else {
            $myTasks = Task::with(['project', 'assignee'])
                ->latest()
                ->take(5)
                ->get();
        }

        return view('dashboard', compact('stats', 'myTasks'));
    }
}
