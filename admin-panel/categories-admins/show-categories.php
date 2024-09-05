<?php
require "../layouts/header.php";
require "../../config/config.php";
require "../../config/helper.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location:" . ADMIN_URL . "/admins/login-admins.php");
    exit();
}
$getCategories = read($conn, 'categories');
?>
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4 d-inline">Categories</h5>
                    <a href="create-category.php" class="btn btn-primary mb-4 text-center float-right">Create
                        Categories</a>
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">name</th>
                            <th scope="col">update</th>
                            <th scope="col">delete</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($getCategories) : ?>
                        <?php foreach ($getCategories as $k => $category) : ?>
                            <tr>
                                <th scope="row"><?= htmlspecialchars($k + 1); ?></th>
                                <td><?= ucfirst(htmlspecialchars($category['name']));  ?></td>
                                <td><a href="update-category.php?id=<?= $category['id'] ?>" class="btn btn-warning text-white text-center ">Update </a></td>
                                <td><a href="delete-category.php?id=<?= $category['id'] ?>" class="btn btn-danger  text-center ">Delete </a></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td class="alert alert-danger" role="alert" colspan="4">No categories found</td>
                        </tr>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?php
require "../layouts/footer.php";
