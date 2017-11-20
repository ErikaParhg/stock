<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

class ProductsTest extends TestCase
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


	public function test_logged_in_users_insert_products()
	{
		Artisan::call('migrate');
		$user = factory('App\User')->create();
		$supplier = factory('App\Supplier')->create();
		$product = array(
			'supplier' => ''.$supplier->id,
            'name' => '2017',
            'description' => 'ano infinito',
            'cost' => '20',
            'quantity' => '100',           
            '_token' => csrf_token()
        );

		$response = $this->withExceptionHandling()
						 ->call('POST', '/product/insert', $product);

        $response->assertStatus(403);

        //se ele estiver
        $response = $this->withExceptionHandling()
						 ->actingAs($user)
						 ->call('POST', '/product/insert', $product);

        $response->assertStatus(302);

        $this->assertDatabaseHas('products', 
        	[
            'name' => '2017',
            'description' => 'ano infinito',
            'cost' => '20.0',
            'quantity' => '100',
            'supplier_id' => ''.$supplier->id,
        ]);
	}

		public function test_unregistered_users_not_insert_products()
		{
	    Artisan::call('migrate');
		$supplier = factory('App\Supplier')->create();
		$response = $this->withExceptionHandling()->call('POST', '/product/insert', [
            'supplier' => ''.$supplier->id,
            'name'=>'Coragem',
            'description' => 'esta perdida',
            'cost' => '20',
            'quantity' => '100',
            '_token' => csrf_token()
        ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('products', 
        	[
        	'supplier_id' => ''.$supplier->id,
            'name' => 'Coragem',
            'description' => 'esta perdida',
            'cost' => '20.0',
            'quantity' => '100',          
        ]);
	}


	public function test_logged_in_users_access_products_page()
	{
		Artisan::call('migrate');
		$user = factory('App\User')->create();

   		$response = $this->withExceptionHandling()->call('GET', '/product');
   		$response->assertStatus(403);

   		$response = $this->withExceptionHandling()->actingAs($user)->call('GET', '/product');
   		$response->assertStatus(200);
   		
	}

	public function test_logged_in_users_unavailable_products()
	{
		Artisan::call('migrate');
		$user = factory('App\User')->create();
		$supplier = factory('App\Supplier')->create();
		$product = factory('App\Product')->create();

		$quantityInitial = $product->quantity;

		$response = $this->withExceptionHandling()->call('POST', '/product/debit', ['id' => ''.$product->id, 'quantity' => '3']);
		$response->assertStatus(403);

		$response = $this->withExceptionHandling()->actingAs($user)->call('POST', '/product/debit', ['id' => ''.$product->id, 'quantity' => '3']);

		$product = \App\Product::all()->first();
		$response->assertStatus(302);
		$this->assertTrue(($quantityInitial  - $product->quantity) == 3);

	}

	public function test_logged_in_users_list_products()
	{
		Artisan::call('migrate');
		$user = factory('App\User')->create();
		$supplier = factory('App\Supplier')->create();
		for ($i = 0; $i < 5; $i++) { 
			$products[] = factory('App\Product')->create();
		}

		$this->assertDatabaseHas('suppliers', ['id' => $supplier->id]);

		$response = $this->withExceptionHandling()
					     ->call('GET', '/product');

		$response->assertStatus(403);

		$response = $this->withExceptionHandling()
					     ->actingAs($user)
					     ->call('GET', '/product');

		$response->assertStatus(200);

		foreach ($products as $product) {
            $response->assertSee($product->name);
            $response->assertSee(''.$product->cost);
            $response->assertSee(''.$product->quantity);
    	}
	}



	public function test_logged_in_users_search_products()
	{
		Artisan::call('migrate');
		$user = factory('App\User')->create();
		$supplier = factory('App\Supplier')->create();
		for ($i = 0; $i < 5; $i++) { 
			$products[] = factory('App\Product')->create();
		}

		$this->assertDatabaseHas('suppliers', ['id' => $supplier->id]);

		$response = $this->withExceptionHandling()
					     ->call('GET', '/product/search', ['name' => $products[3]->name]);

		$response->assertStatus(403);

		$response = $this->withExceptionHandling()
					     ->actingAs($user)
					     ->call('GET', '/product/search', ['name' => $products[3]->name]);

		$response->assertStatus(200);
        $response->assertSee($products[3]->name);
        $response->assertSee(''.$products[3]->cost);
        $response->assertSee(''.$products[3]->quantity);
	}




	public function test_logged_in_users_edit_products()
	{
		Artisan::call('migrate');
		$user = factory('App\User')->create();
		$supplier = factory('App\Supplier')->create();
		$product = factory('App\Product')->create();
		$productEdit = array(
            'id' => $product->id,
            'supplier' => $product->supplier_id,
            'name' => 'Garrafa',
            'description' => $product->description,
            'cost' => $product->cost,
            'quantity' => $product->quantity,
           
            '_token' => csrf_token()
        );

        $this->assertDatabaseHas('products', ['id' => ''.$product->id]);
        $this->assertDatabaseMissing('products', ['name' => 'Garrafa']);

        $response = $this->withExceptionHandling()->call('POST', '/product/edit', $productEdit);
        $response->assertStatus(403);

		$response = $this->withExceptionHandling()->actingAs($user)->call('POST', '/product/edit', $productEdit);

        $response->assertStatus(302);
        $this->assertDatabaseHas('products', ['name' => 'Garrafa']);
        $this->assertDatabaseHas('products', ['id' => ''.$product->id]);
	}

	public function test_logged_in_users_see_products_by_supplier()
	{
		Artisan::call('migrate');
		$user = factory('App\User')->create();
		$supplier = factory('App\Supplier')->create();
		for ($i = 0; $i < 5; $i++) { 
			$products[] = factory('App\Product')->create();
		}

		$this->assertDatabaseHas('suppliers', ['id' => $supplier->id]);

		$response = $this->withExceptionHandling()
					     ->call('GET', '/product/supplier/'.$supplier->id);

		$response->assertStatus(403);

		$response = $this->withExceptionHandling()
					     ->actingAs($user)
					     ->call('GET', '/product/supplier/'.$supplier->id);

		$response->assertStatus(200);

		foreach ($products as $product) {
            $response->assertSee($product->name);
            $response->assertSee(''.$product->cost);
            $response->assertSee(''.$product->quantity);
    	}

	}
}
