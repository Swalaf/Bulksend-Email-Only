<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use App\Models\SubscriberList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriberController extends Controller
{
    public function index()
    {
        $subscribers = Subscriber::where('user_id', Auth::id())
            ->with('lists')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $subscriberLists = SubscriberList::where('user_id', Auth::id())->get();

        return view('subscribers.index', compact('subscribers', 'subscriberLists'));
    }

    public function create()
    {
        $lists = SubscriberList::where('user_id', Auth::id())->get();
        return view('subscribers.create', compact('lists'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'nullable|string|max:255',
            'list_id' => 'nullable|exists:subscriber_lists,id',
        ]);

        $subscriber = Subscriber::firstOrCreate(
            ['email' => $request->email],
            [
                'name' => $request->name,
                'user_id' => Auth::id(),
            ]
        );

        if ($request->list_id) {
            $list = SubscriberList::find($request->list_id);
            if ($list && $list->user_id === Auth::id()) {
                $list->subscribers()->attach($subscriber->id);
            }
        }

        return redirect()->route('subscribers.index')->with('success', 'Subscriber added successfully');
    }

    public function lists()
    {
        return view('subscribers.lists');
    }
}