<?php

namespace Modules\Platform\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'company_name',
        'company_url',
        'ip_address',
        'responded_to',
    ];

    protected $casts = [
        'responded_to' => 'boolean',
    ];

    protected $table = 'messages';
}