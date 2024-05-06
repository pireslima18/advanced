<?php

use frontend\models\Devolucoes;
use frontend\models\Filmes;
use frontend\models\FilmesDevolvidos;
use frontend\models\Clientes;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

use yii\jui\DatePicker;

/** @var yii\web\View $this */
/** @var frontend\models\DevolucoesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Devolucoes';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="devolucoes-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'id_cliente',
                'value' => 'cliente.nome',
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'id_cliente',
                    ArrayHelper::map(
                        Clientes::find()->all(),
                        'id',
                        'nome'
                    ),
                    ['class' => 'form-control', 'prompt' => 'Todos']
                ),
            ],
            [
                'label'=>'Filmes',
                'value' => function ($data) 
                {
                    $arr = FilmesDevolvidos::find()->select('id_filme')->where(['id_devolucao'=>$data->id])->all();
                    $valores = "";
                    foreach ($arr as $value)
                    {
                        $filme = Filmes::find()->where(['id'=>$value->id_filme])->one();
                        $valores .= $filme->nome . ', ';
                    }
                    return $valores;
                },
            ],
            [
                'attribute' => 'data_inicio',
                'format' => ['date', 'php:d/m/Y'],
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'data_inicio',
                    'language' => 'pt',
                    'dateFormat' => 'yyyy-MM-dd',
                    'options' => ['class' => 'form-control']
                ]),
            ],
            [
                'attribute' => 'data_entrega',
                'format' => ['date', 'php:d/m/Y'],
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'data_entrega',
                    'language' => 'pt',
                    'dateFormat' => 'yyyy-MM-dd',
                    'options' => ['class' => 'form-control']
                ]),
            ],
            [
                'attribute' => 'valor_pago',
                'header' => 'Valor a Pagar',
                'value' => function ($model) {
                    return Yii::$app->formatter->asCurrency($model->valor_pago, 'R$');
                },
                'contentOptions' => ['class' => 'text-right'],
            ],
            [
                'attribute' => 'valor_multa',
                'header' => 'Valor Multa',
                'value' => function ($model) {
                    return Yii::$app->formatter->asCurrency($model->valor_multa, 'R$');
                },
                'contentOptions' => ['class' => 'text-right'],
            ],
            [
                'attribute' => 'valor_total',
                'header' => 'Valor Total',
                'value' => function ($model) {
                    return Yii::$app->formatter->asCurrency($model->valor_total, 'R$');
                },
                'contentOptions' => ['class' => 'text-right'],
            ],
            // [
            //     'format' => 'raw',
            //     'value' => function($model) {
            //         return Html::a('Apagar', ['delete', 'id' => $model->id], [
            //             'class' => 'btn btn-danger',
            //             'data' => [
            //                 'confirm' => 'Deseja apagar esse item?',
            //                 'method' => 'post',
            //             ],
            //         ]);
            //     }
            // ],
        ],
    ]); ?>


</div>
