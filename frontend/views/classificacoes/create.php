<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\classificacoes $model */

$this->title = 'Create Classificacoes';
$this->params['breadcrumbs'][] = ['label' => 'Classificacoes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="classificacoes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
