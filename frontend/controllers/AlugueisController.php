<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Alugueis;
use frontend\models\AlugueisSearch;
use frontend\models\Clientes;
use frontend\models\Devolucoes;
use frontend\models\Filmes;
use frontend\models\Classificacoes;
use frontend\models\FilmesAlugados;
use frontend\models\FilmesDevolvidos;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use DateTime;

/**
 * AlugueisController implements the CRUD actions for Alugueis model.
 */
class AlugueisController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Alugueis models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AlugueisSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Alugueis model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Alugueis model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Alugueis();

        // if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
        //     Yii::$app->response->format = Response::FORMAT_JSON;
        //     return $this->renderAjax('create', [
        //         'model' => $model,
        //     ]);
        // }

        if ($this->request->isPost) {
            $objetoCliente = Clientes::findOne($this->request->post('Alugueis')['id_cliente']);
            // Obter idade do cliente
            $idade = $objetoCliente->obterIdadeCliente($objetoCliente);
            
            $idFilmesArray = $this->request->post('Alugueis')['id_filmes'];

            // Verificar classificação de Idade
            foreach($idFilmesArray as $idFilme){
                $objetoFilme = Filmes::findOne($idFilme);
                $objetoClassificacao = Classificacoes::findOne($objetoFilme->classificacao_id);
                // Obter classificação do filme
                $classificacao = $objetoClassificacao->classificacao;

                if($idade < $classificacao && $classificacao != 'L' ){
                    Yii::$app->session->setFlash('error', 'Você não tem idade suficiente para alugar este filme.');
                    $model->loadDefaultValues();
                    // return $this->renderAjax('create', [
                    //     'model' => $model,
                    // ]);
                    return "Idade insuficiente para alugar $objetoFilme->nome.";
                }
            }

            $dataInicio = DateTime::createFromFormat('d/m/Y', $this->request->post('Alugueis')['data_inicio']);
            $dataFim = DateTime::createFromFormat('d/m/Y', $this->request->post('Alugueis')['data_fim']);
            
            $dataTime1 = new DateTime($dataInicio->format('Y-m-d'));
            $dataTime2 = new DateTime($dataFim->format('Y-m-d'));
            
            $diferencaDias = ($dataTime1->diff($dataTime2))->days + 1;
            
            // Obter preço do aluguel
            $precoAluguel = 0;
            foreach($idFilmesArray as $idFilme){
                $objetoFilme = Filmes::findOne($idFilme);
                $precoFilme = $objetoFilme->valor_dia;
                $precoAluguel = $precoAluguel + $precoFilme;
            }

            $precoAluguel = $precoAluguel * $diferencaDias;
                
            if ($model->load($this->request->post())) {
                $model->id_filmes = 1;
                $model->preco_final = $precoAluguel;

                $model->save();

                // Salvar objeto no banco de devoluções
                foreach($idFilmesArray as $idFilme){
                    // echo $id_filme;
                    $objetoFilmesAlugados = new FilmesAlugados();
                    $objetoFilmesAlugados->id_aluguel = $model->id;
                    $objetoFilmesAlugados->id_filme = $idFilme;

                    $objetoFilmesAlugados->save();

                    $objetoCliente->filmes_alugados += 1;
                    $objetoCliente->save();

                    $objetoFilme = Filmes::findOne($idFilme);
                    $objetoFilme->status = 'Indisponível';
                    $objetoFilme->save();
                }
                // Somar total da lista de filmes alugados do cliente

                // return $this->redirect(['view', 'id' => $model->id]);
                echo 'Success';
            } else {
                // die;
                // return $this->render('create', [
                //     'model' => $model,
                // ]);
                echo 'Error';
            }
        } else {
            // $model->loadDefaultValues();
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }

    }

    /**
     * Updates an existing Alugueis model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Alugueis model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $objetoCliente = Clientes::findOne($this->findModel($id)->id_cliente);
        
        $filmesAlugadosArray = FilmesAlugados::find()->where(['id_aluguel' => $id])->all();
        foreach($filmesAlugadosArray as $filmeAlugado){
            // Retirar filme do cliente
            $objetoCliente->filmes_alugados -= 1;
            $objetoCliente->save();
            // Alterar status do filme
            $objetoFilme = Filmes::findOne($filmeAlugado->id_filme);
            $objetoFilme->status = 'Disponível';
            $objetoFilme->save();
            $filmeAlugado->delete();
        }
        
        if($this->findModel($id)->delete()){
            Yii::$app->session->setFlash('success', 'Registro apagado com sucesso!');
        }else{
            Yii::$app->session->setFlash('error', 'Não foi possível apagar esse aluguel pois ainda há registros vinculados a ele.');
        }

        return $this->redirect(['index']);
        
    }

    /**
     * Registrar devolução do aluguel
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionRegistrarDevolucao($id)
    {
        $objetoAluguel = $this->findModel($id);

        // Obter filmesAlugados
        $idFilmesArray = FilmesAlugados::find()->select('id_filme')->where(['id_aluguel' => $id])->all();
        
        $objetoAluguel = $this->findModel($id);
        $idCliente = $objetoAluguel['id_cliente'];
        $dataInicio = $objetoAluguel['data_inicio'];
        $dataFim = $objetoAluguel['data_fim'];
        $precoFinal = $objetoAluguel['preco_final'];

        // Calcula a diferença entre data_inicio e dataAtual
        $dataAtual = date('Y-m-d');
        $dataTime1 = new DateTime($dataInicio);
        $dataTime2 = new DateTime($dataAtual);
        $dataTime3 = new DateTime($dataFim);

        $comparativoAtualFinal = $dataTime2->diff($dataTime3);
        echo $dataAtual; 
        echo "<br>"; 
        echo $dataFim;
        echo "<br>"; 

        $valorTotalMulta = 0;
        if ($dataTime2 > $dataTime3) {
            // Aluguel ultrapassou a data prevista
            $diferencaDiasMulta = ($dataTime2->diff($dataTime3))->days;
            foreach($idFilmesArray as $idFilme){
                // Capturar valor da diaria do filme
                $objetoFilme = Filmes::findOne($idFilme);
                $valorDiaria = $objetoFilme['valor_dia'];
                $valorTotalMulta += ($diferencaDiasMulta * ($valorDiaria * (1/100)));
            }
            echo $valorTotalMulta;
        } elseif ($dataTime2 < $dataTime3) {
            // Aluguel foi devolvido antes da data prevista
            $diferencaDias = ($dataTime1->diff($dataTime2))->days + 1;
            echo "Diferença dias aluguel: " . $diferencaDias;
            echo "<br>";
            echo "<hr>";
            $precoFinal = 0;
            foreach($idFilmesArray as $idFilme){
                // Capturar valor da diaria do filme
                $objetoFilme = Filmes::findOne($idFilme);
                $valorDiaria = $objetoFilme['valor_dia'];
                $precoFinal += $diferencaDias * $valorDiaria;
            }
            echo $precoFinal;
        } else {
            // Aluguel devolvido no dia
        }

        $valorTotal = $precoFinal + $valorTotalMulta;
        $valorTotal = number_format($valorTotal, 2, '.', '');

        // Salvar objeto no banco de devoluções
        $objetoDevolucoes = new Devolucoes();
        $objetoDevolucoes->id_cliente = $idCliente;
        $objetoDevolucoes->id_filme = 1;
        $objetoDevolucoes->data_inicio = $dataInicio;
        $objetoDevolucoes->data_entrega = $dataAtual;
        $objetoDevolucoes->valor_pago = $precoFinal;
        $objetoDevolucoes->valor_multa = $valorTotalMulta;
        $objetoDevolucoes->valor_total =$valorTotal;
        $objetoDevolucoes->save();

        foreach($idFilmesArray as $idFilme){
            // Registrar filmes devolvidos
            $objetoFilmesDevolvidos = new FilmesDevolvidos();
            $objetoFilmesDevolvidos->id_devolucao = $objetoDevolucoes->id;
            $objetoFilmesDevolvidos->id_filme = $idFilme['id_filme'];
            $objetoFilmesDevolvidos->save();
            echo '<br>';
            echo '$objetoDevolucoes->id ' . $objetoDevolucoes->id;
            echo '<br>';
            echo '$idFilme ' . $idFilme['id_filme'];
            
            // Retirar total da lista de filmes alugados do cliente
            $objetoCliente = Clientes::findOne($idCliente);
            if($objetoCliente->filmes_alugados > 0){
                $objetoCliente->filmes_alugados -= 1;
                $objetoCliente->save();
            }
            
            // Alterar status do filme
            $objetoFilme = Filmes::findOne($idFilme);
            $objetoFilme->status = 'Disponível';
            $objetoFilme->save();

        }
        // Apagar FilmesAlugados
        FilmesAlugados::deleteAll(['id_aluguel' => $id]);

        // Apagar Aluguel
        $this->findModel($id)->delete();

        return $this->redirect(['devolucoes/index']);

    }

    /**
     * Finds the Alugueis model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Alugueis the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Alugueis::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
