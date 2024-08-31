<?php
require "../config/config.php";
require "../config/helper.php";
require "../includes/header.php";

$getProductByCategory = read($conn, "products",['category_id' => $_GET['id'],"status" => 1]);
?>
<div class="row mt-5">
    <?php foreach ($getProductByCategory as $product) : ?>
    <div class="col-lg-4 col-md-6 col-sm-10 offset-md-0 offset-sm-1 mb-5">
        <div class="card">
            <img height="213px" class="card-img-top"
                 src="../images/<?= $product['image']; ?>">
            <div class="card-body">
                <h5><b><?= $product['name']; ?></b></h5>
                <div class="d-flex flex-row my-2">
                    <div class="text-muted">
                        <?= $product['description']; ?>
                    </div>

                </div>
                <a href="" class="btn btn-primary w-100 rounded my-2">Discover Products</a>
            </div>
        </div>
    </div>
    <br>
    <?php endforeach; ?>
</div>

<?php require "../includes/footer.php"; ?>
