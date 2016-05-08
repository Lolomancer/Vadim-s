<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Calendar */

$this->title = 'Событие с '.$model->getDateTimeEventStart().' по '.$model->getDateTimeEventEnd();
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'События'), 'url' => ['mycalendar']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="calendar-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            'text:ntext',
            //'creator',
            [
                'attribute' => 'user_name',
                'value' => $model->user->name . ' ' . $model->user->surname
            ],
            //'date_event_start',
            [
                'attribute' => 'date_event_start',
                'value' => $model->getDateTimeEventStart()
            ],
            //'date_event_end',
            [
                'attribute' => 'date_event_end',
                'value' => $model->getDateTimeEventEnd()
            ],
        ],
    ]) ?>

</div>
