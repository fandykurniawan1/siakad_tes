<?php

namespace App\Http\Controllers\Backend\Banner;

use DB, File;
use App\Models\Image;
use Illuminate\Http\Request;
use App\Models\Banner\Banner;
use App\Http\Controllers\Controller;

class BannerController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-banner-list');

        $images = Image::where('type', 'banner')->orderBy('order')->get();

        return view('backend.banner.index', compact('images'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        checkPermissionTo('create-banner');
        DB::beginTransaction();
        try {
            $this->validate($request, [
                'photos.*' => 'image|max:2048',
            ]);

            foreach ($request->file('photos', []) as $order => $imageData) {
                if ($request->hasFile('photos.' . $order)) {
                    $fileUrl = uploadFile($request->file('photos.' . $order), 'uploads/banner/photo');

                    $image = new Image;
                    $image->url = $fileUrl;
                    $image->entity_id = '1';
                    $image->entity_type = 'photo';
                    $image->type = 'banner';
                    $image->order = $order;
                    $image->save();
                }
            }

        } catch (ValidationException $e) {
            DB::rollBack();
            throw new ValidationException($e->validator, $e->getResponse());
        } catch (Exception $e) {
            DB::rollBack();
            return validationError('Failed to add Banner. Please try again.');
        }
        DB::commit();

        return redirect()->route('backend.banner.index')->with('notif_success', 'Banner has been created successfully!');
    }

    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
