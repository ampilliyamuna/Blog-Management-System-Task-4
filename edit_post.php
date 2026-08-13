<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'editor') {
    die("Access Denied! You do not have permission to edit posts.");
}

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$id = $_GET['id'];

// Fetch existing post
$stmt = $conn->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

if (!$post) {
    die("Post not found.");
}

// Update post
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = trim($_POST['title']);
    $content = trim($_POST['content']);

    $update = $conn->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
    $update->bind_param("ssi", $title, $content, $id);

    if ($update->execute()) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Failed to update post.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Post</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<h2>Edit Blog Post</h2>

<form method="POST">

    <label>Title</label><br>
    <input type="text" name="title"
           value="<?php echo htmlspecialchars($post['title']); ?>"
           required><br><br>

    <label>Content</label><br>
    <textarea name="content" rows="6" cols="40" required><?php echo htmlspecialchars($post['content']); ?></textarea><br><br>

    <button type="submit">Update Post</button>

</form>

<br>

<a href="dashboard.php">Back to Dashboard</a>

</body>
</html>