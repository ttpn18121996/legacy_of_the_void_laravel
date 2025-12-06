<?php

namespace App\Enums;

enum ViewMode: string
{
    case TABLE = 'table';
    case GRID = 'grid';
    case LIST = 'list';
}
