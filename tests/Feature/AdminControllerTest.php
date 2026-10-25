<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Contact;
use App\Models\Category;
use App\Models\User;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * キーワードフィルタが有効か
     */
    public function test_keyword_filter(): void
    {
        //Arrange
        $user = User::factory()->create();
        $category = Category::create([
            'content' => 'テストカテゴリー',
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        Contact::factory()->create(['email' => 'target@testsample.com']);
        Contact::factory()->create(['email' => 'other@testsample.com']);

        //Act
        $response = $this->actingAs($user)->get('/admin?keyword=target');

        //Assert
        $response->assertStatus(200);
        $response->assertSee('target@testsample.com');
        $response->assertDontSee('other@testsample.com');
    }

    /**
     * 性別フィルタが有効か
     */
    public function test_gender_filter(): void
    {
        //Arrange
        $user = User::factory()->create();
        $category = Category::create([
            'content' => 'テストカテゴリー',
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        Contact::factory()->create(['gender' => '1', 'email' => 'target@testsample.com']);
        Contact::factory()->create(['gender' => '2', 'email' => 'other@testsample.com']);

        //Act
        $response = $this->actingAs($user)->get('/admin?&gender=1');

        //Assert
        $response->assertStatus(200);
        $response->assertSee('target@testsample.com');
        $response->assertDontSee('other@testsample.com');
    }

    /**
     * カテゴリーフィルタが有効か
     */
    public function test_category_filter(): void
    {
        //Arrange
        $user = User::factory()->create();
        Category::create([
            'id' => '1',
            'content' => 'テストカテゴリー1',
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        Category::create([
            'id' => '2',
            'content' => 'テストカテゴリー2',
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        Contact::factory()->create(['category_id' => '1', 'email' => 'target@testsample.com']);
        Contact::factory()->create(['category_id' => '2', 'email' => 'other@testsample.com']);

        //Act
        $response = $this->actingAs($user)->get('/admin?category_id=1');

        //Assert
        $response->assertStatus(200);
        $response->assertSee('target@testsample.com');
        $response->assertDontSee('other@testsample.com');
    }

    /**
     * 日付フィルタが有効か
     */
    public function test_data_filter(): void
    {
        //Arrange
        $user = User::factory()->create();
        $category = Category::create([
            'content' => 'テストカテゴリー',
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        Contact::factory()->create(['created_at' => '2026-10-20', 'email' => 'target@testsample.com']);
        Contact::factory()->create(['created_at' => '2026-10-21', 'email' => 'other@testsample.com']);

        //Act
        $response = $this->actingAs($user)->get('/admin?date=2026-10-20');

        //Assert
        $response->assertStatus(200);
        $response->assertSee('target@testsample.com');
        $response->assertDontSee('other@testsample.com');
    }

    //不正な性別値は拒否すること
    public function test_gender_cannotinput(): void
    {
        //Arrange・Act
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/admin?gender=' . random_int(4,9));

        //Assert
        $response->assertSessionHasErrors('gender');
    }
}
