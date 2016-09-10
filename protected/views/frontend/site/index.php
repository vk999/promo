<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;

// ----- Auth social network ----
if($auth_soc==1)
{
  echo '<script>
  $(document).ready(function() {
  AccessEmail("'.$email.'");
  });
  </script>
  ';
}

//$r = "";
//if(isset($_GET['r'])) $r = $_GET['r'];
if($action=='' || $action=='#')
{
  $docroot = $_SERVER['DOCUMENT_ROOT'];
  include_once( $docroot . "/protected/views/frontend/site/top_vacancy.php");
  include_once( $docroot . "/protected/views/frontend/site/promo_anket_list.php");
  //Share::setup('LANG_DEFAULT');
}
else
{
	echo '<div id="dyn_page">';
  echo $content['html'];
	echo '</div>';
}
?>