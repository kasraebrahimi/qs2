@extends('layouts.app')

@section('content')
<!-- transfer task -->
  <div class="text-center">
    <form method="POST" action="/transfers">
    {{ csrf_field() }}
       <h4 class="col-lg-10">transfering &nbsp;
         <select name="transferedTaskId" class="form-control custom-select col-lg-3" id="exampleFormControlSelect1" name="">
           @foreach($tasks as $task)
              @if(! $task->transfers->count() || $task->transfers->last()->transferStatus !== 0)
               <option value="{{ $task->id }}" class="dropdown-item" {{ $task->id == $defaultTaskId ? "selected" : ""}}>{{ $task->name }}</option>
              @endif
           @endforeach
         </select>
         &nbsp; to &nbsp;
         <select name="receiverId"class="form-control custom-select col-lg-3" id="exampleFormControlSelect1">
           @foreach($users as $userId => $userName)
             @if($userId !== auth()->user()->id)
               <option value="{{ $userId }}" class="dropdown-item" href="/transfers">{{ $userName }}</option>
             @endif
           @endforeach
         </select>
         &nbsp;&nbsp;
         <button type="submit" class="btn btn-success" style="border-color: snow;"><strong>send</strong></button>
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
  <h4>no outgoing requests!</h4>
  @endif

  <br>

  <!-- incoming tasks -->
  @if(in_array(auth()->user()->id, $receivedTransfers->map->receiverId->all()))
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
                  <form class="d-inline" action="/transfers/reject/{{ $receivedTransfer->id }}" method="POST">
                  {{ csrf_field() }}
                  {{ method_field('DELETE') }}
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fa fa-btn fa-trash"></i>&#10008;
                    </button>
                  </form>

                  <!-- accept button -->
                  <form class="d-inline" action="/transfers/accept/{{ $receivedTransfer->id }}" method="POST">
                  {{ csrf_field() }}
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
  @else
  <h4>no incoming requests!</h4>
  @endif

@endsection
