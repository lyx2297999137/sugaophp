<?php

/**
 * 来源：http://www.cnblogs.com/freespider/archive/2011/04/18/2019506.html
 * MYSQL 操作类
 * @author yht_0616@126.com
 */

namespace sugaophp\Db\Orm\Mysqli;

class Mysql implements Dbinterface {

    private $debug = false;     //true 打开数据库调试模式 false关闭数据库调试模式
    private $version = "";
    private $link_id = NULL;

    /**
     * 连接数据库
     *
     * param  string  $dbhost       数据库主机名<br />
     * param  string  $dbuser       数据库用户名<br />
     * param  string  $dbpw         数据库密码<br />
     * param  string  $dbname       数据库名称<br />
     * param  string  $dbcharset    数据库字符集<br />
     * param  string  $pconnect     持久链接,1为开启,0为关闭
     * return bool
     * */
    public function __construct() {
        $config = \sugaophp\Config::init();
        $dbhost=$config['DB_CONFIG']['DB_HOST'];
        $dbuser=$config['DB_CONFIG']['DB_USERNAME'];
        $dbpwd=$config['DB_CONFIG']['DB_PASSWORD'];
        $dbname=$config['DB_CONFIG']['DB_NAME'];
        $dbcharset = 'utf8';
        $pconnect = 0;
            if ($pconnect) {
                if (!$this->link_id = mysql_pconnect($dbhost, $dbuser, $dbpwd)) {
                    $this->ErrorMsg();
                }
            } else {
                if (!$this->link_id =@mysql_connect($dbhost, $dbuser, $dbpwd, 1)) { //2017年6月8日18:43:38这个会报错。所以用@
                    $this->ErrorMsg();
                }
            }
            $this->version = mysql_get_server_info($this->link_id);
            if ($this->getVersion() > '4.1') {
                if ($dbcharset) {
                    mysql_query("SET character_set_connection=" . $dbcharset . ", character_set_results=" . $dbcharset . ", character_set_client=binary", $this->link_id);
                }

                if ($this->getVersion() > '5.0.1') {
                    mysql_query("SET sql_mode=''", $this->link_id);
                }
            }
            if (mysql_select_db($dbname, $this->link_id) === false) {
                $this->ErrorMsg();
            }
        }

    function connect($dbhost, $dbuser, $dbpwd, $dbname = '', $dbcharset = 'utf8', $pconnect = 0) {
        if ($pconnect) {
            if (!$this->link_id = mysql_pconnect($dbhost, $dbuser, $dbpwd)) {
                $this->ErrorMsg();
            }
        } else {
            if (!$this->link_id = mysql_connect($dbhost, $dbuser, $dbpwd, 1)) {
                $this->ErrorMsg();
            }
        }
        $this->version = mysql_get_server_info($this->link_id);
        if ($this->getVersion() > '4.1') {
            if ($dbcharset) {
                mysql_query("SET character_set_connection=" . $dbcharset . ", character_set_results=" . $dbcharset . ", character_set_client=binary", $this->link_id);
            }

            if ($this->getVersion() > '5.0.1') {
                mysql_query("SET sql_mode=''", $this->link_id);
            }
        }
        if (mysql_select_db($dbname, $this->link_id) === false) {
            $this->ErrorMsg();
        }
    }

    /**
     * 插入数据
     *
     * @param string $table         表名<br />
     * @param array $field_values   数据数组<br />
     * @return id                   最后插入ID
     */
    function save($table, $field_values) {
        $fields = array();
        $values = array();
        $field_names = $this->getCol('DESC ' . $table);

        foreach ($field_names as $value) {
            if (array_key_exists($value, $field_values) == true) {
                $fields [] = $value;
                $values [] = "'" . $field_values [$value] . "'";
            }
        }
        if (!empty($fields)) {
            $sql = 'INSERT INTO ' . $table . ' (' . implode(',', $fields) . ') VALUES (' . implode(',', $values) . ')';
            if ($this->query($sql)) {
                return $this->getLastInsertId();
            }
        }
        return false;
    }

    /**
     * 获取最后插入数据的ID
     */
    function getLastInsertId() {
        return mysql_insert_id($this->link_id);
    }

    /**
     * 更新数据
     *
     * @param string $table         要更新的表<br />
     * @param array $field_values   要更新的数据，使用而为数据例:array('列表1'=>'数值1','列表2'=>'数值2')
     * @param string $where         更新条件
     * @return bool
     */
    function update($table, $field_values, $where = '') {
        $field_names = $this->getCol('DESC ' . $table);
        $sets = array();
        foreach ($field_names as $value) {
            if (array_key_exists($value, $field_values) == true) {
                $sets [] = $value . " = '" . $field_values [$value] . "'";
            }
        }
        if (!empty($sets)) {
            $sql = 'UPDATE ' . $table . ' SET ' . implode(', ', $sets) . ' WHERE ' . $where;
        }
        if ($sql) {
            return $this->query($sql);
        } else {
            return false;
        }
    }

    /**
     * 删除数据
     *
     * @param string $table 要删除的表<br />
     * @param string $where 删除条件，默认删除整个表
     * @return bool
     */
    function delete($table, $where = '') {
        if (empty($where)) {
            $sql = 'DELETE FROM ' . $table;
        } else {
            $sql = 'DELETE FROM ' . $table . ' WHERE ' . $where;
        }
        if ($this->query($sql)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取数据列表
     *
     * @param string $sql   查询语句
     * @return array        二维数组
     */
    function findAll($sql) {
        $res = $this->query($sql);
        if ($res !== false) {
            $arr = array();
            $row = mysql_fetch_assoc($res);
            while ($row) {
                $arr [] = $row;
                $row = mysql_fetch_assoc($res);
            }
            return $arr;
        } else {
            return false;
        }
    }

    /**
     * 获取数据列表
     *
     * @param string $sql   查询语句<br />
     * @param int $numrows  返回个数<br />
     * @param int $offset   指定偏移量
     * @return array        二维数组
     */
    function selectLimit($sql, $numrows = -1, $offset = -1) {
        if ($offset == -1) {
            $sql .= ' LIMIT ' . $numrows;
        } else {
            $sql .= ' LIMIT ' . $offset . ', ' . $numrows;
        }
        return $this->findAll($sql);
    }

    /**
     * 获取一条记录
     *
     * @param string $sql   查询语句
     * @return array        一维数组
     */
    function findOne($sql) {
        $res = $this->query($sql);
        if ($res !== false) {
            return mysql_fetch_assoc($res);
        } else {
            return false;
        }
    }

    /**
     * 返回查询记录数
     *
     * @return int
     */
    function getRowsNum($sql) {
        $query = $this->query($sql);
        return mysql_num_rows($query);
    }

    /**
     * 返回查询的结果的第一个数据
     *
     * @return string
     */
    function getOneField($sql) {
        $val = mysql_fetch_array($this->query($sql));
        return $val[0];
    }

    /**
     * 获取列
     *
     * @param string $sql
     * @return array
     */
    function getCol($sql) {
        $res = $this->query($sql);
        if ($res !== false) {
            $arr = array();
            $row = mysql_fetch_row($res);
            while ($row) {
                $arr [] = $row [0];
                $row = mysql_fetch_row($res);
            }
            return $arr;
        } else {
            return false;
        }
    }

    /**
     * 发送一条 MySQL指令
     *
     * @param string $sql
     * @return bool
     */
    function query($sql) {
        //$sql=str_replace('xxxx_db_',DB_PREFIX, $sql);//xxxx_db   sql语句中将xxxx_db替换成db_prefix
        if ($this->debug)
            echo "<pre><hr>\n" . $sql . "\n<hr></pre>"; //如果设置成调试模式，将打印SQL语句
        if (!($query = mysql_query($sql, $this->link_id))) {
            $this->ErrorMsg();
            return false;
        } else {
            return $query;
        }
    }

    /**
     * 获取数据库版本信息
     */
    function getVersion() {
        return $this->version;
    }

    /**
     * 数据库调试
     */
    function debug() {
        $this->debug = true;
    }

    /**
     * 数据库报错处理
     */
    function ErrorMsg($message = '') {
        if (empty($message))
            $message = @mysql_error();
        exit($message);
    }

    /**
     * 关闭数据库连接（通常不需要，非持久连接会在脚本执行完毕后自动关闭）
     */
    function close() {
        return mysql_close($this->link_id);
    }

}
