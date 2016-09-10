<?php
$resp_map = array(
    "Новая вакансия",
    "Ваш отклик просмотрен работодателем",
    "Ваш профиль просмотрен работодателем",
    "Отклонено",
    "Принято",
    "Обоюдное согласие"
)
?>
<h4>ОТКЛИКНУТЬСЯ НА ВАКАНСИЮ</h4>
<form name="ResponsePromo" method="post" >
    <input type="hidden" name="resp" value="1">
    <table style="background-color: #EEEEEE">
        <tr>
            <td width=300">User ID</td>
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
        <tr>
            <td colspan="2">
                <?php
                 $mode = isset($data['isresponse']) ? $data['isresponse'] : -1;
                 echo '<input type="hidden" name="status" id="status" value="'.$mode.'">';
                 if ($mode==-1) {
                     echo '<input type="submit"  value="Откликнуться">';
                     $mode=0;
                 }
                if ($mode==4) {
                    echo '<input type="submit"  value="Подтвердить">';
                }
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="background-color: #00d1ff; ">
                Текущий статус : <?php echo $resp_map[$mode]; ?>
            </td>
        </tr>
    </table>
</form>
