<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $fillable = ['senderId', 'receiverId', 'transferedTaskId', 'transferStatus'];

    public function task()
    {
      return $this->belongsTo(Task::class, 'transferedTaskId');
    }

    public function sender()
    {
      return $this->belongsTo(User::class, 'senderId');
    }

    public function receiver()
    {
      return $this->belongsTo(User::class, 'receiverId');
    }
}
