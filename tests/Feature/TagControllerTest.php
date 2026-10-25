<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Tag;
use App\Models\User;

class TagControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * タグの新規作成
     */
    
    //タグ名の必須入力
    public function test_tag_name_input_required(): void
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $response = $this->actingAs($user)->post('/admin/tags', [
            'name' => '',
        ]);

        // Assert
        $response->assertSessionHasErrors('name');
    }

    //タグ名の文字数制限
    public function test_tag_name_input_max(): void
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $response = $this->actingAs($user)->post('/admin/tags', [
            'name' => str_repeat('あ', 51),
        ]);

        // Assert
        $response->assertSessionHasErrors('name');
    }

    //タグ名の重複禁止
    public function test_tag_name_input_unique(): void
    {
        // Arrange
        $user = User::factory()->create();
        Tag::factory()->create(['name' => 'タグ1']);

        // Act
        $response = $this->actingAs($user)->post('/admin/tags', [
            'name' => 'タグ1',
        ]);

        // Assert
        $response->assertSessionHasErrors('name');
    }

    /**
     * タグの更新
     */

    //自身の維持は可能
    public function test_tag_name_edit_own(): void
    {
        // Arrange
        $user = User::factory()->create();
        $tag = Tag::factory()->create(['name' => 'タグ1']);

        // Act
        $response = $this->actingAs($user)->put('/admin/tags/' . $tag->id , [
            'name' => 'タグ1',
        ]);

        // Assert
        $response->assertRedirect('/admin');
    }

    //タグ名の重複禁止
    public function test_tag_name_edit_unique(): void
    {
        // Arrange
        $user = User::factory()->create();
        $tag = Tag::factory()->create(['id' => '1', 'name' => 'タグ1']);
        $tag = Tag::factory()->create(['id' => '2', 'name' => 'タグ2']);

        // Act
        $response = $this->actingAs($user)->put('/admin/tags/2', [
            'name' => 'タグ1',
        ]);

        // Assert
        $response->assertSessionHasErrors('name');
    }
}
