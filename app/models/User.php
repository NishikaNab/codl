<?php

class User
{
    public static function all()
    {
        global $conn;
        return $conn->query("SELECT * FROM users")
                    ->fetch_all(MYSQLI_ASSOC);
    }

    public static function find($id)
    {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM users WHERE user_id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public static function store($data)
    {
        global $conn;
        $stmt = $conn->prepare(
            "INSERT INTO users (name, email) VALUES (?, ?)"
        );
        $stmt->bind_param("ss", $data['name'], $data['email']);
        $stmt->execute();
    }

    public static function update($data)
    {
        global $conn;
        $stmt = $conn->prepare(
            "UPDATE users SET name=?, email=? WHERE id=?"
        );
        $stmt->bind_param(
            "ssi",
            $data['name'],
            $data['email'],
            $data['id']
        );
        $stmt->execute();
    }

    public static function delete($id)
    {
        global $conn;
        $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}
