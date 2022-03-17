<?php

// include "../config/connection.php";
// include "../config/helper.php";

function usersFindByEmail($email)
{
    // main variable
    $connection = connection();
    $data = null;
    $sql = "SELECT * FROM users WHERE email = '$email' ORDER BY id DESC LIMIT 1";

    // run sql 
    if ($result = $connection->query($sql)) {
        if ($data = $result->fetch_assoc()) {
            $status = true;
            $message = 'Get data successfully';
        } else {
            $status = false;
            $message = 'Data not found';
        }
    } else {
        $status = false;
        $message = $result->error;
    }

    // close connection
    $connection->close();

    // return information
    return [
        'status' => $status,
        'message' => $message,
        'data' => $data,
    ];
}

function usersFindById($id)
{
    // main variable
    $connection = connection();
    $data = null;
    $sql = "SELECT * FROM users WHERE id = $id ORDER BY id DESC LIMIT 1";

    // run sql 
    if ($result = $connection->query($sql)) {
        if ($data = $result->fetch_assoc()) {
            $status = true;
            $message = 'Get data successfully';
        } else {
            $status = false;
            $message = 'Data not found';
        }
    } else {
        $status = false;
        $message = $result->error;
    }

    // close connection
    $connection->close();

    // return information
    return [
        'status' => $status,
        'message' => $message,
        'data' => $data,
    ];
}

function usersSelect($search = null, $limit = 10, $page = 1)
{
    // main variable
    $connection = connection();
    $data = [];
    $sql = "SELECT * FROM users";
    $offset = ($page * $limit) - 10;

    // search sql
    if ($search) {
        $sql .= " WHERE name like '%$search%' or email like '%$search%'";
    }

    // order desc
    $sql .= ' ORDER BY id DESC';

    // limit offset for pagiatnion
    $sql .= " LIMIT $limit";
    if ($offset > 0) {
        $sql .= " OFFSET $offset";
    }

    // run sql 
    if ($result = $connection->query($sql)) {
        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $data[] = [
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'password' => $row['password'],
                    'created_at' => $row['created_at'],
                    'updated_at' => $row['updated_at']
                ];
            }
            $status = true;
            $message = 'Get record data';
        } else {
            $status = true;
            $message = 'Empty Data';
        }
    } else {
        $status = true;
        $message = $result->error;
    }

    // close connection
    $connection->close();

    // return information
    return [
        'status' => $status,
        'message' => $message,
        'data' => $data,
    ];
}

function usersInsert($name, $email, $password)
{
    // main variable
    $connection = connection();
    $password = md5($password);
    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";

    // run sql
    if ($connection->query($sql) === TRUE) {
        $status = true;
        $message = 'New record created successfully';
        $id = $connection->insert_id;
    } else {
        $status = false;
        $message = $connection->error;
        $id = 0;
    }

    // close connection
    $connection->close();

    // return information
    return [
        'status' => $status,
        'message' => $message,
        'id' => $id,
    ];
}

function usersUpdate($id, $name, $email, $password = null)
{
    // main variable
    $connection = connection();

    // query
    $sql = "UPDATE users SET";
    $sql .= " name = '$name'";
    $sql .= " , email = '$email'";
    if ($password) {
        $sql .= " , password = '" . md5($password) . "'";
    }
    $sql .= " WHERE id = $id";

    // run sql
    if ($connection->query($sql) === TRUE) {
        $status = true;
        $message = 'Record updated successfully';
    } else {
        $status = false;
        $message = $connection->error;
    }

    // close connection
    $connection->close();

    // return information
    return [
        'status' => $status,
        'message' => $message,
    ];
}

function usersDelete($id)
{
    // main variable
    $connection = connection();
    $sql = "DELETE FROM users WHERE id = $id";

    // run sql
    if ($connection->query($sql) === TRUE) {
        $status = true;
        $message = 'Record deleted successfully';
    } else {
        $status = false;
        $message = $connection->error;
    }

    // close connection
    $connection->close();

    // return information
    return [
        'status' => $status,
        'message' => $message,
    ];
}
