<?php

use frontend\models\Filmes;
use frontend\models\Categorias;
use frontend\models\Classificacoes;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\bootstrap5\Modal;
use yii\widgets\Pjax;
use kartik\icons\Icon;
use kartik\select2\Select2;
Icon::map($this);

/** @var yii\web\View $this */
/** @var frontend\models\FilmesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Filmes';
$this->params['breadcrumbs'][] = $this->title;

  
// echo "<pre>:";
// print_r($dataProvider);
// die;

?>
<div class="filmes-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= Html::a('Criar Filme', ['filmes/create'], ['class' => 'btn btn-primary modelCreateButton my-3']) ?>
    
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

    <?php Pjax::begin(['id'=>'filmesGrid']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'nome',
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'nome',
                    'data' => ArrayHelper::map(Filmes::find()->select(['nome'])->distinct()->all(), 'nome', 'nome'),
                    'language' => 'pt',
                    'options' => ['placeholder' => 'Selecione um nome ...'],
                    'pluginOptions' => [
                        'allowClear' => true,
                    ],
                ]),
            ],
            [
                'attribute' => 'categoria.nome_categoria',
                'value' => 'categoria.nome_categoria', // Garantindo que a coluna seja exibida corretamente
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'categoria_id',
                    'data' => ArrayHelper::map(
                        Categorias::find()->asArray()->all(),
                        'id',
                        'nome_categoria'
                    ),
                    'language' => 'pt',
                    'options' => [
                        'placeholder' => 'Selecionar Categoria',
                        'multiple' => false,
                    ],
                    'pluginOptions' => [
                        'allowClear' => true,
                        'closeOnSelect' => false,
                        'tokenSeparators' => [','],
                        // 'language' => [
                        //     'noResults' => new \yii\web\JsExpression("function () { return 'Nenhum resultado encontrado'; }"),
                        // ],
                    ],
                ]),
            ],


            [
                'attribute' => 'classificacao.classificacao',
                'value' => 'classificacao.classificacao', // Adicionando esta linha para garantir que a coluna seja exibida corretamente
                'filter' => Html::activeDropDownList(
                    $searchModel,
                    'classificacao_id',
                    ArrayHelper::map(
                        Classificacoes::find()->asArray()->all(),
                        'id',
                        'classificacao'
                    ),
                    ['class' => 'form-control', 'prompt' => 'Todos']
                ),
            ],
            [
                'attribute' => 'valor_dia',
                'value' => function ($model) {
                    return Yii::$app->formatter->asCurrency($model->valor_dia, 'R$');
                },
                'contentOptions' => ['class' => 'text-right'],
            ],
            [
                'attribute' => 'status',
                'contentOptions' => function ($model, $key, $index, $column) {
                    if ($model->status === 'Indisponível') {
                        return ['class' => 'bg-danger text-white'];
                    } else {
                        return [];
                    }
                },
            ],
            [
                'class' => ActionColumn::className(),
                'template' => '{edit} {delete}',
                'contentOptions' => ['class' => 'text-center'],
                'buttons' => [
                    'edit' => function ($url, $model, $key) {
                        return Html::a(
                            'Editar',
                            ['update', 'id' => $model->id],
                            ['title' => 'Editar', 'id' => 'edit_' . $model->id, 'class' => 'modelEditButton btn btn-primary']
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
