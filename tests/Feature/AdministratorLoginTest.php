<?php

namespace Tests\Feature;

use App\Entities\User;
use Illuminate\Support\Arr;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Entities\AdminUser;
use Illuminate\Support\Facades\Hash;



class AdminUserLoginTest extends TestCase
{
    use withFaker;


    /**
     * @group AdminUserLogin
     */
    function testAdminUserCanLogin() {

        //Arrangement
        $this->withoutExceptionHandling();

        $userinfo = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->unique()->phoneNumber,
            'password' => $this->faker->password(),
        ];

        $admin = [
            'name' => 'hok',
            'email' => 'hokhyk@aliyun.com',
            'phone' => '0064211583830',
            'password' => Hash::make('9900ii'),
        ];

        $user = factory(User::class)->create($admin);
        $token = '';

        //Action
        $response = $this->json('POST', '/api/users/login',
            [
                'name' => $userinfo['name'],
//                'email' => $userinfo['email'],
//                'email' => '999',
//                'phone' => $userinfo['phone'],
                'password' => $userinfo['password'],
            ])->assertStatus(200)
            ->assertJsonStructure(
                ['code', 'message', 'result' =>
                    ['access_token', 'refresh_token', ]
                ]
            )
            ->assertJsonFragment($token);

        //assert database records
        $this->assertDatabaseHas('users',
            $user
        );
    }
}


