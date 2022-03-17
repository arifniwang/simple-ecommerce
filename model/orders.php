<?php

// include "../config/connection.php";
// include "../config/helper.php";

function ordersFindLatestByMonthAndYear($month, $year)
{
    // main variable
    $connection = connection();
    $data = null;
    $sql = "SELECT * FROM orders WHERE MONTH(created_at) = '$month' AND YEAR(created_at) = '$year' ORDER BY id DESC LIMIT 1";

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

function ordersGetByUsers($users_id, $limit = 10, $page = 1)
{
    // main variable
    $connection = connection();
    $data = [];
    $sql = "SELECT * FROM orders WHERE users_id = $users_id";
    $offset = ($page * $limit) - $limit;

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
                    'invoice_code' => $row['invoice_code'],
                    'users_id' => $row['users_id'],
                    'price' => $row['price'],
                    'tax' => $row['tax'],
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

function ordersFindById($id)
{
    // main variable
    $connection = connection();
    $data = null;
    $sql = "SELECT * FROM orders WHERE id = $id ORDER BY id DESC LIMIT 1";

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

function ordersSelect($search = null, $limit = 10, $page = 1)
{
    // main variable
    $connection = connection();
    $data = [];
    $sql = "SELECT * FROM orders";
    $offset = ($page * $limit) - $limit;

    // search sql
    if ($search) {
        $sql .= " WHERE invoice_code like '%$search%'";
        $sql .= " OR banks_name like '%$search%'";
        $sql .= " OR account_name like '%$search%'";
        $sql .= " OR account_number like '%$search%'";

        if (is_numeric($search)) {
            $number = (float) $search;
            $sql .= " OR price like '%$number%'";
            $sql .= " OR tax like '%$number%'";
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
                    'invoice_code' => $row['invoice_code'],
                    'users_id' => $row['users_id'],
                    'price' => $row['price'],
                    'tax' => $row['tax'],
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

function ordersInsert($users_id, $banks_name, $account_name, $account_number, $transfer_receipt, $price)
{
    // main variable
    $connection = connection();
    $tax = $price * 0.1; // ppn 10%
    $total = $price + $tax;
    $invoice_code = generateInvoiceCode();
    $sql = "INSERT INTO orders (invoice_code, users_id, banks_name, account_name, account_number, transfer_receipt, price, tax, total) 
    VALUES ('$invoice_code', $users_id, '$banks_name', '$account_name', '$account_number', '$transfer_receipt', $price, $tax, $total)";

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

function ordersUpdate($id, $users_id, $banks_name, $account_name, $account_number, $transfer_receipt, $price)
{
    // main variable
    $connection = connection();
    $tax = $price * 0.1; // ppn 10%
    $total = $price + $tax;

    // query
    $sql = "UPDATE orders SET";
    $sql .= " users_id = '$users_id',";
    $sql .= " banks_name = '$banks_name',";
    $sql .= " account_name = '$account_name',";
    $sql .= " account_number = '$account_number',";
    $sql .= " transfer_receipt = '$transfer_receipt',";
    $sql .= " price = '$price',";
    $sql .= " tax = '$tax',";
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

function ordersDelete($id)
{
    // main variable
    $connection = connection();
    $sql = "DELETE FROM orders WHERE id = $id";

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
