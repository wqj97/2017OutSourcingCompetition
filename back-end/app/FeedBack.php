<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeedBack extends Model
{
    use SoftDeletes;

    /**
     * Related User
     */
    public function message()
    {
        return $this->getAttribute('F_reply');
    }
}
