<?php
require "../layouts/header.php";
require "../../config/config.php";
require "../../config/helper.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location:" . ADMIN_URL . "/admins/login-admins.php");
    exit();
}

$categoryId = $_GET['id'] ?? null;
if (!$categoryId) {
    header("Location:" . ADMIN_URL . "/categories/index.php");
    exit();
}

$message = '';
$getCategory = read($conn, 'categories', ['id' => $categoryId]);
if (!$getCategory) {
    header("Location: 404.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $name = trim(htmlspecialchars($_POST['name'] ?? ''));

    if (!empty($name)) {
        $updateCategory = update($conn, 'categories', ['name' => $name], ['id' => $categoryId]);

        if ($updateCategory) {
            header("Location: show-categories.php");
        } else {
            $message = "Error updating category";
        }
    } else {
        $message = "Please provide a valid name";
    }
}

?>
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-5 d-inline">Update Category</h5>
                <form method="POST" action="" enctype="multipart/form-data">
                    <!-- Name input -->
                    <div class="form-outline mb-4 mt-4">
                        <input type="text" name="name" id="form2Example1" class="form-control" placeholder="Category name"
                               value="<?= htmlspecialchars($getCategory[0]['name']) ?>" />
                    </div>

                    <!-- Submit button -->
                    <button type="submit" name="submit" class="btn btn-primary mb-4 text-center">Update</button>
                    <?php if (!empty($message)): ?>
                        <div class="alert alert-danger mt-3"><?php echo htmlspecialchars($message); ?></div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
require "../layouts/footer.php";
?>
