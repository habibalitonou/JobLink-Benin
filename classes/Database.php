<?php

class Database
{
    private array $config;
    private PDO $connection;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../config/config.php';

        $dsn = 'mysql:host=' . $this->config['db']['host']
            . ';port=' . $this->config['db']['port']
            . ';dbname=' . $this->config['db']['dbname']
            . ';charset=' . $this->config['db']['charset'];

        $this->connection = new PDO(
            $dsn,
            $this->config['db']['username'],
            $this->config['db']['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }

    public function getConnection(): PDO
    {
        return $this->connection;
    }
}
