<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'due_at',
        'deadline',
        'status'
    ];

    protected $casts = [
        'due_at' => 'datetime',
        'deadline' => 'datetime',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeTodo($query)
    {
        return $query->where('status', 'todo');
    }

    public static function createFromTelegram($userId, $text)
    {
        $category = null;

        if (preg_match('/#(\w+)/u', $text, $matches)) {
            $category = $matches[1];
            $text = trim(str_replace($matches[0], '', $text));
        }

        return self::create([
            'user_id' => $userId,
            'title' => $text,
            'category' => $category,
            'status' => 'todo'
        ]);
    }
}
