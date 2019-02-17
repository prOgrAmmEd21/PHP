<?php
    require_once ("db_connect.php");
	$shopID;
	$id;
    $result['shopNotification'] = array();
	
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
		
		$db = DB::transact_db("SELECT *, CONCAT(lc.client_FName, ' ', lc.client_MidName, ' ', lc.client_LName) as name from notification n, laundry_client lc where n.lsp_ID = ? and n.client_ID = lc.client_ID ORDER BY n.notification_No DESC",
								array($shopID),
								"SELECT"
                            );
		
		$rate_no;
		if(count($db) > 0) {
            foreach($db as $dbs){
      
            $index['client_ID'] = $dbs['client_ID'];
            $index['client_Photo'] = $dbs['client_Photo'];
			$index['client_Name'] = $dbs['name'];
            $index['trans_No'] = $dbs['trans_No'];
            $index['notification_Message'] = $dbs['notification_Message'];
            $index['notification_No'] = $dbs['notification_No'];
			$rate_no = $dbs['rating_NoShop'];
            if($rate_no!=0 && $dbs['notification_Message']=="Finished"){
                $db2 = DB::transact_db("SELECT * from rating_shop where rating_No = ?",
                    array($rate_no),
                    "SELECT"
                );
				if(count($db2)>0){
                    foreach($db2 as $db2s){
						$index['rating_Cust_Service'] = $db2s['rating_Cust_Service'];
						$index['rating_QualityService'] = $db2s['rating_QualityService'];
						$index['rating_Ontime'] = $db2s['rating_Ontime'];
						$index['rating_Overall'] = $db2s['rating_Overall'];
						$index['rating_Comment'] = $db2s['rating_Comment'];
						$index['rating_Date'] = $db2s['rating_Date'];
						$index['rating_No'] = $db2s['rating_No'];
					}
				}
			}
			//$index['rating_NoShop'] = $dbs['rating_NoShop'];
            $index['lsp_ID'] = $dbs['lsp_ID'];
            $index['fromtable'] = "shop";
            
            array_push($result['shopNotification'], $index); }
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