<?php
/**
 * Created by PhpStorm.
 * User: sl
 * Date: 24.04.2016
 * Time: 20:54
 */

class Chat {

    /**
     * Get all themes by user
     *
     * @param $id_user
     * @return mixed
     */
    public function getThemеByIdUser($id_user) {
        $lst_theme = Yii::app()->db->createCommand()
            ->select('c.id_thema, t.title')
            ->from('chat c')
            ->join('chat_thema t', 'c.id_thema=t.id')
            ->order('c.id')
            ->where('c.id_user=:id_user', array(':id_user'=>$id_user))
            ->group('c.id_thema')
            ->queryAll();
        return $lst_theme;
    }


    public function getThemеByEmpl($id_empl) {
        $lst_theme = Yii::app()->db->createCommand()
            ->select('t.id as id_thema, t.title')
            ->from('chat_thema t')
            ->order('t.id')
            ->where('t.id_vacation in (select id from empl_vacations where id_user=:id_user)', array(':id_user'=>$id_empl))
            ->queryAll();
        return $lst_theme;
    }

    /**
     * Get chat
     * @param $id
     * @param $id_theme
     *
     * @return mixed
     */
    public function getChatByUser($id_user, $id_theme) {
        $chat = Yii::app()->db->createCommand()
            ->select("c.id, c.id_empl, c.dt_create, c.is_resp, c.message, concat(r.user_name,' ', r.user_surname) as usr_name, e.company_name")
            ->from('chat c')
            ->join('resume r', 'r.id_user=c.id_user')
            ->join('employer e', 'e.id_user=c.id_empl')
            ->order('c.id')
            ->where('c.id_user=:id_user and c.id_thema=:id_thema', array(':id_user'=>$id_user, ':id_thema'=>$id_theme))
            ->queryAll();
        return $chat;
    }

    public function getChatByUserEmpl($id_user, $id_theme, $id_empl) {
        $chat = Yii::app()->db->createCommand()
            ->select("c.id, c.id_empl, c.dt_create, c.is_resp, c.message, concat(r.user_name,' ', r.user_surname) as usr_name, e.company_name")
            ->from('chat c')
            ->join('resume r', 'r.id_user=c.id_user')
            ->join('employer e', 'e.id_user=c.id_empl')
            ->order('c.id')
            ->where('c.id_user=:id_user and c.id_empl=:id_empl and c.id_thema=:id_thema', array(':id_user'=>$id_user, ':id_thema'=>$id_theme, ':id_empl'=>$id_empl))
            ->queryAll();
        return $chat;
    }

    public function getUserList($id_empl, $id_theme) {
        $chat = Yii::app()->db->createCommand()
            ->select("c.id_user, concat(r.user_name,' ', r.user_surname) as usr_name")
            ->from('chat c')
            ->join('resume r', 'r.id_user=c.id_user')
            ->order('c.id')
            ->where('c.id_empl=:id_empl and c.id_thema=:id_thema', array(':id_empl'=>$id_empl, ':id_thema'=>$id_theme))
            ->group('c.id_user')
            ->queryAll();
        return $chat;
    }

    public function AddMessByUser($id, $id_theme, $id_empl, $message)
    {
        Yii::app()->db->createCommand()
            ->insert('chat', array(
                "id_user" => $id,
                "id_empl" => $id_empl,
                "id_thema" => $id_theme,
                "is_resp" => 0,
                "message" => $message
            ));
    }

    public function AddMessByUserEmpl($id, $id_theme, $id_empl, $message)
    {
        Yii::app()->db->createCommand()
            ->insert('chat', array(
                "id_user" => $id,
                "id_empl" => $id_empl,
                "id_thema" => $id_theme,
                "is_resp" => 1,
                "message" => $message
            ));
    }

    public function AddThemeByEmpl($id_vac, $title)
    {
        Yii::app()->db->createCommand()
            ->insert('chat_thema', array(
                "id_vacation" => $id_vac,
                "title" => $title
            ));
    }
}