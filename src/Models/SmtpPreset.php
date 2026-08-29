<?php

namespace HasinHayder\TyroDashboard\Models;

use Illuminate\Database\Eloquent\Model;

class SmtpPreset extends Model {
    protected $table = 'tyro_smtp_presets';

    protected $fillable = [
        'name',
        'mailer',
        'host',
        'port',
        'encryption',
        'username',
        'password',
        'from_address',
        'from_name',
    ];

    protected $casts = [
        'port' => 'integer',
    ];

    protected $hidden = ['password'];
}
