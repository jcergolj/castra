<?php

namespace App\Enums;

enum ActivityEvents: string
{
    case email_updated_by_user = 'email updated by user';
    case deleted = 'deleted';
}
