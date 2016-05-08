<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Access */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Настройки доступа'), 'url' => ['myaccess']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="access-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Обновить настройки доступа'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Удалить настройки доступа'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Вы уверены, что хотите удалить текущие настройки доступа?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            //'user_owner',
            [
                'attribute' => 'user_owner',
                'value' => User::findOne($model->user_owner)->name . ' ' . User::findOne($model->user_owner)->surname
            ],
            //'user_guest',
            [
                'attribute' => 'user_guest',
                'value' => User::findOne($model->user_guest)->name . ' ' . User::findOne($model->user_guest)->surname
            ],
            'date',
        ],
    ]) ?>

</div>
