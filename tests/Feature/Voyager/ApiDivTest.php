<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiDivtTest extends TestCase
{
    use withFaker;

    /**
     * @group testCanDivTwoNumbers
     */
    public function testCanDivTwoNumbers() {
        //Arrangement
        $this->withoutExceptionHandling();

        $payload = [
            'num1' => $this->faker()->randomFloat(),
//            'num1' => 71217067.7950307000,
            'num2' => $this->faker()->randomFloat(),
//            'num2' => 142434079.5170460000,
            ];

        //Action
        $response = $this->json( 'POST', "/api/voyager/v1/div", $payload)
            //Assertion
            ->assertStatus(200)
            ->assertJson(['code' => 200,
                          'message' => 'Division is successful.',
                          'result' => bcdiv($payload['num1'], $payload['num2'], 10)]);
    }

    /**
     * @group testZeroDivisor
     */
    public function testZeroDivisor() {
        //Arrangement
        $this->withoutExceptionHandling();

        $payload = [
            'num1' => $this->faker()->randomFloat(),
            'num2' => 0,
        ];

        //Action
        $response = $this->json( 'POST', "/api/voyager/v1/div", $payload)->dump()
            //Assertion
            ->assertStatus(200)
            ->assertJson(['code' => 200,
                'error' => 'Division is zero.',]
            );
    }
}
