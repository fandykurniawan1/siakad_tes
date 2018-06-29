<?php

namespace App\Http\Controllers\Backend\Master;

use DB, Datatables;
use App\Models\Master\Brand;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BrandController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-master-data-list');

        return view('backend.master.brand.index');
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
            if ($request->code && Brand::where('code', $request->code)->first())
                return validationError('Code has been used by another Brand');

                $lastCode = Brand::orderBy('code', 'DESC')->first()->code;

            $brand = new Brand;
            $brand->code = $request->code ?: prefix($lastCode + 1, 3);
            $brand->name = $request->name;
            $brand->save();

        } catch (ValidationException $e) {
            DB::rollBack();
            throw new ValidationException($e->validator, $e->getResponse());
        } catch (Exception $e) {
            DB::rollBack();
            return validationError('Failed to add  Brand. Please try again.');
        }
        DB::commit();


        return redirect()->back()->with('notif_success', 'New Brand has been save Successfully!');
    }

    public function edit($id)
    {
        checkPermissionTo('edit-master-data');

        $brand = Brand::FindOrFail($id);

        return view('backend.master.brand.edit', compact('brand'));
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

            if (Brand::where('code', $request->code)->where('id', '!=', $id)->first())
            return validationError('Code has been used by another Brand');

            $brand = Brand::FindOrFail($id);
            $brand->code = $request->code;
            $brand->name = $request->name;
            $brand->save();

        } catch (ValidationException $e) {
            DB::rollBack();
            throw new ValidationException($e->validator, $e->getResponse());
        } catch (Exception $e) {
            DB::rollBack();
            return validationError('Failed to update  Brand. Please try again.');
        }
        DB::commit();

        return redirect()->route('backend.master.brand.index')->with('notif_success', 'New Brand has been updated successfully!');
    }

    public function destroy($id)
    {
        checkPermissionTo('delete-master-data');

        Brand::FindOrFail($id)->delete();

        return redirect()->route('backend.master.brand.index')->with('notif_success', 'New Brand has been deleted successfully!');
    }


    public function getData()
    {
        checkPermissionTo('view-master-data-list');

        $brand = Brand::all();

        return Datatables::of($brand)
                ->addColumn('action', function($brand) {
                    $edit = '<a href="'. route('backend.master.brand.edit', $brand->id) .'" class="btn btn-sm btn-icon text-default tl-tip" data-toggle="tooltip" data-original-title="Edit"><i class="icon wb-edit" aria-hidden="true"></i></a>';
                    $delete = '<a class="btn btn-sm btn-icon text-danger tl-tip" data-href="'.route('backend.master.brand.destroy',$brand->id).'" data-toggle="modal" data-target="#confirm-delete-modal" data-original-title="Delete"><i class="icon wb-trash" aria-hidden="true"></i></a>';

                    return $edit.''.$delete;
                })
                ->rawColumns(['action'])
                ->make(true);
    }
}
