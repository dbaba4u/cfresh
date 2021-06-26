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
    <style>
        /**
           * Define the width, height, margins and position of the watermark.
           **/
        #watermark {
            position: fixed;

            /**
                Set a position in the page for your image
                This should center it vertically
            **/
            bottom:   10cm;
            left:     5.5cm;

            /** Change image dimensions**/
            width:    8cm;
            height:   8cm;

            /** Your watermark should be behind every content**/
            z-index:  -1000;

        }

        #watermark img{
            opacity: 0.1;
        }

        .line0{
            width: 630px;
            height: 15px;
            border-bottom: 0.6px solid blue;
            position: absolute;
            font-size: smaller;
            text-align: center;
            margin-top: 30px;
        }
        .line{
            width: 500px;
            height: 18px;
            border-bottom: 0.6px solid blue;
            position: absolute;
            font-size: smaller;
            text-align: center;
        }
        .line2{
            width: 150px;
            height: 100px;
            border-top: 0.8px solid blue;
            position: absolute;
            font-size: smaller;
            text-align: center;
        }
    </style>
</head>

<body >
<header class="clearfix" style="padding-bottom: -0.2rem; ">
    <div id="logo" style="text-align: center; padding-bottom: -1rem">

        <img src="data:image/png;base64, {{base64_encode(@file_get_contents(asset('images/logo/Cfresh-label.png')))}}" height="100px" width="100px"><br>
        <div >
            <p class="text-center text-primary text-bold">{{$settings->site_name}} <br>
            <span style="font-size: 10px">{{$settings->contact_address}}</span> <br>
            <span style="font-size: 10px">{{$settings->contact_email}}-({{$settings->contact_number}})</span></p>
        </div>
        <br>
        <h3 class="text-bold text-white bg-primary">Cash Receipt </h3>
        <br><br>
    </div>
    <div class="container">
        <div class="row"  >
           <div style="display: inline-block">
               <strong>Date : </strong><span style="text-decoration: underline">{{\Carbon\Carbon::parse($customer_payment->created_at)->toFormattedDateString()}}-({{\Carbon\Carbon::parse($customer_payment->created_at)->format('H:i:s A')}})</span>
           </div>
        </div>

        <div class="row" style="margin-top: 0.5rem;" >
            <div style="display: inline-block">
                <strong>Receipt No : </strong><span style="text-decoration: underline">
                    {{'#'.$index}}
                </span>
            </div>
        </div>
        <br><br>
        <div class="row mb-4"  >
            <div style="display: inline-block">
                <span><strong>Received from</strong> </span>
                <span class="line" style="margin-left: 20px;">
                    <em>{{\App\User::find($customer_payment->customer)->name}}</em>
                </span>
            </div>
        </div>
        <br>
        <div class="row mb-4"  >
            <div style="display: inline-block">
                <span><strong>the sum of</strong> </span>
                <?php $digit = new NumberFormatter("en", NumberFormatter::SPELLOUT);?>
                <span class="line" style="margin-left: 42px;">
                    <em class="text-capitalize">{{$digit->format($customer_payment->amount) . ' naira only' }}</em>
                </span>
            </div>
        </div>

        <br>
        <div class="row"  >
            <div style="display: inline-block">
                <span><strong>being payment for</strong> </span>
                <span class="line" style="margin-left: 1px;">
                    <em>{{$customer_payment->description}}</em>
                </span>
            </div>
            <div class="line0" ></div>
        </div>
        <br>

        <div class="row"  >
            <div style="margin-top: 50px;">
                <span><strong>Customer Address</strong> </span>
                <span class="line" style="margin-left: 1px;">
                    <em>{{$customer->address}}</em>
                </span>
            </div>
        </div>

    </div>
    <br><br><br><br><br><br>

    <div class="text-white bg-primary text-bold text-center" style="display: inline-block; width: 250px; height: 30px">
        <span><strong >Amount: </strong></span>
        <span style="font-size: smaller" >
                <em >NGN {{number_format($customer_payment->amount,2)}}</em>
        </span>

        <span class="line2 text-dark" style="margin-left: 350px;">
            Manager's Signature
        </span>
    </div>
</header>
<div id="watermark">
    <img src="data:image/png;base64, {{base64_encode(@file_get_contents(asset('images/logo/CfreshNew.png')))}}" height="100%" width="100%" />
</div>
{{--<div class="table-responsive" style="padding-top: -0.2rem">
    <table class="table table-striped table-bordered" style='background:  url("data:image/png;base64, {{base64_encode(@file_get_contents(asset('images/logo/dimension.png')))}}"); font-size: 12px'>
        <thead>
        <tr style="background-color: darkred; color: white; font-size: 13px">
            <th>Date</th>
            --}}{{--            <th>Time</th>--}}{{--
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
                --}}{{--                <td>{{\Carbon\Carbon::parse($PDFReports->created_at)->format('H:i:s A')}}</td>--}}{{--
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
</div>--}}
<footer>

    <script type="text/php">
    if ( isset($pdf) ) {
        $font = $fontMetrics->getFont("Times", "bold");
        $color = array(0,0,0);
        $w = $pdf->get_width();
        $h = $pdf->get_height();

    $pdf->page_text(30, 810, "{{\Carbon\Carbon::parse(today())->toFormattedDateString()}}-({{\Carbon\Carbon::parse(now())->format('h:i A')}})", $font, 6, array(0,0,1));

        $pdf->page_text(530, 810, "cfresh.org", $font, 8, array(0,0,1));
    }
</script>

</footer>
<script src="{{asset('frontend/vendors/jquery/jquery-3.2.1.min.js')}}"></script>
<script src="{{ asset('assets/libs/popper.js/dist/popper.min.js') }}"></script>
<script src="{{asset('frontend/vendors/bootstrap/bootstrap.bundle.min.js')}}"></script>
</body>
</html>
