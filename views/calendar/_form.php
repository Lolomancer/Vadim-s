<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datetimepicker\DateTimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Calendar */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="calendar-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>
    
    <?= $form->field($model, 'date_event_start')->widget(DateTimePicker::className(), [
        'language' => 'ru',
        'size' => 'ms',
        'pickButtonIcon' => 'glyphicon glyphicon-time',
        //'inline' => true,
        'clientOptions' => [
            'autoclose' => true,
            'pickerPosition' => 'bottom-left',
            'format' => 'yyyy-mm-dd hh:ii:ss', // if inline = false
            'todayBtn' => true
    ]]) ?>

    <?= $form->field($model, 'date_event_end')->widget(DateTimePicker::className(), [
        'language' => 'ru',
        'size' => 'ms',
        'pickButtonIcon' => 'glyphicon glyphicon-time',
        //'inline' => true,
        'clientOptions' => [
            'autoclose' => true,
            'pickerPosition' => 'bottom-left',
            'format' => 'yyyy-mm-dd hh:ii:ss', // if inline = false
            'todayBtn' => true
        ]]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Создать событие') : Yii::t('app', 'Изменить событие'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
