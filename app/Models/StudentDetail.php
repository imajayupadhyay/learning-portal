<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentDetail extends Model
{
    protected $fillable = [
        'user_id', 'phone', 'date_of_birth', 'gender', 'education',
        'institution', 'interests', 'bio', 'address', 'city',
        'state', 'country', 'pincode', 'linkedin', 'github', 'avatar',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
