<div class="row" id="loginform">
  <div class="well span4 offset4">

    <legend>Авторизация</legend>

    <?php $form=$this->beginWidget('CActiveForm', array(
    	'id'=>'login-form',
    	'enableClientValidation'=>true,
    	'clientOptions'=>array(
    		'validateOnSubmit'=>true,
    	),
      //'htmlOptions'=>array('class'=>'form-horizontal'),
    )); ?>

    <div class="alert alert-block">Поля, отмеченные <span class="required">*</span> обязательны для заполнения.</div>

    <div class="control-group">
      		<?php echo $form->labelEx($model,'username', array('class'=>'control-label')); ?>
      <div class="controls input-append">
      		<?php echo $form->textField($model, 'username', array('class' => 'span3', 'placeholder'=>'Ваш логин')); ?>
          <span class="add-on"><i class="icon-user"></i></span>
       </div>
    		<?php echo $form->error($model,'username'); ?>
    </div>

    <div class="control-group">
    		<?php echo $form->labelEx($model,'password', array('class'=>'control-label')); ?>
      <div class="controls input-append">
    		<?php echo $form->passwordField($model,'password', array('class' => 'span3', 'placeholder'=>'Ваш пароль')); ?>
        <span class="add-on"><i class="icon-certificate"></i></span>
      </div>
    	<?php echo $form->error($model,'password'); ?>
    </div>

    <div class="control-group rememberMe">
      <label class="checkbox">
    		<?php echo $form->checkBox($model,'rememberMe'); ?>Запомнить меня
      </label>
    		<?php echo $form->error($model,'rememberMe'); ?>
    </div>

    <div class="row buttons" style="text-align:right">
    		<?php echo CHtml::submitButton('Войти',array("id"=>"btn_login","class"=>"btn")); ?>
    </div>

    <?php $this->endWidget(); ?>

  </div>
</div>