<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Message extends Model
{

    protected $fillable = ['message', 'user_id', 'to', 'room_id'];
    protected $casts = ['id' => 'integer'];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    public function scopeAvailableMessages($query, Request $request)
    {
        $query->with('author')
            ->where('room_id', $request->get('room'))
            ->where(function($query){
                $query->where('to', Auth::user()->id)
                    ->orWhere('user_id', Auth::user()->id)
                    ->orWhere('to', 0);
            })
           ->latest();
    }

    public function scopeAllMessages($query, Request $request)
    {
        $query->with('author')->where('room_id', $request->get('room'))->latest();
    }


    public function scopeLatest($query)
    {
        $query->orderBy('id','DESC')->limit(50);
    }


}
