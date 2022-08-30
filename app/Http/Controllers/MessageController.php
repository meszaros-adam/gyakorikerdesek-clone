<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function add(Request $request)
    {
        $this->validate($request, [
            'message' => 'required|min:3',
            'addressee_id' => 'required',
        ]);

        return Message::create([
            'message' => $request->message,
            'addressee_id' => $request->addressee_id,
            'sender_id' => Auth::user()->id,
        ]);
    }
    public function getIncoming(Request $request)
    {
        return Message::where('addressee_id', Auth::user()->id)->orderBy($request->orderBy, $request->ordering)->paginate($request->itemPerPage);
    }
    public function getSended(Request $request)
    {
        return Message::where('sender_id', Auth::user()->id)->orderBy($request->orderBy, $request->ordering)->paginate($request->itemPerPage);
    }
    public function getUnreadedCount()
    {
        return Message::where([
            ['addressee_id', Auth::user()->id],
            ['readed', false]
        ])->count();
    }
    public function setMessagesTopReaded()
    {
        return Message::where('addressee_id', Auth::user()->id)->update([
            'readed' => true,
        ]);
    }
}
