<?php
$lst_cmp_typ = Share::getDirectory('cmp_type');

$company_name = !empty($params['company_name']) ? $params['company_name'] : '';
$cmp_typ  = !empty($params['cmp_typ']) ? $params['cmp_typ'] : 0;
?>

<h4>ПОИСК РАБОТОДАТЕЛЕЙ</h4>
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
        <tr>
            <td>Тип компании</td>
            <td><?php Share::GenerateDropDownList('cmp_typ', $lst_cmp_typ, $cmp_typ); ?></td>
        </tr>
        <tr>
            <td>Название работодателя</td>
            <td><input type="text" name="company_name" style="width:300px" value="<?php echo $company_name; ?>"/>
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
        echo '<b>'.$row['company_name'].'</b><br/>';
        echo "Город: ".$row['city_name']."</br>";
        echo "Метро: ".$row['city_name']."</br>";
        echo "Рейтинг: +".$row['rate']." ".$row['rate_neg']."</br>";
        echo '<input type="button" value="detail" id="btrt_'.$row['id_user'].'" onclick="getRating('.$row['id_user'].')"><div id="dvrt_'.$row['id_user'].'"></div></br>';
        echo "Тип работодателя: ".$lst_cmp_typ[$row['cmp_typ']]."</br>";
        echo "<hr>";
    }
}
?>

<script>
    function getRating(id) {
        $("#btrt_"+id).attr("disabled", "true");
        var address = "/ajax/GetRating?";
        $.ajax({
            type: 'GET',
            url: address + 'id=' + id,
            cache: false,
            dataType: 'text',
            success: function (data) {
                $("#dvrt_"+id).html(data);
            },
            error: function (data) {
                alert("Download error!");
                $("#btrt_"+id).attr("disabled", "false");
            }
        });
    }
</script>
