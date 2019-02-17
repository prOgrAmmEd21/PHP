<?php
    require_once ("db_connect.php");
	$shopID;
	$id;
	$comment;
	$rating;
	$rate_no;
    $result['shopUpdateRate'] = array();
	
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
		
		if(!isset($_POST['rating'])) 
		{
			$result['success'] = "0";
			$result['message'] = "error";
			echo json_encode($result);
			exit;
		}
		else 
			$rating = $_POST['rating'];
		
		if(!isset($_POST['comment'])) 
		{
			$result['success'] = "0";
			$result['message'] = "error";
			echo json_encode($result);
			exit;
		}
		else 
			$comment = $_POST['comment'];
		
		if(!isset($_POST['rate_no'])) 
		{
			$result['success'] = "0";
			$result['message'] = "error";
			echo json_encode($result);
			exit;
		}
		else 
			$rate_no = $_POST['rate_no'];
		
		$db_lspID = DB::transact_db("SELECT lsp_ID FROM laundry_service_provider where shop_ID = ?",
								array($id),
								"SELECT");
		if(count($db_lspID) > 0)
		{
            foreach($db_lspID as $db_lspIDs)
				$shopID = $db_lspIDs['lsp_ID'];
        }
	
        $db = DB::transact_db("UPDATE rating_shop SET rating_Score = ?, rating_Comment = ? WHERE rating_No = ? && lsp_ID = ?",
								array($rating, $comment, $rate_no, $shopID),
								"UPDATE"
                            );
			$index['updated'] = "updated";
            array_push($result['shopUpdateRate'], $index); 
            $result['success'] = "1";
            $result['message'] = "success"; 
            echo json_encode($result);
       
	}

?>