<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vaksin extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'vaksins';

    protected $fillable = [
        'nama_vaksin',
        'deskripsi',
        'urutan',
        'aktif',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'aktif' => 'boolean',
        'urutan' => 'integer',
    ];

    /**
     * Boot method untuk tracking otomatis
     */
    protected static function boot()
    {
        parent::boot();

        // Saat membuat vaksin baru
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->id();
            }
        });

        // Saat mengupdate vaksin
        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updated_by = auth()->id();
            }
        });

        // Saat menghapus vaksin (soft delete)
        static::deleting(function ($model) {
            if (auth()->check()) {
                $model->deleted_by = auth()->id();
                $model->save(); // Save deleted_by sebelum soft delete
            }
        });
    }

    /**
     * Relasi ke User yang membuat vaksin
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Relasi ke User yang terakhir mengupdate vaksin
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Relasi ke User yang menghapus vaksin
     */
    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
