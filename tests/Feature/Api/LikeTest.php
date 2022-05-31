<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Like;

use App\Models\Product;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LikeTest extends TestCase
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
    public function it_gets_likes_list()
    {
        $likes = Like::factory()
            ->count(5)
            ->create();

        $response = $this->getJson(route('api.likes.index'));

        $response->assertOk()->assertSee($likes[0]->id);
    }

    /**
     * @test
     */
    public function it_stores_the_like()
    {
        $data = Like::factory()
            ->make()
            ->toArray();

        $response = $this->postJson(route('api.likes.store'), $data);

        $this->assertDatabaseHas('likes', $data);

        $response->assertStatus(201)->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_updates_the_like()
    {
        $like = Like::factory()->create();

        $user = User::factory()->create();
        $product = Product::factory()->create();

        $data = [
            'user_id' => $user->id,
            'post_id' => $product->id,
        ];

        $response = $this->putJson(route('api.likes.update', $like), $data);

        $data['id'] = $like->id;

        $this->assertDatabaseHas('likes', $data);

        $response->assertOk()->assertJsonFragment($data);
    }

    /**
     * @test
     */
    public function it_deletes_the_like()
    {
        $like = Like::factory()->create();

        $response = $this->deleteJson(route('api.likes.destroy', $like));

        $this->assertDeleted($like);

        $response->assertNoContent();
    }
}
