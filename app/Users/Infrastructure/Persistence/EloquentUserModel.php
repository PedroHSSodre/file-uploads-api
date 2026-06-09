<?php

declare(strict_types=1);

namespace App\Users\Infrastructure\Persistence;

use Illuminate\Foundation\Auth\User as Authenticatable;

class EloquentUserModel extends Authenticatable
{
    protected $table = 'users';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'user_name',
        'user_password',
        'user_email',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'user_password',
    ];

    public function getAuthPassword(): string
    {
        return (string) $this->user_password;
    }
}
