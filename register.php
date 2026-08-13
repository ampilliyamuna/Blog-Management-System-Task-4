<?php
include 'config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    // Username validation
    if (empty($username)) {

        $message = "Username is required!";

    } elseif (strlen($username) < 3 || strlen($username) > 30) {

        $message = "Username must be between 3 and 30 characters!";

    } elseif (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {

        $message = "Username can contain only letters, numbers and underscore!";

    // Password validation
    } elseif (strlen($password) < 8) {

        $message = "Password must contain at least 8 characters!";

    } elseif ($password !== $confirm) {

        $message = "Passwords do not match!";

    } else {

        // Check if username already exists
        $check = $conn->prepare(
            "SELECT id FROM users WHERE username = ?"
        );

        $check->bind_param("s", $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {

            $message = "Username already exists!";

        } else {

            // Secure password hashing
            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            // New users get normal user role
            $role = "user";

            $stmt = $conn->prepare(
                "INSERT INTO users (username, password, role)
                 VALUES (?, ?, ?)"
            );

            $stmt->bind_param(
                "sss",
                $username,
                $hashedPassword,
                $role
            );

            if ($stmt->execute()) {

                header("Location: login.php");
                exit();

            } else {

                $message = "Registration failed!";
            }

            $stmt->close();
        }

        $check->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Your CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body style="background: linear-gradient(to right, #4facfe, #00f2fe);">

<div class="container">
    <div class="row justify-content-center align-items-center vh-100">
        <div class="col-md-5">

            <div class="card shadow-lg border-0 rounded-4">

                <div class="card-header bg-success text-white text-center rounded-top-4 py-4">

                    <i class="bi bi-person-plus-fill display-1"></i>

                    <h2 class="mt-2">User Registration</h2>

                </div>

                <div class="card-body p-4">

                    <?php if(!empty($message)) { ?>
                        <div class="alert alert-danger">
                            <?php echo $message; ?>
                        </div>
                    <?php } ?>

                    <form method="POST" onsubmit="return validateForm()">

                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text"
                                    name="username"
                                    class="form-control form-control-lg"
                                    placeholder="Enter Username"
                                    minlength="3"
                                    maxlength="30"
                                    pattern="[A-Za-z0-9_]+"
                                    required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password"
       name="password"
       id="password"
       class="form-control form-control-lg"
       placeholder="Enter Password"
       minlength="8"
       required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Confirm Password</label>
                            <input type="password"
       name="confirm_password"
       id="confirm_password"
       class="form-control form-control-lg"
       placeholder="Confirm Password"
       minlength="8"
       required>
                        </div>

                        <div class="d-grid">
                            <button type="submit"
                                    class="btn btn-success btn-lg rounded-pill">
                                Register
                            </button>
                        </div>

                    </form>

                    <hr>

                    <p class="text-center mb-0">
                        Already have an account?
                        <a href="login.php" class="fw-bold text-decoration-none">
                            Login
                        </a>
                    </p>

                </div>

            </div>

            <p class="text-center text-white mt-4">
                &copy; 2026 Blog Management System
            </p>

        </div>
    </div>
</div>
<script>
function validateForm() {

    const password = document.getElementById("password").value;
    const confirmPassword =
        document.getElementById("confirm_password").value;

    if (password.length < 8) {
        alert("Password must contain at least 8 characters!");
        return false;
    }

    if (password !== confirmPassword) {
        alert("Passwords do not match!");
        return false;
    }

    return true;
}
</script>
</body>
</html>