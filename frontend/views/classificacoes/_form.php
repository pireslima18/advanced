<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var frontend\models\classificacoes $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="classificacoes-form">

    <?php $form = ActiveForm::begin(['id'=>$model->formName()]); ?>

    <?= $form->field($model, 'classificacao')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success mt-3']) ?>
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
                // console.log(result);
                if(result === "Success"){
                    $(\$form).trigger("reset");
                    $(document).find('#modalCreate').modal('hide');
                    $.pjax.reload({container:'#categoriasGrid'});
                }else{
                    $("#modalCreateContent").html(result);
                }
            }).fail(function(){
                console.log('Server error');
            });
            return false;
        })

    JS;
    $this->registerJs($script);
?>
