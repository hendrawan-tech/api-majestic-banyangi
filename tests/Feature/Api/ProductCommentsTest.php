<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Product;
use App\Models\Comment;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductCommentsTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create(['email' => 'admin@admin.com']);

        Sanctum::actingAs($user, [], 'web');

        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_gets_product_comments()
    {
        $product = Product::factory()->create();
        $comments = Comment::factory()
            ->count(2)
            ->create([
                'destination_id' => $product->id,
            ]);

        $response = $this->getJson(
            route('api.products.comments.index', $product)
        );

        $response->assertOk()->assertSee($comments[0]->comment);
    }

    /**
     * @test
     */
    public function it_stores_the_product_comments()
    {
        $product = Product::factory()->create();
        $data = Comment::factory()
            ->make([
                'destination_id' => $product->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.products.comments.store', $product),
            $data
        );

        $this->assertDatabaseHas('comments', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $comment = Comment::latest('id')->first();

        $this->assertEquals($product->id, $comment->destination_id);
    }
}
