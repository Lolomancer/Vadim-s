<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Calendar */

$this->title = Yii::t('app', 'Обновление события с ', [
    'modelClass' => 'Calendar',
]) .$model->getDateTimeEventStart().' по '.$model->getDateTimeEventEnd();
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'События'), 'url' => ['mycalendar']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Обновить');
?>
<div class="calendar-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
