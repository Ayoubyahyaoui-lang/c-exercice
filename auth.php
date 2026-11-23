<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('index.php');
verify_csrf();
$action = $_POST['action'] ?? '';

try {
    $pdo = get_pdo();
    if ($action === 'register') {
        $username = trim($_POST['username'] ?? '');
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';
        if (!preg_match('/^[a-zA-Z0-9_-]{3,30}$/', $username)) throw new Exception('Username must be 3–30 letters, numbers, underscores or hyphens.');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 190) throw new Exception('Enter a valid email address.');
        if (strlen($password) < 8) throw new Exception('Password must contain at least 8 characters.');
        $stmt = $pdo->prepare('INSERT INTO users (username,email,password_hash,balance) VALUES (?,?,?,1000.00)');
        $stmt->execute([$username,$email,password_hash($password,PASSWORD_DEFAULT)]);
        $id = (int)$pdo->lastInsertId();
        $_SESSION['user'] = ['id'=>$id,'username'=>$username,'email'=>$email,'role'=>'user','balance'=>1000.00];
        flash('success','Welcome to BETZONE216! 1,000 demo credits were added to your wallet.');
        redirect('account.php');
    }

    if ($action === 'login') {
        $email = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email=? LIMIT 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if (!$user || !password_verify($password,$user['password_hash']) || $user['status'] !== 'active') throw new Exception('Invalid email or password.');
        $_SESSION['user'] = ['id'=>(int)$user['id'],'username'=>$user['username'],'email'=>$user['email'],'role'=>$user['role'],'balance'=>(float)$user['balance']];
        $_SESSION['logged_in_at'] = time();
        redirect($user['role']==='admin'?'admin.php':'account.php');
    }

    if ($action === 'logout') { session_destroy(); redirect('index.php'); }
    throw new Exception('Unknown authentication action.');
} catch (Throwable $e) {
    flash('error',$e->getMessage());
    redirect('account.php?auth=' . ($action === 'register' ? 'register' : 'login'));
}
