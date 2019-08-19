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
class ManageUserTest extends TestCase
{

//    use DatabaseMigrations;
    use withFaker;

    /**
     * @group destroyUser
     */
    public function testCanDestroyUser()
    {
        //Arrangement
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        //Action
        $response = $this->json('DELETE', "/api/admin/manageusers/{$user->_id}")
            ->assertJson(['code' => 200, 'message' => 'User is deleted.']);

        //assert database record is deleted.
        $this->assertDatabaseMissing('users', json_decode(json_encode($user), true));
    }

    /**
     * @group canNotDestroyEmpty
     */
    public function testCanNotDestroyEmptyUser()
    {
        //Arrangement
        $this->withoutExceptionHandling();

        $id = $this->faker->randomDigitNotNull();

        //Action
        $response = $this->json('DELETE', "/api/admin/manageusers/{$id}")
            ->assertJson(['code' => 417, 'message' => 'Not an user found.']);
    }

    /**
     * @group updateUserAdmin
     */
    public function testCanUpdateUser()
    {
        //Arrangement
        $this->withoutExceptionHandling();
        // $data to be submitted for update.
        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->unique()->phoneNumber,
            'password' => $this->faker->password(),
        ];

        $userReturned = Arr::except($data, ['password']);

        $User = factory(User::class)->create();

        //Action
        $response = $this->json('PUT', "/api/admin/manageusers/{$User->_id}", $data)
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
     * @group viewAllUsers
     */
    public function testCanViewAllUsers()
    {
        //Arrangement
        $this->withoutExceptionHandling();

        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        //Action
        $response = $this->json('GET', "/api/admin/manageusers")
            ->assertStatus(200)
            ->assertJsonStructure(
                ['code', 'message', 'result' => [
                        ['_id', 'name', 'email', 'phone', 'created_at', 'updated_at'],
                    ]
                ]
            )
            ->assertJsonFragment(
                [
                    json_decode(json_encode($user1), true)
                ]
            )
            ->assertJsonFragment(
                [
                    json_decode(json_encode($user2), true)
                ]
            );
    }


    /**
     * @group storeUser
     */
    public function testCanStoreOneUser()
    {
        //Arrangement
        $this->withoutExceptionHandling();

        $user = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->unique()->phoneNumber,
            'password' => $this->faker->password(),
        ];

        $userReturned = Arr::except($user, ['password']);

        //Action
        $response = $this->json('POST', "/api/admin/manageusers", $user)
            ->assertStatus(201)
            ->assertJsonStructure(
                ['code', 'message', 'result' =>
                    ['_id', 'name', 'email', 'phone', 'created_at', 'updated_at']
                ]
            )
            ->assertJsonFragment($userReturned);

        //assert database records
        $this->assertDatabaseHas('users',
            $user
        );
    }

    /**
     * @group viewUser
     */
    public function testCanViewOneUser()
    {
        //Arrangement
        $this->withoutExceptionHandling();
        //Create a blog User
        $user = factory(User::class)->create();

        //Action
        $response = $this->json('GET', "/api/admin/manageusers/{$user->_id}")
            ->assertStatus(200)
            ->assertJsonFragment(json_decode(json_encode($user), true))
            ->assertJsonStructure(
                ['code', 'message', 'result' =>
                    ['_id', 'name', 'email', 'phone', 'created_at', 'updated_at']
            ]);
    }
}

