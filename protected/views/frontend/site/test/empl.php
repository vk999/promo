<h1>Test Employer</h1>
<?php
$id_user        = !empty($data['id_user']) ? $data['id_user'] : '0';
//$id_resume      = !empty($data['id']) ? $data['id'] : '0';
$company_name   = !empty($data['company_name']) ? $data['company_name'] : '';
$type           = !empty($data['type']) ? $data['type'] : '';
$user_name      = !empty($data['user_name']) ? $data['user_name'] : '';
$user_surname   = !empty($data['user_surname']) ? $data['user_surname'] : '';
$birthday       = !empty($data['birthday']) ? $data['birthday'] : '';
$city_name      = !empty($data['city_name']) ? $data['city_name'] : '';
$country_name   = !empty($data['country_name']) ? $data['country_name'] : '';

$web            = !empty($data['attr']['web']) ? $data['attr']['web'] : '';
$logo           = !empty($data['attr']['logo']) ? $data['attr']['logo'] : '';
$user_name      = !empty($data['user_name']) ? $data['user_name'] : '';
$user_surname   = !empty($data['user_surname']) ? $data['user_surname'] : '';
$phone_mb       = !empty($data['attr']['phone_mb']) ? $data['attr']['phone_mb'] : '';
$position       = !empty($data['attr']['position']) ? $data['attr']['position'] : '';
$is_news        = !empty($data['attr']['is_news']) ? $data['attr']['is_news'] : '0';


?>
<form name="EmplForm" method="post" >
    <input type="hidden" name="blocks_cnt" value="<?php echo $data['blocks_cnt']; ?>">
    <table style="background-color: #FFFFC0">
        <tr>
            <td width=300">ID работодателя (hidden)</td>
            <td><input type="text" name="id_user" id="id_user" value="<?php echo $id_user; ?>"/></td>
        </tr>

    </table>



    <h4>ОСНОВНАЯ ИНФОРМАЦИЯ</h4>
    <table style="background-color: #E0E0C0">
        <tr>
            <td width=300">Название компании</td>
            <td><input type="text" name="company_name" id="company_name" value="<?php echo $company_name; ?>"/></td>
        </tr>
        <tr>
            <td>Тип компании</td>
            <td><?php Share::GenerateDropDownList('type', $data['lst_company_type'], $type); ?></td>
        </tr>

        <tr>
            <td>Страна</td>
            <td><input type="text" name="country_name" id="country_name" value="<?php echo $country_name; ?>"/><?php echo "ID=".$data['country_id'];?></td>
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
            <tr>
                <td>Другое</td>
                <td><input type="text" name="addinfo_'.$i.'" id="addinfo" value="'.$r['addinfo'].'"/></td>
            </tr></table>';
            $i++;
            }
        ?>
            </td></tr>
        <tr>
            <td>Web сайт</td>
            <td><input type="text" name="web" id="web" value="<?php echo $web; ?>"/></td>
        </tr>

        <tr>
            <td>Логотип (фото url)</td>
            <td><input type="text" name="logo" id="logo2" value="<?php echo $logo; ?>"/></td>
        </tr>

    </table>
    <h4>КОНТАКТНАЯ ИНФОРМАЦИЯ</h4>
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
            <td>Моб. Телефон</td>
            <td><input type="text" name="phone_mb" id="phone_mb" value="<?php echo $phone_mb; ?>"/></td>
        </tr>

        <tr>
            <td>Ваша должность</td>
            <td><input type="text" name="position" id="position" value="<?php echo $position; ?>"/></td>
        </tr>

        <tr>
            <td>ПОЛУЧАТЬ НОВОСТИ ОБ ИЗМЕНЕНИЯХ И НОВЫХ ВОЗМОЖНОСТЯХ НА САЙТЕ</td>
            <td>
                <?php
                $key = $is_news;
                $select = ($is_news==1) ? 'checked' : '';
                echo '<input type="checkbox" name="is_news[]" value="1" ' . $select . ' />' ;
                ?>
            </td>
        </tr>
    </table>

    <input type="submit" value="Update"/>
</form>
