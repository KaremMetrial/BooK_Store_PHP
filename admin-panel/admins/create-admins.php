<?php
require "../layouts/header.php";
require "../../config/config.php";
require "../../config/helper.php";

if (!isset($_SESSION['admin_id'])) {
    header("Location:" . ADMIN_URL . "/admins/login-admins.php");
    exit();
}
// Initialize message variable
$message = '';
// Generate and store CSRF token if not already set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

if (isset($_POST['submit'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $message = 'Invalid CSRF token.';
    } else {
        // Sanitize and validate input
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $username = htmlspecialchars($_POST['username']);
        $password = $_POST['password'];
        if (empty($email) || empty($username) || empty($password)) {
            $message = 'Please fill out all fields.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = 'Please enter a valid email address.';
        } elseif (strlen($password) < 6) {
            $message = 'Password must be at least 6 characters long.';
        } else {
            try {
                // Prepare a statement to check if the email already exists
                $getemail = read($conn, 'admins', ['email' => $email]);

                if ($getemail) {
                    $message = 'This email is already registered.';
                } else {
                    // Hash the password for secure storage
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Prepare a statement to insert the new user into the database
                    insert($conn, 'admins', ['username' => $username, 'email' => $email, 'password' => $hashed_password]);
                    header('Location:' . ADMIN_URL . '/admins/admins.php');
                    exit();
                }
            } catch (PDOException $e) {
                $message = "Error: " . htmlspecialchars($e->getMessage());
            }
        }
    }
}

?>

    <form method="POST" action="" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">

        <!-- Email input -->
        <div class="form-group mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" placeholder="Enter email"/>
        </div>

        <!-- Username input -->
        <div class="form-group mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" id="username" class="form-control" placeholder="Enter username"
            />
        </div>

        <!-- Password input -->
        <div class="form-group mb-4">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Enter password"
            />
        </div>

        <!-- Submit button -->
        <div class="">
            <button type="submit" name="submit" class="btn btn-primary">Create</button>
            <!-- Display any messages to the user -->
            <?php if (!empty($message)): ?>
                <div class="alert alert-info mt-3"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
        </div>
    </form>
<?php
require "../layouts/footer.php";