@extends('layouts.main-app')

@section('title', 'SRS Biz Analytics - BFFHAI')

@section('links_css')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endsection

@section('content')

<div class="row mt-3">
    <div class="col-md-6 px-1">
        <div class="card">
            <div class="card-header text-center">
                <span class="h6">TICKET COUNT PER CATEGORY</span>
            </div>
            <div class="row p-3 justify-content-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <button class="btn btn-outline-secondary btn-sm" type="button" onclick="document.getElementById('chart1_daterange').focus();">
                            <i class='bx bx-calendar'></i>
                        </button>
                        <input type="text" name="chart1_daterange" id="chart1_daterange" class="form-control form-control-sm text-center chart_daterange" value="{{ $initialFrom .' - '. $initialTo}}">
                    </div>
                </div>
            </div>
            <div style="height: 47px;">
                
            </div>
            <div style="height: 47px;">

            </div>
            <div class="card-body">
                <div class="chart-container mt-3">
                    <canvas class="w-100" id="chart" style="height: 50vh;"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 px-1">
        <div class="card">
            <div class="card-header text-center">
                <span>TICKET STATUS PER CATEGORY</span>
            </div>
            <div class="row p-3 justify-content-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <button class="btn btn-outline-secondary btn-sm" type="button" onclick="document.getElementById('ticket_status_daterange').focus();">
                            <i class='bx bx-calendar'></i>
                        </button>
                        <input type="text" name="ticket_status_daterange" id="ticket_status_daterange" class="form-control form-control-sm text-center chart_daterange" value="{{ $initialFrom .' - '. $initialTo}}">
                    </div>
                </div>
            </div>
            <div class="row p-2 justify-content-center">
                <div class="col-md-7">
                    <div class="input-group input-group-sm">
                        <button class="btn btn-outline-secondary px-3" type="button" style="font-size: 13px; cursor: default;">Category</button>
                        <select class="form-select" name="tix_stat_chart_option" id="category" onchange="changeSubCategories()">
                            <option value="1">Resident</option>
                            <option value="2">Non-resident</option>
                            <option value="3">Commercial</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row p-2 justify-content-center">
                <div class="col-md-7">
                    <div class="input-group input-group-sm">
                        <button class="btn btn-outline-secondary" type="button" style="font-size: 11px; cursor: default;">Sub-Category</button>
                        <select class="form-select" name="" id="sub_category">
                        </select>
                    </div>
                </div>
            </div>
            {{-- <div class="container">
                <div class="row px-3 py-2 justify-content-center">
                    <div class="col-md-8 col-8 text-center">
                        <input type="radio" class="btn-check" name="tix_stat_chart_option" id="tix_stat_chart_resident" value="1" autocomplete="off" checked>
                        <label class="btn btn-outline-primary btn-sm px-3 mx-1" for="tix_stat_chart_resident" style="font-size: 12px;">Resident</label>
                
                        <input type="radio" class="btn-check" name="tix_stat_chart_option" id="tix_stat_chart_non_resident"value="2" autocomplete="off">
                        <label class="btn btn-outline-primary btn-sm px-3 mx-1" for="tix_stat_chart_non_resident" style="font-size: 12px;">Non-resident</label>
                
                        <input type="radio" class="btn-check" name="tix_stat_chart_option" id="tix_stat_chart_commercial" value="3" autocomplete="off">
                        <label class="btn btn-outline-primary btn-sm px-3 mx-1" for="tix_stat_chart_commercial" style="font-size: 12px;">Commercial</label>
                    </div>
                </div>
            </div> --}}
            <div class="card-body">
                <div class="chart-container mt-3">
                    <canvas class="w-100" id="ticket_status_chart" style="height: 50vh;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('links_js')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.2.0/chartjs-plugin-datalabels.min.js" integrity="sha512-JPcRR8yFa8mmCsfrw4TNte1ZvF1e3+1SdGMslZvmrzDYxS69J7J49vkFL8u6u8PlPJK+H3voElBtUCzaXj+6ig==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
$(document).ready(function () {
    // $('input[name="chart1_daterange"]').daterangepicker();
    $('.chart_daterange').daterangepicker({
        minDate: '12/01/2022'
    });
    
    const ctx = document.getElementById('chart');
    const ctxTicketStatus = document.getElementById('ticket_status_chart');

    var category1 = [];
    var category2 = [];
    var category3 = [];


    const topLabels = {
        id: 'topLabels',
        afterDatasetsDraw(chart2, args, pluginOptions) {
            const { ctx, scales: { x, y } } = chart2;
            
            chart2.data.datasets[0].data.forEach((datapoint, index) => {
                const datasetArray = [];

                chart2.data.datasets.forEach((dataset) => {
                    if(typeof dataset.data[index] === 'undefined') {
                        datasetArray.push(0);
                    }
                    else {
                        datasetArray.push(dataset.data[index]);
                    }
                });

                function totalSum(total, values) {
                    return total + values;
                }

                let sum = datasetArray.reduce(totalSum, 0);

                ctx.font = 'bold 12px sans-serif';
                ctx.fillStyle = 'rgba(255, 26, 104, 1)';
                ctx.textAlign = 'center';
                ctx.fillText(sum, x.getPixelForValue(index), y.getPixelForValue(sum) - 10);

            });
        }
    }

    getSubCategories = () => {
        $.ajax({
            // url: '{{ route("getSubCategories") }}',
            url: '/sticker/request/sub_categories',
            data: {
                'category': $('#category').val()
            },
            success: function (data) {
                var all = {'value': 0, 'name': 'All'};
                var html = '<option value="0">All</option>';

                category1.push(all);
                category2.push(all);
                category3.push(all);

                $.each(data, function (index, item) {
                    if (item.name != 'Deed of Sale (DOS)') {
                        if (item.category_id == 1) {
                            category1.push({'value': item.id, 'name': item.name});
                            html += '<option value="'+item.id+'">'+item.name+'</option>';
                        } else if (item.category_id == 2) {
                            category2.push({'value': item.id, 'name': item.name});
                        } else if (item.category_id == 3) {
                            category3.push({'value': item.id, 'name': item.name})
                        }
                    }
                });
                $('#sub_category').html(html);
            }
        });
    }

    changeSubCategories = () => {
        var category = $('#category').val();
        var html = '';

        switch(category) {
            case '1':
                for(let i = 0; i < category1.length; i++) {
                    html += `<option value="${category1[i]['value']}">${category1[i]['name']}</option>`;
                }
                break;
            case '2':
                for(let i = 0; i < category2.length; i++) {
                    html += `<option value="${category2[i]['value']}">${category2[i]['name']}</option>`;
                }
                break;
            case '3':
                for(let i = 0; i < category3.length; i++) {
                    html += `<option value="${category3[i]['value']}">${category3[i]['name']}</option>`;
                }
                break;
        }
        $('#sub_category').html(html);
    }

    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: '',
                data: [],
            }]
        },
        options: {
            maintainAspectRatio: false,
            scales: {
                // y: {
                //   beginAtZero: true
                // }
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            // borderWidth: 1,
            plugins: {
                datalabels: {
                    // color: 'white',
                    font: {
                        weight: 'bold'
                    },
                }
            },
        }, 
        plugins: [ChartDataLabels],
    });

    // var chart2 = new Chart(ctx2, {
    //     type: 'bar',
    //     data: {
    //         labels: [],
    //         datasets: [{
    //             label: '',
    //             data: [],
    //         }]
    //     },
    //     options: {
    //         maintainAspectRatio: false,
    //         scales: {
    //             x: {
    //                 stacked: true,
    //                 grid: {
    //                     display: false
    //                 }
    //             },
    //             y: {
    //                 stacked: true,
    //                 beginAtZero: true,
    //                 grace: 220
    //             }
    //         },
    //         borderWidth: 1,
    //         plugins: {
    //             // title: {
    //             //     display: true,
    //             //     text: 'No. of Tickets Status',
    //             // }
    //             // datalabels: {
    //             //     anchor: 'end',
    //             //     align: 'start',
    //             //     // formatter: (value, context) => {
    //                 //     const datasetArray = [];
    //                 //     context.chart.data.datasets.forEach((dataset) => {
    //                 //         if (dataset.data[context.dataIndex] != undefined) {
    //                 //             datasetArray.push(dataset.data[context.dataIndex]);
    //                 //         }
    //                 //     });

    //                 //     function totalSum(total, datapoint) {
    //                 //         return total + datapoint;
    //                 //     }

    //                 //     let sum = datasetArray.reduce(totalSum, 0);

    //                 //     if (context.datasetIndex === datasetArray.length -1) {
    //                 //         return sum;
    //                 //     } else {
    //                 //         return '';
    //                 //     }
    //                 // }
    //             //},
    //             datalabels: {
    //                 formatter: (value, context) => {
    //                     return value || null;
    //                 }
    //             },
    //             legend: {
    //                 onClick: null 
    //                 // onClick: function (evt, item) {
    //                 //     handleClick();
    //                 //     Chart.defaults.plugins.legend.onClick.call(this, evt, item, this);
    //                 // }
    //             }
    //         },

    //     },
    //     plugins: [ChartDataLabels, topLabels],
    // });

    var ticketStatusChart = new Chart(ctxTicketStatus, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: '',
                data: [],
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                datalabels: {
                    backgroundColor: function(context) {
                        return context.dataset.backgroundColor;
                    },
                    borderRadius: 4,
                    color: 'white',
                    font: {
                        size: '10',
                        weight: 'bold'
                    },
                    padding: 5
                }
            },
            aspectRatio: 5 / 3,
            elements: {
                line: {
                    fill: false,
                    tension: 0.4
                }
            },
        },
        plugins: [ChartDataLabels],
    });

    var getTicketChart = (dates) => {
        $.ajax({
            url: '{{ route("chart.tickets") }}',
            dataType: 'json',
            data: {
                dates: dates,
            },
            success: function (data) {
                var residentData = {
                    label: 'Resident',
                    data: data.residentData,
                    borderColor: '#4baced',
                    backgroundColor: '#9ad0f5',
                };

                var nonResidentData = {
                    label: 'Non-resident',
                    data: data.nonResidentData,
                    borderColor: 'black',
                    backgroundColor: 'black',
                    datalabels: {
                        color: 'white'
                    }
                };

                var commercialData = {
                    label: 'Commercial',
                    data: data.commercialData,
                    borderColor: '#ffb56b',
                    backgroundColor: '#ffcf9f',
                };

                var ticketsData = {
                    labels: data.labels,
                    datasets: [residentData, nonResidentData, commercialData],
                };

                chart.data = ticketsData;
                chart.update();
            }
        });
    }

    var getTicketByStatus = (dates, category = 1, subCategory = 0) => {
        $.ajax({
            url: '{{ route("chart-line.tickets.status") }}',
            dataType: 'json',
            data: {
                dates: dates,
                category: category,
                subCategory: subCategory
            },
            success: function (data) {
                var totalData = {
                    label: 'Total',
                    data: data.totalData,
                    backgroundColor: '#36a2eb',
                    borderColor: '#36a2eb',
                    datalabels: {
                        align: 'end',
                        anchor: 'end'
                    }
                };

                var rejectedData = {
                    label: 'Rejected',
                    data: data.rejectedData,
                    backgroundColor: '#ff3784',
                    borderColor: '#ff3784',
                };

                var openData = {
                    label: 'Open',
                    data: data.openData,
                    backgroundColor: '#4bc0c0',
                    borderColor: '#4bc0c0',
                    datalabels: {
                        align: 'start',
                        anchor: 'start'
                    }
                };

                var ticketsByStatusData = {
                    labels: data.labels,
                    datasets: [totalData, rejectedData, openData],
                };

                ticketStatusChart.data = ticketsByStatusData;
                ticketStatusChart.update();   
            }
        });
    }

    getSubCategories();

    getTicketChart($('input[name="chart1_daterange"]').val());
    getTicketByStatus($('input[name="ticket_status_daterange"]').val());

    $('input[name="chart1_daterange"]').on('change', function () {
        getTicketChart($('input[name="chart1_daterange"]').val());
    });

    $('input[name="ticket_status_daterange"]').on('change', function () {
        getTicketByStatus($('input[name="ticket_status_daterange"]').val(), $('#category').val(), $('#sub_category').val());
    });
    
    $('input[name="tix_stat_chart_option"]').on('change', function () {
        getTicketByStatus($('input[name="ticket_status_daterange"]').val(), $('input[name="tix_stat_chart_option"]:checked').val());
    });

    $('#category').on('change', function () {
        getTicketByStatus($('input[name="ticket_status_daterange"]').val(), $(this).val(), $('#sub_category').val());
    });

    $('#sub_category').on('change', function () {
        getTicketByStatus($('input[name="ticket_status_daterange"]').val(), $('#category').val(), $(this).val());
    });

});
</script>
@endsection