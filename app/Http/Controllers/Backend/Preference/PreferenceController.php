<?php

namespace App\Http\Controllers\Backend\Preference;

use Session;
use App\Models\Preference;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PreferenceController extends Controller
{
    public function showForm (Request $request)
    {
        checkPermissionTo('change-preference');

        $logo = Preference::valueOf('logo');
        $activationEmailTemplate = Preference::valueOf('activation_email_template');

        return view('backend.preference.edit', compact('logo', 'activationEmailTemplate'));
    }

    public function update (Request $request)
    {
        checkPermissionTo('change-preference');

        $this->validate($request, [
            'logo' => 'nullable|image|max:2048',
        ]);

        Preference::updateValueOf('activation_email_template', $request->activation_email_template);

        if ($request->hasFile('logo')) {
            if ($logo = Preference::valueOf('logo')) deleteFile($logo);

            $fileUrl = uploadFile($request->file('logo'), 'uploads/preference/logo');
            Preference::updateValueOf('logo', $fileUrl);
            Session::forget('header_logo');
        }

        return redirect()->route('backend.preference.edit')->with('notif_success', 'Preference has been updated successfully!');
    }
}