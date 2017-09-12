<?php
namespace sugaophp\Db\Orm;
use ErrorException;
use InvalidArgumentException;
use PDO;
/**
 * 
 * http://blog.csdn.net/fanhengguang_php/article/details/54964490
 */
class ActiveRecord {

    protected $table_name;  
    protected $id_name = 'id';
    protected $attributes = array();
    /** 
     * Username 
     * @var String 
     */  
    protected $username='root';  
    /** 
     * password 
     * @var  String 
     */  
    protected $password='root';  
    /** 
     * The DBMS hostname 
     * @var  String 
     */  
    protected $hostname='localhost';  
    /** 
     * The database name 
     * @var  String 
     */  
    protected $dbname='sugaophp';  
    /** 
     * The DBMS connection port 
     * @var  String 
     */  
    protected $port = "3306";  
    function __construct(Array $attributes = null) {
        $this->attributes = $attributes;
    }

    public function __set($key, $value) {
        $this->setAttribute($key, $value);
    }

    /**
     * Save the model 
     * @return bool 
     */
    public function save() {
        try {
            if (array_key_exists($this->id_name, $this->attributes)) {
                $attributes = $this->attributes;
                unset($attributes[$this->id_name]);
                $this->update($attributes);
            } else {
                $id = $this->insert($this->attributes);
                $this->setAttribute($this->id_name, $id);
            }
        } catch (ErrorException $e) {
            return false;
        }

        return true;
    }

    /**
     * Used to prepare the PDO statement 
     * 
     * @param $connection 
     * @param $values 
     * @param $type 
     * @return mixed 
     * @throws InvalidArgumentException 
     */
    protected function prepareStatement($connection, $values, $type) {
        if ($type == "insert") {
            $sql = "INSERT INTO {$this->table_name} (";
            foreach ($values as $key => $value) {
                $sql .= "{$key}";
                if ($value != end($values))
                    $sql .= ",";
            }
            $sql .= ") VALUES(";
            foreach ($values as $key => $value) {
                $sql .= ":{$key}";
                if ($value != end($values))
                    $sql .= ",";
            }
            $sql .= ")";
        }
        elseif ($type == "update") {
            $sql = "UPDATE {$this->table_name} SET ";
            foreach ($values as $key => $value) {
                $sql .= "{$key} =:{$key}";
                if ($value != end($values))
                    $sql .= ",";
            }
            $sql .= " WHERE {$this->id_name}=:{$this->id_name}";
        }
        else {
            throw new InvalidArgumentException("PrepareStatement need to be insert,update or delete");
        }

        return $connection->prepare($sql);
    }

    /**
     * Get the connection to the database 
     * 
     * @throws  PDOException 
     */
    protected function getConnection() {
//        $conn =new Db();
//        return $conn;
        try {
            $conn = new PDO("mysql:host={$this->hostname};dbname={$this->dbname};port=$this->port", $this->username, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $conn;
        } catch (PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
        }
    }

    /**
     * Used to insert a new record 
     * @param array $values 
     * @throws ErrorException 
     */
    public function insert(array $values) {
        $connection = $this->getConnection();
        $statement = $this->prepareStatement($connection, $values, "insert");
        foreach ($values as $key => $value) {
            $statement->bindValue(":{$key}", $value);
        }

        $success = $statement->execute($values);
        if (!$success)
            throw new ErrorException;

        return $connection->lastInsertId();
    }

    /**
     * Update the current row with new values 
     * 
     * @param array $values 
     * @return bool 
     * @throws ErrorException 
     * @throws BadMethodCallException 
     */
    public function update(array $values) {
        if (!isset($this->attributes[$this->id_name]))
            throw new BadMethodCallException("Cannot call update on an object non already fetched");

        $connection = $this->getConnection();
        $statement = $this->prepareStatement($connection, $values, "update");
        foreach ($values as $key => $value) {
            $statement->bindValue(":{$key}", $value);
        }
        $statement->bindValue(":{$this->id_name}", $this->attributes[$this->id_name]);
        $success = $statement->execute();

        // update the current values  
        foreach ($values as $key => $value) {
            $this->setAttribute($key, $value);
        }

        if (!$success)
            throw new ErrorException;

        return true;
    }
     /**
     * Sets the named attribute value.
     * @param string $name the attribute name
     * @param mixed $value the attribute value.
     * @throws InvalidParamException if the named attribute does not exist.
     * @see hasAttribute()
     */
    public function setAttribute($name, $value)
    {
//        if ($this->hasAttribute($name)) {
            $this->attributes[$name] = $value;
//        } else {
//            echo get_class($this) . ' has no attribute named "' . $name . '".';die;
//            throw new InvalidParamException(get_class($this) . ' has no attribute named "' . $name . '".');
//        }
    }
      /**
     * Returns a value indicating whether the model has an attribute with the specified name.
     * @param string $name the name of the attribute
     * @return bool whether the model has an attribute with the specified name.
     */
    public function hasAttribute($name)
    {
        return isset($this->attributes[$name]) || in_array($name, $this->attributes(), true);
    }
      /**
     * Returns the list of attribute names.
     * By default, this method returns all public non-static properties of the class.
     * You may override this method to change the default behavior.
     * @return array list of attribute names.
     */
    public function attributes()
    {
        $class = new \ReflectionClass($this);
        $names = [];
        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
            if (!$property->isStatic()) {
                $names[] = $property->getName();
            }
        }

        return $names;
    }

}
