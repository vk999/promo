<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/style.css" />
 	
	<?php   Yii::app()->getClientScript()->registerCoreScript('jquery'); 
	Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/bootstrap.min.js', CClientScript::POS_HEAD);
	?>
	<script language="JavaScript" src="/js/topmenu_bo.js"></script>
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
<div class="container">
	<div class="row" id="header">	    
			<div class="span12">			
<?php
    $lang = Yii::app()->session['lang'];
    if(empty($lang)) { $lang = 'ru'; Yii::app()->session['lang']='ru';}
    $menu = new Menu;
    $topmenu = $menu->getTreeDB(0, $lang, 4, !Yii::app()->user->isGuest);
?>
        <div id="logo">Prommu Admin</div>
        <div class="navbar">
          <div class="navbar-inner">

        <?php
        if(Yii::app()->user->isGuest) {
          echo '<ul class="nav">
            <li class="active"><a href="'.Yii::app()->homeUrl.'site/login">Login</a></li>
            </ul>';
        } else {
        ?>
         <ul class="nav">
            <li class="active"><a href="<?php echo Yii::app()->homeUrl?>site/menu">Меню</a></li>
            <li><a href="<?php echo Yii::app()->homeUrl?>site/pages">Страницы</a></li>
            <li><a href="<?php echo Yii::app()->homeUrl?>site/newspages">Новости</a></li>
            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Пользователи<b class="caret"></b></a>
             <ul class="dropdown-menu">
                 <li><a href="<?php echo Yii::app()->homeUrl?>site/users">Учетные данные</a></li>
                 <li><a href="<?php echo Yii::app()->homeUrl?>site/cards">Карточки</a></li>
             </ul>

             <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Словари<b class="caret"></b></a>
              <ul class="dropdown-menu">
                  <li><a href="<?php echo Yii::app()->homeUrl?>site/university">ВУЗы</a></li>
                  <li><a href="<?php echo Yii::app()->homeUrl?>site/cities">Города</a></li>
                  <li><a href="#">Метро</a></li>
                  <li><a href="<?php echo Yii::app()->homeUrl?>site/rating"">Рейтинг</a></li>
              </ul>            
            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Настройки<b class="caret"></b></a>
              <ul class="dropdown-menu">
<?php
      $rows = Yii::app()->db->createCommand('SELECT name, title FROM modules WHERE in_list=1 ORDER BY id')->queryAll();
      foreach($rows as $row) {
        echo '<li><a href="'.Yii::app()->homeUrl.'site/Module/'.$row['name'].'">'.$row['title'].'</a></li>';
      }
?>
    					</ul>
            </li>
             <li><a href="<?php echo Yii::app()->homeUrl?>site/stat_api">Статистика API</a></li>
            <li><a href="<?php echo Yii::app()->homeUrl?>site/logout">Выход</a></li>
         </ul>
        <?php
        }
        ?>
          
          </div><!-- mainmenu -->
      </div><!-- navbar -->
      <div class="lang_zone">
	
<!--    
    <div class="btn_lang ru"><a href="#" onclick="setLang('ru')">Рус</a></div>
    <div class="btn_lang en"><a href="#" onclick="setLang('en')">Eng</a></div>
-->    
      </div><!-- lang_zone -->
    </div><!-- span12 -->
  </div><!-- header -->

  <div class="row" id="content">
		<div class="span12">

      <div class="modal hide fade" id="pop-up-mess" tabindex="-1" role="dialog">
				<div class="modal-header"><button class="close" type="button" data-dismiss="modal">?</button></p>
					<h3 style="text-align:center;color:#FFA0A0;">TITLE</h3>
				</div>
				<div class="modal-body">          
          <p>MESSAGE</p>
				</div>
				<div class="modal-footer"><button class="btn" data-dismiss="modal">Закрыть</button><br />
        </div>
      </div>

      <?php echo $content; ?>
    </div><!-- span12 -->
  </div><!-- content -->

	<div class="row" id="footer">
		<div class="span12">
		Copyright &copy; <?php echo date('Y'); ?> by &nbsp;<a href="#">Euro Asian service sales promotion</a>&nbsp;&nbsp;
		All Rights Reserved.
    </div><!-- span12 -->
	</div><!-- footer -->

</div><!-- page -->

<script language="JavaScript">
<?php
/*
	$uri = Share::getModuleName();
	if(count($uri)>2)
		echo "var select_url='".$uri[1]."/".$uri[2]."';";
	else
    	echo "var select_url='none';";
    	
  echo "var topmenu=".$topmenu.";";
  */
?>

	function setLang(lang) {
	   SetColorLang(lang);
	   $.ajax({
        type:'GET',
        url:'admin.php?r=Ajax/SetLang&lang='+lang,
        cache: false,
        dataType: 'text',
        success:function (data) {
        	//alert(data);
        	window.location="";
        },
        error: function(data){
    	   alert("Download error!");
        }
    	});

	}

	function SetColorLang(lang){
		$(".btn_lang").css('opacity','1.0');
		$("."+lang).css('opacity','0.5');
	}

	$(document).ready(function() {
		$("ul#topnav li").hover(function() {
			//$(".submenu").show();
			var sm = $(this).find("span");
			if(sm[0]!=undefined) $(".submenu").show();
		} , function() { //on hover out...
			$(".submenu").hide();
		});

		// --- lang
		var lang = '<?php echo $lang; ?>';
    SetColorLang(lang);
	});

</script>

</body>
</html>
