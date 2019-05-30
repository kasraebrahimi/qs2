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
    public function cancel(User $user, Transfer $transfer)
    {
        return $user->id == $transfer->senderId && $transfer->transferStatus == 0;
    }

    public function respond(User $user, Transfer $transfer)
    {
      return $user->id == $transfer->receiverId && $transfer->transferStatus == 0;
    }

}
