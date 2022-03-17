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

$sub_total = 0;
$cart = cartGetJoinRelationByUsers($_SESSION['id']);

// form variable
$banks_name = "";
$account_name = "";
$account_number = "";

if (isset($_POST['submit'])) {
    // check data
    $cart = cartGetJoinRelationByUsers($_SESSION['id']);

    // form variable
    $sub_total = 0;
    $banks_name = $_POST['banks_name'];
    $account_name = $_POST['account_name'];
    $account_number = $_POST['account_number'];

    if ($cart['data'] === null) {
        echo "<script>alert('Cart is empty')</script>";
    } else {
        $image = uploadImage('transfer_receipt');
        if (!$image['status']) {
            echo "<script>alert('Failed Upload Transfer Receipt')</script>";
        } else {
            // image path
            $transfer_receipt = $image['data'];

            // calculate sub total and make temp delete id
            $cart_id = [];
            foreach ($cart['data'] as $i => $row) {
                $sub_total += $row['products_price'] * $row['qty'];
                $cart_id[] = $row['id'];
            }

            // save data
            $orders_id = ordersInsert($_SESSION['id'], $banks_name, $account_name, $account_number, $transfer_receipt, $sub_total);
            foreach ($cart['data'] as $i => $row) {
                $total = $row['products_price'] * $row['qty'];
                $details_id = orderDetailsInsert(
                    $orders_id['id'],
                    $row['products_id'],
                    $row['products_name'],
                    $row['category'],
                    $row['products_image'],
                    $row['products_description'],
                    $row['products_price'],
                    $row['qty']
                );
            }

            // delete cart
            cartDeleteByListId($cart_id);
            $_SESSION['cart'] = 0;
        }

        echo "<script>alert('Checkout Success, thanks for ordering we\'ll message on your email!')</script>";
        echo "<script>window.location.href = 'cart.php'</script>";
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

    <section id="cart" class="h-100 h-custom">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col">
                    <div class="card">
                        <div class="card-body p-4">

                            <div class="row">

                                <div class="col-lg-7">
                                    <h5 class="mb-3">
                                        <a href="index.php" class="text-body"><i class="fas fa-long-arrow-alt-left me-2"></i>Continue shopping</a>
                                    </h5>
                                    <hr>
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <div>
                                            <p class="mb-1">Shopping cart</p>
                                        </div>
                                        <div>
                                            <p class="mb-0">You have <?php echo $_SESSION['cart'] ?> items in your cart</p>
                                        </div>
                                    </div>

                                    <?php foreach ($cart['data'] as $i => $row) : ?>
                                        <?php
                                        $total = $row['products_price'] * $row['qty'];
                                        $sub_total += $total;
                                        ?>
                                        <div class="card mb-3">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between">
                                                    <div class="d-flex flex-row align-items-center">
                                                        <div>
                                                            <img src="<?php echo $row['products_image'] ?>" class="img-fluid rounded-3" alt="Shopping item" style="width: 65px;">
                                                        </div>
                                                        <div class="ms-3">
                                                            <h5>
                                                                <a href="product-detail.php?id=<?php echo $row['products_id'] ?>"><?php echo $row['products_name'] ?></a>
                                                            </h5>
                                                            <p class="small mb-0"><?php echo $row['category'] ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex flex-row align-items-center">
                                                        <div style="width: 50px;">
                                                            <h5 class="fw-normal mb-0"><?php echo numberFormat($row['qty']) ?></h5>
                                                        </div>
                                                        <div class="me-4">
                                                            <h5 class="mb-0">Rp <?php echo numberFormat($total) ?></h5>
                                                        </div>
                                                        <a href="cart_delete.php?id=<?php echo $row['id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure to delete from chart?')">
                                                            Delete
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>


                                <?php
                                $ppn = $sub_total * 0.1;
                                $grand_total = $sub_total + $ppn;
                                ?>
                                <form method="POST" enctype="multipart/form-data" class="col-lg-5">

                                    <div class="card bg-primary text-white rounded-3">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-4">
                                                <h5 class="mb-0">Payment details</h5>
                                            </div>

                                            <p class="small mb-4">
                                                Please Transfer before Checkout <br>
                                                <b>BCA: 123 456 7891 (Arif Niwang Djati)</b>
                                            </p>

                                            <form class="mt-4">
                                                <div class="form-outline form-white mb-4">
                                                    <input type="text" id="banks_name" name="banks_name" class="form-control" placeholder="e.g. Mandiri, BCA, BRI" value="<?php echo $banks_name; ?>" required />
                                                    <label class="form-label" for="banks_name">Banks Name</label>
                                                </div>

                                                <div class="form-outline form-white mb-4">
                                                    <input type="text" id="account_name" name="account_name" class="form-control" placeholder="e.g. Rizky Nugraha" value="<?php echo $account_name; ?>" required />
                                                    <label class="form-label" for="account_name">Account Name</label>
                                                </div>

                                                <div class="form-outline form-white mb-4">
                                                    <input type="text" id="account_number" name="account_number" class="form-control" placeholder="e.g. 1234 5678 9012 3457" value="<?php echo $account_number; ?>" required />
                                                    <label class="form-label" for="account_number">Account Number</label>
                                                </div>

                                                <div class="form-outline mb-4">
                                                    <input type="file" id="transfer_receipt" name="transfer_receipt" class="form-control" accept="image/png, image/jpg, image/jpeg" required />
                                                    <label class="form-label" for="transfer_receipt">Transfer Receipt</label>
                                                </div>
                                            </form>

                                            <hr class="my-4">

                                            <div class="d-flex justify-content-between">
                                                <p class="mb-2">Subtotal</p>
                                                <p class="mb-2">Rp <?php echo numberFormat($sub_total) ?></p>
                                            </div>

                                            <div class="d-flex justify-content-between">
                                                <p class="mb-2">Tax</p>
                                                <p class="mb-2">Rp <?php echo numberFormat($ppn) ?></p>
                                            </div>

                                            <div class="d-flex justify-content-between mb-4">
                                                <p class="mb-2"><b>Total</b></p>
                                                <p class="mb-2"><b>Rp <?php echo numberFormat($grand_total) ?></b></p>
                                            </div>

                                            <button type="submit" name="submit" class="btn btn-danger btn-block btn-lg w-100">
                                                <div class="d-flex justify-content-center">
                                                    <span>Checkout <i class="fas fa-long-arrow-alt-right ms-2"></i></span>
                                                </div>
                                            </button>

                                        </div>
                                    </div>

                                </form>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include "template/footer.php" ?>
    <?php include "template/script.php" ?>
</body>

</html>