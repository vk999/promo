<?php
$lst_position = Share::getDirectory('position');
$photo = !empty($params['photo']) ? 'checked' : '';
$isman          = !empty($data['attr']['isman']) ? $data['attr']['isman'] : '';
$iswoman        = !empty($data['attr']['iswoman']) ? $data['attr']['iswoman'] : '';
?>

<form name="PromoForm" method="post" >
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
        <tr>
            <td>С фото</td>
            <td><input type="checkbox" name="photo[]" value="1" <?php echo $photo;?> ></td>
        </tr>
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
    </table>

    <input type="submit" value="Search"/>
</form>

    <hr/>
    <h4>РЕЗУЛЬТАТЫ ПОИСКА</h4>
<?php

if(!empty($data)) {
    foreach ($data as $row) {
        echo '<b>Соискатель</b><br/>';
        //$isman = !empty($row['isman']) ? 'Юноши' : '';
        //$iswoman = !empty($row['iswoman']) ? 'Девушки' : '';
        //echo "$isman / $iswoman <br/>";
        //echo "Город: ".$row['city_name']."</br>";
        //echo "Вид работы: ".$lst_empl_typ[$row['attr']['empl_typ']]."</br>";
        echo "ФИО: ". $row['user_name']." ". $row['user_surname']."</br>";
        echo "фото: ". $row['attr']['photo']."</br>";
        echo "<hr>";
    }
}
?>