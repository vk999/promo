<?php

/**
 * Created by PhpStorm.
 * User: wind
 * Date: 11.02.16
 * Time: 22:23
 */
class Auth
{

    public function Authorize($login, $passw)
    {
        if(get_magic_quotes_gpc()==1)
        {
            $login = !empty($login) ? stripslashes(trim($login)) : '';
            $passw = !empty($passw) ? stripslashes(trim($passw)) : '';
        }
        /*
        $login = !empty($login) ? mysql_real_escape_string($login) : '';
        $passw = !empty($passw) ? mysql_real_escape_string($passw) : '';
        */
        $sql = "select id_user, status from user where login='$login' and passw=md5('$passw') limit 1;";
        $res = Yii::app()->db->createCommand($sql)->queryAll();
        $token = '';
        $uid = '';
        $status = 0;

        if (!empty($res) && $res[0]['id_user'] > 0) {
            $status = $res[0]['status'];
            // Generate TOKEN
            $token = md5($login . date("d.m.Y") . md5($passw));
            $uid = md5($res[0]['id_user']);

            $id_user = Yii::app()->db->createCommand()
                ->select('id')
                ->from('user_work')
                ->where('`id_user`=:id_user', array(':id_user' => $res[0]['id_user']))
                ->queryScalar();

            if ($id_user > 0) {
                $res = Yii::app()->db->createCommand()
                    ->update('user_work', array(
                        'token' => $token,
                        'date_login' => date('Y-m-d H:i:s'),
                    ), 'id_user=:id_user', array(':id_user' => $id_user));
            } else {
                $res = Yii::app()->db->createCommand()
                    ->insert('user_work', array(
                        'token' => $token,
                        'uid' => $uid,
                        'id_user' => $res[0]['id_user'],
                        'date_login' => date('Y-m-d H:i:s'),
                    ));
            }

        }

        return array(
            "access_token" => $token,
            "uid" => $uid,
            "type" => $status,
            "login" => $login,
            "rating" => 0,
            "count_resp" => 0
        );
    }


    /**
     * User activation
     * return id_user
     *
     * @param $token
     * @return int
     */
    public function CheckTokenActivate($token) {
        $res = Yii::app()->db->createCommand()
            ->select("id_user, token, status")
            ->from('user_activate')
            ->where('token = :uid', array(':uid' => $token))
            ->queryRow();

        $user = array('id'=>0, 'type'=>0);

        if (!empty($res['id_user'])) {
            $user['id'] = $res['id_user'];
            $user['type'] = $res['status'];
        }

        return $user;
    }


    public function SetActivate($id_user) {
        Yii::app()->db->createCommand()
            ->update('user', array(
                'isblocked' => 3,
            ), 'id_user=:id_user', array(':id_user' => $id_user));

        Yii::app()->db->createCommand()
            ->delete('user_activate', 'id_user=:id_user', array(':id_user' => $id_user));

    }

    /*
     * ====== Table user, field isblocked ==========
     * 0 - полностью активен
     * 1 - заблокирован
     * 2 - ожидает активации
     * 3 - активирован, но еще не заполнил все необходимые поля
     * ...............................................
     * 0 - fully active
     * 1 - blocked
     * 2 - it awaiting activation
     * 3 - it activated, but has not filled out all required fields
     */

} 