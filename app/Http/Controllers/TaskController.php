<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TaskRepository;
use App\Task;

class TaskController extends Controller
{
    protected $tasks;


    public function __construct(TaskRepository $tasks)
    {
      $this->middleware('auth');

      $this->tasks = $tasks;
    }

    public function index(Request $request)
    {
      return view('tasks.index', [
        'tasks' => $this->tasks->forUser($request->user())->where('status', 0)
      ]);
    }

    public function store(Request $request)
    {
      $this->validate($request, [
        'name' => ['required', 'max: 255']
      ]);

      $request->user()->tasks()->create([
        'name' => $request->name
      ]);

      return redirect('/tasks');
    }

    public function archive(Request $request, Task $task)
    {
      $this->authorize('destroy', $task);

      $task->status = 1;
      $task->save();

      return redirect('/tasks');
    }

}
