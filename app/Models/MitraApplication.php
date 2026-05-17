<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MitraApplication extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'no_whatsapp',
        'nama_toko',
        'alamat_toko',
        'kota',
        'pengalaman_tahun',
        'spesialisasi',
        'foto_toko',
        'foto_ktp',
        'sertifikat',
        'alasan_bergabung',
        'sumber_info',
        'status',
        'catatan_admin',
        'approved_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'approved_at' => 'datetime',
            'pengalaman_tahun' => 'integer',
        ];
    }

    /**
     * Get the user that owns the application.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the application is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if the application is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'disetujui';
    }

    /**
     * Check if the application is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'ditolak';
    }
}