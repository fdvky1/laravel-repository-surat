<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLetterRequest;
use App\Http\Requests\UpdateStatusRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Letter;
use App\Models\Attachment;
use App\Models\Classification;
use App\Models\Notes;
use App\Models\Dispositions;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use PDF;


class LetterController extends Controller
{

    public function print($id)
    {
        try {
            $user = auth()->user();
            $letter = Letter::when($user->role == 'user', function($query) use ($user){
                                return $query->where('status', 'published')->orWhere('created_by', $user->id);
                            })
                            ->outgoing()
                            ->find($id);
            $pdf = PDF::loadView('outgoing.print', [
                'data' => $letter,
            ]);
            $pdf->setPaper('A4', 'portrait');
            return $pdf->download("$letter->regarding.pdf");
        } catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function outgoing(Request $request): View
    {
        $user = auth()->user();
        $letters = Letter::when($user->role == 'user', function($query) use ($user){
                           return $query->where('status', 'published') ->orWhere('created_by', $user->id);
                         })
                         ->outgoing()
                         ->render($request->search)
                         ->get();

        return view('outgoing.list',[
            'data' => $letters,
        ]);
    }

    public function incoming(Request $request): View
    {
        $user = auth()->user();
        $letters = Letter::when($user->role == 'user', function($query) use ($user){
                           return  $query->where('status', 'published') ->orWhere('created_by', $user->id);
                         })
                         ->incoming()
                         ->render($request->search)
                         ->get();

        return view('incoming.list',[
            'data' => $letters,
        ]);
    }

    public function createOutgoing(): View
    {
        return view('outgoing.create', [
            'classifications' => Classification::all(),
        ]);
    }

    public function createIncoming(): View
    {
        return view('incoming.create', [
            'classifications' => Classification::all(),
        ]);
    }

    public function updateStatus(UpdateStatusRequest $request): RedirectResponse
    {
        try {
            $req = $request->validated();
            $user = auth()->user();
            if ($req['status'] == 'disposition'){
                Dispositions::where('letter_id', $req['letter_id'])->delete();
                foreach ($request->selected_users as $u) {
                    Dispositions::create([
                        'user_id' => $u,
                        'letter_id' => $req['letter_id']
                    ]);
                }
            }
            if($req['status'] != 'published' && $req['note'] && strlen($req['note']) > 0){
                Notes::create([
                    'user_id' => $user->id,
                    'letter_id' => $req['letter_id'],
                    'note' => $req['note']
                ]);
            }
            Letter::where('id', $req['letter_id'])->update([
                'status' => $req['status']
            ]);
            return back()
                ->with('success', 'Success update status');
        } catch (\Throwable $exception) {
            // LOG::error($exception->getMessage());
            return back()->with('error', $exception->getMessage());
        }

    }

    public function store(StoreLetterRequest $request): RedirectResponse
    {
        try {
            $user = auth()->user();
            $newLetter = $request->validated();
            $newLetter['created_by'] = $user->id;
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
                ->route($request['type'] == 'outgoing' ? 'outgoing.list' : 'incoming.list')
                ->with('success', __('Success save Letter'));
        } catch (\Throwable $exception) {
            // Log::error($exception->getMessage());
            return back()->with('error', $exception->getMessage());
        }
    }

    public function show($id): View
    {
        $user = auth()->user();
        $letter = Letter::when($user->role == 'user', function($query) use ($user){
                            return  $query->where('status', 'published') ->orWhere('created_by', $user->id);
                        })
                        ->find($id);
        return view('show', [
            'data' => $letter,
            'users' => $user->role != 'user' && $letter->status != 'published' ? User::all() : []
        ]);
    }

    public function remove($id): RedirectResponse
    {
        try {
            $user = auth()->user();
            Letter::where('created_by', $user->id)->find($id)->delete();
            return back()
                ->with('success', 'Success delete Letter');
        } catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

}
