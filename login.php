<?php
session_start();
include 'config.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Enkripsi password

    // Query untuk mendapatkan data user dan role berdasarkan username dan password
    $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Jika login berhasil, ambil data user
        $user_data = $result->fetch_assoc();

        // Set session untuk username dan role
        $_SESSION['username'] = $user_data['username'];
        $_SESSION['role'] = $user_data['role']; // Simpan role ke dalam session

        // Redirect ke halaman utama
        header("Location: index.php");
        exit();
    } else {
        // Jika login gagal
        $error = "Username atau password salah!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <h3 class="text-center">Login</h3>
            <?php if (isset($error)) { ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php } ?>
            <form method="post" action="login.php">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
            </form>

            <!-- Tautan Reset Password dan Daftar -->
            <div class="text-center mt-3">
                <a href="reset_password.php">Forgot Password?</a>
                <br>
                Belum punya akun? <a href="register.php">Daftar</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
