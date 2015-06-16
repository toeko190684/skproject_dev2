<!doctype html>
<html>
    <head>
        <title>Harviacode - Datatables</title>
        <link rel="stylesheet" href="css/bootstrap.min.css"/>
        <link rel="stylesheet" href="css/dataTables.bootstrap.css"/>
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Serverside Datatables - Harviacode
                            <div class="btn-group pull-right">
                                <a href="#">Add</a>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="provinsi">
                                    <thead>
                                        <tr>
                                            <th>Id Provinsi</th>
                                            <th>Nama Provinsi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
        </div>
        
        <script src="js/jquery-1.11.0.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="datatables/jquery.dataTables.js"></script>
        <script src="datatables/dataTables.bootstrap.js"></script>
        <script>
            $(document).ready(function() {
                var t = $('#provinsi').DataTable( {
                    "ajax": "ajax/provinsi.php",
                    "order": [[ 1, 'asc' ]],
                    "columns": [
                        { 
                            "data": "id_provinsi",
                            "width": "120px",
                            "sClass": "text-center"
                        },
                        { "data": "provinsi" },
                    ]
                } );
            } );
        </script>
    </body>
</html>
