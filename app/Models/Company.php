<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $table = 'company';

    protected $fillable = [
        'name',
        'description',
        'slug',
        'image',
        'background_color',
        'text_color',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSlug($query, $slug = null)
    {
        if ($slug) {
            return $query->where('slug', $slug);
        }

        return $query->where('isHomepage', true);
    }

    public function templates()
    {
        return $this->belongsToMany(Template::class)->withPivot('id', 'order', 'data');
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }
}
