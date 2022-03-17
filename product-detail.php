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


if (!isset($_GET['id']) || $_GET['id'] == '') {
    header('Location: index.php');
}

$product = productsFindById($_GET['id']);
if ($product['data'] === null) {
    header('Location: index.php');
}

$categories = categoriesFindById($product['data']['categories_id']);
$path = 'products-' . $categories['data']['id'];
$cart = cartFindByUserAndProducts($_SESSION['id'], $product['data']['id']);
$existing_qty = ($cart['data'] == null ? 1 : $cart['data']['qty']);

if (isset($_POST['submit'])) {
    if (!isset($_SESSION['login']) || $_SESSION['login'] == '') {
        echo "<script>alert('Please login first to add cart')</script>";
        echo "<script>window.location.href = 'login.php'</script>";
    } else if ($cart['data'] == null) {
        cartInsert($_SESSION['id'], $product['data']['id'], $_POST['qty']); // save data
        $_SESSION['cart'] = $_SESSION['cart'] + 1; // update cart session
    } else {
        cartUpdate($cart['data']['id'], $_SESSION['id'], $product['data']['id'], $_POST['qty']);
        echo "<script>alert('Cart has been added.')</script>";
        echo "<script>window.location.href = 'cart.php'</script>";
    }
    $existing_qty = $_POST['qty'];
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

    <section id="product-details" class="d-flex flex-column justify-content-center align-items-center pt-3 mb-3">
        <form class="container" method="POST">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item">
                        <a href="products.php?c=<?php echo $categories['data']['id'] ?>"><?php echo $categories['data']['category'] ?></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Product Details</li>
                </ol>
            </nav>

            <div class="card mb-3">
                <div class="row g-0">
                    <div class="col-lg-6">
                        <img src="<?php echo $product['data']['image'] ?>" class="img-fluid w-100 mb-3">
                    </div>
                    <div class="col-lg-6">
                        <div class="card-body">
                            <div class="d-flex flex-row justify-content-between">
                                <h2 class="card-title"><?php echo $product['data']['name'] ?></h2>
                                <h3 class="card-text"><?php echo "Rp " . numberFormat($product['data']['price']) ?></h3>
                            </div>
                            <div class="card mb-3">
                                <div class="card-body">
                                    <p class="card-text"><?php echo nl2br($product['data']['description']) ?></p>
                                </div>
                            </div>
                            <div class="d-flex flex-row justify-content-between">
                                <div class="d-flex flex-row justify-content-between align-content-center">
                                    <label for="qty">Qty</label>
                                    <input id="qty" name="qty" type="number" class="form-control ms-3" placeholder="QTY" value="<?php echo $existing_qty ?>" min="1" required>
                                </div>
                                <button type="submit" name="submit" class="btn btn-danger">Add to Cart</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>

    <?php include "template/footer.php" ?>
    <?php include "template/script.php" ?>
</body>

</html>