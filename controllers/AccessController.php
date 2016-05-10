<?php

namespace app\controllers;

use app\models\Calendar;
use app\models\User;
use Yii;
use app\models\Access;
use app\models\search\CalendarSearch;
use app\models\search\AccessSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * AccessController implements the CRUD actions for Access model.
 */
class AccessController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['Myaccess', 'friends', 'dates', 'create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['Myaccess', 'friends', 'dates', 'create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Access models.
     * @return mixed
     */
    public function actionMyaccess()
    {
        $searchModel = new AccessSearch();
        $dataProvider = $searchModel->search([
            'AccessSearch' => [
                'user_owner' => Yii::$app->user->id
    ]
        ]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Access model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Access model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Access();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Access model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Access model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['myaccess']);
    }

    /**
     * Finds the Access model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Access the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Access::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Shows the Users with shared Dates for logged User
     *
     * @return string
     */
    public function actionFriends()
    {
        $searchModel = new AccessSearch();

        $dataProvider = $searchModel->search([
            'query' => Access::find()->withUserGuest(Yii::$app->user->id)->groupBy('user_owner')
        ]);

        return $this->render('friends', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionDates($id)
    {
        $searchModel = new AccessSearch();

        $dataProvider = $searchModel->search([
            'query' => Access::find()->withUserGuest(Yii::$app->user->id)->withUserOwner($id)->groupBy('date')
        ]);

        return $this->render('dates', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }


}
