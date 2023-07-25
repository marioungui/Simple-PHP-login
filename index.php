<?php
// Include the header.php that handles the user session and initial HTML data
require_once 'header.php';
?>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">
            <!-- Placeholder logo or your actual logo -->
            My Logo
        </a>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                    <?php if (isAdmin()): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="create-user.php">Add New User</a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<!-- Add the rest of your page content here -->
<div class="container mt-4">
    <h1>Welcome to My Website</h1>
    <?php if (isset($_SESSION['user_id'])): ?>
        <p>Hello, <?php echo $_SESSION['username']; ?>!</p>
    <?php else: ?>
        <p>Please log in to access the content.</p>
    <?php endif; ?>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
