<?php

// include "../config/connection.php";
// include "../config/helper.php";

function orderDetailsGetByOrders($orders_id)
{
    // main variable
    $connection = connection();
    $data = [];
    $sql = "SELECT * FROM order_details WHERE orders_id = $orders_id ORDER BY id DESC";

    // run sql 
    if ($result = $connection->query($sql)) {
        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $data[] = [
                    'id' => $row['id'],
                    'orders_id' => $row['orders_id'],
                    'products_id' => $row['products_id'],
                    'name' => $row['name'],
                    'category' => $row['category'],
                    'image' => $row['image'],
                    'description' => $row['description'],
                    'price' => $row['price'],
                    'qty' => $row['qty'],
                    'total' => $row['total'],
                    'created_at' => $row['created_at'],
                    'updated_at' => $row['updated_at'],
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

function orderDetailsFindById($id)
{
    // main variable
    $connection = connection();
    $data = null;
    $sql = "SELECT * FROM order_details WHERE id = $id ORDER BY id DESC LIMIT 1";

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

function orderDetailsSelect($search = null, $limit = 10, $page = 1)
{
    // main variable
    $connection = connection();
    $data = [];
    $sql = "SELECT * FROM order_details";
    $offset = ($page * $limit) - $limit;

    // search sql
    if ($search) {
        $sql .= " WHERE name like '%$search%'";
        $sql .= " OR category like '%$search%'";
        $sql .= " OR description like '%$search%'";

        if (is_numeric($search)) {
            $number = (float) $search;
            $sql .= " OR price like '%$number%'";
            $sql .= " OR qty like '%$number%'";
            $sql .= " OR total like '%$number%'";
        }
    }

    // order desc
    $sql .= " ORDER BY id DESC";

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
                    'orders_id' => $row['orders_id'],
                    'products_id' => $row['products_id'],
                    'name' => $row['name'],
                    'category' => $row['category'],
                    'image' => $row['image'],
                    'description' => $row['description'],
                    'price' => $row['price'],
                    'qty' => $row['qty'],
                    'total' => $row['total'],
                    'created_at' => $row['created_at'],
                    'updated_at' => $row['updated_at'],
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

function orderDetailsInsert($orders_id, $products_id, $name, $category, $image, $description, $price, $qty)
{
    // main variable
    $connection = connection();
    $total = $price * $qty;

    // query
    $sql = "INSERT INTO order_details (orders_id, products_id, name, category, image, description, price, qty, total) 
    VALUES ($orders_id, $products_id, '$name', '$category', '$image', '$description', $price, $qty, $total)";

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

function orderDetailsUpdate($id, $orders_id, $products_id, $name, $category, $image, $description, $price, $qty)
{
    // main variable
    $connection = connection();
    $total = $price * $qty;

    // query
    $sql = "UPDATE order_details SET";
    $sql .= " orders_id = '$orders_id',";
    $sql .= " products_id = '$products_id',";
    $sql .= " name = '$name',";
    $sql .= " category = '$category',";
    $sql .= " image = '$image',";
    $sql .= " description = '$description',";
    $sql .= " price = '$price',";
    $sql .= " qty = '$qty',";
    $sql .= " total = '$total'";
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

function orderDetailsDelete($id)
{
    // main variable
    $connection = connection();
    $sql = "DELETE FROM order_details WHERE id = $id";

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
