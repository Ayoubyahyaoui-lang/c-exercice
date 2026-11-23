<?php
require_once __DIR__ . '/includes/header.php';
$success=flash('success');$error=flash('error');
if(is_logged_in()){
  require_login();
  $pdo=get_pdo();$uid=current_user_id();
  $s=$pdo->prepare('SELECT * FROM users WHERE id=?');$s->execute([$uid]);$user=$s->fetch();$_SESSION['user']['balance']=(float)$user['balance'];
  $b=$pdo->prepare('SELECT * FROM bets WHERE user_id=? ORDER BY placed_at DESC LIMIT 20');$b->execute([$uid]);$bets=$b->fetchAll();
  $t=$pdo->prepare('SELECT * FROM transactions WHERE user_id=? ORDER BY created_at DESC LIMIT 20');$t->execute([$uid]);$transactions=$t->fetchAll();
}else{$auth=$_GET['auth']??'login';}
?>
<?php if($success):?><div class="notice success"><?= e($success) ?></div><?php endif; ?><?php if($error):?><div class="notice error"><?= e($error) ?></div><?php endif; ?>
<?php if(!is_logged_in()): ?><script>document.addEventListener('DOMContentLoaded',()=>document.querySelector('[data-auth="<?= $auth==='register'?'register':'login' ?>"]')?.click());</script><?php endif; ?>
<?php if(is_logged_in()): ?>
<div class="page-head"><div><span class="eyebrow">ACCOUNT</span><h1>My dashboard</h1></div></div>
<div class="account-grid"><section class="wallet-card"><span>Virtual wallet</span><h2>₡ <?= money((float)$user['balance']) ?></h2><div class="wallet-actions"><form action="api/transactions.php" method="post"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="type" value="deposit"><input type="number" name="amount" min="1" max="100000" value="500" required><button class="btn btn-primary">Add demo credits</button></form><form action="api/transactions.php" method="post"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="type" value="withdrawal"><input type="number" name="amount" min="1" max="100000" value="100" required><button class="btn btn-ghost">Demo withdrawal</button></form></div></section><section class="profile-card"><h3>Profile</h3><label>Username<input value="<?= e($user['username']) ?>" disabled></label><label>Email<input value="<?= e($user['email']) ?>" disabled></label><form action="auth.php" method="post"><input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="logout"><button class="btn btn-ghost">Log out</button></form></section></div>
<div class="history-grid"><section><h3>Betting history</h3><div class="history-list"><?php if(!$bets):?><p class="muted">No bets placed yet.</p><?php endif; foreach($bets as $b):?><div class="history-row"><div><b>Bet #<?= $b['id'] ?></b><span><?= date('M j · H:i',strtotime($b['placed_at'])) ?></span></div><div><span>₡ <?= money((float)$b['stake']) ?></span><strong class="status-<?= e($b['status']) ?>"><?= e($b['status']) ?></strong></div></div><?php endforeach; ?></div></section><section><h3>Transactions</h3><div class="history-list"><?php if(!$transactions):?><p class="muted">No transactions yet.</p><?php endif; foreach($transactions as $t):?><div class="history-row"><div><b><?= ucfirst(e($t['type'])) ?></b><span><?= date('M j · H:i',strtotime($t['created_at'])) ?></span></div><strong>₡ <?= money((float)$t['amount']) ?></strong></div><?php endforeach; ?></div></section></div>
<?php endif; ?>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
