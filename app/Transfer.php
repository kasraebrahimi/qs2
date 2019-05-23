<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    public function task()
    {
      return $this->belongsTo(Task:class);
    }
}
