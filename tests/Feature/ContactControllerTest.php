<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase;

    //すべての必須項目・タグの入力を受け付ける
    public function test_contact_input(): void
    {
        //Arrange
        $category = Category::create([
            'content' => 'テストカテゴリー',
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        $response = Contact::factory()->create();
        $tag = Tag::factory()->create();

        // Act
        $response = $this->post('/contacts', [
            'category_id' => Category::inRandomOrder()->value('id'),
            'first_name' => fake()->name(),
            'last_name' => fake()->name(),
            'gender' => fake()->randomElement(['1', '2', '3']),
            'email' => fake()->email(),
            'tel' => fake()->numerify('0##########'),
            'address' => fake()->address(),
            'building' => fake()->text(30),
            'detail' => fake()->sentence(),
            'updated_at' => now(),
            'created_at' => now(),
            'tags_id' => Tag::inRandomOrder()->value('id'),
        ]);

        //Assert
        $response->assertRedirect(route('contact.thanks'));
    }

    //不正な電話番号形式は拒否すること
    public function test_tel_cannotinput(): void
    {
        //Arrange・Act
         $response = $this->post('/contacts', [
            'tel' => fake()->numerify('1~9,##########'),
         ]);

        //Assert
        $response->assertSessionHasErrors('tel');
    }
}
