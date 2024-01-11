<?php

/**
 * Service API:  txabbchmgameplay
 * Purpose: Sync the Challenge Mode 'gameplay' data to the ABB backend 
 * File name: txabbchmgameplay.php
 * Author: Suresh Kodoor
 * 
 * JSON Payload:
 * [                       // A JSON array of 'gameplay' JSON objects (this will allow to send multiple 'gameplay' objects in one shot, especially while syncing offline data together)
 * {
 *    "objid":"",          // Identifier for this object/packet (can check the 'response' with this 'objid' to see if this object/packet is valid and successfully received)
 *    "avatarname":"",
 *    "deviceid":"",
 *    "id_game_play":"",
 *    "id_game":"",
 *    "start_time":"",
 * },
 * .
 * .
 * .
 * {
 *    "objid":"",          
 *    "avatarname":"",
 *    "deviceid":"",
 *    "id_game_play":"",
 *    "id_game":"",
 *    "start_time":""
 * }
 * ]
 *    
 * JSON Response:         // A JSON array of Response JSON objects (each response object corresponds to the update status of individual telemetry data object) 
 * [
 * {
 *  "objid":"",
 *  "status":"failed/success",
 *  "description":"reason for failure/success message"
 * },
 * .
 * .
 * .
 *  "objid":"",
 *  "status":"failed/success",
 *  "description":"reason for failure/success message"
 * },
 * 
 * ]
 */
    session_start();
    
    $appbasedirorg = dirname(__FILE__);
    $appbasedir = substr($appbasedirorg,0,-4); // remove the directory name api
    $_SESSION['ABSAPP_BASE_DIR'] = $appbasedir;
 
    $appconfigfile = $appbasedir."/config/appconfig.php";
    $_SESSION['ABSAPP_CONFIG_FILE'] = $appconfigfile;
  
    $dbconfigfile = $appbasedir."/config/dbconfig.php";
    $_SESSION['ABSAPP_DB_CONFIG_FILE'] = $dbconfigfile;
    
  
    $querystr = $_SERVER['QUERY_STRING']; 
    
    $hosturl = "http://".$_SERVER['HTTP_HOST'];
    $requesturi = $_SERVER['REQUEST_URI']; 
    $lenuri = strripos($requesturi,"/",0);  // find the position of last occurance of '/'
    $appurl = substr($requesturi,0,$lenuri-4); // 4 chars removed as the uri will contain 'api' directory also
  
    $appbaseurl = $hosturl.$appurl."/"; 
    $_SESSION['ABSAPP_BASE_URL'] = $appbaseurl;
    
    require_once($_SESSION['ABSAPP_BASE_DIR']."/servicefunctions/servicefunctions.php");
   
    // get posted data
    // $jsonstring = $_GET['json'];
    // Using file_get_contents instead to get the content to a string. 
    // Note: json_decode works only with UTF-8 encoded strings. 
    // So, if not UTF-8 encoded, use the function  $jsonstring = utf8_encode($jsonstring) before calling json_decode
    $jsonstring = file_get_contents("php://input");
    
    $jsondata_array = json_decode($jsonstring); 

    $responsedata_array = array();
    
    if(count($jsondata_array,1) == 0) {
   
        $responsedata = array(
            'objid' => '',
            'status' => "failed",
            'description' => "Received no input JSON data."
        );
        $em = new exceptionMgr(" ");
        $em->logInfo("txabbchmgameplay: Error: Received no input JSON data."); 
    }
    else {
        
        foreach($jsondata_array as $data) {
     
            if($data) {
                $objid          = $data->{'objid'};
                $avatarname     = $data->{'avatarname'};
                $deviceid       = $data->{'deviceid'};
                $id_game_play   = $data->{'id_game_play'};
                $id_game        = $data->{'id_game'};
                $start_time     = $data->{'start_time'};
                $hints          = $data->{'hints'};
    
                $childexists = checkIfNameAndDeviceRegistered($avatarname, $deviceid); 
                
                if(!$childexists) {
                    $responsedata = array(
                        'objid' => $objid,
                        'status'       => "failed",
                        'description'  => "No account exists with the given name and deviceid"
                    );
                    $em = new exceptionMgr(" ");
                    $em->logInfo("txabbchmgameplay: Error: No account exists with the given name and deviceid (".$avatarname.",".$deviceid.")"); 
                }
                else {

                    $childid = getChildIdByNameAndDevice($avatarname,$deviceid);

                    if($childid == null) {
                        $responsedata = array(
                            'objid' => $objid,
                            'status' => "failed",
                            'description' => "Could not retrive the Child ID for the given name and deviceid"
                        );
                        $em = new exceptionMgr(" ");
                        $em->logInfo("txabbchmgameplay: Error: Could not retrive the Child ID for the given name and deviceid (".$avatarname.",".$deviceid.")"); 
                    }
                    else {
               
                        $objGameplay = new gameplay();
               
                        $objGameplay->setGamePlayId($id_game_play);
                        $objGameplay->setChildName($avatarname);
                        $objGameplay->setChildId($childid);
                        $objGameplay->setDeviceId($deviceid);
                        $objGameplay->setGameId($id_game);
                        $objGameplay->setStartTime($start_time);
                        $objGameplay->setHints($hints);
           
                        $rtn = saveCHMGameplay($objGameplay);
    
                        if($rtn) {
                            $responsedata = array(
                                'objid' => $objid,
                                'status' => "success",
                                'description' => ""
                            );
                        }
                        else {
                            $responsedata = array(
                                'objid' => $objid,
                                'status' => "failed",
                                'description' => "Failed to save Gameplay Data."
                            );
                            $em = new exceptionMgr(" ");
                            $em->logInfo("txabbchmgameplay: Error: Failed to save Gameplay Data."); 
                        }
                    }
                }
            }
            else {
        
                $responsedata = array(
                    'objid' => $objid,
                    'status' => "failed",
                    'description' => "Data missing for this JSON object."
                );
                $em = new exceptionMgr(" ");
                $em->logInfo("txabbchmgameplay: Error: Data missing for this JSON object."); 
             }
    
             array_push($responsedata_array,$responsedata);
        }
    }
    
    header('Content-type: application/json');
    echo json_encode($responsedata_array);
    
?>    