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
$path = 'index';
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
    <section id="carouselBanner" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <?php for ($i = 0; $i < 4; $i++) : ?>
                <button type="button" data-bs-target="#carouselBanner" data-bs-slide-to="<?php echo $i ?>" class="<?php echo ($i === 0 ? 'active' : '') ?>"></button>
            <?php endfor ?>
        </div>
        <div class="carousel-inner">
            <?php for ($i = 1; $i <= 4; $i++) : ?>
                <div class="carousel-item <?php echo ($i === 1 ? 'active' : '') ?>">
                    <img src="assets/img/banner<?php echo $i ?>.jpg" class="img-banner" alt="Banner <?php echo $i ?>">
                </div>
            <?php endfor ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselBanner" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselBanner" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </section>

    <section id="categories" class="container px-4 py-5">

        <?php $categories = categoriesSelect(); ?>
        <?php foreach ($categories['data'] as $i => $category) : ?>
            <?php $products = productsGetByCategories($category['id'], 3); ?>

            <div class="d-flex flex-row justify-content-between border-bottom pt-4">
                <h3 class="pb-2"><?php echo $category['category'] ?></h3>
                <h6 class="align-self-center"><a href="products.php?c=<?php echo $category['id'] ?>">See All</a></h6>
            </div>

            <div class="row g-4 py-4 row-cols-1 row-cols-lg-3">
                <?php foreach ($products['data'] as $j => $product) : ?>
                    <div class="col-12 col-lg-4">
                        <div class="card">
                            <img src="<?php echo $product['image'] ?>" class="card-img-top img-100">
                            <div class="card-body">
                                <h5 class="card-title text-center"><?php echo $product['name'] ?></h5>
                                <p class="card-text text-center"><?php echo substr($product['description'], 0, 150) ?></p>
                                <a href="product-detail.php?id=<?php echo $product['id'] ?>" class="btn btn-primary w-100">Detail</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </section>

    <?php include "template/footer.php"; ?>
    <?php include "template/script.php"; ?>
</body>

</html>