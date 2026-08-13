<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'editor') {
    die("Access Denied! You do not have permission to add posts.");
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    $stmt = $conn->prepare("INSERT INTO posts (title, content) VALUES (?, ?)");
    $stmt->bind_param("ss", $title, $content);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        $message = "Failed to add post!";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Post</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<h2>Add New Blog Post</h2>

<p style="color:red;"><?php echo $message; ?></p>

<form method="POST">

    <label>Title</label><br>
    <input type="text" name="title" required><br><br>

    <label>Content</label><br>
    <textarea name="content" rows="6" cols="40" required></textarea><br><br>

    <button type="submit">Publish</button>

</form>

<br>

<a href="dashboard.php">Back to Dashboard</a>

</body>
</html>