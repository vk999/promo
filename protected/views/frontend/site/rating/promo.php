<?php Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl . '/js/jsform.js', CClientScript::POS_HEAD);
Yii::app()->getClientScript()->registerScriptFile('/js/jquery-ui-1.10.3.custom.min.js', CClientScript::POS_HEAD);
?>

<!-- MODAL WINDOW -->
<div class="modal hide fade" id="Modal" tabindex="-1" role="dialog">
    <div class="modal-header">
        <button class="close" type="button" data-dismiss="modal"><img src="/images/ico/close_btn_large.png" width="32">
        </button>
        </button>
        <h3 id="myModalLabel">Задать оценку:</h3>
    </div>
    <div class="modal-body">
        <div id="slider-range"></div>
        <label for="amount">Оценка в % от 0 до 100</label>
        <input type="text" id="amount" style="border:0; color:#f6931f; font-weight:bold;"/>
        <script>
            $("#slider-range").slider({
                range:"min",
                min:0,
                max:100,
                value:50,
                slide:function (event, ui) {
                    $("#amount").val(ui.value);
                }
            });
            $("#amount").val($("#slider-range").slider("value"));
        </script>
    </div>
    <div class="modal-footer">
        <button class="btn btn-danger" data-dismiss="modal">Закрыть</button>
        <button class="btn btn-primary" data-dismiss="modal" onclick="rat()">Сохранить изменения</button>
    </div>
    <input type="hidden" id="pkid"/>
</div>


<h3>Оценки работодателей</h3>

<div class="row">
    <div class="span4">
        <h3>Рекламные агентства</h3>
        <br/>

        <p id="info"></p>
        <h3 id="name_ra"></h3>
        <p id="info_ra"></p>

        <div id="list_ra" class="portlet-content"></div>
    </div>

    <!-- Vacancy lists block -->
    <div class="span8">
        <h3>Проекты</h3>
        <input type="hidden" name="vid" id="vid"/>
        <div id="list"></div>
    </div>

</div>


<script type="text/javascript">

    <?php Share::PrintMechJson();?>
    var objForm =
        (
            {"total_err":0, "param":""},
                [
                    {
                        "name":"lastname",
                        "value":"",
                        "tp":0,
                        "validate":1,
                        "errmess":"введите фамилию"
                    }
                ]);

    var prj_id;


    function getAllProjects() {
        if (!!uid) {
            link = site_url + "?cmd=GET_PROMO_PROJECTS&value=&uid=" + uid + "&callback=?";
            jsonp(link, function (data) {
                parseView(data);
                getRatingRa();
            });
        }
    }

    function parseView(data) {
        var html = [];

        for (var i = 0; i < data.length; i++) {
            console.log("block_vac: "+i);
            html.push('<div class="block_vac" id="b', data[i].id_jobs, '">');
            html.push('<h4>', urldecode(data[i].name_act), '</h4>');
            html.push('<table border="0">');
            html.push('<tr><td style="width:130px">название акции</td>');
            html.push('<td><b>', urldecode(data[i].name_act), '</b></td></tr>');

            html.push('<tr><td>город</td>');
            html.push('<td>', data[i].city, '</td></tr>');

            html.push('<tr><td>механика</td>');
            html.push('<td>', ShowMech(data[i].mech), '</td></tr>');

            html.push('<tr><td>оплата в час</td>');
            html.push('<td>', data[i].pay, '</td></tr>');

            html.push('<tr><td>сроки акции</td>');
            html.push('<td>', data[i].date_begin, ' - ', data[i].date_end, '</td></tr>');

            html.push('</table>');
            html.push('<div class="productRate" id="d_', data[i].prj_id, '"><div style="width: ', data[i].rating, '%"></div></div>');
            html.push('<div style="display:inline">');
            html.push('<a href="javascript:viewVac(', data[i].id_jobs, ')" class="btn btn-info btn-mini">детали <i class="icon-chevron-down"></i></a>&nbsp;');
            html.push('<a href="#Modal" data-toggle="modal" onclick="javascript:showRatingEdit(', data[i].prj_id, ')" class="btn btn-primary btn-mini">изменить рейтинг <i class="icon-chevron-down"></i></a>');
            html.push('</div><div class="clear"></div>');
            html.push('<div id="p_', data[i].id_jobs, '"></div>');
            html.push('</div>');

        }

        $("#list").html(html.join(''));
        //AutoHeight();
    }

    function viewVac(id) {
        $("#b" + id + " table").toggle("slow");
    }

    function showRatingEdit(id) {
        prj_id = id;
    }

    function rat(val) {
        if (!!uid) {
            val = $("#amount").val();
            var x = "prj_id:" + prj_id + ",rating:" + val;
            var xen = encript(x, token);
            link = site_url + "?cmd=SET_RATING_PROMO&mode=3&value=" + xen + "&uid=" + uid + "&callback=?";
            jsonp(link, function (data) {
                close_pop_up('#pop-up-rating');
                $("#d_" + prj_id).html('<div style="width: ' + val + '%"></div>');
                var txt = val / 20 + " из 15";
                $("#t_" + prj_id).html(txt);
            });
        }
    }


    function AutoHeight() {
    }


    function getRatingRa() {
        if (!!uid) {
            link = site_url + "?cmd=GET_RATING_RA&value=&uid=" + uid + "&callback=?";
            jsonp(link, function (data) {
                ShowRating(data);
            });
        }
    }

    function ShowRating(data) {
        /*
        if(isfull)
        {
           var info = data.fio+'<br/>тел.: '+data.phone+'<br/>email: <a href="mailto:'+data.email+'">'+data.email+'</a>';
           $("#info").html(info);
           $('#photo').attr("src","/content/"+data.photo);
        }
        */
        var html = [];
        html.push('<ul>');
        for (var i = 0; i < data.length; i++) {
            html.push('<li class="span2">',
                '<a target="_blank" href="http://', data[i].web,
                '" title="', data[i].name,
                '"><img src="/content/', data[i].logo, '" /></a><br/><b>', data[i].name, '</b>');
            html.push('<div class="productRate"><div style="width: ', data[i].rating, '%"></div></div>');
            html.push('</li>');
        }
        html.push('</ul>');
        $("#list_ra").html(html.join(''));

        AutoHeight();
    }

</script>