<?php

namespace App\Policies;

use App\User;
use App\Task;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function destroy(User $user, Task $task)
    {
      $condition1 = $task->user_id === $user->id;
      $condition2 = true;
      if ($task->transfers->last() !== null) {
        $condition2 = $task->transfers->last()->transferStatus !== 0;
      }

      return $condition1 && $condition2;
    }
}
