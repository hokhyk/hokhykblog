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
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->unique()->phoneNumber,
            'password' => $this->faker->password(),
        ];

        $User = factory(User::class)->create();

        //Action
        $response = $this->json('PUT', "/api/Users/{$User->_id}", $data)
            ->assertStatus(200)
            ->assertJsonStructure(
                ['code', 'message', 'result' => ['_id', 'title', 'User_content', 'created_at', 'updated_at']]
            )
            ->assertJsonFragment($data);

        //assert database record is updated
        $this->assertDatabaseHas('Users',
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

