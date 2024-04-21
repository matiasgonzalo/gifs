<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\ApiRequest
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $requested_service_name
 * @property string $body_request
 * @property integer $code_response
 * @property string $body_response
 * @property string $ip_source
 */
class ApiRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'requested_service_name',
        'body_request',
        'code_response',
        'body_response',
        'ip_source',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'body_request' => 'array',
        'body_response' => 'array',
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
