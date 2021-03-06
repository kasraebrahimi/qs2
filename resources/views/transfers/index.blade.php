@extends('layouts.app')

@section('content')

@if (request()->has('task_id'))
<script type="text/javascript">
   function formfocus() {
     document.getElementById('user').focus();
   }
   window.onload = formfocus;
 </script>
 @endif

<!-- transfer task -->
  <div class="text-center">
    <form method="POST" action="/transfers">
    {{ csrf_field() }}
       <h4 class="col-lg-10">transfering &nbsp;
         <select name="transferedTaskId" class="form-control custom-select col-lg-3" id="task">
               <option class="placeholder" selected disabled>--select task--</option>
           @foreach($tasks as $task)
              @if(! $task->transfers->count() || $task->transfers->last()->transferStatus !== 0)
               <option value="{{ $task->id }}" class="dropdown-item" {{ $task->id == request()->task_id ? "selected" : ""}}>{{ $task->name }}</option>
              @endif
           @endforeach
         </select>
         &nbsp; to &nbsp;
         <select name="receiverId"class="form-control custom-select col-lg-3" id="user">
               <option class="placeholder" selected disabled>--select user--</option>
           @foreach($users as $userId => $userName)
               <option value="{{ $userId }}" class="dropdown-item" href="/transfers">{{ $userName }}</option>
           @endforeach
         </select>
         &nbsp;&nbsp;
         <button type="submit" class="btn btn-success" style="border-color: snow;"><strong>send</strong></button>
       </h4>
    </form>
  </div>
  <br><br>
  <!-- Outgoing tasks -->
  @if($sentTransfers->count())
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
                  <form class="d-inline" action="/transfers/{{ $sentTransfer->id }}" method="POST">
                  {{ csrf_field() }}
                  {{ method_field('DELETE') }}
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
  @if($receivedTransfers->count())
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
                  {{ method_field('PATCH') }}
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fa fa-btn fa-trash"></i>&#10008;
                    </button>
                  </form>

                  <!-- accept button -->
                  <form class="d-inline" action="/transfers/accept/{{ $receivedTransfer->id }}" method="POST">
                  {{ csrf_field() }}
                  {{ method_field('PATCH') }}
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
