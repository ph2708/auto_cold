<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\ServiceOrder;
use App\Models\ServiceOrderPhoto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ServiceOrderPhotoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_can_upload_before_and_after_photos_to_service_order()
    {
        Storage::fake('public');

        $admin = User::where('username', 'admin')->first();
        $customer = Customer::create(['name' => 'Cliente Foto Teste', 'whatsapp' => '1199999999']);
        $vehicle = Vehicle::create(['customer_id' => $customer->id, 'plate' => 'FOT1A23', 'brand' => 'Fiat', 'model' => 'Uno']);
        
        $os = ServiceOrder::create([
            'order_number' => 'OS-2026-9999',
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'user_id' => $admin->id,
            'status' => 'in_progress',
            'reported_defect' => 'Farol quebrado e painel em curto',
            'entry_date' => date('Y-m-d'),
        ]);

        $photoFile = UploadedFile::fake()->image('farol_danificado.jpg');

        // Upload da Foto de Entrada (Antes)
        $response = $this->actingAs($admin)->post(route('service_orders.photos.store', $os), [
            'stage' => 'before',
            'title' => 'Farol com lente trincada na entrada',
            'description' => 'Cliente ciente de avaria prévia',
            'photos' => [$photoFile],
        ]);

        $response->assertRedirect();

        $photo = ServiceOrderPhoto::where('service_order_id', $os->id)->first();
        $this->assertNotNull($photo);
        $this->assertEquals('before', $photo->stage);
        $this->assertEquals('Farol com lente trincada na entrada', $photo->title);

        Storage::disk('public')->assertExists($photo->file_path);
    }
}
