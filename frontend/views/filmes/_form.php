<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use frontend\models\Categorias;
use frontend\models\Classificacoes;
use kartik\file\FileInput;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var frontend\models\Filmes $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="filmes-form">

    <?php $form = ActiveForm::begin(['id'=>$model->formName(), 'options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'nome')->textInput(['maxlength' => true, 'autocomplete' => 'off']) ?>

    <?= $form->field($model, 'categoria_id')->dropDownList(
        ArrayHelper::map(Categorias::find()->all(), 'id', 'nome_categoria'),
        ['prompt' => 'Selecionar Categoria']
    ) ?>

    <?= $form->field($model, 'file')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => [
            'initialPreview' => [
                // Se houver uma imagem associada ao modelo, exiba-a
                $model->logo ? Html::img(Yii::getAlias('@web') . '/' . $model->logo, ['class' => 'file-preview-image']) : null,
            ],
            'initialPreviewConfig' => [
                // Se houver uma imagem associada ao modelo, exiba o botão de exclusão
            ],
            'initialCaption' => $model->logo ? $model->logo : '', // Nome do arquivo inicial, se houver
            'showUpload' => false, // Não exibir botão de upload se não houver arquivo
            'showRemove' => true, // Exibir botão de remoção se houver arquivo
            'showClose' => false, // Não exibir botão de fechar
            'showCaption' => true, // Exibir legenda
            'overwriteInitial' => false, // Não substituir a visualização inicial
        ],
    ]); ?>

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
            var form = $(this);
            var formData = new FormData(form[0]);

            $.ajax({
                url: form.attr("action"),
                type: form.attr("method"),
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(result) {
                    console.log(result);
                    if(result === "Success"){
                        form.trigger("reset");
                        $('#modalCreate').modal('hide');
                        $.pjax.reload({container:'#filmesGrid'});
                    }else{
                        $("#message").html(result);
                    }
                },
                error: function(){
                    console.log('Server error');
                }
            });

            return  false;
        });

    JS;
    $this->registerJs($script);
?>
