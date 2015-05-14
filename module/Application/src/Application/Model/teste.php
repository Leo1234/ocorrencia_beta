
<!doctype html>
<html lang="en">
    <head>
        <script type="text/javascript" src="C:/Users/Leonildo_/Desktop/bower_components/js/jquery-1.10.2.js"></script>
        
        <script type="text/javascript" src="C:/Users/Leonildo_/Desktop/bower_components/moment/min/moment-with-locales.js"></script>
        <script type="text/javascript" src="C:/Users/Leonildo_/Desktop/bower_components/js/bootstrap.js"></script>
        <script type="text/javascript" src="C:/Users/Leonildo_/Desktop/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
        
        <link rel="stylesheet" href="C:/Users/Leonildo_/Desktop/bower_components/css/bootstrap.css" />
        <link rel="stylesheet" href="C:/Users/Leonildo_/Desktop/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" />
        <script type="text/javascript" src="C:/Users/Leonildo_/Desktop/bower_components/moment/locale/pt-br.js"></script>
        <link rel="stylesheet" href="C:/Users/Leonildo_/Desktop/bower_components/css/bootstrap.min.css" />
    </head>

    <body>
        <div class="container">
            <div class="row">
                <div class='col-sm-6'>
                    <div class="form-group">
                        <div class='input-group date' id='datetimepicker1'>
                            <input type='text' class="form-control" />
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div>
                <script type="text/javascript">
                    $(function() {
                        $('#datetimepicker1').datetimepicker({
                            locale: 'pt-br'
                        });
                    });
                </script>
            </div>
        </div>
    </body>
</html>