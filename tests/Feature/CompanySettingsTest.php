<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\CompanySetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CompanySettingsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Guests cannot view settings.
     */
    public function test_guests_cannot_access_settings()
    {
        $this->get(route('settings.edit'))->assertRedirect(route('login'));
        $this->put(route('settings.update'), [])->assertRedirect(route('login'));
    }

    /**
     * Authenticated user can view the settings page.
     */
    public function test_authenticated_user_can_view_settings_page()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('settings.edit'));

        $response->assertStatus(200);
        $response->assertSee('Configurações da Empresa');
    }

    /**
     * Authenticated user can create/update company settings.
     */
    public function test_authenticated_user_can_update_settings()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put(route('settings.update'), [
            'company_name' => 'Minha Empresa Tech',
            'document' => '12.345.678/0001-90',
            'email' => 'financeiro@minhaempresa.com',
            'phone' => '(11) 98888-8888',
            'address' => 'Rua das Flores, 123',
            'primary_color' => '#ff0000',
            'secondary_color' => '#00ff00',
            'signature_text' => 'CEO da Empresa',
            'pdf_template' => 'modern',
        ]);

        $response->assertRedirect(route('settings.edit'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('company_settings', [
            'user_id' => $user->id,
            'company_name' => 'Minha Empresa Tech',
            'document' => '12.345.678/0001-90',
            'email' => 'financeiro@minhaempresa.com',
            'phone' => '(11) 98888-8888',
            'address' => 'Rua das Flores, 123',
            'primary_color' => '#ff0000',
            'secondary_color' => '#00ff00',
            'signature_text' => 'CEO da Empresa',
            'pdf_template' => 'modern',
        ]);
    }

    /**
     * Authenticated user can upload logo and signature.
     */
    public function test_authenticated_user_can_upload_logo_and_signature()
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $logoFile = UploadedFile::fake()->image('my_logo.png', 100, 100);
        $signatureFile = UploadedFile::fake()->image('my_sig.png', 200, 50);

        $response = $this->actingAs($user)->put(route('settings.update'), [
            'primary_color' => '#2563eb',
            'secondary_color' => '#1e3a8a',
            'pdf_template' => 'classic',
            'logo' => $logoFile,
            'signature' => $signatureFile,
        ]);

        $response->assertRedirect(route('settings.edit'));
        $response->assertSessionHas('success');

        $setting = CompanySetting::where('user_id', $user->id)->first();
        $this->assertNotNull($setting);
        $this->assertNotNull($setting->logo_path);
        $this->assertNotNull($setting->signature_path);

        Storage::disk('public')->assertExists($setting->logo_path);
        Storage::disk('public')->assertExists($setting->signature_path);
    }
}
