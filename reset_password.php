<?php
include 'config.php';

if (isset($_POST['reset_password'])) {
    $username = $_POST['username'];
    $new_password = md5($_POST['new_password']); // Enkripsi password

    // Cek apakah username ada di database
    $result = $conn->query("SELECT * FROM users WHERE username='$username'");
    if ($result->num_rows > 0) {
        // Jika username ada, perbarui password
        $sql = "UPDATE users SET password='$new_password' WHERE username='$username'";
        $conn->query($sql);
        $success = "Password berhasil direset. Silakan <a href='login.php'>login</a> dengan password baru Anda.";
    } else {
        $error = "Username tidak ditemukan!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h3 class="text-center">Reset Password</h3>

    <?php if (isset($success)) { ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php } elseif (isset($error)) { ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php } ?>

    <form method="post" action="reset_password.php">
        <div class="mb-3">
            <label for="username" class="form-label">Enter your username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="new_password" class="form-label">New Password</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
        </div>
        <button type="submit" name="reset_password" class="btn btn-primary w-100">Reset Password</button>
    </form>
</div>
</body>
</html>
