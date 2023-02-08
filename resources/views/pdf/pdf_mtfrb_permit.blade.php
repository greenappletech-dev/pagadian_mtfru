<html>
<title>MTFRB Application Form</title>
<header>
    <style>

        body {
            font-family: Arial, sans-serif;
            font-weight: bold;
        }

        @page {
            margin: 0px;
        }

        .page .form {
            width: 816px;
            position: fixed;
        }

        .page .operator_img {
            position: absolute;
            top: 142px;
            right: 17px;
            width: 149px;
            height: 154px;
        }

        .page .operator_name {
            width: 350px;
            text-align: center;
            position: absolute;
            top: 147px;
            left: 20px;
            text-wrap: normal;
            font-size: 20px;
        }

        .mtfrb_case_no {
            width: 195px;
            text-align: center;
            position: absolute;
            top: 148px;
            right: 181px;
            text-wrap: normal;
            font-size: 22px;
        }

        .address {
            width: 350px;
            text-align: left;
            position: absolute;
            top: 208px;
            left: 20px;
            text-wrap: normal;
            font-size: 15px;
        }

        .body_number {
            width: 195px;
            text-align: center;
            position: absolute;
            top: 201px;
            right: 181px;
            text-wrap: normal;
            font-size: 30px;
        }

        .pertain_operator_name {
            width: 510px;
            position: absolute;
            top: 388px;
            right: 23px;
            text-align: center;
            text-wrap: normal;
        }

        .pertain_address {
            width: 681px;
            position: absolute;
            top: 430px;
            right: 20px;
            font-size: 15px;
            text-wrap: normal;
        }

        .make_type {
            width: 178px;
            text-align: center;
            position: absolute;
            top: 672px;
            left: 19px;
            font-size: 18px;
        }

        .engine_motor_no {
            width: 178px;
            text-align: center;
            position: absolute;
            top: 672px;
            left: 218px;
            font-size: 18px;
        }

        .chassis_no {
            width: 178px;
            text-align: center;
            position: absolute;
            top: 672px;
            right: 220px;
            font-size: 18px;
        }

        .plate_no {
            width: 178px;
            text-align: center;
            position: absolute;
            top: 672px;
            right: 21px;
            font-size: 18px;
        }

        .expiration_date {
            width: 350px;
            text-align: center;
            position: absolute;
            bottom: 422px;
            left: 22px;
            font-size: 18px;
        }

        .issue_day {
            width: 90px;
            text-align: center;
            position: absolute;
            bottom: 194px;
            left: 157px;
            font-size: 15px;
        }

        .issue_month {
            width: 144px;
            text-align: center;
            position: absolute;
            bottom: 194px;
            left: 310px;
            font-size: 15px;
        }

        table {
            position: absolute;
            bottom: 105px;
            left: 20px;
            border-collapse: collapse;
        }

        table tr th {
            border: 2px solid #0984e3;
            padding: 5px;
            font-size: 12px;
            color: #0984e3;
        }

        .page {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .page {
            page-break-after: always;
        }

        .chairman {
            position: absolute;
            bottom: 130px;
            right: 90px;
        }

        /*.page:last-child {*/
        /*    page-break-after: unset;*/
        /*}*/



    </style>
</header>
<body>

@for($i = 1; $i <= 4; $i++)

<div class="page">

    <img class="form" src="{{ asset('image/forms/MTOP_PERMIT.jpg') }}" alt="">

    {{--    OPERATORS IMAGE    --}}
    @if($data[2] !== null)

        <img class="operator_img" src="{{ asset('image/operator_image/' . $data[2]['name']) }}" alt="">
    @endif


    <div class="operator_name">{{ $data[0]['full_name'] }}</div>
    <div class="mtfrb_case_no">{{ $data[0]['mtfrb_case_no'] }}</div>
    <div class="address">{{ $data[0]['address'] . ' / ' . $data[0]['mobile']  . ' / ' . $data[5] }}</div>
    <div class="body_number">{{ $data[0]['body_number'] }}</div>

    <div class="pertain_operator_name">{{$data[0]['full_name']}}</div>
    <div class="pertain_address">{{ $data[0]['address']}}</div>

    <div class="make_type">{{ $data[0]['make_type'] }}</div>
    <div class="engine_motor_no">{{ $data[0]['engine_motor_no'] }}</div>
    <div class="chassis_no">{{ $data[0]['chassis_no'] }}</div>
    <div class="plate_no">{{ $data[0]['plate_no'] }}</div>

    <div class="expiration_date">{{ date('F d, Y', strtotime($data[0]['validity_date'])) }}</div>

    {{ $day = date('j', strtotime($data[3] === 'CU' ? $data[0]['validity_date'] : $data[0]['trnx_date'])) }}
    {{ $last_digit = substr($day, -1) }}
    {{ $ordinal = 'th' }}

    @if($day < 21 && $day > 4)
        {{$ordinal = 'th'}}
    @elseif(($last_digit % 100) === 3)
        {{ $ordinal = 'rd' }}
    @elseif(($last_digit % 100) === 2)
        {{ $ordinal = 'nd' }}
    @elseif(($last_digit % 100) === 1)
        {{ $ordinal = 'st' }}
    @elseif(($last_digit % 100) === 0)
        {{ $ordinal = 'th' }}
    @endif

    {{ $year = date('Y', strtotime($data[0]['trnx_date'])) }}

    {{--  GET VALIDITY DATE AND MINUS TO 2  --}}
    @if($data[3] === 'CU')
        {{ $year = \Carbon\Carbon::parse($data[0]['validity_date'])->subYears(2)->format('Y') }}
    @endif


    <div class="issue_day">{{ $day.$ordinal }}</div>
    <div class="issue_month">{{ date('F', strtotime($data[3] === 'CU' ? $data[0]['validity_date'] : $data[0]['trnx_date'])) . ', ' . $year }}</div>


    <table>
        <tr>
            <th style="width: 50px">{{ $data[3] }}</th>
            {{--                <th style="width: 120px">OWNER'S COPY</th>--}}
        </tr>
    </table>

            @if($i == 1)
                <table>
                    <tr>
                        <th style="width: 50px">{{ $data[3] }}</th>
                        <th style="width: 120px">OWNER'S COPY</th>
                    </tr>
                    @if($data[3] === 'CU')
                        <tr>
                            <th style="width: 120px" colspan="2">{{ date('F d, Y', strtotime($data[0]['trnx_date'])) }}</th>
                        </tr>
                    @endif
                </table>
            @elseif($i == 2)
                <table>
                    <tr>
                        <th style="width: 50px">{{ $data[3] }}</th>
                        <th style="width: 120px">MTFRU COPY</th>
                    </tr>
                    @if($data[3] === 'CU')
                        <tr>
                            <th style="width: 120px" colspan="2">{{ date('F d, Y', strtotime($data[0]['trnx_date'])) }}</th>
                        </tr>
                    @endif
                </table>
            @elseif($i == 3)
                <table>
                    <tr>
                        <th style="width: 50px">{{ $data[3] }}</th>
                        <th style="width: 120px">MTFRB COPY</th>
                    </tr>
                    @if($data[3] === 'CU')
                        <tr>
                            <th style="width: 120px" colspan="2">{{ date('F d, Y', strtotime($data[0]['trnx_date'])) }}</th>
                        </tr>
                    @endif
                </table>
            @elseif($i == 4)
                <table>
                    <tr>
                        <th style="width: 50px">{{ $data[3] }}</th>
                        <th style="width: 120px">LTO COPY</th>
                    </tr>
                    @if($data[3] === 'CU')
                        <tr>
                            <th style="width: 120px" colspan="2">{{ date('F d, Y', strtotime($data[0]['trnx_date'])) }}</th>
                        </tr>
                    @endif
                </table>
            @endif

    <div class="chairman">{{ $data[4]->mtfru_chairman }}</div>

</div>


@endfor


</body>
</html>

