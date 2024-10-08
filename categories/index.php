<?php
require "../config/config.php";
require "../config/helper.php";
require "../includes/header.php";

$getCategories = read($conn, "categories");

?>
<div class="row mt-5">
    <?php foreach ($getCategories as $category) : ?>
    <div class="col-lg-4 col-md-6 col-sm-10 offset-md-0 offset-sm-1 mb-5">
        <div class="card">
            <img height="213px" class="card-img-top"
                 src="image/<?= $category['image']; ?>">
            <div class="card-body">
                <h5><b><?= $category['name']; ?></b></h5>
                <div class="d-flex flex-row my-2">
                    <div class="text-muted">
                        <?= $category['description']; ?>
                    </div>

                </div>
                <a href="single-category.php?id=<?= $category['id']; ?>" class="btn btn-primary w-100 rounded my-2">Discover Products</a>
            </div>
        </div>
    </div>
    <br>
    <?php endforeach; ?>
</div>

<?php require "../includes/footer.php"; ?>
