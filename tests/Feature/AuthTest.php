<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class AuthTest extends TestCase
{
   public function testRegister(){
       $response = $this->json('POST', '/api/register', [
           'name'=>$name='Test',
           'email' => $email = time().'test@example.com',
           'password'=>$password = '123456789'
       ]);

    //    Log::info(1, [$response->getContent()]);

       $response->assertStatus(200);

       // receive token
       $this->assertArrayHasKey('access_token', $response->json());
   }
   
   public function testLogin(){
       User::create([
           'name'=>'Test',
           'email'=>$email=time().'@example.com',
           'password'=>$password=bcrypt('123456789')
       ]);

    //    simulated landing
       $response = $this->json('POST', route('login'), [
           'email'=>$email,
           'password'=>'123456789'
       ]);

       Log::info(1, [$response->getContent()]);

       // 로그인 성공 여부 확인 및 토큰 수신
       $response->assertStatus(200);

       $this->assertArrayHasKey('access_token', $response->json());

       // delete users
       User::where('email', $email)->delete();
   }
}
