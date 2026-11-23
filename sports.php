<?php
require_once __DIR__ . '/includes/header.php';
$sportFilter = preg_replace('/[^a-z-]/','',$_GET['sport'] ?? '');
$sql = "SELECT m.*,l.name league_name,s.name sport_name,s.slug sport_slug FROM matches m JOIN leagues l ON l.id=m.league_id JOIN sports s ON s.id=l.sport_id";
$params=[];
if ($sportFilter) {$sql .= ' WHERE s.slug=?';$params[]=$sportFilter;}
$sql .= ' ORDER BY m.status="live" DESC,m.start_at ASC';
$st=$pdo=get_pdo()->prepare($sql);$st->execute($params);$matches=$st->fetchAll();
$sports=get_pdo()->query('SELECT * FROM sports ORDER BY id')->fetchAll();
?>
<div class="page-head"><div><span class="eyebrow">MARKETS</span><h1>Sports</h1></div></div>
<div class="sport-tabs"><a class="<?= !$sportFilter?'active':'' ?>" href="sports.php">All sports</a><?php foreach($sports as $s): ?><a class="<?= $sportFilter===$s['slug']?'active':'' ?>" href="sports.php?sport=<?= e($s['slug']) ?>"><?= e($s['name']) ?></a><?php endforeach; ?></div>
<div class="market-table">
<div class="market-header"><div>Match</div><div>1</div><div>X</div><div>2</div></div>
<?php foreach($matches as $m):
$os=get_pdo()->prepare("SELECT * FROM odds WHERE match_id=? AND market='1X2' ORDER BY id");$os->execute([$m['id']]);$odds=$os->fetchAll();
?><div class="market-row"><div class="market-match"><span><?= e($m['league_name']) ?> · <?= $m['status']==='live' ? 'LIVE' : date('M j, H:i',strtotime($m['start_at'])) ?></span><strong><?= e($m['home_team']) ?></strong><strong><?= e($m['away_team']) ?></strong></div><div class="market-odds"><?php for($i=0;$i<3;$i++){ if(isset($odds[$i])){ $o=$odds[$i]; ?><button class="odd-btn compact" data-match="<?= $m['id'] ?>" data-market="1X2" data-selection="<?= e($o['selection']) ?>" data-label="<?= e($o['label']) ?>" data-odd="<?= $o['odd'] ?>"><strong><?= number_format((float)$o['odd'],2) ?></strong></button><?php }else{?><span class="no-odd">—</span><?php }} ?></div></div><?php endforeach; ?>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
