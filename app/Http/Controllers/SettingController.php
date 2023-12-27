<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use App\Models\Setting;

class SettingController extends Controller
{
    //
    public function show(): View
    {
        $data = Setting::find(1);
        return view('setting', [
            'data' => $data
        ]);
    }
    
    public function update(Request $request): RedirectResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'header' => ['required'],
                'subheader' => ['required'],
                'address' => ['required'],
                'contact' => ['required'],
                'position_name' => ['required'],
                'name' => ['required']
            ]);
    
            Setting::find(1)->update($validator->validate());
            return back()
                ->with('success', 'Success update setting');
        } catch (\Throwable $exception) {
            return back()->withErrors($exception->getMessage());
        }
    }

    public function updatePhoto(Request $request)
    {
        try {
            $data = Setting::find(1);
            $validator = Validator::make($request->all(), [
                'company_photo' => [
                    'required',
                    'image',
                    'mimes:jpeg,png,jpg,gif',
                    'max:5120',
                    'dimensions:max_width=3000,max_height=3000',
                ],
            ]);
            $validator->validated();
            $extension = $request->company_photo->getClientOriginalExtension();
            if (!in_array($extension, ['png', 'jpg', 'jpeg'])) throw new \Exception('Unsuported file type');;
            $request->company_photo->storeAs('public/', $data->image_name);
            return back()
            ->with('success', 'Success update photo');
        } catch (\Throwable $exception) {
            return back()->withErrors($exception->getMessage());
        }
    }

}
