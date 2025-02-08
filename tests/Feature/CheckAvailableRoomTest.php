<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CheckAvailableRoomTest extends TestCase
{
    public $token;
    /**
     * A basic feature test example.
     */
    private function getToken() {

        $responseJSON = $this->post(Route('authenticate'), [
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

    public function test_check_available_room(): void {
        $responseJSON = $this->getToken();
        $this->assertFalse(key_exists('error', $responseJSON));
        if(isset($responseJSON['token'])) {
            $token = $responseJSON['token'];
            $responseJSON = $this->get(Route('room')."?date_check_in=2025-01-26&date_check_out=2025-01-27&pax_count=2",["token" => $token])->decodeResponseJson()->json();

            $this->assertFalse(key_exists('error', $responseJSON));
        }

    }
}
