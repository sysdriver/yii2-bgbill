<?php

namespace frontend\modules\bgbill\models;

use frontend\modules\aresiptv\models;

use Yii;

/**
 * This is the model class for table "bg_contract_to_subscriber".
 *
 * @property integer $id
 * @property integer $cid
 * @property integer $subscr_id
 * @property integer $domain_id
 * @property string $username
 * @property string $password
 * @property string $first_name
 * @property string $middle_name
 * @property string $surname
 * @property string $added_on
 * @property integer $max_terminal
 * @property integer $disabled
 * @property integer $address
 * @property integer $cname
 */
class ContractToSubscriber extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bg_contract_to_subscriber';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cid', 'username', 'password', 'first_name', 'added_on'], 'required'],   //'subscr_id', 'middle_name', 'surname', 
            [['cid', 'subscr_id', 'domain_id', 'max_terminal', 'disabled', 'address'], 'integer'],
            [['username', 'password'], 'string', 'max' => 32],
            [['first_name', 'middle_name', 'surname'], 'string', 'max' => 100],
            [['cid'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cid' => 'Cid',
            'subscr_id' => 'Subscr ID',
            'domain_id' => 'Domain ID',
            'username' => 'Username',
            'password' => 'Password',
            'first_name' => 'First Name',
            'middle_name' => 'Middle Name',
            'surname' => 'Surname',
            'added_on' => 'Added On',
            'max_terminal' => 'Max Terminal',
            'disabled' => 'Disabled',
            'address' => 'Address',
            'cname' => 'Client Name',
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function addSubscriber()
    {
        $aresDb = Yii::$app->get('dbaresiptv');
        $sbcbr = models\Subscriber::find()
            ->where(['username' => $this->username])
            ->one();

        if(empty($sbcbr)) {            
            $sql = "INSERT INTO subscriber (domain_id, username, password, first_name, 
                middle_name, surname, added_on, max_terminal, disabled, address)
            values (62, '".$this->username."', '".$this->password."',
            '".$this->first_name."', '".$this->middle_name."',
            '".$this->surname."', '".$this->added_on."', ".$this->max_terminal.", False, 0)";

            $command = $aresDb->createCommand($sql);        //   ->execute();
            $dbRes = $command->execute();
            $id = $aresDb->getLastInsertID('subscriber_id_seq');
            
            if(!empty($id)) {
                $this->subscr_id = $id;
                $this->save();
                
                $sbcbr = models\Subscriber::find()
                    ->where(['username' => $this->username])
                    ->one();
                $sbcbr->addPackages();
            }
        } else {
            echo 'Already exists in db. Subscriber id = ';
            $sbcbr->addPackages();
        }
    }
    
    /**
     * Получаем id всех контрактов из BG billing, на которых подключен тариф IPTV
     */
    public static function getIptvContractsFromBg($limit=0)
    {
        $sql = "SELECT c.id FROM contract c
                LEFT JOIN contract_tariff ct ON ct.cid = c.id
                LEFT JOIN tariff_plan tp ON tp.id = ct.tpid
                WHERE tp.id IN (59,64) 
                AND c.status=0 -- только активные
                GROUP BY c.id"
                .(!empty($limit)?" LIMIT ".$limit:"");
        
        $bgDb = Yii::$app->get('dbbgbilling');
        $command = $bgDb->createCommand($sql);        //   ->execute();
        $rows = $command->queryAll();

        return $rows;
    }
}
