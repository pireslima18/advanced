<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\bootstrap5\Modal;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-dark bg-dark fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Filmes', 'url' => ['/filmes/index']],
        ['label' => 'Clientes', 'url' => ['/clientes/index']],
        ['label' => 'Aluguéis', 'url' => ['/alugueis/index']],
        ['label' => 'Devolução', 'url' => ['/devolucoes/index']],
        ['label' => 'Categorias', 'url' => ['/categorias/index']],
        ['label' => 'Classificacoes', 'url' => ['/classificacoes/index']],
    ];

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav me-auto mb-2 mb-md-0'],
        'items' => $menuItems,
    ]);

    
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>

        <!-- <div class="text-center mb-4">
            <?= Html::a('Criar Cliente', ['clientes/create'], ['class' => 'btn btn-primary modelCreateButton']) ?>
            <?= Html::a('Criar Filme', ['filmes/create'], ['class' => 'btn btn-primary modelCreateButton']) ?>
            <?= Html::a('Criar Aluguel', ['alugueis/create'], ['class' => 'btn btn-primary modelCreateButton']) ?>
            <?= Html::a('Criar Categoria', ['categorias/create'], ['class' => 'btn btn-primary modelCreateButton']) ?>
            <?= Alert::widget() ?>
        </div> -->
        <?= $content ?>
    </div>

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
</main>

<footer class="footer mt-auto py-3 text-muted">
    <div class="container">
        <p class="float-start">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
        <p class="float-end"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
