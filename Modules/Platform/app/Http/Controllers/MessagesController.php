<?php

namespace Modules\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Platform\Models\Message;

class MessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request )
    {
        $messages = Message::all();

        return Inertia::render('Platform/Messages/Index', [
            'messages' => $messages
        ]);
    }

    /**
     * View the specified resource.
     */
    public function update( Request $request, Message $message )
    {
        $message->update([
            'responded_to' => true,
        ]);

        return redirect()->route('platform.messages.index');
    }
}
