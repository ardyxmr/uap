<?php
include 'config.php';

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Enkripsi password

    // Cek apakah username atau email sudah digunakan
    $check = $conn->query("SELECT * FROM users WHERE username='$username' OR email='$email'");
    
    // Cek apakah query berhasil
    if ($check === false) {
        die("Error: " . $conn->error); // Menampilkan pesan error dari MySQL
    }
    
    if ($check->num_rows > 0) {
        $error = "Username atau email sudah terdaftar!";
    } else {
        // Jika belum ada, simpan data pengguna baru
        $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
        if ($conn->query($sql) === TRUE) {
            $success = "Registrasi berhasil! Silakan <a href='login.php'>login</a>.";
        } else {
            $error = "Terjadi kesalahan. Silakan coba lagi!";
        }
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <h3 class="text-center">Register</h3>
            <?php if (isset($error)) { ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php } elseif (isset($success)) { ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php } ?>
            <form method="post" action="register.php">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" name="register" class="btn btn-primary w-100">Daftar</button>
            </form>
            <!-- Tautan ke halaman login -->
            <div class="text-center mt-3">
                Sudah punya akun? <a href="login.php">Login</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
