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


if (!isset($_GET['c']) || $_GET['c'] == '') {
    header('Location: index.php');
}

$path = 'products-' . $_GET['c'];
$pagination = 6;
$categories = categoriesFindById($_GET['c']);
$total = productsCountByCategories($categories['data']['id']);


// pagination
$current_page = (isset($_GET['page']) ? $_GET['page'] : 1);
$max_page = ceil($total['data'] / $pagination);

// get data
$products = productsGetByCategories($categories['data']['id'], $pagination, $current_page);
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

    <section id="products" class="container px-4 py-5">

        <h2 class="title text-center"><?php echo $categories['data']['category'] ?></h2>

        <div class="row g-4 py-4 row-cols-1 row-cols-lg-3">
            <?php foreach ($products['data'] as $j => $product) : ?>
                <div class="col-12 col-lg-4">
                    <div class="card">
                        <img src="<?php echo $product['image'] ?>" class="card-img-top img-100">
                        <div class="card-body">
                            <h5 class="card-title text-center"><?php echo $product['name'] ?></h5>
                            <p class="card-text text-center"><?php echo substr($product['description'], 0, 120) ?></p>
                            <a href="product-detail.php?id=<?php echo $product['id'] ?>" class="btn btn-primary w-100">Detail</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (count($products['data']) < 1) : ?>
                <div class="col-12 col-lg-12">
                    <h2 class="text-center">Data Not Found</h2>
                </div>
            <?php endif; ?>
        </div>

        <?php if (count($products['data']) > 0) : ?>
            <div class="d-flex justify-content-center">
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <li class="page-item <?php echo ($current_page <= 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?php echo ($current_page <= 1) ? '#' : '?c=' . $_GET['c'] . '&page=' . ($current_page - 1) ?>">Previous</a>
                        </li>
                        <?php for ($i = 1; $i <= $max_page; $i++) : ?>
                            <li class="page-item  <?php echo ($current_page == $i) ? 'active' : '' ?>">
                                <a class="page-link" href="<?php echo '?c=' . $_GET['c'] . '&page=' . $i ?>"><?php echo $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?php echo ($current_page >= $max_page) ? 'disabled' : '' ?>">
                            <a class="page-link" href="<?php echo ($current_page >= $max_page) ? '#' : '?c=' . $_GET['c'] . '&page=' . ($current_page + 1) ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    </section>

    <?php include "template/footer.php" ?>
    <?php include "template/script.php" ?>
</body>

</html>