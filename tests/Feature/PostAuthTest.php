<?php

namespace Tests\Feature;

use Tests\TestCase;

class PostAuthTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCredentials()
    {
        $this->withoutExceptionHandling();
        
        $response = $this->post('/api/credentials',[
            'email' => env('USER_TEST'),
            'password' => env('PASSWORD_TEST'),
        ]);
        
        $response
        ->assertStatus(200)
        ->assertJsonStructure([
            'token', 'success'
        ]);
    }
}
