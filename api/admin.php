<?php
require_once __DIR__ . '/../includes/functions.php';require_once __DIR__ . '/../config/config.php';require_once __DIR__ . '/../config/database.php';require_admin();if($_SERVER['REQUEST_METHOD']!=='POST')redirect('../admin.php');verify_csrf();
$matchId=filter_var($_POST['match_id']??null,FILTER_VALIDATE_INT,['options'=>['min_range'=>1]]);$selection=trim($_POST['selection']??'');$odd=filter_var($_POST['odd']??null,FILTER_VALIDATE_FLOAT,['options'=>['min_range'=>1,'max_range'=>100]]);
if(!$matchId||$selection===''||$odd===false){flash('error','Invalid odds update');redirect('../admin.php');}
try{$pdo=get_pdo();$q=$pdo->prepare('UPDATE odds SET odd=? WHERE match_id=? AND selection=?');$q->execute([$odd,$matchId,$selection]);flash('success','Odds updated');}catch(Throwable $e){flash('error','Could not update odds');}redirect('../admin.php');
