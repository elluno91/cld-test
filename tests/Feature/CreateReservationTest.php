<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateReservationTest extends TestCase
{
    public $token;
    /**
     * A basic feature test example.
     */
    private function getToken() {

        $responseJSON = $this->post(Route('authenticate.store'), [
            'app_id' => '123456',
            'app_secret' => '123456',
        ])->decodeResponseJson()->json();
        return $responseJSON;

    }

    public function test_authenticate_and_get_token(): void
    {
        $responseJSON = $this->getToken();
        $this->assertFalse(key_exists('error', $responseJSON));

    }

    public function test_create_reservation(): void {
        $responseJSON = $this->getToken();

        $response = $this->get(Route('customer.index'), [
            'Token' => $responseJSON['token'],
        ]);
        $responseDecoded = $response->decodeResponseJson()->json();
        $response->assertStatus(200);

    }
}
