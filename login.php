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

if (isset($_SESSION['login']) && $_SESSION['login'] != '') {
    header('Location: index.php');
}

// form variable
$name = "";
$email = "";
$password = "";

if (isset($_POST['submit'])) {
    // check email
    $users = usersFindByEmail($_POST['email']);

    if ($users['data'] === null) {
        echo "<script>alert('Email is invalid')</script>";
    } else if ($users['data']['password'] != md5($_POST['password'])) {
        echo "<script>alert('Password is invalid')</script>";
    } else {
        $cart = cartGetJoinRelationByUsers($users['data']['id']);

        $_SESSION['login'] = 1;
        $_SESSION['id'] = $users['data']['id'];
        $_SESSION['name'] = $users['data']['name'];
        $_SESSION['cart'] = count($cart['data']);

        echo "<script>alert('Login Success, welcome back " . $users['data']['name'] . "!')</script>";
        echo "<script>window.location.href = 'index.php'</script>";
    }

    // form variable
    $email = $_POST['email'];
    $password = $_POST['password'];
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

    <section id="auth" class="d-flex flex-column justify-content-center align-items-center">
        <div class="container">
            <div class="row">
                <div class="col-12 col-lg-6 ms-auto me-auto">
                    <form method="POST" class="text-center">
                        <h1 class="h3 mb-3 fw-normal text-center">Please sign in</h1>
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" value="<?php echo $email ?>">
                            <label for="email">Email address</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" value="<?php echo $password ?>">
                            <label for="password">Password</label>
                        </div>
                        <button class="w-100 mb-3 btn btn-lg btn-primary text-center" type="submit" name="submit">Login</button>
                        <p class="text-center">
                            Don't have an account? <a href="register.php">Register Here</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <?php include "template/footer.php" ?>
    <?php include "template/script.php" ?>
</body>

</html>