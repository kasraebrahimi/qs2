<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;

class TransferController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    public function index(Task $task)
    {
      return view('transfers.index', compact('task'));
    }
}
