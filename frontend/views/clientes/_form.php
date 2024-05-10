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
    
    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= Yii::$app->session->getFlash('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true, 'autocomplete' => 'off']); ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'autocomplete' => 'off']); ?>

    <?= $form->field($model, 'telefone')->textInput([
        'maxlength' => true,
        'id' => 'telefone',
        'autocomplete' => 'off'
    ]) ?>

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
                    $("#statusError").removeClass('d-none');
                    $("#statusError").html(result);
                }
            }).fail(function(){
                console.log('Server error');
            });
            return false;
        })

        $('#telefone').on('input', function (e) {
            var telefone = $(this).val().replace(/\D/g, ''); // Remove todos os caracteres não numéricos

            var telefoneFormatado = '';

            if (telefone.length > 0) {
                telefoneFormatado += '(' + telefone.substring(0, 2); // Adiciona os primeiros dois dígitos
            }

            if (telefone.length > 2) {
                telefoneFormatado += ') ' + telefone.substring(2, 3); // Adiciona o terceiro dígito
            }

            if (telefone.length > 3) {
                telefoneFormatado += ' ' + telefone.substring(3, 7); // Adiciona os próximos quatro dígitos
            }

            if (telefone.length > 7) {
                telefoneFormatado += '-' + telefone.substring(7, 11); // Adiciona os últimos quatro dígitos
            }

            $(this).val(telefoneFormatado);
        });

        // Para permitir a exclusão dos caracteres
        $('#telefone').on('keydown', function (e) {
            var key = e.keyCode || e.charCode;

            // Permitir a exclusão apenas se estiver apagando caracteres formatados
            if (key === 8 || key === 46) {
                var telefone = $(this).val().replace(/\D/g, '');
                var telefoneFormatado = '';

                if (telefone.length > 0) {
                    telefoneFormatado += '(' + telefone.substring(0, 2); // Adiciona os primeiros dois dígitos
                }

                if (telefone.length > 2) {
                    telefoneFormatado += ') ' + telefone.substring(2, 3); // Adiciona o terceiro dígito
                }

                if (telefone.length > 3) {
                    telefoneFormatado += ' ' + telefone.substring(3, 7); // Adiciona os próximos quatro dígitos
                }

                if (telefone.length > 7) {
                    telefoneFormatado += '-' + telefone.substring(7, 11); // Adiciona os últimos quatro dígitos
                }

                $(this).val(telefoneFormatado);
            }
        });

    JS;
    $this->registerJs($script);
?>