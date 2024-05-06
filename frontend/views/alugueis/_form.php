<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;
use frontend\models\Filmes;
use frontend\models\Clientes;
use kartik\select2\Select2;

/** @var yii\web\View $this */
/** @var frontend\models\Alugueis $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="alert alert-danger d-none" role="alert" id="statusError">
</div>

<div class="alugueis-form">

    <?php $form = ActiveForm::begin(['id'=>$model->formName()]); ?>

    <?= $form->field($model, 'id_cliente')->dropDownList(
        ArrayHelper::map(Clientes::find()->all(), 'id', 'nome'),
        ['prompt' => 'Selecionar Cliente']
    ); ?>
    
    <?= $form->field($model, 'id_filmes')->widget(Select2::class, [
        'data' => ArrayHelper::map(Filmes::find()->where(['status' => 'disponivel'])->all(), 'id', 'nome'),
        'options' => [
            'placeholder' => 'Selecionar Filme',
            'multiple' => true,
        ],
        'pluginOptions' => [
            'allowClear' => true,
            'closeOnSelect' => false,
            'tokenSeparators' => [','],
        ],
    ]); ?>

    <?= $form->field($model, 'data_inicio')->widget(DatePicker::classname(), [
        'language' => 'pt',
        'dateFormat' => 'yyyy/MM/dd',
        'options' => ['class' => 'form-control', 'autocomplete' => 'off']
    ]); ?>

    <?= $form->field($model, 'data_fim')->widget(DatePicker::classname(), [
        'language' => 'pt',
        'dateFormat' => 'yyyy/MM/dd',
        'options' => ['class' => 'form-control', 'autocomplete' => 'off']
    ]); ?>

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
                    $.pjax.reload({container:'#alugueisGrid'});
                }else{
                    $("#statusError").removeClass('d-none');
                    $("#statusError").html(result);
                }
            }).fail(function(){
                console.log('Server error');
            });
            return false;
        });

    JS;
    $this->registerJs($script);
?>
