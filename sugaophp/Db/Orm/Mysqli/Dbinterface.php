<?php
/**
 * 接口。类必须实现这个
 */
namespace sugaophp\Db\Orm\Mysqli;

interface Dbinterface {
    public function findOne($sql);
}
