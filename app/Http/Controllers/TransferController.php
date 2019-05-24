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

    public function continue(Request $request)
    {
      return view('transfers.index');
    }

    public function index(Request $request)
    {
      $sentTransfers = auth()->user()->sentTransfers;
      $receivedTransfers = auth()->user()->receivedTransfers;
      $tasks = auth()->user()->tasks->pluck('name', 'id')->all();
      $users = User::all()->pluck('name', 'id')->all();

      return view('transfers.index', [
        'sentTransfers' => $sentTransfers,
        'receivedTransfers' => $receivedTransfers,
        'defaultTaskId' => $request->taskId,
        'tasks' => $tasks,
        'users' => $users
      ]);


    }

    public function create(Request $request, Transfer $transfer)
    {
      $transfer->create([
        'senderId' => auth()->user()->id,
        'receiverId' => $request->receiverId,
        'transferedTaskId' => $request->transferedTaskId,
      ]);

      return redirect('/transfers');
    }

    public function destroy(Request $request)
    {
      $task = \App\Task::find($request->deleteTaskId);
      $transfer = $task->transfer;
      $transfer->delete();

      return redirect('/transfers');
    }
}
