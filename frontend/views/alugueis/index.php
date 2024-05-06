<?php

use frontend\models\Alugueis;
use frontend\models\Clientes;
use frontend\models\Filmes;
use frontend\models\FilmesAlugados;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\jui\DatePicker;
use yii\bootstrap5\Modal;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var frontend\models\AlugueisSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Alugueis';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="alugueis-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= Html::a('Criar Aluguel', ['alugueis/create'], ['class' => 'btn btn-primary modelCreateButton my-3']) ?>
    <!-- Modal -->
    <?php
        Modal::begin([
            'id' => 'modalCreate',
            'size' => 'modal-lg',
            // 'clientOptions' => ['backdrop' => 'static', 'keyboard' => TRUE]
        ]);
        echo '<div id="modalCreateContent"></div>';
        Modal::end();
    ?>
    <!-- End Modal -->
    
    <?php Pjax::begin(['id'=>'alugueisGrid']); ?>
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
            ['label'=>'Filmes',
            'value' => function ($data) 
                {
                    $arr = FilmesAlugados::find()->select('id_filme')->where(['id_aluguel'=>$data->id])->all();
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
                    'dateFormat' => 'yyyy/MM/dd',
                    'options' => ['class' => 'form-control'],
                ]),
            ],
            [
                'attribute' => 'data_fim',
                'format' => ['date', 'php:d/m/Y'],
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'data_fim',
                    'language' => 'pt',
                    'dateFormat' => 'yyyy/MM/dd',
                    'options' => ['class' => 'form-control'],
                ]),
            ],
            [
                'attribute' => 'preco_final',
                'value' => function ($model) {
                    return Yii::$app->formatter->asCurrency($model->preco_final, 'R$');
                },
                'contentOptions' => ['class' => 'text-right'],
            ],
            [
                'attribute' => 'status',
                'filterOptions' => ['class' => 'd-none']
            ],
            [
                'format' => 'raw',
                'value' => function($model) {
                    return Html::a('Confirmar Devolução', ['registrar-devolucao', 'id' => $model->id], [
                        'class' => 'btn btn-success',
                        'data' => [
                            'confirm' => 'Deseja marcar esse item como devolvido?',
                            'method' => 'post',
                        ],
                    ]);
                }
            ],
        ],
    ]); ?>

    <?php
        $script = <<< JS

        $('.modelEditButton').click(function (e){
            e.preventDefault();
            $.get($(this).attr('href'), function(data) {
                $('#modalCreate').modal('show').find('#modalCreateContent').html(data)
            });
            document.getElementById('modalHeader').innerHTML = '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h4>' + $(this).attr('title') + '</h4>';
            return false;
        });

        JS;
        $this->registerJs($script);
    ?>
    
    <?php Pjax::end(); ?>


</div>
