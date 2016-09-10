<?php
$id             = !empty($data['id']) ? $data['id'] : '0';
$id_user        = !empty($data['id_user']) ? $data['id_user'] : '0';
$title          = !empty($data['title']) ? $data['title'] : '';
$requirements   = !empty($data['requirements']) ? $data['requirements'] : '';
$duties         = !empty($data['duties']) ? $data['duties'] : '';
$conditions     = !empty($data['conditions']) ? $data['conditions'] : '';
$rem_ispublic   = !empty($data['rem_ispublic']) ? $data['rem_ispublic'] : '';
$rem_date       = !empty($data['rem_date']) ? $data['rem_date'] : '';
$other_po       = !empty($data['attr']['other_po']) ? $data['attr']['other_po'] : '';
$empl_typ       = !empty($data['attr']['empl_typ']) ? $data['attr']['empl_typ'] : '';
$date_begin     = !empty($data['date_begin']) ? $data['date_begin'] : '';
$date_end       = !empty($data['date_end']) ? $data['date_end'] : '';
$tpsalary       = !empty($data['attr']['tpsalary']) ? $data['attr']['tpsalary'] : '';
$salary         = !empty($data['attr']['salary']) ? $data['attr']['salary'] : '';
$is_exp         = !empty($data['attr']['is_exp']) ? $data['attr']['is_exp'] : '';
$isman          = !empty($data['isman']) ? $data['isman'] : '';
$iswoman        = !empty($data['iswoman']) ? $data['iswoman'] : '';
$age_from       = !empty($data['attr']['age_from']) ? $data['attr']['age_from'] : '';
$age_to         = !empty($data['attr']['age_to']) ? $data['attr']['age_to'] : '';
$is_med         = !empty($data['attr']['is_med']) ? $data['attr']['is_med'] : '';
$is_car         = !empty($data['attr']['is_car']) ? $data['attr']['is_car'] : '';
$contact_info   = !empty($data['contact_info']) ? $data['contact_info'] : '';
$is_contact_reg  = !empty($data['is_contact_reg']) ? $data['is_contact_reg'] : '';
?>
<form name="EmplForm" method="post" >
    <input type="hidden" name="blocks_cnt" value="<?php echo $data['blocks_cnt']; ?>">
    <table style="background-color: #FFFFC0">
        <tr>
            <td width=300">ID вакансии (hidden)</td>
            <td><input type="text" name="id" id="id" value="<?php echo $id; ?>"/></td>
        </tr>
        <tr>
            <td width=300">ID работодателя (hidden)</td>
            <td><input type="text" name="id_user" id="id_user" value="<?php echo $id_user; ?>"/></td>
        </tr>
    </table>

    <table style="background-color: #E0E0C0">
        <tr>
            <td width=300">Заголовок вакансии *</td>
            <td><input type="text" name="title" id="title" style="width:600px;" value="<?php echo $title; ?>"/></td>
        </tr>
        <tr>
            <td width=300">Описание вакансии * :</td>
            <td>требования
                <TEXTAREA NAME="requirements" WRAP="virtual" COLS="40" ROWS="3" style="width:600px;"><?php echo $requirements; ?></TEXTAREA><br/>
                обязанности
                <TEXTAREA NAME="duties" WRAP="virtual" COLS="40" ROWS="3" style="width:600px;"><?php echo $duties; ?></TEXTAREA><br/>
                условия
                <TEXTAREA NAME="conditions" WRAP="virtual" COLS="40" ROWS="3" style="width:600px;"><?php echo $conditions; ?></TEXTAREA><br/>
            </td>
        </tr>

        <tr>
            <td width=300">АВТО СНЯТИЕ ВАКАНСИИ С ПУБЛИКАЦИИ</td>
            <td><?php
                $key = $rem_ispublic;
                $select = ($rem_ispublic==1) ? 'checked' : '';
                echo '<input type="checkbox" name="rem_ispublic[]" value="1" ' . $select . ' />' ;
                echo '<input type="text" name="rem_date" id="rem_date" value="'.$rem_date.'"/>';
                ?>
            </td>
        </tr>
        <tr>
            <td width="300">Должность</td>
            <td>
                <?php
                $position = $data['position'];
                $r = $data['lst_position'];
                foreach ($r as $key => $value) {
                    $select = '';
                    if(!empty($position[$key])) {
                        $select = ($position[$key] == 1) ? 'checked' : '';
                    }
                    echo '<input type="checkbox" name="position[]" value="'.$key.'" ' . $select . ' />'. $value . '<br/>';
                }

                ?>
            </td>
        </tr>
        <tr>
            <td width=300">Свой вариант (должность)</td>
            <td><input type="text" name="other_po" id="other_po" style="width:600px;" value="<?php echo $other_po; ?>"/></td>
        </tr>
        <tr><td colspan="2">
        <?php
        $i=1;
        foreach ($data['blocks_city'] as $r) {
            echo '<table style="background-color: #F0F0C0;">';
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
            </table>';
            $i++;
        }
        ?>
        </td></tr>
        <tr>
            <td>Вид занятости</td>
            <td><?php Share::GenerateDropDownList('empl_typ', $data['lst_empl_typ'], $empl_typ); ?></td>
        </tr>

        <tr>
            <td>Дата начала работы</td>
            <td><input type="text" name="date_begin" id="date_begin"  value="<?php echo $date_begin; ?>"/></td>
        </tr>
        <tr>
            <td>Дата завершения работы</td>
            <td><input type="text" name="date_end" id="date_end"  value="<?php echo $date_end; ?>"/></td>
        </tr>
        <tr>
            <td>Заработная плата</td>
            <td><?php Share::GenerateDropDownList('tpsalary', $data['lst_tpsalary'], $tpsalary); ?></td>
        </tr>
        <tr>
            <td>Заработная плата (сума)</td>
            <td><input type="text" name="salary" id="salary"  value="<?php echo $salary; ?>"/></td>
        </tr>
        <tr>
            <td>Опыт работы (0/1)</td>
            <td>
                <?php
                $select = ($is_exp==1) ? 'checked' : '';
                echo '<input type="checkbox" name="is_exp[]" value="1" ' . $select . ' />' ;
                ?>
            </td>
        </tr>
        <tr>
            <td>ЮНОШИ (0/1)</td>
            <td>
                <?php
                $select = ($isman==1) ? 'checked' : '';
                echo '<input type="checkbox" name="isman[]" value="1" ' . $select . ' />' ;
                ?>
            </td>
        </tr>
        <tr>
            <td>ДЕВУШКИ (0/1)</td>
            <td>
                <?php
                $select = ($iswoman==1) ? 'checked' : '';
                echo '<input type="checkbox" name="iswoman[]" value="1" ' . $select . ' />' ;
                ?>
            </td>
        </tr>
        <tr>
            <td>Возраст с</td>
            <td><input type="text" name="age_from" id="age_from"  value="<?php echo $age_from; ?>"/></td>
        </tr>
        <tr>
            <td>Возраст по</td>
            <td><input type="text" name="age_to" id="age_to"  value="<?php echo $age_to; ?>"/></td>
        </tr>

        <tr>
            <td>НАЛИЧИЕ МЕДКНИЖКИ (0/1)</td>
            <td>
                <?php
                $select = ($is_med==1) ? 'checked' : '';
                echo '<input type="checkbox" name="is_med[]" value="1" ' . $select . ' />' ;
                ?>
            </td>
        </tr>

        <tr>
            <td>НАЛИЧИЕ АВТОМОБИЛЯ (0/1)</td>
            <td>
                <?php
                $select = ($is_car==1) ? 'checked' : '';
                echo '<input type="checkbox" name="is_car[]" value="1" ' . $select . ' />' ;
                ?>
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
            <td width=300">Контактная информация:</td>
            <td> <TEXTAREA NAME="contact_info" WRAP="virtual" COLS="40" ROWS="3" style="width:600px;"><?php echo $contact_info; ?></TEXTAREA><br/>
            </td>
        </tr>
        <tr>
            <td>ПУБЛИКОВАТЬ КОНТАКТНЫЕ ДАННЫЕ (0/1)</td>
            <td>
                <?php
                $select = ($is_contact_reg==1) ? 'checked' : '';
                echo '<input type="checkbox" name="is_contact_reg[]" value="1" ' . $select . ' />' ;
                ?>
            </td>
        </tr>
    </table>

    <input type="submit" value="Update"/>
</form>