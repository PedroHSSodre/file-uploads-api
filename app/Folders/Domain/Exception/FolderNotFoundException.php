<?php

declare(strict_types=1);

namespace App\Folders\Domain\Exception;

use RuntimeException;

class FolderNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Folder not found.');
    }
}
