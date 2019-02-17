<?php
error_reporting(E_ALL);

    require_once ("db_connect.php");
    $result = array();
    $result['shopPosts'] = array();
        $db = DB::transact_db("SELECT cp.*, CONCAT(c.client_FName, ' ', c.client_MidName, ' ', c.client_LName) AS name, c.client_Address, c.client_Contact from client_post cp, laundry_client c where cp.client_ID = c.client_ID",
								array(),
								"SELECT"
                            );
        if(count($db) > 0) {
            foreach($db as $dbs){
      
            $index['postName'] = $dbs['name'];
            $index['postMeters'] = $dbs['client_Address'];
            $index['postMessage'] = $dbs['post_message'];
            $index['postDate'] = $dbs['post_date'];
            $index['postContact'] = $dbs['client_Contact'];
            
            array_push($result['shopPosts'], $index); }
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


?>