<?php
$settings = \App\Setting::where('id',1)->first();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
{{--    <link rel="stylesheet" href="{{asset('frontend/vendors/bootstrap/bootstrap.min.css')}}">--}}
    <style>
        table.blueTable {
            border: 1px solid #1C6EA4;
            background-color: #EEEEEE;
            width: 100%;
            text-align: left;
            border-collapse: collapse;
        }
        table.blueTable td, table.blueTable th {
            border: 1px solid #AAAAAA;
            padding: 3px 2px;
        }
        table.blueTable tbody td {
            font-size: 13px;
        }
        table.blueTable tr:nth-child(even) {
            background: #D0E4F5;
        }
        table.blueTable thead {
            background: #1C6EA4;
            background: -moz-linear-gradient(top, #5592bb 0%, #327cad 66%, #1C6EA4 100%);
            background: -webkit-linear-gradient(top, #5592bb 0%, #327cad 66%, #1C6EA4 100%);
            background: linear-gradient(to bottom, #5592bb 0%, #327cad 66%, #1C6EA4 100%);
            border-bottom: 2px solid #444444;
        }
        table.blueTable thead th {
            font-size: 15px;
            font-weight: bold;
            color: #FFFFFF;
            border-left: 2px solid #D0E4F5;
        }
        table.blueTable thead th:first-child {
            border-left: none;
        }

        table.blueTable tfoot {
            font-size: 14px;
            font-weight: bold;
            color: #FFFFFF;
            background: #D0E4F5;
            background: -moz-linear-gradient(top, #dcebf7 0%, #d4e6f6 66%, #D0E4F5 100%);
            background: -webkit-linear-gradient(top, #dcebf7 0%, #d4e6f6 66%, #D0E4F5 100%);
            background: linear-gradient(to bottom, #dcebf7 0%, #d4e6f6 66%, #D0E4F5 100%);
            border-top: 2px solid #444444;
        }
        table.blueTable tfoot td {
            font-size: 14px;
        }
        table.blueTable tfoot .links {
            text-align: right;
        }
        table.blueTable tfoot .links a{
            display: inline-block;
            background: #1C6EA4;
            color: #FFFFFF;
            padding: 2px 8px;
            border-radius: 5px;
        }
    </style>
</head>

<body >
<header class="clearfix" style="padding-bottom: -0.2rem; ">
    <div id="logo" style="text-align: center; padding-bottom: -1rem">

        <img src="data:image/png;base64, {{base64_encode(@file_get_contents(asset('images/logo/Cfresh-label.png')))}}" height="100px" width="100px"><br><br>
        <h3 class="text-bold text-primary">C-fresh Expenses Summary </h3>
        <br><br>
    </div>
    <div class="row" style="margin-top: 0.5rem;" >
        <div class="float-left col-md-6" style="width: 350px">
            <table class="table table-bordered  table-sm blueTable" style="font-size: 12px">
                <tr >
                    <td style="background-color: darkred; color: white;"><strong>Statement Period</strong></td>
                    <td>{{\Carbon\Carbon::parse($from)->toFormattedDateString()}} to {{\Carbon\Carbon::parse($to)->toFormattedDateString()}}</td>
                </tr>

                <tr >
                    <td style="background-color: darkred; color: white;"><strong>Total Expenses</strong></td>
                    <td>NGN {{number_format($total_amount, 2)}}</td>
                </tr>

                <tr >
                    <td style="background-color: darkred; color: white;"><strong>Discount Lost</strong></td>
                    <td>NGN {{number_format($discount_lost, 2)}}</td>
                </tr>

                <tr >
                    <td style="background-color: darkred; color: white;"><strong>Damage Lost</strong></td>
                    <td>NGN {{number_format($damage_lost, 2)}}</td>
                </tr>

            </table>
        </div>
        <div class="col"></div>
        <div class="float-right col-md-6" >
            <table class="table table-borderless table-sm" >
{{--                <tr style="text-align: center">--}}
{{--                    <td><span style="color: darkred; font-size: 14px;  text-align: right"><strong><h3>{{$settings->site_name}}</h3></strong></span></td>--}}
{{--                </tr>--}}
{{--                <tr>--}}
{{--                    <td style="font-size: 12px; color: #cccccc; text-align: right; padding-top: -0.2rem  ">{{$settings->contact_address}}</td>--}}
{{--                </tr>--}}
{{--                <tr>--}}
{{--                    <td style="font-size: 12px; color: #cccccc;  text-align: right; padding-top: -0.5rem ">{{$settings->contact_email}}-({{$settings->contact_number}})</td>--}}
{{--                </tr>--}}
            </table>


        </div>
    </div>
</header>

<div class="table-responsive " style="padding-top: -0.2rem">
    <table class="table table-striped table-bordered blueTable" style='background:  url("data:image/png;base64, {{base64_encode(@file_get_contents(asset('images/logo/dimension.png')))}}"); font-size: 12px'>
        <thead>
        <tr style="background-color: darkred; color: white; font-size: 13px">
            <th>Date</th>
            <th>Time</th>
            <th>Amount</th>
            <th>User</th>
            <th>description</th>
{{--            <th>Receipt</th>--}}
        </tr>
        </thead>
        <tbody>
        @foreach ($PDFReport as $PDFReports)
            <tr style="font-size: 12px">
                <td style="background-color: lightgoldenrodyellow">{{\Carbon\Carbon::parse($PDFReports->created_at)->toFormattedDateString()}}</td>
                <td>{{\Carbon\Carbon::parse($PDFReports->created_at)->format('h:i A')}}</td>
                <td>NGN {{number_format($PDFReports->amount, 2)}}</td>
                <td>{{!empty(\App\Admin::where('id',$PDFReports->user_id)->first()->employee->name) ?  \App\Admin::where('id',$PDFReports->user_id)->first()->employee->name : ''}}</td>
                <td>{{$PDFReports->description}}</td>
{{--                <td>--}}
{{--                    @if(!empty($ViewsPages->doc))--}}
{{--                        <a href="{{asset($ViewsPages->doc)}}">Receipt</a>--}}
{{--                    @else--}}
{{--                        No Receipt--}}
{{--                    @endif--}}
{{--                </td>--}}
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<script type="text/php">
    if ( isset($pdf) ) {
        $font = $fontMetrics->getFont("Times", "bold");
        $color = array(0,0,0);
        $w = $pdf->get_width();
        $h = $pdf->get_height();

    $pdf->page_text(30, 810, "{{\Carbon\Carbon::parse(today())->toFormattedDateString()}}-({{\Carbon\Carbon::parse(now())->format('h:i A')}})", $font, 6, array(0,0,1));
        $pdf->page_text(270, 810, "Page {PAGE_NUM} of {PAGE_COUNT}", $font, 6, $color);
        $pdf->page_text(530, 810, "cfresh.org", $font, 8, array(0,0,1));
    }
</script>
{{--<script src="{{asset('frontend/vendors/jquery/jquery-3.2.1.min.js')}}"></script>--}}
{{--<script src="{{ asset('assets/libs/popper.js/dist/popper.min.js') }}"></script>--}}
{{--<script src="{{asset('frontend/vendors/bootstrap/bootstrap.bundle.min.js')}}"></script>--}}
</body>
</html>
