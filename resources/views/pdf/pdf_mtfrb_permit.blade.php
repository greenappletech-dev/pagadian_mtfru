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
            top: 151px;
            left: 20px;
            text-wrap: normal;
        }

        .mtfrb_case_no {
            width: 195px;
            text-align: center;
            position: absolute;
            top: 151px;
            right: 181px;
            text-wrap: normal;
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
            top: 208px;
            right: 181px;
            text-wrap: normal;
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
            top: 431px;
            right: 20px;
            font-size: 13px;
            text-wrap: normal;
        }

        .make_type {
            width: 178px;
            text-align: center;
            position: absolute;
            top: 676px;
            left: 19px;
            font-size: 12px;
        }

        .engine_motor_no {
            width: 178px;
            text-align: center;
            position: absolute;
            top: 676px;
            left: 218px;
            font-size: 12px;
        }

        .chassis_no {
            width: 178px;
            text-align: center;
            position: absolute;
            top: 676px;
            right: 220px;
            font-size: 12px;
        }

        .plate_no {
            width: 178px;
            text-align: center;
            position: absolute;
            top: 676px;
            right: 21px;
            font-size: 12px;
        }

        .expiration_date {
            width: 350px;
            text-align: center;
            position: absolute;
            bottom: 424px;
            left: 22px;
            font-size: 15px;
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

        .copy_for {
            position: absolute;
            bottom: 0%;
            left: 44%;
            transform: translate(-50%, -50%);
            text-align: center;
            font-weight: bold;
            font-style: italic;
            padding: 5px 50px;
            border: 2px solid #636e72;
            font-size: 12px;
            color: #636e72;
        }

        .page {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .page {
            page-break-after: always;
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

        <img class="operator_img" src="{{ asset('image/operator_image/' . $data[2]['name']) }}" alt="">

        <div class="operator_name">{{ $data[0]['full_name'] }}</div>
        <div class="mtfrb_case_no">{{ $data[0]['mtfrb_case_no'] }}</div>
        <div class="address">{{ $data[0]['address'] . ' ' . $data[0]['brgy_code'] . '-' . $data[0]['brgy_desc']  }}</div>
        <div class="body_number">{{ $data[0]['body_number'] }}</div>

        <div class="pertain_operator_name">{{$data[0]['full_name']}}</div>
        <div class="pertain_address">{{ $data[0]['address'] . ' ' . $data[0]['brgy_code'] . '-' . $data[0]['brgy_desc']  }}</div>

        <div class="make_type">{{ $data[0]['make_type'] }}</div>
        <div class="engine_motor_no">{{ $data[0]['engine_motor_no'] }}</div>
        <div class="chassis_no">{{ $data[0]['chassis_no'] }}</div>
        <div class="plate_no">{{ $data[0]['plate_no'] }}</div>

        <div class="expiration_date">{{ date('m/d/Y', strtotime($data[0]['validity_date'])) }}</div>

        {{ $day = date('j', strtotime($data[3])) }}
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

        <div class="issue_day">{{ $day.$ordinal }}</div>
        <div class="issue_month">{{ date('F', strtotime($data[3])) . ', ' . date('Y', strtotime($data[3])) }}</div>

        @if($i == 1)
            <div class="copy_for">OWNER'S COPY</div>
        @elseif($i == 2)
            <div class="copy_for">MTFRU COPY</div>
        @elseif($i == 3)
            <div class="copy_for">MTFRB COPY</div>
        @elseif($i == 4)
            <div class="copy_for">LTO COPY</div>
        @endif

    </div>
@endfor


</body>
</html>

