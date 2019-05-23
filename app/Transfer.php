<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $fillable = ['senderId', 'receiverId', 'transferedTaskId', 'transferStatus'];
    public function task()
    {
      return $this->belongsTo(Task::class);
    }

    public function sender()
    {
      // return $this->belongsTo(User::class, 'senderId', 'id');
    }

    public function receiver()
    {
      // return $this->belongsTo(User::class, 'receiverId', 'id');  Why first 'receiverId' and then 'id'; in User model it is vice versa;
    }
}
