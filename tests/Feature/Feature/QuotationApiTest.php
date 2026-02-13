<?php

namespace Tests\Feature\Feature;

use App\Quotation;
use App\User;
use App\Customer;
use App\Product;
use App\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class QuotationApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;
    protected $customer;
    protected $product;
    protected $service;

    protected function setUp(): void
    {
        parent::setUp();
        // Seed some data for foreign keys
        $this->user = User::factory()->create();
        $this->customer = Customer::factory()->create();
        $this->product = Product::factory()->create();
        $this->service = Service::factory()->create();

        // Authenticate a user for API requests
        Passport::actingAs($this->user);
    }

    /** @test */
    public function an_authenticated_user_can_view_any_quotations()
    {
        // Create quotations for the authenticated user
        Quotation::factory()->count(3)->create(['user_id' => $this->user->id]);
        // Create quotations for another user
        Quotation::factory()->count(2)->create();

        $response = $this->getJson('/api/quotations');

        $response->assertStatus(200)
            ->assertJsonCount(5, 'data'); // Should see all quotations, as per policy viewAny
    }

    /** @test */
    public function an_unauthenticated_user_cannot_view_quotations()
    {
        Passport::actingAs(null); // Unauthenticate the user
        $response = $this->getJson('/api/quotations');
        $response->assertStatus(401); // Unauthorized
    }

    /** @test */
    public function an_authenticated_user_can_create_a_quotation()
    {
        $data = [
            'customer_id' => $this->customer->id,
            'title' => 'Test Quotation',
            'notes' => 'Some notes for the quotation.',
            'products' => [
                ['product_id' => $this->product->id, 'quantity' => 2, 'price' => 100.00, 'discount' => 5.00]
            ],
            'services' => [
                ['service_id' => $this->service->id, 'quantity' => 1, 'price' => 50.00]
            ]
        ];

        $response = $this->postJson('/api/quotations', $data);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => ['id', 'customer_id', 'user_id', 'title', 'products', 'services']
            ])
            ->assertJson([
                'data' => [
                    'customer_id' => $this->customer->id,
                    'user_id' => $this->user->id,
                    'title' => 'Test Quotation',
                ]
            ]);

        $this->assertDatabaseHas('quotations', ['title' => 'Test Quotation', 'user_id' => $this->user->id]);
        $this->assertDatabaseHas('quotation_products', ['quotation_id' => $response->json('data.id'), 'product_id' => $this->product->id, 'quantity' => 2]);
        $this->assertDatabaseHas('quotation_services', ['quotation_id' => $response->json('data.id'), 'service_id' => $this->service->id, 'quantity' => 1]);
    }

    /** @test */
    public function creating_a_quotation_requires_customer_id()
    {
        $data = [
            // 'customer_id' => $this->customer->id, // Missing
            'title' => 'Test Quotation',
        ];

        $response = $this->postJson('/api/quotations', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('customer_id');
    }

    /** @test */
    public function an_authenticated_user_can_view_their_own_quotation()
    {
        $quotation = Quotation::factory()->create(['user_id' => $this->user->id, 'customer_id' => $this->customer->id]);

        $response = $this->getJson("/api/quotations/{$quotation->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => ['id' => $quotation->id, 'user_id' => $this->user->id]
            ]);
    }

    /** @test */
    public function an_authenticated_user_cannot_view_another_users_quotation()
    {
        $anotherUser = User::factory()->create();
        $quotation = Quotation::factory()->create(['user_id' => $anotherUser->id, 'customer_id' => $this->customer->id]);

        $response = $this->getJson("/api/quotations/{$quotation->id}");

        $response->assertStatus(403); // Forbidden
    }

    /** @test */
    public function an_authenticated_user_can_update_their_own_quotation()
    {
        $quotation = Quotation::factory()->create(['user_id' => $this->user->id, 'customer_id' => $this->customer->id]);
        $newCustomer = Customer::factory()->create();

        $data = [
            'customer_id' => $newCustomer->id,
            'title' => 'Updated Quotation Title',
            'products' => [
                ['product_id' => $this->product->id, 'quantity' => 3, 'price' => 110.00]
            ]
        ];

        $response = $this->putJson("/api/quotations/{$quotation->id}", $data);

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $quotation->id,
                    'customer_id' => $newCustomer->id,
                    'title' => 'Updated Quotation Title',
                ]
            ]);

        $this->assertDatabaseHas('quotations', ['id' => $quotation->id, 'title' => 'Updated Quotation Title', 'customer_id' => $newCustomer->id]);
        $this->assertDatabaseHas('quotation_products', ['quotation_id' => $quotation->id, 'product_id' => $this->product->id, 'quantity' => 3]);
    }

    /** @test */
    public function an_authenticated_user_cannot_update_another_users_quotation()
    {
        $anotherUser = User::factory()->create();
        $quotation = Quotation::factory()->create(['user_id' => $anotherUser->id, 'customer_id' => $this->customer->id]);

        $data = [
            'title' => 'Attempted Update',
        ];

        $response = $this->putJson("/api/quotations/{$quotation->id}", $data);

        $response->assertStatus(403); // Forbidden
    }

    /** @test */
    public function updating_a_quotation_with_invalid_data_returns_validation_errors()
    {
        $quotation = Quotation::factory()->create(['user_id' => $this->user->id, 'customer_id' => $this->customer->id]);

        $data = [
            'customer_id' => 9999, // Non-existent customer
            'status' => 'invalid-status',
        ];

        $response = $this->putJson("/api/quotations/{$quotation->id}", $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['customer_id', 'status']);
    }

    /** @test */
    public function an_authenticated_user_can_delete_their_own_quotation()
    {
        $quotation = Quotation::factory()->create(['user_id' => $this->user->id, 'customer_id' => $this->customer->id]);

        $response = $this->deleteJson("/api/quotations/{$quotation->id}");

        $response->assertStatus(204); // No Content
        $this->assertDatabaseMissing('quotations', ['id' => $quotation->id]);
    }

    /** @test */
    public function an_authenticated_user_cannot_delete_another_users_quotation()
    {
        $anotherUser = User::factory()->create();
        $quotation = Quotation::factory()->create(['user_id' => $anotherUser->id, 'customer_id' => $this->customer->id]);

        $response = $this->deleteJson("/api/quotations/{$quotation->id}");

        $response->assertStatus(403); // Forbidden
        $this->assertDatabaseHas('quotations', ['id' => $quotation->id]); // Should not be deleted
    }

    /** @test */
    public function deleting_a_non_existent_quotation_returns_404()
    {
        $response = $this->deleteJson('/api/quotations/999');

        $response->assertStatus(404); // Not Found
    }
}
