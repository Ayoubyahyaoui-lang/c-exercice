<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/functions.php';

$pageTitle = $pageTitle ?? APP_NAME;
$current = basename($_SERVER['PHP_SELF'], '.php');
?><!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle) ?> — <?= APP_NAME ?></title>
  <meta name="theme-color" content="#080b10">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="app-shell">
  <aside class="sidebar" id="sidebar">
    <a class="brand" href="index.php" aria-label="BETZONE216 home">
      <span class="brand-mark"><i class="bi bi-lightning-charge-fill"></i></span>
      <span>BET<span>ZONE</span><b>216</b></span>
    </a>
    <nav class="side-nav">
      <a class="<?= $current==='index'?'active':'' ?>" href="index.php"><i class="bi bi-grid-fill"></i><span>Highlights</span></a>
      <a class="<?= $current==='sports'?'active':'' ?>" href="sports.php"><i class="bi bi-trophy-fill"></i><span>Sports</span></a>
      <a class="<?= $current==='live'?'active':'' ?>" href="live.php"><span class="live-dot"></span><span>Live Now</span></a>
      <a class="<?= $current==='casino'?'active':'' ?>" href="casino.php"><i class="bi bi-dice-5-fill"></i><span>Casino Demo</span></a>
    </nav>
    <div class="side-section">
      <p>Top sports</p>
      <a href="sports.php?sport=football"><span class="sport-icon">⚽</span>Football</a>
      <a href="sports.php?sport=basketball"><span class="sport-icon">🏀</span>Basketball</a>
      <a href="sports.php?sport=tennis"><span class="sport-icon">🎾</span>Tennis</a>
    </div>
  </aside>

  <div class="main-shell">
    <header class="topbar">
      <button class="icon-btn menu-toggle" id="menuToggle" aria-label="Toggle menu"><i class="bi bi-list"></i></button>
      <div class="quick-search"><i class="bi bi-search"></i><input id="globalSearch" type="search" placeholder="Search teams or leagues" aria-label="Search"></div>
      <?php if (is_logged_in()): ?>
        <a class="wallet-chip" href="account.php"><i class="bi bi-wallet2"></i><span>₡ <?= money((float)$_SESSION['user']['balance']) ?></span></a>
        <a class="profile-btn" href="account.php"><i class="bi bi-person-circle"></i><span><?= e($_SESSION['user']['username']) ?></span></a>
      <?php else: ?>
        <button class="btn btn-ghost" data-auth="login">Log in</button>
        <button class="btn btn-primary" data-auth="register">Create account</button>
      <?php endif; ?>
    </header>

    <main class="content-area">
