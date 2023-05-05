<?php

declare(strict_types=1);

namespace WHInterviewTask;

class Controller
{
    public function get_db_connection(): \PDO
    {
        $dsn = 'mysql:host=' . App::config('mysql.host') . ';port=' . App::config('mysql.port');
        return new \PDO(
            $dsn,
            App::config('mysql.user'),
            App::config('mysql.password'),
        );
    }
}
