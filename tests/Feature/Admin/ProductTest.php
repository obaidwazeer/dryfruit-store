<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Tests\TestCase;

class ProductTest extends TestCase
{
    public function test_guest_cannot_access_product_management(): void
    {
        $response = $this->get('/admin/products');

        $response->assertRedirect();
    }

    public function test_admin_can_access_product_management(): void
    {
        $user = User::factory()->create();

        $user->givePermissionTo('products.view');

        $this->actingAs($user);

        $response = $this->get('/admin/products');

        $response->assertSuccessful();
    }
}
