<?php
require "../config/config.php";
require "../config/helper.php";
require "../includes/header.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update'])) {
        $product_id = $_POST['id'];
        $new_amount = $_POST['product_amount'];

        $updateCart = update($conn, 'cart', ['product_amount' => $new_amount], ['product_id' => $product_id, 'user_id' => $_SESSION['user_id']]);

        if ($updateCart) {
            header('Location: cart.php');
            exit();
        } else {
            echo "fail";
        }
        exit();
    }

    if (isset($_POST['delete'])) {
        $product_id = $_POST['id'];

        $deleteCart = delete($conn, 'cart', ['product_id' => $product_id, 'user_id' => $_SESSION['user_id']]);

        if ($deleteCart) {
            header('Location: cart.php');
            exit();
        } else {
            echo "fail";
        }
        exit();
    }

    if (isset($_POST['delete_all'])) {
        $user_id = $_POST['user_id'];

        $deleteAllCart = delete($conn, 'cart', ['user_id' => $user_id]);

        if ($deleteAllCart) {
            header('Location: cart.php');
            exit();
        } else {
            echo "fail";
        }
        exit();
    }
}

if (!isset($_SESSION['user_id'])) {
    header('location:../auth/login.php');
    exit();
}

$products = read($conn, 'cart', ['user_id' => $_SESSION['user_id']]);
$i = 1;
?>

<div class="row d-flex justify-content-center align-items-center h-100 mt-5">
    <div class="col-12">
        <div class="card card-registration card-registration-2" style="border-radius: 15px;">
            <div class="card-body p-0">
                <div class="row g-0">
                    <div class="col-lg-8">
                        <div class="p-5">
                            <div class="d-flex justify-content-between align-items-center mb-5">
                                <h1 class="fw-bold mb-0 text-black">Shopping Cart</h1>
                            </div>
                            <table class="table" height="190">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Image</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Total Price</th>
                                    <th scope="col"><button value="<?= $_SESSION['user_id']; ?>"  class="delete_all btn btn-danger text-white">Clear</button></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($products as $product): ?>
                                    <tr class="mb-4">
                                        <th scope="row"><?= $i; ?></th>
                                        <td><img width="100" height="100" src="../images/<?= $product['product_image']; ?>" class="img-fluid rounded-3" alt="<?= $product['product_name']; ?>"></td>
                                        <td><?= $product['product_name']; ?></td>
                                        <td class="pro_price">$<?= $product['product_price']; ?></td>
                                        <td><input id="form1" min="1" name="quantity" value="<?= $product['product_amount']; ?>" type="number" class="form-control form-control-sm pro_amount" /></td>
                                        <td class="total_price">$<?= $product['product_price'] * $product['product_amount']; ?></td>
                                        <td><button value="<?= $product['product_id']; ?>" class="btn-update btn btn-warning text-white"><i class="fas fa-pen"></i> </button></td>
                                        <td><button value="<?= $product['product_id']; ?>" class="btn-delete btn btn-danger text-white"><i class="fas fa-trash-alt"></i> </button></td>
                                    </tr>
                                    <?php $i++; ?>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                            <a href="../index.php" class="btn btn-success text-white"><i class="fas fa-arrow-left"></i>  Continue Shopping</a>
                        </div>
                    </div>
                    <div class="col-lg-4 bg-grey">
                        <div class="p-5">
                            <h3 class="fw-bold mb-5 mt-2 pt-1">Summary</h3>
                            <hr class="my-4">
                            <div class="d-flex justify-content-between mb-5">
                                <h5 class="text-uppercase">Total price</h5>
                                <h5 class="summary_total_price">$<?= array_sum(array_map(function($product) { return $product['product_price'] * $product['product_amount']; }, $products)); ?></h5>
                            </div>
                            <button type="button" class="btn btn-dark btn-block btn-lg" data-mdb-ripple-color="dark">Checkout</button>
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
<script>
    $(document).ready(function () {
        $(".pro_amount").on('input', function () {
            var $el = $(this).closest('tr');
            var pro_amount = $el.find(".pro_amount").val();
            var pro_price = parseFloat($el.find(".pro_price").text().replace('$', ''));

            var total = pro_amount * pro_price;
            $el.find(".total_price").text('$' + total.toFixed(2));

            updateSummaryTotal();
        });

        $(".btn-update").on('click', function (e) {
            e.preventDefault();
            var $el = $(this).closest('tr');
            var id = $(this).val();
            var pro_amount = $el.find(".pro_amount").val();

            $.ajax({
                type: "POST",
                url: "cart.php",
                data: {
                    update: "update",
                    id: id,
                    product_amount: pro_amount
                },
                success: function (response) {
                    alert("Item updated successfully");
                    window.location.reload();
                },
                error: function () {
                    alert("Failed to update item. Please try again.");
                }
            });
        });

        $(".btn-delete").on('click', function (e) {
            e.preventDefault();
            var id = $(this).val();

            $.ajax({
                type: "POST",
                url: "cart.php",
                data: {
                    delete: "delete",
                    id: id
                },
                success: function (response) {
                    alert("Item deleted successfully");
                    window.location.reload();
                },
                error: function () {
                    alert("Failed to delete item. Please try again.");
                }
            });
        });

        $(".delete_all").on('click', function (e) {
            e.preventDefault();
            var user_id = $(this).val();

            $.ajax({
                type: "POST",
                url: "cart.php",
                data: {
                    delete_all: "delete_all",
                    user_id: user_id
                },
                success: function (response) {
                    alert("All items deleted successfully");
                    window.location.reload();
                },
                error: function () {
                    alert("Failed to delete items. Please try again.");
                }
            });
        });

        function updateSummaryTotal() {
            var summaryTotal = 0;
            $(".total_price").each(function () {
                summaryTotal += parseFloat($(this).text().replace('$', ''));
            });
            $(".summary_total_price").text('$' + summaryTotal.toFixed(2));
        }
    });

</script>
