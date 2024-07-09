<html>
<title>MTFRB Application Form</title>
<header>
    <style>

        body {
            font-family: Arial, sans-serif;
        }

        img {
            width: 815px;
            position: absolute;
            top: -45px;
            left: -45px;
        }

        span {
            font-weight: bold;
        }

        .operator_name {
            margin-right: 30%;
            font-size: 20px;
        }

        .mtfrb_case_no {
            padding-left: 100px;
            font-size: 22px;
        }

        .body_number {
            margin-left: 530px;
            position: relative;
            top: 100px;
            font-size: 30px;
        }

        /* .mtfrb_case_no_recorded {

            text-align: left;
        } */

        .payment_information {
            width: 100%;
            position: absolute;
            bottom: 0;
            padding: 10px;
            border: 2px dashed #000;
        }

        table {
            font-weight: bold;
            font-size: 13px;
        }

        table tr td {
            padding: 3px 2px;
        }

        table tr td:nth-child(2) {
            text-align: right;
        }
        .page-break {
            page-break-after: always;
        }
        .cont{
            width: 760px;
            height: 250px;
            text-align: left;
            position: absolute;
            top: 8.9%;
            left: 0px;
            font-size: 20px;
        }
        .contan1{
            position: absolute;
            top: 0;
            width: 380px;
        }
        .contan2{
            position: absolute;
            top: 0;
            right: 0;
            width: 300px;
        }
        .cont2{
            position: absolute;
            top: 50;
            right: 60;
        }
        .cont-3{
            width:100%;
            text-align: left;
            position: absolute;
            padding-left: 10px;
            top: 469px;
            left: 47px;
            font-size: 20px;
        }



    </style>
</header>

<body>
    {{$data_count = count($data)}}
@for ($i=0; $i<$data_count; $i++)
{{--@dd($data[1][0]['name'])--}}

<img src="{{ asset('image/forms/RECEIPT_APPLICATION.jpg') }}" alt="">
<div class="cont">
    <div class="contan1">
        <span class="operator_name">{{ $data[$i][0]['full_name'] }}</span>
    </div>
    <div class="contan2">
        <span class="mtfrb_case_no">{{ $data[$i][0]['mtfrb_case_no'] }}</span>
    </div>
    <div class="cont2">
        <span class="body_number">{{ $data[$i][0]['body_number'] }}</span>
    </div>
    
</div>

<div class="cont-3">
    <span class="mtfrb_case_no_recorded">{{ $data[$i][0]['mtfrb_case_no'] }}</span>
</div>


<div class="payment_information">

    <table style="width: 100%">
        <tr>
            <td>OR NO:</td>
            <td>{{ $data[$i][0]['or_number'] }}</td>
        </tr>
        <tr>
            <td>OR DATE:</td>
            <td>{{ date('m/d/Y', strtotime($data[$i][0]['trnx_date'])) }}</td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #000" colspan="2">CHARGES</td>
        </tr>

        {{$totals = 0}}

        @foreach($data[$i][1] as $charge)
            <tr style="font-size: 13px;">
                <td style="font-style: italic; font-weight: normal; padding: 2px 0;">{{ $charge->inc_desc }}</td>

                <td style="font-style: italic; font-weight: normal; padding: 2px 0;">{{ number_format($charge->ln_amnt, 2) }}</td>
                {{ $totals += $charge->ln_amnt }}
            </tr>
        @endforeach

        <tr>
            <td colspan="2" style="border-top: 2px solid #000"></td>
        </tr>

        <tr style="border-top: 2px solid #000">
            <td>TOTAL:</td>
            <td>{{ number_format($totals, 2) }}</td>
        </tr>
    </table>

</div>
@if ($i != $data_count-1)
    <div class="page-break"></div>
@endif

@endfor
</body>

</html>

