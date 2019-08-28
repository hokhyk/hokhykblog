<?php

namespace Tests\Feature;

use App\Entities\User;
use Illuminate\Support\Arr;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Entities\AdminUser;
use Illuminate\Support\Facades\Hash;


class AdministratorLogoutTest extends TestCase
{
    use withFaker;


    /**
     * @group AdminUserLogout
     */
    function testAdminUserCanLogout()
    {

        //Arrangement
        $this->withoutExceptionHandling();

        $password =$this->faker->password();
        $userinfo = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->unique()->phoneNumber,
            'password' => Hash::make($password),
        ];

        $user = factory(User::class)->create($userinfo);

        $response = $this->json('POST', '/api/users/login',
            [
                'name' => $userinfo['name'],
//                'email' => $userinfo['email'],
//                'phone' => $userinfo['phone'],
                'password' => $password,
            ])
            ->assertStatus(200)
            ->assertJsonStructure(
                ['code', 'message', 'result' =>
                    ['token_type', 'expires_in', 'access_token', 'refresh_token',]
                ]
            );

        // set up the bearer token header.
        $accessToken = $response->result->access_token;
        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$accessToken,
        ];

        //Action
        $logout_response = $this->json('POST', '/api/users/logout', [], $headers)
            ->assertJson([
                'code' => '200',
                'message' => 'Log out successfully.',
            ]);
    }
}


