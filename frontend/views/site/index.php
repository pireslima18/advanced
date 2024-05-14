<?php

/** @var yii\web\View $this */

use yii\helpers\Url;

$this->title = 'My Yii Application';

$imageUrl = Url::to('@web/uploads/images.jpg');

?>

<style>
    .card-img-top {
        height: 400px; /* Defina a altura desejada para as imagens */
        object-fit: cover; /* Ajusta a imagem para cobrir a área designada */
    }
</style>

<div class="site-index">
    <div class="p-5 mb-4 bg-transparent rounded-3">
        <div class="container-fluid py-5 text-center">
            <h1 class="display-4">Controle de Alugueis</h1>
            <p class="fs-5 fw-light">Uma maneira rápida e prática de controlar seu comércio.</p>
        </div>
    </div>

    <div class="body-content">

        <div class="container">
            <div class="row">
                <?php foreach($filmesModel as $filme){ ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="<?= Url::to('@web/' . $filme->logo) ?>" class="card-img-top" alt="<?= $filme->nome ?>">
                            <div class="card-body">
                                <img src="<?= Url::to('@web/' . $filme->classificacao->imagem_classificacao) ?>" class="img-fluid" width = "13px" height = "13px" alt="<?= $filme->nome ?>">
                                <h5 class="card-title"><?= $filme->nome ?></h5>
                                <p>Categoria: Ação</p>
                                <p>Classificação: L</p>
                                <p>Valor diária: R$ 10.45</p>
                                <a href="#" class="btn btn-primary">Alugar</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

    </div>
</div>
