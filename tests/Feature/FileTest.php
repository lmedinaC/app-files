<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class FileTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test()
    {
        $this->withoutExceptionHandling();
        $token = $this->post('/api/credentials',[
            'email' => 'asd@asd.com',
            'password' => 'asdasdasd',
        ]);

        $data = json_decode($token->getContent());
        // $this->assertEquals('asdasd',$data->token);
        $token->assertStatus(200)
        ->assertJsonStructure([
            'token', 'success'
        ]);

        $user = User::where('email', 'asd@asd.com')->first();
        $token = JWTAuth::fromUser($user);
        
        $stub = __DIR__.'/imageTest/test.txt';
        $name = 'test1.png';
        $path = sys_get_temp_dir().'/'.$name;
        copy($stub, $path);
        $file = new UploadedFile($path, $name, 'csv', null, true);
        $response = $this->post('/api/files', [], [], ['document' => $file], ['Accept' => 'application/json']);

        $response
        ->assertStatus(200);
    }
}
