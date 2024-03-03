<?php
namespace App;

class CONFIG
{
    public static function set($key, $value)
    {
        // Serialize the value before storing
        $serializedValue = serialize($value);
        // Check if the configuration key already exists
        $result = DB::query("SELECT * FROM config WHERE name = '$key'");
        if ($result && $result->num_rows > 0) {
            // Update existing configuration entry
            DB::query("UPDATE config SET value = '$serializedValue' WHERE name = '$key'");
        } else {
            // Insert new configuration entry
            DB::query("INSERT INTO config (name, value) VALUES ('$key', '$serializedValue')");
        }
    }

    public static function get($key, $default = null)
    {
        $result = DB::query("SELECT value FROM config WHERE name = '$key'");
        if ($result && $result->num_rows == 1) {
            $row = $result->fetch_assoc();
            // Unserialize the value after retrieval
            $value = unserialize($row['value']);
            return $value !== false ? $value : $default;
        } else {
            return $default;
        }
    }

    // Other methods remain the same...
}
