<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Tag;

class ContactModelTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 1つのカテゴリが複数のcontactを持つ
     */
    public function test_category_has_many_contacts(): void
    {
        // Arrange・Act
        $category = Category::create([
            'content' => 'テストカテゴリー',
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        $contacts = Contact::factory()->count(3)->create([
            'category_id' => $category->id,
        ]);

        // Assert
        $this->assertCount(3, $category->contacts);
        foreach ($contacts as $contact) {
            $this->assertTrue($category->contacts->contains($contact));
        };
    }

    /**
     * 1つのcontactが特定のカテゴリに属し、複数のタグと同期できる
     */
    public function test_contact_has_category_and_tags(): void
    {
        // Arrange・Act
        $category = Category::create([
            'content' => 'テストカテゴリー',
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        $tags = Tag::factory()->count(3)->create();
        $contact = Contact::factory()->create([
            'category_id' => $category->id,
        ]);
        $contact->tags()->attach($tags);

        // Assert
        $this->assertEquals($category->id, $contact->category_id);
        $this->assertCount(3, $contact->tags);
        foreach ($tags as $tag) {
            $this->assertTrue($contact->tags->contains($tag));
        };
    }

    /**
     * 1つのタグが複数のcontactsに紐づいている
     */
    public function test_tag_has_many_contacts(): void
    {
        // Arrange・Act
        $category = Category::create([
            'content' => 'テストカテゴリー',
            'updated_at' => now(),
            'created_at' => now(),
        ]);
        $tag = Tag::factory()->create();
        $contacts = Contact::factory()->count(3)->create([
            'category_id' => $category->id,
        ]);
        $tag->contacts()->attach($contacts);

        // Assert
        $this->assertCount(3, $tag->contacts);
        foreach ($contacts as $contact) {
            $this->assertTrue($tag->contacts->contains($contact));
        };
    }
}

