<?php

class Asset
{
    public static function all()
    {
        global $conn;
        $sql = "SELECT * FROM assets";
        return $conn->query($sql)->fetch_all(MYSQLI_ASSOC);
    }

    public static function store($data)
    {
        global $conn;
        $stmt = $conn->prepare(
            "INSERT INTO assets (column_name)
             VALUES (?, ?, ?)"
        );
        $stmt->bind_param(
            "ssi",
            // $data['column_name'],
        );
        $stmt->execute();
    }

    public static function countAll()
    {
        global $conn;
        $result = $conn->query("SELECT COUNT(*) AS total FROM assets");
        return $result->fetch_assoc()['total'];
    }

    public static function countByStatus($status)
    {
        global $conn;
        $stmt = $conn->prepare(
            "SELECT COUNT(*) AS total FROM assets WHERE status = ?"
        );
        $stmt->bind_param("s", $status);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc()['total'];
    }
}
