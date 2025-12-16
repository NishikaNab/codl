<?php

class Damage
{
    public static function all()
    {
        global $conn;
        $sql = "SELECT * FROM damage_report";
        return $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public static function getDamageLevel()
    {
        global $conn;
        $sql = "SHOW COLUMNS FROM damage_report LIKE 'damage_level'";
        $result = $conn->query($sql);
        $enumValues = [];
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $type = $row['Type']; 
            if (preg_match("/^enum\('(.*)'\)$/", $type, $matches)) {
                $enumValues = explode("','", $matches[1]);
            }
        }
        return $enumValues;
    }
}