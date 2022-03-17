<?php
session_start();
include "config/helper.php";
include "config/connection.php";
include "model/cart.php";
include "model/categories.php";
include "model/orders.php";
include "model/order_details.php";
include "model/products.php";
include "model/users.php";


if (!isset($_SESSION['login']) || $_SESSION['login'] == '') {
    echo "<script>alert('Please login first to continue.')</script>";
    echo "<script>window.location.href = 'login.php'</script>";
} else if (!isset($_GET['id']) || $_GET['id'] == '') {
    echo "<script>alert('Invalid Parameter.')</script>";
    echo "<script>window.location.href = 'cart.php'</script>";
}

// get data
$cart = cartFindById($_GET['id']);

if ($cart['data'] === null) {
    echo "<script>alert('Data notfound.')</script>";
    echo "<script>window.location.href = 'cart.php'</script>";
} else if ($cart['data']['users_id'] != $_SESSION['id']) {
    echo "<script>alert('Data notfound.')</script>";
    echo "<script>window.location.href = 'cart.php'</script>";
} else {
    $act = cartDelete($cart['data']['id']);
    $_SESSION['cart'] = $_SESSION['cart'] - 1;

    echo "<script>alert('Cart has been deleted.')</script>";
    echo "<script>window.location.href = 'cart.php'</script>";
}
