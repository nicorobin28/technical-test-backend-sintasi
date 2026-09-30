<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Visit extends Model
{
    protected $fillable = ['patient_id', 'visit_date', 'complaint', 'doctor', 'notes'];

    protected $casts = ['visit_date' => 'date'];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

}
