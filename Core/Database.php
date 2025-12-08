<?php

declare(strict_types=1);

namespace Core;

use PDO;

class Database
{
    private $db;

    public function __construct($config)
    {
        $this->db = new PDO($this->getDsn($config));
    }

    private function getDsn($config)
    {
        $driver = $config['driver'];

        unset($config['driver']);

        $dsn = $driver . ':' . http_build_query($config, '', ';');

        if ($driver == 'sqlite') {
            $dsn = $driver . ':' . $config['database'];
        }

        return $dsn;
    }

    public function query($query, $class = null, $params = [])
    {
        $prepare = $this->db->prepare($query);

        if ($class) {
            $prepare->setFetchMode(PDO::FETCH_CLASS, $class);
        }

        // 🔧 Normaliza os parâmetros antes do execute()
        if (!is_array($params)) {
            // Se for string, transforma em array
            $params = [$params];
        } elseif (isset($params[0]) && is_array($params[0])) {
            // Se for array aninhado ([[...]]), pega o primeiro nível
            $params = $params[0];
        }

        $prepare->execute($params);

        return $prepare;
    }

    public function beginTransaction()
    {
        $this->db->beginTransaction();
    }

    public function commit()
    {
        $this->db->commit();
    }

    public function rollBack()
    {
        $this->db->rollBack();
    }


    public function lastInsertId()
    {
        return $this->db->lastInsertId();
    }
}
