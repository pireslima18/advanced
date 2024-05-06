<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var frontend\models\DevolucoesSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="devolucoes-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'id_cliente') ?>

    <?= $form->field($model, 'id_filme') ?>

    <?= $form->field($model, 'data_inicio') ?>

    <?= $form->field($model, 'data_entrega') ?>

    <?php // echo $form->field($model, 'valor_pago') ?>

    <?php // echo $form->field($model, 'valor_multa') ?>

    <?php // echo $form->field($model, 'valor_total') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
