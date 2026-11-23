<?php
require_once __DIR__ . '/includes/header.php';
?>
<div class="page-head"><div><span class="eyebrow">DEMO CASINO</span><h1>Games room</h1></div><span class="demo-badge">Virtual credits only</span></div>
<div class="casino-grid">
<div class="game-card"><div class="game-art purple"><span>♠</span><b>21</b></div><div class="game-info"><div><h3>Neon Blackjack</h3><span>Table game</span></div><button class="btn btn-primary play-demo" data-game="Neon Blackjack">Play demo</button></div></div>
<div class="game-card"><div class="game-art blue"><span>◆</span><b>777</b></div><div class="game-info"><div><h3>Skyline Spins</h3><span>Slots</span></div><button class="btn btn-primary play-demo" data-game="Skyline Spins">Play demo</button></div></div>
<div class="game-card"><div class="game-art green"><span>●</span><b>ZERO</b></div><div class="game-info"><div><h3>Green Roulette</h3><span>Wheel game</span></div><button class="btn btn-primary play-demo" data-game="Green Roulette">Play demo</button></div></div>
</div>
<div class="casino-demo-modal" id="casinoModal"><div class="casino-demo"><button class="modal-close" id="closeCasino"><i class="bi bi-x-lg"></i></button><span class="eyebrow">DEMO</span><h2 id="casinoTitle">Game</h2><div class="demo-machine"><div id="demoResult">777</div></div><button class="btn btn-primary btn-wide" id="spinDemo">Try 1 virtual credit</button></div></div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
