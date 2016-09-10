<?php
$filter = array("1"=>"1 день", "3"=>"3 дня", "7"=>"неделя", "31"=>"месяц");
$day = 1;
if(isset($_GET['day'])){
    $day=intval($_GET['day']);
}

echo '<h3><i>Статистика API : '.$filter[$day].'</i></h3>';
?>
<script>

	$(function(){
	
		$("[rel='tooltip']").tooltip({
		
			delay: { show: 50, hide: 500 }
		
		});
		
		$("[rel='popover']").popover();
	
	});

</script>
<?php
foreach($filter as $k=>$v)
{
    echo '<a href="/admin/site/stat_api?day='. $k.'">'. $v .'</a>&nbsp;&nbsp;&nbsp;&nbsp;';
}

$itemsProvider = new CArrayDataProvider($items, array(
    'keyField'=>'id_user',
    'sort'=>array(
        'attributes'=>array(
            'begin'=>array(
                'asc'=>'dtcreate',
                'desc'=>'dtcreate DESC',
            ),
            '*',
        ),
    ),
    'pagination' => array(
        'pageSize' => 20,
        'itemCount' => 100,
    ),
));


echo CHtml::form('/admin/site/UserUpdate?id=0','POST',array("id"=>"form"));
$this->widget('zii.widgets.grid.CGridView', array(
  'id'=>'my-grid',
  'dataProvider'=>$itemsProvider,
  'htmlOptions'=>array('class'=>'table table-hover'),
  //'filter'=>$model,
  'enablePagination' => true,
  'columns'=>array(
        array(
            'name' => 'id_user',
            'value' => '$data["id_user"]',
            'type' => 'raw',
            'htmlOptions'=>array('style'=>'width: 50px; text-align: center;'),
            'filter'=>'',
        ),

        array(
            'name' => 'login',
            'value' => '$data["login"]',
            'type' => 'raw',
            //'htmlOptions'=>array('style'=>'width: 200px;'),
        ),
      array(
          'name' => 'email',
          'value' => '$data["email"]',
          'type' => 'raw',
          //'htmlOptions'=>array('style'=>'width: 200px;'),
      ),

        array(
            'name' => 'status',
            'value' => 'ShowStatus($data["status"], $data["id_user"])',
            'type' => 'raw',
            'filter'=>'',
        ),
      array(
          'name' => 'file',
          'value' => 'GetFile($data["id_user"],'.$day.')',
          'type' => 'raw',
          'filter'=>'',
      ),

)));

function ShowStatus($status, $id_user) {
    $uinfo = array('','Откл.','Промоутер','Работодат.','РА');
    $color = array('', 'label-inverse', 'label-success', 'label-info', 'label-important');
    return '<span class="label '.$color[$status].'">'.$uinfo[$status].'</span>';
}

function GetFile($id_user, $day) {
    $uinfo = array('','Откл.','Промоутер','Работодат.','РА');
    $color = array('', 'label-inverse', 'label-success', 'label-info', 'label-important');
    return '<a href="/admin/site/report/'.$id_user.'?day='.$day.'" rel="tooltip" data-placement="top" ><span class="label label-info">xls отчет</span></a>';
}


echo CHtml::endForm();
?>
<script type="text/javascript">
function onchangeLang(sel)
{
	var value = sel.options[sel.selectedIndex].value;
	$("#field_lang").val(value);
	//alert(value);
 	$("#form").attr("action","/admin/site/pages");
  	$("#btn_submit").click();
}
</script>