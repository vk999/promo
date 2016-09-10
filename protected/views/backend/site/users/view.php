<h3><i>Администрирование пользователей</i></h3>
<script>

	$(function(){
	
		$("[rel='tooltip']").tooltip({
		
			delay: { show: 50, hide: 500 }
		
		});
		
		$("[rel='popover']").popover();
	
	});

</script>

<?php
echo CHtml::form('/admin/site/UserUpdate?id=0','POST',array("id"=>"form"));
$this->widget('zii.widgets.grid.CGridView', array(
  'id'=>'my-grid',
  'dataProvider'=>$model->search(),
  'htmlOptions'=>array('class'=>'table table-hover'),
  'filter'=>$model,  
  'enablePagination' => true,
  'columns'=>array(
        array(
            'name' => 'id_user',
            'value' => '$data->id_user',
            'type' => 'raw',
            'htmlOptions'=>array('style'=>'width: 50px; text-align: center;'),
            'filter'=>'',
        ),
        array(
            'name' => 'login',
            'value' => '$data->login',
            'type' => 'raw',
            //'htmlOptions'=>array('style'=>'width: 200px;'),
        ),
        array(
            'name' => 'email',
            'value' => '$data->email',
            'type' => 'raw',
        ),
/*        
        array(
            'name' => 'ip',
            'value' => '$data->ip',
            'type' => 'raw',
            'htmlOptions' => array('class'=>'m_element'),
        ),
*/        
        array(
            'name' => 'access_time',
            'value' => '$data->access_time',
            'type' => 'raw',
            'htmlOptions' => array('class'=>'m_element'),
            'filter'=>'',
        ),
        array(
            'name' => 'booble_index',
            'value' => '$data->booble_index',
            'type' => 'raw',
            'filter'=>'',
        ),        
        array(
            'name' => 'status',
            'value' => 'ShowStatus($data->status, $data->id_user)',
            'type' => 'raw',
            'filter'=>'',
        ),        
        array(
            'name' => 'isblocked',
            'type' => 'raw',
            'filter'=>'',
			'value' => 'ShowBlocked($data->isblocked, $data->id_user)',
            'htmlOptions' => array('class'=>'\"isblocked_".$data->isblocked'),
        ),
  
)));

function ShowStatus($status, $id_user) {
  $uinfo = array('','Откл.','Промоутер','Работодат.','РА');
  $color = array('', 'label-inverse', 'label-success', 'label-info', 'label-important');
  return '<a href="/admin/site/UserUpdate/'.$id_user.'" rel="tooltip" data-placement="top" title="Редактировать"><span class="label '.$color[$status].'">'.$uinfo[$status].'</span></a>';
}

function ShowBlocked($blocked, $id_user) {
  $block = $blocked==0 ? "Блокировать" : "Разблокировать";
  $link = '<a href="/admin/site/UserBlocked/'.$id_user.'" rel="tooltip" data-placement="top" title="'.$block.'">';
  if($blocked==0) {
    return $link.'<span class="label label-success"><i class="icon-off icon-white"></i></span></a>';
  } else {
    return $link.'<span class="label label-important"><i class="icon-off icon-white"></i></span></a>';
  }
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