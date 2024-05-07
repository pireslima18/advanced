<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Clientes;
use frontend\models\ClientesSearch;
use frontend\models\Alugueis;
use frontend\models\Devolucoes;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ClientesController implements the CRUD actions for Clientes model.
 */
class ClientesController extends Controller
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
     * Lists all Clientes models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ClientesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Clientes model.
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
     * Creates a new Clientes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Clientes();

        // if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
        //     Yii::$app->response->format = 'json';
        //     return \yii\widgets\ActiveForm::validate($model);
        // }

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                // return $this->redirect(['view', 'id' => $model->id]);
                echo 'Success';
            } else {
                echo 'Error';
                // return $this->render('create', [
                //     'model' => $model,
                // ]);
            }
        } else {
            // $model->loadDefaultValues();
            return $this->renderAjax('create', [
                'model' => $model,
            ]);
        }

    }

    /**
     * Updates an existing Clientes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($this->request->isPost){
            if ($model->load($this->request->post()) && $model->save()) {
                // return $this->redirect(['view', 'id' => $model->id]);
                echo 'Success';
            }else{
                echo 'Error';
            }
        }else{
            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Clientes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $aluguelArray = Alugueis::find()->where(['id_cliente'=>$id])->all();
        $devolucoesArray = Devolucoes::find()->where(['id_cliente'=>$id])->all();
        if($this->findModel($id)->filmes_alugados === 0 && count($aluguelArray) === 0 && count($devolucoesArray) === 0){
            $this->findModel($id)->delete();
            Yii::$app->session->setFlash('success', 'Cliente apagado com sucesso!');
        }else{
            Yii::$app->session->setFlash('error', 'Não foi possível apagar ' . $this->findModel($id)->nome . ' pois ainda há registros vinculados a ele.');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Clientes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Clientes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Clientes::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
