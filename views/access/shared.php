<?php

use yii\helpers\Html;
use yii\widgets\ListView;
use app\models\Calendar;
use yii\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $searchModel app\models\search\CalendarSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'События друзей');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="calendar-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
        foreach($events as $value1){
            $date = new \DateTime($value1[0]['date_event_start']);
            echo "<h2>Дата ".$date->format('d.m.Y').":</h2>";
            foreach ($value1 as $key => $value){
                echo "<a href='/calendar/view/".$value["id"]."'>
                Поделился ".User::findIdentity($value['creator'])->name.": Событие ".Calendar::findOne($value['id'])->text."</a><br>";
            }
        }
    ?>

</div>
