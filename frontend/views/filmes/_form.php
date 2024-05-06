<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use frontend\models\Categorias;
use frontend\models\Classificacoes;

/** @var yii\web\View $this */
/** @var frontend\models\Filmes $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="filmes-form">

    <?php $form = ActiveForm::begin(['id'=>$model->formName()]); ?>

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true, 'autocomplete' => 'off']) ?>

    <?= $form->field($model, 'categoria_id')->dropDownList(
        ArrayHelper::map(Categorias::find()->all(), 'id', 'nome_categoria'),
        ['prompt' => 'Selecionar Categoria']
    ) ?>

    <?= $form->field($model, 'classificacao_id')->dropDownList(
        ArrayHelper::map(Classificacoes::find()->all(), 'id', 'classificacao'),
        ['prompt' => 'Selecionar Classificacao']
    ) ?>

    <?= $form->field($model, 'valor_dia')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList([ 'Disponível' => 'Disponível', 'Indisponível' => 'Indisponível', ], ['prompt' => '']) ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success mt-3', 'id' => 'salvarFilme']) ?>
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
                $.pjax.reload({container:'#filmesGrid'});
            }else{
                $("#message").html(result);
            }
        }).fail(function(){
            console.log('Server error');
        });
        return false;
        });

    JS;
    $this->registerJs($script);
?>
