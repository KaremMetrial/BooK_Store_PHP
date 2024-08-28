<?php
require "../config/config.php";
require "../config/helper.php";
require "../includes/header.php";
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/login.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $seCart = read($conn, 'cart', ['product_id' => $_GET['id'], 'user_id' => $_SESSION['user_id']]);
    $product = read($conn, 'products', ['id' => $_GET['id']]);
    if (!$product) {
        echo "<center><h2>Product not found</h2></center>";
        exit();
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $pro_id = htmlspecialchars($_POST['product_id']);
    $pro_name = htmlspecialchars($_POST['product_name']);
    $pro_price = htmlspecialchars($_POST['product_price']);
    $pro_image = htmlspecialchars($_POST['product_image']);
    $pro_amount = htmlspecialchars($_POST['product_amount']);
    $pro_file = htmlspecialchars($_POST['product_file']);
    $user_id = $_POST['user_id'];

    $inCart = insert($conn, 'cart', [
        'user_id' => $user_id,
        'product_id' => $pro_id,
        'product_name' => $pro_name,
        'product_price' => $pro_price,
        'product_image' => $pro_image,
        'product_amount' => $pro_amount,
        'product_file' => $pro_file,
    ]);

}
?>
<div class="row d-flex justify-content-center my-5">
    <div class="col-md-10">
        <div class="card">
            <div class="row">
                <div class="col-md-6">
                    <div class="images p-3">
                        <div class="text-center p-4"><img id="main-image"
                                                          src="../images/<?= $product[0]['image']; ?>"
                                                          width="100%"/>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="product p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center"><a href="../index.php"
                                                                      class="ml-1 btn btn-primary"><i
                                            class="fa fa-long-arrow-left"></i> Back</a></div>
                            <i class="fa fa-shopping-cart text-muted"></i>
                        </div>
                        <div class="mt-4 mb-3">
                            <h5 class="text-uppercase"><?= $product[0]['name']; ?></h5>
                            <div class="price d-flex flex-row align-items-center"><span
                                        class="act-price">$<?= $product[0]['price']; ?></span>
                            </div>
                        </div>
                        <p class="about"><?= $product[0]['description']; ?>.</p>
                        <form action="" method="post" id="form-data">
                            <input type="hidden" name="csrf_token"
                                   value="<?php echo htmlspecialchars($csrf_token); ?>">
                            <div class="">
                                <input type="hidden" name="product_id" class="form-control" id=""
                                       value="<?= $product[0]['id']; ?>">
                            </div>
                            <div class="">
                                <input type="hidden" name="product_name" class="form-control" id=""
                                       value="<?= $product[0]['name']; ?>">
                            </div>
                            <div class="">
                                <input type="hidden" name="product_image" class="form-control" id=""
                                       value="<?= $product[0]['image']; ?>">
                            </div>
                            <div class="">
                                <input type="hidden" name="product_price" class="form-control" id=""
                                       value="<?= $product[0]['price']; ?>">
                            </div>
                            <div class="">
                                <input type="hidden" name="product_amount" class="form-control" id="" value="1">
                            </div>
                            <div class="">
                                <input type="hidden" name="product_file" class="form-control" id=""
                                       value="<?= $product[0]['file']; ?>">
                            </div>
                            <div class="">
                                <input type="hidden" name="user_id" class="form-control" id=""
                                       value="<?= $_SESSION['user_id']; ?>">
                            </div>
                            <div class="cart mt-4 align-items-center">
                                <?php if ($seCart):?>
                                    <button id="submit" disabled type="submit" name="submit"
                                            class="btn btn-primary text-uppercase mr-2 px-4"><i
                                                class="fas fa-shopping-cart"></i> Added to cart
                                    </button>
                                <?php else: ?>
                                    <button id="submit" type="submit" name="submit"
                                            class="btn btn-primary text-uppercase mr-2 px-4"><i
                                                class="fas fa-shopping-cart"></i> Add to cart
                                    </button>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require "../includes/footer.php";
?>
<script>
    $(document).ready(function () {
        $('#form-data').on('submit', function (e) {
            e.preventDefault(); // Prevent the form from submitting normally
            var formdata = $(this).serialize() + "&submit=submit";
            $.ajax({
                type: 'POST',
                url: "single.php?id=<?= $product[0]['id']; ?>",
                data: formdata,
                success: function (response) {
                    alert("Added to cart successfully!");
                    $("#submit").html("<i class='fas fa-shopping-cart'></i> Added to cart").prop("disabled", true);
                    window.location.reload();
                },
                error: function () {
                    alert("Failed to add to cart. Please try again.");
                }
            });
        });

    });
</script>

