<?php

declare(strict_types=1);

namespace App\Folders\Domain\Exception;

use RuntimeException;

class FolderAlreadyExistsException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Folder already exists in this location.');
    }
}
