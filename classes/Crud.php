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

