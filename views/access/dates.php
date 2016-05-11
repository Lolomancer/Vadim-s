<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use app\models\Calendar;
use yii\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\CalendarSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'События друга').' '.User::findOne($id)->name . ' ' . User::findOne($id)->surname;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="calendar-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'attribute' => 'date',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return Html::a(Yii::$app->formatter->asDate($model->date, 'dd/MM/yyyy'),
                            ['/calendar/shared/'.$model->user_owner.'?date='.$model->date]
                        );
                    },
                ],
            ],
        ]); ?>

</div>
