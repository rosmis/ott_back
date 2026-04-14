<?php

declare(strict_types=1);

namespace App\Enums;

enum VideoStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Archived = 'archived';
}
