<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Message;
use App\Events\NewMessage;

class ContactsController extends Controller
{
    public function get(){
        $contacts = User::where('id', '!=', auth()->id())->get();

        return response()->json($contacts);
    }

    public function getMessagesFor($id){

        $messages = Message::where('from', $id)->orWhere('to', $id)->get();

        /*$messages = Message::where(function ($q) use($id){
            $q->where('from', auth()->user()->id);
            $q->where('to', $id);
                })->orWhere(function ($q) use($id) {
                    $q->where('from', $id);
                    $q->where('to', auth()->user()->id);
                        })->get();
        */
        return response()->json($messages);
    }

    public function send(Request $request)
    {
        $message = Message::create([
            'from' => auth()->id(),
            'to' => $request->contact_id,
            'text' => $request->text
        ]);

        broadcast(new NewMessage($message));

        return response()->json($message);
    }
}
