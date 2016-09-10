<?php
/**
 * Created by PhpStorm.
 * User: sl
 * Date: 11.04.2016
 * Time: 22:03
 */
?>
<form name="PromoForm" method="post" >
    <input type="hidden" name="search" value="1">
    <table style="background-color: #EEEEEE">
        <tr>
            <td width=300"><input type="text" name="search" id="search"></td>
            <td>
                <select name="stype" id="stype">
                    <option value="vacation" selected>Вакансии</option>
                    <option value="resume">Резюме</option>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="button" id="fast_search" value="do Ajax (3 chars)" onclick="fastSearch()">
                <input type="button" id="filter" value="НАЙТИ">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div id="dvres"></div>
            </td>
        </tr>
    </table>
</form>

<script>
    function fastSearch() {
        $("#dvres").html('');
        var address = "/ajax/FastSearch?";
        $.ajax({
            type: 'GET',
            url: address + 'stype=' + $("#stype").val() + "&search="+$("#search").val(),
            cache: false,
            dataType: 'text',
            success: function (data) {
                $("#dvres").html(data);
            },
            error: function (data) {
                alert("Download error!");
                $("#btrt_"+id).attr("disabled", "false");
            }
        });
    }
</script>
