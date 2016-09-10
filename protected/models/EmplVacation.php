<?php

/**
 * Created by PhpStorm.
 * User: sldeb
 * Date: 18.03.2016
 * Time: 10:11
 */
class EmplVacation
{
    public function  getVacation($id_vac)
    {
        $vac = Yii::app()->db->createCommand()
            ->select("e.id, e.id_user, e.title, e.requirements, e.duties, e.conditions, e.rem_ispublic,
             DATE_FORMAT(e.rem_date,'%d.%m.%Y') as rem_date,
             DATE_FORMAT(e.date_begin,'%d.%m.%Y') as date_begin,
             DATE_FORMAT(e.date_end,'%d.%m.%Y') as date_end,
             e.isman, e.iswoman, e.contact_info, e.is_contact_reg")
            ->from('empl_vacations e')
            ->where('e.id=:id_vac', array(':id_vac' => $id_vac))
            ->limit(1)
            ->queryAll();
        $result = $vac[0];

        // Get Positions
        $list = Yii::app()->db->createCommand()
            ->select('id_attr, val')
            ->from('empl_position')
            ->where('id_vacation=:id_vac', array(':id_vac' => $id_vac))
            ->queryAll();
        $lst_pos = [];
        foreach ($list as $r) {
            $lst_pos[$r['id_attr']] = $r['val'];
        }

        // Get directory of position
        $result['lst_position'] = Share::getDirectory('position');
        $result['position'] = $lst_pos;

        // Get attributes
        $result['attr'] = Share::getEmplAttrib($id_vac);

        // Blocks - City
        $list = Yii::app()->db->createCommand()
            ->select('uc.id_city, c.name as city_name, uc.street')
            ->from('empl_city uc')
            ->join('city c', 'c.id_city=uc.id_city')
            ->where('uc.id_vac=:id_vac', array(':id_vac' => $id_vac))
            ->queryAll();
        $result['blocks_city'] = $list;
        $result['blocks_cnt'] = count($list);

        // Get directory of Employer type
        $result['lst_empl_typ'] = Share::getDirectory('empl_typ');

        // Get directory of Salary type
        $result['lst_tpsalary'] = Share::getDirectory('tpsalary');


        // List of User langs
        $lst = Yii::app()->db->createCommand()
            ->select('id_lang')
            ->from('empl_langs')
            ->order('id_lang')
            ->where('id_vacations=:id_vac', array(':id_vac' => $id_vac))
            ->queryAll();

        $arr = [];
        foreach ($lst as $r) {
            $arr[$r['id_lang']] = 1;
        }

        $result['langs'] = $arr;
        $result['lst_langs'] = Share::getDirectory('langs');

        return $result;
    }

    public function setEmplVacation($id_vac, $params)
    {
        $valid = array(
            'other_po', 'empl_typ', 'tpsalary', 'salary', 'age_from', 'age_to',
        );
        $valid_check = array(
            'is_med' => 0,
            'is_car' => 0,
            'is_exp' => 0
        );

        // Update empl_vacations
        $rem_ispublic = empty($params['rem_ispublic'][0]) ? 0 : 1;
        $isman = empty($params['isman'][0]) ? 0 : 1;
        $iswoman = empty($params['iswoman'][0]) ? 0 : 1;
        $is_contact_reg = empty($params['is_contact_reg'][0]) ? 0 : 1;
        Yii::app()->db->createCommand()
            ->update('empl_vacations', array(
                'title' => $params['title'],
                'requirements' => $params['requirements'],
                'duties' => $params['duties'],
                'conditions' => $params['conditions'],
                'rem_ispublic' => $rem_ispublic,
                'rem_date' => Share::dateFormatToMySql($params['rem_date']),
                'date_begin' => Share::dateFormatToMySql($params['date_begin']),
                'date_end' => Share::dateFormatToMySql($params['date_end']),
                'isman' => $isman,
                'iswoman' => $iswoman,
                'contact_info' => $params['contact_info'],
                'is_contact_reg' => $is_contact_reg
            ), 'id=:id_vac', array(':id_vac' => $id_vac));

        // Update attributes
        foreach ($params as $key => $value) {
            if (in_array($key, $valid)) {
                Yii::app()->db->createCommand()
                    ->update('empl_attribs', array(
                        "val" => $value,
                    ), '`id_vac`=:id_vac and `key`=:key', array(':id_vac' => $id_vac, ':key' => $key));
            }
        }
        // Update attributes (checkboxes)
        foreach ($valid_check as $key => $value) {
            if (!empty($params[$key][0])) {
                $value = $params[$key][0];
            }
            Yii::app()->db->createCommand()
                ->update('empl_attribs', array(
                    "val" => $value,
                ), '`id_vac`=:id_vac and `key`=:key', array(':id_vac' => $id_vac, ':key' => $key));
        }
        // Update languages
        Yii::app()->db->createCommand()->delete('empl_langs', array('in', 'id_vacations', array($id_vac)));

        foreach ($params['lang'] as $value) {
            Yii::app()->db->createCommand()
                ->insert('empl_langs', array(
                    "id_vacations" => $id_vac,
                    "id_lang" => $value,
                ));
        }

        // Position
        Yii::app()->db->createCommand()->delete('empl_position', array('in', 'id_vacation', array($id_vac)));

        if (!empty($params['position'])) {
            foreach ($params['position'] as $key) {
                $id = $key;

                Yii::app()->db->createCommand()
                    ->insert('empl_position', array(
                        "id_vacation" => $id_vac,
                        "id_attr" => $id,
                        "val" => 1
                    ));
            }
        }

        // Blocks
        $blocks_cnt = $params['blocks_cnt'];
        Yii::app()->db->createCommand()->delete('empl_city', array('in', 'id_vac', array($id_vac)));

        for ($j = 1; $j <= $blocks_cnt; $j++) {
            $city_id = Share::checkCity($params['city_name_' . $j]);
            Yii::app()->db->createCommand()
                ->insert('empl_city', array(
                    "id_city" => $city_id,
                    "street" => $params['street_' . $j],
                    "id_vac" => $id_vac
                ));
        }
    }

    public function searchVacation($params, $limit = 250, $id_promo=0)
    {
        $filter = "date_begin<=now() and date_end>=now()";
        if (!empty($params['id_city'])) {
            $filter .= ' AND c.id_city in (' . implode(',', $params['id_city']) . ')';
        }

        $filter_pos = '1 as cnt_pos';
        if (!empty($params['position'])) {
            $filter_pos = '(select count(*) from empl_position where id_vacation=e.id and id_attr in (' . implode(',', $params['position']) . ')) as cnt_pos ';
        }

        $vac = Yii::app()->db->createCommand()
            ->select("e.id, e.id_user, c.id_city, cn.name as city_name, e.title, e.requirements, e.duties, e.conditions, e.rem_ispublic,
             DATE_FORMAT(e.rem_date,'%d.%m.%Y') as rem_date,
             DATE_FORMAT(e.date_begin,'%d.%m.%Y') as date_begin,
             DATE_FORMAT(e.date_end,'%d.%m.%Y') as date_end,
             e.isman, e.iswoman, e.contact_info, e.is_contact_reg, 1 as attr, 1 as filter_attr, em.company_name, 0 as isresp, " . $filter_pos)
            ->from('empl_vacations e')
            ->join('empl_city c', 'c.id_vac = e.id')
            ->join('city cn', 'cn.id_city = c.id_city')
            ->join('employer em', 'em.id_user = e.id_user')
            ->where($filter)
            ->limit($limit)
            ->queryAll();

        $resp = Yii::app()->db->createCommand()
            ->select("id_vacation")
            ->from('vacation_stat')
            ->where("id_promo=:id_promo", array(":id_promo"=>$id_promo))
            ->queryAll();

        $fvac = [];
        for ($i = 0; $i < count($vac); $i++) {
            if ($vac[$i]['cnt_pos'] > 0) $fvac[] = $vac[$i];
        }

        for ($i = 0; $i < count($fvac); $i++) {
            $on = false;
            foreach ($resp as $r) {
                if ($r['id_vacation'] == $fvac[$i]['id']) {
                    $on = true;
                }
            }
            if ($on) {
                $fvac[$i]['isresp'] = 1;
            }

            $fvac[$i]['attr'] = Share::getEmplAttrib($fvac[$i]['id']);

            if (!empty($params['empl_typ'])) {
                if ($params['empl_typ'] != $fvac[$i]['attr']['empl_typ']) {
                    $fvac[$i]['filter_attr'] = 0;
                }
            }


            if (!($params['salary_from'] <= $fvac[$i]['attr']['salary'] && $params['salary_to'] >= $fvac[$i]['attr']['salary'])) {
                $fvac[$i]['filter_attr'] = 0;
            }

            if (!empty($params['tpsalary'])) {
                if ($params['tpsalary'] != $fvac[$i]['attr']['tpsalary']) {
                    $fvac[$i]['filter_attr'] = 0;
                }
            }

            if (!($params['age_from'] >= $fvac[$i]['attr']['age_from'] && $params['age_to'] <= $fvac[$i]['attr']['age_to'])) {
                $fvac[$i]['filter_attr'] = 0;
            }

        }

        // Filter by attributes
        $fa_vac = [];
        for ($i = 0; $i < count($fvac); $i++) {
            if ($fvac[$i]['filter_attr'] > 0) $fa_vac[] = $fvac[$i];
        }

        return $fa_vac;
    }


    public function  getVacationsByEmpl($id_empl)
    {
        $vac = Yii::app()->db->createCommand()
            ->select("e.id, e.title")
            ->from('empl_vacations e')
            ->where('e.id_user=:id_user', array(':id_user' => $id_empl))
            ->queryAll();
        return $vac;
    }
}