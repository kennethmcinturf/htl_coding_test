<?php

namespace Tests\Unit;

use App\Key;
use App\Technician;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EndPointTest extends TestCase
{
    public function testOrderPage()
    {
        $response = $this->get('/seeOrders');

        $response->assertStatus(200);
    }

    public function testApi()
    {
        $key = Key::first();
        $tech = Technician::first();

        $response = $this->postJson('/api/orders', ['key' => $key->id, 'tech' => $tech->id])->decodeResponseJson();

        $this->assertEquals($response['order']['key_id'], $key->id);
        $this->assertEquals($response['order']['technician_id'], $tech->id);

        $orderId = $response['order']['id'];

        $updateKey = Key::orderBy('id', 'desc')->first();

        $response = $this->call('PUT', '/api/orders/'.$orderId, ['key' => $updateKey->id, 'tech' => $tech->id])->decodeResponseJson();

        $this->assertEquals($response['order']['key_id'], $updateKey->id);
        $this->assertEquals($response['order']['technician_id'], $tech->id);

        $response = $this->call('DELETE', '/api/orders/'.$orderId);

        $this->assertEquals(200, $response->getStatusCode());
    }
}
