<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ApiAddTest extends TestCase
{
    use withFaker;

    /**
     * @group testCanAddTwoNumbers
     */
    public function testCanAddTwoNumbers() {
        //Arrangement
        $this->withoutExceptionHandling();

        $payload = [
            'num1' => $this->faker()->randomFloat(),
            'num2' => $this->faker()->randomFloat(),
            ];

        //Action
        $response = $this->post("/api/add", $payload)
            //Assertion
            ->assertStatus(200)
            ->assertJson(['code' => 200,
                          'message' => 'Addition is successful.',
                          'result' => bdadd($payload['num1'], $payload['num2'], 10)]);
    }
}
