<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Yaitulah</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    
    <!-- Highcharts -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-3d.js"></script>
    <script src="https://code.highcharts.com/modules/drilldown.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/maps/modules/map.js"></script>
    <script src="https://code.highcharts.com/mapdata/custom/british-isles.js"></script>
    <script src="https://code.highcharts.com/mapdata/countries/id/id-all.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.3.6/proj4.js"></script>

    <!-- JSGrid -->
    <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css" />
    <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid-theme.min.css" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>


</head>
<body>
<div class="col-xl-12">

    <div class="row">
        <div id="chartCont"></div>
    </div>

    <div class="row">
        <div id="gridCont"></div>
    </div>

</div>
    <script type="text/javascript">

    $(document).ready(function(){
        $('#gridCont').hide()//pas page ke load, sembunyiin gridTable
    })

        Highcharts.chart('chartCont', {//init highchart di div chartCont
            chart: {
                type: 'pie',
                events: {
                    load: function(e){
                        //pas nge load, ambil data lewat ajax
                        var chart = this

                            $.ajax({
                            url: 'getData.php',       
                            type: "GET",
                            data: {param: null},
                            dataType: "json",
                            beforeSend: function(e){    
                                chart.showLoading()
                            },
                            success: function(ret){
                                chart.addSeries({                        
                                    name: ret.name,
                                    data: ret.data
                                });
                                chart.hideLoading()
                            }
                        })
                        
                    },
                    drilldown: function(e){
                        //pas nge drilldown, ambil data baru lewat ajax
                        var chart = this

                            $.ajax({
                                url: 'getData.php',       
                                type: "GET",
                                data: {param: e.point.drilldownSearcher+'-'+e.point.drilldown},
                                dataType: "json",
                                beforeSend: function(e){    
                                    chart.showLoading()
                                },
                                success: function(ret){
                                    
                                    if(ret.data[0].drilldown == undefined){
                                        console.log(e.point.drilldown)
                                        gridShow(e.point.drilldown)//buat gridTable muncul, tambahin parameter isi drilldown
                                    }

                                    chart.addSeriesAsDrilldown(e.point,{
                                        id: ret.id,                      
                                        name: ret.name,
                                        data: ret.data
                                    });
                                    chart.hideLoading()
                                }
                            })

                        
                    },
                    drillup: function(e){
                        
                        $('#gridCont').hide()//sembunyiin grid table
                        
                    }
                }
            },
            title: {
                text: 'Ya Begitulah'
            },
            xAxis: {
                type: 'category'
            },
            yAxis: {
                minPadding: 0.2,
                maxPadding: 0.2,
                title: {
                    text: 'Value',
                    margin: 80
                }
            },
        })


    function gridShow(param){
       
        $('#gridCont').show()//munculin gritable

        $('#gridCont').jsGrid({//init gridTable di div gridCont

            width: "100%",
            height: "600px",

            filtering: false,
            inserting:true,
            editing: true,
            sorting: true,
            paging: true,
            autoload: true,
            pageSize: 10,
            pageButtonCount: 5,
            deleteConfirm: "Do you really want to delete data?",

            controller: {
                loadData: function(filter){
                    return $.ajax({
                        type: "GET",
                        url: "fetchData.php",
                        data: {param: param}//parameter isinya dari drilldown chart (male / female)
                    })
                },
                insertItem: function(item){
                    return $.ajax({
                        type: "POST",
                        url: "fetchData.php",
                        data:item
                    })
                },
                updateItem: function(item){
                    return $.ajax({
                        type: "PUT",
                        url: "fetchData.php",
                        data: item
                    });
                },
                deleteItem: function(item){
                    return $.ajax({
                        type: "DELETE",
                        url: "fetchData.php",
                        data: item
                    });
                },
            },
            fields: [
                {
                    name: "id",
                    type: "hidden",
                    css: 'hide'
                },
                {
                    name: "first_name", 
                    type: "text", 
                    width: 150, 
                    validate: "required"
                },
                {
                    name: "last_name", 
                    type: "text", 
                    width: 150, 
                    validate: "required"
                },
                {
                    name: "age", 
                    type: "text", 
                    width: 50, 
                    validate: function(value)
                    {
                    if(value > 0){
                        return true;
                    }
                    }
                },
                {
                    name: "gender", 
                    type: "select", 
                    items: [
                    { Name: "", Id: '' },
                    { Name: "Male", Id: 'male' },
                    { Name: "Female", Id: 'female' }
                    ], 
                    valueField: "Id", 
                    textField: "Name", 
                    validate: "required"
                },
                {
                    type: "control"
                }
            ]

        })

    }

    </script>
</body>
</html>