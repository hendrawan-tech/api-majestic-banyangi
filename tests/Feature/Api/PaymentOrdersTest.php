<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Order;
use App\Models\Payment;

use Tests\TestCase;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentOrdersTest extends TestCase
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
    public function it_gets_payment_orders()
    {
        $payment = Payment::factory()->create();
        $orders = Order::factory()
            ->count(2)
            ->create([
                'payment_id' => $payment->id,
            ]);

        $response = $this->getJson(
            route('api.payments.orders.index', $payment)
        );

        $response->assertOk()->assertSee($orders[0]->code);
    }

    /**
     * @test
     */
    public function it_stores_the_payment_orders()
    {
        $payment = Payment::factory()->create();
        $data = Order::factory()
            ->make([
                'payment_id' => $payment->id,
            ])
            ->toArray();

        $response = $this->postJson(
            route('api.payments.orders.store', $payment),
            $data
        );

        $this->assertDatabaseHas('orders', $data);

        $response->assertStatus(201)->assertJsonFragment($data);

        $order = Order::latest('id')->first();

        $this->assertEquals($payment->id, $order->payment_id);
    }
}
