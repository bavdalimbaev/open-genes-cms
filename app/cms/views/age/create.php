<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model cms\models\Age */

$this->title = 'Добавить филум';
$this->params['breadcrumbs'][] = ['label' => 'Ages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="age-create">

    <h2><?= Html::encode($this->title) ?></h2>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
