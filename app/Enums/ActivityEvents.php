<?php

namespace App\Enums;

enum ActivityEvents: string
{
    case EmailUpdatedByUser = 'email_updated_by_user';
    case Deleted = 'deleted';
}
