<?php
/**
 * Created by PhpStorm.
 * User: sl
 * Date: 18.04.2016
 * Time: 21:26
 */
?>
<h4>Отзыв на соискателя (от работодателя)</h4>
<form name="RatingEmpl" method="post" >
    <input type="hidden" name="resp" value="1">
    <table style="background-color: #EEEEEE">
        <tr>
            <td width=300">Promo ID</td>
            <td>
                <input type="text" name="id_user" id="id_user" value="4">
            </td>
        </tr>
        <tr>
            <td width=300">Vacation ID</td>
            <td>
                <input type="text" name="id_vacation" id="id_vacation" value="1">
            </td>
        </tr>
    </table>
    <table>
        <tr>
            <td>Оценка</td>
            <td>
                <?php
                foreach($data as $row) {
                    echo '<select name="point_'.$row['id'].'" style="width:70px">';
                    echo '<option value="-'.$row['value'].'">-'.$row['value'].'</option>';
                    echo '<option selected value="0">0</option>';
                    echo '<option value="'.$row['value'].'">'.$row['value'].'</option>';
                    echo '</select>';
                    echo $row['descr']."<br/>\r\n";
                }
                ?>
            </td>
        </tr>
    </table>
    <input type="submit" value="Оценить">
</form>