<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\file\FileInput;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var frontend\models\classificacoes $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="classificacoes-form">

    <?php $form = ActiveForm::begin(['id'=>$model->formName()]); ?>

    <?= $form->field($model, 'classificacao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'file')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => [
            'initialPreview' => [
                // Se houver uma imagem associada ao modelo, exiba-a
                $model->imagem_classificacao ? Html::img(Yii::getAlias('@web') . '/' . $model->imagem_classificacao, ['class' => 'file-preview-image']) : null,
            ],
            'initialPreviewConfig' => [
                // Se houver uma imagem associada ao modelo, exiba o botão de exclusão
            ],
            'initialCaption' => $model->imagem_classificacao ? $model->imagem_classificacao : '', // Nome do arquivo inicial, se houver
            'showUpload' => false, // Não exibir botão de upload se não houver arquivo
            'showRemove' => true, // Exibir botão de remoção se houver arquivo
            'showClose' => false, // Não exibir botão de fechar
            'showCaption' => true, // Exibir legenda
            'overwriteInitial' => false, // Não substituir a visualização inicial
        ],
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success mt-3']) ?>
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
                        $.pjax.reload({container:'#categoriasGrid'});
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
