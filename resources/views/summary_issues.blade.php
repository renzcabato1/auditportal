@extends('layouts.header')
@section('content')

<div class="content">
   
    <div class="row">
        <div class="col-md-12">
            <div id="bar_chart"></div>
            <br>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div id="piechart"></div>
            <br>
        </div>
    </div>
    @if(session()->has('status'))
    <div class="row">
        <div class="col-12">
            <div class="alert alert-success alert-with-icon alert-dismissible fade show" data-notify="container">
                <button type="button" aria-hidden="true" class="close" data-dismiss="alert" aria-label="Close">
                    <i class="nc-icon nc-simple-remove"></i>
                </button>
                <span data-notify="icon" class="nc-icon nc-bell-55"></span>
                <span data-notify="message">{{session()->get('status')}}</span>
            </div>
        </div>
    </div>
    @endif
    {{-- <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Business Units </h4>
                </div>
                
                <div class="card-body">
                    <div class="table-responsive">
                        <table id='issues_view'  class="table issues_view">
                            <thead class=" text-primary">
                                
                                <th>
                                    Cluster
                                </th>
                                <th>
                                    BU Code
                                </th>
                                <th>
                                    Business Unit
                                </th>
                                <th >
                                    Number of Issues
                                </th>
                            </thead>
                            <tbody>
                                @foreach($codes as $code)
                                <tr>
                                    <td>
                                        {{$code->cluster_info->cluster_name}}
                                    </td>
                                    <td>
                                        {{$code->bu_code}}
                                    </td>
                                    <td>
                                        {{$code->bu_name}}
                                    </td>
                                    <td>
                                        <a href="#viewIssues{{$code->id}}" data-toggle="modal" title='View all Issues'   >{{count($code->issues_info)}}</a>
                                    </td>
                                </tr>
                                @include('viewIssues')
                                @endforeach
                            </tbody>
                        </table>
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Clusters  </h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id='issues_view'   class="table issues_view">
                            <thead class=" text-primary">
                                <th>
                                    Cluster
                                </th>
                                <th >
                                    Number of Issues
                                </th>
                            </thead>
                            <tbody>
                                @foreach($clusters as $cluster)
                                <tr>
                                    <td>
                                        {{$cluster->cluster_name}} 
                                    </td>
                                    <td>
                                        @php
                                        $total_issue = 0;
                                        @endphp
                                        @foreach($cluster->codes_info as $clus)
                                        @php
                                        $total_issue = $total_issue + count($clus->issues_info)
                                        @endphp
                                        @endforeach
                                        {{$total_issue}}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    
    <script type="text/javascript">
        
        // $(document).ready(function()
        //                 {
           
            
            // }); 
            
            // Load google charts
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);
            
            // Draw the chart and set the chart values
            function get_count()
            {
            
              var tmp =  $.ajax({ 
                global: false,   //create an ajax request to load_page.php
                async: false,
                type: "GET",
                url: "{{ url('/get-cluster-dashboard') }}",            
                data: {
                    
                },
                dataType: "json",   //expect html to be returned
              
                });
                return tmp;
            }
            function get_open_close()
            {
            
              var tmp =  $.ajax({ 
                global: false,   //create an ajax request to load_page.php
                async: false,
                type: "GET",
                url: "{{ url('/get-open-close') }}",            
                data: {
                    
                },
                dataType: "json",   //expect html to be returned
              
                });
                return tmp;
            }
            function drawChart() {
            var data1 = get_count();
            data1 = JSON.parse(data1['responseText']);
      
            var data = google.visualization.arrayToDataTable(data1);
                    console.log(data);
                    // console.log(data1);
                // Optional; add a title and set the width and height of the chart
                var options = {'title':'Clusters - Number of Open Issues', 'width':550, 'height':400};
                
                // Display the chart inside the <div> element with id="piechart"
                    var chart = new google.visualization.PieChart(document.getElementById('piechart'));
                    chart.draw(data, options);
                }
            </script>
            
            
            
            <script  type="text/javascript">
                $(document).ready(function() {
                    $('.issues_view').DataTable(
                    {
                        // scrollX: true,
                        ordering : false,
                    }
                    );
                } );
            </script>
            <script type="text/javascript">
                // Load the Visualization API and the line package.
                google.charts.load('current', {'packages':['bar']});
                // Set a callback to run when the Google Visualization API is loaded.
                google.charts.setOnLoadCallback(drawChart);
                 
                 function drawChart() {
                    var data_bu = get_open_close();
                    data_bu = JSON.parse(data_bu['responseText']);
                     var data = new google.visualization.DataTable();
                     // Add legends with data type
                     data.addColumn('string', 'Count');
                     data.addColumn('number', 'Total Open Issues');
                     data.addColumn('number', 'Action plans due');
                     data.addColumn('number', 'Action plans not due');

                    //  console.log(data_bu);

                     jQuery.each(data_bu, function(businessUnitId) {

                        var array1 = data_bu[businessUnitId].issues_info;
                        var count_action_plan_due = 0;
                        var count_action_plan_not_due = 0;
                         jQuery.each(array1, function(id) {
                            //  alert(array1[id].action_plan_due.length);
                             count_action_plan_due = count_action_plan_due + array1[id].action_plan_due.length;
                             count_action_plan_not_due = count_action_plan_not_due + array1[id].action_plan_not_due.length;
                            });
                         
                        data.addRow([data_bu[businessUnitId].bu_code, parseInt(data_bu[businessUnitId].issues_info_count), parseInt(count_action_plan_due), parseInt(count_action_plan_not_due)]);
                    });
                     var options = {
                        chart: {
                          title: 'Business Units',
                          subtitle: 'Show total open issues and Open action plans'
                     },
                    //  width: 1200,
                     height: 500,
                     axes: {
                     x: {
                      0: {side: 'bottom'}
                    }
                  }
                };
                var chart = new google.charts.Bar(document.getElementById('bar_chart'));
                chart.draw(data, options);
                          
                google.visualization.events.addListener(chart, 'select', selectHandler);

                function selectHandler(e) {	
                    var selection = chart.getSelection();
                    console.log(selection);
                    if (selection.length > 0) {
                        var mydata = data.getValue(selection[0].row,0);
                            alert(mydata);
                                //i want get key data L when klik stacked data L or P when klik stacked data P, because i want to send data

                        chart.setSelection([]);
                    }
                }
             }
           
            </script>
        </div>
    @endsection
        