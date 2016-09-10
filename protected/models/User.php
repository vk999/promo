<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id_user
 * @property string $login
 * @property string $passw
 * @property string $access_time
 * @property integer $booble_index
 * @property string $ip
 * @property integer $status
 * @property integer $isblocked
 */
class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return User the static model class
	 */
	public $confirm;
	//public function getConfirn(){} 
	
	//public function setConfirn($value){}
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('login, passw, email, confirm', 'required'),
			array('booble_index, status, isblocked', 'numerical', 'integerOnly'=>true),
			array('login, passw', 'length', 'max'=>64),
			array('email','email'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id_user, login, email, booble_index, status, isblocked', 'safe', 'on'=>'search'),
		);

	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id_user' => '#',
			'login' => Share::lng('ANEM_LOGIN'),
			'passw' => Share::lng('ANEM_PASSW'),
			'email' => Share::lng('AL_EMAIL'),
			'access_time' => 'Дата входа',
			'booble_index' => 'INDEX',
			'ip' => 'Ip',
			'status' =>'Статус',
			'isblocked' => '+/-',
			'confirm' => Share::lword('CONFIRM'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id_user',$this->id_user);

		$criteria->compare('login',$this->login,true);

		$criteria->compare('passw',$this->passw,true);

		$criteria->compare('email',$this->email,true);

		$criteria->compare('access_time',$this->access_time,true);

		$criteria->compare('booble_index',$this->booble_index);

		$criteria->compare('ip',$this->ip,true);

		$criteria->compare('status',$this->status);

		$criteria->compare('isblocked',$this->isblocked);

		return new CActiveDataProvider('User', array(
			'criteria'=>$criteria,
			'pagination' => array('pageSize' => 20,),
		));
	}


	// *** Services ***
	public function blocked($id)
	{
   		$md = User::model()->findByPk($id);
   		$md->isblocked = $md->isblocked ^ 1;
   		Yii::app()->db->createCommand()->update('user', array(
    		'isblocked'=>$md->isblocked), 'id_user=:id', array(':id'=>$id));
   		//echo $md->isblocked; die;
   		$md->save();
   		return $md->isblocked;
	}

	public function newUser()
	{
		//$res = array('ip'=>'0.0.0.0', 'access_time'=>'0', 'passw'=>'123', 'isblocked'=>'0');
		$this->ip = '0.0.0.0';
		$this->access_time = '0';
		$this->passw = md5('123456');
		//$this->passw = md5(mt_rand());
		$this->isblocked = '0';
		return $this;
	}

	public function updateUser($id, $attributes)
	{
   		if($id==0){
   			$md = new User;
   			$md->newUser();
   			$md->id_user=null;
   		}
   		else {
   			$md = User::model()->findByPk($id);
   		}
   		$md->login = $attributes['login'];
   		$md->email = $attributes['email'];
   		$md->status = $attributes['status'];
   		$md->isblocked = $attributes['isblocked'];
   		$md->booble_index = $attributes['booble_index'];
   		//print_r($attributes);die;
   		//$md->isblocked = $md->isblocked ^ 1;
   		//echo $md->isblocked; die;
   		return $md->save();
	}

	public function getUsers()
	{
		$result = Yii::app()->db->createCommand()
    			->select('id_user, login, passw, email, access_time, booble_index, ip, status, isblocked')
    			->from('user')
    			->order('booble_index, id_user')
    			->queryAll();
    	return $result;
	}
}