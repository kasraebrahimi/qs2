@extends('layouts.app')

@section('content')
    <form method="POST" action="/transfers">
    {{ csrf_field() }}
       <h4>Transfering
         <select name="transferedTaskId" class="form-control custom-select col-lg-3" id="exampleFormControlSelect1" name="">
           @foreach($tasks as $taskId => $taskName)
               <option value="{{ $taskId }}" class="dropdown-item" href="/transfers" {{ $taskId == $task->id ? "selected" : ""}}>{{ $taskName }}</option>
           @endforeach
         </select>
         <span>to</span>
         <select name="ReceiverId"class="form-control custom-select col-lg-2" id="exampleFormControlSelect1">
           @foreach($users as $userId => $userName)
             @if($userId !== $task->user->id)
               <option value="{{ $userId }}" class="dropdown-item" href="/transfers">{{ $userName }}</option>
             @endif
           @endforeach
         </select>
         <button type="submit" class="btn btn-primary">transfer</button>
       </h4>
   </form>

@endsection
