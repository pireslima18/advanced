<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var frontend\models\Filmes $model */

$this->title = $model->nome;
$this->params['breadcrumbs'][] = ['label' => 'Filmes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="filmes-view">

    <h1 class="mb-3"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Atualizar', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Deletar', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Tem certeza que deseja deletar esse item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'nome',
            'categoria_id',
            [
                'attribute' => 'classificacao.classificacao',
                'header' => 'Classificação',
                'filter' => Html::activeDropDownList(
                    $model,
                    'classificacao_id',
                    ArrayHelper::map(
                        $model::find()->all(),
                        'classificacao_id',
                        'classificacao.classificacao'
                    ),
                    ['class' => 'form-control', 'prompt' => 'Todos']
                ),
            ],
            [
                'attribute' => 'valor_dia',
                'header' => 'Valor Dia',
                'value' => function ($model) {
                    return Yii::$app->formatter->asCurrency($model->valor_dia, 'R$');
                },
                'contentOptions' => ['class' => 'text-right'],
            ],
            'status',
        ],
    ]) ?>

</div>
