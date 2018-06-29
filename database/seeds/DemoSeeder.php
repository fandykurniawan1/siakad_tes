<?php

use App\Models\Master\ProductCategory;
use App\Models\Preference;
use App\Models\UserManagement\Permission;
use App\Models\UserManagement\Role;
use App\Models\UserManagement\User;
use Illuminate\Database\Seeder;
use Webpatser\Uuid\Uuid;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::select('SET FOREIGN_KEY_CHECKS=0;');
        User::wherePlatform('Admin')->forceDelete();
        Role::wherePlatform('Admin')->forceDelete();
        ProductCategory::truncate();
        Preference::wherePlatform('Admin')->forceDelete();
        DB::select('SET FOREIGN_KEY_CHECKS=1;');

        $admin = new Role;
        $admin->platform = 'Admin';
        $admin->name = 'admin';
        $admin->display_name = 'Administrator';
        $admin->save();
        $admin->attachPermissions(Permission::all());

        $user = new User;
        $user->name = 'Jimmy Setiawan';
        $user->email = 'admin@admin.com';
        $user->username = 'admin';
        $user->password = bcrypt('123123');
        $user->platform = 'Admin';
        $user->active = 1;
        $user->save();
        $user->attachRole($admin);

        Preference::insert([
            [
                'id' => Uuid::generate()->string,
                'key' => 'logo',
                'value' => url('assets/images/logo.png'),
                'platform' => 'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ], [
                'id' => Uuid::generate()->string,
                'key' => 'activation_email_template',
                'value' => '<p>Hi, Please click this link %LINK% to continue your registration</p>',
                'platform' => 'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        $productCategories = ['Handphone & Tablet', 'Olahraga & Aktivitas Luar Ruang', 'Komputer & Laptop', 'Kamera', 'Mainan & Video Games', 'Peralatan Elektronik', 'Fashion Pria', 'Home & Living', 'Fashion Wanita', 'Ibu & Anak', 'Tiket & Voucher', 'Kesehatan & Kecantikan', 'Otomotif', 'Galeri Indonesia'];
        foreach ($productCategories as $productCategoryName) {
            do {
                $code = (string) rand(100, 999);
            } while (ProductCategory::whereCode($code)->first() != null);

            $parentProductCategory        = new ProductCategory;
            $parentProductCategory->code  = $code;
            $parentProductCategory->name  = $productCategoryName;
            $parentProductCategory->save();
        }
        $numberChildren = 3;
        foreach ($productCategories as $productCategoryName) {
            $parentProductCategory = ProductCategory::whereName($productCategoryName)->first();

            for ($i = 0; $i < $numberChildren; $i++) {
                do {
                    $code = (string) rand(100, 999);
                } while (ProductCategory::whereCode($code)->first() != null);

                $subParentProductCategory            = new ProductCategory;
                $subParentProductCategory->code      = $code;
                $subParentProductCategory->name      = (string) $productCategoryName . ' ' . $i;
                $subParentProductCategory->parent_id = $parentProductCategory->id;
                $subParentProductCategory->save();

                for ($j = 0; $j < $numberChildren; $j++) {
                    do {
                        $code = (string) rand(100, 999);
                    } while (ProductCategory::whereCode($code)->first() != null);

                    $childProductCategory            = new ProductCategory;
                    $childProductCategory->code      = $code;
                    $childProductCategory->name      = (string) $subParentProductCategory->name . '.' . $j;
                    $childProductCategory->parent_id = $subParentProductCategory->id;
                    $childProductCategory->save();
                }
            }
        }
    }
}
