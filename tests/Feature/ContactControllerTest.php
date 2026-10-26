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

    /**
     * お問い合わせ保存テスト
     */

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

    /**
     * 画面アクセステスト
     */

    //入力ページの表示
    public function test_display_form(): void
    {
        //Arrange
        $categories = Category::create([
            'content' => 'テストカテゴリー',
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        $tags = Tag::factory()->create([
            'name' => 'テストタグ',
        ]);

        // Act
        $response = $this->get('/');

        // Assert
        $response->assertViewIs('contact.index');
        $response->assertViewHas(['categories', 'tags']);
        $response->assertSee(['テストカテゴリー', 'テストタグ']);
    }

    //確認ページの表示 
    public function test_display_confirm(): void
    {
        //Arrange
        $categories = Category::create([
            'content' => 'テストカテゴリー',
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        $tags = Tag::factory()->create([
            'name' => 'テストタグ',
        ]);

        // Act
        $response = $this->post('/contacts/confirm', [
            'first_name' => 'テスト苗字',
            'last_name' => 'テスト名前',
            'gender' => '1',
            'email' => 'test@sample.com',
            'tel' => '09012345678',
            'address' => 'テスト住所',
            'detail' => 'テスト内容',
            'category_id' => $categories->id,
            'tag_ids' => [$tags->id],
        ]);

        // Assert
        $response->assertViewIs('contact.confirm');
        $response->assertSee([
            'テストカテゴリー', 'テストタグ', 'テスト苗字', 'テスト名前', '男性',
            'test@sample.com', '09012345678', 'テスト住所', 'テスト内容',
            ]);
    }

    //確認ページのエラー表示 
    public function test_display_confirm_validation(): void
    {
        //Arrange・Act
        $response = $this->post('/contacts/confirm');

        // Assert
        $response->assertStatus(302);
    }

    //contact保存
    public function test_post_contact(): void
    {
        //Arrange
        $categories = Category::create([
            'id' => '1',
            'content' => 'テストカテゴリー',
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        $tags = Tag::factory()->create([
            'name' => 'テストタグ',
        ]);

        // Act
        $response = $this->post('/contacts', [
            'first_name' => 'テスト苗字',
            'last_name' => 'テスト名前',
            'gender' => '1',
            'email' => 'test@sample.com',
            'tel' => '09012345678',
            'address' => 'テスト住所',
            'detail' => 'テスト内容',
            'category_id' => $categories->id,
            'tag_ids' => [$tags->id],
        ]);

        // Assert
        $response->assertRedirect('/thanks');
        $this->assertDatabaseHas('contacts', [
            'first_name' => 'テスト苗字',
            'last_name' => 'テスト名前',
            'gender' => '1',
            'email' => 'test@sample.com',
            'tel' => '09012345678',
            'address' => 'テスト住所',
            'category_id' => '1',
            'detail' => 'テスト内容',
            ]);
        $this->assertDatabaseCount('contact_tag', 1);
    }
}
