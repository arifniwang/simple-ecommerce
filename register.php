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

// save registration
if (isset($_POST['submit'])) {
    // check email
    $users = usersFindByEmail($_POST['email']);

    if ($users['data'] === null) {
        $save = usersInsert($_POST['name'], $_POST['email'], $_POST['password']);
        echo "<script>alert('Register Success, you can login now!')</script>";
        echo "<script>window.location.href = 'login.php'</script>";
        exit;
    } else {
        echo "<script>alert('Register failed, Email has been taken.')</script>";

        // form variable
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
    }
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
                        <h1 class="h3 mb-3 fw-normal text-center">Register Form</h1>
                        <div class="form-floating mb-3">
                            <input type="text" name="name" class="form-control" id="name" placeholder="your name" required value="<?php echo $name; ?>">
                            <label for="name">Name</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="email" name="email" class="form-control" id="email" placeholder="name@example.com" required value="<?php echo $email; ?>">
                            <label for="email">Email address</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" name="password" class="form-control" id="password" placeholder="Password" required value="<?php echo $password; ?>">
                            <label for="password">Password</label>
                        </div>
                        <button class="w-100 mb-3 btn btn-lg btn-primary text-center" type="submit" name="submit">Sign in</button>
                        <p class="text-center">
                            Back to <a href="login.php">Login</a>
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