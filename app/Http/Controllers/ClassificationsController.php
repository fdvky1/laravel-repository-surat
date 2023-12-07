<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\View;
use App\Models\Classification;
use App\Http\Requests\StoreClassificationRequest;
use App\Http\Requests\UpdateClassificationRequest;



class ClassificationsController extends Controller
{
    public function show(Request $request): View
    {
        $search = $request->input('search');

        $classifications = Classification::query()
            ->when($search, function ($query) use ($search) {
                return $query->where('code', 'like', '%' . $search . '%');
            })
            ->get();

        return view('classifications.index', [
            'classifications' => $classifications,
        ]);
    }
    public function store(StoreClassificationRequest $request): RedirectResponse
    {
        try {
            Classification::create($request->validated());
            return back()->with('success', 'success create classification letter');
        } catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function update(UpdateClassificationRequest $request, $id): RedirectResponse
    {
        try {
            Classification::find($id)->update($request->validated());
            return back()->with('success', 'success update classification letter');
        } catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }

    public function remove($id): RedirectResponse
    {
        try {
            Classification::find($id)->delete();
            return back()->with('success','success delete classification letter');
        } catch (\Throwable $exception) {
            return back()->with('error', $exception->getMessage());
        }
    }
}
