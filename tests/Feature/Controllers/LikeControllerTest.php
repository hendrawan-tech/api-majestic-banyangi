<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Like;

use App\Models\Product;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LikeControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(
            User::factory()->create(['email' => 'admin@admin.com'])
        );

        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_displays_index_view_with_likes()
    {
        $likes = Like::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('likes.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.likes.index')
            ->assertViewHas('likes');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_like()
    {
        $response = $this->get(route('likes.create'));

        $response->assertOk()->assertViewIs('app.likes.create');
    }

    /**
     * @test
     */
    public function it_stores_the_like()
    {
        $data = Like::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('likes.store'), $data);

        $this->assertDatabaseHas('likes', $data);

        $like = Like::latest('id')->first();

        $response->assertRedirect(route('likes.edit', $like));
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_like()
    {
        $like = Like::factory()->create();

        $response = $this->get(route('likes.show', $like));

        $response
            ->assertOk()
            ->assertViewIs('app.likes.show')
            ->assertViewHas('like');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_like()
    {
        $like = Like::factory()->create();

        $response = $this->get(route('likes.edit', $like));

        $response
            ->assertOk()
            ->assertViewIs('app.likes.edit')
            ->assertViewHas('like');
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

        $response = $this->put(route('likes.update', $like), $data);

        $data['id'] = $like->id;

        $this->assertDatabaseHas('likes', $data);

        $response->assertRedirect(route('likes.edit', $like));
    }

    /**
     * @test
     */
    public function it_deletes_the_like()
    {
        $like = Like::factory()->create();

        $response = $this->delete(route('likes.destroy', $like));

        $response->assertRedirect(route('likes.index'));

        $this->assertDeleted($like);
    }
}
