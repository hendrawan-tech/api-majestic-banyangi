<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Like;
use App\Models\Product;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductLikesTest extends TestCase
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
    public function it_gets_product_likes()
    {
        $product = Product::factory()->create();
        $likes = Like::factory()
            ->count(2)
            ->create([
                'post_id' => $product->id,
            ]);

        $response = $this->getJson(route('api.products.likes.index', $product));

        $response->assertOk()->assertSee($likes[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_product_likes()
    {
        $product = Product::factory()->create();
        $data = Like::factory()
            ->make([
                'post_id' => $product->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.products.likes.store', $product),
            $data
        );

        $this->assertDatabaseHas('likes', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $like = Like::latest('id')->first();

        $this->assertEquals($product->id, $like->post_id);
    }
}
