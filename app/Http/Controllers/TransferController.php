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

    public function index(Request $request)
    {
      $sentTransfers = auth()->user()->sentTransfers->sortBy('updated_at');
      $receivedTransfers = auth()->user()->receivedTransfers->sortBy('updated_at');
      $tasks = auth()->user()->tasks;
      $users = User::all()->pluck('name', 'id')->all();

      return view('transfers.index', [
        'sentTransfers' => $sentTransfers,
        'receivedTransfers' => $receivedTransfers,
        'defaultTaskId' => $request->taskId,
        'tasks' => $tasks,
        'users' => $users
      ]);


    }

    public function create(Request $request,Transfer $transfer)
    {
      $this->validate($request, [
        'receiverId' => ['required', 'integer', 'min: 1'],
        'transferedTaskId' => ['required', 'integer', 'min: 1']
      ]);

      // Authorizations:
      // 1. a user CANNOT send a task to HIMSELF. [OK]
      abort_if(\Gate::allows('transfer-to-self', $request), 403);

      // 2. a user CANNOT send a task to a NON-EXISTANT USER. [OK]
      abort_if(User::find($request->receiverId) === null, 403);

      $task = Task::find($request->transferedTaskId);
      // 3. a user CANNOT send a task that is NOT HIS. [OK]
      abort_if($task->user->id !== auth()->user()->id, 403);

      // 4. NO DUPLICATE TRANSFERS. [OK]

      $transfer->create([
        'senderId' => auth()->user()->id,
        'receiverId' => $request->receiverId,
        'transferedTaskId' => $request->transferedTaskId,
        'transferStatus' => 0
      ]);

      return redirect('/transfers');
    }

    public function destroy(Request $request)
    {
      $task = Task::find($request->deleteTaskId);
      $transfer = $task->transfers->last();

      $this->authorize('cancel', $transfer);
      $transfer->delete();

      return back();
    }

    public function accept(Request $request, Transfer $transfer)
    {
      $this->authorize('respond', $transfer);

      $transfer->transferStatus = 1;

      $task = $transfer->task;
      $task->user_id = $transfer->receiverId;
      $task->save();

      $transfer->save();

      return redirect('/transfers');
    }

    public function reject(Request $request, Transfer $transfer)
    {
      $this->authorize('respond', $transfer);
      $transfer->transferStatus = 2;
      $transfer->save();

      return redirect('/transfers');
    }
}
