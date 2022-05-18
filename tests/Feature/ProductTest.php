<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ProductTest extends TestCase
{
    protected function authenticate(){
        $user = User::create([
            'name'=>'test',
            'email'=>rand(12345,678910).'test@gmail.com',
            'password'=>Hash::make('secret9874')
        ]);

        if(!auth()->attempt(['email'=>$user->email, 'password'=>'secret9874'])){
            return response(['message'=>'Login credentials are invalid.']);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;
        Log::debug(':::::::::'.$accessToken);

        return $accessToken;
    }

    public function test_create_product(){
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization'=>'Bearer '.$token,
        ])->json('POST', 'api/product', [
            'name'=>'Test product',
            'sku'=> 'test-sku',
            'upc'=>'test-upc'
        ]);

        Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }

    public function test_update_product(){
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization'=>'Bearer '.$token,
        ])->json('PUT', 'api/product/3', [
            'name'=>'Test product111',
            'sku'=>'test-sku',
            'upc'=>'test-upc'
        ]);

        Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }

    public function test_find_product(){
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization'=>'Bearer '.$token,
        ])->json('GET', 'api/product/1');

        Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }

    public function test_get_all_product(){
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization'=>'Bearer '.$token
        ])->json('GET', 'api/product');

        Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);
    }

    public function test_delete_product(){
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization'=>'Bearer '.$token
        ])->json('DELETE', 'api/product/5');

        Log::info(1, [$response->getContent()]);

        $response->assertStatus(200);

    }
}
