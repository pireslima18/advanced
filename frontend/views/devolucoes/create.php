<?php

use yii\helpers\Html;
use Yii;

/** @var yii\web\View $this */
/** @var frontend\models\Devolucoes $model */

$this->title = 'Create Devolucoes';
$this->params['breadcrumbs'][] = ['label' => 'Devolucoes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="devolucoes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger">
            <?= Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif; ?>

</div>
