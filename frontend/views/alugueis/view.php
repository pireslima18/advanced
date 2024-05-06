<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var frontend\models\Alugueis $model */

$this->title = $model->cliente->nome . ', ' . $model->filme->nome;
$this->params['breadcrumbs'][] = ['label' => 'Alugueis', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="alugueis-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Alterar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Confirmar Devolução', ['registrar-devolucao', 'id' => $model->id], [
            'class' => 'btn btn-success',
            'data' => [
                'confirm' => 'Deseja marcar esse item como devolvido?',
                'method' => 'post',
            ],
        ]) ?>
        <?= Html::a('Deletar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'cliente.nome',
            'filme.nome',
            [
                'attribute' => 'data_inicio',
                'format' => ['date', 'php:d/m/Y'],
            ],
            [
                'attribute' => 'data_fim',
                'format' => ['date', 'php:d/m/Y'],
            ],
            'status',
        ],
    ]) ?>

</div>
