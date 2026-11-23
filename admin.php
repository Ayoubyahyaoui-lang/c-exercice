<?php
require_once __DIR__ . '/includes/header.php';
require_admin();
$pdo=get_pdo();
$stats=['users'=>(int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn(),'matches'=>(int)$pdo->query('SELECT COUNT(*) FROM matches')->fetchColumn(),'bets'=>(int)$pdo->query('SELECT COUNT(*) FROM bets')->fetchColumn(),'balance'=>(float)$pdo->query('SELECT COALESCE(SUM(balance),0) FROM users')->fetchColumn()];
?>
<div class="page-head"><div><span class="eyebrow">CONTROL CENTER</span><h1>Admin dashboard</h1></div></div>
<div class="stats-grid"><article><i class="bi bi-people"></i><span>Users</span><strong><?= $stats['users'] ?></strong></article><article><i class="bi bi-trophy"></i><span>Matches</span><strong><?= $stats['matches'] ?></strong></article><article><i class="bi bi-ticket-perforated"></i><span>Bets</span><strong><?= $stats['bets'] ?></strong></article><article><i class="bi bi-wallet2"></i><span>Demo balance</span><strong>₡ <?= money($stats['balance']) ?></strong></article></div>
<div class="admin-grid"><section><h3>Recent users</h3><?php $users=$pdo->query('SELECT id,username,email,role,balance,status,created_at FROM users ORDER BY id DESC LIMIT 10')->fetchAll(); ?><div class="admin-table"><?php foreach($users as $u):?><div><strong><?= e($u['username']) ?></strong><span><?= e($u['email']) ?></span><b><?= e($u['role']) ?></b><b>₡ <?= money((float)$u['balance']) ?></b></div><?php endforeach; ?></div></section><section><h3>Manage odds</h3><form action="api/admin.php" method="post"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><label>Match ID<input type="number" name="match_id" min="1" required></label><label>Selection<input name="selection" maxlength="50" required></label><label>New odds<input type="number" name="odd" min="1" max="100" step="0.001" required></label><button class="btn btn-primary">Update odds</button></form></section></div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
