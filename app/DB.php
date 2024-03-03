<?php
namespace App;

class DB
{
    public static $conn;
    public static $db_name;
    public static $connError;

    public static function getConn()
    {
        return self::$conn;
    }

    public static function connect($host, $user, $pass, $db)
    {
        self::$db_name = $db;
        try {
            self::$conn = new \mysqli($host, $user, $pass, $db);
        } catch (\Exception $e) {
            self::$connError = "Connection failed: " . $e->getMessage();
        }
    }

    public static function query($sql)
    {
        return self::$conn->query($sql);
    }

    public static function queryAndFetchAll($sql, $type = MYSQLI_ASSOC)
    {
        $result = self::$conn->query($sql);
        if (!$result) {
            return false;
        }
        return $result->fetch_all($type);
    }

    public static function tableExists($table)
    {
        $result = self::$conn->query("SHOW TABLES LIKE '$table'");
        return $result->num_rows > 0;
    }

    public static function countTableRows($table)
    {
        $result = self::$conn->query("SELECT COUNT(*) AS total_rows FROM $table");
        if ($result && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            return $row['total_rows'];
        } else {
            return false;
        }
    }

    public static function createTable($tableName, $fields, $config = [])
    {
        $sql = "CREATE TABLE IF NOT EXISTS $tableName (";
        $primaryKey = isset($config['primary_key']) ? $config['primary_key'] : null;
        foreach ($fields as $fieldName => $fieldDetails) {
            $type = $fieldDetails['type'];
            $length = isset($fieldDetails['length']) ? '(' . $fieldDetails['length'] . ')' : '';
            $null = isset($fieldDetails['null']) && $fieldDetails['null'] ? 'NULL' : 'NOT NULL';
            $extra = isset($fieldDetails['extra']) ? $fieldDetails['extra'] : "";
            $sql .= "$fieldName $type $length $null $extra, ";
        }
        $sql = rtrim($sql, ', ');
        if ($primaryKey) {
            $sql .= ",\n PRIMARY KEY ($primaryKey)";
        }
        if (isset($config['extra'])) {
            $sql .= ", {$config['extra']}";
        }
        $sql .= ")";
        return self::$conn->query($sql);
    }
}
