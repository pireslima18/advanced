<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\Devolucoes $model */

$this->title = 'Update Devolucoes: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Devolucoes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="devolucoes-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
