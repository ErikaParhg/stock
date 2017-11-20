<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use App\User;

class AdministratorTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
 
   public function testExample()
    {
        $this->assertTrue(true);
    }

    public function test_admin_sees_total_stock()
    {
        
        Artisan::call('migrate');
        $password = bcrypt('password');
        $usuarioA = User::create([
            'name' => 'Abgaiu',
            'email' => 'Abgaiu@email.com',
            'permission' => 1,
            'password' => $password
        ]);

        $usuarioB = User::create([
            'name' => 'Soraia',
            'email' => 'Soraia@email.com',
            'permission' => 0,
            'status' => 'Active',
            'password' => $password
        ]);

        $supplier = factory('App\Supplier')->create();
        for ($i = 0; $i < 5; $i++) { 
            $products[] = factory('App\Product')->create();
        }

        $this->assertDatabaseHas('suppliers', ['id' => $supplier->id]);

        //se não estiver logado
        $response = $this->withExceptionHandling()
                         ->call('GET', '/dashboard');

        $response->assertStatus(403);

        //se estiver
        $response = $this->withExceptionHandling()
                         ->actingAs($usuarioA)
                         ->call('GET', '/dashboard');

        $response->assertStatus(200);

        $total = 0;
        foreach ($products as $product) {
            $response->assertSee($product->name);
            $response->assertSee(''.($product->cost * $product->quantity));
            $response->assertSee(''.$product->quantity);
            $total += ($product->custo * $product->quantity);
        }
        $response->assertSee(''.$total);
    } 
}
