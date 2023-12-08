<?php
declare(strict_types=1);
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Task extends Model
{
    use HasFactory;
    use NodeTrait;

    protected $fillable = [
        'user_id',
        'status',
        'priority',
        'title',
        'description',
        'parent_id',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    protected $hidden = [
        '_lft',
        '_rgt',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
