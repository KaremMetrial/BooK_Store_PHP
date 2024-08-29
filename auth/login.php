<?php
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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $message = 'Invalid CSRF token.';
    } else {
        // Sanitize and validate input
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'] ?? ''; // Retrieve password from POST data

        // Check for empty fields
        if (empty($email) || empty($password)) {
            $message = 'Please fill out all fields.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = 'Please enter a valid email address.';
        } else {
            try {
                $data = read($conn, 'users', ['email' => $email]);
                if ($data) {
                    // Check if password field exists and verify password
                    if (isset($data[0]['password']) && password_verify($password, $data[0]['password'])) {
                        $_SESSION['user_id'] = $data[0]['id'];
                        $_SESSION['email'] = $data[0]['email'];
                        $_SESSION['username'] = $data[0]['username'];
                        header("Location: ../index.php");
                        exit();
//                        $message = 'Login successful.';
                    } else {
                        $message = "Invalid email or password.";
                    }
                } else {
                    $message = 'The email address you entered does not exist.';
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
                <form action="" method="post" class="form-control mt-5">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
                    <h4 class="text-center mt-3"> Login </h4>
                   
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
                    <button class="w-100 btn btn-lg btn-primary mt-4" name="submit" type="submit">login</button>
                    <!-- Display any messages to the user -->
                    <?php if (!empty($message)): ?>
                        <div class="alert alert-info mt-3"><?php echo htmlspecialchars($message); ?></div>
                    <?php endif; ?>
                </form>
            </div>
        </div>
 
   <?php
require "../includes/footer.php";
