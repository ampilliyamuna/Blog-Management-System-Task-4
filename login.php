<?php
session_start();
include 'config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            header("Location: dashboard.php");
            exit();

        } else {
            $message = "Invalid password!";
        }

    } else {
        $message = "User not found!";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>

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

                <div class="card-header bg-primary text-white text-center rounded-top-4 py-4">

                    <i class="bi bi-person-circle display-1"></i>

                    <h2 class="mt-2">Login</h2>

                </div>

                <div class="card-body p-4">

                    <?php if(!empty($message)) { ?>
                        <div class="alert alert-danger">
                            <?php echo $message; ?>
                        </div>
                    <?php } ?>

                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label">Username</label>

                            <input
                                type="text"
                                name="username"
                                class="form-control form-control-lg"
                                placeholder="Enter Username"
                                required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Password</label>

                            <input
                                type="password"
                                name="password"
                                class="form-control form-control-lg"
                                placeholder="Enter Password"
                                required>
                        </div>

                        <div class="d-grid">
                            <button
                                type="submit"
                                class="btn btn-primary btn-lg rounded-pill">
                                Login
                            </button>
                        </div>

                    </form>

                    <hr>

                    <p class="text-center mb-0">
                        Don't have an account?
                        <a href="register.php" class="fw-bold text-decoration-none">
                            Register
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

</body>
</html>