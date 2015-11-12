<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class MessagesController extends Controller
{
    /**
     * MessagesController constructor.
     */
    public function __construct()
    {
        $this->middleware('onlyForAdmin', ['only' => ['destroy']]);
    }


    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if(Auth::user()->isAdmin() && $request->get('showPrivate') == 1){
            $message = Message::allMessages($request)->get();
        } else {
            $message = Message::availableMessages($request)->get();
        }

        return $message;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request = $this->prepareRequest($request);

        $msg = Message::create($request->all())->load('author');
        Redis::publish('default_room', $msg);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Message::destroy($id);
    }

    public function prepareRequest(Request $request)
    {
        $request['user_id'] = Auth::user()->id;

        $matches = [];
        preg_match('/(?<=#)([^,]+)(?=,)/', $request->get('message'), $matches);

        if(count($matches)){
            $to = User::where('name', $matches[0])->first();

            if($to && $to->id != Auth::user()->id)
                $request['to'] = $to->id;
        }

        return $request;
    }
}
