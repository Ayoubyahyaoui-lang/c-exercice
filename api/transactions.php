<?php
require_once __DIR__ . '/../includes/functions.php';require_once __DIR__ . '/../config/config.php';require_once __DIR__ . '/../config/database.php';
require_login();if($_SERVER['REQUEST_METHOD']!=='POST')redirect('../account.php');verify_csrf();
$type=$_POST['type']??'';$amount=filter_var($_POST['amount']??null,FILTER_VALIDATE_FLOAT,['options'=>['min_range'=>1,'max_range'=>100000]]);if($amount===false||!in_array($type,['deposit','withdrawal'],true)){flash('error','Enter a valid demo amount.');redirect('../account.php');}
try{$pdo=get_pdo();$pdo->beginTransaction();$uid=current_user_id();$q=$pdo->prepare('SELECT balance,status FROM users WHERE id=? FOR UPDATE');$q->execute([$uid]);$u=$q->fetch();if(!$u||$u['status']!=='active')throw new Exception('Account unavailable');
if($type==='withdrawal'&&(float)$u['balance']<(float)$amount)throw new Exception('Insufficient virtual balance');
$pdo->prepare("INSERT INTO transactions (user_id,type,amount,reference) VALUES (?, ?, ?, ?)")->execute([$uid,$type,$amount,$type==='deposit'?'DEMO-ADD':'DEMO-WITHDRAW']);
$delta=$type==='deposit'?'+':'-';$pdo->prepare("UPDATE users SET balance=balance $delta ? WHERE id=?")->execute([$amount,$uid]);$pdo->commit();$q=$pdo->prepare('SELECT balance FROM users WHERE id=?');$q->execute([$uid]);$_SESSION['user']['balance']=(float)$q->fetchColumn();flash('success',$type==='deposit'?'Demo credits added.':'Demo withdrawal completed.');
}catch(Throwable $e){if(isset($pdo)&&$pdo->inTransaction())$pdo->rollBack();flash('error',$e->getMessage());}redirect('../account.php');
