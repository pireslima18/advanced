<?php

namespace frontend\controllers;

use frontend\models\Filmes;
use frontend\models\FilmesSearch;
use frontend\models\FilmesAlugados;
use frontend\models\FilmesDevolvidos;
use frontend\models\Devolucoes;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;
use Yii;


/**
 * FilmesController implements the CRUD actions for Filmes model.
 */
class FilmesController extends Controller
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
     * Lists all Filmes models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new FilmesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Filmes model.
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
     * Creates a new Filmes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Filmes();

        // if (Yii::$app->request->isPost) {
        //     $model->file = UploadedFile::getInstance($model, 'file');
        //     if ($model->upload()) {
        //         // file is uploaded successfully
        //         return;
        //     }
        // }

        // echo "die";
        // die;

        // if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
        //     Yii::$app->response->format = Response::FORMAT_JSON;;
        //     return ActiveForm::validate($model);
        // }

        
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) /*&& $model->save()*/) {
                $model->file = UploadedFile::getInstance($model, 'file');
                $imageName = $model->nome;
                if($model->file->saveAs('uploads/' . $imageName . '.' . $model->file->extension, false)){
                    $model->logo = 'uploads/' . $imageName . '.' . $model->file->extension;
                }
                if($model->save('false')){
                    echo 'Success';
                }else{
                    echo 'Error';
                }
                // return $this->redirect(['view', 'id' => $model->id]);
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
     * Updates an existing Filmes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if($this->request->isPost){
            if ($model->load($this->request->post()) /*&& $model->save()*/) {
                $model->file = UploadedFile::getInstance($model, 'file');
                $imageName = $model->nome;
                if($model->file->saveAs('uploads/' . $imageName . '.' . $model->file->extension, false)){
                    $model->logo = 'uploads/' . $imageName . '.' . $model->file->extension;
                }
                if($model->save()){
                    echo 'Success';
                }else{
                    echo 'Error';
                }
                // return $this->redirect(['view', 'id' => $model->id]);
            }else{
                echo 'Error';
            }
        } else{
            return $this->renderAjax('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Filmes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $filmesAlugadosArray = FilmesAlugados::find()->where(['id_filme'=>$id])->all();
        $filmesDevolvidosArray = FilmesDevolvidos::find()->where(['id_filme'=>$id])->all();
        $devolucoesArray = Devolucoes::find()->where(['id_filme'=>$id])->all();

        if($this->findModel($id)->status === 'Disponível' && count($filmesAlugadosArray) === 0 && count($devolucoesArray) === 0 && count($filmesDevolvidosArray) === 0){
            if($this->findModel($id)->logo !== null){
                unlink($this->findModel($id)->logo);
            }
            $this->findModel($id)->delete();
            Yii::$app->session->setFlash('success', 'Filme excluído com sucesso.');
        }else{
            Yii::$app->session->setFlash('error', 'Não foi possível apagar ' . $this->findModel($id)->nome . ' pois ainda há registros vinculados a ele.');
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Filmes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Filmes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Filmes::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
