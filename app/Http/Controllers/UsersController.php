<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;


/**
 * Created by PhpStorm.
 * User: dimas
 * Date: 28.10.15
 * Time: 11:13
 */
class UsersController extends Controller
{
    public function setRoom(Request $request)
    {
        $user = Auth::user();
//        $user->setRoom($request->get('room'));
        Redis::publish('room', $request->get('room'));
    }


    public function getUser()
    {
        return Auth::user();
    }
}