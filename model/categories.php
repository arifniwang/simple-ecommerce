<?php

// include "../config/connection.php";
// include "../config/helper.php";

function categoriesFindById($id)
{
    // main variable
    $connection = connection();
    $data = null;
    $sql = "SELECT * FROM categories WHERE id = $id ORDER BY id DESC LIMIT 1";

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

function categoriesSelect($search = null, $limit = 10, $page = 1)
{
    // main variable
    $connection = connection();
    $data = [];
    $sql = "SELECT * FROM categories";
    $offset = ($page * $limit) - 10;

    // search sql
    if ($search) {
        $sql .= " WHERE category like '%$search%'";
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
                    'category' => $row['category'],
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

function categoriesInsert($category)
{
    // main variable
    $connection = connection();
    $sql = "INSERT INTO categories (category) VALUES ('$category')";

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

function categoriesUpdate($id, $category)
{
    // main variable
    $connection = connection();

    // query
    $sql = "UPDATE categories SET";
    $sql .= " category = '$category'";
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

function categoriesDelete($id)
{
    // main variable
    $connection = connection();
    $sql = "DELETE FROM categories WHERE id = $id";

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
