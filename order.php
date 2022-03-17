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
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce</title>
    <?php include "template/style.php"; ?>
</head>

<body>
    <?php include "template/navigation.php" ?>

    <section id="order" class="container"></section>

    <?php include "template/footer.php" ?>
    <?php include "template/script.php" ?>
</body>

</html>