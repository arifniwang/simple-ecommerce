<?php
$nav_categories = categoriesSelect();
$nav_cart = (isset($_SESSION['cart'])) ? ($_SESSION['cart'] > 0 ? $_SESSION['cart'] : "") : "";
$nav_search = (isset($_GET['q'])) ? $_GET['q'] : "";
?>

<nav class="navbar fixed-top navbar-expand-lg navbar-light bg-white">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="assets/img/logo.png" alt="Logo" height="45">
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse main-menu-wrap" id="navbarMenu">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($path == 'index' ? 'active' : '') ?>" aria-current="page" href="index.php">Home</a>
                </li>
                <?php foreach ($nav_categories['data'] as $nav_i => $nav_category) : ?>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($path == 'products-' . $nav_category['id'] ? 'active' : '') ?>" aria-current="page" href="products.php?c=<?php echo $nav_category['id'] ?>">
                            <?php echo $nav_category['category'] ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

            <form class="d-flex" method="GET" action="search.php">
                <input class="form-control me-2" type="search" name="q" placeholder="Search..." value="<?php echo $nav_search ?>">
                <a href="cart.php" class="btn btn-outline-secondary d-flex flex-row justify-content-between" type="button">
                    Cart <span class="badge bg-danger align-self-center ms-2"><?php echo $nav_cart ?></span>
                </a>
                <?php if (!isLogin()) : ?>
                    <a href="login.php" class="btn btn-outline-success d-flex flex-row justify-content-between ms-2" type="button">
                        Login
                    </a>
                <?php else : ?>
                    <div class="btn-group ms-2">
                        <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="navProfile">
                            Hello, <?php echo shortName() ?>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="navProfile">
                            <li><a class="dropdown-item" href="order.php">Order</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="logout.php" onclick="return confirm('are you sure to logout?')">Logout</a></li>
                        </ul>
                    </div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</nav>