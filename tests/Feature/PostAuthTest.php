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
            'email' => 'asd@asd.com',
            'password' => 'asdasdasd',
        ]);
        
        $response
        ->assertStatus(200)
        ->assertJsonStructure([
            'token', 'success'
        ]);
    }
}
