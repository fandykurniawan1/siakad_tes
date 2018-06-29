<?php

namespace App\Http\Controllers\Backend\Master;

use DB, Datatables;
use Illuminate\Http\Request;
use App\Models\Master\Vendor;
use App\Http\Controllers\Controller;

class VendorController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-master-data-list');

        return view('backend.master.vendor.index');
    }

    public function store(Request $request)
    {
        checkPermissionTo('create-master-data');

        DB::beginTransaction();
        try {
            $this->validate($request, [
                'name'          => 'required',
                'code'          => 'nullable|numeric'
            ]);

            if ($request->code && Vendor::where('code', $request->code)->first())
                return validationError('Code has been used by another Vendor');

                $lastCode = Vendor::orderBy('code', 'DESC')->first()->code;

            $vendor = new Vendor;
            $vendor->code = $request->code ?: prefix($lastCode + 1, 3);
            $vendor->name = $request->name;
            $vendor->save();

        } catch (ValidationException $e) {
            DB::rollBack();
            throw new ValidationException($e->validator, $e->getResponse());
        } catch (Exception $e) {
            DB::rollBack();
            return validationError('Failed to add  Vendor. Please try again.');
        }
        DB::commit();


        return redirect()->back()->with('notif_success', 'New Vendor has been save Successfully!');
    }

    public function edit($id)
    {
        checkPermissionTo('edit-master-data');

        $vendor = Vendor::FindOrFail($id);

        return view('backend.master.vendor.edit', compact('vendor'));
    }

    public function update(Request $request, $id)
    {
        checkPermissionTo('edit-master-data');
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'code'          => 'nullable|numeric',
                'name'          => 'required'
            ]);

            if (Vendor::where('code', $request->code)->where('id', '!=', $id)->first())
            return validationError('Code has been used by another Vendor');

            $vendor = Vendor::FindOrFail($id);
            $vendor->code = $request->code;
            $vendor->name = $request->name;
            $vendor->save();

        } catch (ValidationException $e) {
            DB::rollBack();
            throw new ValidationException($e->validator, $e->getResponse());
        } catch (Exception $e) {
            DB::rollBack();
            return validationError('Failed to update  Vendor. Please try again.');
        }
        DB::commit();

        return redirect()->route('backend.master.vendor.index')->with('notif_success', 'New Vendor has been updated successfully!');
    }

    public function destroy($id)
    {
        checkPermissionTo('delete-master-data');

        Vendor::FindOrFail($id)->delete();

        return redirect()->route('backend.master.vendor.index')->with('notif_success', 'New Vendor has been deleted successfully!');
    }

    public function getData()
    {
        checkPermissionTo('view-master-data-list');

        $vendor = Vendor::all();

        return Datatables::of($vendor)
                ->addColumn('action', function($vendor) {
                    $edit = '<a href="'. route('backend.master.vendor.edit', $vendor->id) .'" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Edit"><i class="icon wb-edit" aria-hidden="true"></i></a>';
                    $delete = '<a class="btn btn-sm btn-icon text-danger tl-tip" data-href="'.route('backend.master.vendor.destroy',$vendor->id).'" data-toggle="modal" data-target="#confirm-delete-modal" data-original-title="Delete"><i class="icon wb-trash" aria-hidden="true"></i></a>';

                    return $edit.''.$delete;
                })
                ->rawColumns(['action'])
                ->make(true);
    }
}
