<?php
	error_reporting(E_ALL);
    require_once ("db_connect.php");
	$id;
	$name;
	$shopID;
	$lspID = array();
    $result = array();
    $result['shopMyLaundry'] = array();
	
	if ($_SERVER['REQUEST_METHOD']=='POST') 
	{
		if(!isset($_POST['shop_id']))
		{
			$result['success'] = "0";
			$result['message'] = "error";
			echo json_encode($result);
			exit;
		}
		else
			$id = $_POST['shop_id'];
		
		if(!isset($_POST['shop_name']))
		{
			$result['success'] = "0";
			$result['message'] = "error";
			echo json_encode($result);
			exit;
		}
		else
			$name = $_POST['shop_name'];
	
		$db_lspID = DB::transact_db("SELECT lsp_ID FROM laundry_service_provider where shop_ID = ?",
								array($id),
								"SELECT");
		if(count($db_lspID) > 0)
		{
            foreach($db_lspID as $db_lspIDs)
				$shopID = $db_lspIDs['lsp_ID'];
        }
	
        $db = DB::transact_db("SELECT CONCAT(c.client_FName, ' ', c.client_MidName, ' ', c.client_LName) AS client_Name, c.client_ID, c.client_Address, c.client_Contact, t.* from laundry_client c, laundry_transaction t where t.client_ID = c.client_ID AND t.trans_Status = 'Confirmed' AND t.lsp_ID = ?",
								array($shopID),
								"SELECT"
                            );
	
        if(count($db) > 0)
		{
            foreach($db as $dbs)
			{
				$index['lspID'] = $dbs['lsp_ID'];
				$index['transNo'] = $dbs['trans_No'];
				$index['transDate'] = $dbs['trans_EstDateTime'];
				$index['clientID'] = $dbs['client_ID'];
				$index['clientName'] = $dbs['client_Name'];
				$index['clientAddress'] = $dbs['client_Address'];
				$index['clientContact'] = $dbs['client_Contact'];
				
				array_push($result['shopMyLaundry'], $index);
			}
			$result['success'] = "1";
			$result['message'] = "success"; 
			echo json_encode($result);
		}
		else 
		{
            $result['success'] = "0";
            $result['message'] = "error";
            echo json_encode($result);
            exit;
		}

	}
?>