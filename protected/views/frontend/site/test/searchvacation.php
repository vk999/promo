<?php
// справочники
$lst_empl_typ = Share::getDirectory('empl_typ');
$lst_tpsalary = Share::getDirectory('tpsalary');
$lst_position = Share::getDirectory('position');
$lst_empl_typ = Share::getDirectory('empl_typ');
$empl_typ = !empty($params['empl_typ']) ? $params['empl_typ'] : 0;
$salary_from = !empty($params['salary_from']) ? $params['salary_from'] : 0;
$salary_to = !empty($params['salary_to']) ? $params['salary_to'] : 1000;
$tpsalary = !empty($params['tpsalary']) ? $params['tpsalary'] : 0;
$age_from = !empty($params['age_from']) ? $params['age_from'] : 18;
$age_to = !empty($params['age_to']) ? $params['age_to'] : 30;
?>
<h4>ПОИСК ВАКАНСИЙ</h4>
<form name="EmplForm" method="post" >
    <input type="hidden" name="search" value="1">
    <table style="background-color: #EEEEEE">
        <tr>
            <td width=300">Город</td>
            <td>
                <input type="checkbox" name="id_city[]" value="4400"/>Москва<br/>
                <input type="checkbox" name="id_city[]" value="4330"/>Внуково<br/>
                <input type="checkbox" name="id_city[]" value="4325"/>Быково<br/>
                <input type="checkbox" name="id_city[]" value="4348"/>Дубна<br/>
            </td>
        </tr>
        <tr><td colspan="2"><hr></td></tr>
        <tr>
            <td width="300">Должность</td>
            <td>
                <?php
                $position = !empty($params['position']) ? $params['position'] : [];
                foreach ($lst_position as $key => $value) {
                    $select = '';
                    foreach($position as $p) {
                        if($p == $key) $select = 'checked';
                    }
                    echo '<input type="checkbox" name="position[]" value="'.$key.'" ' . $select . ' />'. $value . '<br/>';
                }

                ?>
            </td>
        </tr>
        <tr>
            <td>Вид занятости</td>
            <td><?php Share::GenerateDropDownList('empl_typ', $lst_empl_typ, $empl_typ); ?></td>
        </tr>
        <tr>
            <td>Заработная плата</td>
            <td><?php Share::GenerateDropDownList('tpsalary', $lst_tpsalary, $tpsalary); ?></td>
        </tr>

        <tr>
            <td>Заработная плата (сума)</td>
            <td>с <input type="text" name="salary_from"  value="<?php echo $salary_from; ?>"/>
            по <input type="text" name="salary_to"  value="<?php echo $salary_to; ?>"/>
            </td>
        </tr>

        <tr>
            <td>Возраст</td>
            <td>с <input type="text" name="age_from" id="age_from"  value="<?php echo $age_from; ?>"/>
                до <input type="text" name="age_to" id="age_to"  value="<?php echo $age_to; ?>"/>
            </td>
        </tr>

    </table>



<input type="submit" value="Search"/>
</form>

<hr/>
<h4>РЕЗУЛЬТАТЫ ПОИСКА</h4>
<?php

if(!empty($data)) {
    foreach ($data as $row) {
        if($row['isresp']) {
            echo '<div style="width:400px; border: #404040 1px solid; margin-bottom: 10px; background: #A0B0FF">';
        } else {
            echo '<div style="width:400px; border: #404040 1px solid; margin-bottom: 10px;">';
        }
        echo '<b>Промоутер</b><br/>';
        $isman = !empty($row['isman']) ? 'Юноши' : '';
        $iswoman = !empty($row['iswoman']) ? 'Девушки' : '';
        echo "$isman / $iswoman <br/>";
        echo "Город: ".$row['city_name']."</br>";
        echo "Вид работы: ".$lst_empl_typ[$row['attr']['empl_typ']]."</br>";
        echo "Оплата: ". $row['attr']['salary']." ". $lst_tpsalary[$row['attr']['tpsalary']]."</br>";
        echo "Период: с ".$row['date_begin']." по ".$row['date_end']."</br>";
        echo $row['company_name']."</br>";
        echo '</div>';
    }
}
?>