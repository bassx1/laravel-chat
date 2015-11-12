<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['title'];


    protected static function boot()
    {
        parent::boot();
        static::deleting(function($room) {
            $room->messages()->delete();
        });
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
