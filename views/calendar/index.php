<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\CalendarSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'События');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="calendar-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Создать событие'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'text:ntext',
            //'creator',
            [
                'attribute' => 'user_owner',
                'content' => function($model){
                    return User::findOne($model->creator)->name . ' ' . User::findOne($model->creator)->surname;
                }
            ],
            //'date_event_start',
            [
                'attribute' => 'date_event_start',
                'content' => function($model){
                    return $model->getDateTimeEventStart();
                }
            ],
            //'date_event_end',
            [
                'attribute' => 'date_event_end',
                'content' => function($model){
                    return $model->getDateTimeEventEnd();
                }
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
