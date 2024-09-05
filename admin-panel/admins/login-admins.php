<?php

require "../layouts/header.php";
require "../../config/config.php";
require "../../config/helper.php";



if (isset($_SESSION['admin_id'])) {
    header("Location: ../index.php");
    exit();
}

$message = '';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $message = 'Invalid CSRF token.';
    } else {
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $message = 'Please fill out all fields.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = 'Please enter a valid email address.';
        } else {
            try {
                $data = read($conn, 'admins', ['email' => $email]);
                if ($data) {
                    if (isset($data[0]['password']) && password_verify($password, $data[0]['password'])) {
                        $_SESSION['admin_id'] = $data[0]['id'];
                        $_SESSION['admin_user'] = $data[0]['username'];
                        header("Location:" . ADMIN_URL . "/index.php");
                        exit();
                    } else {
                        $message = "Invalid email or password.";
                    }
                } else {
                    $message = 'The email address you entered does not exist.';
                }
            } catch (PDOException $e) {
                // Log error instead of displaying it
                error_log("Login error: " . $e->getMessage());
                $message = "An error occurred. Please try again later.";
            }
        }
    }
}
?>

<div class="container">
    <div class="row  position-static">
        <div class="col-md-6 position-absolute" style="top: 50%; left: 50%; transform: translate(-50%,-50%); width: 40%">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <h3 class="card-title text-center mb-4">Login</h3>
                    <form method="POST" action="login-admins.php" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                        <div class="form-group mb-3">
                            <label for="form2Example1" class="form-label">Email</label>
                            <input type="email" name="email" id="form2Example1" class="form-control" placeholder="Enter your email" required />
                            <div class="invalid-feedback">
                                Please enter a valid email address.
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <label for="form2Example2" class="form-label">Password</label>
                            <input type="password" name="password" id="form2Example2" placeholder="Enter your password" class="form-control" required />
                            <div class="invalid-feedback">
                                Please enter your password.
                            </div>
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                    <?php if (!empty($message)): ?>
                        <div class="alert alert-info mt-3"><?php echo htmlspecialchars($message); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require "../layouts/footer.php";
?>
