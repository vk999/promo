<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	 public $user_access = 0;
	 
	 public function init()
	 {
	    $user_id = Yii::app()->user->getId();
      $m = new UserAdm();
      $this->user_access = $m->getAccess($user_id);
	 }
	 
	public function actions()
	{	  		
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
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
/*
		if (Yii::app()->user->isGuest)
	    {
	        $this->redirect(array('site/Login'));
	    }
	    else
	        $this->render('index');
*/
//echo "LOGIN";die;
	   if(self::isAuth())
	      $this->render('index');
	}

	public function isAuth()
	{
		if (Yii::app()->user->isGuest)
	    {
	        //$this->redirect(array('site/Login'));
	        //$this->render('login');
	        $this->actionLogin();
	        Yii::app()->end();
	    }
	    return true;
/*
	    else
	    {
	        print_r($path); die;
	        $this->render($path);
     	}
*/
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
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
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;
    //echo "LOGIN";die;
		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}


	public function actionMenuform()
	{
		$model=new MenuTree;
		$this->render('menu/view',array('model'=>$model));
	}


	public function actionMenu()
	{
    	$model=new MenuTree;
    	if($this->user_access != 1) {
    	    	$this->render('access');
    				return;
      }
    	if(isset($_POST['field_id']))
    	{
    		$id = $_POST['field_id'];
    		$share = new Share;
    		$menu_type = $share->getMenuType();
      	$lang = $share->getLang();
    		$parent = 0;
    		if(isset($_POST['field_parent'])) $parent = $_POST['field_parent'];
    		if(isset($_POST['field_op'])) {
    			if($_POST['field_op']=='FORM')
    			{
					if(self::isAuth())
						$this->render('menu/form',array('model'=>$model, 'id'=>$id, 'menu_type'=>$menu_type, 'menu_parent'=>$parent));
					//self::isAuth('menu/form',array('model'=>$model, 'id'=>$id));
					return;
    			}
    			else if($_POST['field_op']=='EDIT'){
    				$md = new Menu;
    				if($id>0)
    					$md->updateMenu($lang, $id); // Сохранить в базу [UPDATE]
    				else
    				{
    					$md->newMenu($lang, $id, $parent, $menu_type); // Сохранить в базу [NEW]
    				}
    				if(self::isAuth())
    					$this->render('menu/view',array('model'=>$model, 'menu_type'=>$menu_type));
    				//self::isAuth('menu/view',array('model'=>$model));
    				return;
    			}
    			else
    			{
    				if(self::isAuth())
    					$this->render('menu/view',array('model'=>$model, 'menu_type'=>$menu_type));
    				//self::isAuth('menu/view',array('model'=>$model));
    				return;
    			}
    		}
    		if(self::isAuth())
    			$this->render('menu/form',array('model'=>$model, 'id'=>$id));
    		//self::isAuth('menu/form',array('model'=>$model, 'id'=>$id));
    		return;
    	}
    	if(self::isAuth())
    		$this->render('menu/view',array('model'=>$model,'menu_type'=>1));
        //self::isAuth('menu/view');
    	//$this->render('menu/view',array('model'=>$model));
        // если запрос асинхронный, то нам нужно отдать только данные
    /*
        if(Yii::app()->request->isAjaxRequest){
            $model->attributes=$_POST['MenuForm'];
      		if($model->validate()) $model->calcdemo($model->attributes,'');

      		$output = '1235555';
      		echo CHtml::encode($output);
            // Завершаем приложение
            Yii::app()->end();
        }
        else {
            // если запрос не асинхронный, отдаём форму полностью
            $this->render('menu/view',array('model'=>$model));
        }
	*/
	}

	public function actionPages()
	{
		$model=new Pages;
		$id = 0;
		//if(isset($_POST['field_id'])) $id = $_POST['field_id'];
		if(self::isAuth())
			$this->render('pages/view',array('model'=>$model,'id'=>$id));
		//self::isAuth(array('pages/view',array('model'=>$model, 'id'=>$id)));
	}


	public function actionPagesForm()
	{
 		$share = new Share;
		$lang = $share->getLang();
        $pagetype = '';
        if(!empty($_POST['pagetype'])) $pagetype = $_POST['pagetype'];
        if(!empty($_GET['pagetype'])) $pagetype = $_GET['pagetype'];

		$model=new PagesContent;
		$id = 0;
		if(isset($_POST['id'])) $id = $_POST['id'];
		//print_r($_POST);die;
		if(isset($_POST['PagesContent']))
		{
			//print_r($_POST['PagesContent']);die;
			// Save to base
			$model->attributes=$_POST['PagesContent'];
			$model->SaveContent($id,$model,$_POST['PagesContent']['link'],$lang, $pagetype);
            if($_POST['pagetype'] == 'news') {
                $model = new Pages;
                $this->render('pages/newsview', array('model' => $model));
            } else {
                $this->render('pages/view', array('model' => $model));
            }
			return;
		}
		if(self::isAuth())
		   $this->render('pages/form',array('model'=>$model,'id'=>$id, 'pagetype'=>$pagetype));
		//self::isAuth(array('pages/form',array('model'=>$model, 'id'=>$id)));

	}

    public function actionNewsPages()
    {
        $model=new Pages;
        $id = 0;
        //if(isset($_POST['field_id'])) $id = $_POST['field_id'];
        if(self::isAuth())
            $this->render('pages/newsview',array('model'=>$model,'id'=>$id));
        //self::isAuth(array('pages/view',array('model'=>$model, 'id'=>$id)));
    }

	public function actionMenuTree()
	{
    	if($this->user_access != 1) {
    	    	$this->render('access');
    				return;
      }
	    
	    $model=new MenuTree;
	    if(isset($_POST['MenuTree']))
	    {
	      $model->attributes=$_POST['MenuTree'];
	      //if($model->validate()) $model->calcdemo($model->attributes,'');
	    }
	    if(self::isAuth())
	    	$this->render('menuTree/view',array('model'=>$model));
	    //self::isAuth(array('menuTree/view',array('model'=>$model)));
	}

	public function actionPageUpdate($id)
	{
		$model=new PagesContent;
 		$share = new Share;
		$lang = $share->getLang();
        $pagetype = '';
        if(!empty($_POST['pagetype'])) $pagetype = $_POST['pagetype'];
        if(!empty($_GET['pagetype'])) $pagetype = $_GET['pagetype'];

		if(isset($_POST['id'])) $id = $_POST['id'];
		//print_r($_POST);die;
		if(isset($_POST['PagesContent']))
		{
			//print_r($_POST['PagesContent']);die;
			// Save to base
			$model->attributes=$_POST['PagesContent'];
			$model->SaveContent($id,$model,$_POST['PagesContent']['link'],$lang);

            if($pagetype == 'news') {
                $model = new Pages;
                $this->render('pages/newsview', array('model' => $model));
            } else {
                $this->render('pages/view', array('model' => $model));
            }

			return;
		}

		$this->render('pages/form',array('model'=>$model,'id'=>$id, 'pagetype' =>$pagetype));
	}

	public function actionPageDelete($id)
	{
		$model= new PagesContent;
		$model->DeleteContent($id);
		$this->render('pages/view',array('model'=>$model->getAllPages()));
	}


	// **** Управление пользователями (блокировка, смена пароля) ****
	public function actionUsers()
	{
			if(self::isAuth()) {
			   if($this->user_access != 1) {
    	    	$this->render('access');
    				return;
         }

		  $model = new User('search');
		  $model->unsetAttributes();  // clear any default values
      if(isset($_GET['User'])){
            $model->attributes=$_GET['User'];
      }
			$this->render('users/view', array('model'=>$model));
		}

		//$this->render('users/view');
	}



    public function actionStat_api()
    {
        if(self::isAuth()) {
            if($this->user_access != 1) {
                $this->render('access');
                return;
            }

            $model = new ApiLogs;
            $day = 1;
            if(isset($_GET['day'])){
                $day=intval($_GET['day']);
            }
            $items = $model->Read($day, 100);
            $this->render('stat/view', array('items'=>$items));
        }
    }

    public function actionReport($id) {
        if(self::isAuth()) {
            if($this->user_access != 1) {
                $this->render('access');
                return;
            }

            $model = new ApiLogs;

            $day = 1;
            if(isset($_GET['day'])){
                $day=intval($_GET['day']);
            }
            $items = $model->Export($day, $id);
            $dt = new Datetime();
            $dt_string = $dt->format('Y-m-d\TH:i:s\Z');
            include Yii::getPathOfAlias('application')."/views/backend/site/stat/export.php";
            //include "/home/work/promo/www/protected/views/backend/site/stat/export.php";
            $object = array(
                "fileName" => $id."_".$dt_string.".xls",
                "fileSize" => 0,
                "content" => ""
            );

            header("Pragma: public");
            header("Expires: 0");
            header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
            header("Cache-Control: private", false);
            header("Content-Type: application/x-msexcel");
            header("Content-Disposition: attachment; filename=\"" . iconv('UTF-8', 'CP1251', $object['fileName']) . "\";");
            header("Content-Transfer-Encoding:­ binary");
            exportExcelXML($items, $object);
            header("Content-Length: " . $object["fileSize"]);

            echo $object["content"];
            //$this->render('stat/export', array('items'=>$items));

        }
    }


	public function actionUserBlocked($id)
	{
		$model = new User;
		$result = $model->blocked($id);
		//$model->UpdateUser($id, $model->attributes);
		$this->redirect(array('site/users/view'));
	}


	public function actionUserUpdate($id)
	{
		$model = new User;
		if($id==0)
			$model->newUser();
		else
		{
			$model = User::model()->findByPk($id);
		}

		if(isset($_POST['User'])){
			// обработка формы
			//$model = new User;
			$model->attributes = $_POST['User'];
			if($model->validate())
			{
				if ($model->model()->count("login = :login", array(':login' => $model->login))) {
            		// Указанный логин уже занят. Создаем ошибку и передаем в форму
            		$model->addError('login', 'Логин уже занят');
            		$this->render('users/form', array('id'=>$id, 'model'=>$model));
            		return;
                }
				$model->UpdateUser($id, $model->attributes);
				$this->redirect(array('site/users/view'));
				return;
			}
		}
		// --- вывод формы
		$this->render('users/form', array('id'=>$id, 'model'=>$model));
	}

  public function actionLanguages()
  {
 		if(self::isAuth()) {
		  $model = new Languages('search');
		  $model->unsetAttributes();  // clear any default values
      if(isset($_GET['Languages'])){
            $model->attributes=$_GET['Languages'];
      }
			$this->render('languages/ajxform', array('model'=>$model));
		}

  }

  public function actionCities()
	{
   	if($this->user_access != 1) {
    	    	$this->render('access');
    				return;
    }
		if(self::isAuth()) {
		  $model = new City('search');
		  $model->unsetAttributes();  // clear any default values
      if(isset($_GET['City'])){
            $model->attributes=$_GET['City'];
      }
			$this->render('cities/view', array('model'=>$model));
		}			
	}


    public function actionRating()
    {
        if($this->user_access != 1) {
            $this->render('access');
            return;
        }
        if(self::isAuth()) {
            $model = new PointRating();
            $model->unsetAttributes();  // clear any default values
            if(isset($_GET['Rating'])){
                $model->attributes=$_GET['Rating'];
            }

            $this->render('rating/view', array('model'=>$model));
        }
    }
  
  public function actionUniversity()
	{
   	if($this->user_access != 1) {
    	    	$this->render('access');
    				return;
    }

		if(self::isAuth())
			$this->render('university/view');
	}
  
  public function actionBanners()
	{
		//$model=new Championsips;
		if(self::isAuth())
			$this->render('banners/view');
	}

  /**
   * Modules setup
   */
  public function actionModule() {
     	if($this->user_access != 1) {
    	    	$this->render('access');
    				return;
    }
    $this->render('module');
  }


//========= C A R D S ===============

    // **** Управление пользователями (карточки) ****
    public function actionCards()
    {
        if(self::isAuth()) {
            if($this->user_access != 1) {
                $this->render('access');
                return;
            }

            $model = new UserCard('search');
            $model->unsetAttributes();  // clear any default values
            if(isset($_GET['User'])){
                $model->attributes=$_GET['User'];
            }
            $this->render('users/cardsview', array('model'=>$model));
        }

        //$this->render('users/view');
    }

    public function actionCardEdit($id)
    {
        $model = new CardRequest;

        if(isset($_POST['Card'])) {
            // обработка формы
            $model->updateCard($id, $_POST['Card']);
        }

            // --- вывод формы
        $data = $model->getCard($id);
        $this->render('users/cardform', array('id'=>$id, 'data'=>$data));

    }

	public function actionExportCards()
	{
		if($this->user_access != 1) {
			$this->render('access');
			return;
		}
		//my-grid_c0[]
		if(!empty($_POST['my-grid_c0'])) {
			$checks = $_POST["my-grid_c0"];
			//print_r($checks); die;
			$model = new CardRequest;
			$model->exportCSV($checks);
		} else {
			$model = new UserCard('search');
			$model->unsetAttributes();  // clear any default values
			$this->render('users/cardsview', array('model'=>$model));
		}
	}

    public function actionCardResetStatus($id)
    {
        $model = new CardRequest;
        $model->resetCardStatus($id);


        // --- вывод формы
        $model2 = new UserCard('search');
        //$model->unsetAttributes();  // clear any default values
        $this->render('users/cardsview', array('model'=>$model2));
    }


}