<?php
     session_start();

 class error_view_displaymsg {

   function show($arrErrorMsg = "") {
    
  //  print_r($_SESSION);
    
  ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Error Message Notification</title>
<!--<link href="css/reset-fonts-grids.css" rel="stylesheet" type="text/css" />-->
<!--<link href="css/standard.css" rel="stylesheet" type="text/css" />-->
<!--<link href="css/blue/blue_theme.css" rel="stylesheet" type="text/css" />-->
<!--<link href="css/datePicker.css" rel="stylesheet" type="text/css" />-->
<?php
 
?>
</head>
<body>

<!--
<div id="doc4" class="yui-t2">

  <div id="hd"><div class="header_top_border"/> &nbsp; </div>
    <div class="yui-g headerNav">
      <div class="yui-u first">
        <h5>Online management of all your club activities </h5>
      </div>
      <div class="yui-u">
        <ul class="login_links">
          <li> <a href="<?php echo $_SESSION['EMRP_BASE_URL'];?>modules/auth/auth_controller.php">Login</a> </li>
        </ul>
      </div>
    </div>
    <div class="yui-gc">
      <div class="yui-u first">
        <h2><img src="<?php echo $_club_images_dir_url;?>/<?php echo $_SESSION['club_logo_filename'];?>" alt="Logo" align="absmiddle">| <?php echo $_SESSION['club_tagline'];?></h2>      </div>
      <div class="yui-u">
        <ul class="faq_links">
          <li> <a href="#faq">FAQ</a> </li>
          <li> <a href="">|</a> </li>
          <li> <a href="#help">Help</a> </li>
        </ul>
      </div>
    </div>
  </div>
-->
<?php
///include($_SESSION[LIBRIM_BASE_DIR]."/app/views/includes/club_body_header_for_notloggedin_pages.php");
?>

    <div id="bd_full">
            <div class="module" id="tabContent">
          <h1 class="heading"> Error!</h1>

        <form action="<?php echo $_SESSION['LIBRIM_BASE_URL'];?>modules/auth/auth_controller.php?taskname=show_club_login" method="post" enctype="multipart/form-data" name="error_details" id="error_details" >
          <div class="hr">&nbsp;</div><br />
	  
	   <?php

           if(sizeof($arrErrorMsg) > 0) {
         ?>
             <h4  class="notification">
                  <?php
                      if($arrErrorMsg['error_msg'] != '') {
                        echo $arrErrorMsg['error_msg'];
                      }
                  ?>
             </h4>
             <h4  class="notification">
                  <?php
                     if($arrErrorMsg['detailed_msg'] != '') {
                        echo $arrErrorMsg['detailed_msg'];
                     }
                  ?>
             </h4>
             
          <?php
           }
          ?>
	
	     
		      <table class="form_table">

			      <tr>
				<td></td>
				<td><input type="submit" value="Close" class="button2"/></td>
		      </tr>
		      </table>		      
	 </form>


		</div>
<br />

  
    </div>
<?php
   //include($_SESSION[LIBRIM_BASE_DIR]."/app/views/includes/club_footer.php");
?>
  <!--<div id="ft">
   
      <div class="rightFooter fullwidth">
        <ul>
          <li> <a href="#">Home</a> </li>
          <li> | </li>
          <li> <a href="#">Upcoming Events</a> </li>
          <li> | </li>
          <li> <a href="#">Games Schedule</a> </li>
          <li> | </li>
          <li> <a href="#">Team Album</a> </li>
          <li> | </li>
          <li> <a href="#">FAQs </a> </li>
          <li> | </li>
          <li> <a href="#">Help</a> </li>
          <li> | </li>
          <li> <a href="#">Contact Us</a> </li>
        </ul>
        <ul>
          <li class="copyright"> Copyright &copy; 2009 Clubname.com. All rights reserved. </li>
          <li> <a href="#">Terms &amp; Conditions </a> </li>
          <li> | </li>
          <li> <a href="#">Privacy Policy</a> </li>
        </ul>
      </div>
    </div>
</div>-->
</div>
</body>
<?php
   //include($_SESSION[LIBRIM_BASE_DIR]."/app/views/includes/include_scripts.php");
 ?>
<!--<script src="scripts/jquery.js">-->
<!--            </script>-->
<!--<script src="scripts/form_validation.js">-->
<!--            </script>-->
<!--<script src="scripts/browsefix.js">-->
<!--            </script>-->
<!--<script src="scripts/date_picker.js">-->
<!--            </script>-->
<!--<script src="scripts/date.js">-->
<!--            </script>-->

<script type="text/javascript" charset="utf-8">
            $(function()
            {
				
            });
		</script>
		
<script type="text/javascript" charset="utf-8">

			function setTaskName(tasknameval) {
                 $('input[name=taskname]').val(tasknameval);  // set the hidden field 'taskname'
			}
</script>


</html>
<?php
   }
 }
?>
