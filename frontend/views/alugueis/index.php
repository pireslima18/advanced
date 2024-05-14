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

    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= Yii::$app->session->getFlash('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= Yii::$app->session->getFlash('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    
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
            [
            'label'=>'Filmes',
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
                'class' => ActionColumn::className(),
                'template' => '{edit} {delete}',
                'contentOptions' => ['class' => 'text-center'],
                'buttons' => [
                    'edit' => function ($url, $model, $key) {
                        return Html::a(
                            'Confirmar devolução',
                            ['registrar-devolucao', 'id' => $model->id],
                            ['title' => 'Editar', 'id' => 'edit_' . $model->id, 'class' => 'modelEditButton btn btn-success']
                        );
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a(
                            'Apagar',
                            ['delete', 'id' => $model->id],
                            [
                                'title' => 'Deletar',
                                'id' => 'delete_' . $model->id,
                                'data-confirm' => 'Tem certeza de que deseja excluir este item?',
                                'data-method' => 'post',
                                'class' => 'btn btn-danger'
                            ]
                        );
                    },
                ],
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
