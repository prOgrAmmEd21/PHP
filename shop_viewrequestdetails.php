<?php
	error_reporting(E_ALL);
    require_once ("db_connect.php");
	$shop_id;
	$client_id;
	$trans_no;
	$lsp_id;
    $result = array();
    $result['ShopRequestDetails'] = array();
	
	if ($_SERVER['REQUEST_METHOD']=='POST') 
	{		
		if(!isset($_POST['trans_No']))
		{
			$result['success'] = "0";
			$result['message'] = "error";
			echo json_encode($result);
			exit;
		}
		else
			$trans_no = $_POST['trans_No'];
		
        $db = DB::transact_db("SELECT * FROM laundry_transaction lt, laundry_service ls, service s, services_offered so, extra_services es where lt.trans_No = ls.trans_No and so.seroffer_ID = ls.seroffer_ID and s.service_No = ls.service_No and es.extraserv_ID = ls.extraserv_ID and lt.trans_No = ?",
								array($trans_no),
								"SELECT"
                            );
	
        if(count($db) > 0)
		{
            foreach($db as $dbs)
			{
				$index['lspID'] = $dbs['lsp_ID'];
				$index['trans_No'] = $dbs['trans_No'];
				$index['clientID'] = $dbs['client_ID'];
				$index['trans_Service'] = $dbs['service_offered_name'];
				$index['trans_ExtService'] = $dbs['extra_service_name'];
				$index['trans_ServiceType'] = $dbs['service_Type'];
				$index['trans_EstWeight'] = $dbs['trans_EstWeight'];
				$index['trans_EstDateTime'] = $dbs['trans_EstDateTime'];
				
				array_push($result['ShopRequestDetails'], $index);
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