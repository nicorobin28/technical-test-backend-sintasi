<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;

class Patient extends Model
{
    protected $fillable = ['medical_record_number', 'nik', 'name', 'email', 'phone', 'gender', 'birth_date', 'address'];

    protected $hidden = ['nik', 'email'];

    protected $casts = ['birth_date' => 'date'];

    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    public function getDecryptedNikAttribute(): string
    {
        return Crypt::decryptString($this->attributes['nik']);
    }

    public function getDecryptedEmailAttribute(): string
    {
        return Crypt::decryptString($this->attributes['email']);
    }

    public function toSafeArray(): array
    {
        return [
            'medical_record_number' => $this->medical_record_number,
            'nik' => $this->decrypted_nik,
            'name' => $this->name,
            'email' => $this->decrypted_email,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date?->format('Y-m-d'),
            'address' => $this->address,
            'visit_count' => $this->visits_count ?? $this->visits()->count(),
        ];
    }



}
