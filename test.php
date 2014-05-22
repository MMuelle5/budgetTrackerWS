<?php
require 'v1/model/Position.php';
require 'v1/model/Account.php';

include "includes/db_mysql.inc.php";

session_start();
// $db = new mysqli('localhost', 'root', '', 'budget_tracker');
$db = connect();
if ($db->connect_errno) {
    echo "Failed to connect to MySQL: (" . $db->connect_errno . ") " . $db->connect_error;
}

// $account = "22";
// 
// // if($stmt = $db->prepare("SELECT move_id, move_title, amount, move_comment FROM budget_tracker.acc_movements WHERE account_id = ?")){
	// $stmt = $db->prepare("SELECT move_id, move_title, amount, move_comment  FROM budget_tracker.acc_movements where account_id = ?");
// $stmt->bind_param("s", $account); 
// $stmt->execute();
// 
// $res = $stmt->get_result();
// $arr = array();
	// // $stmt->execute();
        // while ($row = $res->fetch_array(MYSQL_ASSOC)) {
        	// // while ($row = mysql_fetch_array($res, MYSQL_ASSOC)){
  // echo $row["move_title"];
//                 
			    // $result = new Position();
				// $result->setMoveId($row['move_id']);
				// $result->setTitle($row['move_title']);
				// $result->setAmount($row['amount']);
				// $result->setComment($row['move_comment']);
				// // $return.push($result);
				// array_push($arr,$result);
// 
            // print "blubb\n";
  // }
// echo uniqid();
	// echo json_encode($arr);
	// echo "durch";
// 	
	// // $stmt2 = $db->prepare("INSERT INTO acc_movements (move_title, amount, move_comment, account_id ) values(?,?,?,?)"); // VALUES(:title, :amount, :comment)");
// // 		
		// // $title =" blubb";
		// // $amount = 200;
		// // $comment =" kwogli";
		// // $account ="995654";
		// // $stmt2->bind_param('sdss', $title, $amount, $comment,$account);
// // 		
		// // // $stmt2->bind_param(0, $title);
		// // // $stmt2->bind_param(1, $amount);
		// // // $stmt2->bind_param(2, $comment);
		// // // $stmt2->bind_param(3, $account);
		// // // $stmt->bind_param(':title', $title);
		// // // $stmt->bind_param(':amount', $amount);
		// // // $stmt->bind_param(':comment', $comment);
		// // $stmt2->execute();
		// // $db->commit();
// 		
	// echo "<br>";
	// echo sha1(microtime(true));
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
	
	echo json_encode($return);
$db->close();
		echo "durcher";

	echo "total wech";
?>