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
                                <!-- TODO: Delete Button -->
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection
