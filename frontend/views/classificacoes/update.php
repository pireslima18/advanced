<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\classificacoes $model */

$this->title = 'Update Classificacoes: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Classificacoes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="classificacoes-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
