<?php

namespace frontend\modules\bgbill\models;

use Yii;

/**
 * This is the model class for table "contract".
 *
 * @property integer $id
 * @property integer $gr
 * @property string $title
 * @property integer $title_pattern_id
 * @property string $pswd
 * @property string $date1
 * @property string $date2
 * @property integer $mode
 * @property string $closesumma
 * @property integer $pgid
 * @property integer $pfid
 * @property integer $fc
 * @property string $comment
 * @property integer $del
 * @property integer $scid
 * @property string $sub_list
 * @property integer $sub_mode
 * @property integer $status
 * @property string $status_date
 * @property string $last_tariff_change
 * @property integer $crm_customer_id
 */
class Contract extends \frontend\modules\bgbill\components\BgActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contract';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('dbbgbilling');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['gr', 'title_pattern_id', 'mode', 'pgid', 'pfid', 'fc', 'del', 'scid', 'sub_mode', 'status', 'crm_customer_id'], 'integer'],
            [['title_pattern_id', 'closesumma', 'sub_list', 'sub_mode'], 'required'],
            [['date1', 'date2', 'status_date', 'last_tariff_change'], 'safe'],
            [['closesumma'], 'number'],
            [['sub_list'], 'string'],
            [['title'], 'string', 'max' => 150],
            [['pswd'], 'string', 'max' => 32],
            [['comment'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gr' => 'Gr',
            'title' => 'Title',
            'title_pattern_id' => 'Title Pattern ID',
            'pswd' => 'Pswd',
            'date1' => 'Date1',
            'date2' => 'Date2',
            'mode' => 'Mode',
            'closesumma' => 'Closesumma',
            'pgid' => 'Pgid',
            'pfid' => 'Pfid',
            'fc' => 'Fc',
            'comment' => 'Comment',
            'del' => 'Del',
            'scid' => 'Scid',
            'sub_list' => 'Sub List',
            'sub_mode' => 'Sub Mode',
            'status' => 'Status',
            'status_date' => 'Status Date',
            'last_tariff_change' => 'Last Tariff Change',
            'crm_customer_id' => 'Crm Customer ID',
        ];
    }
    
    /**
     * Готовим массив для записи на портал IPTV
     * @inheritdoc
     */
    public static function prepContractArr($attributes)
    {
        $login = ContractIptv::getIptvPrefixedLogin($attributes['title']);
        $cntrct = [
            'cid'           => $attributes['id'],
            'subscr_id'     => 0,
            'username'      => $login,
            'password'      => (string) rand(10000,99999),
            'added_on'      => date("Y-m-d"),
            'domain_id'     => 62,
            'max_terminal'  => 5,
            'disabled'      => 0,
            'address'       => 0,
            'surname'       => '',
            'first_name'    => '',
            'middle_name'   => '', 
        ];

        $name = trim($attributes['comment']);

        if($attributes['fc'] == 0) {
            //FL
            $fio_arr = explode(" ", $name);
            $fio_arr = array_values(array_filter($fio_arr));
            
            $names = [
                'surname'    => $fio_arr[0],
                'first_name'    => $fio_arr[1],
                'middle_name'    => $fio_arr[2],
            ];

            $cntrct = array_merge($cntrct,$names);


        } else {
            //UL
            $cntrct['first_name'] = $name;
        }
            
        return $cntrct;
    }
    
    /**
     * Готовим массив для записи на портал IPTV и добавляем во временную таблицу
     * Пишем на портал, если запись уже существует
     * @inheritdoc
     */
    public static function saveContrToSubscriber($attributes)
    {
        $locDb = Yii::$app->get('db');

        //check if contract exists in db
        $cid = $attributes['id'];
        $item = ContractToSubscriber::find()
            ->where(['cid' => $cid])
            ->all();
        
        //print_r($attributes);
        
        if(empty($item)) {
            //insert
            //готовим массив для записи
            $contract = self::prepContractArr($attributes);
            $c2sub = new ContractToSubscriber();

            foreach ($contract as $fName => $fValue) {
                $c2sub->$fName = $fValue;
            }
            
            if($c2sub->save()) {
                $item = ContractToSubscriber::find()
                    ->where(['cid' => $cid])
                    ->all();
                $item[0]->addSubscriber();
            }
            
        } else {
            //обновление существующей записи
            $contract = self::prepContractArr($attributes);
            foreach ($contract as $fName => $fValue) {
                $item[0]->$fName = $fValue;
            }
            
            if($item[0]->save()) {
                $item[0]->addSubscriber();
            }
        }
    }
        
    public static function addSubscriberWithPackets($c2sub)
    {
        //$attributes = $c2sub->getAttributes();       
    }
}
