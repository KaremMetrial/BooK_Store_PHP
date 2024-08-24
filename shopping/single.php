<?php
require "../config/config.php";
require "../config/helper.php";
require "../includes/header.php";

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $product = read($conn, 'products', ['id' => $_GET['id']]);
    if (!$product) {
        echo "<center><h2>Product not found</h2></center>";
        exit();
    }
}
?>
    <div class="row d-flex justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="row">
                    <div class="col-md-6">
                        <div class="images p-3">
                            <div class="text-center p-4"><img id="main-image" src="../images/<?= $product[0]['image']; ?>" width="100%"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="product p-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center"><a href="../index.php" class="ml-1 btn btn-primary"><i
                                                class="fa fa-long-arrow-left"></i> Back</a></div>
                                <i class="fa fa-shopping-cart text-muted"></i>
                            </div>
                            <div class="mt-4 mb-3">
                                <h5 class="text-uppercase"><?= $product[0]['name']; ?></h5>
                                <div class="price d-flex flex-row align-items-center"><span class="act-price">$<?= $product[0]['price']; ?></span>
                                </div>
                            </div>
                            <p class="about"><?= $product[0]['description']; ?>.</p>

                            <div class="cart mt-4 align-items-center">
                                <button class="btn btn-primary text-uppercase mr-2 px-4"><i
                                            class="fas fa-shopping-cart"></i> Add to cart
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
require "../includes/footer.php";
?>