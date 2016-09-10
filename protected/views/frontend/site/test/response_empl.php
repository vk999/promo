<?php
$resp_map = array(
    "Новая вакансия",
    "отклик просмотрен",
    "профиль просмотрен",
    "Отклонено",
    "Принято",
    "Обоюдное согласие"
)
?>
<h4>ОТКЛИКНУТЬСЯ НА СОИСКАТЕЛЯ</h4>
<form name="ResponsePromo" method="post" >
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
        <tr>
            <td colspan="2">
                <?php
                 $mode = isset($data['isresponse']) ? $data['isresponse'] : 0;
                 //echo "mode=$mode";
                 echo '<input type="hidden" name="status" id="status" value="'.$mode.'">';
                 if ($mode==0) {
                     echo '<input type="submit"  value="Просмотреть">';
                 }
                if ($mode==1) {
                    echo '<input type="submit"  value="Просмотреть профиль">';
                }
                if ($mode==2) {
                    echo '<input type="submit"  value="Отклонить" name="btn_submit" id="btn_submit">';
                    echo '<input type="button"  value="Принять" onclick="accept_ok()">';
                }
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="background-color: #00d1ff; ">
                Текущий статус : <?php
                if($mode>-1) echo $resp_map[$mode];
                ?>
            </td>
        </tr>
    </table>
</form>

<script>
    function accept_ok() {
        $("#status").val(3);
        $("#btn_submit").click();
    }
</script>
