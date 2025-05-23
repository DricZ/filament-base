<?php

namespace App\Contracts;

interface Permissionable
{
    /**
     * Daftar permission yang diperlukan untuk model
     */
    public static function getPermissions(): array;
}
