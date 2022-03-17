<?php

// include "../config/connection.php";
// include "../config/helper.php";

function productsCountBySearch($search = null)
{
    // main variable
    $connection = connection();
    $data = 0;
    $sql = "SELECT COUNT(*) AS total FROM products INNER JOIN categories ON categories.id = products.categories_id";

    // search sql
    if ($search) {
        $sql .= " WHERE categories.category like '%$search%' 
        OR products.name like '%$search%' 
        OR products.description like '%$search%'";
    }

    // run sql 
    if ($result = $connection->query($sql)) {
        if ($result->num_rows > 0) {
            // output data of each row
            $status = true;
            $message = 'Get record data';
            $data = $result->fetch_assoc()['total'];
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

function productsCountByCategories($categories_id)
{
    // main variable
    $connection = connection();
    $data = 0;
    $sql = "SELECT COUNT(*) AS total FROM products INNER JOIN categories ON categories.id = products.categories_id 
    WHERE products.categories_id = $categories_id";

    // run sql 
    if ($result = $connection->query($sql)) {
        if ($result->num_rows > 0) {
            // output data of each row
            $status = true;
            $message = 'Get record data';
            $data = $result->fetch_assoc()['total'];
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

function productsFindWithCategoriesById($id)
{
    // main variable
    $connection = connection();
    $data = null;
    $sql = "SELECT products.id, products.categories_id, products.name, categories.category, 
    products.image, products.description, products.price, products.created_at, products.updated_at 
    FROM products INNER JOIN categories ON categories.id = products.categories_id 
    WHERE products.id = $id ORDER BY products.id DESC LIMIT 1";

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

function productsGetByCategories($categories_id, $limit = 10, $page = 1)
{
    // main variable
    $connection = connection();
    $data = [];
    $sql = "SELECT products.id, products.categories_id, products.name, categories.category, 
    products.image, products.description, products.price, products.created_at, products.updated_at
    FROM products INNER JOIN categories ON categories.id = products.categories_id 
    WHERE products.categories_id = $categories_id";
    $offset = ($page * $limit) - 10;


    // order desc
    $sql .= ' ORDER BY products.id DESC';

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
                    'categories_id' => $row['categories_id'],
                    'name' => $row['name'],
                    'category' => $row['category'],
                    'image' => $row['image'],
                    'description' => $row['description'],
                    'price' => $row['price'],
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

function productsGetWithCategories($search = null, $limit = 10, $page = 1)
{
    // main variable
    $connection = connection();
    $data = [];
    $sql = "SELECT products.id, products.categories_id, products.name, categories.category, 
    products.image, products.description, products.price, products.created_at, products.updated_at
    FROM products INNER JOIN categories ON categories.id = products.categories_id";
    $offset = ($page * $limit) - 10;

    // search sql
    if ($search) {
        $sql .= " WHERE categories.category like '%$search%' 
        OR products.name like '%$search%' 
        OR products.description like '%$search%'";
    }

    // order desc
    $sql .= ' ORDER BY products.id DESC';

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
                    'categories_id' => $row['categories_id'],
                    'name' => $row['name'],
                    'category' => $row['category'],
                    'image' => $row['image'],
                    'description' => $row['description'],
                    'price' => $row['price'],
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

function productsFindById($id)
{
    // main variable
    $connection = connection();
    $data = null;
    $sql = "SELECT * FROM products WHERE id = $id ORDER BY id DESC LIMIT 1";

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

function productsSelect($search = null, $limit = 10, $page = 1)
{
    // main variable
    $connection = connection();
    $data = [];
    $sql = "SELECT * FROM products";
    $offset = ($page * $limit) - 10;

    // search sql
    if ($search) {
        $sql .= " WHERE name like '%$search%' OR description like '%$search%'";

        if (is_numeric($search)) {
            $number = (float) $search;
            $sql .= " OR price like '%$number%'";
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
                    'categories_id' => $row['categories_id'],
                    'name' => $row['name'],
                    'image' => $row['image'],
                    'description' => $row['description'],
                    'price' => $row['price'],
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

function productsInsert($categories_id, $name, $image, $description, $price)
{
    // main variable
    $connection = connection();
    $sql = "INSERT INTO products (categories_id, name, image, description, price) VALUES 
    ('$categories_id', '$name', '$image', '$description', '$price')";

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

function productsUpdate($id, $categories_id, $name, $image, $description, $price)
{
    // main variable
    $connection = connection();

    // query
    $sql = "UPDATE products SET";
    $sql .= " categories_id = '$categories_id',";
    $sql .= " name = '$name',";
    $sql .= " image = '$image',";
    $sql .= " description = '$description',";
    $sql .= " price = '$price'";
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

function productsDelete($id)
{
    // main variable
    $connection = connection();
    $sql = "DELETE FROM products WHERE id = $id";

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
