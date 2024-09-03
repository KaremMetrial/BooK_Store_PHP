<?php
require "./layouts/header.php";
require "../config/config.php";
require "../config/helper.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location:" . ADMIN_URL . "/admins/login-admins.php");
    exit();
}

$numProducts = count(read($conn, 'products'));
$numAdmins = count(read($conn, 'admins'));
$numCategories = count(read($conn, 'categories'));

?>
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Products</h5>
                <p class="card-text">Number of products: <?= htmlspecialchars($numProducts); ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Categories</h5>
                <p class="card-text">Number of categories: <?= htmlspecialchars($numCategories); ?></p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Admins</h5>
                <p class="card-text">Number of admins: <?= htmlspecialchars($numAdmins); ?></p>
            </div>
        </div>
    </div>
</div>
<?php
require "./layouts/footer.php";
?>
