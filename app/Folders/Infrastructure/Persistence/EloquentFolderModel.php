<?php

declare(strict_types=1);

namespace App\Folders\Infrastructure\Persistence;

use Illuminate\Database\Eloquent\Model;

class EloquentFolderModel extends Model
{
    protected $table = 'folders';

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'folder_name',
        'folder_parent',
        'folder_user_id',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [];
}
