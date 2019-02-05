<?php

namespace frontend\modules\bgbill\models;

use Yii;
use frontend\modules\aresiptv\models;

/**
 * This is the model class for table "contract".
 *
 * @property integer $cid
 * @property integer $subscriber_id
 * @property integer $subscriber_username
 * 
 * 1176			id	int(10) unsigned	false		код договора	
		1177			gr	bigint(20)	false	0	битовая маска групп	
		1178			title	varchar(150)	false		название договора	
		1179			pswd	varchar(32)	false		пароль доступа к статистике	
		1180			date1	date	true		дата начала действия	
		1181			date2	date	true		дата окончания действия	
		1182			mode	tinyint(4)	false	0	режим баланса 0 - кредит, 1 - дебет	
		1183			closesumma	decimal(10,2)	false		лимит	
		1184			pgid	int(11)	false	0	группа параметров	
		1185			pfid	int(11)	false	0	фирма	
		1186			fc	tinyint(1)	false	0	0 - физ. лицо, 1 - юр. лицо	
		1187			comment	varchar(100)	false		примечание	
		1188			del	tinyint(1)	false	0	1 - договор скрыт	
		1189			scid	int(11)	false	0	код супердоговора, 0 - независимый, -1 - супердоговор	
		1190			sub_list	text	false		список кодов зависимых субдоговоров через запятую	
		3833			status	tinyint(4)	false	0	статус договора: 0 - подключен, 1 - на отключении, 2 - отключен, 3 - закрыт, 4 - приостановлен, 5 - на подключении	
		3834			status_date	date	true		дата смены статуса договора	
		3835			last_tariff_change	datetime	true		дата изменения тарифа	
		3913			title_pattern_id	int(11)	false	0	id шаблона комментария	
		3935			sub_mode	tinyint(4)	false	0	0 - субдоговор с зависимым балансом, 1 - с независимым.	
		4310			crm_customer_id	int(11)	false		
 */
class ContractIptv extends \frontend\modules\bgbill\components\BgModel
{
    public $id;
    public $title;
    public $pswd;
    public $date1;
    public $date2;
    public $closesumma;
    public $mode;
    public $fc;
    public $comment;
    public $scid;
    public $status;
    public $status_date;    

    //поля исключений 
    public static $xcpFields = ['gr','mode','pgid','pfid','del','sub_list','last_tariff_change','title_pattern_id','sub_mode','crm_customer_id'];
    public static $prefix = '094';   //'bg'

    public function rules()
    {
        return [
            [['id','mode','fc','scid','status'], 'integer'],
            [['title','pswd','comment'], 'string'],
            [['date1','date2','comment'], 'safe'],
            [['pswd'], 'safe'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'pswd' => 'Pswd',
            'date1' => 'Date1',
            'date2' => 'Date2',
            'closesumma' => 'Closesumma',
            'fc' => 'Fc',
            'comment' => 'Comment',
            'scid' => 'Scid',
            'status' => 'Status',
            'status_date' => 'Status Date',
        ];
    }
    
    /**
     * Get all fields from contract
     * @param object $contract
     */
    public function setFieldsByContract($contract)
    {
        if(empty($contract->id)) {
            return;
        }

        foreach ($contract as $key => $val) {
            if(!in_array($key, self::$xcpFields)) {
                $this->$key = $val;
            }
        }

        return $this;
    }
    
    /**
     * Get subscriber records from IPTV portal
     * @param object $contract
     */
    public function getSubscriberAcc()
    {
        if(!empty($this->title)) {
            $login = self::getIptvPrefixedLogin($this->title);
        } else {
            return ;
        }
        print_r($login);
        
        $subscriber = models\Subscriber::find()
            ->where(['username' => $login])
            ->one();
        
        
        return $subscriber;
    }
    
    /**
     * get Iptv Login With Prefix
     */
    public static function getIptvPrefixedLogin($title)
    {
        if(empty($title))
            return ;
        
        return sprintf(self::$prefix."%'.06s",trim($title));
    }

}
