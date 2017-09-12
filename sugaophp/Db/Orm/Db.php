<?php

namespace sugaophp\Db\Orm;
use sugaophp\Superglobal;
class Db {

    public $db;
    public $tableName;
    public $sql;
    public $select;
    public $where;

    public function table($tableName = '') {
        $this->tableName = $tableName;
        return $this;
    }

//选择数据库  'DB_TYPE' => 'mysqli',
//    public function getdb() {
    public function __construct() {
        if (empty($this->db)) {
//            $config = \sugaophp\Config::init();
//            $dbtype = $config['DB_CONFIG']['DB_TYPE'];
             $config = Superglobal::$config;
            $dbtype = $config['DB_CONFIG']['DB_TYPE'];
            $dbclass = "\\sugaophp\\Db\\Orm\\Mysqli\\" . $dbtype;
//            dump($dbtype);dump("啦啦啦啦");
            $this->db = new $dbclass();
        }
        return $this->db;
    }

    /**
     * 
     * @param type $data ['id'=>1,'name'=>'2']
     */
    public function where($data = []) {
        $where = '';
        $sign = 0;
        foreach ($data as $k => $v) {
            if ($sign == 0) {
                $where .= $k . ' = "' . $v . '"';
            } else {
                $where .= ' and ' . $k . ' = "' . $v . '"';
            }
            $sign++;
        }
        $this->where = $where;
        return $this;
    }

    /**
     * 
     * @param type $data    ['id','name','age'] 或者不传
     * @return $this
     */
    public function select($data = []) {
        $count = count($data);
        switch ($count) {
            case 0:
                $field = "*";
                break;
            default:
                $field = '';
                foreach ($data as $v) {
                    $field .= $v . ',';
                }
                $field = substr($field, 0, strlen($field) - 1);
                break;
        }
        $this->select = $field;
//        $this->sql = "select " . $field . " from " . $this->tableName;
        return $this;
    }

    public function findOne() {
//        $mysqli = new \sugaophp\Db\Orm\Mysqli\MySqli();
        $this->buildSql();
        $rows = $this->db->findOne($this->sql);
        return $rows;
    }

    public function findAll() {
//        $mysqli = new \sugaophp\Db\Orm\Mysqli\MySqli();
        $this->buildSql();
        $rows = $this->db->findAll($this->sql);
        return $rows;
    }

    public function insert($query) {
//        $mysqli = new \sugaophp\Db\Orm\Mysqli\MySqli();
        $query = "
            insert into `user`(`name`,`age`) values('a',20),('b',18),('c',19);
                ";
        $this->db->query($query);
    }

    public function buildSql() {
        $this->sql = 'select ';
        if (!empty($this->select)) {
            $this->sql .= $this->select;
        }
        $this->sql .= " from " . $this->tableName;
        if (!empty($this->where)) {
            $this->sql .= ' where ' . $this->where;
        }
        dump($this->sql);
//        if(!empty($this->orderby)){  limit
//            $this->sql.= $this->orderby;
//        }
    }

}
