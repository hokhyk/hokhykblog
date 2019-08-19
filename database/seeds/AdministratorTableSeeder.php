<?php

use App\Entities\AdminUser;
use Illuminate\Database\Seeder;

class AdministratorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(AdminUser::class)->create(
            [
                'name' => 'admin',
                'email' => 'admin@admin.org',
                'phone' => '006421000001',
                'password' => '123456',
            ]
        );

        factory(AdminUser::class)->create();
    }
}
