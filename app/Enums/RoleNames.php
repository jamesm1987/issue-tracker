<?php

namespace App\Enums;

class RoleNames
{

    const ADMIN = 'Admin';
    const PROJECT_MANAGER = 'Project Manager';
    const DEVELOPER = 'Developer';
    const CLIENT = 'Client';


    public static function all(): array
    {
        return [
            self::ADMIN,
            self::PROJECT_MANAGER,
            self::DEVELOPER,
            self::CLIENT,
        ];
    }
}
