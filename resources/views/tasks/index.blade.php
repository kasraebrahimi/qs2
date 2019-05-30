@extends('layouts.app')

@section('content')
<!-- Bootstrap Boilerplate... -->

  <div class="panel-body">
      <!-- Display Validation Errors -->
      @include('common.errors')

      <!-- New Task Form -->
      <form action="/tasks" method="POST" class="form-horizontal">
          {{ csrf_field() }}

          <!-- Add Task -->
          <div class="form-group">
              <label for="task-name" class="col-sm-3 control-label">Task</label>

              <div class="input-group col-lg-10 col-sm-offset-3 col-sm-6">
                  <input type="text" name="name" id="task-name" style="margin-right: 6px; border-radius: 5px;" class="form-control d-inline">
                  <button type="submit" class="btn btn-default d-inline">
                      <i class="fa fa-plus"></i> Add Task
                  </button>
              </div>
          </div>
      </form>
  </div>

  <!-- Current Tasks -->
  @if (count($tasks) > 0)
    <div class="panel panel-default">
        <div class="panel-heading">
            Current Tasks
        </div>

        <div class="panel-body col-lg-10">
            <table class="table table-striped task-table">

                <!-- Table Headings -->
                <thead>
                    <th>Task</th>
                    <th>&nbsp;</th>
                </thead>

                <!-- Table Body -->
                <tbody>
                    @foreach ($tasks as $task)
                        <tr>
                            <!-- Task Name -->
                            <td class="table-text">
                                <div>{{ $task->name }}</div>
                            </td>

                            <td>
                                @if(! $task->transfers->count() || $task->transfers->last()->transferStatus !== 0)
                                <!-- Transfer Button -->
                                <form action="/transfers" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('PATCH') }}
                                  <input type="hidden" name="taskId" value="{{ $task->id }}">
                                  <input type="hidden" name="taskUserId" value="{{ $task->user_id }}">
                                  <button type="submit" class="btn btn-info float-right" style="color: azure;">
                                      <i class="fa fa-btn fa-trash"></i>Transfer
                                  </button>
                                </form>
                                <!-- Delete Button -->
                                <form action="{{ url('tasks/'.$task->id) }}" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}

                                  <button type="submit" id="delete-task-{{ $task->id }}" class="btn btn-danger float-right" style="margin-right: 6px;">
                                      <i class="fa fa-btn fa-trash"></i>Delete
                                  </button>
                                </form>
                                @else
                                <form class="d-inline" action="/transfers" method="POST">
                                {{ csrf_field() }}
                                {{ method_field('DELETE') }}
                                  <input type="hidden" name="deleteTaskId" value="{{ $task->id }}">
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
    </div>
@endif
@endsection
