<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLetterRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Letter;
use App\Models\Attachment;
use App\Models\Classification;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;


class LetterController extends Controller
{

    public function outgoing(Request $request): View
    {
        $user = auth()->user();
        $letters = Letter::outgoing($user->id)->render($request->search)->get();

        return view('outgoing.list',[
            'data' => $letters,
        ]);
    }

    public function incoming(Request $request): View
    {
        $user = auth()->user();
        $letters = Letter::incoming($user->id)->render($request->search)->get();

        return view('incoming.list',[
            'data' => $letters,
        ]);
    }

    public function create(): View
    {
        return view('outgoing.create', [
            'classifications' => Classification::all(),
            'users' => User::all()
        ]);
    }

    public function store(StoreLetterRequest $request): RedirectResponse
    {
        try {
            $user = auth()->user();
            $newLetter = $request->validated();
            
            $newLetter['from'] = $user->id;
            $letter = Letter::create($newLetter);
            if ($request->hasFile('attachments')) {
                foreach ($request->attachments as $attachment) {
                    $extension = $attachment->getClientOriginalExtension();
                    if (!in_array($extension, ['png', 'jpg', 'jpeg', 'pdf'])) continue;
                    $filename = time() . '-'. $attachment->getClientOriginalName();
                    $filename = str_replace(' ', '-', $filename);
                    $attachment->storeAs('public/attachments', $filename);
                    Attachment::create([
                        'filename' => $filename,
                        'extension' => $extension,
                        'user_id' => $user->id,
                        'letter_id' => $letter->id,
                    ]);
                }
            }
            return redirect()
                ->route('outgoing')
                ->with('success', __('Success save Letter'));
        } catch (\Throwable $exception) {
            Log::info($exception->getMessage());
            return back()->with('error', $exception->getMessage());
        }
    }

    public function show($id): View
    {
        $user = auth()->user();
        $letter = Letter::find($id);
        return view('show', [
            'data' => $letter,
            'type' => $user->id == $letter->from ? 'incoming' : 'outgoing',
        ]);
    }

    public function remove($id): RedirectResponse
    {
        try {
            $user = auth()->user();
            Letter::where('from', $user->id)->orWhere('to', $user->id)->find($id)->delete();
            return back()
                ->with('success', 'Success delete Letter');
        } catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

}
