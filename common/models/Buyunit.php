<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "agent_buyunit_user".
 *
 * @property string $id                     
 * @property string $user_id                        创建人id
 * @property string $name                           采购单位名称
 * @property string $address                        地址
 * @property string $tel                            电话
 * @property string $unit_code                      唯一识别码
 * @property string $contact_name                   联系人
 * @property string $contact_mobile                 联系人手机
 * @property string $create_date                    创建日期
 * @property string $delete_flag
 * @property string $update_date
 * @property string $audit_flag
 * @property string $description
 *
 * @property AgentBuyunitUserIp[] $agentBuyunitUserIps
 */
class Buyunit extends ActiveRecord
{
    /* 采购商表名 */
    const BUYUNITY_TABLE_NAME = 'agent_buyunit_user';
    /* 采购商IP段 表名 */
    const BUYUNITY_IP_TABLE_NAME = 'agent_buyunit_user_ip';
    /* 采购单 表名 */
    const BUYUNITY_INFO_TABLE_NAME = 'agent_buyunit_info';
    /* 采购产品 表名 */
    const BUYUNITY_SUBJECT_REL_TABLE_NAME = 'agent_buyunit_subject_rel';
    /* logo所在服务器 */
    const LOGO_WEB_ROOT = 'http://wph.tt.eenet.com';
    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agent_buyunit_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['create_date', 'update_date'], 'safe'],
            [['id', 'user_id'], 'string', 'max' => 32],
            [['name'], 'string', 'max' => 50],
            [['address'], 'string', 'max' => 256],
            [['tel'], 'string', 'max' => 30],
            [['unit_code', 'contact_name'], 'string', 'max' => 64],
            [['contact_mobile'], 'string', 'max' => 20],
            [['delete_flag'], 'string', 'max' => 1],
            [['audit_flag'], 'string', 'max' => 2],
            [['description'], 'string', 'max' => 2000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'name' => Yii::t('app', 'Name'),
            'address' => Yii::t('app', 'Address'),
            'tel' => Yii::t('app', 'Tel'),
            'unit_code' => Yii::t('app', 'Unit Code'),
            'contact_name' => Yii::t('app', 'Contact Name'),
            'contact_mobile' => Yii::t('app', 'Contact Mobile'),
            'create_date' => Yii::t('app', 'Create Date'),
            'delete_flag' => Yii::t('app', 'Delete Flag'),
            'update_date' => Yii::t('app', 'Update Date'),
            'audit_flag' => Yii::t('app', 'Audit Flag'),
            'description' => Yii::t('app', 'Description'),
        ];
    }
    
    /**
     * 通过 IP 查寻采购商
     * @param type $ip
     * @return array 采购商信息 [<br/>
     *  'buyunity_id',                      //{string}采购商ID<br/>
     *  'buyunity_name',                    //{string}采购商名称<br/>
     *  'buyunity_logo',                    //{string}采购商Logo<br/>
     *  'start_ip',                         //{string}起始IP<br/>
     *  'end_ip',                           //{string}结束IP<br/>
     *  'subjects' => [subject_id,...],     //{array}购买的学科<br/>
     * ]<br/>
     */
    public static function searchByIp($ip){
        return [
                    'is_experience' => true,
                    'experience_id' => 1,
                    'experience_code' => 1,
                    'experience_unit' => 1,
                ];
        //查出所有采购商
        $buyunity_result = (new Query())
                ->select(['Buyunity.id as buyunity_id','Buyunity.name as buyunity_name','Buyunity.logo_path as buyunity_logo','BuyunityIP.start_ip' ,'BuyunityIP.end_ip'])
                ->from(['Buyunity' => self::BUYUNITY_TABLE_NAME])
                ->leftJoin(['BuyunityIP' => self::BUYUNITY_IP_TABLE_NAME], 'Buyunity.id = BuyunityIP.unit_user_id')
                ->where(['Buyunity.delete_flag' => '0','BuyunityIP.delete_flag' => 0])
                ->all();
        foreach($buyunity_result as $iprow){
            if(self::ipInNetwork($ip,$iprow['start_ip'],$iprow['end_ip'])){
                $iprow['buyunity_logo'] = self::LOGO_WEB_ROOT.$iprow['buyunity_logo'];
                $iprow['is_experience'] = false;
                return array_merge($iprow,['subjects' => self::getBuyunitySubject($iprow['buyunity_id'])]);
            }
        }
        
        //检查是否是体验
        $experience_code = \Yii::$app->getRequest()->getQueryParam('experience_code', null);
        if($experience_code){
            $experience = ExperienceCard::findOne(['experience_code' => $experience_code]);
            if($experience && $experience->create_date){
                return [
                    'is_experience' => true,
                    'experience_id' => $experience->id,
                    'experience_code' => $experience_code,
                    'experience_unit' => $experience->unit_name,
                ];
            }
        }
        
        return null;
    }
    /**
     * 获取当前 IP 对应的采购商
     * @return array 采购商信息 [<br/>
     *  'buyunity_id',                      //{string}采购商ID<br/>
     *  'buyunity_name',                    //{string}采购商名称<br/>
     *  'buyunity_logo',                    //{string}采购商Logo<br/>
     *  'start_ip',                         //{string}起始IP<br/>
     *  'end_ip',                           //{string}结束IP<br/>
     *  'subjects' => [subject_id,...],     //{array}购买的学科<br/>
     * ]<br/>
     */
    public static function getCurrentBuyunit(){
        $session = Yii::$app->session;
        $ip = Yii::$app->request->userIP;
        $session_key = md5(sprintf("%s&%s", \Yii::$app->name, $ip));
        $buyunit = $session->get($session_key);
        if($buyunit == null){
            $buyunit = self::searchByIp($ip);
        }
        return $buyunit;
    }
    
    /**
     * 获取采购商购买产品
     * @param type $id
     */
    public static function getBuyunitySubject($id){
        $buyunity_info_result = (new Query())
                ->select(['BuyunityInfo.id','BuyunityInfo.buyunit_user_id as buyunit_id','BuyunitySubject.subject_id'])
                ->from(['BuyunityInfo' => self::BUYUNITY_INFO_TABLE_NAME])
                ->leftJoin(['BuyunitySubject' => self::BUYUNITY_SUBJECT_REL_TABLE_NAME], 'BuyunityInfo.id = BuyunitySubject.buyunit_info_id')
                ->where([
                    'BuyunityInfo.buyunit_user_id' => $id,                  
                    'BuyunityInfo.delete_flag' => '0',                      //未删除
                    'BuyunityInfo.audit_flag' => '1',])                     //审核通过
                ->andWhere(['>=','BuyunityInfo.expire_date', date('Y-m-d H:i:s',time())])      //未过期
                ->all();
        return ArrayHelper::getColumn($buyunity_info_result, 'subject_id');
    }
    
    /**
    * 判断IP是否在某个网络内 
    * @param $ip
    * @param $start
    * @param $end
    * @return bool
   */

   public static function ipInNetwork($ip, $start, $end)
   {
       $ip = (double) (sprintf("%u", ip2long($ip)));
       $network_start = (double) (sprintf("%u", ip2long($start)));
       $network_end = (double) (sprintf("%u", ip2long($end)));

       if ($ip >= $network_start && $ip <= $network_end)
       {
           return true;
       }
       return false;
   }
   
   /**
    * 检查当前采购商是对目标学科有授权
    * @param int $cat_id 学科ID
    * @return bool  true/false 
    */
   public static function checkAuthorize($cat_id){
        $session = Yii::$app->session;
        $ip = Yii::$app->request->userIP;
        $session_key = md5(sprintf("%s&%s", \Yii::$app->name, $ip));
        $buyunit = $session->get($session_key);
        if($buyunit == null){
            return false;
        }else{
            return array_search($cat_id, $buyunit['subjects']);
        }
   }

    /**
     * @return ActiveQuery
     */
    public function getAgentBuyunitUserIps()
    {
        return $this->hasMany(AgentBuyunitUserIp::className(), ['unit_user_id' => 'id']);
    }
}
