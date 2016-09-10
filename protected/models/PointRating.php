<?php

/**
 * This is the model class for table "point_rating".
 *
 * The followings are the available columns in table 'point_rating':
 * @property integer $id
 * @property integer $grp
 * @property integer $value
 * @property string $descr
 */
class PointRating extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return PointRating the static model class
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
		return 'point_rating';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('grp, value, descr', 'required'),
			array('grp, value', 'numerical', 'integerOnly'=>true),
			array('descr', 'length', 'max'=>128),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, grp, value, descr', 'safe', 'on'=>'search'),
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
			'id' => 'ID',
			'grp' => 'Grp',
			'value' => 'Value',
			'descr' => 'Descr',
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
		$criteria->compare('grp',$this->grp);
		$criteria->compare('value',$this->value);
		$criteria->compare('descr',$this->descr,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

//=================== My Services ====================

    public function EditPoint($name, $id, $val)
    {
        $command = Yii::app()->db->createCommand();
        $command->update('point_rating', array(
            'descr'=>$name,
            'value'=>$val
        ), 'id=:id', array(':id'=>$id));
    }

    public function AddPoint($name, $val, $grp)
    {
        $command = Yii::app()->db->createCommand();
        $command->insert('point_rating', array(
            'value'=>$val,
            'descr'=>$name,
            'grp'=>$grp,
        ));
    }

    /*
     * grp: 1 - Empl, 2 - Promo
     *
     * 1 - работодатель оценивает соискателя
     * 2 - соискатель оценивает работодателя
     */
    public function getPoints($grp) {
        $list = Yii::app()->db->createCommand()
            ->select('*')
            ->from('point_rating')
            ->where('grp=:grp', array(':grp'=>$grp))
            ->queryAll();
        return $list;
    }

    public function getRatingEmpl($id_vacation) {
        $id_empl = Yii::app()->db->createCommand()
            ->select('id_user')
            ->from('empl_vacations')
            ->where('id=:id', array(':id'=>$id_vacation))
            ->queryScalar();

        $sql = "select sum(m.rate) as rate, sum(m.rate_neg) as rate_neg, m.id_point, m.descr from (
select
CASE WHEN rd.point>=0 THEN rd.point else 0 end as rate,
CASE WHEN rd.point<0 THEN rd.point else 0 end as rate_neg,
rd.id_point, r.descr
from rating_details rd, point_rating r
where is_promo=1 and id_vacation in (select id from empl_vacations where id_user=$id_empl)
and r.id = rd.id_point
) m group by m.id_point";
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        return $res;
    }

	public function getRatingPromo($id_promo) {
		$sql = "select sum(m.rate) as rate, sum(m.rate_neg) as rate_neg, m.id_point, m.descr from (
select
CASE WHEN rd.point>=0 THEN rd.point else 0 end as rate,
CASE WHEN rd.point<0 THEN rd.point else 0 end as rate_neg,
rd.id_point, r.descr
from rating_details rd, point_rating r
where is_promo=0 and id_promo = $id_promo
and r.id = rd.id_point
) m group by m.id_point";
		$res = Yii::app()->db->createCommand($sql)->queryAll();
		return $res;
	}

    public function savePoints($grp, $params) {
		// Check duplicate
		$id_promo = empty($params['id_user']) ? 0 : $params['id_user'];
		$id_vacation = empty($params['id_vacation']) ? 0 : $params['id_vacation'];
		$is_promo = ($grp == 1) ? 0 : 1;

		$cnt = Yii::app()->db->createCommand()
			->select('count(*) as cnt')
			->from('rating_details')
			->where('id_vacation=:id_vac and id_promo=:id_promo and is_promo=:is_promo', array(':id_vac'=>$id_vacation, ':id_promo'=>$id_promo, ':is_promo'=>$is_promo))
			->queryScalar();

		if ($cnt>0) {
			return false;
		}

		$res = Yii::app()->db->createCommand()
			->select('*')
			->from('point_rating')
			->where('grp=:grp', array(':grp'=>$grp))
			->queryAll();

		foreach ($res as $r) {
			Yii::app()->db->createCommand()
				->insert('rating_details', array(
					"point" => intval($params['point_'.$r['id']]),
					"id_point" => $r['id'],
					"is_promo" => $is_promo,
					"id_vacation" => $id_vacation,
					"id_promo" => $id_promo,
				));
		}

		return true;
    }

}