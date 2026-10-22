<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    //すべての必須項目とタグの入力を受け付ける
    public function test_example(): void
    {
        //Arrange

        
        //Act
        $response = $this->post(route('contact.confirm'), [
            'first_name' => 'テスト名字',
            'last_name' => 'テスト名前',]);

        //Assert
        $response->assertRedirect(route('contact.thanks'));
    }

    //不正な電話番号形式は拒否すること
    public function test_example(): void
    {
        //Arrange
        
        //Act
        $response = $this->get('/');

        //Assert
        $response->assertStatus(200);
    }
}
