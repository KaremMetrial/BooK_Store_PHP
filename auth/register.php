<?php
session_start();
require "../config/config.php";
require "../config/helper.php";
require "../includes/header.php";

if (isset($_SESSION['username'])) {
    header("location: ../index.php");
    exit();
}
// Initialize message variable
$message = '';

// Generate and store CSRF token if not already set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// Check if the form has been submitted
if (isset($_POST['submit'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $message = 'Invalid CSRF token.';
    } else {
        // Sanitize and validate input
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $username = htmlspecialchars($_POST['username']);
        $password = $_POST['password'];

        // Check for empty fields
        if (empty($email) || empty($username) || empty($password)) {
            $message = 'Please fill out all fields.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = 'Please enter a valid email address.';
        } elseif (strlen($password) < 6) {
            $message = 'Password must be at least 6 characters long.';
        } else {
            try {
                // Prepare a statement to check if the email already exists
                $getemail = read($conn, 'users', ['email' => $email]);

                if ($getemail) {
                    $message = 'This email is already registered.';
                } else {
                    // Hash the password for secure storage
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    // Prepare a statement to insert the new user into the database
                    insert($conn, 'users', ['username' => $username, 'email' => $email, 'password' => $hashed_password]);
                    header('Location: ./login.php');
                    exit();
                }
            } catch (PDOException $e) {
                $message = "Error: " . htmlspecialchars($e->getMessage());
            }
        }
    }
}

?>


        <div class="row justify-content-center">
            <div class="col-md-6">
                <form method="post" action="" class="form-control mt-5">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                    <h4 class="text-center mt-3"> Register </h4>
                    <div class="">
                        <label for="" class="col-sm-2 col-form-label">Username</label>
                        <div class="">
                            <input type="text" name="username" class="form-control" id="" value="">
                        </div>
                    </div>
                    <div class="">
                        <label for="staticEmail" class="col-sm-2 col-form-label">Email</label>
                        <div class="">
                            <input type="email" name="email"  class="form-control" id="" value="">
                        </div>
                    </div>
                    <div class="">
                        <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
                        <div class="">
                            <input type="password" name="password" class="form-control" id="inputPassword">
                        </div>
                    </div>
                    <button class="w-100 btn btn-lg btn-primary mt-4 my-5" name="submit" type="submit">register</button>
                    <!-- Display any messages to the user -->
                    <?php if (!empty($message)): ?>
                        <div class="alert alert-info mt-3"><?php echo htmlspecialchars($message); ?></div>
                    <?php endif; ?>
                </form>
            </div>
        </div>


<?php
require "../includes/footer.php";
?>