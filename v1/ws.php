<?php
require '../libs/Slim/Slim.php';
require 'model/Position.php';
require 'model/Account.php';

include "../includes/db_mysql.inc.php";
//require '../libs/myExtensions/Slim.php';

\Slim\Slim::registerAutoloader();

//$app = new jsonSlim();
$app = new Slim\Slim();

session_start(); 


$app->get('/isLoggedIn', function() {
	if (!(isset($_SESSION['angemeldet']) && $_SESSION['angemeldet'] != '')) {
		$json =json_encode('{"login":false}');
		echo "{$_GET['callback']}($json)";
	}
    else {
		$json =json_encode('{"login":true}');
		// $json =json_encode('{"login":true, "session": '+$_SESSION['angemeldet']+'}');
		echo "{$_GET['callback']}($json)";
    }
});

$app->get('/accounts/:accountId/positions', function ($accountId) {
	// if (!(isset($_SESSION['angemeldet']) && $_SESSION['angemeldet'] != '')) {
		// header('HTTP/1.1 401 Unauthorized', true, 401);
		 // exit;
	// }
	// else {
		$db = connect();
		$return = array();
	
		$acc = $accountId;
			
		if ($stmt = $db->prepare("SELECT move_id, move_title, deposit, liftOff, move_comment  FROM budget_tracker.acc_movements where account_id = ? order by creation_date")) {
			$stmt->bind_param("s", $acc);  
			$stmt->execute();
			
			$res = $stmt->get_result();
	
	        while ($row = $res->fetch_array(MYSQL_ASSOC)) {
					// if(mysql_num_rows($row) > 0) {
						// while ($row = mysql_fetch_array($row, MYSQL_NUM)) {
				    $result = new Position();
					$result->setMoveId($row['move_id']);
					$result->setTitle($row['move_title']);
					$result->setDeposit($row['deposit']);
					$result->setLiftOff($row['liftOff']);
					$result->setComment($row['move_comment']);
					// $return.push($result);
					array_push($return,$result);
			}
			
		}
		
		$db->close();

		header('content-type: application/json; charset=utf-8');
		
		$json = json_encode($return);
		echo isset($_GET['callback'])? "{$_GET['callback']}($json)" : $json;
		// }
});
$app->get('/accounts/:accountId/positions/create', function($accountId) use ($app) {

	$title = $app->request()->get('title');
	$deposit = $app->request()->get('deposit');
	$liftOff = $app->request()->get('liftOff');
	$comment = $app->request()->get('comment');
	$acc = $accountId;
	
	$db = connect();
	
	$stmt = $db->prepare("INSERT INTO acc_movements (move_title, deposit, liftOff, move_comment, account_id ) values(?,?,?,?,?)"); // VALUES(:title, :amount, :comment)");
		
	$stmt->bind_param('sddss', $title, $deposit, $liftOff, $comment,$acc);
	$stmt->execute();
	$db->commit();
	$db->close();
		
});
$app->post('/accounts/positions', function ($data) {
   // create person
});
 
$app->put('/accounts/:accountId/positions/:id', function ($accountId, $id) {
   // update data
});
 
$app->delete('/accounts/:accountId/positions/:id', function ($accountId, $id) {
   // delete data
});
$app->get('/accounts/create', function() use ($app) {
	$accountName = $app->request()->get('accountName');
	$accountId = sha1(microtime(true));
	$isMainOwner = 1;
	
	$user = $_SESSION['angemeldet'];
	
	$db = connect();
	$stmt = $db->prepare("INSERT INTO accounts (account_id, account_name) values(?,?)");
	$stmt->bind_param('ss', $accountId, $accountName);
	$stmt->execute();
	
	$stmt2 = $db->prepare("INSERT INTO user_accounts (user_id, account_id, is_main_owner) values(?,?,?)");
	$stmt2->bind_param('ssi', $user, $accountId, $isMainOwner);
	$stmt2->execute();
	$db->commit();
	$db->close();
	
});
 
$app->get('/accounts', function() use ($app) {
			$user = $_SESSION['angemeldet'];
	
	$db = connect();
	$stmt = $db->prepare("SELECT a.account_id, a.account_name 
							FROM accounts a
						  INNER JOIN user_accounts u
						          ON u.account_id = a.account_id
						         AND u.user_id = ?");
	$stmt->bind_param('s', $user);
	$stmt->execute();
	$res = $stmt->get_result();

	$return = array();
	
    while ($row = $res->fetch_array(MYSQL_ASSOC)) {
    	$account = new Account();
		$account->accountId = $row["account_id"];
		$account->accountName = $row["account_name"];
		
		array_push($return,$account);
	}
	
	$db->close();
	
	$json = json_encode($return);
	echo isset($_GET['callback'])? "{$_GET['callback']}($json)" : $json;
});
$app->run();

?>