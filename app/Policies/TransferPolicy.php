<?php

namespace App\Policies;

use App\User;
use App\Transfer;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransferPolicy
{
    use HandlesAuthorization;
    /**
     * Determine whether the user can access the transfer.
     *
     * @param  \App\User  $user
     * @param  \App\Transfer  $transfer
     * @return mixed
     */
    public function access(User $user, Transfer $transfer)
    {
        return $user->id == $transfer->senderId;
    }

    public function create(User $user, Task $task)
    {
      return $user->id == $task->user->id;
    }
}
