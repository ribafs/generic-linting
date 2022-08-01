<?php
require_once 'Connection.php';
$pdo = Connection::getInstance();
$table = Connection::TABLE;

class Crud
{
    // table name definition and database connection
    public $pdo;
    public $table;
    private $myQuery = "";
    
    // Passando $table aqui, acarreta uma grande flexibilidade, podendo usar esta classe para n tabelas
    public function __construct($pdo, $table)
    {
        $this->table = $table;
        $this->pdo = $pdo;
    }
    
    // Número de campos da tabela atual
    public function numFields(){
        $sql = "SELECT * FROM $this->table";
        $sth = $this->pdo->query($sql);

        return $sth->columnCount();
    }
    
    // Nome de campo pelo número $x
    public function fieldName($x){
        $sql = "SELECT * FROM $this->table";
        $sth = $this->pdo->query($sql);
        $meta = $sth->getColumnMeta($x);
        return $meta['name'];
    }

    /**
     * Check if a table exists in the current database.
     *
     * @param string $table Table to search for.
     * @return bool TRUE if table exists, FALSE if no table found.
     */
    function tableExists($table) {

        // Try a select statement against the table
        // Run it in try-catch in case PDO is in ERRMODE_EXCEPTION.
        try {
            $result = $this->pdo->query("SELECT 1 FROM {$table} LIMIT 1");
        } catch (Exception $e) {
            // We got an exception (table not found)
            return FALSE;
        }

        // Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
        return $result !== FALSE;
    }
    // https://stackoverflow.com/questions/1717495/check-if-a-database-table-exists-using-php-pdo
    
    public function numRows($sql=null){ // Exemplo: $sql = "SELECT * FROM $this->table";
        if(is_null($sql)) {
            $sql = 'SELECT * FROM '.$this->table;
        }
        
        $sth = $this->pdo->query($sql);
        return $sth->rowCount();
    }

    // Para o index.php
    public function labels(){
        $ret = '';
            for($x=0;$x < $this->numFields();$x++){
                $fn = $this->fieldName($x);
                $ret .= "     <td><strong>".ucfirst($fn)."</strong></td>"; 
      	    }
        return $ret;        
    }

    // Para o fetch_data.php
    public function rows($row){
        $ret = '';
            for($x=0;$x < $this->numFields();$x++){
                $fn = $this->fieldName($x);
                $ret .= "     <td> $row[$fn] </td>"; 
      	    }
        return $ret;        
    }

    // Para o index.php
    public function formAdd(){
        $ret = '';
            for($x=1;$x < $this->numFields();$x++){
                $fn = $this->fieldName($x);
                $ret .= "<tr><td>".ucfirst($fn)."</td><td><input type=\"text\" name=\"$fn\"></td></tr>\n<br>"; 
      	    }
        return $ret;        
    }

}

