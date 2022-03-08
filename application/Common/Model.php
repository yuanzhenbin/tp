<?php
require __ROOT__.'config/config.php';

class Model
{
    protected $dbType = DB_TYPE;
    protected $host = DB_HOST;
    protected $dbName = DB_NAME;
    protected $user = DB_USER;
    protected $pass = DB_PWD;
    protected $port = DB_PORT;
    protected $charset = DB_CHAR;
    protected $tablePrefix = DB_PREFIX;

    protected $dsn = null;
    protected $tableName = null;

    protected $field = '*';
    protected $where = null;
    protected $group = null;
    protected $order = null;
    protected $limit = null;

    protected $change = [
        'gt' => '>',
        'egt' => '>=',
        'lt' => '<',
        'elt' => '<=',
        'eq' => '=',
        'neq' => '<>',
    ];

    public function __construct($tableName = '', $connect = [])
    {
        if (!empty($tableName)) {
            if (!empty($this->tablePrefix)) {
                $this->tableName = $this->tablePrefix.$tableName;
            } else {
                $this->tableName = $tableName;
            }
        }

        if (!empty($connect['dbms'])) {
            $this->dbType = $connect['dbms'];
        }
        if (!empty($connect['host'])) {
            $this->host = $connect['host'];
        }
        if (!empty($connect['dbname'])) {
            $this->dbName = $connect['dbname'];
        }
        if (!empty($connect['user'])) {
            $this->user = $connect['user'];
        }
        if (!empty($connect['pass'])) {
            $this->pass = $connect['pass'];
        }
        if (!empty($connect['port'])) {
            $this->port = $connect['port'];
        }

        $this->dsn = $this->dbType.":host=".$this->host.";dbname=".$this->dbName.";port=".$this->port.";charset=".$this->charset;
    }

    public function db()
    {
        $connect = new PDO($this->dsn, $this->user, $this->pass);
        return $connect;
    }

    public function select()
    {
        $sql = 'select '.$this->field.' from '.$this->tableName;

        $where = $this->where;
        if (!empty($where)) {
            $sql = $sql.$where;
        }

        $group = $this->group;
        if (!empty($group)) {
            $sql = $sql.$group;
        }

        $order = $this->order;
        if (!empty($order)) {
            $sql = $sql.$order;
        }

        $limit = $this->limit;
        if (!empty($limit)) {
            $sql = $sql.$limit;
        }

        $connect = $this->db();
        $result = $connect->query($sql);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count()
    {
        $sql = "select COUNT(*) as total from ".$this->tableName;

        $where = $this->where;
        if (!empty($where)) {
            $sql = $sql.$where;
        }

        $group = $this->group;
        if (!empty($group)) {
            $sql = $sql.$group;
        }

        $order = $this->order;
        if (!empty($order)) {
            $sql = $sql.$order;
        }

        $limit = $this->limit;
        if (!empty($limit)) {
            $sql = $sql.$limit;
        }

        $connect = $this->db();
        $result = $connect->query($sql);
        $count =  $result->fetchAll(PDO::FETCH_ASSOC);
        if (isset($count[0]['total'])) {
            return $count[0]['total'];
        } else {
            return 0;
        }

    }

    public function where($data = null)
    {
        if (!empty($data)) {
            if (is_array($data)) {
                foreach ($data as $key => $value) {
                    if (empty($this->where)) {
                        $this->where = " where ";
                    } else {
                        $this->where = $this->where." and ";
                    }
                    if (is_array($value)) {
                        if (count($value) == 2) {
                            if (isset($this->change[$value[0]])) {
                                $the_value = $this->change[$value[0]];
                            } else {
                                $the_value = $this->sql_check($value[0]);
                            }
                            if ($the_value == 'in') {
                                $this->where = $this->where." `".$this->sql_check($key)."` ".$the_value." (".$this->sql_check($value[1]).") ";
                            } else {
                                $this->where = $this->where." `".$this->sql_check($key)."` ".$the_value." ".$this->sql_check($value[1])." ";
                            }
                        } else {
                            $this->where = '';//todo
                        }
                    } else {
                        $this->where = $this->where." `".$this->sql_check($key)."` = '".$this->sql_check($value)."' ";
                    }
                }
            } else {
                $this->where = " where ".$data;
            }
        }

        return $this;
    }

    public function group($data = null)
    {
        $this->group = '';

        if (!empty($data)) {
            $this->group = ' group by '.$this->sql_check($data);
        }

        return $this;
    }

    public function order($data = null)
    {
        $this->order = '';

        if (!empty($data)) {
            $this->order = ' order by '.$this->sql_check($data);
        }

        return $this;
    }

    public function limit($start = null, $end = null)
    {
        $this->limit = '';

        if (is_numeric($start) && $start >= 0) {
            $this->limit = ' limit '.$start;
            if (is_numeric($end) && $end >= 0) {
                $this->limit = $this->limit.' , '.$end;
            }
        }

        return $this;
    }

    public function field($data = null)
    {
        $this->field = '*';

        if (!empty($data)) {
            $this->field = $data;
        }

        return $this;
    }

    function sql_check($value)
    {
        if(!get_magic_quotes_gpc()) {
            // 进行过滤
            $value = addslashes($value);
        }

        $value = str_replace("_", "\_", $value);
        $value = str_replace("%", "\%", $value);

        return $value;
    }

    public function add($data = [])
    {
        if (empty($data)) {
            return false;
        }

        $sql = 'insert into '.$this->tableName;

        $add_key = array_keys($data);
        $add_value = array_values($data);
        if ($add_key && $add_value) {
            $add_key = "`".implode("`,`",$add_key)."`";
            $add_value = "'".implode("','",$add_value)."'";
        } else {
            return false;
        }

        $sql = $sql.' ('.$this->sql_check($add_key).') values ('.$this->sql_check($add_value).')';

        $connect = $this->db();
        $result = $connect->query($sql);
        return $result;
    }

    public function save($data = [])
    {
        if (empty($data)) {
            return false;
        }

        $sql = 'update '.$this->tableName.' set ';

        $save_key = array_keys($data);
        $save_value = array_values($data);
        if ($save_key && $save_value) {
            $key_number = count($save_key);
            foreach ($save_key as $key => $value) {
                if ($key+1 == $key_number) {
                    $sql = $sql.' `'.$this->sql_check($value).'` = "'.$this->sql_check($save_value[$key]).'" ';
                } else {
                    $sql = $sql.' `'.$this->sql_check($value).'` = "'.$this->sql_check($save_value[$key]).'", ';
                }
            }
        } else {
            return false;
        }

        $where = $this->where;
        if (!empty($where)) {
            $sql = $sql.$where;
        }

        $connect = $this->db();
        $result = $connect->query($sql);
        return $result;
    }

    public function delete()
    {
        $sql = 'delete from '.$this->tableName;

        $where = $this->where;
        if (!empty($where)) {
            $sql = $sql.$where;
        } else {
            return false;
        }
        
        $connect = $this->db();
        $result = $connect->query($sql);
        return $result;
    }
}