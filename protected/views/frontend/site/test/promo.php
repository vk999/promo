<h4>Тест Промоутер</h4>
<?php
$id_user        = !empty($data['id_user']) ? $data['id_user'] : '0';
$id_resume      = !empty($data['id']) ? $data['id'] : '0';
$user_name      = !empty($data['user_name']) ? $data['user_name'] : '';
$user_surname   = !empty($data['user_surname']) ? $data['user_surname'] : '';
$birthday       = !empty($data['birthday']) ? $data['birthday'] : '';
$city_name      = !empty($data['city_name']) ? $data['city_name'] : '';
$country_name   = !empty($data['country_name']) ? $data['country_name'] : '';
$photo          = !empty($data['attr']['photo']) ? $data['attr']['photo'] : '';
$is_med          = !empty($data['attr']['is_med']) ? $data['attr']['is_med'] : '0';
$is_auto         = !empty($data['attr']['is_auto']) ? $data['attr']['is_auto'] : '0';
$is_man          = !empty($data['attr']['is_man']) ? $data['attr']['is_man'] : '0';

$phone_mb       = !empty($data['attr']['phone_mb']) ? $data['attr']['phone_mb'] : '';
$phone_ex       = !empty($data['attr']['phone_ex']) ? $data['attr']['phone_ex'] : '';
$email          = !empty($data['attr']['email']) ? $data['attr']['email'] : '';
$skype          = !empty($data['attr']['skype']) ? $data['attr']['skype'] : '';
$vk_link        = !empty($data['attr']['vk_link']) ? $data['attr']['vk_link'] : '';
$icq            = !empty($data['attr']['icq']) ? $data['attr']['icq'] : '';
$viber          = !empty($data['attr']['viber']) ? $data['attr']['viber'] : '';
$other          = !empty($data['attr']['other']) ? $data['attr']['other'] : '';

$city_pt        = !empty($data['attr']['city_pt']) ? $data['attr']['city_pt'] : '';
$street         = !empty($data['attr']['street']) ? $data['attr']['street'] : '';
$other_pt       = !empty($data['attr']['other_pt']) ? $data['attr']['other_pt'] : '';

$practice       = !empty($data['attr']['practice']) ? $data['attr']['practice'] : '0';

$height         = !empty($data['attr']['height']) ? $data['attr']['height'] : '0';
$weight         = !empty($data['attr']['weight']) ? $data['attr']['weight'] : '0';
$size           = !empty($data['attr']['size']) ? $data['attr']['size'] : '0';
$nday = array(
    1 => 'Пн', 2 => 'Вт', 3 => 'Ср', 4 => 'Чт', 5 => 'Пт', 6 => 'Сб', 7 => 'Вс'
);

$clr_hair       = !empty($data['attr']['clr_hair']) ? $data['attr']['clr_hair'] : '0';
$ln_hair        = !empty($data['attr']['ln_hair']) ? $data['attr']['ln_hair'] : '0';
$clr_eye        = !empty($data['attr']['clr_eye']) ? $data['attr']['clr_eye'] : '0';
$size_bst       = !empty($data['attr']['size_bst']) ? $data['attr']['size_bst'] : '0';
$waist          = !empty($data['attr']['waist']) ? $data['attr']['waist'] : '0';
$hips           = !empty($data['attr']['hips']) ? $data['attr']['hips'] : '0';
$edu            = !empty($data['attr']['edu']) ? $data['attr']['edu'] : '0';
$aboutme        = !empty($data['aboutme']) ? $data['aboutme'] : '';
$pay            = !empty($data['attr']['pay']) ? $data['attr']['pay'] : '0';

/*
$size_list = array(
    0 => '--',
    1 => '40(xs)',
    2 => '42(s)',
    4 => '44(m)',
    8 => '46(l)',
    16 => '48(xl)'
);
$work_twd_start = !empty($data['work_twd_start']) ? $data['work_twd_start'] : '';
$work_twd_end = !empty($data['work_twd_end']) ? $data['work_twd_end'] : '';

$work_twe_start = !empty($data['work_twe_start']) ? $data['work_twe_start'] : '';
$work_twe_end = !empty($data['work_twe_end']) ? $data['work_twe_end'] : '';
$id_city = !empty($data['id_city']) ? $data['id_city'] : '';
$isexpirience = !empty($data['isexpirience']) ? $data['isexpirience'] : '0';
$mech = !empty($data['mech']) ? $data['mech'] : '';
$hpay = !empty($data['hpay']) ? $data['hpay'] : '';
$iswinter = !empty($data['iswinter']) ? $data['iswinter'] : '0';
$ready_work = !empty($data['ready_work']) ? $data['ready_work'] : '';
$aboutme = !empty($data['aboutme']) ? $data['aboutme'] : '';
$language = !empty($data['language']) ? $data['language'] : '';
$lang_level = !empty($data['lang_level']) ? $data['lang_level'] : '';
*/

?>
<form name="PromoForm" method="post" >
    <input type="hidden" name="blocks_cnt" value="<?php echo $data['blocks_cnt']; ?>">
    <table style="background-color: #FFFFC0">
        <tr>
            <td width=300">ID соискателя (hidden)</td>
            <td><input type="text" name="id_user" id="id_user" value="<?php echo $id_user; ?>"/></td>
        </tr>

        <tr>
            <td>ID резюме (hidden)</td>
            <td><input type="text" name="id_resume" id="id_resume" value="<?php echo $id_resume; ?>"/></td>
        </tr>
    </table>



        <h4>ОСНОВНАЯ ИНФОРМАЦИЯ</h4>
    <table style="background-color: #E0E0C0">
        <tr>
            <td width=300">Имя</td>
            <td><input type="text" name="user_name" id="user_name" value="<?php echo $user_name; ?>"/></td>
        </tr>

        <tr>
            <td>Фамилия</td>
            <td><input type="text" name="user_surname" id="user_surname" value="<?php echo $user_surname; ?>"/></td>
        </tr>

        <tr>
            <td>Дата рождения</td>
            <td><input type="text" name="birthday" id="birthday" value="<?php echo $birthday; ?>"/></td>
        </tr>

        <tr>
            <td>Страна</td>
            <td><input type="text" name="country_name" id="country_name" value="<?php echo $country_name; ?>"/><?php echo "ID=".$data['country_id'];?></td>
        </tr>

        <tr>
            <td>Город</td>
            <td><input type="text" name="city_name" id="city_name" value="<?php echo $city_name; ?>"/><?php echo "ID=".$data['id_city'];?></td>
        </tr>

        <tr>
            <td>Фото (url)</td>
            <td><input type="text" name="photo" id="photo" value="<?php echo $photo; ?>"/></td>
        </tr>

        <tr>
            <td>Наличие мед. книжки (0/1)</td>
            <td><input type="text" name="is_med" id="is_med" value="<?php echo $is_med; ?>"/></td>
        </tr>

        <tr>
            <td>Наличие автомобиля (0/1)</td>
            <td><input type="text" name="is_auto" id="is_auto" value="<?php echo $is_auto; ?>"/></td>
        </tr>


        <tr>
            <td>Пол (0/1)</td>
            <td><input type="text" name="is_man" id="is_man" value="<?php echo $is_man; ?>"/></td>
        </tr>

    </table>




    <h4>КОНТАКТНАЯ ИНФОРМАЦИЯ</h4>
    <table style="background-color: #E0E0C0">
        <tr>
            <td width="300">Моб. Телефон</td>
            <td><input type="text" name="phone_mb" id="phone_mb" value="<?php echo $phone_mb; ?>"/></td>
        </tr>
        <tr>
            <td>Доп. Моб. Телефон</td>
            <td><input type="text" name="phone_ex" id="phone_ex" value="<?php echo $phone_ex; ?>"/></td>
        </tr>

        <tr>
            <td>Электронная почта</td>
            <td><input type="text" name="email" id="email" value="<?php echo $email; ?>"/></td>
        </tr>

        <tr>
            <td>Skype</td>
            <td><input type="text" name="skype" id="skype" value="<?php echo $skype; ?>"/></td>
        </tr>

        <tr>
            <td>Страница ВКОНТАКТЕ (сылка)</td>
            <td><input type="text" name="vk_link" id="vk_link" value="<?php echo $vk_link; ?>"/></td>
        </tr>

        <tr>
            <td>ICQ</td>
            <td><input type="text" name="icq" id="icq" value="<?php echo $icq; ?>"/></td>
        </tr>

        <tr>
            <td>Viber (0/1)</td>
            <td><input type="text" name="viber" id="viber" value="<?php echo $viber; ?>"/></td>
        </tr>

        <tr>
            <td>Другое</td>
            <td><input type="text" name="other" id="other" value="<?php echo $other; ?>"/></td>
        </tr>
    </table>

    <h4>ЦЕЛЕВАЯ ВАКАНСИЯ</h4>
    <table style="background-color: #E0E0C0">
        <tr>
            <td width="300">Должность</td>
            <td>
                <?php
                $position = $data['position'];
                $r = $data['lst_position'];
                foreach ($r as $key => $value) {
                    $select = $select2 = '';
                    if(!empty($position[$key])) {
                        $select = ($position[$key] == 1) ? 'checked' : '';
                        $select2 = ($position[$key] == 2) ? 'checked' : '';
                        if($position[$key] == 3) {
                            $select = $select2 = 'checked';
                        }
                    }
                    echo '<input type="checkbox" name="position1[]" value="'.$key.'" ' . $select . ' />' ;
                    echo '<input type="checkbox" name="position2[]" value="'.$key.'" ' . $select2 . ' />' . $value . '<br/>';
                }

                ?>
            </td>
        </tr>
        <tr>
            <td>Опыт работы</td>
            <td><?php Share::GenerateDropDownList('practice', $data['lst_practice'], $practice); ?>
            </td>
        </tr>

        <tr>
            <td>Ожидаемая оплата</td>
            <td><input type="text" name="pay" id="pay" value="<?php echo $pay; ?>"/></td>
        </tr>

    </table>

    <h4>УДОБНОЕ МЕСТО И ВРЕМЯ РАБОТЫ</h4>
        <?php
        $i=1;
        foreach ($data['blocks_city'] as $r) {
            echo '<table style="background-color: #E0E0C0">';
            echo '
            <tr>
                <td width="300">Город</td>
                <td><input type="text" name="city_name_'.$i.'" id="city_pt" value="'.$r['city_name'].'"/>'.$r['id_city'].'
                <input type="hidden" name="city_origin_'.$i.'" value="'.$r['id_city'].'">
                </td>
            </tr>
            <tr>
                <td>Улица</td>
                <td><input type="text" name="street_'.$i.'" id="street" value="'.$r['street'].'"/></td>
            </tr>
            <tr>
                <td>Другое</td>
                <td><input type="text" name="addinfo_'.$i.'" id="addinfo" value="'.$r['addinfo'].'"/></td>
            </tr>';
        ?>
        <tr>
            <td>День недели</td>
            <td>
                <?php
                for($j=1; $j<=7; $j++)
                {
                    $k = $i*10 + $j;
                    echo $nday[$j].'&nbsp;&nbsp;';
                    if (!empty($r['times'][$j]) && $r['times'][$j][0]!=0) {
                        echo '<input type="checkbox" name="day_'.$k.'" value="'.$j.'" checked />&nbsp;&nbsp;с&nbsp;' ;
                        echo '<input type="text" name="timeb_'.$k.'" value="'.Share::convMintoHour( $r['times'][$j][0]).'"/>&nbsp;по&nbsp;';
                        echo '<input type="text" name="timee_'.$k.'" value="'.Share::convMintoHour( $r['times'][$j][1]).'"/><br/>';
                    } else {
                        echo '<input type="checkbox" name="day_'.$k.'" value="day' . $j . '" />&nbsp;&nbsp;с&nbsp;';
                        echo '<input type="text" name="timeb_' . $k . '" value=""/>&nbsp;по&nbsp;';
                        echo '<input type="text" name="timee_' . $k . '" value=""/><br/>';
                    }
                }
                ?>
            </td>
        </tr>
    </table>
    <?php
            $i++;
    }
    ?>

<h4>ВНЕШНИЕ ДАННЫЕ</h4>
    <table style="background-color: #E0E0C0">
        <tr>
            <td width="300">Рост (150-215)</td>
            <td><input type="text" name="height" id="height" value="<?php echo $height; ?>"/></td>
        </tr>

        <tr>
            <td>Вес (35-150)</td>
            <td><input type="text" name="weight" id="weight" value="<?php echo $weight; ?>"/></td>
        </tr>

        <tr>
            <td>Цвет волос</td>
            <td><?php Share::GenerateDropDownList('clr_hair', $data['lst_clr_hair'], $clr_hair); ?>
            </td>
        </tr>


        <tr>
            <td>Длина волос</td>
            <td><?php Share::GenerateDropDownList('ln_hair', $data['lst_ln_hair'], $ln_hair); ?>
            </td>
        </tr>

        <tr>
            <td>Цвет глаз</td>
            <td><?php Share::GenerateDropDownList('clr_eye', $data['lst_clr_eye'], $clr_eye); ?>
            </td>
        </tr>

        <tr>
            <td>Размер груди</td>
            <td><?php Share::GenerateDropDownList('size_bst', $data['lst_size_bst'], $size_bst); ?>
            </td>
        </tr>

        <tr>
            <td>Объем талии</td>
            <td><?php Share::GenerateDropDownList('waist', $data['lst_waist'], $waist); ?>
            </td>
        </tr>

        <tr>
            <td>Объем бедер</td>
            <td><?php Share::GenerateDropDownList('hips', $data['lst_hips'], $hips); ?>
            </td>
        </tr>

</table>

    <h4>ДОПОЛНИТЕЛЬНАЯ ИНФОРМАЦИЯ</h4>
    <table style="background-color: #E0E0C0">
        <tr>
            <td width=""300">Образование</td>
            <td><?php Share::GenerateDropDownList('edu', $data['lst_edu'], $edu); ?>
            </td>
        </tr>



        <tr>
            <td>Иностранные языки</td>
        <td>
            <?php
            $position = $data['langs'];
            $r = $data['lst_langs'];
            foreach ($r as $key => $value) {
                $select = '';
                if(!empty($position[$key])) {
                    $select = 'checked';
                }
                echo '<input type="checkbox" value="'.$key.'" name="lang[]" ' . $select . ' />' . $value . '<br/>';
            }
            ?>
        </td>
        </tr>


        <tr>
            <td>О себе</td>
            <td><TEXTAREA NAME="aboutme" WRAP="virtual" COLS="40" ROWS="3"><?php echo $aboutme; ?></TEXTAREA>
            </td>
        </tr>


<!--
        <tr>
            <td>Готовность работать в зимнее время на улице? (0/1)</td>
            <td><input type="text" name="iswinter" id="iswinter" value="echo iswinter; "/></td>
        </tr>
-->


        <tr>
            <!-- ********************** New fields ************************** -->
            <td colspan="2"><hr></td>
        </tr>


    </table>
    <input type="submit" value="Update"/>
</form>
