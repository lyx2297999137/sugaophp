<?php
namespace App\Home\Model;
use sugaophp\Db\Orm\ActiveRecord;
/**
 * User模型
 */
class User extends ActiveRecord{
    protected $table_name='user';
     /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'age'], 'required'],
        ];
    }
}

