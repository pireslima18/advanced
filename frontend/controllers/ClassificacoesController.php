<?php

namespace frontend\controllers;

use Yii;
use frontend\models\classificacoes;
use frontend\models\ClassificacoesSearch;
use frontend\models\Filmes;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ClassificacoesController implements the CRUD actions for classificacoes model.
 */
class ClassificacoesController extends Controller
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
     * Lists all classificacoes models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ClassificacoesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single classificacoes model.
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
     * Creates a new classificacoes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new classificacoes();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) /*&& $model->save()*/) {
                // return $this->redirect(['view', 'id' => $model->id]);
                $model->file = UploadedFile::getInstance($model, 'file');
                $imageName = $model->classificacao;
                if($model->file->saveAs('uploads/classificacoes/' . $imageName . '.' . $model->file->extension, false)){
                    $model->imagem_classificacao = 'uploads/classificacoes/' . $imageName . '.' . $model->file->extension;
                }

                if($model->save()){
                    echo 'Success';
                }else{
                    echo 'Error';
                }
            }else{
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
     * Updates an existing classificacoes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($this->request->isPost){
            if ($model->load($this->request->post()) /*&& $model->save()*/) {
                // return $this->redirect(['view', 'id' => $model->id]);
                $model->file = UploadedFile::getInstance($model, 'file');
                $imageName = $model->classificacao;
                if($model->file->saveAs('uploads/classificacoes/' . $imageName . '.' . $model->file->extension, false)){
                    $model->imagem_classificacao = 'uploads/classificacoes/' . $imageName . '.' . $model->file->extension;
                }

                if($model->save()){
                    echo "Success";
                }else{
                    echo "Erro";
                }
            }else{
                echo "Erro";
            }
        }else{
            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }

    }

    /**
     * Deletes an existing classificacoes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        // Procurar por filmes que utilizam a classificacao como dependencia
        $filmesArray = Filmes::find()->where(['classificacao_id'=>$id])->all();
        if(count($filmesArray) === 0){
            if($this->findModel($id)->imagem_classificacao !== null){
                unlink($this->findModel($id)->imagem_classificacao);
            }
            $this->findModel($id)->delete();
            Yii::$app->session->setFlash('success', 'Registro apagado com sucesso!');
        }else{
            Yii::$app->session->setFlash('error', 'Não foi possível apagar ' . $this->findModel($id)->classificacao . ' pois ainda há registros vinculados a ele.');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the classificacoes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return classificacoes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = classificacoes::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
