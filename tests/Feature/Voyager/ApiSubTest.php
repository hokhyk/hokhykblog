<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiSubTest extends TestCase
{
    use withFaker;

    /**
     * @group testCanSubTwoNumbers
     */
    public function testCanSubTwoNumbers() {
        //Arrangement
        $this->withoutExceptionHandling();

        $payload = [
            'num1' => $this->faker()->randomFloat(),
//            'num1' => 71217067.7950307000,
            'num2' => $this->faker()->randomFloat(),
//            'num2' => 142434079.5170460000,
            ];

        //Action
        $response = $this->json( 'POST', "/api/voyager/v1/sub", $payload)
            //Assertion
            ->assertStatus(200)
            ->assertJson(['code' => 200,
                          'message' => 'Subtraction is successful.',
                          'result' => bcsub($payload['num1'], $payload['num2'], 10)]);
    }
}
