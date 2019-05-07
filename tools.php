<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Tools Ceritanya</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</head>
<body>
    <select id='tableName'>
        <option value=''>Pilih Tabel</otion>
        <option value='sample_data'>sample_data</option>
    </select>

    <form action='submitTools.php' type="post">
        Nama: <select class='dropdown' name='name'></select><br/>
        Y: <select class='dropdown' name='name'></select><br/>
        Group By: <select class='dropdown' name='name'></select><br/>
    </form>

    <script type='text/javascript'>
        $('#tableName').change(function(){
            var tableName = $(this).val()

            $.ajax({
                url: 'dropdownData.php',
                data: {param: tableName},
                type: 'GET',
                success: function(ret){
                    ret = JSON.parse(ret)
                    
                    var data = '';
                    
                    $.each(ret, function(i,v){
                        data += '<option value="'+v+'">'+v.replace(/_/g,' ')+'</option>'
                    })

                    $('.dropdown').html(data);
                }
            })
        })
    </script>
</body>
</html>