<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

/** @var yii\web\View $this */
/** @var frontend\models\Clientes $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="clientes-form">

    <?php $form = ActiveForm::begin(['id'=>$model->formName()]); ?>

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true, 'autocomplete' => 'off']); ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'autocomplete' => 'off']); ?>

    <?= $form->field($model, 'telefone')->textInput(['maxlength' => true, 'id' => 'telefone', 'autocomplete' => 'off']); ?>

    <?= $form->field($model, 'data_nascimento')->widget(DatePicker::classname(), [
        'language' => 'pt',
        'dateFormat' => 'dd/MM/yyyy',
        'options' => ['class' => 'form-control', 'autocomplete' => 'off']
    ]); ?>

    <?= $form->field($model, 'filmes_alugados')->textInput(['type' => 'number']); ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success mt-3']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php
    $script = <<< JS

        $('form#{$model->formName()}').on('beforeSubmit', function(e){
            var \$form = $(this);
            $.post(
                \$form.attr("action"),
                \$form.serialize()
            )
            .done(function(result) {
                console.log(result);
                if(result === "Success"){
                    $(\$form).trigger("reset");
                    $(document).find('#modalCreate').modal('hide');
                    $.pjax.reload({container:'#clientesGrid'});
                }else{
                    $("#message").html(result);
                }
            }).fail(function(){
                console.log('Server error');
            });
            return false;
        })

    JS;
    $this->registerJs($script);
?>