<?php
require_once __DIR__ . '/includes/header.php';
$pdo=get_pdo();
$stmt=$pdo->query("SELECT m.*,l.name league_name FROM matches m JOIN leagues l ON l.id=m.league_id WHERE m.status='live' ORDER BY m.minute DESC");
$matches=$stmt->fetchAll();
?>
<div class="page-head"><div><span class="eyebrow live-eyebrow">● LIVE CENTER</span><h1>Matches in play</h1></div><div class="live-pulse"><i></i> Streaming odds</div></div>
<div class="live-grid">
<?php foreach($matches as $m):
$o=$pdo->prepare("SELECT * FROM odds WHERE match_id=? AND market='1X2' ORDER BY id LIMIT 3");$o->execute([$m['id']]);$odds=$o->fetchAll();
?><article class="live-card"><div class="live-card-head"><span><?= e($m['league_name']) ?></span><b><?= e((string)$m['minute']) ?>'</b></div><div class="live-score"><div><span class="team-crest"><?= strtoupper(substr($m['home_team'],0,1)) ?></span><strong><?= e($m['home_team']) ?></strong></div><div class="score"><b><?= $m['home_score'] ?></b><i>—</i><b><?= $m['away_score'] ?></b></div><div><span class="team-crest away"><?= strtoupper(substr($m['away_team'],0,1)) ?></span><strong><?= e($m['away_team']) ?></strong></div></div><div class="odds-row"><?php foreach($odds as $x): ?><button class="odd-btn" data-match="<?= $m['id'] ?>" data-market="1X2" data-selection="<?= e($x['selection']) ?>" data-label="<?= e($x['label']) ?>" data-odd="<?= $x['odd'] ?>"><span><?= e($x['label']) ?></span><strong><?= number_format((float)$x['odd'],2) ?></strong></button><?php endforeach; ?></div></article><?php endforeach; ?>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
