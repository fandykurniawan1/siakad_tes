<?php

namespace App\Http\Controllers\Backend\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\ProductCategory;
use DB, Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductCategoryController extends Controller
{
    public function index()
    {
        checkPermissionTo('view-master-data-list');

        $masterProductCategories = ProductCategory::orderBy('name')->get();

        $data = collect();
        $parentProductCategories = $this->getParentProductCategory($masterProductCategories, null, $data);

        $data = collect();
        $productCategories = $this->getSubProductCategory($parentProductCategories, $masterProductCategories, null, $data);

        return view('backend.master.product-category.index', compact('productCategories'));
    }

    public function getSubProductCategory($parentProductCategories, $productCategories, $productCategory, $data, $level = 0, $storedProductCategoryId = null)
    {
        $productCategoryId = $productCategory ? $productCategory->id : null;
        $subProductCategories = $productCategories->where('parent_id', $productCategoryId);
        if ($subProductCategories->first()) {
            $level++;
            foreach ($subProductCategories as $i => $productCategory) {
                if (is_array($data)) {
                    array_push($data, [
                        'id'        => $productCategory->id,
                        'text'      => $productCategory->name,
                        'level'     => $level,
                        'selected'  => ($storedProductCategoryId == $productCategory->id) ? true : false
                    ]);
                } else {
                    $data->push([
                        'id'                => $productCategory->id,
                        'name'              => $productCategory->name,
                        'code'              => $productCategory->code,
                        'is_grandparent'    => $productCategory->isParent,
                        'index_id'          => $i + 1,
                        'index_parent_id'   => $parentProductCategories->where('id', $productCategory->parent_id)->first()['index_id']
                    ]);
                }
                
                $data = $this->getSubProductCategory($parentProductCategories, $productCategories, $productCategory, $data, $level, $storedProductCategoryId);
            }

            return $data;
        } else {
            return $data;
        }        
    }

    public function getParentProductCategory($productCategories, $productCategory, $data)
    {
        $productCategoryId = $productCategory ? $productCategory->id : null;
        $subProductCategories = $productCategories->where('parent_id', $productCategoryId);
        if ($subProductCategories->first()) {
            foreach ($subProductCategories as $i => $productCategory) {
                if ($productCategories->where('parent_id', $productCategory->id)->first()) {
                    $data->push([
                        'id'                => $productCategory->id,
                        'index_id'          => $i + 1,
                    ]);
                }
                
                $data = $this->getParentProductCategory($productCategories, $productCategory, $data);
            }

            return $data;
        } else {
            return $data;
        }        
    }

    public function create()
    {
        checkPermissionTo('create-master-data');

        $productCategories = ProductCategory::orderBy('name')->get();
        $data = $this->getSubProductCategory(null, $productCategories, null, []);
        $productCategories = json_encode($data);

        return view('backend.master.product-category.create', compact('productCategories'));
    }

    public function store(Request $request)
    {
        checkPermissionTo('create-master-data');

        DB::beginTransaction();
        try {
            $rules = [
                'code'      => 'nullable|unique:master_product_categories,code,NULL,id,deleted_at,NULL',
                'name'      => 'required',
                'parent'    => 'nullable|string|exists:master_product_categories,id,deleted_at,NULL',
            ];
            $this->validate($request, $rules);

            do {
                $code = (string) (($request->code) ?: rand(100, 999));
            } while (ProductCategory::whereCode($code)->first() != null);

            $productCategory            = new ProductCategory;
            $productCategory->code      = $code;
            $productCategory->name      = $request->name;
            $productCategory->parent_id = $request->parent;
            $productCategory->save();
        } catch (ValidationException $e) {
            DB::rollBack();
            throw new ValidationException($e->validator, $e->getResponse());
        } catch (Exception $e) {
            DB::rollBack();
            return validationError('Failed to create product category.');
        }
        DB::commit();

        return redirect()->route('backend.master.product-category.index')->with('notif_success', 'Product category successfully created.');
    }

    public function edit($id)
    {
        checkPermissionTo('edit-master-data');

        $productCategory = ProductCategory::findOrFail($id);

        $productCategories = ProductCategory::orderBy('name')->get();
        $data = $this->getSubProductCategory(null, $productCategories, null, [], 0, $productCategory->parent_id);
        $productCategories = json_encode($data);

        return view('backend.master.product-category.edit', compact('productCategory', 'productCategories'));
    }

    public function update(Request $request, $id)
    {
        checkPermissionTo('edit-master-data');

        DB::beginTransaction();
        try {
            $productCategory = ProductCategory::lockForUpdate()->findOrFail($id);

            $rules = [
                'code'      => 'nullable|unique:master_product_categories,code,' . $id . ',id,deleted_at,NULL',
                'name'      => 'required',
                'parent'    => 'nullable|string|exists:master_product_categories,id,deleted_at,NULL',
            ];
            $this->validate($request, $rules);

            do {
                $code = (string) (($request->code) ?: rand(100, 999));
            } while (ProductCategory::whereCode($code)->where('id', '!=', $id)->first() != null);

            $productCategory->code      = $code;
            $productCategory->name      = $request->name;
            $productCategory->parent_id = $request->parent;
            $productCategory->save();
        } catch (ValidationException $e) {
            DB::rollBack();
            throw new ValidationException($e->validator, $e->getResponse());
        } catch (Exception $e) {
            DB::rollBack();
            return validationError('Failed to update product category.');
        }
        DB::commit();

        return redirect()->route('backend.master.product-category.index')->with('notif_success', 'Product category successfully updated.');
    }

    public function destroy($id)
    {
        checkPermissionTo('delete-master-data');

        DB::beginTransaction();
        try {
            $productCategory = ProductCategory::findOrFail($id);
            $productCategory->delete();
        } catch (Exception $e) {
            DB::rollBack();
            return validationError('Failed to delete product category.');
        }
        DB::commit();

        return redirect()->route('backend.master.product-category.index')->with('notif_success', 'Product category successfully deleted.');
    }
}
