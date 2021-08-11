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
            width: 350px;
            text-align: center;
            position: absolute;
            top: 118px;
            left: -20px;
        }

        .mtfrb_case_no {
            width: 190px;
            text-align: center;
            position: absolute;
            top: 118px;
            right: -20px;
        }

        .body_number {
            width: 190px;
            text-align: center;
            position: absolute;
            top: 180px;
            right: -20px;
        }

        .mtfrb_case_no_recorded {
            width: 190px;
            text-align: center;
            position: absolute;
            top: 472px;
            left: 47px;
        }

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


    </style>
</header>
<body>

{{--@dd($data[1][0]['name'])--}}

<img src="{{ asset('image/forms/RECEIPT_APPLICATION.jpg') }}" alt="">

<span class="operator_name">{{ $data[0]['full_name'] }}</span>
<span class="mtfrb_case_no">{{ $data[0]['mtfrb_case_no'] }}</span>
<span class="body_number">{{ $data[0]['body_number'] }}</span>

<span class="mtfrb_case_no_recorded">{{ $data[0]['mtfrb_case_no'] }}</span>

<div class="payment_information">

    <table style="width: 100%">
        <tr>
            <td>OR NO:</td>
            <td>{{ $data[0]['or_number'] }}</td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid #000" colspan="2">CHARGES</td>
        </tr>

        {{$totals = 0}}

        @foreach($data[1] as $charges)
            <tr style="font-size: 13px;">
                <td style="font-style: italic; font-weight: normal; padding: 5px 0;">{{ $charges['name'] }}</td>
                <td style="font-style: italic; font-weight: normal; padding: 5px 0;">{{ number_format($charges['price'], 2) }}</td>
                {{ $totals +=  $charges['price'] }}
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

</body>
</html>

