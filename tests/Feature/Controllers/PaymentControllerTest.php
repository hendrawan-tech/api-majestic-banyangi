<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Payment;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentControllerTest extends TestCase
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
    public function it_displays_index_view_with_payments()
    {
        $payments = Payment::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('payments.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.payments.index')
            ->assertViewHas('payments');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_payment()
    {
        $response = $this->get(route('payments.create'));

        $response->assertOk()->assertViewIs('app.payments.create');
    }

    /**
     * @test
     */
    public function it_stores_the_payment()
    {
        $data = Payment::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('payments.store'), $data);

        $this->assertDatabaseHas('payments', $data);

        $payment = Payment::latest('id')->first();

        $response->assertRedirect(route('payments.edit', $payment));
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_payment()
    {
        $payment = Payment::factory()->create();

        $response = $this->get(route('payments.show', $payment));

        $response
            ->assertOk()
            ->assertViewIs('app.payments.show')
            ->assertViewHas('payment');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_payment()
    {
        $payment = Payment::factory()->create();

        $response = $this->get(route('payments.edit', $payment));

        $response
            ->assertOk()
            ->assertViewIs('app.payments.edit')
            ->assertViewHas('payment');
    }

    /**
     * @test
     */
    public function it_updates_the_payment()
    {
        $payment = Payment::factory()->create();

        $data = [
            'name' => $this->faker->name,
            'number' => $this->faker->text(255),
            'bank' => $this->faker->text(255),
        ];

        $response = $this->put(route('payments.update', $payment), $data);

        $data['id'] = $payment->id;

        $this->assertDatabaseHas('payments', $data);

        $response->assertRedirect(route('payments.edit', $payment));
    }

    /**
     * @test
     */
    public function it_deletes_the_payment()
    {
        $payment = Payment::factory()->create();

        $response = $this->delete(route('payments.destroy', $payment));

        $response->assertRedirect(route('payments.index'));

        $this->assertDeleted($payment);
    }
}
