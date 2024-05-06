<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var frontend\models\Devolucoes $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="devolucoes-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'id_cliente')->textInput() ?>

    <?= $form->field($model, 'id_filme')->textInput() ?>

    <?= $form->field($model, 'data_inicio')->textInput() ?>

    <?= $form->field($model, 'data_entrega')->textInput() ?>

    <?= $form->field($model, 'valor_pago')->textInput() ?>

    <?= $form->field($model, 'valor_multa')->textInput() ?>

    <?= $form->field($model, 'valor_total')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
