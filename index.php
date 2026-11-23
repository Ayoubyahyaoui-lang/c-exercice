<?php
require_once __DIR__ . '/includes/header.php';
$pdo = get_pdo();
$sports = $pdo->query("SELECT * FROM sports ORDER BY id")->fetchAll();
$live = $pdo->query("SELECT m.*,l.name league_name,s.slug sport_slug FROM matches m JOIN leagues l ON l.id=m.league_id JOIN sports s ON s.id=l.sport_id WHERE m.status='live' ORDER BY m.minute DESC LIMIT 6")->fetchAll();
$featured = $pdo->query("SELECT m.*,l.name league_name,s.slug sport_slug FROM matches m JOIN leagues l ON l.id=m.league_id JOIN sports s ON s.id=l.sport_id WHERE m.is_featured=1 AND m.status!='finished' ORDER BY m.start_at LIMIT 6")->fetchAll();
$oddsByMatch = [];
foreach ($featured as $m) {
    $st=$pdo->prepare("SELECT * FROM odds WHERE match_id=? AND market='1X2' ORDER BY id LIMIT 3");$st->execute([$m['id']]);$oddsByMatch[$m['id']]=$st->fetchAll();
}
?>
<section class="hero">
  <div class="hero-copy"><span class="eyebrow"><i class="bi bi-lightning-charge-fill"></i> LIVE EXPERIENCE</span><h1>Choose the moment.<br><em>Own the game.</em></h1><p>Explore live markets, top leagues and your next virtual bet.</p><a class="btn btn-primary" href="sports.php"><i class="bi bi-play-fill"></i>Explore matches</a></div>
  <div class="hero-art"><div class="orb orb-a"></div><div class="orb orb-b"></div><div class="score-card"><span>LIVE · 67'</span><div><b>PSG</b><strong>2</strong></div><div><b>Dortmund</b><strong>1</strong></div></div></div>
</section>

<div class="section-head"><div><span class="eyebrow">TRENDING NOW</span><h2>Featured matches</h2></div><a href="sports.php">View all <i class="bi bi-arrow-right"></i></a></div>
<div class="match-grid">
<?php foreach ($featured as $m): ?>
<div class="match-card">
  <div class="match-meta"><span class="league"><i class="bi bi-circle-fill"></i><?= e($m['league_name']) ?></span><span><?= $m['status']==='live' ? "LIVE · {$m['minute']}'" : date('D · H:i',strtotime($m['start_at'])) ?></span></div>
  <div class="teams"><div><span class="team-crest"><?= strtoupper(substr($m['home_team'],0,1)) ?></span><strong><?= e($m['home_team']) ?></strong></div><div><span class="team-crest away"><?= strtoupper(substr($m['away_team'],0,1)) ?></span><strong><?= e($m['away_team']) ?></strong></div></div>
  <div class="odds-row">
  <?php foreach ($oddsByMatch[$m['id']] as $o): ?><button class="odd-btn" data-match="<?= $m['id'] ?>" data-market="1X2" data-selection="<?= e($o['selection']) ?>" data-label="<?= e($o['label']) ?>" data-odd="<?= $o['odd'] ?>"><span><?= e($o['label']) ?></span><strong><?= number_format((float)$o['odd'],2) ?></strong></button><?php endforeach; ?>
  </div>
</div>
<?php endforeach; ?>
</div>

<div class="content-columns"><section><div class="section-head"><div><span class="eyebrow live-eyebrow">● LIVE</span><h2>In play</h2></div><a href="live.php">Live center</a></div>
<div class="live-list">
<?php foreach ($live as $m): ?><a class="live-row" href="live.php"><div class="live-time"><b><?= e((string)$m['minute']) ?>'</b><span>Live</span></div><div class="live-teams"><strong><?= e($m['home_team']) ?><em><?= $m['home_score'] ?></em></strong><strong><?= e($m['away_team']) ?><em><?= $m['away_score'] ?></em></strong></div><i class="bi bi-chevron-right"></i></a><?php endforeach; ?>
</div></section>
<aside class="promo-card"><span>NEW PLAYER ZONE</span><h3>1,000 virtual<br>credits to start</h3><p>Practice match selections in a risk-free environment.</p><a class="btn btn-light" href="casino.php">Try casino demo</a></aside></div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
