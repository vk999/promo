<?php
/**
 * Created by PhpStorm.
 * User: wind
 * Date: 17.02.16
 * Time: 22:22
 */

class Promo {

    public function getPromo($id_user)
    {
        $user = Yii::app()->db->createCommand()
            ->select("e.fio, c.name as city_name, DATE_FORMAT(e.birthday,'%d.%m.%Y') as birthday,
                e.phone, e.email, e.photo, e.isman, u.name as education, e.education_type, usr.login")
            ->from('resume e')
		    ->join('city c', 'c.id_city=e.id_city')
            ->join('university u', 'u.id=e.id_universiti')
		    ->join('user usr', 'usr.id_user=e.id_user')
		    ->where('e.id_user=:id_user', array(':id_user' => $id_user))
            ->queryAll();

        return $user[0];

/*
        SELECT p.id as prj_id, j.id_jobs, j.name_act, "+
		"DATE_FORMAT(j.date_begin,'%d.%m.%Y') as date_begin, " +
		"DATE_FORMAT(j.date_end,'%d.%m.%Y') as date_end, " +
		"j.date_public, "+
		"c.name as city, " +
		"j.pay, "+
		"p.rating_promo, "+
		"j.mech " +
		"FROM projects p "+
		"RIGHT JOIN jobs j ON j.id_jobs=p.id_vac "+
		"left join city c ON c.id_city=j.id_city "+
		" WHERE p.id_promo="+user_id+
	" ORDER BY j.date_public
*/
    }

    /**
     * Full resume
     * @param int $id_user
     * @param int $resume_id
     *
     * @return mixed
     */
    public function getPromoFull($id_user = 0, $resume_id = 0)
    {
        $filter = $resume_id==0 ? 'e.id_user = :id' : 'e.id = :id';
        $id = $resume_id==0 ? $id_user : $resume_id;
        $data = Yii::app()->db->createCommand()
            ->select("e.id_user, e.id, e.user_name, e.user_surname, e.id_city, DATE_FORMAT(e.birthday,'%d.%m.%Y') as birthday, e.aboutme, e.id_city, c.country_id, c.name as city_name, ct.name as country_name")
            ->from('resume e')
            ->join('city c', 'c.id_city=e.id_city')
            ->join('country ct', 'ct.id_country=c.country_id')
            ->where($filter, array(':id' => $id))
            ->limit(1)
            ->queryAll();

        $result = $data[0];

        // Get attributes
        $result['attr'] = Share::getUserAttrib($id_user);

        // Get User Position
        $list = Yii::app()->db->createCommand()
            ->select('id_attr, val')
            ->from('user_position')
            ->order('id')
            ->where('id_user=:id_user', array(':id_user'=>$id_user))
            ->queryAll();
        $lst_pos = [];
        foreach($list as $r) {
            $lst_pos[$r['id_attr']] = $r['val'];
        }

        $result['position'] = $lst_pos;

        // Get directory of position
        $result['lst_position'] = Share::getDirectory('position');


        // List of User langs
        $lst = Yii::app()->db->createCommand()
            ->select('id_lang')
            ->from('user_langs')
            ->order('id_lang')
            ->where('id_user=:id_user', array(':id_user'=>$id_user))
            ->queryAll();

        $arr = [];
        foreach($lst as $r) {
            $arr[$r['id_lang']] = 1;
        }

        $result['langs'] = $arr;
        $result['lst_langs'] = Share::getDirectory('langs');


        // Blocks - City
        $list = Yii::app()->db->createCommand()
            ->select('uc.id_city, c.name as city_name, uc.street, uc.addinfo')
            ->from('user_city uc')
            ->join('city c', 'c.id_city=uc.id_city')
            ->order('uc.id')
            ->where('uc.id_user=:id_user', array(':id_user'=>$id_user))
            ->queryAll();
        $result['blocks_city'] = $list;
        $cnt = count($list);
        for($i=0; $i<$cnt; $i++) {
            // Times
            $list = Yii::app()->db->createCommand()
                ->select('nday, timeb, timee')
                ->from('user_wtime')
                ->order('nday')
                ->where('id_user=:id_user and id_city=:id_city', array(':id_user'=>$id_user, ':id_city'=>$result['blocks_city'][$i]['id_city']))
                ->queryAll();
            $lst_time = [];
            foreach($list as $r) {
                $lst_time[$r['nday']] = array($r['timeb'], $r['timee']);
            }
            $result['blocks_city'][$i]['times'] = $lst_time;
            $result['blocks_cnt'] = $i+1;
        }
        // ............. end block ...........

        // Get directory of practice
        $result['lst_practice'] = Share::getDirectory('practice');

        // Get directory of color of hair
        $result['lst_clr_hair'] = Share::getDirectory('clr_hair');

        // Get directory of length of hair
        $result['lst_ln_hair'] = Share::getDirectory('ln_hair');

        // Get directory of color of eye
        $result['lst_clr_eye'] = Share::getDirectory('clr_eye');

        // Get directory of size of bust
        $result['lst_size_bst'] = Share::getDirectory('size_bst');

        // Get directory of size of waist
        $result['lst_waist'] = Share::getDirectory('waist');

        // Get directory of size of hips
        $result['lst_hips'] = Share::getDirectory('hips');

        // Get directory of educations
        $result['lst_edu'] = Share::getDirectory('edu');

        //print_r($result); die;
        return $result;
    }


    /**
     * Full resume, Update
     *
     * @param $id_user
     * @param $params
     */
    public function setPromoFull($id_user, $params) {
        $valid = array(
            'is_auto','height','is_med','is_man','photo','phone_mb','phone_ex','email','skype','vk_link','viber',
            'icq','other','city','city_pt','weight','pay',
            'practice', 'clr_hair', 'ln_hair', 'clr_eye', 'size_bst', 'hips', 'edu', 'waist'
        );
        /*
        $list = array(
            'practice', 'clr_hair', 'ln_hair', 'clr_eye', 'size_bst', 'hips', 'edu'
        );
        */

        $id_city = Share::checkCity($params['city_name']);

        // Update resume
        Yii::app()->db->createCommand()
            ->update('resume', array(
                'user_name' => $params['user_name'],
                'user_surname' => $params['user_surname'],
                'id_city' => $id_city,
                'aboutme' => $params['aboutme'],
                'birthday' => Share::dateFormatToMySql($params['birthday']),
            ), 'id_user=:id_user', array(':id_user' => $id_user));

        foreach($params as $key => $value) {
            if (in_array ($key, $valid)) {
                Yii::app()->db->createCommand()
                    ->update('user_attribs', array(
                        "val" => $value,
                    ), '`id_usr`=:id_user and `key`=:key', array(':id_user' => $id_user, ':key'=>$key));
            }
        }

        // Blocks
        $blocks_cnt = $params['blocks_cnt'];
        Yii::app()->db->createCommand()->delete('user_city', array('in', 'id_user', array($id_user)));
        Yii::app()->db->createCommand()->delete('user_wtime', array('in', 'id_user', array($id_user)));

        for($j=1; $j<=$blocks_cnt; $j++) {
            $city_id = Share::checkCity($params['city_name_'.$j]);
            Yii::app()->db->createCommand()
                ->insert('user_city', array(
                    "id_city" => $city_id,
                    "street" => $params['street_' . $j],
                    "addinfo" => $params['addinfo_' . $j],
                    "id_user" => $id_user
                ));

            // Time
            for ($i = 1; $i <= 7; $i++) {
                $k = $j*10+$i;
                if (isset($params['day_' . $k])) {
                    Yii::app()->db->createCommand()
                        ->insert('user_wtime', array(
                            "timeb" => Share::convTimetoMin($params['timeb_' . $k]),
                            "timee" => Share::convTimetoMin($params['timee_' . $k]),
                            'id_user' => $id_user,
                            'nday' => $i,
                            'id_city' => $city_id
                        ));
                }
            }
        }


        // Languages
        Yii::app()->db->createCommand()->delete('user_langs', array('in', 'id_user', array($id_user)));

        foreach($params['lang'] as $value) {
            Yii::app()->db->createCommand()
                ->insert('user_langs', array(
                    "id_user" => $id_user,
                    "id_lang" => $value,
                ));
        }

        // Position
        $lst_pos = Share::getDirectory('position');
        Yii::app()->db->createCommand()->delete('user_position', array('in', 'id_user', array($id_user)));
        foreach($lst_pos as $key => $value) {
            $id = $key;
            $v = 0;
            if($this->checkPos($params['position1'], $key)) {
                $v = 1;
            }
            if($this->checkPos($params['position2'], $key)) {
                if($v==1) {
                    $v = 3;
                } else {
                    $v = 2;
                }
            }

            if($v > 0) {
                Yii::app()->db->createCommand()
                    ->insert('user_position', array(
                        "id_user" => $id_user,
                        "id_attr" => $id,
                        "val" => $v,
                    ));
            }

        }
    }

    public function searchPromo($params)
    {
        $filter = '1=1';
        $photo = empty($params['photo']) ? null : 1;

        $filter_pos = '1 as cnt_pos';
        if(!empty($params['id_city'])) {
            $filter.=' AND c.id_city in ('.implode(',',$params['id_city']).')';
        }

        if(!empty($params['position'])) {
            $filter_pos = '(select count(*) from user_position where id_user=e.id_user and id_attr in ('.implode(',',$params['position']).')) as cnt_pos ';
        }

        $data = Yii::app()->db->createCommand()
            ->select("e.id_user, e.id, e.user_name, e.user_surname, e.id_city, DATE_FORMAT(e.birthday,'%d.%m.%Y') as birthday, e.aboutme,
            e.id_city, c.country_id, c.name as city_name, ct.name as country_name, 1 as attr, 1 as filter_attr, ".$filter_pos)
            ->from('resume e')
            ->join('city c', 'c.id_city=e.id_city')
            ->join('country ct', 'ct.id_country=c.country_id')
            ->where($filter, array())
            ->queryAll();


        $fpromo = [];
        for($i=0; $i<count($data); $i++) {
            if($data[$i]['cnt_pos'] > 0) $fpromo[] = $data[$i];
        }

        $iswoman = !empty($params['iswoman']) ? true : false;
        $isman = !empty($params['isman']) ? true : false;

        for($i=0; $i<count($fpromo); $i++) {
            $fpromo[$i]['attr'] = Share::getUserAttrib($fpromo[$i]['id_user']);

            if($photo) {
                if(empty($fpromo[$i]['attr']['photo'])) {
                    $fpromo[$i]['filter_attr'] = 0;
            }}


            if( !empty($isman) || !empty($iswoman) ) {
                if( !empty($isman) && !empty($iswoman) ) {
                    // nothing, exit (man and woman)
                } else {
                    // man or woman
                    if(empty($isman) && $fpromo[$i]['attr']['iswoman']==false && !empty($iswoman)) {
                        $fpromo[$i]['filter_attr'] = 0;
                    }
                    if(empty($params['iswoman']) && $fpromo[$i]['attr']['iswoman']==false && !empty($isman)) {
                        $fpromo[$i]['filter_attr'] = 0;
                    }

                }
            }

        }
        // Filter by attributes
        $fa_vac = [];
        for($i=0; $i<count($fpromo); $i++) {
            if($fpromo[$i]['filter_attr'] > 0) $fa_vac[] = $fpromo[$i];
        }

        return $fa_vac;
    }

    private function checkPos($arr, $val)
    {
            foreach ($arr as $value) {
                if ($value == $val) {
                    return true;
                }
            }
            return false;
    }

}