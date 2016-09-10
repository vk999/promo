<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="language" content="en"/>
    <meta name="google-site-verification" content="bEdRPmKNiZRvzw8kRs4jC-Jjijv52z8i3Uxo_Va-nXk"/>

    <!-- blueprint CSS framework -->
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css"
          media="screen, projection"/>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css"
          media="print"/>

    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <!--
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/formd.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/lang_front.css" />

    <link rel="icon" href="http://prommu.ru/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/clients.css" />
-->
    <link rel="stylesheet" type="text/css" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"/>


    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/styles/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/styles/style.css"/>


    <?php
    Yii::app()->getClientScript()->registerCoreScript('jquery');
    Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl . '/js/topmenu_old.js', CClientScript::POS_HEAD);
    Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl . '/js/access.js', CClientScript::POS_HEAD);
    Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl . '/js/rotator.js', CClientScript::POS_HEAD);
    Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl . '/js/bootstrap.min.js', CClientScript::POS_HEAD);
    //Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl . '/js/ui/regional_ru.js', CClientScript::POS_HEAD);
    //Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/bootstrap-transition.js', CClientScript::POS_HEAD);
    //Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/bootstrap-collapse.js', CClientScript::POS_HEAD);

    $setup = ContentPlus::getActionInfo('ModSite');

    ?>
    <script src="http://vkontakte.ru/js/api/openapi.js" type="text/javascript"></script>
    <!--script src="http://www.odnoklassniki.ru/oauth/resources.do?type=js" type="text/javascript" charset="utf-8"></script-->
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <!--[if IE]>
    <style>
        .child {
            margin: -5px;
            padding: 5px;
            zoom: 1;
        }
    </style>
    <![endif]-->

    <style>
        <?php echo $setup['logo_css'];?>
    </style>

    <?php
    $lang = Share::getLangSelected();
    Share::getLanguages('login', $lang);
    $menu = new Menu;

    // Общее кол-во анкет с резюме
    $total_promo = Share::getTotalAnketsOfResume();

    // Общее кол-во анкет с вакансиями
    $total_vac = Share::getTotalAnketsOfResume();

    /** *************************
     *     LANG BUTTONS
     ****************************/
    function ShowLangMenu($lang)
    {
        echo '<ul class="nav-tabs lang_zone">';
        /*
        $res = Share::getLangBtn();
        foreach ($res as $row)
        {
            if ($lang == $row['name'])
                echo '<li>' . $row['title'] . '</li>';
            else
                echo '<li><a href="#" onclick="setLang(\'' . $row['name'] . '\')">' . $row['title'] . '</a></li>';
        }
        */
        echo '</ul>';
    }

    /** *************************
     *     ENTER BUTTONS
     ****************************/
    function ShowEnterButtons()
    {
        echo '<div class="enter2">';
        echo '<a href="#reg_select" onclick="ClearCookie()" data-toggle="modal">' . Share::lword('REGISTER') . '</a>&nbsp;&nbsp;|&nbsp;';
        echo '<a href="#login_w" data-toggle="modal">Вход</a>';
        echo '</div>';
    }

    /** *************************
     *     ROOT MENU
     ****************************/
    function checkParent($res, $id)
    {
        $result = false;
        foreach ($res as $row)
        {
            if ($row['parent_id'] == $id) $result = true;
        }
        return $result;
    }

    function ShowChildren($res, $id)
    {
        foreach ($res as $row)
        {
            if ($row['parent_id'] == $id) {
                echo '<li><a href="/site/' . $row['module'] . '/' . $row['link'] . '">' . $row['name'] . '</a></li>' . "\r\n";
            }
        }
    }

    function ShowRootMenu($lang, $menu)
    {
        $uri = Share::getModuleName();
        $res = $menu->getTreeDB(0, $lang, 1, 0); // главное верхнее меню
        echo '<ul class="nav nav-pills menu_white">';
        $mactive = ContentPlus::getActionID();
        foreach ($res as $row)
        {
            $cl = "dir";
            if ($mactive == $row['link']) $cl = "dir active";
            if ($row['parent_id'] == 0) {
                if (checkParent($res, $row['id'])) {
                    echo '<li class="dropdown ' . $cl . '"><a href="#" data-toggle="dropdown" class="dropdown-toggle">' . $row['name'] . '<b class="caret"></b></a>' . "\r\n";
                    echo '<ul class="dropdown-menu">';
                    echo '<li><a href="/site/' . $row['module'] . '/' . $row['link'] . '">' . $row['name'] . '</a></li>' . "\r\n";
                    ShowChildren($res, $row['id']);
                    echo '</ul></li>';
                }
                else
                    echo '<li class="' . $cl . '"><a href="/site/' . $row['module'] . '/' . $row['link'] . '">' . $row['name'] . '</a></li>' . "\r\n";
            }
        }
        echo '</ul>';
    }

    /** *************************
     *     BANNER
     ****************************/
    function ShowBanner()
    {

        $res = Yii::app()->db->createCommand()
            ->select('url, name, file')
            ->from('banners')
            ->where('ishide=0')
            ->queryAll();
        echo '<div class="bnrot"><div id="rotator">';
        foreach ($res as $row)
        {
            echo '<div><img src="/content/' . $row['file'] . '" title="' . $row['name'] . '" /></div>';
        }
        echo '</div></div>';
    }

    /** *************************
     *     HEADER CONTACTS
     ****************************/
    function ShowContacts($setup)
    {
        echo '
    <br/>
    <div class="bg_contacts">
    <table>
    <tr><td style="text-align:right">Наши контакты</td><td><span style="font-size:16px">' . $setup['phone1'] . '</span></td>
    </tr><tr>
    <td style="text-align:right">Для клиентов</td><td><span style="font-size:16px">' . $setup['phone2'] . '</span></td>
    </tr><tr>
    <td style="text-align:right">Email</td><td><a href="mailto:' . $setup['email'] . '" style="font-size:14px; color:#fff;">' . $setup['email'] . '</a></td>
    </tr></table></div>';
        //</div>';
    }
    ?>
</head>

<body>
<div class="container non-responsive">
<div class="row" id="header">
    <div class="span7">
        <a href="/">
            <div id="logo" class="child"></div>
        </a>
    </div>
    <div class="span5">

        <div class="row">
            <div class="span2">
                <!-- lang buttons -->
                <?php ShowLangMenu($lang);?>
            </div>
            <div class="span2 offset1">
                <!-- enter buttons -->
                <?php ShowEnterButtons();?>
            </div>
        </div>

        <div class="row">
            <div class="span6">
                <!-- root menu -->
                <?php ShowRootMenu($lang, $menu);?>
            </div>
        </div>

        <!-- social buttons -->
        <div class="row">
            <div class="span6">
                <div class="hd_contacts cs">
                    <a href="#" onclick="VK.Auth.login(authInfoVK);"><img src="/images/ico/vk.png"/></a>
                    <a href="/site/facebook"><img src="/images/ico/fb.png"/></a>
                    <a href="#"><img src="/images/ico/tv.png"/></a>
                </div>
            </div>
        </div>

    </div>
    <!-- span5 -->
</div>
<!-- row header -->


<div class="row" id="header2">
    <div class="span7">
        <?php ShowBanner();?>
    </div>

    <div class="span5 hd_contacts">

        <?php ShowContacts($setup);?>

        <div class="row">
            <div class="subhd_block" id="sb_text">
                Зарегистрируйся и получи в свое распоряжение ЛИЧНЫЙ КАБИНЕТ.
                Узнай максимум информации о предстоящей работе. Общайся с
                членами будущей команды. Ставь рейтинги работодателю.
                <br/><br/>
                <a class="bigfont" href="/site/RegisterPromo">
                    <div class="bigbtn_blue"><br/>СОЗДАТЬ АНКЕТУ
                        <!--small>в базе <?php echo $total_promo;?> анкет</small-->
                    </div>
                </a>
                <a class="bigfont" href="/site/RegisterEmployer">
                    <div class="bigbtn_orange"><br/>СОЗДАТЬ ВАКАНСИЮ
                        <!--<small>в базе <?php echo $total_vac;?> анкет</small-->
                    </div>
                </a>
            </div>

            <div id="hd_info" style="display:none">
                <div class="star"><b id="myrating_i"></b> <span id="myrating">Ваш рейтинг 0 из 5</span></div>
                <div class="inf"><b id="cnt_response_i"></b> <span id="cnt_response">У вас 0 новых сообщений</span>
                </div>
            </div>

            <div class="subhd_block" id="sb_auth" style="display:none;">
            </div>
        </div>
        <!-- row -->

    </div>

</div>
<!-- row header2-->

<!--
 <div id="container_demo" style="height:163px;">
 <div id="wrapper" style="width:360px; height:163px;padding:10px;">
			<?php
echo '<table><tr><td>';
echo CHtml::label(Share::lword('LOGIN'), 'r_login', array("class=" => "uname", "data-icon" => "u"));
echo CHtml::textField('r_login', '');
echo '</td><td>';
echo CHtml::label(Share::lword('PASSWORD'), 'r_passw', array("class=" => "uname", "data-icon" => "p"));
echo CHtml::passwordField('r_passw', '');
echo '</td></tr></table>';
?>
     <p align="right">
			<a href="#" onclick="Access()" class="btn" ><?php echo Share::lword('ENTER'); ?></a>
     </p>
 </div>
 </div>
</div>
-->

<div class="row" id="content">
    <div class="span12">
        <div id="mainmenu" class="mymenu" style="display:none"></div>
        <?php
        echo $content;
        ?>
    </div>
</div>


<!-- MODAL WINDOW -->
<div class="modal hide fade" id="login_w" tabindex="-1" role="dialog">
    <div class="modal-header">
        <button class="close" type="button" data-dismiss="modal"><img src="/images/ico/close_btn_large.png" width="32">
        </button>
        </p>
        <h3 id="myModalLabel">Авторизация пользователя</h3>
    </div>
    <div class="modal-body offset3">
        <?php
        echo '<div class="control-group">';
        echo '<label class="control-label" style="margin:0">Логин</label>';
        //echo CHtml::label(Share::lword('NAME'), 'r_login', array("class="=>"control-label"));
        echo '<div class="controls input-append">';
        echo CHtml::textField('r_login', '', array("placeholder" => 'Логин'));
        echo '<span class="add-on"><i class="icon-user"></i></span>';
        echo '</div></div>';

        echo '<div class="control-group">';
        echo '<label class="control-label">' . Share::lword('PASSWORD') . '</label>';
        echo '<div class="controls input-append">';
        echo CHtml::passwordField('r_passw', '', array("placeholder" => Share::lword('PASSWORD')));
        echo '<span class="add-on"><i class="icon-barcode"></i></span>';
        echo '</div></div>';
        ?>
        <div class="alert alert-error" id="mess_auth">
            <div id="mess_auth_b"></div>
        </div>
    </div>

    <div class="modal-footer">
        <a href="#" onclick="Access()" class="btn btn-primary"><?php echo Share::lword('ENTER'); ?></a>
    </div>
</div>


<!-- MODAL AUTH -->
<div class="modal hide fade" id="reg_select" tabindex="-1" role="dialog">
    <div class="modal-header">
        <button class="close" type="button" data-dismiss="modal"><img src="/images/ico/close_btn_large.png" width="32">
        </button>
        </p>
        <h3 id="myModalLabel">Зарегистрироваться как:</h3>
    </div>
    <div class="modal-body offset3">
        <ul style="list-style: none;margin: 5px; padding: 0;">
            <li><a href="/site/registerPromo" class="btn btn-info span3">Промоутер</a></li>
            <li><a href="/site/RegisterEmployer" class="btn btn-success span3">Работодатель</a></li>
            <li><a href="/site/registerRa" class="btn btn-warning span3">Рекламное агентсво</a></li>
        </ul>
    </div>
    <br><br>
</div>


<!-- MODAL MESSAGE -->
<div class="modal hide fade" id="messModal" tabindex="-1" role="dialog">
    <div class="modal-header">
        <button class="close" type="button" data-dismiss="modal"><img src="/images/ico/close_btn_large.png" width="32">
        </button>
        </p>
        <h3 id="messTitle">TITLE</h3>
    </div>
    <div class="modal-body" id="messBody">
    </div>
</div>






<?php
$lnk = array('paidservices', 'regulations', 'students', 'tips', 'support', 'help');
$lnkNames = Share::getPageNames($lnk);
?>

<div class="row" id="footer">
    <div class="span3">
        <h4 class="ff">Работодателям</h4>
        <ul class="nomark">
            <li><a href="/site/page/paidservices"><?php echo $lnkNames['paidservices'];?></a></li>
            <li><a href="/site/page/regulations"><?php echo $lnkNames['regulations'];?></a></li>
        </ul>
    </div>


    <div class="span3">
        <h4 class="ff">Соискателям</h4>
        <ul class="nomark">
            <li><a href="/site/page/students"><?php echo $lnkNames['students'];?></a></li>
            <li><a href="/site/page/tips"><?php echo $lnkNames['tips'];?></a></li>
        </ul>
    </div>


    <div class="span3">
        <h4 class="ff">Помощь</h4>
        <ul class="nomark">
            <li><a href="/site/page/support"><?php echo $lnkNames['support'];?></a></li>
            <li><a href="/site/page/help"><?php echo $lnkNames['help'];?></a></li>
            </li>
    </div>


    <div class="span3">
        <?php echo $setup['js_footer']; ?>
        <p style="color:#C0C0C0;">Copyright &copy; <?php echo date('Y'); ?> by EURO-ASIAN SERVICE SALES INCENTIVE<br/>
            All Rights Reserved.</p>
    </div>
</div>
<!-- row footer -->

</div>
<!-- container -->

<!-- page Pop Up
</div>
   <div id="pop-up">
    	<h3 style="text-align:center">Зарегистрироваться как:</h3>
      <ul style="list-style: none;margin: 5px; padding: 0;">
        <li><a href="/site/registerPromo" class="btn full">Промоутер</a></li>
        <li><a href="/site/RegisterEmployer" class="btn full">Работодатель</a></li>
        <li><a href="/site/registerRa" class="btn full">Рекламное агентсво</a></li>
      </ul>
    	<div class="btn_panel">
			<a href='#' class='btn small' style='width:70px;margin-right:5px; display:inline;' onclick="close_pop_up('#pop-up');">Отмена</a>
    	</div>
    	<input type="hidden" id="pkid" />
	 </div>

   <div id="pop-up-mess">
    	<h3 style="text-align:center;color:#FFA0A0;">TITLE</h3>
      <p>MESSAGE</p>
    	<div class="btn_panel" style="border-top:1px solid #900000;">
			<a href='#' class='btn small' style='width:70px;margin-right:5px; display:inline;' onclick="close_pop_up('#pop-up-mess');">Закрыть</a>
    	</div>
	 </div>

   <div id="vk_api_transport"></div>
-->
<script type="text/javascript">
VK.init({
    apiId: <?php echo Share::setup('VK_ID');?>
});

function authInfoVK(response) {
    if (response.session) {
        //alert('user: '+response.session.mid);
        CheckCID(response.session.mid, 'vk');
    } else {
        //alert('not auth');
        showPopUp('', 'Ошибка авторизации!');
    }
}

//VK.Auth.getLoginStatus(authInfo);
//VK.UI.button('login_button');

var topmenu;
<?php
echo "var lang='" . $lang . "';";
$uri = Share::getModuleName();
if (count($uri) > 1)
    echo "var select_url='" . $uri[1] . "';";
else
    echo "var select_url='none';";

//echo "var topmenu=".$topmenu.";";
?>
//alert(select_url);

function showauth_form() {
    $("#sb_text").css("display", "none");
    $("#sb_auth").css("display", "block");
}


function setLang(lang) {
    SetColorLang(lang);
    $.ajax({
        type:'GET',
        url:'/ajax/SetLang?lang=' + lang,
        cache:false,
        dataType:'text',
        success:function (data) {
            //alert(data);
            window.location = "";
        },
        error:function (data) {
            alert("Download error!");
        }
    });

}

function CheckCID(cid, cname) {
    $.ajax({
        type:'GET',
        url:'/ajax/CheckCid?cid=' + cid + '&cname=' + cname,
        cache:false,
        dataType:'text',
        success:function (data) {
            if (data == '0') {
                // no auth
                window.location = "/site/registerPromo";
            } else {
                // auth
                AccessCid(cid, cname);
            }

        },
        error:function (data) {
            alert("Download error!");
        }
    });
}


function SetColorLang(lang) {
    $(".btn_lang").css('opacity', '1.0');
    $("." + lang).css('opacity', '0.5');
}


function open_pop_up(box) {
    $("#overlay").css("height", $(document).height());
    $("#overlay").show();
    $(box).center_pop_up();
    $(box).show(500);
}

function close_pop_up(box) {
    $(box).hide(500);
    $("#overlay").delay(550).hide(1);
}

function showPopUp(title, message) {
    $("#messTitle").text(title);
    $("#messBody").text(message);
    $('#messModal').modal();

    /*
        $("#pop-up-mess h3").html(title);
        $("#pop-up-mess p").html(message);
        open_pop_up("#pop-up-mess");
    */
}


/* Menu of personal cabinete */
function getTopMenu() {
    if(uid == undefined || uid=='') return;
    var ut = -1;
    if (u_type == -1 || u_type == undefined) u_type = get_cookie("utype");
    if (u_type == undefined)
        u_type = -1;
    else
        ut = parseInt(u_type) + 3;
    // --- top menu
    $.ajax({
        type:'GET',
        url:"/ajax/getTopMenu?lang=ru&utype=" + ut,
        cache:false,
        dataType:'json',
        success:function (data) {
            topmenu = data;
            for(var i=0; i<topmenu.length; i++){
                if(topmenu[i].module=='Sitepage') {topmenu[i].module='Sitepage/?uid='+uid;}
            }
            generateTopMenu();
        },
        error:function (data) {
            alert("Download error!");
        }
    });
}


jQuery.fn.center_pop_up = function () {
    this.css('position', 'absolute');
    //this.css('top', ($(window).height() - this.height()) / 2+$(window).scrollTop() + 'px');
    this.css('top', '400px');
    this.css('left', ($(window).width() - this.width()) / 2 + $(window).scrollLeft() + 'px');
}


// высвечивает приветствие
function ShowAuth() {
    console.log('ShowAuth');
    $("#login_w").modal("hide");
    GetMemberToken();
//alert(token);
    var messHello = Array('', '', 'промоутера', 'работодателя', 'рекламного агентства');
    if (!!token) {
        //$("#enter").hide();
        $(".enter2").html('&nbsp;&nbsp;<a href="#" onclick="Exit()">Выход</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
        $("#sb_text").html('<b>' + login + '</b>, добро пожаловать в личный кабинет ' + messHello[u_type] + '<br/>');
        //$("#reg_info").html('<b>'+login+'</b>, добро пожаловать в личный кабинет '+messHello[u_type]+'<br/>');
        $("#sb_auth").hide();
        $("#sb_text").show();
        $("#mainmenu").show();
        $("#hd_info").show();
        //$("#reg_info").show();
        var rt = (rating / 100) * 5;
        var rt2 = Math.round(rt * 100) / 100;
        $("#myrating").html("Ваш рейтинг " + rt2 + " из 5");
        $("#myrating_i").html(rt2);

        $("#cnt_response").html("У вас " + cnt_response + " новых сообщений");
        $("#cnt_response_i").html(cnt_response);

        //alert($("#mainmenu").html());
    } else {
        $("#sb_text").show();
        $("#sb_text").css("visibility", "visible");
    }
    //alert($("#sb_text").text());
}


$(document).ready(function () {
    $("#mess_auth").hide();
    $('#r_passw').bind('keypress', function (e) {
        if (e.keyCode == 13) {
            Access();
        }
    });

    $("ul#topnav li").hover(function () {
        //$(".submenu").show();
        var sm = $(this).find("span");
        if (sm[0] != undefined) $(".submenu").show();
    }, function () { //on hover out...
        $(".submenu").hide();
    });

    // --- lang
    var lang = '<?php echo $lang; ?>';
    SetColorLang(lang);
    ShowAuth();

    // --- top menu
    getTopMenu();

    var pg = getPath();
    pg = pg.toLowerCase();
    console.log('page = '+pg);

/*
    $("a[href='/site/Sitepage/']").attr('href', '/site/Sitepage/?uid='+uid);

        $("a").each(function(){
            somevar = $(this).attr("href");
            console.log('link = '+somevar);
        });
*/
    switch(pg)
    {
        case 'registeremployer':        getForm_RegisterEmployer(); break;
        case 'registerpromo':           getForm_RegisterPromo(); break;
        case 'resume':                  checkSecure(); run_parse = true; getForm_Resume(); break;
        case 'vacationlist':            checkSecure(); getEmplInfo(); break;
        case 'vacationshow':
        case 'vacation':
                                        if (vid == undefined)
                                            getForm_Blank();
                                        else
                                            getForm(vid);
                                        break;
        case 'organizer':               checkSecure(); readOrg(); break;
        case 'searchempl':              checkSecure(); break;
        case 'searchpromo':             checkSecure(); break;
        case 'showresume':              getForm_Resume(); break;
        case 'responseempl':            checkSecure(); getAllVacation();
                                        // Inject buttons
                                        var h = $("#btn_panel_resume").html();
                                        h = '<a href="#" onclick="acceptResume()" class="btn small" style="float:right; padding:0px;display:none;" id="btn_accept" >Принять</a>' + h;
                                        $("#btn_panel_resume").html(h);
                                        break;
        case 'responsepromo':           checkSecure(); getAllVacation(); break;
        case 'rating':                  getRaInfo(); break;
        case 'ratingempl':              checkSecure(); getEmplInfo(); break;
        case 'ratingpromo':             checkSecure(); getAllProjects(); break;
        case 'editra':                  getForm_RegisterRa(); break;
        case 'forum':                   checkSecure(); break;
    }

});

function checkSecure() {
    if(token==undefined || token=='') {
        uid = '';
        history.go(-(history.length - 1));
        window.location.replace("/");
    }
}
</script>


</body>
</html>
