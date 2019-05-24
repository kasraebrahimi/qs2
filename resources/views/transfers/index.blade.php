@extends('layouts.app')

@section('content')
<!-- transfer task -->
  <div class="text-center">
    <form method="POST" action="/transfers">
    {{ csrf_field() }}
       <h4 class="col-lg-10">transfering
         <select name="transferedTaskId" class="form-control custom-select col-lg-3" id="exampleFormControlSelect1" name="">
           @foreach($tasks as $task)
              @if(!$task->transfer)
               <option value="{{ $task->id }}" class="dropdown-item" {{ $task->id == $defaultTaskId ? "selected" : ""}}>{{ $task->name }}</option>
              @endif
           @endforeach
         </select>
         to
         <select name="receiverId"class="form-control custom-select col-lg-3" id="exampleFormControlSelect1">
           @foreach($users as $userId => $userName)
             @if($userId !== auth()->user()->id)
               <option value="{{ $userId }}" class="dropdown-item" href="/transfers">{{ $userName }}</option>
             @endif
           @endforeach
         </select>
         <button type="submit" class="btn btn-primary" style="border-color: snow;"><strong>verify</strong></button>
       </h4>
    </form>
  </div>
  <br><br>
  <!-- Outgoing tasks -->
  @if(in_array(auth()->user()->id, $sentTransfers->map->senderId->all()))
  <h3>&nbsp;&nbsp;&nbsp;Outgoing tasks</h3>
  <div class="panel-body col-lg-10">
      <table class="table table-striped task-table">
          <!-- Table Headings -->
          <thead class="text-center">
              <th>Task</th>
              <th>Receiver</th>
              <th>Sent@</th>
              <th>Responded@</th>
              <th>Action</th>
          </thead>
          <!-- Table Body -->
          <tbody>
            @foreach($sentTransfers as $sentTransfer)
            <tr class="text-center">
              <td>{{ $sentTransfer->task->name }}</td>
              <td>{{ $sentTransfer->receiver->name}}</td>
              <td>{{ $sentTransfer->created_at }}</td>
              <td>{{ $sentTransfer->updated_at == $sentTransfer->created_at ? 'Not Yet' : $sentTransfer->updated_at }}</td>
              <td>
                @if($sentTransfer->transferStatus != 0)
                  @if($sentTransfer->transferStatus == 1)
                    {{ 'Accepted' }}
                  @else
                    {{ 'Rejected' }}
                  @endif
                @else
                  <!-- cancel button -->
                  <form class="d-inline" action="/transfers" method="POST">
                  {{ csrf_field() }}
                  {{ method_field('DELETE') }}
                    <input type="hidden" name="deleteTaskId" value="{{ $sentTransfer->task->id }}">
                    <button type="submit" class="btn btn-default float-right" style="margin-right: 6px;">
                        <i class="fa fa-btn fa-trash"></i>cancel
                    </button>
                  </form>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
      </table>
  </div>
  @else
  <h4>there is no outgoing request!</h4>
  @endif

  <br>

  <!-- incoming tasks -->
  <h3>&nbsp;&nbsp;&nbsp;Incoming tasks</h3>
  <div class="panel-body col-lg-10">
      <table class="table table-striped task-table">
          <!-- Table Headings -->
          <thead class="text-center">
              <th>Task</th>
              <th>Sender</th>
              <th>Received@</th>
              <th>Responded@</th>
              <th>Action</th>
          </thead>
          <!-- Table Body -->
          <tbody>
            @foreach($receivedTransfers as $receivedTransfer)
            <tr class="text-center">
              <td>{{ $receivedTransfer->task->name }}</td>
              <td>{{ $receivedTransfer->sender->name}}</td>
              <td>{{ $receivedTransfer->created_at }}</td>
              <td>{{ $receivedTransfer->updated_at == $receivedTransfer->created_at ? 'Not Yet' : $receivedTransfer->updated_at }}</td>
              <td>
                @if($receivedTransfer->transferStatus != 0)
                  @if($receivedTransfer->transferStatus == 1)
                    {{ 'Accepted' }}
                  @else
                    {{ 'Rejected' }}
                  @endif
                @else

                <div class="btn-group">
                  <!-- reject button -->
                  <form class="d-inline" action="/rejected-transfers" method="POST">
                  {{ csrf_field() }}
                    <input type="hidden" name="rejectedTransferId" value="{{ $receivedTransfer->id }}">
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fa fa-btn fa-trash"></i>&#10008;
                    </button>
                  </form>

                  <!-- accept button -->
                  <form class="d-inline" action="accepted-transfers" method="POST">
                  {{ csrf_field() }}
                    <input type="hidden" name="acceptedTransferId" value="{{ $receivedTransfer->id }}">
                    <button type="submit" class="btn btn-success btn-sm" style="margin-left: 4px;">
                        <i class="fa fa-btn fa-trash"></i>&#10004;
                    </button>
                  </form>
                </div>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
      </table>
  </div>

@endsection
