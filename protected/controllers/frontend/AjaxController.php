<?php

class AjaxController extends CController
{
    // actionIndex вызывается всегда, когда action не указан явно.
    function actionIndex()
    {
        $input = Yii::app()->request->getPost('input');
        //$input = '123';
        // для примера будем приводить строку к верхнему регистру
        $output = mb_strtoupper($input, 'utf-8');

        // если запрос асинхронный, то нам нужно отдать только данные
        if (Yii::app()->request->isAjaxRequest) {
            echo CHtml::encode($output);
            // Завершаем приложение
            Yii::app()->end();
        } else {
            // если запрос не асинхронный, отдаём форму полностью
            $this->render('//site/menuTree/_form', array(
                'input' => $input,
                'output' => $output,
            ));
        }
    }


    function actionListmenu()
    {
        $share = new Share;
//   		$lang = $share->getLangAjax();
        $lang = $_GET['lang'];
        $menu_type = $share->getMenuTypeAjax();
        $menu = new Menu;
        if (Yii::app()->user->isGuest)
            echo "Access denied!";
        else {
            $html = $menu->getMenuListHtml($lang, $menu_type);
            echo $html;
        }
        Yii::app()->end();
    }

    function actionDeleteMenu()
    {
        $share = new Share;
        //$lang = $share->getLangAjax();
        $lang = $_GET['lang'];
        $menu_type = $share->getMenuTypeAjax();
        $id = $_GET['id'];
        $menu = new Menu;
        $html = $menu->delMenu($id, $lang, $menu_type);
        echo $html;
        Yii::app()->end();
    }


    function actionChangePosMenu()
    {
        $id = $_GET['id'];
        $switch = $_GET['switch'];
        $share = new Share;
        //$lang = $share->getLangAjax();
        $lang = $_GET['lang'];
        $menu_type = $share->getMenuTypeAjax();
        $menu = new Menu;
        $html = $menu->getMenuListOfPos($id, $switch, $lang, $menu_type);
        echo $html;
        Yii::app()->end();
    }

    public function actionUpload()
    {
        $this->render('nicUpload');
    }

    public function actionSetLang()
    {
        $lang = $_GET['lang'];
        Yii::app()->session['lang'] = $lang;
        echo $lang;
        Yii::app()->end();
    }

    public function actionEditLanguage()
    {
        $id = intval($_GET['id']);
        $lang = $_GET['lang'];
        $page = $_GET['page'];
        $keyword = $_GET['keyword'];
        $value = $_GET['value'];

        $model = new Languages;
        if ($id == 0)
            echo $model->Add($lang, $page, $keyword, $value);
        else
            echo $model->Edit($id, $lang, $page, $keyword, $value);

    }

    public function actionGetLanguage()
    {
        $id = intval($_GET['id']);
        $model = new Languages;
        $res = $model->GetRow($id);
        echo CJSON::encode($res);
    }


    public function actionDelLanguage()
    {
        $id = intval($_GET['id']);
        $model = new Languages;
        $res = $model->DeleteRow($id);
        echo $res;
    }


// ************* Cities ************************
    public function actionAddCity()
    {
        $name = $_GET['name'];
        $model = new City;
        $model->AddName($name);
        echo("Ok");
    }

    public function actionEditCity()
    {
        $name = $_GET['name'];
        $id = $_GET['id'];
        $model = new City;
        $model->EditName($name, $id);
        echo("Ok");
    }

    public function actionSetActiveCity($id)
    {
        $id = $_GET['id'];
        $model = new City;
        $res = $model->SetActive($id);
        if (!isset($res)) $res = 0;
        echo($res);
    }

    public function actionGetCity()
    {
        $filter = $_GET['name_startsWith'];
        $model = new City;
        $res = $model->GetList(10, $filter);
        echo CJSON::encode($res);

    }

// ************* University ************************
    public function actionAddUniversity()
    {
        $name = $_GET['name'];
        $model = new University;
        $model->AddName($name);
        echo("Ok");
    }

    public function actionEditUniversity()
    {
        $name = $_GET['name'];
        $id = $_GET['id'];
        $model = new University;
        $model->EditName($name, $id);
        echo("Ok");
    }

    public function actionSetActiveUniversity($id)
    {
        $id = $_GET['id'];
        $model = new University;
        $res = $model->SetActive($id);
        if (!isset($res)) $res = 0;
        echo($res);
    }

    public function actionGetUniversity()
    {
        $filter = $_GET['name_startsWith'];
        $model = new University;
        $res = $model->GetList(10, $filter);
        echo CJSON::encode($res);

    }


// ************* Banners ************************
    public function actionAddBanners()
    {
        $name = $_GET['name'];
        $link = $_GET['linkbanner'];
        $file_banner = $_GET['filebanner'];
        $model = new Banners;
        $model->AddName($name, $link, $file_banner);
        echo("Ok");
    }

    public function actionEditBanners()
    {
        $name = $_GET['name'];
        $link = $_GET['linkbanner'];
        $file_banner = $_GET['filebanner'];
        $id = $_GET['id'];
        $model = new Banners;
        $model->EditName($name, $link, $file_banner, $id);
        echo("Ok");
    }

    public function actionSetActiveBanners($id)
    {
        $id = $_GET['id'];
        $model = new Banners;
        $res = $model->SetActive($id);
        if (!isset($res)) $res = 0;
        echo($res);
    }

    public function actionGetBanners()
    {
        $filter = $_GET['name_startsWith'];
        $model = new Banners;
        $res = $model->GetList(10, $filter);
        echo CJSON::encode($res);

    }

    public function actionGetRa()
    {
        $filter = $_GET['name_startsWith'];
        $model = new Ra;
        $res = $model->GetList(10, $filter);
        echo CJSON::encode($res);
    }

    public function actionGetTopMenu()
    {
        $lang = $_GET['lang'];
        $utype = $_GET['utype'];
        $mtype = 1;  // главное меню

        $menu = new Menu;

        header("Content-type: application/json");
        if ($utype > 0) {
            echo $menu->getTwoTree(0, $lang, $mtype, $utype, 1);
        } else {
            echo $menu->getTree(0, $lang, $mtype, 0);
        }
    }

    public function actionMail()
    {
        $mail = $_GET['mail'];
        $login = $_GET['l'];

        $res = Yii::app()->db->createCommand()
            ->select('id_user, status')
            ->from('user')
            ->where('login = :login AND email=:email', array(':login' => $login, ':email' => $mail))
            ->queryRow();

        $id_user = $res['id_user'];
        $status = $res['status'];
        //$tm = new Date().getTime();

        $mtime = microtime(true);
        $time = floor($mtime);
        $ms = $mtime - $time;

        $token = md5(md5($id_user) . md5($ms));

        Yii::app()->db->createCommand()
            ->insert('user_activate', array(
                'id_user' => $id_user,
                'token' => $token,
                'status' => $status
            ));


        $docroot = $_SERVER['DOCUMENT_ROOT'];
        include_once($docroot . "/mail.php");
        $res = sendEmail($mail, $token);
        header("Content-type: application/json");
        if ($res)
            echo '{"message":"Письмо доставлено"}';
        else
            echo '{"message":"Почтовый адрес неверный"}';
    }

    public function actionCheckCid()
    {
        $cid = $_GET['cid'];
        $cname = $_GET['cname'];

        $res = Share::getCID($cid, $cname);
        if ($res['id_user'] > 0)
            echo '1';
        else
            echo '0';
    }


    public function actionGetRating()
    {
        $id = (!empty($_GET['id'])) ? $_GET['id'] : 0;
        $model = new Employer();
        $res = $model->getEmplRating($id);
        echo '<table>';
        foreach ($res as $r) {
            echo '<tr>';
            echo '<td>' . $r['rate'] . '</td>';
            echo '<td>' . $r['rate_neg'] . '</td>';
            echo '<td>' . $r['descr'] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }

    public function actionFastSearch()
    {
        $params = [];
        $html = '';
        $stype = (!empty($_GET['stype'])) ? $_GET['stype'] : '';
        if ($stype == 'vacation') {
            // Vacations
            $model = new EmplVacation;
            if(!empty($_GET['search'])) {
                $params['id_city'] = Share::findCities($_GET['search']);
                if(count($params['id_city']) == 0) {
                    $params['id_city'] = null;
                    $pos = Share::FindPositions($_GET['search']);
                    $params['position'] = empty($pos) ? array(0) : array($pos);
                }
                $params['salary_from'] = 1;
                $params['salary_to'] = 100000;
                $params['age_from'] = 99;
                $params['age_to'] = 1;

                $data = $model->searchVacation($params, 10);

                if(!empty($data)) {
                    $lst_empl_typ = Share::getDirectory('empl_typ');
                    $lst_tpsalary = Share::getDirectory('tpsalary');
                    foreach ($data as $row) {
                        $html .= '<b>Промоутер</b><br/>';
                        $isman = !empty($row['isman']) ? 'Юноши' : '';
                        $iswoman = !empty($row['iswoman']) ? 'Девушки' : '';
                        $html .= "$isman / $iswoman <br/>";
                        $html .= "Город: ".$row['city_name']."</br>";
                        $html .= "Вид работы: ".$lst_empl_typ[$row['attr']['empl_typ']]."</br>";
                        $html .= "Оплата: ". $row['attr']['salary']." ". $lst_tpsalary[$row['attr']['tpsalary']]."</br>";
                        $html .= "Период: с ".$row['date_begin']." по ".$row['date_end']."</br>";
                        $html .= $row['company_name']."</br>";
                        $html .= "<hr>";
                    }
                }
            }
        } else {
            // Resume
            $model = new Promo;
            if(!empty($_GET['search'])) {
                $params['id_city'] = Share::findCities($_GET['search']);
                if(count($params['id_city']) == 0) {
                    $params['id_city'] = null;
                    $pos = Share::FindPositions($_GET['search']);
                    $params['position'] = empty($pos) ? array(0) : array($pos);

                }
                $data = $model->searchPromo($params);
                if(!empty($data)) {
                    foreach ($data as $row) {
                        $html .= '<b>Соискатель</b><br/>';
                        //$isman = !empty($row['isman']) ? 'Юноши' : '';
                        //$iswoman = !empty($row['iswoman']) ? 'Девушки' : '';
                        //echo "$isman / $iswoman <br/>";
                        $html .= "Город: ".$row['city_name']."</br>";
                        $html .= "ФИО: ". $row['user_name']." ". $row['user_surname']."</br>";
                        $html .= "фото: ". $row['attr']['photo']."</br>";
                        $html .= "<hr>";
                    }
                }

            }

        }
        echo $html;

    }


    public function actionGet_theme_by_user()
    {
        $id = (!empty($_GET['id'])) ? $_GET['id'] : 0;
        $model = new Chat();
        $res = $model->getThemеByIdUser($id);
        header("Content-type: application/json");
        echo json_encode($res);
    }

    public function actionGet_theme_by_empl()
    {
        $id = (!empty($_GET['id'])) ? $_GET['id'] : 0;
        $model = new Chat();
        header("Content-type: application/json");
        $res = $model->getThemеByEmpl($id);
        echo json_encode($res);

    }

    public function actionGet_chat_by_user()
    {
        $id = (!empty($_GET['id'])) ? $_GET['id'] : 0;
        $id_theme = (!empty($_GET['id_theme'])) ? $_GET['id_theme'] : 0;
        $model = new Chat();
        $res = $model->getChatByUser($id, $id_theme);
        header("Content-type: application/json");
        echo json_encode($res);
    }

    public function actionGet_chat_by_user_empl()
    {
        $id_user = (!empty($_GET['id_user'])) ? $_GET['id_user'] : 0;
        $id_empl = (!empty($_GET['id_empl'])) ? $_GET['id_empl'] : 0;
        $id_theme = (!empty($_GET['id_theme'])) ? $_GET['id_theme'] : 0;
        $model = new Chat();
        $res = $model->getChatByUserEmpl($id_user, $id_theme, $id_empl);
        header("Content-type: application/json");
        echo json_encode($res);

    }

    public function actionGet_users_by_empl()
    {
        // ID Employer
        $id = (!empty($_GET['id'])) ? $_GET['id'] : 0;
        $id_theme = (!empty($_GET['id_theme'])) ? $_GET['id_theme'] : 0;
        $model = new Chat();
        $res = $model->getUserList($id, $id_theme);
        header("Content-type: application/json");
        echo json_encode($res);
    }

    public function actionAdd_mess_by_user()
    {
        $id = (!empty($_GET['id'])) ? $_GET['id'] : 0;
        $id_theme = (!empty($_GET['id_theme'])) ? $_GET['id_theme'] : 0;
        $id_empl = (!empty($_GET['id_empl'])) ? $_GET['id_empl'] : 0;
        $message = (!empty($_GET['mess'])) ? $_GET['mess'] : '';
        //$message = json_decode('{"message":"'.$message.'"}');
        $message = urldecode($message);
        //$message = iconv("UTF-8", "UTF-8", $message);
        $model = new Chat();
        $model->AddMessByUser($id, $id_theme, $id_empl, $message);
        header("Content-type: application/json");
        $res = $model->getChatByUser($id, $id_theme);
        echo json_encode($res);
    }

    public function actionAdd_mess_by_user_empl()
    {
        $id = (!empty($_GET['id'])) ? $_GET['id'] : 0;
        $id_theme = (!empty($_GET['id_theme'])) ? $_GET['id_theme'] : 0;
        $id_empl = (!empty($_GET['id_empl'])) ? $_GET['id_empl'] : 0;
        $message = (!empty($_GET['mess'])) ? $_GET['mess'] : '';
        //$message = json_decode('{"message":"'.$message.'"}');
        $message = urldecode($message);
        //$message = iconv("UTF-8", "UTF-8", $message);
        $model = new Chat();
        $model->AddMessByUserEmpl($id, $id_theme, $id_empl, $message);
        header("Content-type: application/json");
        $res = $model->getChatByUser($id, $id_theme);
        echo json_encode($res);
    }

    public function actionGet_vacations_by_empl()
    {
        $id = (!empty($_GET['id'])) ? $_GET['id'] : 0;
        $model = new EmplVacation();
        $res = $model->getVacationsByEmpl($id);
        header("Content-type: application/json");
        echo json_encode($res);
    }

    public function actionAdd_new_theme_by_empl()
    {
        $id = (!empty($_GET['id'])) ? $_GET['id'] : 0;
        $id_vac = (!empty($_GET['id_vac'])) ? $_GET['id_vac'] : 0;
        //$id_empl = (!empty($_GET['id_empl'])) ? $_GET['id_empl'] : 0;
        $title = (!empty($_GET['title'])) ? $_GET['title'] : '';
        $title = urldecode($title);
        $model = new Chat();
        $model->AddThemeByEmpl($id_vac, $title);
        header("Content-type: application/json");
        $res = $model->getThemеByEmpl($id);
        echo json_encode($res);
    }

}

?>