<?php
/**
 * Created by PhpStorm.
 * User: sl
 * Date: 13.04.2016
 * Time: 22:19
 */

class Response {

    public function getStatus($id_promo, $id_vacation) {
        $resp = Yii::app()->db->createCommand()
            ->select("*")
            ->from('vacation_stat')
            ->where("id_promo=:id_promo and id_vacation=:id_vacation", array(":id_promo"=>$id_promo, ":id_vacation"=>$id_vacation))
            ->limit(1)
            ->queryRow();
        //print_r($resp); die;
        return $resp;
    }



    public function setStatus($id_promo, $id_vacation, $status) {
        if ($status == -1) {
            $command = Yii::app()->db->createCommand();
            $command->insert('vacation_stat', array(
                'id_promo'=>$id_promo,
                'id_vacation'=>$id_vacation,
                'isresponse'=>0
            ));
        } else {
            $status += 1;
            if($status<=5) {
                Yii::app()->db->createCommand()
                    ->update('vacation_stat', array(
                        'isresponse' => $status
                    ), 'id_promo=:id_promo and id_vacation=:id_vacation', array(':id_promo' => $id_promo, ':id_vacation' => $id_vacation));
            }
        }
    }
}