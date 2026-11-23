<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_login();
header('Content-Type: application/json');
verify_csrf();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['error'=>'Method not allowed']); exit; }
$payload=json_decode(file_get_contents('php://input'),true);
$stake=filter_var($payload['stake']??null,FILTER_VALIDATE_FLOAT,['options'=>['min_range'=>1]]);
$selections=$payload['selections']??[];
if($stake===false||!is_array($selections)||count($selections)<1||count($selections)>20){http_response_code(422);echo json_encode(['error'=>'Invalid stake or selections']);exit;}
try{$pdo=get_pdo();$pdo->beginTransaction();$uid=current_user_id();
  $u=$pdo->prepare('SELECT balance,status FROM users WHERE id=? FOR UPDATE');$u->execute([$uid]);$user=$u->fetch();
  if(!$user||$user['status']!=='active'||(float)$user['balance']<(float)$stake)throw new Exception('Insufficient virtual balance');
  $total=1;$verified=[];
  foreach($selections as $selection){
    $matchId=filter_var($selection['match_id']??null,FILTER_VALIDATE_INT,['options'=>['min_range'=>1]]);
    $market=preg_replace('/[^A-Z_]/','',(string)($selection['market']??''));
    $key=preg_replace('/[^a-zA-Z0-9_-]/','',(string)($selection['selection']??''));
    if(!$matchId||strlen($key)<1)throw new Exception('Invalid selection');
    $q=$pdo->prepare('SELECT selection,label,odd FROM odds WHERE match_id=? AND market=? AND selection=? LIMIT 1');$q->execute([$matchId,$market,$key]);$odd=$q->fetch();
    if(!$odd)throw new Exception('Market is no longer available');
    $total*= (float)$odd['odd'];$verified[]=['match_id'=>$matchId,'market'=>$market,'selection'=>$odd['selection'],'label'=>$odd['label'],'odd'=>(float)$odd['odd']];
  }
  $potential=round((float)$stake*$total,2);
  $pdo->prepare('INSERT INTO bets (user_id,stake,total_odds,potential_win,status) VALUES (?,?,?,?,'+"'pending'"+')')->execute([$uid,$stake,$total,$potential]);
  $betId=(int)$pdo->lastInsertId();
  foreach($verified as $v){$pdo->prepare('INSERT INTO bet_selections (bet_id,match_id,market,selection,label,odd) VALUES (?,?,?,?,?,?)')->execute([$betId,$v['match_id'],$v['market'],$v['selection'],$v['label'],$v['odd']]);}
  $pdo->prepare('UPDATE users SET balance=balance-? WHERE id=?')->execute([$stake,$uid]);
  $pdo->prepare("INSERT INTO transactions (user_id,type,amount,reference) VALUES (?,'bet',?,?)")->execute([$uid,$stake,'BET-'.$betId]);
  $pdo->commit();
  $b=$pdo->prepare('SELECT balance FROM users WHERE id=?');$b->execute([$uid]);$_SESSION['user']['balance']=(float)$b->fetchColumn();
  echo json_encode(['success'=>true,'bet_id'=>$betId,'potential_win'=>$potential,'balance'=>$_SESSION['user']['balance']]);
}catch(Throwable $e){if($pdo->inTransaction())$pdo->rollBack();http_response_code(422);echo json_encode(['error'=>$e->getMessage()]);}
