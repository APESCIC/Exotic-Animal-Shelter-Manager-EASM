<?php

namespace App\Install;

use Illuminate\Support\Facades\DB;
use PDO;
use PDOException;

class DatabaseConnector
{
    /**
     * Prove the posted MySQL credentials can open the empty database.
     *
     * @param  array{host: string, port: string|int, database: string, username: string, password?: string|null}  $credentials
     */
    public function test(array $credentials): void
    {
        $dsn = sprintf(
            'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
            $credentials['host'],
            $credentials['port'],
            $credentials['database'],
        );

        try {
            new PDO($dsn, $credentials['username'], (string) ($credentials['password'] ?? ''), [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 5,
            ]);
        } catch (PDOException $e) {
            throw new DatabaseConnectionException(
                'Could not connect to the database with those credentials. Check host, database name, username, and password.',
                previous: $e,
            );
        }
    }

    /**
     * Point the running app at the posted MySQL database before migrate.
     *
     * @param  array{host: string, port: string|int, database: string, username: string, password?: string|null}  $credentials
     */
    public function apply(array $credentials): void
    {
        config([
            'database.default' => 'mysql',
            'database.connections.mysql.host' => $credentials['host'],
            'database.connections.mysql.port' => $credentials['port'],
            'database.connections.mysql.database' => $credentials['database'],
            'database.connections.mysql.username' => $credentials['username'],
            'database.connections.mysql.password' => (string) ($credentials['password'] ?? ''),
        ]);

        DB::purge('mysql');
        DB::reconnect('mysql');
    }
}
