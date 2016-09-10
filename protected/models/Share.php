<?php

class Share
{
    public static $arr_word = array();
    public static $arr_setup = array();
    public static $arr_lang = array();
    public static $lang = null;
    public static $dict = null;

    public static $empl_type = array(0 => 'не важно', 1 => 'временная', 2 => 'постоянная');

    public function getLangAjax()
    {
        $lang = 'ru';
        if (isset($_GET['lang'])) {
            $lg = $_GET['lang'];
            if ($lg == 'ru' || $lg == 'en') $lang = $lg;
        }
        return $lang;
    }

    public function getLang()
    {
        $lang = 'ru';
        //print_r($_POST);die;
        if (isset($_POST['field_lang'])) {
            $lg = $_POST['field_lang'];
            if ($lg == 'ru' || $lg == 'en') $lang = $lg;
        } else {
            if (isset($_GET['lang'])) {
                $lg = $_GET['lang'];
                if ($lg == 'ru' || $lg == 'en') $lang = $lg;
            }
        }
        return $lang;
    }

    public function getMenuTypeAjax()
    {
        $mtype = 1;
        if (isset($_GET['menu_type'])) {
            if (is_numeric($_GET['menu_type'])) {
                $mtype = $_GET['menu_type'];
            }
        }
        return $mtype;
    }


    public function getMenuType()
    {
        $mtype = 1;
        if (isset($_POST['field_menu_type'])) {
            if ($_POST['field_menu_type']) $mtype = $_POST['field_menu_type'];
        }
        return $mtype;
    }

    // ������� �������������� ����.
    public static function data_convert($data, $year, $time, $second)
    {
        $res = "";
        $part = explode(" ", $data);
        $ymd = explode("-", $part[0]);
        $hms = explode(":", $part[1]);
        if ($year == 1) {
            $res .= $ymd[2];
            $res .= "." . $ymd[1];
            $res .= "." . $ymd[0];
        }
        if ($time == 1) {
            $res .= " " . $hms[0];
            $res .= ":" . $hms[1];
            if ($second == 1) $res .= ":" . $hms[2];
        }
        return $res;
    }

    //��������� URI
    public static function getModuleName()
    {
        $url = Yii::app()->getUrlManager()->parseUrl(Yii::app()->getRequest());
        $arr = explode('/', $url);
        return $arr;
    }

    public static function CheckName($text)
    {
        if (empty($text))
            return "[?]";
        else
            return $text;
    }


    public static function getLanguages($page_name, $lang)
    {
        $res = Yii::app()->db->createCommand()
            ->select('id, keyword, value, page, lang')
            ->from('languages')
            ->where('page=:page and lang=:lang', array(':page' => $page_name, ':lang' => $lang))
            ->queryAll();

        $arr = array();
        foreach ($res as $row) {
            $arr[$row['keyword']] = $row['value'];
        }
        self::$arr_word = $arr;
        return $arr;
    }

    public static function lword($key)
    {
        if (isset(self::$arr_word[$key]))
            return self::$arr_word[$key];
        else
            return "***";
    }


    public static function getLangBtn()
    {
        $res = Yii::app()->db->createCommand()
            ->select('name, title')
            ->from('lang')
            ->queryAll();

        return $res;
    }


    //-----CONFIG------
    public static function setup($key)
    {
        if (self::$arr_setup == null) self::config();
        return self::$arr_setup[$key];
    }

    public static function getExtend($filename)
    {
        //$ext = pathinfo($filename, PATHINFO_EXTENSION);
        $pos = strpos($filename, '.');
        $pos2 = strpos($filename, '/');
        if ($pos2 > 0)
            $ext = substr($filename, $pos + 1, $pos2 - $pos - 1);
        else
            $ext = substr($filename, $pos + 1);
        return $ext;
    }

    public static function config()
    {
        $url = $_SERVER['HTTP_HOST'];
        $zone = self::getExtend($url);
        $file = $_SERVER['DOCUMENT_ROOT'] . '/protected/config/setup_' . $zone . '.ini';
        $r = array();
        if ($F = fopen($file, "r")) {
            while (($line = fgets($F)) !== false) {
                list($k, $v) = explode("\t", $line, 2);
                $r[trim($k)] = trim($v);
            }
            fclose($F);
        }
        self::$arr_setup = $r;
        return $r;
    }


    //-----LANG---------
    public static function getLangSelected()
    {
        $lang = Yii::app()->session['lang'];
        if (empty($lang)) {
            $lang = self::setup('LANG_DEFAULT');
            Yii::app()->session['lang'] = self::setup('LANG_DEFAULT');
        }
        if (self::$lang != $lang) {
            self::$lang = $lang;
            self::getLangs();
        }
        //self::$lang = $lang;
        return $lang;
    }

    public static function lng($key)
    {
        if (self::$lang == null) self::getLangSelected();
        if (self::$arr_lang == null) self::getLangs();
        //print_r(self::$arr_lang);
        return self::$arr_lang[$key];
    }


    // �������� ������������ (����������� ����� VK, FB, ������������� ...)
    public static function getCID($cid, $cname)
    {
        Yii::app()->session['cid'] = $cid;
        Yii::app()->session['cname'] = $cname;

        $res = Yii::app()->db->createCommand()
            ->select('cid,cname,id_user,email')
            ->from('user')
            ->where('cid=:cid and cname=:cname', array(':cid' => $cid, ':cname' => $cname))
            ->limit(1)
            ->queryRow();
        return $res;
    }


    public static function getLangs()
    {
        $file = $_SERVER['DOCUMENT_ROOT'] . '/langs/' . self::$lang . '.dat';
        $r = array();
        if ($F = fopen($file, "r")) {
            while (($line = fgets($F)) !== false) {
                list($k, $v) = explode("\t", $line, 2);
                $r[trim($k)] = trim($v);
            }
            fclose($F);
        }
        self::$arr_lang = $r;
        return $r;
    }
    //-------------------

    /*
    public static function getUserID()
    {
       $cookie_uid = '';
       if(isset(Yii::app()->request->cookies['uid'])){
          $cookie_uid = Yii::app()->request->cookies['uid']->value;
       }
       if($cookie_uid=='') return 0;
            $r = Yii::app()->db->createCommand()
          ->select("id_user")
          ->from('user_work')
          ->where("uid = :uid", array(':uid'=>$cookie_uid))
          ->queryRow();
       return $r['id_user'];
    }
    */

    /**  ����� ����������
     */
    public static function getTotalAnketsOfResume()
    {
        // ����� ���-�� ����� � ������
        $total_promo = 0;
        if (!isset(Yii::app()->session['total_promo'])) {
            $total_promo = Yii::app()->db->createCommand()
                ->select('count(*) as cnt')
                ->from('resume')
                ->queryScalar();
        } else {
            $total_promo = Yii::app()->session['total_promo'];
        }
        return $total_promo;
    }

    public static function getTotalAnketsOfVacation()
    {
        // ����� ���-�� ����� � ����������
        $total_vac = 0;
        if (!isset(Yii::app()->session['total_vac'])) {
            $total_vac = Yii::app()->db->createCommand()
                ->select('count(*) as cnt')
                ->from('jobs')
                ->where('ispublic=1')
                ->queryScalar();
        } else {
            $total_vac = Yii::app()->session['total_vac'];
        }
        return $total_vac;
    }


    public static function setProtected($msg)
    {
        $msg = str_replace("[u]", "<u>", $msg);
        $msg = str_replace("[U]", "<u>", $msg);
        $msg = str_replace("[i]", "<i>", $msg);
        $msg = str_replace("[I]", "<i>", $msg);
        $msg = str_replace("[b]", "<B>", $msg);
        $msg = str_replace("[B]", "<B>", $msg);
        $msg = str_replace("[sub]", "<SUB>", $msg);
        $msg = str_replace("[SUB]", "<SUB>", $msg);
        $msg = str_replace("[sup]", "<SUP>", $msg);
        $msg = str_replace("[SUP]", "<SUP>", $msg);
        $msg = str_replace("[/u]", "</u>", $msg);
        $msg = str_replace("[/U]", "</u>", $msg);
        $msg = str_replace("[/i]", "</i>", $msg);
        $msg = str_replace("[/I]", "</i>", $msg);
        $msg = str_replace("[/b]", "</B>", $msg);
        $msg = str_replace("[/B]", "</B>", $msg);
        $msg = str_replace("[/SUB]", "</SUB>", $msg);
        $msg = str_replace("[/sub]", "</SUB>", $msg);
        $msg = str_replace("[/SUP]", "</SUP>", $msg);
        $msg = str_replace("[/sup]", "</SUP>", $msg);
        //$msg = eregi_replace("(.*)\\[url\\](.*)\\[/url\\](.*)","\\1<a href=\\2>\\2</a>\\3",$msg);
        $msg = str_replace("\n", " ", $msg);
        $msg = str_replace("\r", " ", $msg);
        $msg = str_replace("'", '"', $msg);
        return $msg;
    }

    public static function getPageNames($arrLinks)
    {

        $sql = "SELECT p.id, p.link, pc.name FROM pages p LEFT JOIN pages_content pc ON pc.page_id=p.id WHERE pc.lang='ru' AND (";

        foreach ($arrLinks as $link) {
            $sql .= " OR p.link like '$link'";
        }

        $sql .= ")";

        $sql = str_replace('AND ( OR', 'AND (', $sql);

        $res = Yii::app()->db->createCommand($sql)->queryAll();

        // ��������� ������������� ������
        $arr = array();

        foreach ($res as $r) {
            $arr[$r['link']] = $r['name'];
        }

        return $arr;
    }

    public static function PrintMechJson()
    {
        $res = Yii::app()->db->createCommand()
            ->select("key, val as name")
            ->from('dictionary')
            ->where("grp='MECH'")
            ->queryAll();
        echo "var mechInfo = {";
        foreach ($res as $r) {
            echo '"' . $r["key"] . '":"' . $r["name"] . '", ';
        }
        echo "end:1};\r\n";
    }


    public static function getUserID($uid)
    {
        $id_user = Yii::app()->db->createCommand()
            ->select('id_user')
            ->from('user_work')
            ->where('`uid`=:uid', array(':uid' => $uid))
            ->queryScalar();
        return $id_user;
    }

    public static function checkCity($city)
    {
        $city_new = mb_strtoupper(mb_substr($city, 0, 1,'UTF-8'), 'UTF-8') . mb_strtolower(mb_substr($city, 1, strlen($city)-1, 'UTF-8'), 'UTF-8');
        $id_city = 0;
        $id_city = Yii::app()->db->createCommand()
            ->select('id_city')
            ->from('city')
            ->where('`name`=:name', array(':name' => $city_new))
            ->queryScalar();
/*
        if (empty($id_city)) {
            Yii::app()->db->createCommand()
                ->insert('city', array(
                    'name' => $city_new,
                ));
            $id_city = Yii::app()->db->getLastInsertID();
        }
*/
        return $id_city;
    }

    public static function dateFormatToMySql($date) {
        $dt = explode (".", $date);
        return date('Y-m-d', strtotime($dt[2].'-'.$dt[1].'-'.$dt[0]));
    }

    public static function initDict() {
        if (!empty(self::$dict)) {
            return false;
        } else {
            $res = Yii::app()->db->createCommand()
                ->select("id, key")
                ->from('user_attr_dict')
                ->where("id_par=:parent", array(':parent'=>0))
                ->order('key')
                ->queryAll();

            self::$dict = [];
            foreach($res as $r) {
                if( !empty($r['key'])) {
                    self::$dict[$r['key']] = $r['id'];
                }
            }
        }
        return true;
    }

    public static function getDirectory($dict_name) {
        self::initDict();
        $parent_id = self::$dict[$dict_name];
        $res = Yii::app()->db->createCommand()
            ->select("id, key, value")
            ->from('user_attr_dict')
            ->where("id_par=:parent", array(':parent'=>$parent_id))
            ->order('id')
            ->queryAll();

       // print_r($res);die;
        $lst = [];
        foreach($res as $r) {
            $lst[$r['id']] = $r['value'];
        }
        return $lst;
    }

    public static function convMintoHour($time) {
        $min = $time % 60;
        $time = floor($time / 60);
        return str_pad($time, 2, '0', STR_PAD_LEFT).':'.str_pad($min, 2, '0', STR_PAD_LEFT);
    }

    public static function convTimetoMin($time) {
        $arr = explode(':', $time);
        $min = $arr[0] * 60 + $arr[1];
        return $min;
    }

    public static function getUserAttrib($id_user) {
        // Read attributes
        $list = Yii::app()->db->createCommand()
            ->select('id, key, val')
            ->from('user_attribs')
            ->order('id')
            ->where('id_usr=:id_user', array(':id_user'=>$id_user))
            ->queryAll();
        $lst = [];
        foreach($list as $r) {
            $lst[$r['key']] = $r['val'];
        }
        return $lst;
    }

    public static function getEmplAttrib($id_vac) {
// Read attributes
        $list = Yii::app()->db->createCommand()
            ->select('id, key, val')
            ->from('empl_attribs')
            ->order('id')
            ->where('id_vac=:id', array(':id'=>$id_vac))
            ->queryAll();
        $lst = [];
        foreach($list as $r) {
            $lst[$r['key']] = $r['val'];
        }
        return $lst;
    }

    public static function GenerateDropDownList($lst_name, $r, $id_selected) {
        echo '<select name="'. $lst_name. '" id="'. $lst_name. '">';
        $i=1;
        foreach ($r as $key => $value) {
           $select = ($id_selected == $key) ? 'selected' : '';
           echo '<option id="'.$lst_name.'_'.$i++.'" value="'.$key.'" ' . $select . '>' . $value . '</option>';
        }
        echo '</select>';
    }

    public static function FindPositions($search_pos) {
        // Get Positions
        $lst_position = self::getDirectory('position');
        $search_pos = mb_strtolower($search_pos);
        foreach($lst_position as $key=>$value) {
            $pos = strpos (mb_strtolower($value), $search_pos);
            //echo "$pos $value <br>";
            if( substr_count (mb_strtolower($value), $search_pos) >0 ) {
                return $key;
            }
        }
        return null;
    }

    public static function findCities($cities) {
        $arr_city = mb_split(" ", $cities);
        $res = [];
        foreach($arr_city as $c) {
            $id_city = self::checkCity($c);
            if($id_city>0) {$res[] = $id_city;}
            //$res[] = self::checkCity($c);
        }
        //$res = substr($res, 0, strlen($res)-1);
        //echo "<h1>".implode(',',$res)."</h1>";
        //die;
        return $res;
    }

}

?>