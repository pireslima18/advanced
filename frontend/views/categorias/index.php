<?php

use frontend\models\Categorias;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\bootstrap5\Modal;
use yii\widgets\Pjax;
use kartik\icons\Icon;
Icon::map($this);

/** @var yii\web\View $this */
/** @var frontend\models\CategoriasSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Categorias';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="categorias-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= Html::a('Criar Categoria', ['categorias/create'], ['class' => 'btn btn-primary modelCreateButton my-3']) ?>
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
    
    <?php Pjax::begin(['id'=>'categoriasGrid']); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            
            'nome_categoria',
            // [
            //     'class' => ActionColumn::className(),
            //     'urlCreator' => function ($action, Categorias $model, $key, $index, $column) {
            //         return Url::toRoute([$action, 'id' => $model->id]);
            //     }
            // ],
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
