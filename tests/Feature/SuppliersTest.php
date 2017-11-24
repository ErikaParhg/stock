<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

class SuppliersTest extends TestCase
{
    public function test_users_logged_in_to_access_suppliers_page()
    {
        Artisan::call('migrate');

        $user = factory('App\User')->create();
   
        $response = $this->withExceptionHandling()->actingAs($user)->call('GET', '/supplier');
        $response->assertStatus(200);            
    }

    public function test_users_logged_in_insert_suppliers()
    {
        Artisan::call('migrate');

        $user = factory('App\User')->create();

        $response = $this->withExceptionHandling()->actingAs($user)->call('POST', '/supplier/insert', [
                'name' => 'Labor Ltda',
                'cnpj' => 1235401347732, 
                'address' => 'Dutra, 52', 
                '_token' => csrf_token()
        ]);
       
        $response->assertStatus(302);

        $this->assertDatabaseHas('suppliers', [
            'name' => 'Labor Ltda', 
            'cnpj' => 1235401347732, 
            'address' => 'Dutra, 52']);
    }
 
    public function test_logged_in_users_can_list_suppliers()
    {
        Artisan::call('migrate');
        
        $user = factory('App\User')->create();
            for ($i = 0; $i < 5; $i++) { 
                $suppliers[] = factory('App\Supplier')->create();
            }
       
        $response = $this->withExceptionHandling()->actingAs($user)->call('GET', 'supplier');
        $response->assertStatus(200);

        foreach ($suppliers as $supplier) {
            $response->assertSee($supplier->name);
            $response->assertSee(''.$supplier->cnpj);
            $response->assertSee(''.$supplier->address);
        }
    }

    public function test_logge_in_users_search_suppliers()
    {
        Artisan::call('migrate');

        $user = factory('App\User')->create();
            for ($i = 0; $i < 5; $i++) { 
                $suppliers[] = factory('App\Supplier')->create();
            }

        $response = $this->withExceptionHandling()->actingAs($user)
                         ->call('GET', '/supplier/search', ['name' => $suppliers[3]->name]);
        $response->assertStatus(200);

        $response->assertSee($suppliers[3]->name);
        $response->assertSee(''.$suppliers[3]->cnpj);
        $response->assertSee(''.$suppliers[3]->address);
    }

    public function test_users_logged_in_edit_suppliers()
    {
        Artisan::call('migrate');

        $user = factory('App\User')->create();
        $supplier = factory('App\Supplier')->create();
        $editSupplier = array(
            'id' => $supplier->id,
            'name' => 'Antonieta',
            'cnpj' => $supplier->cnpj,
            'address' => $supplier->address,
            '_token' => csrf_token()
        );
        $this->assertDatabaseHas('suppliers', ['id' => ''.$supplier->id]);
        $this->assertDatabaseMissing('suppliers', ['name' => 'Antonieta']);

        $response = $this->withExceptionHandling()->actingAs($user)->call('POST', '/supplier/edit', $editSupplier);
        $response->assertStatus(302);

        $this->assertDatabaseHas('suppliers', ['name' => 'Antonieta']);
        $this->assertDatabaseHas('suppliers', ['id' => ''.$supplier->id]);
    }

    public function test_users_logged_in_delete_suppliers()
    {
        Artisan::call('migrate');

        $user = factory('App\User')->create();
        $supplier = factory('App\Supplier')->create();
        $this->assertDatabaseHas('suppliers', ['id' => $supplier->id]);

        $response = $this->withExceptionHandling()->actingAs($user) 
                         ->call('POST', '/supplier/delete', ['id' => $supplier->id]);
        $response->assertStatus(302);

        $this->assertSoftDeleted('suppliers', ['id' => $supplier->id]);
        $this->assertDatabaseMissing('products', ['supplier_id' => $supplier->id]);
    }
}