<?php

namespace app\controllers;

use Yii;
use app\models\Calendar;
use app\models\Access;
use app\models\search\AccessSearch;
use app\models\search\CalendarSearch;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CalendarController implements the CRUD actions for Calendar model.
 */
class CalendarController extends Controller
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
                'only' => ['mycalendar', 'shared','create', 'update', 'delete'],
                'rules' => [
                    [
                        'actions' => ['mycalendar', 'shared', 'create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],

        ];
    }

    /**
     * Displays a single Calendar model.
     *
     * @param $id
     * @return string
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $result = Access::checkAccess($model);

        if($result)
        {
            switch($result) {
                case Access::ACCESS_CREATOR:
                    return $this->render('viewCreator', [
                        'model' => $model,
                    ]);
                    break;
                case Access::ACCESS_GUEST:
                    return $this->render('viewGuest', [
                        'model' => $model,
                    ]);
                    break;
            }
        }
        throw new ForbiddenHttpException("Not allowed! ");

    }

    /**
     * Creates a new Calendar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Calendar();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Calendar model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param $id
     * @return string|\yii\web\Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (Access::checkIsCreator($model)) {

            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
        throw new ForbiddenHttpException("Not allowed to update event of other user", 403);
    }

    /**
     * Deletes an existing Calendar model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return mixed
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if (Access::checkIsCreator($model)) {
            $model->delete();
            return $this->redirect(['mycalendar']);
        }
        throw new ForbiddenHttpException("Not allowed to delete event of other user", 403);
    }

    /**
     * Finds the Calendar model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Calendar the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Calendar::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Lists user Calendar models.
     *
     * @return string
     */
    public function actionMycalendar()
    {
        $searchModel = new CalendarSearch();

        $dataProvider = $searchModel->search([
            'CalendarSearch'=> [
                'creator' => Yii::$app->user->id
                ]
            ]);

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);

    }

    /**
     * Routes to the shared events view with selected user_owner
     * and share date for logged user as user_guest
     *
     * @param $id
     * @param $date
     * @return string
     */
    public function actionShared($id, $date)
    {
        $searchModel = new CalendarSearch();

        $dataProvider = $searchModel->search([
            'query' => Calendar::find()->withCreator($id)->withDate($date)
        ]);

        return $this->render('shared', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'id' => $id,
            'date' => $date
        ]);
    }
}
