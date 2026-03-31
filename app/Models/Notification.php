<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory, Searchable;

    /**
     * الحقول القابلة للبحث (فقط الحقول النصية المناسبة للبحث)
     *
     * @var array<int, string>
     */
    protected $searchableFields = [
        'title',
        'message',
        'user.username',
        'user.email',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'message',
        'user_id',
        'is_read',
        'read_at',
        'is_deleted',
        'is_synced',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_read' => 'boolean',
        'is_deleted' => 'boolean',
        'is_synced' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Get the user that the notification belongs to.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * فلترة عامة
     */
    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->search($search);
        });

        $textFields = ['title', 'message'];
        foreach ($textFields as $field) {
            if (!empty($filters[$field])) {
                $query->where($field, 'like', '%' . $filters[$field] . '%');
            }
        }

        $exactFields = ['id', 'user_id'];
        foreach ($exactFields as $field) {
            if (isset($filters[$field])) {
                $query->where($field, $filters[$field]);
            }
        }

        $boolFields = ['is_read', 'is_deleted', 'is_synced'];
        foreach ($boolFields as $field) {
            if (array_key_exists($field, $filters)) {
                $value = $filters[$field];

                if (is_bool($value)) {
                    $query->where($field, $value);
                    continue;
                }

                if (is_numeric($value)) {
                    $query->where($field, (int) $value === 1);
                    continue;
                }

                if (is_string($value)) {
                    $normalized = strtolower($value);
                    if (in_array($normalized, ['true', '1'], true)) {
                        $query->where($field, true);
                    } elseif (in_array($normalized, ['false', '0'], true)) {
                        $query->where($field, false);
                    }
                }
            }
        }

        if (!empty($filters['created_from'])) {
            $query->where('created_at', '>=', $filters['created_from']);
        }
        if (!empty($filters['created_to'])) {
            $query->where('created_at', '<=', $filters['created_to']);
        }
        if (!empty($filters['read_from'])) {
            $query->where('read_at', '>=', $filters['read_from']);
        }
        if (!empty($filters['read_to'])) {
            $query->where('read_at', '<=', $filters['read_to']);
        }
        if (!empty($filters['updated_from'])) {
            $query->where('updated_at', '>=', $filters['updated_from']);
        }
        if (!empty($filters['updated_to'])) {
            $query->where('updated_at', '<=', $filters['updated_to']);
        }
    }
}
