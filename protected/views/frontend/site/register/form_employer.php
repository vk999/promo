<?php  Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/ajaxfileupload.js', CClientScript::POS_HEAD);
/** Bug tracking
 *  1) Дата не в русском формате
 *  2) Глобальный message при валидации
 */
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/jsform.js', CClientScript::POS_HEAD);
Yii::app()->getClientScript()->registerScriptFile(Yii::app()->request->baseUrl.'/js/page/reg_employer.js', CClientScript::POS_HEAD);
echo CHtml::form('','post',array('enctype'=>'multipart/form-data'));
echo '<h3>'.Share::lng("ANEM_TITLE").'</h3>';
?>
<div class="row">
  <div class="span3">

  <div class="photo"><img id="photo" src="/images/man.png"/></div>
  <input id="fileToUpload" type="file" name="fileToUpload" title="Выберите файл" style="display:none" onchange="ajaxFileUpload()"/>
  <input type="hidden" id="photo_file">
  <input type="hidden" id="ra_id">
  <input type="hidden" id="photo">
  <?php
    echo '<img id="loading" src="'.Yii::app()->homeUrl.'images/loading.gif" style="display:none;">';
  ?>
  <button class="btn btn-success" id="buttonUpload" onclick="return Upload();" title="Загрузить">Загрузить</button>
 </div>

  <div class="span9">
  <div class="form-horizontal">
<div class="accordion" id="accordion2">

    <div class="accordion-group">
    <div class="accordion-heading silver">
      <a href="#collapseOne" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" >Основная информация</a>
    </div>
    <div id="collapseOne" class="accordion-body collapse in">
      <div class="accordion-inner">

       <div class="control-group">
          <label class="control-label"><?php echo Share::lng('ANEM_LN'); ?> * <span id="lastname_err" class="err badge badge-important">!</span></label>
          <div class="controls input-append">
              <input type="text" name="lastname" id="lastname" placeholder="<?php echo Share::lng('ANEM_LN'); ?>" />
          </div>
       </div>

       <div class="control-group">
          <label class="control-label"><?php echo Share::lng('ANEM_FN'); ?> * <span id="firstname_err" class="err badge badge-important">!</span></label>
          <div class="controls input-append">
            <input type="text" name="firstname" id="firstname" placeholder="<?php echo Share::lng('ANEM_FN'); ?>"/>
          </div>
       </div>

       <div class="control-group">
          <label class="control-label"><?php echo Share::lng('ANEM_SN'); ?> * <span id="surname_err" class="err badge badge-important">!</span></label>
          <div class="controls input-append">
            <input type="text" name="surname" id="surname" placeholder="<?php echo Share::lng('ANEM_SN'); ?>"/>
          </div>
       </div>


       <div class="control-group">
          <label class="control-label">Дата рождения * <span id="birthday_err" class="err badge badge-important">!</span></label>
          <div class="controls input-append">
            <?php
          	  echo $this->widget('zii.widgets.jui.CJuiDatePicker', array('model'=>$model, 'attribute'=>'access_time','language'=>'ru', 'id'=>'birthday',
              'options'=>array('showButtonPanel'=>'true',
                  'yearRange'=>'-70:+0',
                  'changeYear'=>'true',
                  'changeMonth'=>'true' )), true);
            ?>
          </div>
       </div>

      <div class="control-group">
          <label class="control-label">Пол * </label>
          <div class="controls input-append">
            <label class="radio">Мужской<input type="radio" name="isman" id="isman_1" checked value="1"></label>
            <label class="radio">Женский<input type="radio" name="isman" id="isman_0" value="0"></label>
          </div>
      </div>


      <div class="control-group">
          <label class="control-label"><?php echo Share::lng('ANEM_ADV');?> * </label>
          <div class="controls input-append">
            <input type="text" name="ra" id="ra" placeholder="<?php echo Share::lng('ANEM_EX-ADV'); ?>" onchange="checkRA()"/>
            <br/><a href="/site/registerRa" class="btn btn-success btn-small"><?php echo Share::lng('ANEM_REGADV');?></a><!--Зарегистрировать Рекламное Агентство-->
          </div>
       </div>

     <div class="control-group">
          <label class="control-label"><?php echo Share::lng('ANEM_INTEREST'); ?> * </label>
          <div class="controls input-append">
            <textarea name="interests" id="interests" style="height:80px" placeholder="<?php echo Share::lng('ANEM_EX-INTEREST');?>"></textarea>
          </div>
      </div>

      </div>
    </div>
    </div>

<!--
    <div class="accordion-group">
    <div class="accordion-heading silver">
      <a href="#collapseTwo" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2">Образование</a>
    </div>
    <div id="collapseTwo" class="accordion-body collapse">
      <div class="accordion-inner">

         <div class="control-group">
          <label class="control-label">Учебное заведение * <span id="education_err" class="err badge badge-important">!</span></label>
          <div class="controls input-append">
            <input type="text" name="education" id="education" placeholder="ВУЗ" />
          </div>
        </div>

        <div class="control-group">
          <label class="control-label">Образование * <span id="education_type_err" class="err badge badge-important">!</span></label>
          <div class="controls input-append">
            <label class="radio">Высшее<input id="education_type_1" type="radio" name="education_type" value="1"></label>
            <label class="radio">Среднее<input id="education_type_2" type="radio" name="education_type" value="2" ></label>
            <label class="radio">Начальное<input id="education_type_3" type="radio" name="education_type" value="3" ></label>
          </div>
        </div>

      </div>
    </div>
    </div>
-->
   <div class="accordion-group">
    <div class="accordion-heading silver">
      <a href="#collapseThree" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2">Контактная информация</a>
    </div>
    <div id="collapseThree" class="accordion-body collapse">
      <div class="accordion-inner">

       <div class="control-group">
          <label class="control-label">Регион * <span id="city_err" class="err badge badge-important">!</span></label>
          <div class="controls input-append">
            <input type="text" name="city" id="city" placeholder="пример: Москва" />
          </div>
       </div>

       <div class="control-group">
          <label class="control-label">Е-майл * <span id="email_err" class="err badge badge-important">!</span></label>
          <div class="controls input-append">
            <input type="text" name="email" id="email" placeholder="пример: sergey78@mail.com"  class="asci_only" />
          </div>
       </div>


       <div class="control-group">
          <label class="control-label">Телефон 1 * <span id="phone1_err" class="err badge badge-important">!</span></label>
          <div class="controls input-append">
            <input type="text" name="phone1" id="phone1" class="phone" placeholder="пример: 095-8001122" />
          </div>
       </div>

       <div class="control-group">
          <label class="control-label">Телефон 2 * <span id="phone2_err" class="err badge badge-important">!</span></label>
          <div class="controls input-append">
            <input type="text" name="phone2" id="phone2" class="phone" placeholder="пример: 095-8001111" />
          </div>
       </div>

      </div>
    </div>
   </div>


  <div class="accordion-group">
    <div class="accordion-heading silver">
      <a href="#collapseFour" class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2">Аккаунт</a>
    </div>
    <div id="collapseFour" class="accordion-body collapse">
      <div class="accordion-inner">

       <div class="control-group">
          <label class="control-label">Логин (ник) * <span id="login_err" class="err badge badge-important">!</span></label>
          <div class="controls input-append">
            <input type="text" name="login" id="login" placeholder="пример: sergey78" class="asci_only"/>
            <span class="err" id="login_err"></span>
         </div>
       </div>

      <p>
        <a href="javascript:ShowPassw()" class="btn small" id="btn_update_passw">Сменить пароль</a>
      </p>

      <div id="block_passw" style="display:none;">

        <div class="control-group" id="elem_passw_old">
            <label class="control-label"><?php echo Share::lng('ANEM_PASSW-OLD');?> * <span id="password_err" class="err badge badge-important">!</span></label>
            <div class="controls input-append">
              <input type="password" name="passw_old" id="passw_old" placeholder="password"/>
            </div>
        </div>

        <div class="control-group" >
            <label class="control-label"><?php echo Share::lng('ANEM_PASSW');?> * <span id="elem_passw_old_err" class="err badge badge-important">!</span></label>
            <div class="controls input-append">
              <input type="password" name="passw" id="passw" placeholder="password" onchange="checkPassw()"/>
            </div>
        </div>

        <div class="control-group" >
            <label class="control-label"><?php echo Share::lng('ANEM_CONFIRM');?> * <span id="confirm_err" class="err badge badge-important">!</span></label>
            <div class="controls input-append">
              <input type="password" name="confirm" id="confirm" placeholder="password" onchange="checkPassw()"/>
            </div>
        </div>

        <p id="msg_passw"></p>
        <a href="javascript:UpdatePassw()" class="btn small" id="btn2_update_passw">Сменить пароль</a>

      </div> <!--block_passw-->


      </div>
    </div>
    </div>


</div><!-- End Accordeon -->

    </div><!-- end pan1 -->




    <div class="clear"></div>
  </div><!-- end wrapper -->
</div><!-- end container_demo -->

<div class="clear"></div>
<br/>
<div id="msg" class="alert" style="display:none"></div>
<div class="alert alert-error err" id="total_err">
    Не все поля заполнены! Проверьте фому.
</div>
    <div class="modal-footer">
			<a href='/' class='btn btn-inverse' id='btn_cancel_form' >Отмена</a>
      <a href='javascript:Save();' class='btn btn-primary' id='btn_save_form' >Сохранить</a>
 	  </div>

<?php echo CHtml::endForm();?> 
<script type="text/javascript">
<?php
echo 'var err_user     = "'.Share::lng('ANEM_ERR-USER').'";';     //Такой пользователь уже существует
echo 'var err_login    = "'.Share::lng('ANEM_ERR-LOGIN').'";';    //Введите логин
echo 'var err_passw    = "'.Share::lng('ANEM_ERR_PASSW').'";';    //Введите пароль
echo 'var err_phone    = "'.Share::lng('ANEM_ERR_PHONE').'";';    //Введите телефон
echo 'var err_confirm  = "'.Share::lng('ANEM_ERR-CONFIRM').'";';  //Пароли не совпадают
echo 'var err_email    = "'.Share::lng('ANEM_ERR-EMAIL').'";';    //Некорректный email
echo 'var err_lastn    = "'.Share::lng('ANEM_ERR_LASTN').'";';    //Введите фамилию
echo 'var err_firstn   = "'.Share::lng('ANEM_ERR_FIRSTN').'";';   //Введите имя
echo 'var err_surn     = "'.Share::lng('ANEM_ERR_SURN').'";';     //Введите отчество
echo 'var err_city     = "'.Share::lng('ANEM_ERR_CITY').'";';     //Введите регион (областной центр)
echo 'var err_adv      = "'.Share::lng('ANEM_ERR_ADV').'";';      //Выбeрите Рекламное Агентство
echo 'var err_edu      = "'.Share::lng('ANEM_ERR_EDU').'";';      //Укажите учебное заведение
echo 'var err_edu_type = "'.Share::lng('ANEM_ERR_EDU_TYPE').'";'; //Выберите тип образования
echo 'var err_birth    = "'.Share::lng('ANEM_ERR_BIRTH').'";';    //Введите дату рождения
echo 'var err_network  = "'.Share::lng('ANEM_ERR_NETWORK').'";';  //Ошибка передачи данных!
echo 'var mess1        = "'.Share::lng('MS_ACTIVATE1').'";';      //Поздравляем, вы получили аккаунт
echo 'var mess2        = "'.Share::lng('MS_ACTIVATE2').'";';      //для входа в личный кабинет
echo 'var mess3        = "'.Share::lng('MS_ACTIVATE3').'";';      //Для активации пароля - вам отправлено письмо на адрес
echo 'var mess4        = "'.Share::lng('MS_ACTIVATE4').'";';      //Откройте письмо и перейдите по ссылке
?>
</script>
