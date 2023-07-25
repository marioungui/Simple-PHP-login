<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Login</h2>
        <?php if (isset($_GET['reset'])): ?>
            <?php if ($_GET['reset'] === 'success'): ?>
                <div class="alert alert-success" role="alert">
                    Your password has been reset successfully. You can now log in with your new password.
                </div>
            <?php elseif ($_GET['reset'] === 'invalid'): ?>
                <div class="alert alert-danger" role="alert">
                    The password reset link is invalid or has expired. Please request a new reset link.
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (isset($_GET['password'])): ?>
            <?php if ($_GET['password'] === 'false'): ?>
                <div class="alert alert-danger" role="alert">
                    Your password or user is not valid. Please try again.
                </div>
            <?php endif; ?>
        <?php endif; ?>
        <form action="login-check.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <div class="mt-3">
            <a href="password-forgot.php">Forgot Password?</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>