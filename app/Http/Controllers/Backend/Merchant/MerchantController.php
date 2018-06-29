<?php

namespace App\Http\Controllers\Backend\Merchant;

use App\Models\Image;
use Illuminate\Http\Request;
use App\Models\Location\City;
use App\Models\Location\Country;
use Yajra\DataTables\DataTables;
use App\Models\Location\Province;
use App\Models\Merchant\Merchant;
use App\Models\UserManagement\Role;
use App\Models\UserManagement\User;
use App\Http\Controllers\Controller;
use App\Models\UserManagement\Permission;

class MerchantController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-merchant-list');

        return view('backend.merchant.index');
    }

    public function create()
    {
        checkPermissionTo('create-merchant');

        $countries = Country::all();
        $provinces = Province::all();

        return view('backend.merchant.create', compact('countries', 'provinces'));
    }

    public function store(Request $request)
    {
        checkPermissionTo('create-merchant');

        $this->validate($request, [
            'image'             => 'nullable|image|max:2048',
            'code'              => 'nullable|numeric',
            'merchant_name'     => 'required',
            'phone'             => 'nullable|numeric|digits_between:6,15',
            'merchant_email'    => 'nullable|email',
            'address'           => 'nullable',
            'type'              => 'required|in:Default,Money Changer',
            'country_id'        => 'nullable|uuid|exists:master_countries,id,deleted_at,NULL',
            'province_id'       => 'nullable|uuid|exists:master_provinces,id,deleted_at,NULL',
            'city_id'           => 'nullable|uuid|exists:master_cities,id,deleted_at,NULL',
            'longitude'         => 'nullable|numeric|min:-180|max:180',
            'latitude'          => 'nullable|numeric|min:-90|max:90',

            'name'              => 'required',
            'email'             => 'required|email|unique:users,email,NULL,id,deleted_at,NULL',
            'username'          => 'required|unique:users,username,NULL,id,deleted_at,NULL',
            'password'          => 'required|confirmed'
        ]);

        if ($request->code && Merchant::where('code', $request->code)->first())
            return validationError('Code has been used by another sales');
        $lastCode = optional(Merchant::orderBy('code', 'DESC')->first())->code;

        $merchant                  = new Merchant;
        $merchant->code            = $request->code ? : prefix($lastCode ? $lastCode + 1 : 1, 4);
        $merchant->name            = $request->merchant_name;
        $merchant->phone           = $request->phone;
        $merchant->email           = $request->merchant_email;
        $merchant->address         = $request->address;
        $merchant->type            = $request->type;
        $merchant->country_id      = $request->country_id;
        $merchant->province_id     = $request->province_id;
        $merchant->city_id         = $request->city_id;
        $merchant->longitude       = $request->longitude;
        $merchant->latitude        = $request->latitude;
        $merchant->status          = $request->status ? 'Active' : 'Not Active';
        $merchant->save();

        $admin = new Role;
        $admin->platform = 'Merchant';
        $admin->name = 'admin';
        $admin->display_name = 'Administrator';
        $admin->save();
        $admin->attachPermissions(Permission::withoutGlobalScope('scope')->whereScope('Merchant Backend')->get());

        $user = new User;
        $user->merchant_id     = $merchant->id;
        $user->name            = $request->name;
        $user->email           = $request->email;
        $user->username        = $request->username;
        $user->password        = bcrypt($request->password);
        $user->platform        = 'Merchant';
        $user->merchant_id     = $merchant->id;
        $user->active          = 1;
        $user->save();
        $user->attachRole($admin);

        if ($request->hasFile('image')) {
            $fileUrl = uploadFile(request()->file('image'), 'uploads/merchant');

            $image = new Image;
            $image->url = $fileUrl;
            $image->type = 'photo';
            $merchant->images()->save($image);
        }

        return redirect()->route('backend.merchant.index')->with('notif_success', 'New merchant has been saved successfully.');
    }

    public function edit($id)
    {
        checkPermissionTo('edit-merchant');

        $merchant  = Merchant::findOrFail($id);
        $user      = User::withoutGlobalScopes(['active', 'platform'])->where('merchant_id', $merchant->id)->firstOrFail();
        $countries = Country::all();
        $provinces = Province::all();
        $cities    = City::all();

        return view('backend.merchant.edit', compact('merchant', 'countries', 'provinces', 'cities', 'user'));
    }

    public function update(Request $request, $id)
    {
        checkPermissionTo('edit-merchant');

        $this->validate($request, [
            'image'             => 'nullable|image|max:2048',
            'code'              => 'nullable|numeric',
            'merchant_name'     => 'required',
            'phone'             => 'nullable|numeric|digits_between:6,15',
            'merchant_email'    => 'nullable|email',
            'address'           => 'nullable',
            'type'              => 'required|in:Default,Money Changer,Outlet',
            'country_id'        => 'nullable|uuid|exists:master_countries,id,deleted_at,NULL',
            'province_id'       => 'nullable|uuid|exists:master_provinces,id,deleted_at,NULL',
            'city_id'           => 'nullable|uuid|exists:master_cities,id,deleted_at,NULL',
            'longitude'         => 'nullable|numeric|min:-180|max:180',
            'latitude'          => 'nullable|numeric|min:-90|max:90'
        ]);

        if ($request->code && Merchant::where('code', $request->code)->where('id', '!=', $id)->first())
            return validationError('Code has been used by another merchant');

        $merchant                  = Merchant::findOrfail($id);
        $merchant->code            = $request->code;
        $merchant->name            = $request->merchant_name;
        $merchant->phone           = $request->phone;
        $merchant->email           = $request->merchant_email;
        $merchant->address         = $request->address;
        $merchant->type            = $request->type;
        $merchant->country_id      = $request->country_id;
        $merchant->province_id     = $request->province_id;
        $merchant->city_id         = $request->city_id;
        $merchant->longitude       = $request->longitude;
        $merchant->latitude        = $request->latitude;
        $merchant->status          = $request->status ? 'Active' : 'Not Active';
        $merchant->save();

        if ($request->hasFile('image')) {
            $fileUrl = uploadFile($request->file('image'), 'uploads/merchant');

            if ($merchant->photo) { deleteFile($merchant->photo); $merchant->photo()->delete(); }

            $image = new Image;
            $image->url = $fileUrl;
            $image->type = 'photo';
            $merchant->images()->save($image);
        }

        return redirect()->route('backend.merchant.index')->with('notif_success', 'Merchant has been updated successfully.');
    }

    public function destroy(Merchant $merchant)
    {
        checkPermissionTo('delete-merchant');

        User::withoutGlobalScopes()->where('merchant_id', $merchant->id)->delete();

        if ($merchant->photo) { deleteFile($merchant->photo); $merchant->photo()->delete(); }
        $merchant->delete();

        return redirect()->back()->with('notif_success', 'Merchant has been deleted successfully.');
    }

    public function getData()
    {
        checkPermissionTo('view-merchant-list');

        $merchants = Merchant::whereNull('parent_id')->select('merchants.*');

        return Datatables::of($merchants)
            ->addColumn('action', function($merchant){
                $edit = '<a href="'. route('backend.merchant.edit', $merchant->id) .'" class="btn btn-sm btn-icon text-primary tl-tip" data-toggle="tooltip" data-original-title="Edit"><i class="icon wb-edit" aria-hidden="true"></i></a>';
                $delete = '<a class="btn btn-sm btn-icon text-danger tl-tip" data-href="'. route('backend.merchant.destroy', $merchant->id) .'" data-toggle="modal" data-target="#confirm-delete-modal" data-original-title="Delete"><i class="icon wb-trash" aria-hidden="true"></i></a>';

                return (userCan('edit-merchant') ? $edit : '') . (userCan('delete-merchant') ? $delete : '');
            })
            ->editColumn('status', function($merchant) {
                if ($merchant->status == 'Active')
                    return '<i class="icon wb-check text-success"></i>';
                else if ($merchant->status == 'Not Active')
                    return '<i class="icon wb-close text-danger">';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }
}
