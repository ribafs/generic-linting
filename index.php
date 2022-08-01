<?php
  include_once("header.php");

include_once("classes/Crud.php");
$table = Connection::TABLE;
$crud = new Crud($pdo, $table);

if(!$crud->tableExists($table)){
    print '<div class="container"><h3>Antes configure o banco de dados e indique a tabela em classes/Connection.php</h3>';
    exit;
}

$stmt = $crud->pdo->prepare("SELECT COUNT(*) AS id FROM {$table}");
$stmt->execute();
$rows = $stmt->fetch(PDO::FETCH_ASSOC);

// get total no. of pages
$totalPages = ceil($rows['id']/Connection::REGS_PER_PAGE);
?>
<body>

<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading text-center">
            <h1>Listagem Genérica</h1>
            <h3>Com PDO, busca, paginação e Bootstrap</h3>
        </div>
        <div class="row">

            <!-- Form de busca-->
            <div class="col-md-12">
                <form action="search.php" method="get" >
                  <div class="pull-right top">
                  <span class="pull-right">  
                    <label class="control-label" for="palavra" style="padding-right: 5px;">
                      <input type="text" value="" placeholder="Nome ou parte" class="form-control" name="keyword">
                    </label>
                    <button class="btn btn-primary text-right"><span class="glyphicon glyphicon-search"></span> Busca</button>&nbsp;
                  </span>                 
                  </div>
                </form>
            </div>
	    </div>

        <table class="table table-bordered table-hover">
            <thead>  
                <tr>
                    <?=$crud->labels()?>
                </tr>
            </thead>
            <tbody id="pg-results">
            </tbody>
        </table>
        <div class="panel-footer text-center">
            <div class="pagination"></div>
        </div>
    </div>
</div>
    
<script src="assets/js/jquery-1.10.2.min.js" type="text/javascript"></script>
<script src="assets/js/bootstrap.min.js" type="text/javascript"></script>
<script src="assets/js/jquery.bootpag.min.js" type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function() {
    $("#pg-results").load("fetch_data.php");
    $(".pagination").bootpag({
        total: <?php echo $totalPages; ?>,
        page: 1,
        maxVisible: <?=Connection::LINKS_PER_PAGE?>,// páginas da paginação
        leaps: true,
        firstLastUse: true,
        first: 'Primeiro',//←
        last: 'Último',//→
        wrapClass: 'pagination',
        activeClass: 'active',
        disabledClass: 'disabled',
        nextClass: 'next',
        prevClass: 'prev',
        lastClass: 'last',
        firstClass: 'first'
    }).on("page", function(e, page_num){
        //e.preventDefault();
        $("#results").prepend('<div class="loading-indication"><img src="ajax-loader.gif" /> Loading...</div>');
        $("#pg-results").load("fetch_data.php", {"page": page_num});
    });
});
</script>

<?php include_once("footer.php"); ?>

