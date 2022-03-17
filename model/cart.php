<?php
// session_start();
// include "../config/connection.php";
// include "../config/helper.php";

function cartDeleteByListId(array $list_id = [])
{
    // main variable
    $connection = connection();
    $id = "(" . implode(",", $list_id) . ")";
    $sql = "DELETE FROM cart WHERE id IN $id";


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

function cartFindByUserAndProducts($users_id, $products_id)
{
    // main variable
    $connection = connection();
    $data = null;
    $sql = "SELECT * FROM cart WHERE users_id = $users_id AND products_id = $products_id ORDER BY id DESC LIMIT 1";

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

function cartSaveByUsersAndMultipleProductsId($users_id, array $products_id = [])
{
    // main variable
    $connection = connection();
    $sql = "INSERT INTO cart (users_id, products_id) VALUES ";

    // set multiple insert
    foreach ($products_id as $key => $p_id) {
        if ($key === 0) {
            $sql .= "('$users_id', '$p_id')";
        } else {
            $sql .= ",('$users_id', '$p_id')";
        }
    }

    // run sql
    if ($connection->query($sql) === TRUE) {
        $status = true;
        $message = 'New record created successfully';
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

function cartGetJoinRelationByUsers($users_id, $limit = 10, $page = 1)
{
    // main variable
    $connection = connection();
    $data = [];
    $sql = "SELECT cart.id, cart.users_id, cart.products_id, products.categories_id, cart.created_at, cart.updated_at,
    users.name as users_name, users.email as users_email, products.name as products_name, products.image as products_image, 
    products.description as products_description, products.price as products_price, categories.category, cart.qty 
    FROM cart 
    INNER JOIN users ON users.id = cart.users_id 
    INNER JOIN products ON products.id = cart.products_id 
    INNER JOIN categories ON categories.id = products.categories_id 
    ";
    $offset = ($page * $limit) - 10;

    // filter by users
    $sql .= "WHERE cart.users_id = $users_id ";

    // order desc
    $sql .= ' ORDER BY cart.id DESC';

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
                    'users_id' => $row['users_id'],
                    'products_id' => $row['products_id'],
                    'categories_id' => $row['categories_id'],
                    'qty' => $row['qty'],
                    'users_name' => $row['users_name'],
                    'users_email' => $row['users_email'],
                    'products_name' => $row['products_name'],
                    'products_image' => $row['products_image'],
                    'products_description' => $row['products_description'],
                    'products_price' => $row['products_price'],
                    'category' => $row['category'],
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

function cartGetJoinRelation($search = null, $limit = 10, $page = 1)
{
    // main variable
    $connection = connection();
    $data = [];
    $sql = "SELECT cart.id, cart.users_id, cart.products_id, products.categories_id, cart.created_at, cart.updated_at,
    users.name as users_name, users.email as users_email, products.name as products_name, products.image as products_image, 
    products.description as products_description, products.price as products_price, categories.category, cart.qty
    FROM cart 
    INNER JOIN users ON users.id = cart.users_id 
    INNER JOIN products ON products.id = cart.products_id 
    INNER JOIN categories ON categories.id = products.categories_id 
    ";
    $offset = ($page * $limit) - 10;

    // search sql
    if ($search) {
        $sql .= " WHERE users.name like '%$search%' 
        OR users.email like '%$search%' 
        OR products.name like '%$search%' 
        OR products.description like '%$search%' 
        OR categories.category like '%$search%' 
        ";

        if (is_numeric($search)) {
            $number = (float) $search;
            $sql .= " OR products.price like '%$number%'";
        }
    }

    // order desc
    $sql .= ' ORDER BY cart.id DESC';

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
                    'users_id' => $row['users_id'],
                    'products_id' => $row['products_id'],
                    'categories_id' => $row['categories_id'],
                    'qty' => $row['qty'],
                    'users_name' => $row['users_name'],
                    'users_email' => $row['users_email'],
                    'products_name' => $row['products_name'],
                    'products_image' => $row['products_image'],
                    'products_description' => $row['products_description'],
                    'products_price' => $row['products_price'],
                    'category' => $row['category'],
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

function cartFindById($id)
{
    // main variable
    $connection = connection();
    $data = null;
    $sql = "SELECT * FROM cart WHERE id = $id ORDER BY id DESC LIMIT 1";

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

function cartSelect($search = null, $limit = 10, $page = 1)
{
    // main variable
    $connection = connection();
    $data = [];
    $sql = "SELECT * FROM cart";
    $offset = ($page * $limit) - 10;

    // search sql
    if ($search) {
        $sql .= " WHERE users_id like '%$search%' OR products_id like '%$search%'";
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
                    'users_id' => $row['users_id'],
                    'products_id' => $row['products_id'],
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

function cartInsert($users_id, $products_id, $qty)
{
    // main variable
    $connection = connection();
    $sql = "INSERT INTO cart (users_id, products_id, qty) VALUES ('$users_id', '$products_id', $qty)";

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

function cartUpdate($id, $users_id, $products_id, $qty)
{
    // main variable
    $connection = connection();

    // query
    $sql = "UPDATE cart SET";
    $sql .= " users_id = '$users_id',";
    $sql .= " products_id = '$products_id',";
    $sql .= " qty = '$qty'";
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

function cartDelete($id)
{
    // main variable
    $connection = connection();
    $sql = "DELETE FROM cart WHERE id = $id";

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
