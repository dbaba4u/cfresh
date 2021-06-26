<?php
$settings = \App\Setting::where('id',1)->first();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{asset('frontend/vendors/bootstrap/bootstrap.min.css')}}">
</head>

<body >
<header class="clearfix" style="padding-bottom: -0.2rem; ">
    <div id="logo" style="text-align: center; padding-bottom: -1rem">

        <img src="data:image/png;base64, {{base64_encode(@file_get_contents(asset('images/logo/Cfresh-label.png')))}}" height="100px" width="100px"><br><br>
        <h3 class="text-bold text-primary">Summary of Damages </h3>
        <br><br>
    </div>
    <div class="row" style="margin-top: 0.5rem;" >
        <div class="float-left col-md-6" style="width: 350px">
            <table class="table table-bordered  table-sm" style="font-size: 12px">
                <tr>
                    <td style="background-color: darkred; color: white;"><strong>Statement Period</strong></td>
                    <td>{{\Carbon\Carbon::parse($from)->toFormattedDateString()}} to {{\Carbon\Carbon::parse($to)->toFormattedDateString()}}</td>
                </tr>

                <tr>
                    <td class="text-center" style="background-color: darkred; color: white;" colspan="2"><strong>Damages Amount (Naira)</strong></td>
                </tr>
                <tr >
                    <td style="background-color: darkred; color: white;"><strong>Preforms</strong></td>
                    <td>{{number_format($preforms_amt, 2)}}</td>
                </tr>
                <tr >
                    <td style="background-color: darkred; color: white;"><strong>Caps</strong></td>
                    <td>{{number_format($caps_amt, 2)}}</td>
                </tr>
                <tr >
                    <td style="background-color: darkred; color: white;"><strong>Labels</strong></td>
                    <td>{{number_format($labels_amt, 2)}}</td>
                </tr>
                <tr>
                    <td class="text-center" style="background-color: darkred; color: white;" colspan="2"><strong>Total Amount (Naira)</strong></td>
                </tr>
                <tr >
                    <td style="background-color: darkred; color: white;"><strong>Total amount</strong></td>
                    <td>{{number_format($total_amount, 2)}}</td>
                </tr>

                <tr>
                    <td class="text-center" style="background-color: darkred; color: white;" colspan="2"><strong>Damages Quantity</strong></td>
                </tr>
                <tr >
                    <td style="background-color: darkred; color: white;"><strong>Preforms</strong></td>
                    <td>{{number_format($preforms_qty, 0)}}</td>
                </tr>
                <tr >
                    <td style="background-color: darkred; color: white;"><strong>Caps</strong></td>
                    <td>{{number_format($caps_qty, 0)}}</td>
                </tr>
                <tr >
                    <td style="background-color: darkred; color: white;"><strong>Labels</strong></td>
                    <td>{{number_format($labels_qty, 0)}}</td>
                </tr>

            </table>
        </div>
        <div class="col"></div>
        <div class="float-right col-md-6" >
            <table class="table table-borderless table-sm" >
                <tr>
                    <td><span style="color: darkred; font-size: 14px;  text-align: right"><strong><h3>{{$settings->site_name}}</h3></strong></span></td>
                </tr>
                <tr>
                    <td style="font-size: 12px; color: #cccccc; text-align: right; padding-top: -0.2rem  ">{{$settings->contact_address}}</td>
                </tr>
                <tr>
                    <td style="font-size: 12px; color: #cccccc;  text-align: right; padding-top: -0.5rem ">{{$settings->contact_email}}-({{$settings->contact_number}})</td>
                </tr>
            </table>


        </div>
    </div>
</header>
<div class="table-responsive" style="padding-top: -0.2rem">
    <table class="table table-striped table-bordered" style='background:  url("data:image/png;base64, {{base64_encode(@file_get_contents(asset('images/logo/dimension.png')))}}"); font-size: 12px'>
        <thead>
        <tr style="background-color: darkred; color: white; font-size: 13px">
            <th>Date</th>
{{--            <th>Time</th>--}}
            <th>Batch Code</th>
            <th>Material</th>
            <th>Quantity</th>
            <th>Amount</th>
            <th>User</th>

        </tr>
        </thead>
        <tbody>
        @foreach ($PDFReport as $PDFReports)
            <tr style="font-size: 10px">
                <td>{{\Carbon\Carbon::parse($PDFReports->created_at)->toDateString()}}</td>
{{--                <td>{{\Carbon\Carbon::parse($PDFReports->created_at)->format('H:i:s A')}}</td>--}}
                <td>{{$PDFReports->batch}}</td>
                <?php
                $first_three_cha = (substr($PDFReports->batch,0,3));
                if ($first_three_cha == 'PRE'){
                    $material =  'Preforms';
                }
                if ($first_three_cha == 'CAP'){
                    $material =  'Caps';
                }
                if ($first_three_cha == 'LBL'){
                    $material =  'Labels';
                }
                ?>
                <td>{{$material}}</td>
                <td>{{$PDFReports->quantity}}</td>
                <td>NGN {{number_format($PDFReports->amount, 2)}}</td>
                <td>{{$PDFReports->admin_name}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<footer>

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

</footer>
<script src="{{asset('frontend/vendors/jquery/jquery-3.2.1.min.js')}}"></script>
<script src="{{ asset('assets/libs/popper.js/dist/popper.min.js') }}"></script>
<script src="{{asset('frontend/vendors/bootstrap/bootstrap.bundle.min.js')}}"></script>
</body>
</html>
