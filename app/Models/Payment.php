<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'jenis',
        'jumlah',
        'status',
        'tanggal',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'jumlah' => 'integer',
            'tanggal' => 'date',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function getJenisLabelAttribute(): string
    {
        return match ($this->jenis) {
            'dp' => 'Uang Muka (DP)',
            'pelunasan' => 'Pelunasan Akhir',
            'maintenance' => 'Biaya Maintenance',
            default => 'Lainnya',
        };
    }
}
