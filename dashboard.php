<?php
session_start();
include 'config.php';

/* --------------------------------
   Check Login
--------------------------------- */

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

/* --------------------------------
   Get User Information
--------------------------------- */

$username = $_SESSION['username'] ?? 'User';
$role = $_SESSION['role'] ?? 'user';

/* --------------------------------
   Pagination Settings
--------------------------------- */

$limit = 5;

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $limit;

/* --------------------------------
   Search
--------------------------------- */

$search = "";

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

/* --------------------------------
   Count Total Posts
   Using Prepared Statement
--------------------------------- */

if ($search !== "") {

    $keyword = "%" . $search . "%";

    $total_stmt = $conn->prepare(
        "SELECT COUNT(*) AS total
         FROM posts
         WHERE title LIKE ? OR content LIKE ?"
    );

    $total_stmt->bind_param(
        "ss",
        $keyword,
        $keyword
    );

} else {

    $total_stmt = $conn->prepare(
        "SELECT COUNT(*) AS total
         FROM posts"
    );
}

$total_stmt->execute();

$total_result = $total_stmt->get_result();

$total_row = $total_result->fetch_assoc();

$total_posts = $total_row['total'];

$total_stmt->close();

/* --------------------------------
   Calculate Total Pages
--------------------------------- */

$total_pages = ($total_posts > 0)
    ? ceil($total_posts / $limit)
    : 1;

/* --------------------------------
   Get Posts
   Using Prepared Statements
--------------------------------- */

if ($search !== "") {

    $keyword = "%" . $search . "%";

    $stmt = $conn->prepare(
        "SELECT id, title, content, created_at
         FROM posts
         WHERE title LIKE ? OR content LIKE ?
         ORDER BY id DESC
         LIMIT ?, ?"
    );

    $stmt->bind_param(
        "ssii",
        $keyword,
        $keyword,
        $offset,
        $limit
    );

} else {

    $stmt = $conn->prepare(
        "SELECT id, title, content, created_at
         FROM posts
         ORDER BY id DESC
         LIMIT ?, ?"
    );

    $stmt->bind_param(
        "ii",
        $offset,
        $limit
    );
}

$stmt->execute();

$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html>

<head>

    <title>Blog Management Dashboard</title>

    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">

</head>

<body style="background-color:#f8f9fa;">

<div class="container mt-5">

    <div class="card shadow-lg">

        <!-- Header -->

        <div class="card-header bg-dark text-white
                    d-flex justify-content-between
                    align-items-center">

            <h3 class="mb-0">

                <i class="bi bi-speedometer2"></i>

                Dashboard

            </h3>

            <div>

                <span class="me-3">

                    Welcome,

                    <strong>
                        <?php echo htmlspecialchars($username); ?>
                    </strong>

                    <span class="badge bg-info text-dark ms-2">
                        <?php echo htmlspecialchars($role); ?>
                    </span>

                </span>

                <a
                    href="logout.php"
                    class="btn btn-danger btn-sm">

                    <i class="bi bi-box-arrow-right"></i>

                    Logout

                </a>

            </div>

        </div>


        <!-- Body -->

        <div class="card-body">


            <!-- Add Post -->

            <div class="d-flex justify-content-between mb-3">

                <a
                    href="add_post.php"
                    class="btn btn-success">

                    <i class="bi bi-plus-circle"></i>

                    Add New Post

                </a>

            </div>


            <!-- Search -->

            <form
                method="GET"
                class="row g-2 mb-4">

                <div class="col-md-10">

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Search posts..."
                        value="<?php echo htmlspecialchars($search); ?>">

                </div>

                <div class="col-md-2">

                    <button
                        type="submit"
                        class="btn btn-primary w-100">

                        <i class="bi bi-search"></i>

                        Search

                    </button>

                </div>

            </form>


            <!-- Clear Search -->

            <?php if ($search !== "") { ?>

                <div class="mb-3">

                    <a
                        href="dashboard.php"
                        class="btn btn-secondary btn-sm">

                        <i class="bi bi-x-circle"></i>

                        Clear Search

                    </a>

                </div>

            <?php } ?>


            <!-- Heading -->

            <h4 class="mb-3">

                <?php

                if ($search !== "") {

                    echo "Search Results";

                } else {

                    echo "All Blog Posts";

                }

                ?>

            </h4>


            <!-- Posts Table -->

            <table class="table table-bordered table-hover">

                <thead class="table-primary">

                    <tr>

                        <th>ID</th>

                        <th>Title</th>

                        <th>Content</th>

                        <th>Created At</th>

                        <th width="180">Action</th>

                    </tr>

                </thead>


                <tbody>

                <?php if ($result->num_rows > 0) { ?>

                    <?php while ($row = $result->fetch_assoc()) { ?>

                        <tr>

                            <td>
                                <?php echo (int)$row['id']; ?>
                            </td>


                            <td>
                                <?php
                                echo htmlspecialchars($row['title']);
                                ?>
                            </td>


                            <td>
                                <?php
                                echo htmlspecialchars($row['content']);
                                ?>
                            </td>


                            <td>
                                <?php
                                echo htmlspecialchars($row['created_at']);
                                ?>
                            </td>


                            <td>

                                <?php
                                /*
                                   Only admin and editor
                                   can edit/delete posts
                                */
                                ?>

                                <?php if ($role === 'admin' || $role === 'editor') { ?>

                                    <!-- Edit -->

                                    <a
                                        href="edit_post.php?id=<?php echo (int)$row['id']; ?>"
                                        class="btn btn-warning btn-sm">

                                        <i class="bi bi-pencil-square"></i>

                                        Edit

                                    </a>


                                    <!-- Delete -->

                                    <a
                                        href="delete_post.php?id=<?php echo (int)$row['id']; ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Delete this post?')">

                                        <i class="bi bi-trash"></i>

                                        Delete

                                    </a>

                                <?php } else { ?>

                                    <span class="text-muted">
                                        View Only
                                    </span>

                                <?php } ?>

                            </td>

                        </tr>

                    <?php } ?>

                <?php } else { ?>

                    <tr>

                        <td
                            colspan="5"
                            class="text-center text-muted">

                            No posts found.

                        </td>

                    </tr>

                <?php } ?>

                </tbody>

            </table>


            <!-- Pagination -->

            <?php if ($total_pages > 1) { ?>

                <nav class="mt-4">

                    <ul class="pagination justify-content-center">


                        <!-- Previous -->

                        <?php if ($page > 1) { ?>

                            <li class="page-item">

                                <a
                                    class="page-link"
                                    href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>">

                                    Previous

                                </a>

                            </li>

                        <?php } ?>


                        <!-- Page Numbers -->

                        <?php for ($i = 1; $i <= $total_pages; $i++) { ?>

                            <li
                                class="page-item
                                <?php
                                if ($i == $page) {
                                    echo 'active';
                                }
                                ?>">

                                <a
                                    class="page-link"
                                    href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>">

                                    <?php echo $i; ?>

                                </a>

                            </li>

                        <?php } ?>


                        <!-- Next -->

                        <?php if ($page < $total_pages) { ?>

                            <li class="page-item">

                                <a
                                    class="page-link"
                                    href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>">

                                    Next

                                </a>

                            </li>

                        <?php } ?>

                    </ul>

                </nav>

            <?php } ?>


        </div>

    </div>

</div>


<!-- Footer -->

<footer class="text-center mt-5 mb-3 text-muted">

    © 2026 Blog Management System |
    Developed by Yamu

</footer>


</body>

</html>

<?php

$stmt->close();
$conn->close();

?>