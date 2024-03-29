<?php

class servicefunctions_dao {

    private $dbh; // dbhandler object
    
    function __construct() {
        
        $this->dbh = services_dbhandler::getInstance();         
    }
    
    
    
	
	function checkIfNameAndDeviceRegistered($childname, $deviceid) {

	    $additional_condition = " and  deviceid = '$deviceid'";
	    $arrResult = $this->dbh->readRecords('child_tbl','id_child','child_name',$childname, $additional_condition);

	    if(count($arrResult,1) == 0) return false;
	    else return true;
	}
      
	
    
    function getChildByNameAndDevice($childname, $deviceid) {
        
        $query = "SELECT C.*, G.description AS gradedescr, L.description AS langdescr
                 FROM child_tbl C
                 JOIN grade_tbl G ON C.id_grade = G.id_grade
                 JOIN language_tbl L ON C.id_language = L.id_language
                 WHERE C.child_name = '$childname' AND C.deviceid = '$deviceid'";
      
        $arrResult = $this->dbh->readRecordsWithQuery($query);
        
        
        if(count($arrResult,1) == 0) return false;
        else {
            $objChild = $this->createChildObject($arrResult[0]);
            return $objChild;
        }
    }


    private function createChildObject($arrData) {
          
        $objChild = new child();
          
        $objChild->setChildId($arrData['id_child']);
        $objChild->setChildName(stripslashes($arrData['child_name']));
        $objChild->setDeviceId($arrData['deviceid']);
        $objChild->setGradeId($arrData['id_grade']);
        $objChild->setGradeName($arrData['gradedescr']);
        $objChild->setSchoolTypeId($arrData['school_type']);
        $objChild->setGeo($arrData['geo']);
        $objChild->setOrg($arrData['organization']);
        $objChild->setLanguageId($arrData['id_language']);
        $objChild->setLanguageName($arrData['langdescr']);
        $objChild->setPicFileName($arrData['avatar_pic']);
        
        return $objChild;
    }
      
    //to get user progress details
    function getChildUserProgress($childid) {
        
        //PNS -> Practice mode Number Sense
        //PNO -> Practice mode Number Operation
        //PM -> Practice mode Measurement
        //PSG -> Practice mode Shape Games
        //PNSN -> Practice mode Number sense numbers
        //PNSF -> Practice mode Number sense fractions
        //PNSS -> Practice mode Number sense sequence
        //PNSC -> Practice mode Number sense comparison
        //PNSPV -> Practice mode Number sense place value
        //PML -> Practice mode Measurement Length
        //PMW -> Practice mode Measurement weight
        //PMT -> Practice mode Measurement time
        //PMV -> Practice mode Measurement volume
        //PNOA -> Practice mode Measurement Addition
        //PNOS -> Practice mode Measurement Subtraction
        //PNOM -> Practice mode Measurement Multiplication
        //PNOD -> Practice mode Measurement Divison

        //Practice mode query.
        $query = "SELECT ( SELECT COUNT(DISTINCT(id_game)) FROM game_play_tbl WHERE EXISTS (SELECT 1 FROM game_play_detail_tbl WHERE game_play_detail_tbl.id_game_play = game_play_tbl.id_game_play) AND id_game like 'NS%' AND id_child = '$childid') AS PNS, 
                ( SELECT COUNT(DISTINCT(id_game)) FROM game_play_tbl WHERE EXISTS (SELECT 1 FROM game_play_detail_tbl WHERE game_play_detail_tbl.id_game_play = game_play_tbl.id_game_play) AND id_game like 'AL%' AND id_child = '$childid') AS PALG, 
                ( SELECT COUNT(DISTINCT(id_game)) FROM game_play_tbl WHERE EXISTS (SELECT 1 FROM game_play_detail_tbl WHERE game_play_detail_tbl.id_game_play = game_play_tbl.id_game_play) AND (id_game like 'GM%') AND id_child = '$childid') AS PGM,
                ( SELECT COUNT(DISTINCT(id_game)) FROM game_play_tbl WHERE EXISTS (SELECT 1 FROM game_play_detail_tbl WHERE game_play_detail_tbl.id_game_play = game_play_tbl.id_game_play) AND id_game like 'NSN%' AND id_child = '$childid') AS PNSN,
                ( SELECT COUNT(DISTINCT(id_game)) FROM game_play_tbl WHERE EXISTS (SELECT 1 FROM game_play_detail_tbl WHERE game_play_detail_tbl.id_game_play = game_play_tbl.id_game_play) AND (id_game like 'NS_IN%') AND id_child = '$childid') AS PNSI,
                ( SELECT COUNT(DISTINCT(id_game)) FROM game_play_tbl WHERE EXISTS (SELECT 1 FROM game_play_detail_tbl WHERE game_play_detail_tbl.id_game_play = game_play_tbl.id_game_play) AND (id_game like 'NSF%') AND id_child = '$childid') AS PNSF,
                ( SELECT COUNT(DISTINCT(id_game)) FROM game_play_tbl WHERE EXISTS (SELECT 1 FROM game_play_detail_tbl WHERE game_play_detail_tbl.id_game_play = game_play_tbl.id_game_play) AND id_game like 'NSD%' AND id_child = '$childid') AS PNSD,
                ( SELECT COUNT(DISTINCT(id_game)) FROM game_play_tbl WHERE EXISTS (SELECT 1 FROM game_play_detail_tbl WHERE game_play_detail_tbl.id_game_play = game_play_tbl.id_game_play) AND id_game like 'NSR%' AND id_child = '$childid') AS PNSR,
                ( SELECT COUNT(DISTINCT(id_game)) FROM game_play_tbl WHERE EXISTS (SELECT 1 FROM game_play_detail_tbl WHERE game_play_detail_tbl.id_game_play = game_play_tbl.id_game_play) AND id_game like 'ALGV%' AND id_child = '$childid') AS PALGV,
                ( SELECT COUNT(DISTINCT(id_game)) FROM game_play_tbl WHERE EXISTS (SELECT 1 FROM game_play_detail_tbl WHERE game_play_detail_tbl.id_game_play = game_play_tbl.id_game_play) AND id_game like 'GM%' AND id_child = '$childid') AS PGMS,
                ( SELECT COUNT(DISTINCT(id_game)) FROM game_play_tbl WHERE EXISTS (SELECT 1 FROM game_play_detail_tbl WHERE game_play_detail_tbl.id_game_play = game_play_tbl.id_game_play) AND (id_game like 'GMPR%' OR id_game like 'GMM%') AND id_child = '$childid') AS PGMM,";
                // ( SELECT COUNT(DISTINCT(id_game)) FROM game_play_tbl WHERE EXISTS (SELECT 1 FROM game_play_detail_tbl WHERE game_play_detail_tbl.id_game_play = game_play_tbl.id_game_play) AND id_game like 'MW%' AND id_child = '$childid') AS PMW,
                // ( SELECT COUNT(DISTINCT(id_game)) FROM game_play_tbl WHERE EXISTS (SELECT 1 FROM game_play_detail_tbl WHERE game_play_detail_tbl.id_game_play = game_play_tbl.id_game_play) AND id_game like 'MV%' AND id_child = '$childid') AS PMV,";

                // -- ( SELECT COUNT(DISTINCT(id_game)) FROM game_play_tbl WHERE EXISTS (SELECT 1 FROM game_play_detail_tbl WHERE game_play_detail_tbl.id_game_play = game_play_tbl.id_game_play) AND id_game like 'MT%' AND id_child = '$childid') AS PMT,
                // -- ( SELECT COUNT(DISTINCT(id_game)) FROM game_play_tbl WHERE EXISTS (SELECT 1 FROM game_play_detail_tbl WHERE game_play_detail_tbl.id_game_play = game_play_tbl.id_game_play) AND id_game like 'NOA%' AND id_child = '$childid') AS PNOA,
                // -- ( SELECT COUNT(DISTINCT(id_game)) FROM game_play_tbl WHERE EXISTS (SELECT 1 FROM game_play_detail_tbl WHERE game_play_detail_tbl.id_game_play = game_play_tbl.id_game_play) AND id_game like 'NOS%' AND id_child = '$childid') AS PNOS,
                // -- ( SELECT COUNT(DISTINCT(id_game)) FROM game_play_tbl WHERE EXISTS (SELECT 1 FROM game_play_detail_tbl WHERE game_play_detail_tbl.id_game_play = game_play_tbl.id_game_play) AND id_game like 'NOM%' AND id_child = '$childid') AS PNOM,
                // -- ( SELECT COUNT(DISTINCT(id_game)) FROM game_play_tbl WHERE EXISTS (SELECT 1 FROM game_play_detail_tbl WHERE game_play_detail_tbl.id_game_play = game_play_tbl.id_game_play) AND id_game like 'NOD%' AND id_child = '$childid') AS PNOD,
                // -- ( SELECT COUNT(DISTINCT(id_game)) FROM game_play_tbl WHERE EXISTS (SELECT 1 FROM game_play_detail_tbl WHERE game_play_detail_tbl.id_game_play = game_play_tbl.id_game_play) AND id_game like 'NOLD%' AND id_child = '$childid') AS PNOLD,
                // -- ( SELECT COUNT(DISTINCT(id_game)) FROM game_play_tbl WHERE EXISTS (SELECT 1 FROM game_play_detail_tbl WHERE game_play_detail_tbl.id_game_play = game_play_tbl.id_game_play) AND id_game like 'NOLM%' AND id_child = '$childid') AS PNOLM,";

        //challenge mode query
        $query .= "( SELECT SUM(time2answer) FROM `game_play_detail_tbl` WHERE id_child = '$childid') AS PMST,
                ( SELECT SUM(time2answer) FROM `chm_game_play_detail_tbl` WHERE id_child = '$childid') AS CMST,
                ( SELECT SUM(time2answer) FROM `game_play_detail_tbl` WHERE id_child = '$childid' AND (id_question like 'NSN%' OR id_question like 'NS%' OR id_question like 'NSD%' OR id_question like 'NSF%' OR id_question like 'NSRP%')) AS PMNST,
                ( SELECT SUM(time2answer) FROM `chm_game_play_detail_tbl` WHERE id_child = '$childid' AND id_question like 'NS%' ) AS CMNSST,
                ( SELECT SUM(time2answer) FROM `game_play_detail_tbl` WHERE id_child = '$childid' AND (id_question like 'GMS%' OR id_question like 'GMR%' OR id_question like 'GMCR%' OR id_question like 'GMPAR%' OR id_question like 'GMAN%' OR id_question like 'GMLA%')) AS PMGMT,
                ( SELECT SUM(time2answer) FROM `chm_game_play_detail_tbl` WHERE id_child = '$childid' AND id_question like 'NO%' ) AS CMNOST,
                ( SELECT SUM(time2answer) FROM `game_play_detail_tbl` WHERE id_child = '$childid' AND id_question like 'AL%') AS PALGT,
                ( SELECT SUM(time2answer) FROM `chm_game_play_detail_tbl` WHERE id_child = '$childid' AND (id_question like 'M%' OR id_question like 'SSR%')) AS CMMST,
                ( SELECT COUNT(DISTINCT(id_game)) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'NS%' AND id_child = '$childid') AS CNS, 
                ( SELECT COUNT(DISTINCT(id_game)) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'NO%' AND id_child = '$childid') AS CNO, 
                ( SELECT COUNT(DISTINCT(id_game)) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND (id_game like 'M%' OR id_game like 'SSR%') AND id_child = '$childid') AS CM,
                ( SELECT COUNT(DISTINCT(id_game)) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND (id_game like 'NSF%' OR id_game like 'NSD%') AND id_child = '$childid') AS CNSF,
                ( SELECT COUNT(DISTINCT(id_game)) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'NSS%' AND id_child = '$childid') AS CNSS,
                ( SELECT COUNT(DISTINCT(id_game)) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'NSC%' AND id_child = '$childid') AS CNSC,
                ( SELECT COUNT(DISTINCT(id_game)) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'NSPV%' AND id_child = '$childid') AS CNSPV,
                ( SELECT COUNT(DISTINCT(id_game)) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'NSN%' AND id_child = '$childid') AS CNSN,
                ( SELECT COUNT(DISTINCT(id_game)) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND (id_game like 'ML%' OR id_game like 'SSR%') AND id_child = '$childid') AS CML,
                ( SELECT COUNT(DISTINCT(id_game)) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'MW%' AND id_child = '$childid') AS CMW,
                ( SELECT COUNT(DISTINCT(id_game)) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'MV%' AND id_child = '$childid') AS CMV,
                ( SELECT COUNT(DISTINCT(id_game)) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'MT%' AND id_child = '$childid') AS CMTi,
                ( SELECT COUNT(DISTINCT(id_game)) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'NOA%' AND id_child = '$childid') AS CNOA,
                ( SELECT COUNT(DISTINCT(id_game)) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'NOS%' AND id_child = '$childid') AS CNOS,
                ( SELECT COUNT(DISTINCT(id_game)) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'NOM%' AND id_child = '$childid') AS CNOM,
                ( SELECT COUNT(DISTINCT(id_game)) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'NOD%' AND id_child = '$childid') AS CNOD,";

        //challenge mode Total pass count
        $query .= "( SELECT COUNT(*) FROM chm_game_play_detail_tbl WHERE id_question like 'NS%' AND pass like 'yes' AND id_child = '$childid') AS CNSP, 
                ( SELECT COUNT(*) FROM chm_game_play_detail_tbl WHERE id_question like 'NO%' AND pass like 'yes' AND id_child = '$childid') AS CNOP, 
                ( SELECT COUNT(*) FROM chm_game_play_detail_tbl WHERE (id_question like 'M%' OR id_question like 'SSR%') AND pass like 'yes' AND id_child = '$childid') AS CMP,
                ( SELECT COUNT(*) FROM chm_game_play_detail_tbl WHERE (id_question like 'NSF%' OR id_question like 'NSD%') AND pass like 'yes' AND id_child = '$childid') AS CNSFP,
                ( SELECT COUNT(*) FROM chm_game_play_detail_tbl WHERE id_question like 'NSS%' AND pass like 'yes' AND id_child = '$childid') AS CNSSP,
                ( SELECT COUNT(*) FROM chm_game_play_detail_tbl WHERE id_question like 'NSC%' AND pass like 'yes' AND id_child = '$childid') AS CNSCP,
                ( SELECT COUNT(*) FROM chm_game_play_detail_tbl WHERE id_question like 'NSPV%' AND pass like 'yes' AND id_child = '$childid') AS CNSPVP,
                ( SELECT COUNT(*) FROM chm_game_play_detail_tbl WHERE id_question like 'NSN%' AND pass like 'yes' AND id_child = '$childid') AS CNSNP,
                ( SELECT COUNT(*) FROM chm_game_play_detail_tbl WHERE (id_question like 'ML%' OR id_question like 'SSR%') AND pass like 'yes' AND id_child = '$childid') AS CMLP,
                ( SELECT COUNT(*) FROM chm_game_play_detail_tbl WHERE id_question like 'MW%' AND pass like 'yes' AND id_child = '$childid') AS CMWP,
                ( SELECT COUNT(*) FROM chm_game_play_detail_tbl WHERE id_question like 'MV%' AND pass like 'yes' AND id_child = '$childid') AS CMVP,
                ( SELECT COUNT(*) FROM chm_game_play_detail_tbl WHERE id_question like 'MT%' AND pass like 'yes' AND id_child = '$childid') AS CMTP,
                ( SELECT COUNT(*) FROM chm_game_play_detail_tbl WHERE id_question like 'NOA%' AND pass like 'yes' AND id_child = '$childid') AS CNOAP,
                ( SELECT COUNT(*) FROM chm_game_play_detail_tbl WHERE id_question like 'NOS%' AND pass like 'yes' AND id_child = '$childid') AS CNOSP,
                ( SELECT COUNT(*) FROM chm_game_play_detail_tbl WHERE id_question like 'NOM%' AND pass like 'yes' AND id_child = '$childid') AS CNOMP,
                ( SELECT COUNT(*) FROM chm_game_play_detail_tbl WHERE id_question like 'NOD%' AND pass like 'yes' AND id_child = '$childid') AS CNODP,";

        //challenge mode Total fail count
        $query .= "( SELECT COUNT(*) FROM chm_game_play_detail_tbl WHERE id_question like 'NS%' AND pass like 'no' AND id_child = '$childid') AS CNSFF, 
                ( SELECT COUNT(*) FROM chm_game_play_detail_tbl WHERE id_question like 'NO%' AND pass like 'no' AND id_child = '$childid') AS CNOF, 
                ( SELECT COUNT(*) FROM chm_game_play_detail_tbl WHERE (id_question like 'M%' OR id_question like 'SSR%') AND pass like 'no' AND id_child = '$childid') AS CMF,
                ( SELECT COUNT(*) FROM chm_game_play_detail_tbl WHERE (id_question like 'NSF%' OR id_question like 'NSD%') AND pass like 'no' AND id_child = '$childid') AS CNSFFF,
                ( SELECT COUNT(*) FROM chm_game_play_detail_tbl WHERE id_question like 'NSS%' AND pass like 'no' AND id_child = '$childid') AS CNSSF,
                ( SELECT COUNT(*) FROM chm_game_play_detail_tbl WHERE id_question like 'NSC%' AND pass like 'no' AND id_child = '$childid') AS CNSCF,
                ( SELECT COUNT(*) FROM chm_game_play_detail_tbl WHERE id_question like 'NSPV%' AND pass like 'no' AND id_child = '$childid') AS CNSPVF,
                ( SELECT COUNT(*) FROM chm_game_play_detail_tbl WHERE id_question like 'NSN%' AND pass like 'no' AND id_child = '$childid') AS CNSNF,
                ( SELECT COUNT(*) FROM chm_game_play_detail_tbl WHERE (id_question like 'ML%' OR id_question like 'SSR%') AND pass like 'no' AND id_child = '$childid') AS CMLF,
                ( SELECT COUNT(*) FROM chm_game_play_detail_tbl WHERE id_question like 'MW%' AND pass like 'no' AND id_child = '$childid') AS CMWF,
                ( SELECT COUNT(*) FROM chm_game_play_detail_tbl WHERE id_question like 'MV%' AND pass like 'no' AND id_child = '$childid') AS CMVF,
                ( SELECT COUNT(*) FROM chm_game_play_detail_tbl WHERE id_question like 'MT%' AND pass like 'no' AND id_child = '$childid') AS CMTF,
                ( SELECT COUNT(*) FROM chm_game_play_detail_tbl WHERE id_question like 'NOA%' AND pass like 'no' AND id_child = '$childid') AS CNOAF,
                ( SELECT COUNT(*) FROM chm_game_play_detail_tbl WHERE id_question like 'NOS%' AND pass like 'no' AND id_child = '$childid') AS CNOSF,
                ( SELECT COUNT(*) FROM chm_game_play_detail_tbl WHERE id_question like 'NOM%' AND pass like 'no' AND id_child = '$childid') AS CNOMF,
                ( SELECT COUNT(*) FROM chm_game_play_detail_tbl WHERE id_question like 'NOD%' AND pass like 'no' AND id_child = '$childid') AS CNODF,";

        //challenge mode Total Hints
        $query .= "( SELECT COUNT(*) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'NS%' AND id_child = '$childid') AS CNST, 
                ( SELECT COUNT(*) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'NO%' AND id_child = '$childid') AS CNOT, 
                ( SELECT COUNT(*) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND (id_game like 'M%' OR id_game like 'SSR%') AND id_child = '$childid') AS CMT,
                ( SELECT COUNT(*) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND (id_game like 'NSF%' OR id_game like 'NSD%') AND id_child = '$childid') AS CNSFT,
                ( SELECT COUNT(*) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'NSS%' AND id_child = '$childid') AS CNSST,
                ( SELECT COUNT(*) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'NSC%' AND id_child = '$childid') AS CNSCT,
                ( SELECT COUNT(*) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'NSPV%' AND id_child = '$childid') AS CNSPVT,
                ( SELECT COUNT(*) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'NSN%' AND id_child = '$childid') AS CNSNT,
                ( SELECT COUNT(*) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND (id_game like 'ML%' OR id_game like 'SSR%') AND id_child = '$childid') AS CMLT,
                ( SELECT COUNT(*) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'MW%' AND id_child = '$childid') AS CMWT,
                ( SELECT COUNT(*) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'MV%' AND id_child = '$childid') AS CMVT,
                ( SELECT COUNT(*) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'MT%' AND id_child = '$childid') AS CMTT,
                ( SELECT COUNT(*) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'NOA%' AND id_child = '$childid') AS CNOAT,
                ( SELECT COUNT(*) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'NOS%' AND id_child = '$childid') AS CNOST,
                ( SELECT COUNT(*) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'NOM%' AND id_child = '$childid') AS CNOMT,
                ( SELECT COUNT(*) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'NOD%' AND id_child = '$childid') AS CNODT,";

        //challenge mode Total Hints
        $query .= "( SELECT SUM(`hints`) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'NS%' AND id_child = '$childid') AS CNSH, 
                ( SELECT SUM(`hints`) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'NO%' AND id_child = '$childid') AS CNOH, 
                ( SELECT SUM(`hints`) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND (id_game like 'M%' OR id_game like 'SSR%') AND id_child = '$childid') AS CMH,
                ( SELECT SUM(`hints`) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND (id_game like 'NSF%' OR id_game like 'NSD%') AND id_child = '$childid') AS CNSFH,
                ( SELECT SUM(`hints`) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'NSS%' AND id_child = '$childid') AS CNSSH,
                ( SELECT SUM(`hints`) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'NSC%' AND id_child = '$childid') AS CNSCH,
                ( SELECT SUM(`hints`) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'NSPV%' AND id_child = '$childid') AS CNSPVH,
                ( SELECT SUM(`hints`) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'NSN%' AND id_child = '$childid') AS CNSNH,
                ( SELECT SUM(`hints`) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND (id_game like 'ML%' OR id_game like 'SSR%') AND id_child = '$childid') AS CMLH,
                ( SELECT SUM(`hints`) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'MW%' AND id_child = '$childid') AS CMWH,
                ( SELECT SUM(`hints`) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'MV%' AND id_child = '$childid') AS CMVH,
                ( SELECT SUM(`hints`) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'MT%' AND id_child = '$childid') AS CMTH,
                ( SELECT SUM(`hints`) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'NOA%' AND id_child = '$childid') AS CNOAH,
                ( SELECT SUM(`hints`) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'NOS%' AND id_child = '$childid') AS CNOSH,
                ( SELECT SUM(`hints`) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'NOM%' AND id_child = '$childid') AS CNOMH,
                ( SELECT SUM(`hints`) FROM chm_game_play_tbl WHERE EXISTS (SELECT 1 FROM chm_game_play_detail_tbl WHERE chm_game_play_detail_tbl.id_game_play = chm_game_play_tbl.id_game_play) AND id_game like 'NOD%' AND id_child = '$childid') AS CNODH";


        $arrResult = $this->dbh->readRecordsWithQuery($query);
        
        if(count($arrResult,1) == 0) return false;
        else {
            $objChild = $this->getChildUserProgressObject($arrResult[0]);
            return $objChild;
            //var_dump($arrResult[0]);
        }
    }

    private function getChildUserProgressObject($arrData) {
        
        $objGameplay = new userprogress();
        
   
        $objGameplay->setPMST($arrData['PMST']);
        $objGameplay->setCMST($arrData['CMST']);
//
        $objGameplay->setPMNST($arrData['PMNST']);
        $objGameplay->setCMNSST($arrData['CMNSST']);
//
        $objGameplay->setPMGMT($arrData['PMGMT']);
        $objGameplay->setCMMST($arrData['CMMST']);
//
        $objGameplay->setPALGT($arrData['PALGT']);
        $objGameplay->setCMNOST($arrData['CMNOST']);

        $objGameplay->setPNS($arrData['PNS']);
        $objGameplay->setPALG($arrData['PALG']);
        $objGameplay->setPGM($arrData['PGM']);
        $objGameplay->setPNSN($arrData['PNSN']);
        $objGameplay->setPNSI($arrData['PNSI']);
        $objGameplay->setPNSF($arrData['PNSF']);
        $objGameplay->setPNSD($arrData['PNSD']);
        $objGameplay->setPNSR($arrData['PNSR']);
        $objGameplay->setPALGV($arrData['PALGV']);
        $objGameplay->setPGMS($arrData['PGMS']);
        $objGameplay->setPGMM($arrData['PGMM']);
        
        // $objGameplay->setPMW($arrData['PMW']);
        // $objGameplay->setPMT($arrData['PMT']);
        // $objGameplay->setPMV($arrData['PMV']);
        // $objGameplay->setPNOA($arrData['PNOA']);
        // $objGameplay->setPNOS($arrData['PNOS']);
        // $objGameplay->setPNOM($arrData['PNOM']);
        // $objGameplay->setPNOD($arrData['PNOD']);
        // $objGameplay->setPNOLD($arrData['PNOLD']);
        // $objGameplay->setPNOLM($arrData['PNOLM']);


        //  $objGameplay->setCNS($arrData['CNS']);
        // $objGameplay->setCNO($arrData['CNO']);
        // $objGameplay->setCM($arrData['CM']);
        // $objGameplay->setCNSN($arrData['CNSN']);
        // $objGameplay->setCNSF($arrData['CNSF']);
        // $objGameplay->setCNSS($arrData['CNSS']);
        // $objGameplay->setCNSC($arrData['CNSC']);
        // $objGameplay->setCNSPV($arrData['CNSPV']);
        // $objGameplay->setCML($arrData['CML']);
        // $objGameplay->setCMW($arrData['CMW']);
        // $objGameplay->setCMTi($arrData['CMTi']);
        // $objGameplay->setCMV($arrData['CMV']);
        // $objGameplay->setCNOA($arrData['CNOA']);
        // $objGameplay->setCNOS($arrData['CNOS']);
        // $objGameplay->setCNOM($arrData['CNOM']);
        // $objGameplay->setCNOD($arrData['CNOD']);

        // $objGameplay->setCNSP($arrData['CNSP']);
        // $objGameplay->setCNOP($arrData['CNOP']);
        // $objGameplay->setCMP($arrData['CMP']);
        // $objGameplay->setCNSNP($arrData['CNSNP']);
        // $objGameplay->setCNSFP($arrData['CNSFP']);
        // $objGameplay->setCNSSP($arrData['CNSSP']);
        // $objGameplay->setCNSCP($arrData['CNSCP']);
        // $objGameplay->setCNSPVP($arrData['CNSPVP']);
        // $objGameplay->setCMLP($arrData['CMLP']);
        // $objGameplay->setCMWP($arrData['CMWP']);
        // $objGameplay->setCMTP($arrData['CMTP']);
        // $objGameplay->setCMVP($arrData['CMVP']);
        // $objGameplay->setCNOAP($arrData['CNOAP']);
        // $objGameplay->setCNOSP($arrData['CNOSP']);
        // $objGameplay->setCNOMP($arrData['CNOMP']);
        // $objGameplay->setCNODP($arrData['CNODP']);

    //    $objGameplay->setCNSFF($arrData['CNSFF']);
        // $objGameplay->setCNOF($arrData['CNOF']);
        // $objGameplay->setCMF($arrData['CMF']);
        // $objGameplay->setCNSNF($arrData['CNSNF']);
        // $objGameplay->setCNSFFF($arrData['CNSFFF']);
        // $objGameplay->setCNSSF($arrData['CNSSF']);
        // $objGameplay->setCNSCF($arrData['CNSCF']);
        // $objGameplay->setCNSPVF($arrData['CNSPVF']);
        // $objGameplay->setCMLF($arrData['CMLF']);
        // $objGameplay->setCMWF($arrData['CMWF']);
        // $objGameplay->setCMTF($arrData['CMTF']);
        // $objGameplay->setCMVF($arrData['CMVF']);
        // $objGameplay->setCNOAF($arrData['CNOAF']);
        // $objGameplay->setCNOSF($arrData['CNOSF']);
        // $objGameplay->setCNOMF($arrData['CNOMF']);
        // $objGameplay->setCNODF($arrData['CNODF']);

        //  $objGameplay->setCNST($arrData['CNST']);
        // $objGameplay->setCNOT($arrData['CNOT']);
        // $objGameplay->setCMT($arrData['CMT']);
        // $objGameplay->setCNSNT($arrData['CNSNT']);
        // $objGameplay->setCNSFT($arrData['CNSFT']);
        // $objGameplay->setCNSST($arrData['CNSST']);
        // $objGameplay->setCNSCT($arrData['CNSCT']);
        // $objGameplay->setCNSPVT($arrData['CNSPVT']);
        // $objGameplay->setCMLT($arrData['CMLT']);
        // $objGameplay->setCMWT($arrData['CMWT']);
        // $objGameplay->setCMTT($arrData['CMTT']);
        // $objGameplay->setCMVT($arrData['CMVT']);
        // $objGameplay->setCNOAT($arrData['CNOAT']);
        // $objGameplay->setCNOST($arrData['CNOST']);
        // $objGameplay->setCNOMT($arrData['CNOMT']);
        // $objGameplay->setCNODT($arrData['CNODT']);

        //  $objGameplay->setCNSH($arrData['CNSH']);
        // $objGameplay->setCNOH($arrData['CNOH']);
        // $objGameplay->setCMH($arrData['CMH']);
        // $objGameplay->setCNSNH($arrData['CNSNH']);
        // $objGameplay->setCNSFH($arrData['CNSFH']);
        // $objGameplay->setCNSSH($arrData['CNSSH']);
        // $objGameplay->setCNSCH($arrData['CNSCH']);
        // $objGameplay->setCNSPVH($arrData['CNSPVH']);
        // $objGameplay->setCMLH($arrData['CMLH']);
        // $objGameplay->setCMWH($arrData['CMWH']);
        // $objGameplay->setCMTH($arrData['CMTH']);
        // $objGameplay->setCMVH($arrData['CMVH']);
        // $objGameplay->setCNOAH($arrData['CNOAH']);
        // $objGameplay->setCNOSH($arrData['CNOSH']);
        // $objGameplay->setCNOMH($arrData['CNOMH']);
        // $objGameplay->setCNODH($arrData['CNODH']);

         
        return $objGameplay;

        print $PNSN;
    }

}  // end of the Class 
?>