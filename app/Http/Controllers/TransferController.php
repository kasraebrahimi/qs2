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
      $users = User::all()->where('id', '!==', auth()->user()->id)->pluck('name', 'id')->all();

      return view('transfers.index', [
        'sentTransfers' => $sentTransfers,
        'receivedTransfers' => $receivedTransfers,
        'defaultTaskId' => $request->taskId,
        'tasks' => $tasks,
        'users' => $users,
      ]);


    }

    public function create(Request $request, Transfer $transfer)
    {
      $this->validate($request, [
        'receiverId' => ['required', 'integer', 'min: 1'],
        'transferedTaskId' => ['required', 'integer', 'min: 1']
      ]);

      $this->authorizeCreateTransfer($request);

      $transfer->create([
        'senderId' => auth()->user()->id,
        'receiverId' => $request->receiverId,
        'transferedTaskId' => $request->transferedTaskId,
        'transferStatus' => 0
      ]);

      return redirect('/transfers');
    }

    public function destroy(Request $request, Transfer $transfer)
    {
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

    protected function authorizeCreateTransfer(Request $request)
    {
      // Authorizations:
      // 1. a user CANNOT send a task to HIMSELF.
      $restriction1 = \Gate::allows('transfer-to-self', $request);

      // 2. a user CANNOT send a task to a NON-EXISTANT USER.
      $restriction2 = User::findOrFail($request->receiverId) === null;

      $task = Task::findOrFail($request->transferedTaskId);
      // 3. a user CANNOT send a task that is NOT HIS.
      $restriction3 = $task->user->id !== auth()->user()->id;

      // 4. NO DUPLICATE TRANSFERS.
      $restriction4 = Transfer::where('senderId', $task->user->id)->where('transferedTaskId', $request->transferedTaskId)->count();

      return !($restriction1 || $restriction2 || $restriction3 || $restriction4);
    }
}
