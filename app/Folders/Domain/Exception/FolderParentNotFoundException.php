<?php

declare(strict_types=1);

namespace App\Folders\Domain\Exception;

use RuntimeException;

class FolderParentNotFoundException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Folder parent not found.');
    }
}
