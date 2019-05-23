<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;
use App\User;

class TransferController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function create(Task $task, User $user)
    {
      $users = User::all()->pluck('name', 'id')->all();
      $tasks = auth()->user()->tasks->pluck('name', 'id')->all();

      return view('transfers.create', [
        'task' => $task,
        'tasks' => $tasks,
        'users' => $users
      ]);
    }

    public function store(Request $request)
    {
      dd($request->all());
    }
}
