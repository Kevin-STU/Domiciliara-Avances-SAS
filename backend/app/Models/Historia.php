<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Historia extends Model
{
    use HasFactory;

    protected $fillable = [
        'professional_id',
        'patient_id',
        'patient_info',
        'date_time',
        'consecutive',
        'current_status',
        'antecedents',
        'final_evolution',
        'professional_concept',
        'recommendations',
        'assisted',
        'firma',
    ];

    public function professional()
    {
        return $this->belongsTo(User::class, 'professional_id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}
