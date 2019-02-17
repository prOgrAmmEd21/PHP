<?php
    require_once ("db_connect.php");
	$shopID;
	$id;
    $result['shopRate'] = array();
	
	if ($_SERVER['REQUEST_METHOD']=='POST')
	{
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
				$shopID = $db_lspIDs['lsp_ID'];
        }
	
        $db = DB::transact_db("SELECT CONCAT(c.client_FName, ' ', c.client_MidName, ' ', c.client_LName) AS client_Name, r.*, AVG(rs.rating_Overall) AS avgRate from laundry_client c, rating r, rating_shop rs where c.client_ID = r. client_ID && r.lsp_ID = ?",
								array($shopID),
								"SELECT"
                            );
        if(count($db) > 0) {
            foreach($db as $dbs){
      
            $index['client_Name'] = $dbs['client_Name'];
            $index['rating_No'] = $dbs['rating_No'];
            $index['avgRate'] = $dbs['avgRate'];
            $index['rating_Score'] = $dbs['rating_Score'];
            $index['rating_Comment'] = $dbs['rating_Comment'];
            $index['rating_Date'] = $dbs['rating_Date'];
            
            array_push($result['shopRate'], $index); }
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