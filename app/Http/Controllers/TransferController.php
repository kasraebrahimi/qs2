<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;
use App\User;
use App\Transfer;

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

    public function store(Request $request, Transfer $transfer)
    {
      $transfer->create([
        'senderId' => auth()->user()->id,
        'receiverId' => $request->receiverId,
        'transferedTaskId' => $request->transferedTaskId,
      ]);

      return redirect('/transfers');
    }

    public function index()
    {
      $transfers = auth()->user()->sender;
      $transfers = auth()->user()->receiver;
      dd($transfers);
      return view('transfers.index');
    }
}
