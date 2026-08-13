<?php
include 'config.php';

$result = $conn->query("SELECT * FROM posts ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Blog Management System</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<h1>Blog Management System</h1>

<a href="login.php">Login</a> |
<a href="register.php">Register</a>

<hr>

<?php while($row = $result->fetch_assoc()) { ?>

<h2><?php echo htmlspecialchars($row['title']); ?></h2>

<p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>

<hr>

<?php } ?>

</body>
</html>