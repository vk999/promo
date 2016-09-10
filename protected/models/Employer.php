<?php

/**
 * Created by PhpStorm.
 * User: wind
 * Date: 12.02.16
 * Time: 22:34
 */
class Employer
{

    public function addUserEmployer(
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
        $isman)
    {
        $id_user = 0;
        $id_city = 0;

        //$birthday = ConvDate($birthday) . toString();

        Yii::app()->db->createCommand()
            ->insert('user', array(
                'login' => $login,
                'passw' => md5($passw),
                'email' => $email,
                'access_time' => date('Y-m-d H:i:s'),
                'status' => 3,
                'isblocked' => 1,
                'cid' => '',
                'cname' => '',
            ));

        $id_user = Yii::app()->db->getLastInsertID();

        $id_city = Share::checkCity($city);

        Yii::app()->db->createCommand()
            ->insert('employer', array(
                'id_user' => $id_user,
                'id_city' => $id_city,
                'birthday' => Share::dateFormatToMySql($birthday),
                'education_type' => 0,
                'email' => $email,
                'photo' => $photo,
                'aboutme' => $interests,
                'fio' => $fio,
                'company_name' => $company,
                'web' => $www,
                'position' => $position,
                'phone1' => $phone1,
                'phone2' => $phone2,
                'id_ra' => $ra_id,
                'isman' => $isman,
                'avg_rating' => 0,
            ));

        return $id_user;
    }


    public function updateUserEmployer(
        $id_user,
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
        $isman)
    {
        $id_city = Share::checkCity($city);

        Yii::app()->db->createCommand()
            ->update('employer', array(
                'id_city' => $id_city,
                'birthday' => Share::dateFormatToMySql($birthday),
                'email' => $email,
                'photo' => $photo,
                'aboutme' => $interests,
                'fio' => $fio,
                'company_name' => $company,
                'web' => $www,
                'position' => $position,
                'phone1' => $phone1,
                'phone2' => $phone2,
                'id_ra' => $ra_id,
                'isman' => $isman,
            ), 'id_user=:id_user', array(':id_user' => $id_user));

        // Check updating login or password
        $user = Yii::app()->db->createCommand()
            ->select('login, passw')
            ->from('user')
            ->where('`id_user`=:id_user', array(':id_user' => $id_user))
            ->queryAll();

        if (!empty($passw)) {
            if ($user[0]['login'] != $login || $user[0]['passw'] != md5($passw)) {
                Yii::app()->db->createCommand()
                    ->update('user', array(
                        'login' => $login,
                        'passw' => md5($passw),
                    ), 'id_user=:id_user', array(':id_user' => $id_user));
            }
        }

        return $id_user;
    }

    public function getEmployer($id_user)
    {
        $user = Yii::app()->db->createCommand()
            ->select("e.id_user, e.company_name, e.type, e.user_name, e.user_surname")
            ->from('employer e')
            ->where('`id_user`=:id_user', array(':id_user' => $id_user))
            ->limit(1)
            ->queryAll();
        $result = $user[0];

        // Get directory of color of hair
        $result['lst_company_type'] = Share::getDirectory('cmp_type');

        // Get attributes
        $result['attr'] = Share::getUserAttrib($id_user);

        // Blocks - City
        $list = Yii::app()->db->createCommand()
            ->select('uc.id_city, c.name as city_name, c.country_id, uc.street, uc.addinfo')
            ->from('user_city uc')
            ->join('city c', 'c.id_city=uc.id_city')
            ->order('uc.id')
            ->where('uc.id_user=:id_user', array(':id_user'=>$id_user))
            ->queryAll();
        $result['blocks_city'] = $list;
        $result['blocks_cnt'] = count($list);

        // Country
        $list = Yii::app()->db->createCommand()
            ->select('id_country, name')
            ->from('country')
            ->where('id_country=:id_country', array(':id_country'=>$result['blocks_city'][0]['country_id']))
            ->limit(1)
            ->queryAll();
        $result['country_id'] = $list[0]['id_country'];
        $result['country_name'] = $list[0]['name'];
        return $result;
    }


    public function setEmplFull($id_user, $params)
    {
        $valid = array(
            'logo','web','phone_mb','position','is_news'
        );

        // Update empoyer profiler
        Yii::app()->db->createCommand()
            ->update('employer', array(
                'company_name' => $params['company_name'],
                'user_name' => $params['user_name'],
                'user_surname' => $params['user_surname'],
                'type'=>$params['type']
            ), 'id_user=:id_user', array(':id_user' => $id_user));

        Yii::app()->db->createCommand()
            ->update('user_attribs', array(
                "val" => "0",
            ), '`id_usr`=:id_user and `key`=:key', array(':id_user' => $id_user, ':key'=>'is_news'));

        foreach($params as $key => $value) {
            if (in_array ($key, $valid)) {
                if($key == 'is_news') {
                    $value = $value[0];
                }
                Yii::app()->db->createCommand()
                    ->update('user_attribs', array(
                        "val" => $value,
                    ), '`id_usr`=:id_user and `key`=:key', array(':id_user' => $id_user, ':key'=>$key));
            }
        }

        // Blocks
        $blocks_cnt = $params['blocks_cnt'];
        Yii::app()->db->createCommand()->delete('user_city', array('in', 'id_user', array($id_user)));

        for($j=1; $j<=$blocks_cnt; $j++) {
            $city_id = Share::checkCity($params['city_name_' . $j]);
            Yii::app()->db->createCommand()
                ->insert('user_city', array(
                    "id_city" => $city_id,
                    "street" => $params['street_' . $j],
                    "addinfo" => $params['addinfo_' . $j],
                    "id_user" => $id_user
                ));
        }
        //print_r($params);
    }

    public function searchEmployer($params)
    {
        $filter = '1=1';
        if(!empty($params['id_city'])) {
            $filter.=' AND c.id_city in ('.implode(',',$params['id_city']).')';
        }

        if(!empty($params['company_name'])) {
            $filter.=" AND e.company_name like '%".$params['company_name']."%'";
        }

        $empl = Yii::app()->db->createCommand()
            ->select("e.id, e.id_user, c.id_city, cn.name as city_name, e.user_name, e.user_surname, e.rate, e.rate_neg, e.type as cmp_typ, e.company_name")
            ->from('employer e')
            ->join('user_city c','c.id_user = e.id_user')
            ->join('city cn','cn.id_city = c.id_city')
            ->where($filter)
            ->limit(250)
            ->group('id_user, id_city')
            ->queryAll();

        for($i=0; $i<count($empl); $i++) {
            $empl[$i]['attr'] = Share::getUserAttrib($empl[$i]['id_user']);
        }

        return $empl;
    }

    public function getEmplRating($id_user)
    {
        $id_user = intval($id_user); // int protect
        $sql = "select sum(m.rate) as rate, sum(m.rate_neg) as rate_neg, m.id_point, m.descr from (
select
CASE WHEN rd.point>=0 THEN rd.point else 0 end as rate,
CASE WHEN rd.point<0 THEN rd.point else 0 end as rate_neg,
rd.id_point, r.descr
from rating_details rd, point_rating r
where is_promo=1 and id_vacation in (select id from empl_vacations where id_user=$id_user)
and r.id = rd.id_point
) m group by m.id_point";
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        return $res;
    }

} 