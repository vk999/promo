<h3><i>Администрирование карточек</i></h3>
<script>

    $(function(){

        $("[rel='tooltip']").tooltip({

            delay: { show: 50, hide: 500 }

        });

        $("[rel='popover']").popover();

    });

</script>

<?php
echo CHtml::form('/admin/site/UserUpdate?id=0','POST',array("id"=>"form", "name"=>"form"));
$this->widget('zii.widgets.grid.CGridView', array(
    'rowCssClassExpression' => function($row, $data) {
        // $row - номер строки начиная с 0
        // $data - ваша моделька
        if ($data->processed == 1) {
            return 'tr_class_usual';
        } else {
            return 'tr_class_select';
        }
        // в остальных случаях функция ничего не вернет и tr будет без класса
    },
    'id'=>'my-grid',
    'dataProvider'=>$model->search(),
    'htmlOptions'=>array('class'=>'table table-hover'),
    'filter'=>$model,
    'enablePagination' => true,
    'columns'=>array(
        array(
            'class'=>'CCheckBoxColumn',
            'selectableRows' => 2,
            'checkBoxHtmlOptions' => array('class' => 'checkclass'),
            'value' => '$data->id',
        ),
        array(
            'name' => 'id',
            'value' => '$data->id',
            'type' => 'raw',
            'htmlOptions'=>array('style'=>'width: 50px; text-align: center;'),
            'filter'=>'',
        ),
        array(
            'name' => 'name',
            'value' => '$data->name',
            'type' => 'raw',
            //'htmlOptions'=>array('style'=>'width: 200px;'),
        ),
        array(
            'name' => 'fff',
            'value' => '$data->fff',
            'type' => 'raw',
            //'htmlOptions'=>array('style'=>'width: 200px;'),
        ),
        array(
            'name' => 'iii',
            'value' => '$data->iii',
            'type' => 'raw',
            //'htmlOptions'=>array('style'=>'width: 200px;'),
        ),
        array(
            'name' => 'status',
            'value' => 'ShowStatus($data->id)',
            'type' => 'raw',
            'filter'=>'',
        ),

    )));

function ShowStatus($id_user) {
    return '<a href="/admin/site/CardEdit/'.$id_user.'" rel="tooltip" data-placement="top" title="Редактировать"><span class="label label-info">Редактировать</span></a>'.
        '<a href="/admin/site/CardResetStatus/'.$id_user.'" rel="tooltip" data-placement="top" title="Сброс статуса"><span class="label label-alert">Сброс&nbsp;&nbsp;статуса </span></a>';
}


echo CHtml::endForm();
?>
<a href="#" target="_blank" onclick="export_send()">Export to CSV</a>

<script type="text/javascript">
    function onchangeLang(sel)
    {
        var value = sel.options[sel.selectedIndex].value;
        $("#field_lang").val(value);
        //alert(value);
        $("#form").attr("action","/admin/site/pages");
        $("#btn_submit").click();
    }

    function export_send() { 
      document.forms['form'].method = 'POST';
      document.forms['form'].action = "/admin/site/ExportCards";
      document.forms['form'].submit();
    }

</script>