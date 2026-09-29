<?php
session_start();
include 'koneksi.php';

$error = '';

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    
    if (!empty($username) && !empty($password)) {
        try {
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                // Pengecekan password langsung (karena di database teks biasa '12345')
                if ($password === $user['password']) {
                    $_SESSION['user'] = $user['username'];
                    $_SESSION['role'] = isset($user['role']) ? $user['role'] : 'admin';

                    // Menggunakan garis miring di depan (/) agar mengarah ke root utama dan tidak menumpuk folder
                    header("Location: /dashboard.php");
                    exit;
                } else {
                    $error = "Password yang Anda masukkan salah!";
                }
            } else {
                $error = "Username tidak ditemukan di database!";
            }
        } catch (PDOException $e) {
            $error = "Terjadi kesalahan sistem: " . $e->getMessage();
        }
    } else {
        $error = "Silakan masukkan username dan password terlebih dahulu!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Peta Jabatan Kementerian ESDM</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: Arial, sans-serif; }
        body { background-color: #f4f6f9; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .login-card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 100%; max-width: 380px; }
        .login-card h2 { text-align: center; color: #0f233f; margin-bottom: 20px; font-size: 20px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; color: #333; font-size: 14px; }
        .form-group input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; font-size: 14px; }
        .btn-login { width: 100%; padding: 10px; background-color: #0f233f; color: white; border: none; border-radius: 4px; font-size: 16px; cursor: pointer; }
        .btn-login:hover { background-color: #1f3b64; }
        .alert { background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 4px; margin-bottom: 15px; font-size: 13px; text-align: center; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>PETA JABATAN ESDM</h2>
        <?php if (!empty($error)): ?>
            <div class="alert"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label>Username / NIP</label>
                <input type="text" name="username" placeholder="Masukkan username..." required autocomplete="off">
            </div>
            <div class="form-group" style="margin-top: 10px;">
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan password..." required>
            </div>
            <button type="submit" name="login" class="btn-login" style="margin-top: 15px;">Masuk</button>
        </form>
    </div>
</body>
</html>
