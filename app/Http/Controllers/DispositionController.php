<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\Dispositions;
use Illuminate\Contracts\View\View;
use App\Http\Requests\UpdateDispositionRequest;
use App\Models\Letter;
use App\Models\Notes;
use Illuminate\Support\Facades\Log;



class DispositionController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();
        $dispositions = Dispositions::where('user_id', $user->id)->orderByRaw("FIELD(status, 'pending') DESC")->orderBy('status', 'asc')->get();
        return view('disposition.index', [
            'dispositions' => $dispositions
        ]);
    }

    public function update(UpdateDispositionRequest $request, $id): RedirectResponse
    {
        try {
            $req = $request->validated();
            $user = auth()->user();
            $disposition = Dispositions::where('status', 'pending')->where('user_id', $user->id)->find($id);
            if(!$disposition) throw new \Exception('Unauthorized');
            if($req['status'] == 'require_revision')
            {
                Letter::where('id', $req['letter_id'])->update([
                    'status' => 'require_revision'
                ]);
            } else {
                $disposition->update(['status' => $req['status']]);
                if(Dispositions::where('status', 'accept')->where('letter_id', $req['letter_id'])->count() == Dispositions::where('letter_id', $req['letter_id'])->count())
                {
                    Letter::where('type', 'outgoing')->where('id', $req['letter_id'])->update([
                        'letter_number' => (Letter::where('type', 'outgoing')->where('status', 'published')->count() + 1)
                    ]);
                    Letter::where('id', $req['letter_id'])->update([
                        'status' => 'published',
                    ]);
                }
            }
            if($req['note'] && strlen($req['note']) > 0){
                Notes::create([
                    'user_id' => $user->id,
                    'letter_id' => $req['letter_id'],
                    'note' => $req['note']
                ]);
            }
            return back()
                ->with('success', 'Success update status');
        } catch (\Throwable $exception) {
            return back()->withErrors($exception->getMessage());
        }
    }

    public function show($id): View | RedirectResponse
    {
        try {
            $user = auth()->user();
            $disposition = Dispositions::where('user_id', $user->id)->where('letter_id', $id)->first();
            return view('disposition.show', [
                'disposition' => $disposition,
                'data' => $disposition->letter,
            ]);
        } catch (\Throwable $exception) {
            return redirect()->route('home');
        }
    }
}
