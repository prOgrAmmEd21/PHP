<?php
	error_reporting(E_ALL);
    require_once ("db_connect.php");
	$shop_id;
	$client_id;
	$trans_no;
	$lsp_id;
    $result = array();
    $result['ShopLaundryDetails'] = array();
	
	if ($_SERVER['REQUEST_METHOD']=='POST') 
	{
		if(!isset($_POST['shop_ID']))
		{
			$result['success'] = "0";
			$result['message'] = "error";
			echo json_encode($result);
			exit;
		}
		else
			$shop_id = $_POST['shop_ID'];
		
		if(!isset($_POST['client_ID']))
		{
			$result['success'] = "0";
			$result['message'] = "error";
			echo json_encode($result);
			exit;
		}
		else
			$client_id = $_POST['client_ID'];
		
		if(!isset($_POST['trans_No']))
		{
			$result['success'] = "0";
			$result['message'] = "error";
			echo json_encode($result);
			exit;
		}
		else
			$trans_no = $_POST['trans_No'];
	
		$db_lspID = DB::transact_db("SELECT lsp_ID FROM laundry_service_provider where shop_ID = ?",
								array($shop_id),
								"SELECT");
		if(count($db_lspID) > 0)
		{
            foreach($db_lspID as $db_lspIDs)
				$lsp_id = $db_lspIDs['lsp_ID'];
        }
	
        $db = DB::transact_db("SELECT d.*, i.* FROM laundry_details d, client_inventory i WHERE d.cinv_No = i.cinv_No AND d.client_ID = i.client_ID AND d.client_ID = ? AND i.client_ID = ?",
								array($client_id, $client_id),
								"SELECT"
                            );
	
        if(count($db) > 0)
		{
            foreach($db as $dbs)
			{
				$index['lspID'] = $dbs['lsp_ID'];
				$index['transNo'] = $dbs['trans_No'];
				$index['clientID'] = $dbs['client_ID'];
				$index['cinvNo'] = $dbs['cinv_No'];
				$index['detail_Count'] = $dbs['detail_Count'];
				$index['cinv_ItemTag'] = $dbs['cinv_ItemTag'];
				$index['description'] = $dbs['cinv_ItemBrand']." ".$dbs['cinv_ItemColor']." ".$dbs['cinv_ItemDescription'];
				$index['date'] = $dbs['date'];
				
				array_push($result['ShopLaundryDetails'], $index);
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