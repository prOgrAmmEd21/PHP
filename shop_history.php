<?php
    require_once ("db_connect.php");
	$shopID;
	$id;
    $result['shopHistory'] = array();
	
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
		
		$db_lspID = DB::transact_db("SELECT lsp_ID FROM laundry_service_provider where shop_ID = ?",
								array($id),
								"SELECT");
		if(count($db_lspID) > 0)
		{
            foreach($db_lspID as $db_lspIDs)
				$shopID = $db_lspIDs['lsp_ID'];
        }
	
        $db = DB::transact_db("SELECT *, CONCAT(lc.client_FName, ' ', lc.client_MidName, ' ', lc.client_LName) AS name FROM laundry_transaction lt, rating_shop r, laundry_client lc WHERE lt.trans_No = r.trans_No and lc.client_ID = lt.client_ID and lt.lsp_ID = ? AND lt.trans_Status = 'Finished' ORDER BY lt.trans_No DESC",
								array($shopID),
								"SELECT"
                            );
        if(count($db) > 0) {
            foreach($db as $dbs){
      
            $index['rating_No'] = $dbs['rating_No'];
            $index['trans_No'] = $dbs['trans_No'];
            $index['date'] = $dbs['trans_DateOfRequest'];
            $index['weight'] = $dbs['trans_EstWeight']; 
            $index['rating_Score'] = $dbs['rating_Overall']; 
            $index['rating_Comment'] = $dbs['rating_Comment']; 
            $index['rating_Date'] = $dbs['rating_Date'];
            $index['name'] = $dbs['name'];
            
            array_push($result['shopHistory'], $index); }
            $result['success'] = "1";
            $result['message'] = "success"; 
            echo json_encode($result);
        } 
       else {
            $result['success'] = "0";
            $result['message'] = "error";
            echo json_encode($result);
            exit;
       }
	}

?>