<?php
require "../config/config.php";
require "../config/helper.php";
require "../includes/header.php";

$getProductByCategory = read($conn, "products",['category_id' => $_GET['id'],"status" => 1]);
?>
<div class="row mt-5">
    <?php foreach ($getProductByCategory as $product) : ?>
        <div class="col-lg-4 col-md-6 col-sm-10 offset-md-0 offset-sm-1">
            <div class="card">
                <img height="213px" class="card-img-top" src="<?= APPURL; ?>/images/<?= $product['image']; ?>">
                <div class="card-body">
                    <h5 class="d-inline"><b><?= $product['name']; ?></b></h5>
                    <h5 class="d-inline">
                        <div class="text-muted d-inline">($<?= $product['price']; ?>/item)</div>
                    </h5>
                    <p> <?= substr($product['description'],0,80); ?>... </p>
                    <a href="../shopping/single.php?id=<?= $product['id']; ?>" class="btn btn-primary w-100 rounded my-2"> More<i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        <br>
    <?php endforeach; ?>
</div>

<?php require "../includes/footer.php"; ?>
