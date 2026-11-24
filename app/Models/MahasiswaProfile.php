<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class MahasiswaProfile extends Model
{
    protected $fillable = [
        'user_id', 'nama_lengkap', 'prodi', 'semester',
        'nik_encrypted', 'no_hp_encrypted', 'alamat_encrypted',
        'nik', 'no_hp', 'alamat'
    ];

    // Mutator: Otomatis enkripsi AES-256-CBC saat disimpan
    public function setNikAttribute($value)
    {
        $this->attributes['nik_encrypted'] = Crypt::encryptString($value);
    }

    // Accessor: Otomatis dekripsi saat ditampilkan di frontend
    public function getNikAttribute()
    {
        return Crypt::decryptString($this->attributes['nik_encrypted']);
    }

    public function setNoHpAttribute($value)
    {
        $this->attributes['no_hp_encrypted'] = Crypt::encryptString($value);
    }

    public function getNoHpAttribute()
    {
        return Crypt::decryptString($this->attributes['no_hp_encrypted']);
    }

    public function setAlamatAttribute($value)
    {
        $this->attributes['alamat_encrypted'] = Crypt::encryptString($value);
    }

    public function getAlamatAttribute()
    {
        return Crypt::decryptString($this->attributes['alamat_encrypted']);
    }
}