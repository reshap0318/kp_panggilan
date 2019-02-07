@extends('layouts.frontend')

@section('title')
  dashboard
@stop

@section('content')
<script type="text/javascript" src="{{ URL::asset('/gantela/vendors/daterangepick/jquery.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/gantela/vendors/daterangepick/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/gantela/vendors/daterangepick/daterangepicker.min.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ URL::asset('/gantela/vendors/daterangepick/daterangepicker.css') }}" />
<div class="">
  <div class="row">
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
      <div class="tile-stats">
        <div class="icon"><i class="fa fa-sign-in"></i></div>
        <div id="atotal" class="count">@if($banyakmax)
            {{$banyakmax[0]->total}}
            @else
              0
            @endif
        </div>
        <h3>Panggilan Masuk Terbanyak</h3>
        <p id="ptotal">@if($banyakmax)
            {{$banyakmax[0]->nama}}
          @else
            Data Tidak Ada
          @endif</p>
      </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
      <div class="tile-stats">
        <div class="icon"><i class="fa fa-check-square-o"></i></div>
        <div id="aselesai" class="count">@if($panggilanselesai)
            {{$panggilanselesai[0]->terselesaikan}}
            @else
              0
            @endif</div>
        <h3>Panggilan Terselesaikan Terbanyak</h3>
        <p id="pselesai">@if($panggilanselesai)
            {{$panggilanselesai[0]->nama}}
          @else
            Data Tidak Ada
          @endif</p>
      </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
      <div class="tile-stats">
        <div class="icon"><i class="fa fa-question-circle"></i></div>
        <div id="aprank" class="count">@if($panggilanprank)
            {{$panggilanprank[0]->prank}}
          @else
            0
          @endif</div>
        <h3>Panggilan Prank Terbanyak</h3>
        <p id="pprank">@if($panggilanprank)
            {{$panggilanprank[0]->nama}}
          @else
            Data Tidak Ada
          @endif</p>
      </div>
    </div>
    <div class="animated flipInY col-lg-3 col-md-3 col-sm-6 col-xs-12">
      <div class="tile-stats">
        <div class="icon"><i class="fa fa-sign-out"></i></div>
        <div id="atidak" class="count">@if($panggilantidakmax)
            {{$panggilantidakmax[0]->tidak_terjawab}}
          @else
            0
          @endif</div>
        <h3>Panggilan Tidak Terjawab Terbanyak</h3>
        <p id="ptidak">@if($panggilantidakmax)
            {{$panggilantidakmax[0]->nama}}
          @else
            Data Tidak Ada
          @endif</p>
      </div>
    </div>
  </div>
  <br>
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <div class="dashboard_graph">
          <div class="row x_title">
            <div class="col-md-6">
              <h3>Grafik Laporan Panggilan<small></small></h3>
            </div>
            <div class="col-md-6">
              <div class="control-group">
                <div class="controls">
                  <div class="input-prepend input-group">
                    <input type="text" onchange="reload()" style="width: 200px" name="waktu" id="reportrange" class="form-control pull-right" value="01/20/2018 - 01/25/2018" />
                    <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                  </div>
                </div>
              </div>
            </div>
                <script type="text/javascript">
                  $(function() {

                      var start = moment().subtract(29, 'days');
                      var end = moment();

                      function cb(start, end) {
                          $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                      }

                      $('#reportrange').daterangepicker({
                          startDate: start,
                          endDate: end,
                          ranges: {
                             'Today': [moment(), moment()],
                             'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                             'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                             'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                             'This Month': [moment().startOf('month'), moment().endOf('month')],
                             'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                          }
                      }, cb);

                      cb(start, end);

                  });
                </script>
          </div>
          <div class="col-md-12 col-sm-12 col-xs-12">
                <div id="app">
                {!! $chart->container() !!}
                </div>
                <script src="https://unpkg.com/vue"></script>
                <script>
                    var app = new Vue({
                        el: '#app',
                    });
                </script>
                <script src=https://cdnjs.cloudflare.com/ajax/libs/echarts/4.0.2/echarts-en.min.js charset=utf-8></script>
                <!-- <script src="{{ URL::asset('/gantela/vendors/jquery/dist/jquery.min.js') }}"></script> -->
                <script>
                  id = "{!! $chart->id !!}";
                  var myChart = echarts.init(document.getElementById(id));

                  // specify chart configuration item and data
                  var option = {
                        title: {
                            text: ''
                        },
                        tooltip : {
                            trigger: 'axis',
                            axisPointer: {
                                type: 'shadow',
                                label: {
                                    show: true
                                }
                            }
                        },
                        toolbox: {
                            show : true,
                            feature : {
                                saveAsImage : {show: true},
                                magicType: {show: true, type: ['line', 'bar']},
                            }
                        },
                        calculable : true,
                        legend: {
                            data:['Panggilan Masuk','Panggilan Terselesaikan', 'Panggilan Prank','Panggilan Tidak Terjawab']
                        },
                        xAxis: {
                            type : 'category',
                            data: ['Tidak Terdaftar']
                        },
                        yAxis: {
                          type : 'value'
                        },
                        series: [{
                            name: 'DataBase Tidak Terhubung',
                            type: 'bar',
                            data: [0, 100, 20]
                        }]
                    };
                  // use configuration item and data specified to show chart
                  myChart.setOption(option);


                  function reload() {
                    var tanggal=document.getElementById("reportrange").value;
                    var server="<?php echo Request::root(); ?>";
                    tanggal = tanggal.replace(" - ",",");
                    console.log(server+'/datadash?data='+tanggal);
                    var x = new Array();
                    var y = new Array();
                    var p = new Array();
                    var q = new Array();
                    var k = new Array();
                    var l = new Array();
                    $.ajax({
                      url: server+'/datadash?data='+tanggal, data: "", dataType: 'json', success: function(rows)
                        {
                          var datas = rows['angka'];
                          for (var i = 0; i < datas.length; i++) {
                            var data = datas[i];
                            x.push(data);
                          }

                          datax = rows['label'];
                          for (var i = 0; i < datax.length; i++) {
                            var datl = datax[i];
                            y.push(datl);
                          }


                          myChart.setOption({
                      			series : x,
                            xAxis: {
                                data: y,
                            },
                      		});

                          var datas2 = rows['dit'];
                          p = datas2;

                          datax2 = rows['dits'];
                          for (var i = 0; i < datax2.length; i++) {
                            var datl = datax2[i];
                            q.push(datl);
                          }

                          myChart2.setOption({
                      			series : p,
                            xAxis: {
                                data: q,
                            },
                      		});

                          var datas3 = rows['dut'];
                          l = datas3;

                          datax3 = rows['duts'];
                          for (var i = 0; i < datax3.length; i++) {
                            var datl = datax3[i];
                            k.push(datl);
                          }

                          myChart3.setOption({
                      			series : l,
                            xAxis: {
                                data: k,
                            },
                      		});

                          datass = rows['pselesai'];
                          for (var i = 0; i < datass.length; i++) {
                            var da =  datass[i];
                            document.getElementById("aselesai").innerHTML = da.terselesaikan;
                            document.getElementById("pselesai").innerHTML = da.nama;
                          }

                          datat = rows['ptotal'];
                          for (var i = 0; i < datat.length; i++) {
                            var d = datat[i];
                            document.getElementById("atotal").innerHTML = d.total;
                            document.getElementById("ptotal").innerHTML = d.nama;
                          }

                          datatt = rows['ptidak'];
                          for (var i = 0; i < datatt.length; i++) {
                            var ds = datatt[i];
                            document.getElementById("atidak").innerHTML = ds.tidak_terjawab;
                            document.getElementById("ptidak").innerHTML = ds.nama;
                          }

                          datap = rows['pprank'];
                          for (var i = 0; i < datap.length; i++) {
                            var dp = datap[i];
                            document.getElementById("aprank").innerHTML = dp.prank;
                            document.getElementById("pprank").innerHTML = dp.nama;
                          }
                        }
                      });
                  }
                </script>
          </div>
          <div class="clearfix"></div>
      </div>
    </div>
  </div>
  <br>
  <div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">
      <div class="dashboard_graph">
          <div class="col-md-12 col-sm-12 col-xs-12">
                <div id="app2">
                {!! $chart_terbaik->container() !!}
                </div>
                <script src="https://unpkg.com/vue"></script>
                <script>
                    var app = new Vue({
                        el: '#app2',
                    });
                </script>
                <script src=https://cdnjs.cloudflare.com/ajax/libs/echarts/4.0.2/echarts-en.min.js charset=utf-8></script>
                <!-- <script src="{{ URL::asset('/gantela/vendors/jquery/dist/jquery.min.js') }}"></script> -->
                <script>
                  id = "{!! $chart_terbaik->id !!}";
                  console.log("{!! $chart_terbaik->id !!}");
                  var myChart2 = echarts.init(document.getElementById(id));

                  // specify chart configuration item and data
                  var option = {
                        title: {
                            text: ''
                        },
                        tooltip : {
                            trigger: 'axis',
                            axisPointer: {
                                type: 'shadow',
                                label: {
                                    show: true
                                }
                            }
                        },
                        toolbox: {
                            show : true,
                            feature : {
                                saveAsImage : {show: true},
                                magicType: {show: true, type: ['line', 'bar']},
                            }
                        },
                        calculable : true,
                        legend: {
                            data:['TOP 10 Terbaik']
                        },
                        xAxis: {
                            type : 'category',
                            data: ['Tidak Terdaftar']
                        },
                        yAxis: {
                          type : 'value'
                        },
                        series: [{
                            name: 'DataBase Tidak Terhubung',
                            type: 'bar',
                            data: [0, 100, 20]
                        }]
                    };
                  // use configuration item and data specified to show chart
                  myChart2.setOption(option);
                </script>
          </div>
          <div class="clearfix"></div>
      </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
      <div class="dashboard_graph">
          <div class="col-md-12 col-sm-12 col-xs-12">
                <div id="app3">
                {!! $chart_terbaik2->container() !!}
                </div>
                <script src="https://unpkg.com/vue"></script>
                <script>
                    var app = new Vue({
                        el: '#app3',
                    });
                </script>
                <script src=https://cdnjs.cloudflare.com/ajax/libs/echarts/4.0.2/echarts-en.min.js charset=utf-8></script>
                <!-- <script src="{{ URL::asset('/gantela/vendors/jquery/dist/jquery.min.js') }}"></script> -->
                <script>
                  id = "{!! $chart_terbaik2->id !!}";
                  console.log("{!! $chart_terbaik2->id !!}");
                  var myChart3 = echarts.init(document.getElementById(id));

                  // specify chart configuration item and data
                  var option = {
                        title: {
                            text: ''
                        },
                        tooltip : {
                            trigger: 'axis',
                            axisPointer: {
                                type: 'shadow',
                                label: {
                                    show: true
                                }
                            }
                        },
                        toolbox: {
                            show : true,
                            feature : {
                                saveAsImage : {show: true},
                                magicType: {show: true, type: ['line', 'bar']},
                            }
                        },
                        calculable : true,
                        legend: {
                            data:['TOP 10 Terbaik Tampa Prank']
                        },
                        xAxis: {
                            type : 'category',
                            data: ['Tidak Terdaftar']
                        },
                        yAxis: {
                          type : 'value'
                        },
                        series: [{
                            name: 'DataBase Tidak Terhubung',
                            type: 'bar',
                            data: [0, 100, 20]
                        }]
                    };
                  // use configuration item and data specified to show chart
                  myChart3.setOption(option);
                </script>
          </div>
          <div class="clearfix"></div>
      </div>
    </div>
  </div>
</div>
@stop
