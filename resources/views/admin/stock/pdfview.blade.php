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
        <h3 class="text-bold text-primary">Summary of Batch History </h3>
        <br><br>
    </div>
    <div class="row" style="margin-top: 0.5rem;" >
        <div class="float-left col-md-6" style="width: 350px">
            <table class="table table-bordered  table-sm" style="font-size: 12px">
                <tr>
                    <td style="background-color: darkred; color: white;"><strong>Statement Period</strong></td>
                    <td colspan="2">{{\Carbon\Carbon::parse($from)->toFormattedDateString()}} to {{\Carbon\Carbon::parse($to)->toFormattedDateString()}}</td>
                </tr>

                <tr>
                    <td class="text-center" style="background-color: darkred; color: white;" ><strong>Materials</strong></td>
                    <td class="text-center" style="background-color: darkred; color: white;" ><strong>Quantities</strong></td>
                    <td class="text-center" style="background-color: darkred; color: white;" ><strong> Amount (Naira)</strong></td>
                </tr>
                <tr >
                    <td style="background-color: darkred; color: white;"><strong>Preforms</strong></td>
                    <td>{{number_format($preforms_qty, 0)}}</td>
                    <td>{{number_format($preforms_amt, 2)}}</td>
                </tr>
                <tr >
                    <td style="background-color: darkred; color: white;"><strong>Caps</strong></td>
                    <td>{{number_format($caps_qty, 0)}}</td>
                    <td>{{number_format($caps_amt, 2)}}</td>
                </tr>
                <tr >
                    <td style="background-color: darkred; color: white;"><strong>Labels</strong></td>
                    <td>{{number_format($labels_qty, 0)}}</td>
                    <td>{{number_format($labels_amt, 2)}}</td>
                </tr>
                <tr >
                    <td style="background-color: darkred; color: white;"><strong>Total Cost (Naira)</strong></td>
                    <td></td>
                    <td class="text-center"><strong>{{number_format($total_cost, 2)}}</strong></td>
                </tr>

               {{-- <tr>
                    <td class="text-center" style="background-color: darkred; color: white;" colspan="3"><strong>Remaining Balance</strong></td>
                </tr>

                <tr >
                    <td style="background-color: darkred; color: white;"><strong>Opening(Preforms)</strong></td>
                    <td>{{number_format($preforms_open_qty, 0)}}</td>
                    <td>{{number_format($preforms_open_amt, 2)}}</td>
                </tr> <tr >
                    <td style="background-color: darkred; color: white;"><strong>Closing(Preforms)</strong></td>
                    <td>{{number_format($preforms_close_qty, 0)}}</td>
                    <td>{{number_format($preforms_open_amt, 2)}}</td>
                </tr>
                <tr >
                    <td style="background-color: darkred; color: white;"><strong>Opening(Caps)</strong></td>
                    <td>{{number_format($caps_open_qty, 0)}}</td>
                    <td>{{number_format($caps_open_amt, 2)}}</td>
                </tr> <tr >
                    <td style="background-color: darkred; color: white;"><strong>Closing(Caps)</strong></td>
                    <td>{{number_format($caps_close_qty, 0)}}</td>
                    <td>{{number_format($caps_open_amt, 2)}}</td>
                </tr>
                <tr >
                    <td style="background-color: darkred; color: white;"><strong>Opening(Labels)</strong></td>
                    <td>{{number_format($labels_open_qty, 0)}}</td>
                    <td>{{number_format($labels_open_amt, 2)}}</td>
                </tr> <tr >
                    <td style="background-color: darkred; color: white;"><strong>Closing(Labels)</strong></td>
                    <td>{{number_format($labels_close_qty, 0)}}</td>
                    <td>{{number_format($labels_open_amt, 2)}}</td>
                </tr>--}}
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
        <tr style="background-color: darkred; color: white; font-size: 12px">
            <th >Batch Date</th>
            <th>Batch Name</th>
            <th>Cost</th>
            <th>Material</th>
            <th>Case</th>
            <th>Bags</th>
            <th>Total Materials</th>
            <th>material/bag</th>
            <th>Company</th>
            <th>Description</th>

        </tr>
        </thead>
        <tbody>
        @foreach ($PDFReport as $PDFReports)
            <tr style="font-size: 10px">
                <td style="background-color: lightgoldenrodyellow">{{\Carbon\Carbon::parse($PDFReports->created_at)->toFormattedDateString()}}</td>
                <td>{{ $PDFReports->batch_name }}</td>
                <td>NGN {{number_format($PDFReports->amount, 2)}}</td>
                <td>{{$PDFReports->material}}</td>
                @if($PDFReports->material == 'Preforms')
                    <td>{{$PDFReports->batch->preform['box']['case']}}</td>
                @elseif($PDFReports->material == 'Caps')
                    <td>{{$PDFReports->batch->cap['box']['case']}}</td>
                @elseif($PDFReports->material == 'Labels')
                    <td>{{$PDFReports->batch->Label['box']['case']}}</td>
                @endif
                <td>{{$PDFReports->no_bags}}</td>
                <td>{{$PDFReports->tot_materials}}</td>
                <td>{{$PDFReports->no_materials}}</td>
                <td>{{$PDFReports->company}}</td>
                <td>{{$PDFReports->description}}</td>
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
<script src="{{asset('frontend/vendors/jquery/jquery-3.2.1.min.js')}}"></script>
<script src="{{ asset('assets/libs/popper.js/dist/popper.min.js') }}"></script>
<script src="{{asset('frontend/vendors/bootstrap/bootstrap.bundle.min.js')}}"></script>
</body>
</html>
