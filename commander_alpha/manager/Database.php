<?php
require_once dirname(__FILE__).'/AppConfig.php';
/**
 * Description of Database
 * This class allow to insert, delete and update info
 * into db
 *
 * @author francesco
 */
class Database {

    private $con = false;               // Checks to see if the connection is active
    private $result = array();          // Results that are returned from the query

    /*
     * Connects to the database, only one connection
     * allowed
     */
    public function connect()
    {
        if(!$this->con)
        {
            $myconn = @mysql_connect(AppConfig::instance()->DB_HOST,AppConfig::instance()->DB_USER,AppConfig::instance()->DB_PASS);
            if($myconn)
            {
                $seldb = @mysql_select_db(AppConfig::instance()->DB_NAME,$myconn);
                if($seldb)
                {
                    $this->con = true;
                    return true;
                }
                else
                {
                    return false;
                }
            }
            else
            {
                return false;
            }
        }
        else
        {
            return true;
        }
    }

    /*
    * Changes the new database, sets all current results
    * to null
    */
    public function setDatabase($name)
    {
        if($this->con)
        {
            if(@mysql_close())
            {
                $this->con = false;
                $this->results = null;
                AppConfig::instance()->DB_NAME = $name;
                $this->connect();
            }
        }

    }

    /*
    * Checks to see if the table exists when performing
    * queries
    */
    private function tableExists($table)
    {
        $tablesInDb = @mysql_query('SHOW TABLES FROM '.AppConfig::instance()->DB_NAME.' LIKE "'.$table.'"');
        if($tablesInDb)
        {
            if(mysql_num_rows($tablesInDb)==1)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }

    /*
    * Selects information from the database.
    * Required: table (the name of the table)
    * Optional: rows (the columns requested, separated by commas)
    *           where (column = value as a string)
    *           order (column DIRECTION as a string)
    */
    public function select($table, $rows = '*', $where = null, $order = null)
    {
        $q = 'SELECT '.$rows.' FROM '.$table;
        if($where != null)
            $q .= ' WHERE '.$where;
        if($order != null)
            $q .= ' ORDER BY '.$order;

        $query = @mysql_query($q);

        //print_r($q);

        if($query)
        {
            $this->numResults = mysql_num_rows($query);
            for($i = 0; $i < $this->numResults; $i++)
            {
                $r = mysql_fetch_array($query);
                $key = array_keys($r);
                for($x = 0; $x < count($key); $x++)
                {
                    // Sanitizes keys so only alphavalues are allowed
                    if(!is_int($key[$x]))
                    {
                        if(mysql_num_rows($query) > 1)
                            $this->result[$i][$key[$x]] = $r[$key[$x]];
                        else if(mysql_num_rows($query) < 1)
                            $this->result = null;
                        else
                            $this->result[$key[$x]] = $r[$key[$x]];
                    }
                }
            }
            return true;
        }
        else
        {
            return false;
        }
    }

    /*
    * Insert values into the table
    * Required: table (the name of the table)
    *           values (the values to be inserted)
    * Optional: rows (if values don't match the number of rows)
    */
    public function insert($table,$values,$rows = null)
    {
        if($this->tableExists($table))
        {
            $insert = 'INSERT INTO '.$table;
            if($rows != null) {
                $insert .= ' ('.$rows.')';
            }

            for($i = 0; $i < count($values); $i++) {
                if(is_string($values[$i]))
                    $values[$i] = '"'.$values[$i].'"';
            }

            $values = implode(',',$values);
            $insert .= ' VALUES ('.$values.')';

            $ins = @mysql_query($insert);
            if($ins) {
                return true;
            } else {
                return false;
            }
        }
    }

    /*
    * Deletes table or records where condition is true
    * Required: table (the name of the table)
    * Optional: where (condition [column =  value])
    */
    public function delete($table,$where = null)
    {
        if($this->tableExists($table))
        {
            if($where == null)
            {
                $delete = 'DELETE '.$table;
            }
            else
            {
                $delete = 'DELETE FROM '.$table.' WHERE '.$where;
            }
            $del = @mysql_query($delete);

            if($del)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    /*
     * Updates the database with the values sent
     * Required: table (the name of the table to be updated
     *           rows (the rows/values in a key/value array
     *           where (the row/condition in an array (row,condition) )
     */
    public function update($table,$rows,$where)
    {
        if($this->tableExists($table))
        {
            // Parse the where values
            // even values (including 0) contain the where rows
            // odd values contain the clauses for the row

            for($i = 0; $i < count($where); $i++) {
                if($i%2 != 0) {
                    if(is_string($where[$i])) {
                        if(($i+1) != null)
                            $where[$i] = '"'.$where[$i].'" AND ';
                        else
                            $where[$i] = '"'.$where[$i].'"';
                    }
                }
            }

            $chiavi = array();
            $valori = array();
            $toale = array();

            //new code
            $i = 0;
            foreach ($where as $key => $value) {
                if($i%2 == 0) {
                    $chiavi[] = $value;
                    } else {
                       $valori[] = $value;
                    }
                $i++;
                }

            $where2 = array_combine($chiavi, $valori);
            $new_array = array_map(create_function('$key, $value', 'return $key."=".$value." ";'), array_keys($where2), array_values($where2));
            
            for($i = 0; $i < (count($new_array)-1); $i++) {
                if(($i+1) != null) $new_array[$i] .= " AND ";
            }
            
            $where = implode($new_array);

            $update = 'UPDATE '.$table.' SET ';
            $keys = array_keys($rows);
            for($i = 0; $i < count($rows); $i++) {
                if(is_string($rows[$keys[$i]])) {
                    $update .= $keys[$i].'="'.$rows[$keys[$i]].'"';
                } else {
                    $update .= $keys[$i].'='.$rows[$keys[$i]];
                    }

                // Parse to add commas
                if($i != count($rows)-1) {
                    $update .= ',';
                }
            }

            $update .= ' WHERE '.$where;
            $query = @mysql_query($update);
            if($query) {
                return true;
            } else {
                return false;
                }

        } else {
            return false;
        }
    }

   /*
    * Returns the result set
    */
    public function getResult()
    {
        return $this->result;
    }


}
?>
