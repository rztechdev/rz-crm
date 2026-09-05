<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class CompanySetting extends Model
{
    protected $fillable = [
        'company_name',
        'brand_name',
        'tagline',
        'domicile_city',
        'email_support',
        'email_company',
        'email_internal_alert',
        'website_url',
        'phone_support',
        'phone_support_2',
        'phone_admin_alerts',
        'bank_name',
        'bank_account_number',
        'bank_account_holder',
        'qris_image_path',
        'logo_image_path',
        'signature_image_path',
        'director_name',
        'director_title',
        'invoice_terms',
        'wa_api_url',
        'wa_api_key',
        'wa_sender_phone',
        'portal_sync_url',
        'portal_sync_secret',
    ];

    /**
     * Get or create the singleton instance of company settings.
     */
    public static function get(): self
    {
        $setting = static::first();
        if (!$setting) {
            $setting = static::create([
                'company_name' => 'PT RZ DIGITAL CREATIVE ARTHA',
                'brand_name' => 'RZ Digital Creative',
                'tagline' => 'Software House & Digital Solutions',
                'domicile_city' => 'Tangerang Selatan',
                'email_support' => 'support@rzdigitalcreative.my.id',
                'email_company' => 'company@rzdigitalcreative.my.id',
                'email_internal_alert' => 'rzsupportidn@gmail.com',
                'website_url' => 'https://rzdigitalcreative.my.id',
                'phone_support' => '0858-0874-9131',
                'phone_support_2' => '0821-1620-0363',
                'phone_admin_alerts' => '085808749131,082116200363',
                'bank_name' => 'Bank Central Asia (BCA)',
                'bank_account_number' => '4740769826',
                'bank_account_holder' => 'MUHAMAD RYAN RIZKI',
                'qris_image_path' => 'images/qris.jpg',
                'logo_image_path' => 'images/logo_rz_teks.png',
                'director_name' => 'MUHAMAD RYAN RIZKI',
                'director_title' => 'Finance & Executive Director',
                'invoice_terms' => "1. Pembayaran resmi hanya sah apabila ditransfer ke rekening BCA atas nama MUHAMAD RYAN RIZKI atau via QRIS resmi RZ Digital Creative.\n2. Kwitansi resmi lunas bertanda tangan digital akan diterbitkan otomatis setelah pembayaran diverifikasi.\n3. Untuk bantuan teknis atau administrasi penagihan, hubungi WhatsApp: 0858-0874-9131.",
                'wa_api_url' => 'https://wa.flustra.id/api/v1/messages/text',
                'wa_api_key' => (app()->environment('testing') || app()->runningUnitTests() || defined('PHPUNIT_COMPOSER_INSTALL')) ? null : 'fwa_inms6r8v.AudVimF78NFPbkU0gDUHgmJiHxCqaJ1f4veMqrh0',
                'wa_sender_phone' => '0823-1828-0376',
                'portal_sync_url' => config('services.portal.sync_url', 'https://portalclient.rzdigitalcreative.my.id/api/internal/v1/sync-client-project'),
                'portal_sync_secret' => config('services.portal.sync_secret', 'rz_portal_sync_secret_key_2026'),
            ]);
        } else {
            // Self-heal if gateway fields are empty in existing record (except in testing)
            $isTest = app()->environment('testing') || app()->runningUnitTests() || defined('PHPUNIT_COMPOSER_INSTALL');
            $updates = [];
            if (empty($setting->wa_api_url)) $updates['wa_api_url'] = 'https://wa.flustra.id/api/v1/messages/text';
            if (empty($setting->wa_api_key) && !$isTest) $updates['wa_api_key'] = 'fwa_inms6r8v.AudVimF78NFPbkU0gDUHgmJiHxCqaJ1f4veMqrh0';
            if (empty($setting->wa_sender_phone)) $updates['wa_sender_phone'] = '0823-1828-0376';
            if (empty($setting->portal_sync_url)) $updates['portal_sync_url'] = config('services.portal.sync_url', 'https://portalclient.rzdigitalcreative.my.id/api/internal/v1/sync-client-project');
            if (empty($setting->portal_sync_secret)) $updates['portal_sync_secret'] = config('services.portal.sync_secret', 'rz_portal_sync_secret_key_2026');
            if (!empty($updates)) {
                $setting->update($updates);
            }
        }

        return $setting;
    }

    /**
     * Get standard bank info string for WA captions and footer texts.
     */
    public function getBankInfoStringAttribute(): string
    {
        return "{$this->bank_name} {$this->bank_account_number} a.n {$this->bank_account_holder}";
    }

    /**
     * Get QRIS Image Base64 Data URI for reliable PDF rendering.
     */
    public function getQrisBase64Attribute(): ?string
    {
        // 1. Check custom uploaded file in storage
        if (!empty($this->qris_image_path)) {
            if (Storage::disk('public')->exists($this->qris_image_path)) {
                $content = Storage::disk('public')->get($this->qris_image_path);
                $mime = Storage::disk('public')->mimeType($this->qris_image_path) ?: 'image/jpeg';
                return 'data:' . $mime . ';base64,' . base64_encode($content);
            }

            // 2. Check in public directory
            $publicPath = public_path($this->qris_image_path);
            if (file_exists($publicPath)) {
                $mime = mime_content_type($publicPath) ?: 'image/jpeg';
                return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($publicPath));
            }
        }

        // 3. Fallback default
        $defaultPath = public_path('images/qris.jpg');
        if (file_exists($defaultPath)) {
            return 'data:image/jpeg;base64,' . base64_encode(file_get_contents($defaultPath));
        }

        return null;
    }

    /**
     * Get Logo Image Base64 Data URI for reliable PDF rendering.
     */
    public function getLogoBase64Attribute(): ?string
    {
        if (!empty($this->logo_image_path)) {
            if (Storage::disk('public')->exists($this->logo_image_path)) {
                $content = Storage::disk('public')->get($this->logo_image_path);
                $mime = Storage::disk('public')->mimeType($this->logo_image_path) ?: 'image/png';
                return 'data:' . $mime . ';base64,' . base64_encode($content);
            }

            $publicPath = public_path($this->logo_image_path);
            if (file_exists($publicPath)) {
                $mime = mime_content_type($publicPath) ?: 'image/png';
                return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($publicPath));
            }
        }

        $defaultPath = public_path('images/logo_rz_teks.png');
        if (file_exists($defaultPath)) {
            return 'data:image/png;base64,' . base64_encode(file_get_contents($defaultPath));
        }

        return null;
    }

    /**
     * Get Web URL for QRIS preview.
     */
    public function getQrisUrlAttribute(): string
    {
        if (!empty($this->qris_image_path)) {
            if (Storage::disk('public')->exists($this->qris_image_path)) {
                return Storage::disk('public')->url($this->qris_image_path);
            }
            if (file_exists(public_path($this->qris_image_path))) {
                return asset($this->qris_image_path);
            }
        }

        return asset('images/qris.jpg');
    }

    /**
     * Get Web URL for Logo preview.
     */
    public function getLogoUrlAttribute(): string
    {
        if (!empty($this->logo_image_path)) {
            if (Storage::disk('public')->exists($this->logo_image_path)) {
                return Storage::disk('public')->url($this->logo_image_path);
            }
            if (file_exists(public_path($this->logo_image_path))) {
                return asset($this->logo_image_path);
            }
        }

        return asset('images/logo_rz_teks.png');
    }

    /**
     * Get Signature Image Base64 Data URI for reliable PDF rendering.
     */
    public function getSignatureBase64Attribute(): ?string
    {
        if (!empty($this->signature_image_path)) {
            if (Storage::disk('public')->exists($this->signature_image_path)) {
                $content = Storage::disk('public')->get($this->signature_image_path);
                $mime = Storage::disk('public')->mimeType($this->signature_image_path) ?: 'image/png';
                return 'data:' . $mime . ';base64,' . base64_encode($content);
            }

            $publicPath = public_path($this->signature_image_path);
            if (file_exists($publicPath)) {
                $mime = mime_content_type($publicPath) ?: 'image/png';
                return 'data:' . $mime . ';base64,' . base64_encode(file_get_contents($publicPath));
            }
        }

        $fallbackPath = public_path('images/signature.png');
        if (file_exists($fallbackPath)) {
            return 'data:image/png;base64,' . base64_encode(file_get_contents($fallbackPath));
        }

        return null;
    }

    /**
     * Get Signature URL for web display.
     */
    public function getSignatureUrlAttribute(): ?string
    {
        if (!empty($this->signature_image_path)) {
            if (Storage::disk('public')->exists($this->signature_image_path)) {
                return Storage::disk('public')->url($this->signature_image_path);
            }
            if (file_exists(public_path($this->signature_image_path))) {
                return asset($this->signature_image_path);
            }
        }

        if (file_exists(public_path('images/signature.png'))) {
            return asset('images/signature.png');
        }

        return null;
    }

    /**
     * Get array of admin alert phone numbers.
     */
    public function getAdminAlertPhonesArrayAttribute(): array
    {
        $phones = explode(',', (string) $this->phone_admin_alerts);
        return array_values(array_filter(array_map('trim', $phones)));
    }
}
