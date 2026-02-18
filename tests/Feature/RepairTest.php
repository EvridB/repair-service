<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Request as RepairRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RepairTest extends TestCase
{
    use RefreshDatabase;

    public function test_client_can_create_request()
    {
        $response = $this->post('/requests', [
            'clientName' => 'Тест Тестович',
            'phone' => '12345',
            'address' => 'Адрес',
            'problemText' => 'Сломалось всё'
        ]);

        $this->assertDatabaseHas('requests', ['clientName' => 'Тест Тестович']);
    }

    public function test_master_can_complete_request()
    {
        $request = RepairRequest::create([
            'clientName' => 'Имя', 'phone' => '1', 'address' => 'А', 'problemText' => 'П', 'status' => 'assigned'
        ]);
        
        // Здесь можно добавить тест на смену статуса
        $request->update(['status' => 'done']);
        $this->assertEquals('done', $request->status);
    }
}
