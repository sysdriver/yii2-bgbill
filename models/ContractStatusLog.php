<?php

namespace frontend\modules\bgbill\models;

use Yii;

/**
 * This is the model class for table "contract_status_log".
 *
 * @property integer $id
 * @property integer $status
 * @property integer $uid
 * @property string $date
 * @property string $comment
 * @property integer $cid
 * @property string $date1
 * @property string $date2
 */
class ContractStatusLog extends \frontend\modules\bgbill\components\BgActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contract_status_log';
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
            [['status', 'uid', 'date', 'comment', 'date1'], 'required'],
            [['status', 'uid', 'cid'], 'integer'],
            [['date', 'date1', 'date2'], 'safe'],
            [['comment'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'uid' => 'Uid',
            'date' => 'Date',
            'comment' => 'Comment',
            'cid' => 'Cid',
            'date1' => 'Date1',
            'date2' => 'Date2',
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function getLogAsCsv($bDate,$eDate)
    {
        if(empty($bDate) || empty($eDate)) {
            return;
        }
        
        $bDate = date('Y-m-d',strtotime($bDate));
        $eDate = date('Y-m-d',strtotime($eDate));
        
        
        $conn = self::getDb();
        
        $sql = "SELECT cnt.title,cnt.comment, csl.date,csl.comment,
            csl.status cslstatus, IF(csl.status=0,'подключен',IF(csl.status=1,'на отключении',
                IF(csl.status=2,'отключен (расторжение)',
                    IF(csl.status=3,'закрыт',
                    IF(csl.status=4,'приостановлен',IF(csl.status=5,'на подключении','неизвестный'))
                )))),
            cnt.status cntstatus, 
            IF(cnt.status=0,'подключен',IF(cnt.status=1,'на отключении',
                IF(cnt.status=2,'отключен (расторжение)',
                    IF(cnt.status=3,'закрыт',
                    IF(cnt.status=4,'приостановлен',IF(cnt.status=5,'на подключении','неизвестный'))
                ))))
            FROM `contract_status_log` csl
            LEFT JOIN contract cnt ON csl.cid=cnt.id

            WHERE csl.date >= '$bDate' AND csl.date < '$eDate'
            ORDER BY csl.date";
        
        $command = $conn->createCommand($sql);
        $dbRes = $command->queryAll();
        $header = "N дог.;Описание;Время;Код статуса;Статус;Код тек. статуса;Тек. статус;\r\n";
        $csv = mb_convert_encoding($header, 'cp1251', 'utf-8');
        
        foreach ($dbRes as $row) {
            foreach ($row as $k => $col) {
                $csv .= mb_convert_encoding($col, 'cp1251', 'utf-8').';';
            }
            $csv .= "\r\n";
        }
        
        return $csv;
    }
    
    /**
     * @inheritdoc
     */
    public static function getOutflow()
    {
        $conn = self::getDb();
        
        $sql = "SELECT distinct cnt.title,cnt.comment,
            csl.date AS change_date, cnt.status,
            IF(cnt.status=0,'подключен',IF(cnt.status=1,'на отключении',
                IF(cnt.status=2,'отключен (расторжение)',
                    IF(cnt.status=3,'закрыт',
                    IF(cnt.status=4,'приостановлен',IF(cnt.status=5,'на подключении','неизвестный'))
                )))) contr_status

            FROM `contract` cnt 
            LEFT JOIN `contract_status_log` csl ON csl.cid=cnt.id
            INNER JOIN (SELECT cid, MAX(date) max_date 
                FROM `contract_status_log` 
                GROUP BY cid) groupped_csl 
            ON csl.cid = groupped_csl.cid AND groupped_csl.max_date = csl.date

            WHERE cnt.status IN (2,3)";

        $command = $conn->createCommand($sql);
        $dbRes = $command->queryAll();
        
        $header = "N дог.;Описание;Дата смены статуса;Код статуса;Статус;\r\n";
        $csv = mb_convert_encoding($header, 'cp1251', 'utf-8');
        
        foreach ($dbRes as $row) {
            foreach ($row as $k => $col) {
                $csv .= mb_convert_encoding($col, 'cp1251', 'utf-8').';';
            }
            $csv .= "\r\n";
        }
        
        return $csv;
    }
}
