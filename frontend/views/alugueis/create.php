<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\Alugueis $model */

$this->title = 'Criar Aluguel';
$this->params['breadcrumbs'][] = ['label' => 'Alugueis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="alugueis-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
