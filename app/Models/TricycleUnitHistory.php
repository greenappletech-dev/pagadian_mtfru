<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TricycleUnitHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'tricycle_id',
        'mtop_application_id',
        'operator_id',
        'body_number',
        'make_type',
        'engine_motor_no',
        'chassis_no',
        'plate_no',
        'replaced_at',
    ];

    protected $casts = [
        'replaced_at' => 'datetime',
    ];

    public function tricycle() {
        return $this->belongsTo(Tricycle::class);
    }

    /* every unit previously carried by this tricycle, newest first */
    public function fetchByTricycle($tricycle_id) {
        return TricycleUnitHistory::where('tricycle_id', $tricycle_id)
            ->orderBy('replaced_at', 'DESC')
            ->get();
    }

    /* where an engine number has been used before, for tracing a re-applied unit */
    public function fetchByEngineOrChassis($value) {
        $value = strtoupper(trim($value));

        return TricycleUnitHistory::whereRaw('upper(trim(engine_motor_no)) = ?', [$value])
            ->orWhereRaw('upper(trim(chassis_no)) = ?', [$value])
            ->orderBy('replaced_at', 'DESC')
            ->get();
    }
}
