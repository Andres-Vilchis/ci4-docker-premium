<?php

namespace App\Libraries;

use Config\Database;

class DatabaseHealth
{
    public static function check(): bool
    {
        try {
            $db = Database::connect();
            $db->query('SELECT 1');
            return true;
        } catch (\Throwable $e) {
            log_message('error', 'DB health failed: ' . $e->getMessage());
            return false;
        }
    }
}