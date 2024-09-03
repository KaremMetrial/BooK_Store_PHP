<?php
require "../layouts/header.php";
require "../../config/config.php";
require "../../config/helper.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location:" . ADMIN_URL . "/admins/login-admins.php");
    exit();
}

$getAdmins = read($conn, "admins");

?>
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4 d-inline">Admins</h5>
                <a href="create-admins.php" class="btn btn-primary mb-4 text-center float-right">Create Admins</a>
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Username</th>
                        <th scope="col">Email</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($getAdmins): ?>
                        <?php foreach ($getAdmins as $k => $admin): ?>
                            <tr>
                                <th scope="row"><?= htmlspecialchars($k + 1); ?></th>
                                <td><?= htmlspecialchars($admin['username']); ?></td>
                                <td><?= htmlspecialchars($admin['email']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td class="alert alert-danger" role="alert" colspan="3">No admins found</td>
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
?>
