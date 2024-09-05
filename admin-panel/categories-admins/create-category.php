<?php
require "../layouts/header.php";
require "../../config/config.php";
require "../../config/helper.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location: " . ADMIN_URL . "/admins/login-admins.php");
    exit();
}

$message = '';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $message = 'Invalid CSRF token.';
    } else {
        $name = strtolower(trim($_POST['name'] ?? ''));
        $description = trim($_POST['description'] ?? '');
        $image = $_FILES['image'];

        if (empty($name) || empty($description)) {
            $message = 'Please fill out all fields.';
        } elseif ($image['error'] !== UPLOAD_ERR_OK) {
            $message = 'Error uploading image.';
        } else {
            try {
                $existingCategory = read($conn, 'categories', ['name' => $name]);

                if ($existingCategory) {
                    $message = 'This category already exists.';
                } else {
                    $targetDir = "../../categories/image/";
                    $targetFile = $targetDir . $image["name"];
                    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

                    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
                    if (!in_array($imageFileType, $allowedTypes)) {
                        $message = 'Only JPG, JPEG, PNG, and GIF files are allowed.';
                    } else {
                        if (move_uploaded_file($image["tmp_name"], $targetFile)) {
                            $insertSuccess = insert($conn, 'categories', [
                                'name' => $name,
                                'description' => $description,
                                'image' => $image["name"]
                            ]);
                                header('Location: show-categories.php');
                                exit();

                        } else {
                            $message = 'Failed to upload image.';
                        }
                    }
                }
            } catch (PDOException $e) {
                error_log("Database Error: " . $e->getMessage());
                $message = "An unexpected error occurred. Please try again later.";
            }
        }
    }
}
?>

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-5 d-inline">Create Category</h5>
                <form method="POST" action="" enctype="multipart/form-data">
                    <!-- CSRF Token -->
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

                    <!-- Name input -->
                    <div class="form-outline mb-4 mt-4">
                        <input type="text" name="name" id="categoryName" class="form-control"
                               placeholder="Category Name"
                               value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>"
                               required/>
                    </div>

                    <!-- Description input -->
                    <div class="form-outline mb-4">
                        <textarea name="description" id="categoryDescription" class="form-control" placeholder="Category Description" required><?php echo isset($_POST['description']) ? htmlspecialchars($_POST['description']) : ''; ?></textarea>
                    </div>
                    <!-- Image input -->
                    <div class="form-outline mb-4">
                        <input type="file" name="image" id="categoryImage" class="form-control" accept="image/*" required />
                    </div>

                    <!-- Submit button -->
                    <button type="submit" name="submit" class="btn btn-primary mb-4">Create</button>

                    <!-- Display any messages to the user -->
                    <?php if (!empty($message)): ?>
                        <div class="alert alert-info mt-3"><?php echo htmlspecialchars($message); ?></div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
require "../layouts/footer.php";
?>
