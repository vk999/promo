<style>
    .small {font-family: "Helvetica", monospace; font-size: 8pt; font-weight: bold; }
    .user .block1 {width: 90%; background-color: #20DD20}
    .empl {text-align: right}
    .empl .block1 {width: 90%; background-color: #90FF90; margin-left: 100px;}
</style>

<h4>ЛИЧНЫЙ КАБИНЕТ. ПЕРЕПИСКА</h4>
<p>ID соискателя
    <input type="text" name="id_user" id="id_user" value="4">
</p>
<hr>

<h4>Тема:</h4>
<div id="theme"></div>
<hr>
<h4 id="theme_title"></h4>
<div id="chat"></div>
<textarea id="add_mess"></textarea>
<a href="#" onclick="add_mess()">Отправить</a>;
<script>
    var theme_list;
    var id_theme;
    var index;
    var id_empl;

    function getThemebyUser() {
        var id = $("#id_user").val();
        //$("#btrt_"+id).attr("disabled", "true");
        var address = "/ajax/Get_theme_by_user?";
        $.ajax({
            type: 'GET',
            url: address + 'id=' + id,
            cache: false,
            dataType: 'json',
            success: function (data) {
               ShowTheme(data)
            },
            error: function (data) {
                alert("Download error!");
            }
        });
    }

    function ShowTheme(data) {
        this.theme_list = data;
        var html = [];
        for (var i = 0; i < data.length; i++) {
            html.push('<a href="#" onclick="GetChat(',  data[i]["id_thema"], ',', i, ')">', data[i]["title"], '</a><br/>');
        }
        $('#theme').html(html.join(''));
    }

    function ShowChat(data) {
        this.id_empl = data[0]["id_empl"];
        var html = [];
        for (var i = 0; i < data.length; i++) {
            if(data[i]["is_resp"]==0) {
                html.push('<div class="user">');
                html.push('<span class="small">', data[i]["usr_name"], '&nbsp;&nbsp;&nbsp;', data[i]["dt_create"], '</span>');
            } else {
                html.push('<div class="empl">');
                html.push('<span class="small">', data[i]["company_name"], '&nbsp;&nbsp;&nbsp;', data[i]["dt_create"], '</span>');
            }
            html.push('<div class="block1">', data[i]["message"], '</div></div><br/>');
        }
        $('#chat').html(html.join(''));
    }


    function GetChat(id_theme, i) {
        this.id_theme = id_theme;
        this.index = i;
        $('#theme_title').html(this.theme_list[i]["title"]);
        var id = $("#id_user").val();
        var address = "/ajax/Get_chat_by_user?";
        $.ajax({
            type: 'GET',
            url: address + 'id=' + id + '&id_theme=' + id_theme,
            cache: false,
            dataType: 'json',
            success: function (data) {

                ShowChat(data)
            },
            error: function (data) {
                alert("Download error!");
            }
        });
    }

    function add_mess() {
        var id_empl = this.id_empl;
        var id = $("#id_user").val();
        var address = "/ajax/Add_mess_by_user?";
        var id_theme = this.id_theme;
        var index = this.index;
        var mess = encodeURIComponent($("#add_mess").val());
        $.ajax({
            type: 'GET',
            url: address + 'id=' + id + '&id_theme=' + this.id_theme + '&id_empl='+id_empl + '&mess=' + mess,
            cache: false,
            dataType: 'json',
            success: function (data) {
                ShowChat(data)
            },
            error: function (data) {
                alert("Download error!");
            }
        });
    }


    $(document).ready(function(){
        getThemebyUser();
    });
</script>

