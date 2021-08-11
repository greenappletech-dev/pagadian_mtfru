<html>
<title>MTFRB Application Form</title>
<header>
    <style>

        body {
            font-family: Arial, sans-serif;
            font-weight: bold;
        }

        .form {
            width: 815px;
            position: absolute;
            top: -45px;
            left: -45px;
        }

        .operator_img {
            position: absolute;
            top: 95px;
            right: -28px;
            width: 148px;
            height: 154px;
        }

        .operator_name {
            width: 350px;
            text-align: center;
            position: absolute;
            top: 105px;
            left: -23px;
            text-wrap: normal;
        }

        .mtfrb_case_no {
            width: 195px;
            text-align: center;
            position: absolute;
            top: 105px;
            left: 394px;
            text-wrap: normal;
        }

        .address {
            width: 350px;
            text-align: left;
            position: absolute;
            top: 160px;
            left: -23px;
            text-wrap: normal;
            font-size: 15px;
        }

        .body_number {
            width: 195px;
            text-align: center;
            position: absolute;
            top: 164px;
            left: 394px;
            text-wrap: normal;
        }

        .pertain_operator_name {
            width: 510px;
            position: absolute;
            top: 343px;
            left: 240px;
            text-align: center;
            text-wrap: normal;
        }

        .pertain_address {
            width: 681px;
            position: absolute;
            top: 380px;
            left: 70px;
            font-size: 13px;
            text-wrap: normal;
        }

        .make_type {
            width: 178px;
            text-align: center;
            position: absolute;
            top: 630px;
            left: -26px;
            font-size: 12px;
        }

        .engine_motor_no {
            width: 178px;
            text-align: center;
            position: absolute;
            top: 630px;
            left: 173px;
            font-size: 12px;
        }

        .chassis_no {
            width: 178px;
            text-align: center;
            position: absolute;
            top: 630px;
            right: 175px;
            font-size: 12px;
        }

        .plate_no {
            width: 178px;
            text-align: center;
            position: absolute;
            top: 630px;
            right: -23px;
            font-size: 12px;
        }

        .expiration_date {
            width: 350px;
            text-align: center;
            position: absolute;
            top: 856px;
            left: -23px;
            font-size: 15px;
        }

        .issue_day {
            width: 350px;
            text-align: center;
            position: absolute;
            bottom: 150px;
            left: -20px;
            font-size: 15px;
        }

        .issue_month {
            width: 144px;
            text-align: center;
            position: absolute;
            bottom: 150px;
            left: 265px;
            font-size: 15px;
        }



    </style>
</header>
<body>
<img class="form" src="{{ asset('image/forms/MTOP_PERMIT.jpg') }}" alt="">

{{--         OPERATORS IMAGE          --}}

<img class="operator_img" src="{{ asset('image/operator_image/' . $data[2]['name']) }}" alt="">

<span class="operator_name">{{ $data[0]['full_name'] }}</span>
<span class="mtfrb_case_no">{{ $data[0]['mtfrb_case_no'] }}</span>
<span class="address">{{ $data[0]['address'] . ' ' . $data[0]['brgy_code'] . '-' . $data[0]['brgy_desc']  }}</span>
<span class="body_number">{{ $data[0]['body_number'] }}</span>

<span class="pertain_operator_name">{{$data[0]['full_name']}}</span>
<span class="pertain_address">{{ $data[0]['address'] . ' ' . $data[0]['brgy_code'] . '-' . $data[0]['brgy_desc']  }}</span>

<span class="make_type">{{ $data[0]['make_type'] }}</span>
<span class="engine_motor_no">{{ $data[0]['engine_motor_no'] }}</span>
<span class="chassis_no">{{ $data[0]['chassis_no'] }}</span>
<span class="plate_no">{{ $data[0]['plate_no'] }}</span>

<span class="expiration_date">{{ date('m/d/Y', strtotime($data[0]['validity_date'])) }}</span>

{{ $day = date('d', strtotime($data[3])) }}
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

<span class="issue_day">{{ $day.$ordinal }}</span>
<span class="issue_month">{{ date('F', strtotime($data[3])) . ', ' . date('Y', strtotime($data[3])) }}</span>


</body>
</html>

