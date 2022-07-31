<?php
include_once("classes/Crud.php");
$table = Connection::TABLE;
$crud = new Crud($pdo, $table);

if (isset($_POST["page"])) {
    $page_no = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH);
    if(!is_numeric($page_no))
        die("Error fetching data! Invalid page number!!!");
} else {
    $page_no = 1;
}

// get record starting position
$start = (($page_no-1) * Connection::REGS_PER_PAGE);

$sgbd = Connection::$sgbd;
if($sgbd == 'mysql'){
    $results = $crud->pdo->prepare("SELECT * FROM {$table} ORDER BY id LIMIT $start, ".Connection::REGS_PER_PAGE);

}else if($sgbd == 'pgsql'){
    $results = $crud->pdo->prepare("SELECT * FROM {$table} ORDER BY id LIMIT " . Connection::REGS_PER_PAGE. " OFFSET $start");
}

$results->execute();

while($row = $results->fetch(PDO::FETCH_ASSOC)) {
    echo "<tr>";
    print $crud->rows($row); // Chama o método rows
print "
    </tr>";
}


