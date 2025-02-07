<?php
require_once 'database.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$stmt = $pdo->query("SELECT COUNT(*) FROM admins");
$adminCount = $stmt->fetchColumn();

if ($adminCount == 0) {
    $username = 'admin';
    $password = 'admin';
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO admins (username, password_hash) VALUES (?, ?)");
    $stmt->execute([$username, $hashedPassword]);

    echo "Admin créé avec succès. Identifiant : admin / Mot de passe : admin";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password_hash'])) {
        $_SESSION['admin_logged_in'] = true;
        header('Location: index.php');
        exit;
    } else {
        $error = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>

<form method="POST">
    <input type="text" name="username" placeholder="Nom d'utilisateur" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <button type="submit">Se connecter</button>
</form>
<?= isset($error) ? "<p>$error</p>" : "" ?>
