<?php
Yii::app()->getClientScript()->registerCoreScript('jquery');
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/ajaxfileupload.js', CClientScript::POS_HEAD);

echo '<div class="span11">
<h3><i>Редактирование карточки пользователя</i> ID='.$data['id'].'</h3>';

echo CHtml::form('','post',array('enctype'=>'multipart/form-data', 'class'=>'form-horizontal'));
//echo CHtml::hiddenField('field_lang', $lang, array('type'=>"hidden"));
//echo CHtml::hiddenField('pagetype', $pagetype, array('type'=>"hidden"));


echo '<div class="control-group">
      <label class="control-label">Фирма</label>
	    <div class="controls input-append">';
echo CHtml::textField('Card[name]', $data['name'], array('class'=>'admform span5'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Должность</label>
	    <div class="controls input-append">';
echo CHtml::textField('Card[post]', $data['post'], array('class'=>'admform span5'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Табельный номер</label>
	    <div class="controls input-append">';
echo CHtml::textField('Card[tabn]', $data['tabn'], array('class'=>'admform span2'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Фамилия</label>
	    <div class="controls input-append">';
echo CHtml::textField('Card[fff]', $data['fff'], array('class'=>'admform span5'));
echo '  <span class="add-on"></span>';
echo '</div></div>';


echo '<div class="control-group">
      <label class="control-label">Имя</label>
	    <div class="controls input-append">';
echo CHtml::textField('Card[iii]', $data['iii'], array('class'=>'admform span5'));
echo '  <span class="add-on"></span>';
echo '</div></div>';


echo '<div class="control-group">
      <label class="control-label">Отчество</label>
	    <div class="controls input-append">';
echo CHtml::textField('Card[ooo]', $data['ooo'], array('class'=>'admform span5'));
echo '  <span class="add-on"></span>';
echo '</div></div>';


echo '<div class="control-group">
      <label class="control-label">Тип удостоверения личности (21-паспорт)</label>
	    <div class="controls input-append">';
echo CHtml::textField('Card[doctype]', $data['doctype'], array('class'=>'admform span2'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Серия</label>
	    <div class="controls input-append">';
echo CHtml::textField('Card[docser]', $data['docser'], array('class'=>'admform span5'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Номер</label>
	    <div class="controls input-append">';
echo CHtml::textField('Card[docnum]', $data['docnum'], array('class'=>'admform span5'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Дата выдачи (дд.мм.гггг)</label>
	    <div class="controls input-append">';
echo $this->widget('zii.widgets.jui.CJuiDatePicker', array('name'=>'Card[docdate]', 'value'=>$data['docdate'], 'language'=>'ru', 'id'=>'docdate', 'options'=>array('dateFormat'=>'dd.mm.yy', 'minDate'=>'Today'),), true);
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Код подразделения выдавший документ (паспорт)</label>
	    <div class="controls input-append">';
echo CHtml::textField('Card[docorgcode]', $data['docorgcode'], array('class'=>'admform span2'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Кем выдан документ (паспорт)</label>
	    <div class="controls input-append">';
echo CHtml::textField('Card[docorgname]', $data['docorgname'], array('class'=>'admform span5'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Дата рождения</label>
	    <div class="controls input-append">';
echo $this->widget('zii.widgets.jui.CJuiDatePicker', array('name'=>'Card[borndate]', 'value'=>$data['borndate'], 'language'=>'ru', 'id'=>'borndate', 'options'=>array('dateFormat'=>'dd.mm.yy', 'minDate'=>'Today'),), true);
echo '  <span class="add-on"></span>';
echo '</div></div>';


echo '<div class="control-group">
      <label class="control-label">Место рождения</label>
	    <div class="controls input-append">';
echo CHtml::textField('Card[bornplace]', $data['bornplace'], array('class'=>'admform span5'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Телефон</label>
	    <div class="controls input-append">';
echo CHtml::textField('Card[tel]', $data['tel'], array('class'=>'admform span5'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Адрес прописки</label>
	    <div class="controls input-append">';
echo CHtml::textField('Card[regaddr]', $data['regaddr'], array('class'=>'admform span5'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Код страны</label>
	    <div class="controls input-append">';
echo CHtml::textField('Card[regcountry]', $data['regcountry'], array('class'=>'admform span2'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Адрес фактического проживания</label>
	    <div class="controls input-append">';
echo CHtml::textField('Card[liveaddr]', $data['liveaddr'], array('class'=>'admform span5'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Дата создания</label>
	    <div class="controls input-append">';
echo CHtml::textField('Card[crdate]', $data['crdate'], array('class'=>'admform span2', 'readonly'=>true));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Комментарий</label>
	    <div class="controls input-append">';
echo CHtml::textField('Card[comment]', $data['comment'], array('class'=>'admform span5'));
echo '  <span class="add-on"></span>';
echo '</div></div>';

echo '<div class="control-group">
      <label class="control-label">Сканы</label>
	    <div class="controls">';

$lst = json_decode($data['files']);
    echo '<ul class="controls" id="lst_scan">';
if(!empty($lst)) {
    $i = 1;
    foreach ($lst as $r) {
        echo '<li><a class="btn btn-success" href="' . $r->orig . '" target="_blank">Документ ' . $i . ' просмотр</a>';
        echo '<a href="javascript:Del(' . $i . ')" class="btn btn-warning">Удалить</a>';
        echo '</li>';
        $i++;
    }
}
    echo '</ul>';

echo '<div class="controls input-append">';
echo '<input id="fileToUpload" type="file" name="fileToUpload" class="admform span5"/>';
echo '</div>';
echo '<button class="btn btn-success" id="buttonUpload" onclick="return ajaxFileUpload();">Загрузить</button>';
//echo '<a class="controls btn btn-success" href="javascript:add_doc()" >Добавить скан документа</a>';
echo '</div></div>';


echo '<div class="span11">';
echo '<div style="float:right;  display:inline;">';
echo CHtml::submitButton('Сохранить',array("class"=>"btn btn-success", "id"=>"btn_submit"));
echo '&nbsp;&nbsp;';
echo '<a href="/admin/site/cards" class="btn btn-warning" id="btn_cancel">Отмена</a>';
//echo CHtml::tag('input',array("id"=>"btn_cancel", "type"=>"button", "value"=>"Отмена", "class"=>"btn btn-warning"));
echo '</div></div>';




//$this->endWidget();
echo CHtml::endForm();
?>
</div>

<script>
    function Del(key)
    {
        if(confirm('Вы действительно хотите удалить документ '+key))
        {
            $.ajax({
                type:'GET',
                url:'/admin/ajax/DeleteScan?key='+key+'&id=<?php echo $data['id']?>',
                cache: false,
                dataType: 'text',
                success:function (data) {
                    $("#lst_scan").html(data);
                },
                error: function(data){
                    alert("Download error!");
                }
            });
        }
    }


    function Add(fname)
    {
            $.ajax({
                type:'GET',
                url:'/admin/ajax/AddScan?id=<?php echo $data['id']?>&fname='+fname,
                cache: false,
                dataType: 'text',
                success:function (data) {
                    $("#lst_scan").html(data);
                },
                error: function(data){
                    alert("Download error!");
                }
            });

    }



    function ajaxFileUpload()
    {

        $.ajaxFileUpload
        (
            {
                url:'/uploads/doajaxdocupload.php',
                secureuri:false,
                fileElementId:'fileToUpload',
                dataType: 'json',
                data:{name:'logan', id:'id'},
                success: function (data, status)
                {
                    if(typeof(data.error) != 'undefined')
                    {
                        if(data.error != '')
                        {
                            alert(data.error);
                        }else
                        {
                            //alert(data.name);
                            Add(data.name);

                        }
                    }
                },
                error: function (data, status, e)
                {
                    alert(e);
                }
            }
        )

        return false;

    }
</script>