<?php
	error_reporting(E_ALL);
	require_once ("db_connect.php");
	$clientID;
	$transNo;
	
	if ($_SERVER['REQUEST_METHOD']=='POST')
	{		
	    if(!isset($_POST['clientID'])) 
		{
			$result['success'] = "0";
			$result['message'] = "error";
			echo json_encode($result);
			exit;
		}
		else 
			$clientID = $_POST['clientID'];
		
	    if(!isset($_POST['transNo'])) 
		{
			$result['success'] = "0";
			$result['message'] = "error";
			echo json_encode($result);
			exit;
		}
		else 
			$transNo = $_POST['transNo'];
		
	    if(!isset($_POST['shopID'])) 
		{
			$result['success'] = "0";
			$result['message'] = "error";
			echo json_encode($result);
			exit;
		}
		else 
			$id = $_POST['shopID'];
		
		$db_lspID = DB::transact_db("SELECT lsp_ID FROM laundry_service_provider where shop_ID = ?",
								array($id),
								"SELECT");
		if(count($db_lspID) > 0)
		{
            foreach($db_lspID as $db_lspIDs)
				$lspID = $db_lspIDs['lsp_ID'];
        }
		
			$updateDB = DB::transact_db("UPDATE laundry_transaction SET trans_Status = 'Finished' WHERE trans_No = ? AND client_ID = ?;",
							array($transNo, $clientID),
							"UPDATE"
                           );
			$result['success'] = "1";
			$result['message'] = "success"; 
			echo json_encode($result);
			
			
		$insertDB = DB::transact_db("INSERT INTO notification (notification_No, client_ID, lsp_ID, trans_No, rating_No, rating_Nohw, rating_NoShop, notification_Message) VALUES (NULL, ?, ?, ?, NULL, NULL, NULL, 'Finished');",
							array($clientID, $lspID, $transNo),
							"UPDATE"
                           );
		$insertDB = DB::transact_db("INSERT INTO history (history_No, client_ID, lsp_ID, trans_No, rating_No) VALUES (NULL, ?, ?, ?, NULL);",
							array($clientID, $lspID, $transNo),
							"INSERT"
                           );
	}
?>