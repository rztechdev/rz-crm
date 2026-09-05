<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'lead_id',
        'nama_project',
        'paket',
        'harga',
        'status',
        'portal_project_id',
        'portal_user_id',
        'synced_to_portal_at',
        'tanggal_mulai',
        'tanggal_selesai',
        'link_website',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'integer',
            'tanggal_mulai' => 'date',
            'tanggal_selesai' => 'date',
            'synced_to_portal_at' => 'datetime',
        ];
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'project_id');
    }

    public function maintenanceSubscription(): HasOne
    {
        return $this->hasOne(MaintenanceSubscription::class, 'project_id');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(ProjectSubscription::class, 'project_id');
    }

    public function activeSubscription(): HasOne
    {
        return $this->hasOne(ProjectSubscription::class, 'project_id')
            ->whereIn('status', ['aktif', 'akan_expired', 'diperpanjang'])
            ->latestOfMany();
    }

    public function getTotalPaidAttribute(): int
    {
        return $this->payments()->where('status', 'lunas')->sum('jumlah');
    }

    public function getRemainingBalanceAttribute(): int
    {
        return max(0, $this->harga - $this->total_paid);
    }

    public function getTotalTerbayarAttribute(): int
    {
        return $this->total_paid;
    }

    public function getSisaTagihanAttribute(): int
    {
        return $this->remaining_balance;
    }

    public function getPaymentStatusAttribute(): string
    {
        $paid = $this->total_paid;
        $total = $this->harga;

        if ($paid >= $total && $total > 0) {
            return 'lunas';
        }
        if ($paid > 0) {
            return 'dp_diterima';
        }
        return 'unpaid';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'draft' => 'Draft / Baru',
            'dp_diterima' => 'DP Diterima',
            'dikerjakan' => 'Sedang Dikerjakan',
            'review' => 'Review Klien',
            'selesai' => 'Selesai & Live',
            'dibatalkan' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }

    public function getPaketLabelAttribute(): string
    {
        return match ($this->paket) {
            'landing_page' => 'Landing Page',
            'company_profile' => 'Company Profile',
            'toko_kasir' => 'Toko & Kasir POS',
            'custom' => 'Custom Web App',
            default => ucfirst($this->paket),
        };
    }
}
