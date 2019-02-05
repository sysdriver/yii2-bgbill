<?php

namespace frontend\modules\bgbill\controllers;

use Yii;
use frontend\modules\bgbill\models\Contract;
use frontend\modules\bgbill\models\ContractSearch;
use frontend\modules\bgbill\models\ContractStatusLog;
use frontend\modules\bgbill\models\ContractAccount;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use frontend\modules\bgbill\models;
use frontend\modules\bgbill\models\ContractToSubscriber;
use frontend\modules\aresiptv\models\Subscriber;
use frontend\modules\aresiptv\models\SubscriberPackage;

/**
 * ContractController implements the CRUD actions for Contract model.
 */
class ContractController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        //do not update, create or delete records in FIAS manually !!!
                        'actions' => ['index','getfile','report-log','report-outflow',
                            'report-money','add-iptv','add-iptv-account'],
                        'allow' => true,
                        'roles' => ['viewBgbModule'],
                    ],
                    [
                        //do not update, create or delete records in FIAS manually !!!
                        'actions' => ['multiple-add-iptv',
                            'multiple-delete-iptv'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Contract models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ContractSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Contract model.
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
     * Creates a new Contract model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Contract();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Contract model.
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
     * Deletes an existing Contract model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    /**
     * Show ContractStatusLog report.
     * @return mixed
     */
    public function actionReportLog()
    {
        $searchModel = new ContractSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = new ContractStatusLog();
        $params =  Yii::$app->request->bodyParams;

        if(!empty($params['from_date']) && !empty($params['to_date'])) {
            $content = ContractStatusLog::getLogAsCsv($params['from_date'],$params['to_date']);
            
            if(empty($content)) {
                return;
            }
            
            $attachmentName = 'load.csv';
            Yii::$app->response-> sendContentAsFile ($content, $attachmentName);   //, $options = [] 
        } else {
            return $this->render('report-log', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }
    
    /**
     * Show Contract Outflow report.
     * @return mixed
     */
    public function actionReportOutflow()
    {
        $searchModel = new ContractSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = new ContractStatusLog();
        $params =  Yii::$app->request->bodyParams;
        
        if(!empty($_POST) ) {
            $content = ContractStatusLog::getOutflow();
            
            if(empty($content)) {
                echo 'empty';
                return;
            }
            
            $attachmentName = 'load.csv';
            Yii::$app->response-> sendContentAsFile ($content, $attachmentName);
        } else {
        
            return $this->render('report-outflow', [
                'model' => $model,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }
    
    /**
     * Show ContractStatusLog report.
     * 
     */
    public function actionGetfile()
    {
        $searchModel = new ContractSearch();
        print_r(Yii::$app->request->queryParams);
        die();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $model = new \frontend\models\bgbilling\ContractStatusLog();

        return $this->render('report-log', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionReportMoney()
    {
        $model = new ContractAccount();
        $items = ContractAccount::find()->indexBy('cid')->all();
        
        $rows = [
            0 => [
               'contract' => 1,
                'summ' => 200, 
                'status' => false,
            ],
        ];
        
        $provider = new ArrayDataProvider([
            'allModels' => $rows,
            'pagination' => [
                'pageSize' => 100,
                'route' => 'bgbill/contract/report-money'
            ],
        ]);


        
        return $this->render('report-money',compact('model','items','provider'));
        
    }
    
    /**
     * Add IPTV account on contract.
     * @return mixed
     */
    public function actionAddIptv()
    {
        $items = [];
        $model = new models\ContractIptv();
        $rqst = Yii::$app->request->queryParams;
        $cid = $rqst['cid'];
        print_r($rqst);
        
        if(!empty($cid)) {
            $contract = Contract::find()->where(['id' => $cid])->one();
            $model = new models\ContractIptv();
            $model = $model->setFieldsByContract($contract);
            $subscriber = $model->getSubscriberAcc();

            return $this->render('add-iptv',compact('items','model','subscriber'));
        } else {
            echo 'ERROR: no contract id get param!';
        }
    }
    
    /**
     * Add IPTV account.
     * @return mixed
     */
    public function actionAddIptvAccount()
    {
        $data = Yii::$app->request->post();     //Array ( [cid] => 3735 )
        $message = self::AddIptv($data['cid']);
        
        return $message;
    }
    
    
    /**
     * Add IPTV accounts.
     * @return mixed
     */
    public function actionMultipleAddIptv()
    {
        $userId = \Yii::$app->user->identity->id;
        $info = $userId == 1 ? 1 : 0;
       
        $limit = 0; //0 - no limit
        $items = ContractToSubscriber::getIptvContractsFromBg($limit);
        $message = '';

        foreach ($items as $k => $cid) {
            //print_r($cid);
            $message .= self::AddIptv($cid['id']);
        }        

        return $this->render('multiple-add-iptv',compact('items','info','message'));
    }
    
            
    /**
     * Add one IPTV account on bgbilling contract.
     * @param int $cid contract id
     * @return string $message
     */
   public static function AddIptv($cid,$status=null)
   {
        $c2sub = ContractToSubscriber::find()->where(['cid' => $cid])->one();
        //если нет записи с активной связкой с учеткой IPTV, добавляем
        if(empty($c2sub->subscr_id)) {
            //print_r($cid);
            $item = Contract::find()->where(['status' => !is_null($status)?$status:[0,1,2,3],'id' => $cid])->one();
            $attributes =  $item->getAttributes();
            print_r($attributes);
            Contract::saveContrToSubscriber($attributes);
            $message .= 'Для договора '.$item->title.' была добавлена учетная запись на портал IPTV('.$cid.')<br>';
        } else {
            $message .= 'У клиента '.$c2sub->cname.' уже есть учетная запись на портале IPTV('.$cid.')<br>';
        }
        
        return $message;
   }
    
    /**
     * Delete IPTV accounts.
     * @return mixed
     */
    public function actionMultipleDeleteIptv()
    {
        $userId = \Yii::$app->user->identity->id;
        $info = $userId == 1 ? 1 : 0;
        
        $items = ContractToSubscriber::find()
            ->where('subscr_id != :val',['val' => 0])
            ->limit(500)
            ->all();

        foreach ($items as $k => $item) {            
            $subscrId = $item->subscr_id;
            $sbscr = Subscriber::find()
                ->where('id = :val',['val' => $subscrId])
                ->one();
            
            //удаляем связанные данные - пакеты:
            $sbscrPkgs = SubscriberPackage::find()
                ->where('subscriber_id = :val',['val' => $subscrId])
                ->all();
            
            foreach ($sbscrPkgs as $k => $sbscrPkg) {
                if(!empty($sbscrPkg->subscriber_id)) {
                    if($sbscrPkg->delete()) {
                        
                    } else {
                        echo "Can't delete package!!!";
                    }
                }
            }
            
            if(!empty($sbscr->id)) {
                if($sbscr->delete()) {
                    $item->subscr_id = 0;   //обнуляем привязку к клиенту IPTV
                    $item->save();
                    
                } else {
                    echo "Can't delete subscriber!!!";
                }
            }

            echo 'Удален клиент с кодом '.$subscrId.'<br>';
            
        }
        
        return $this->render('multiple-delete-iptv',compact('items','info'));
        
    }
    
    /**
     * Finds the Contract model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Contract the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Contract::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
