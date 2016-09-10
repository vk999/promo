<?php

class SiteController extends Controller
{
    /**
     * Declares class-based actions.
     */
    public function actions()
    {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
                'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
            ),
            // page action renders "static" pages stored under 'protected/views/site/pages'
            // They can be accessed via: index.php?r=site/page&view=FileName
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users.
     */
    public function actionIndex()
    {
        // renders the view file 'protected/views/site/index.php'
        // using the default layout 'protected/views/layouts/main.php'
        $model = new PagesContent;
        $lang = Yii::app()->session['lang'];
        if (empty($lang)) {
            $lang = 'ru';
            Yii::app()->session['lang'] = 'ru';
        }
        $action = ContentPlus::getActionID();
        $content = $model->getPageContent('about', $lang);
        //		$content['html'] = '';
        $this->render('index', array('content' => $content, 'auth_soc' => 0, 'action' => $action));
    }

    /**
     * This is the action to handle external exceptions.
     */
    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Displays the contact page
     */
    public function actionContact()
    {
        $model = new ContactForm;
        if (isset($_POST['ContactForm'])) {
            $model->attributes = $_POST['ContactForm'];
            if ($model->validate()) {
                $name = '=?UTF-8?B?' . base64_encode($model->name) . '?=';
                $subject = '=?UTF-8?B?' . base64_encode($model->subject) . '?=';
                $headers = "From: $name <{$model->email}>\r\n" .
                    "Reply-To: {$model->email}\r\n" .
                    "MIME-Version: 1.0\r\n" .
                    "Content-type: text/plain; charset=UTF-8";

                mail(Yii::app()->params['adminEmail'], $subject, $model->body, $headers);
                Yii::app()->user->setFlash('contact', 'Thank you for contacting us. We will respond to you as soon as possible.');
                $this->refresh();
            }
        }
        $this->render('contact', array('model' => $model));
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        $model = new LoginForm;
        echo "LOGIN";
        die;
        // if it is ajax validation request
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        // collect user input data
        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate() && $model->login())
                $this->redirect(Yii::app()->user->returnUrl);
        }
        // display the login form
        //$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
        //Yii::app()->user->login($this->_identity,$duration);

        //$this->render('login',array('model'=>$model));
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        //    Yii::app()->request->cookies->clear();
        Yii::app()->user->logout();
        Yii::app()->request->cookies->clear();
        $this->redirect(Yii::app()->homeUrl);
    }


    public function actionPage()
    {
        $action = ContentPlus::getActionID();
        if ($action != '') {
            $model = new PagesContent;
            $lang = Yii::app()->session['lang'];
            $content = $model->getPageContent($action, $lang); //$uri[2],$lang
            $this->render('index', array('content' => $content, 'auth_soc' => 0, 'action' => $action));
        }
        else
            $this->render('error');
    }

/*
    public function actionRegister()
    {
    $model = new User;
    if(isset($_POST['User'])){
       $model->attributes=$_POST['User'];
       if($model->validate()) {
                    // Валидация прошла успешно
                    // Добавляем данные в базу
                    //$model->save();
                    //$this->render('register/form_ra',array('model'=>$model));
                    Yii::app()->session['register']=$_POST['User'];
                    $this->redirect(array('site/RegisterRa'));
                    return;
                }
        else {
          //echo "Not valide";die;
        }
    }

    $this->render('register/form_ra',array('model'=>$model));
    }
*/

    public function actionRegisterPromo()
    {
        $model = new User;
        $promo = new Promo();
        if (isset($_POST['company'])) {
            $login = isset($_POST['login']) ? $_POST['login'] : '';
            $passw = isset($_POST['passw']) ? $_POST['passw'] : '';
            $email = $login; //isset($_POST['login']) ? $_POST['login'] : '';
            $fio = isset($_POST['fio']) ? $_POST['fio'] : '';
            $isman = isset($_POST['isman']) ? $_POST['isman'] : '';
            $birthday = isset($_POST['birthday']) ? $_POST['birthday'] : '';
            $education = isset($_POST['education']) ? $_POST['education'] : '';
            $education_type = isset($_POST['education_type']) ? $_POST['education_type'] : '';
            //$company = isset($_POST['company']) ? $_POST['company'] : '';
            $city = isset($_POST['city']) ? $_POST['city'] : '';
            $email = isset($_POST['email']) ? $_POST['email'] : '';
            $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
            $position = isset($_POST['position']) ? $_POST['position'] : '';
        }
        $data = $promo->getPromo(5);
        $this->render('register/form_promo', array('model' => $model, 'data' => $data));
    }


    public function actionRegisterRa()
    {
        $model = new Ra;
        $this->render('register/form_ra', array('model' => $model));
    }

    public function actionRegisterEmployer()
    {
        $model = new User;
        $user_id = 0;
        $act = new Employer;

        if (isset(Yii::app()->request->cookies['prommu'])) {
            // Is authorise
            $data = Yii::app()->request->cookies['prommu'];
            $data = base64_decode($data);
            $data = json_decode($data);
            $user_id = Share::getUserID($data->uid);
            //echo $data->uid . "\t". $user_id; die;
        }

        if (isset($_POST['company'])) {
            $login = isset($_POST['login']) ? $_POST['login'] : '';
            $passw = isset($_POST['passw']) ? $_POST['passw'] : '';
            $email = $login; //isset($_POST['login']) ? $_POST['login'] : '';
            $company = isset($_POST['company']) ? $_POST['company'] : '';
            $www = isset($_POST['www']) ? $_POST['www'] : '';
            $fio = isset($_POST['fio']) ? $_POST['fio'] : '';
            $position = isset($_POST['position']) ? $_POST['position'] : '';
            $city = isset($_POST['city']) ? $_POST['city'] : '';
            $interests = isset($_POST['interests']) ? $_POST['interests'] : '';
            $birthday = isset($_POST['birthday']) ? $_POST['birthday'] : '';
            $photo = isset($_POST['$hoto']) ? $_POST['photo'] : '';
            $ra_id = isset($_POST['ra_id']) ? $_POST['ra_id'] : '';
            $phone1 = isset($_POST['phone']) ? $_POST['phone'] : '';
            $phone2 = isset($_POST['phone2']) ? $_POST['phone2'] : '';
            $isman = isset($_POST['isman']) ? $_POST['isman'] : '';

            if($user_id > 0) {
                // Update user
                $act->updateUserEmployer(
                    $user_id,
                    $login,
                    $passw,
                    $email,
                    $company,
                    $www,
                    $fio,
                    $position,
                    $city,
                    $interests,
                    $birthday,
                    $photo,
                    $ra_id,
                    $phone1,
                    $phone2,
                    $isman
                );
            } else {
                // New user
                $user_id = $act->addUserEmployer(
                    $login,
                    $passw,
                    $email,
                    $company,
                    $www,
                    $fio,
                    $position,
                    $city,
                    $interests,
                    $birthday,
                    $photo,
                    $ra_id,
                    $phone1,
                    $phone2,
                    $isman
                );
            }

        }
        $data = $act->getEmployer($user_id);
        $this->render('register/form_employer', array('model' => $model, 'data' => $data));
    }


    public function actionClients()
    {
        $this->render('tabloid');
    }

    public function actionVacation()
    {
        // модель используется для работы календарика Yii, для формы не нужно
        $vid = "";
        $model = new User;
        if (isset($_POST['vid'])) {
            $vid = $_POST['vid'];
        }
        $this->render('vacation/form', array('model' => $model, 'vid' => $vid));
    }

    public function actionVacationShow()
    {
        $vid = "";
        //$model = new User;
        if (isset($_POST['vid'])) {
            $vid = $_POST['vid'];
        }
        $this->render('vacation/show', array('vid' => $vid));
    }

    public function actionVacationShowPublic()
    {
        $vid = "";
        //$model = new User;
        if (isset($_POST['vid'])) {
            $vid = $_POST['vid'];
        }
        $this->render('vacation/show_public', array('vid' => $vid));
    }

    /*  -- deprecated --
    public function actionVacationList()
    {
        $this->render('vacation/view');
    }
    */

    public function actionLkEmpl()
    {
        $this->render('lk/view_empl');
    }


    public function actionResume()
    {
        $model = new User;
        $this->render('register/form_promo_full', array('model' => $model));
    }

    public function actionPhoto()
    {
        if (isset($_POST['op'])) {
            if ($_POST['op'] != '') {
                $id = intval($_POST['id']);
                $op = $_POST['op'];
                if ($op == 'DEL') {
                    $res = Yii::app()->db->createCommand()
                        ->select("id, photo")
                        ->from('photo')
                        ->where("id = :id", array(':id' => $id))
                        ->queryRow();
                    $path = $_SERVER['DOCUMENT_ROOT'] . '/content/' . $res['photo'];
                    try
                    {
                        if (file_exists($path)) {
                            unlink($_SERVER['DOCUMENT_ROOT'] . '/content/' . $res['photo']);
                            if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/content/thumbs/' . $res['photo']))
                                unlink($_SERVER['DOCUMENT_ROOT'] . '/content/thumbs/' . $res['photo']);
                            // delete from DB
                            $res = Yii::app()->db->createCommand()
                                ->delete('photo', 'id=:id', array(':id' => $id));
                        }
                    } catch (Exception $e) {
                    }
                } else if ($op == 'ADD') {
                    $uid = Share::getUserID();
                    $photo_name = $_POST['photo_name'];
                    Yii::app()->db->createCommand()
                        ->insert('photo', array(
                        'photo' => $photo_name,
                        'id_user' => $uid,
                    ));
                }
            }
        }
        $this->render('register/form_photo');
    }

    public function actionOrganizer()
    {
        //$model = new User;
        $this->render('organizer/view_promo');
    }

    public function actionSearchEmpl()
    {
        $model = new User;
        $this->render('search/employer', array('model' => $model));
    }

    public function actionSearchPromo()
    {
        $model = new User;
        $this->render('search/promo', array('model' => $model));
    }

    public function actionShowResume()
    {
        //$model = new User;
        $vid = "";
        //$model = new User;
        if (isset($_POST['vid'])) {
            $vid = $_POST['vid'];
        }
        $this->render('resume/view_resume', array('vid' => $vid));
    }

    public function actionShowResumePublic()
    {
        //$model = new User;
        $vid = "";
        //$model = new User;
        if (isset($_POST['vid'])) {
            $vid = $_POST['vid'];
        }
        $this->render('resume/view_resume_public', array('vid' => $vid));
    }

    public function actionActivate()
    {
        $model = new Auth;
        if (isset($_GET['uid'])) {
            $uid = $_GET['uid'];
            $user = $model->CheckTokenActivate($uid);

            if ($user['id']>0 && $user['type'] == 3) {
                // The Employer
                $model->SetActivate($user['id']);
                $model_empl = new Employer;
                $profile = $model_empl->getEmployer($user['id']);
                $this->render('test/empl', array('data' => $profile));
                return;
            } else if($user['id']>0) {
                // The Promouter (Candidate)
                $model->SetActivate($user['id']);
                $model_promo = new Promo;
                $profile = $model_promo->getPromoFull($user['id']);
                $this->render('test/promo', array('data' => $profile));
                return;
            }
        }
        // --- No token or Activation expired
        // Активация уже была. Нет записи в user_activate. Или токен ключ недействительный
        $this->render('alert_activation');
    }

    public function actionResponseEmpl()
    {
        $this->render('response/empl');
    }

    public function actionResponsePromo()
    {
        $this->render('response/promo');
    }

    // аккаунт РА (Оценки)
    public function actionRating()
    {
        $this->render('rating/view');
    }

    public function actionRatingEmpl()
    {
        $this->render('rating/empl');
    }

    public function actionRatingPromo()
    {
        $this->render('rating/promo');
    }

    public function actionShowRa()
    {
        $this->render('pages/view_ra');
    }

    public function actionEditRa()
    {
        $this->render('register/form_ra');
    }

    public function actionSitepage()
    {
        if (isset($_GET['uid'])) {
            $uid = $_GET['uid'];
            $uid = str_replace('/','',$uid);
            $res = Yii::app()->db->createCommand()
                ->select("u.id_user, r.id_ra")
                ->rightJoin('ra r', 'r.id_user = u.id_user')
                ->from('user_work u')
                ->where('uid = :uid', array(':uid' => $uid))
                ->queryRow();
            $id = $res['id_ra'];

            $model = RaContent::model()->findByPk($id);
            if (!isset($model->content)) $model = new RaContent;
            if (isset($_POST['RaContent'])) {
                $model->attributes = $_POST['RaContent'];
                $model->id_ra = $id;
                $model->save();
            }
            $this->render('pages/form', array('id' => $id, 'model' => $model));
        } else {
            $this->actionIndex();
        }
    }

    // Авторизация фейсбук
    public function actionFacebook()
    {
        $docroot = $_SERVER['DOCUMENT_ROOT'];
        # Подключаем файл библиотеки
        require $docroot . '/uploads/facebook.php';
        # Создаем объект класса Facebook
        $facebook = new Facebook(array(
            'appId' => Share::setup('FB_ID'), //'540192692728862',
            'secret' => Share::setup('FB_KEY'), //'3c86035b6485c88008cb6dc67b42bfba',
            'cookie' => true
        ));

        $fc_uid = $facebook->getUser();
        if (!empty($fc_uid)) {
            try {
                $fc_api_call = array(
                    'method' => 'users.getinfo',
                    'uids' => $fc_uid,
                    'fields' => 'uid, first_name, last_name, pic_square, pic_big, sex, email, education_history, birthday, current_location'
                );

                $fc_users_getinfo = $facebook->api($fc_api_call);
            } catch (FacebookApiException $e) {
                error_log($e);
                $user = null;
            }

            //print_r($fc_users_getinfo); die;
            // проверка юзера в базе
            $res = Share::getCID($fc_users_getinfo[0]["uid"], 'fb');
            if ($res['id_user'] > 0) {
                // приветствие
                //$this->render('pages/form',array('id'=>$res['id_user'],'model'=>$model));
                $model = new PagesContent;
                $lang = Yii::app()->session['lang'];
                if (empty($lang)) {
                    $lang = 'ru';
                    Yii::app()->session['lang'] = 'ru';
                }
                $content = $model->getPageContent('about', $lang);
                $this->render('index', array('content' => $content, 'auth_soc' => 1, 'email' => $res['email']));
            }
            else
            {
                // переход на форму регистрации
                $model = new User;
                $this->render('register/form_promo', array('model' => $model, 'fc_users_getinfo' => $fc_users_getinfo));
            }
        }
        else
        {
            # Нет данных об авторизации в сессии, направляем пользователя для авторизации
            $login_url = $facebook->getLoginUrl();
            header("Location: " . $login_url);
        }

    }

    public function actionForum()
    {
        $this->render('forum/view_f');
    }

    public function actionAuth()
    {
        $login = isset($_POST['login']) ? $_POST['login'] : '';
        $passw = isset($_POST['passw']) ? $_POST['passw'] : '';
        $model = new Auth;
        $res = $model->Authorize($login, $passw);
        if(!empty($res)) {
            $data = json_encode(array('uid'=>$res['uid'], 'token'=>$res['access_token']));
            Yii::app()->request->cookies['prommu'] = new CHttpCookie('prommu', base64_encode($data));

        }
        $this->render('test_auth', array('res' => $res));
    }

    public function actionPromofullTest()
    {
        $model = new Promo();
        if (isset($_POST['id_user']))
        {
            $id_user = intval($_POST['id_user']);
            $model->setPromoFull($id_user, $_POST);
        }
        $data = $model->getPromoFull(4); // (user_id, resume_id)
        $this->render('test/promo', array('data' => $data));
    }

    public function actionEmplfullTest()
    {
        $model = new Employer();
        if (isset($_POST['id_user']))
        {
            $id_user = intval($_POST['id_user']);
            $model->setEmplFull($id_user, $_POST);
        }
        $data = $model->getEmployer(1); // (user_id, resume_id)
        $this->render('test/empl', array('data' => $data));
    }

    public function actionEmplvacationTest()
    {
        $model = new EmplVacation();
        if (isset($_POST['id']))
        {
            $id_vac = intval($_POST['id']);
            $model->setEmplVacation($id_vac, $_POST);
        }
        $data = $model->getVacation(1); // (user_id, resume_id)
        $this->render('test/emplvacation', array('data' => $data));
    }

    public function actionSearchvacationTest()
    {
        $model = new EmplVacation();
        $data = null;
        if (isset($_POST['search']))
        {
            $data = $model->searchVacation($_POST, 250, 4);
        }
        $this->render('test/searchvacation', array('data' => $data, 'params'=>$_POST));
    }

    public function actionSearchemplTest()
    {
        $model = new Employer();
        $data = null;
        if (isset($_POST['search']))
        {
            $data = $model->searchEmployer($_POST);
        }
        $this->render('test/searchempl', array('data' => $data, 'params'=>$_POST));
    }


    public function actionSearchPromoTest()
    {
        $model = new Promo();
        $data = null;
        if (isset($_POST['search']))
        {
            $data = $model->searchPromo($_POST);
        }
        $this->render('test/searchpromo', array('data' => $data, 'params'=>$_POST));
    }

    public function actionSearchHomeTest()
    {
        $model = new Promo();
        $data = null;
        if (isset($_POST['search']))
        {
            $data = $model->searchPromo($_POST);
        }
        $this->render('test/searchhome', array('data' => $data, 'params'=>$_POST));
    }

    public function actionResponsePromoTest()
    {
        $id_promo = empty($_POST['id_user']) ? 4 : $_POST['id_user'];
        $id_vacation = empty($_POST['id_vacation']) ? 1 : $_POST['id_vacation'];
        $model = new Response();
        if (isset($_POST['resp']))
        {
            $status = isset($_POST['status']) ? $_POST['status'] : -1;
            $model->setStatus($id_promo, $id_vacation, $status);
        }
        $data = $model->getStatus($id_promo, $id_vacation);
        $this->render('test/response_promo', array('data' => $data));
    }

    public function actionResponseEmplTest()
    {
        $id_promo = empty($_POST['id_user']) ? 4 : $_POST['id_user'];
        $id_vacation = empty($_POST['id_vacation']) ? 1 : $_POST['id_vacation'];
        $model = new Response();
        if (isset($_POST['resp']))
        {
            $status = isset($_POST['status']) ? $_POST['status'] : -1;
            $model->setStatus($id_promo, $id_vacation, $status);
        }
        $data = $model->getStatus($id_promo, $id_vacation);
        $this->render('test/response_empl', array('data' => $data));
    }

    public function actionRatingEmplTest() {
        $model = new PointRating();
        if (isset($_POST['resp'])) {
            $ret = $model->savePoints(2, $_POST);
            $id_vacation = empty($_POST['id_vacation']) ? 1 : $_POST['id_vacation'];
            $data = $model->getRatingEmpl($id_vacation);
            $this->render('test/rating_empl_show', array('data' => $data, 'is_save' => $ret));
            return;
        }
        $data = $model->getPoints(2);
        $this->render('test/rating_empl', array('data' => $data));
    }


    public function actionRatingPromoTest() {
        $model = new PointRating();
        if (isset($_POST['resp'])) {
            $ret = $model->savePoints(1, $_POST);
            $id_user = empty($_POST['id_user']) ? 1 : $_POST['id_user'];
            $data = $model->getRatingPromo($id_user);
            $this->render('test/rating_promo_show', array('data' => $data, 'is_save' => $ret));
            return;
        }
        $data = $model->getPoints(1);
        $this->render('test/rating_promo', array('data' => $data));
    }

    public function actionChatbypromo() {
        $this->render('test/chat_promo');
    }

    public function actionChatbyempl() {
        $this->render('test/chat_empl');
    }

}