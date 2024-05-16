<?php

/** @var yii\web\View $this */

use yii\helpers\Url;

$this->title = 'My Yii Application';

$imageUrl = Url::to('@web/uploads/images.jpg');

// echo "<pre>";
// print_r($filmesModel);
// die;

?>

<style>
    .card-img-top {
        height: 400px; /*Defina a altura desejada para as imagens*/
        object-fit: cover; /* Ajusta a imagem para cobrir a área designada */
    }
    .position-relative {
        position: relative;
    }
    .img-overlay {
        width: 70px;
        position: absolute;
        bottom: 10px;
        right: 10px;
    }
    .statusFilme{
        width: 100%;
        padding: 0;
        font-size: 16px
    }
    .statusFilme p{
        height: 25px;
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
        <?php foreach($filmesModel as $filme): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="position-relative">
                        <img src="<?= Url::to('@web/' . $filme->logo) ?>" class="card-img-top" alt="<?= $filme->nome ?>">
                        <div class="img-overlay d-flex align-items-end justify-content-end">
                            <img src="<?= Url::to('@web/' . $filme->classificacao->imagem_classificacao) ?>" class="img-fluid" alt="<?= $filme->nome ?>">
                        </div>
                    </div>

                    <div class="statusFilme">
                        <?php if($filme->status == 'Indisponível'){?>
                            <p class="text-center rounded-bottom text-white bg-danger fw-bold fst-italic">Indisponivel</p>
                        <?php } ?>
                        <?php if($filme->status == 'Disponível'){?>
                            <p class="text-center rounded-bottom text-white bg-success fw-bold fst-italic">Disponivel</p>
                        <?php } ?>    
                    </div>

                    <div class="card-body">
                        <h5 class="card-title"><?= $filme->nome ?></h5>
                        <p>Categoria: Ação</p>
                        <p>Valor diária: R$ 10.45</p>
                        <!-- <a href="#" class="btn btn-primary">Alugar</a> -->
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

    </div>
</div>
