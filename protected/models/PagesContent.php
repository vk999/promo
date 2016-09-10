<?php

/**
 * This is the model class for table "pages_content".
 *
 * The followings are the available columns in table 'pages_content':
 * @property integer $id
 * @property integer $page_id
 * @property integer $hidden
 * @property string $name
 * @property string $html
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property string $lang
 */
class PagesContent extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return PagesContent the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'pages_content';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('page_id', 'required'),
			array('page_id, hidden', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>50),
            array('img', 'length', 'max'=>50),
            array('anons', 'safe'),
			array('meta_title, meta_keywords', 'length', 'max'=>190),
			array('meta_description', 'length', 'max'=>255),
			array('lang', 'length', 'max'=>2),
			array('html', 'safe'),
            array('pubdate', 'date', 'format' => 'yyyy-M-d H:m:s'),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, page_id, hidden, name, html, meta_title, meta_description, meta_keywords, lang', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		//return array(
		//);
//				return array(
//        'Pages'=>array(self::BELONGS_TO, 'Pages ', array('id'=>'page_id')));


		return array(
			'page' => array(self::BELONGS_TO, 'Pages', 'page_id'),
		);


	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'Id',
			'page_id' => 'Page',
			'hidden' => 'Hidden',
			'name' => 'Name',
            'anons' => 'Anons',
            'img' => 'Img',
			'html' => 'Html',
			'meta_title' => 'Meta Title',
			'meta_description' => 'Meta Description',
			'meta_keywords' => 'Meta Keywords',
			'lang' => 'Lang',
            'pubdate' => 'Pubdate',
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

		$criteria->compare('id',$this->id);

		$criteria->compare('page_id',$this->page_id);

		$criteria->compare('hidden',$this->hidden);

		$criteria->compare('name',$this->name,true);

        $criteria->compare('img',$this->img,true);

		$criteria->compare('html',$this->html,true);

		$criteria->compare('meta_title',$this->meta_title,true);

		$criteria->compare('meta_description',$this->meta_description,true);

		$criteria->compare('meta_keywords',$this->meta_keywords,true);

		$criteria->compare('lang',$this->lang,true);

		return new CActiveDataProvider('PagesContent', array(
			'criteria'=>$criteria,
		));
	}


	/* ------------ FUNCTIONAL --------------*/
	public function getContent($lang, $id) {
		if($id==0)
		{
			$result = new PagesContent;
			$result->id = 0;
			$result->hidden = 0;
			$result->lang = $lang;
			return $result;
		}
		else {
        /*
			$result = Yii::app()->db->createCommand()
    	->select('pages.id, pages.link, hidden, name, html, meta_title, meta_description, meta_keywords, lang')
    	->leftJoin('pages_content n', 'n.page_id=pages.id AND lang=:lang', array(':lang'=>$lang))
    	->from('pages')
    	->where('lang=:lang and pages.id=:id', array(':lang'=>$lang, ':id'=>$id))
    	->order('id')
    	->queryRow();
    	*/
       // print_r($result);die;
        //return $result;
        $result = Yii::app()->db->createCommand()
    	->select('id')
    	//->rightJoin('pages', 'pages.id=pages_content.page_id AND lang=:lang', array(':lang'=>$lang))
    	->from('pages_content')
    	->where('page_id=:pid and lang=:lang', array(':pid'=>$id, ':lang'=>$lang))
    	->queryRow();
        //return $result;
        $page_id = $result['id'];
    	return PagesContent::model()->findByPk($page_id);
		}
	}

	public function SaveContent($id,$content,$link,$lang, $pagetype = ''){
		if($id==0)
		{
			// New
            $group_id = ($pagetype=='news') ? 2 : 1;

			$command = Yii::app()->db->createCommand();
			$command->insert('pages', array(
    		    'link'=>$link,
                'group_id'=>$group_id
			));
			$id = Yii::app()->db->lastInsertID;

			$content->page_id = $id;
			$content->lang = $lang;
            //$content->pubdate = Share::dateFormatToMySql($content->pubdate);

            $content->save();

			if($lang=='ru')
				$lang='en';
			else
				$lang='ru';

			$content = new PagesContent();

			$content->page_id = $id;
			$content->lang = $lang;

            $content->save();
/*
			if(!$content->save()) {
    			print_r($content->getErrors()); die;
    		}
*/
		}
		else {
			// Update
			$result = Yii::app()->db->createCommand()
    			->select('id, page_id')
    			->from('pages_content')
    			->where('page_id=:pid and lang=:lang', array(':pid'=>$id, ':lang'=>$lang))
    			->queryRow();
//    		echo $lang."; id=".$result['id']; die;
    		if($result['id']>0)
    		{
				//$command = Yii::app()->db->createCommand();
				$model = PagesContent::model()->findByPk($result['id']);
			//print_r($model);die;
				$model->attributes = $content->attributes;
				$model->page_id = $result['page_id'];
				$model->lang = $lang;
                //$model->pubdate = Share::dateFormatToMySql($content->pubdate);
			//$model->page_id = 1;
			if(!$model->save()) {
    			print_r($model->getErrors()); die;
    		}

    		$model2 = Pages::model()->findByPk($id);
    		$model2->link = $link;
                if($pagetype=='news') {
                    $model2->group_id = 2;
                }
    		$model2->save();
			//echo($content->name);die;
			//$model->name = $content->name;
			//return $model->update();

/*
			$command->update('pages_content', array(
    			'name'=>$content->name,
    		    'hidden'=>$content->hidden,
    		    'html'=>$content->html,
    		    'meta_title'=>$content->meta_title,
    		    'meta_description'=>$content->meta_description,
    		    'meta_keywords'=>$content->meta_keywords
			), 'page_id=:id, lang=:lang', array(':id'=>$id, ':lang'=>'ru'));
*/
			}
		}
	}

	public function getAllPages()
	{
		$result = Yii::app()->db->createCommand()
    			->select('id, link, group_id')
    			->from('pages')
    			->queryAll();
    	return $result;
	}

	public function getPageContent($link,$lang)
	{
		$result = Yii::app()->db->createCommand()
    			->select('t.id, t.link, p.name, p.html, p.meta_title, p.meta_description, p.meta_keywords, p.lang')
    			->from('pages t')
    			->rightJoin('pages_content p', 't.id=p.page_id')
    			->where('t.link=:link and p.lang=:lang and hidden=:hidden', array(':link'=>$link, ':lang'=>$lang, ':hidden'=>0))
    			->queryRow();
    	return $result;
	}

	public function DeleteContent($id)
	{
			$command = Yii::app()->db->createCommand()
    			->delete('pages_content','page_id=:id', array(':id'=>$id));

			$command = Yii::app()->db->createCommand()
    			->delete('pages','id=:id', array(':id'=>$id));
	}
}