<?php

namespace TakerIo\HttpClient\Models;

use Illuminate\Database\Eloquent\Model;

class HttpLog extends Model
{
    protected $table = 'http_logs';

    protected $fillable = [
        'route',
        'type',
        'request',
        'response',
        'headers',
        'code'
    ];
}
