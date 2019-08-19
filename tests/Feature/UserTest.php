<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Entities\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;

/**
 * Class ViewOneUserTest
 * @package Tests\Feature
 */
class UserTest extends TestCase
{

//    use DatabaseMigrations;
    use withFaker;

    /**
     * @group updateMyself
     */
    public function testCanUpdateMyself()
    {
        //Arrangement
        $this->withoutExceptionHandling();
        // $data to be submitted for update.
        $data = [

            //TODO: Normally name should not be changed, only nick name.
            'name' => $this->faker->name,

            //TODO: Normally for common user to change email it won't be one step.
            'email' => $this->faker->unique()->safeEmail,

            //TODO: Normally for common user to change phone number, old phone number will be needed for verification.
            // And a verification text code will sent to user by phone or email and cached for verificatioin. it won't be one step.
            'phone' => $this->faker->unique()->phoneNumber,

            //TODO: Normally for common user to change password, old password will be needed for verification.
            // And a verification text code will sent to user by phone or email and cached for verificatioin. it won't be one step.
            'password' => $this->faker->password(),
        ];

        $userReturned = Arr::except($data, ['password']);

        $User = factory(User::class)->create();

        //Action
        $response = $this->json('PUT', "/api/users/{$User->_id}", $data)
            ->assertStatus(200)
            ->assertJsonStructure(
                ['code', 'message', 'result' => ['_id', 'name', 'email', 'phone', 'created_at', 'updated_at']]
            )
            ->assertJsonFragment($userReturned);

        //assert database record is updated
        $this->assertDatabaseHas('users',
            $data);
    }


    /**
     * @group viewMyself
     */
    public function testCanViewMyself()
    {
        //Arrangement
        $this->withoutExceptionHandling();
        //Create a blog User
        $user = factory(User::class)->create();

        //Action
        $response = $this->json('GET', "/api/users/{$user->_id}")
            ->assertStatus(200)
            ->assertJsonFragment(json_decode(json_encode($user), true))
            ->assertJsonStructure(
                ['code', 'message', 'result' =>
                    ['_id', 'name', 'email', 'phone', 'created_at', 'updated_at']
            ]);
    }
}

