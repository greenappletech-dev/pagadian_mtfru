<html>
<title>Supporting Docs</title>
<header>
<style>
    img {
        height: 1055px;
    }

    body {
        font-family: Arial, sans-serif;
        font-weight: bold;
        font-size: 15px;
    }

    .admeasurement .full_name {
        text-align: center;
        width: 307px;
        position: absolute;
        top: 217px;
        left: 25px;
        padding-top: 15px;
        box-sizing: border-box;
    }

    .admeasurement .barangay {
        text-align: center;
        width: 170px;
        position: absolute;
        top: 192px;
        left: 337px;
        padding-top: 15px;
        box-sizing: border-box;
    }

    .admeasurement .transact_date {
        text-align: center;
        width: 170px;
        position: absolute;
        top: 192px;
        right: 58px;
        padding-top: 15px;
        box-sizing: border-box;
    }

    .admeasurement .address {
        width: 346px;
        position: absolute;
        top: 233px;
        right: 38px;
        padding-top: 15px;
        box-sizing: border-box;
        font-size: 12px;
    }

    .admeasurement .boat_type {
        width: 330px;
        position: absolute;
        top: 338px;
        right: 58px;
        box-sizing: border-box;
        text-align: center;
    }

    .admeasurement .boat_desc {
        text-align: center;
        width: 300px;
        position: absolute;
        top: 290px;
        left: 30px;
        padding-top: 15px;
        box-sizing: border-box;
    }

    .admeasurement .body_number {
        text-align: center;
        width: 282px;
        position: absolute;
        top: 340px;
        left: 50px;
        padding-top: 15px;
        box-sizing: border-box;
    }

    .admeasurement .license_number {
        text-align: center;
        width: 282px;
        position: absolute;
        top: 378px;
        left: 50px;
        padding-top: 15px;
        box-sizing: border-box;
    }

    .admeasurement .fishing_gear {
        text-align: center;
        width: 282px;
        position: absolute;
        top: 413px;
        left: 50px;
        padding-top: 15px;
        box-sizing: border-box;
    }

    .admeasurement .manning_crew {
        text-align: center;
        width: 346px;
        position: absolute;
        top: 413px;
        right: 38px;
        padding-top: 15px;
        box-sizing: border-box;
    }

    .admeasurement .length {
        text-align: center;
        width: 100px;
        position: absolute;
        top: 467px;
        left: 56px;
        padding-top: 15px;
        box-sizing: border-box;
    }

    .admeasurement .width {
        text-align: center;
        width: 100px;
        position: absolute;
        top: 467px;
        left: 170px;
        padding-top: 15px;
        box-sizing: border-box;
    }

    .admeasurement .dept {
        text-align: center;
        width: 100px;
        position: absolute;
        top: 467px;
        left: 287px;
        padding-top: 15px;
        box-sizing: border-box;
    }

    .admeasurement .gross_tonnage {
        text-align: center;
        width: 100px;
        position: absolute;
        top: 467px;
        right: 207px;
        padding-top: 15px;
        box-sizing: border-box;
    }

    .admeasurement .net_tonnage {
        text-align: center;
        width: 100px;
        position: absolute;
        top: 467px;
        right: 68px;
        padding-top: 15px;
        box-sizing: border-box;
    }

    .admeasurement table {
        position: absolute;
        width: 85%;
        top: 550px;
        left: 50px;
    }

    .admeasurement table tr th {
        padding: 0;
        font-size: 13px;
        padding-bottom: 2px;
    }

    .cert_numbers .license_number {
        position: absolute;
        top: 183px;
        left: 350px;
        width: 120px;
        text-align: center;
        font-size: 14px;
    }

    .cert_numbers .table-1 {
        position: absolute;
        top: 288px;
        left: 64px;
        width: 589px;
    }

    .cert_numbers .table-1 tr th{
        padding-top: 14px;
        font-size: 12px;
    }

    .cert_numbers .table-2 {
        position: absolute;
        top: 455px;
        left: 64px;
        width: 589px;
        font-size: 13px;
    }

    .cert_numbers .permitted {
        position: absolute;
        top: 504px;
        left: 250px;
        text-align: center;
        font-size: 13px;
    }

    .cert_numbers .table-3 {
        position: absolute;
        top: 560px;
        left: 100px;
        width: 530px;
        font-size: 13px;
    }

    .cert_numbers .table-3 tr th {
        padding-top: 20px;
    }

    .cert_numbers .table-4 {
        position: absolute;
        bottom: 293px;
        left: 64px;
        width: 590px;
        font-size: 13px;
    }

    .cert_numbers .table-4 tr th{
        padding-bottom: 4px;
    }

    .cert_numbers .issue_at {
        position: absolute;
        bottom: 193px;
        left: 168px;
        text-align: center;
        font-size: 13px;
    }

    .cert_numbers .issue_date {
        position: absolute;
        bottom: 193px;
        left: 368px;
        text-align: center;
        font-size: 13px;
    }

    .cert_numbers .issue_month {
        position: absolute;
        bottom: 193px;
        right: 160px;
        text-align: center;
        font-size: 13px;
    }

    .cert_numbers .validity_date {
        position: absolute;
        bottom: 168px;
        right: 217px;
        text-align: center;
        font-size: 13px;
    }

    .cert_numbers .or_number {
        position: absolute;
        bottom: 168px;
        left: 200px;
        text-align: center;
        font-size: 13px;
    }

    .page {
        position: relative;
        width: 100%;
        height: 100%;
    }

    .page {
        page-break-after: always;
    }


    .inspection .full_name {
        text-align: center;
        width: 290px;
        position: absolute;
        top: 278px;
        left: 270px;
        box-sizing: border-box;
        font-size: 15px;
    }

    .inspection .civil_status {
        text-align: center;
        width: 290px;
        position: absolute;
        top: 278px;
        right: -24px;
        box-sizing: border-box;
        font-size: 15px;
    }

    .inspection .age {
        text-align: center;
        width: 50px;
        position: absolute;
        top: 303px;
        left: 124px;
        box-sizing: border-box;
        font-size: 15px;
    }

    .inspection .address {
        width: 370px;
        position: absolute;
        top: 327px;
        left: 75px;
        box-sizing: border-box;
        font-size: 12px;
    }

    .inspection .boat_desc {
        text-align: center;
        width: 280px;
        position: absolute;
        top: 347px;
        left: 136px;
        box-sizing: border-box;
    }

    .inspection .manning_crew {
        text-align: center;
        width: 100px;
        position: absolute;
        top: 347px;
        right: 60px;
        box-sizing: border-box;
    }

    .inspection .length {
        text-align: center;
        width: 70px;
        position: absolute;
        top: 367px;
        left: 205px;
        box-sizing: border-box;
    }

    .inspection .width {
        text-align: center;
        width: 70px;
        position: absolute;
        top: 367px;
        left: 320px;
        box-sizing: border-box;
    }

    .inspection .dept {
        text-align: center;
        width: 50px;
        position: absolute;
        top: 367px;
        left: 455px;
        box-sizing: border-box;
    }

    .inspection .gross_tonnage {
        text-align: center;
        width: 50px;
        position: absolute;
        top: 367px;
        right: 140px;
        box-sizing: border-box;
    }

    .inspection .net_tonnage {
        text-align: center;
        width: 50px;
        position: absolute;
        top: 367px;
        right: 65px;
        box-sizing: border-box;
    }

    .inspection .boat_color {
        text-align: center;
        width: 300px;
        position: absolute;
        top: 390px;
        left: 120px;
        box-sizing: border-box;
    }

    .inspection .boat_type {
        text-align: center;
        width: 150px;
        position: absolute;
        top: 390px;
        right: 80px;
        box-sizing: border-box;
        font-size: 13px;
    }

    .inspection table {
        position: absolute;
        width: 70%;
        top: 410px;
        right: 70px;
        font-size: 13px;
    }

    .inspection table tr th{
        padding-bottom: 5px;
    }

    .inspection .issue_date {
        position: absolute;
        text-align: center;
        width: 90px;
        top: 625px;
        left: 202px;
    }

    .inspection .issue_month {
        position: absolute;
        text-align: center;
        width: 120px;
        top: 625px;
        left: 374px;
    }

    .inspection .issue_year {
        position: absolute;
        text-align: center;
        width: 120px;
        top: 625px;
        right: 110px;
    }

    .inspection .validity_date {
        position: absolute;
        text-align: center;
        width: 198px;
        bottom: 255px;
        left: 185px;
    }

    .permit_to_operate {
        font-size: 13px;
    }

    .permit_to_operate .boat_desc {
        text-align: center;
        width: 194px;
        position: absolute;
        top: 305px;
        left: 70px;
        box-sizing: border-box;
    }

    .permit_to_operate .body_number {
        text-align: center;
        width: 377px;
        position: absolute;
        top: 305px;
        left: 270px;
        box-sizing: border-box;
    }

    .permit_to_operate .boat_type {
        text-align: center;
        width: 182px;
        position: absolute;
        top: 338px;
        left: 268px;
        box-sizing: border-box;
    }

    .permit_to_operate .home_port {
        text-align: center;
        width: 182px;
        position: absolute;
        top: 338px;
        right: 80px;
        box-sizing: border-box;
    }

    .permit_to_operate .birth_date {
        text-align: center;
        width: 194px;
        position: absolute;
        top: 370px;
        left: 70px;
        box-sizing: border-box;
    }

    .permit_to_operate .place_of_birth {
        text-align: center;
        width: 377px;
        position: absolute;
        top: 370px;
        left: 270px;
        box-sizing: border-box;
    }

    .permit_to_operate .owner_address {
        text-align: center;
        width: 700px;
        position: absolute;
        top: 474px;
        left: 0;
        box-sizing: border-box;
    }

    .permit_to_operate .boat_specs {
        position: absolute;
        top: 580px;
        left: 115px;
        width: 68.7%;
    }

    .permit_to_operate .engine {
        position: absolute;
        bottom: 285px;
        left: 74px;
        width: 83%;
        font-size: 10px;
        border-collapse: collapse;
    }

    .permit_to_operate .engine tr th {
        padding-bottom: 3px;
    }

    .permit_to_operate .issue_at {
        position: absolute;
        bottom: 205px;
        left: 130px;
        width: 168px;
        font-size: 12px;
        text-align: center;
    }

    .permit_to_operate .issue_day {
        position: absolute;
        bottom: 205px;
        left: 320px;
        width: 110px;
        font-size: 12px;
        text-align: center;
    }

    .permit_to_operate .issue {
        position: absolute;
        bottom: 205px;
        right: 150px;
        width: 110px;
        font-size: 12px;
        text-align: center;
    }

    .permit_to_operate .or_number {
        position: absolute;
        bottom: 190px;
        left: 199px;
        width: 110px;
        font-size: 12px;
        text-align: center;
    }

    .permit_to_operate .validity_date {
        position: absolute;
        bottom: 190px;
        right: 240px;
        width: 110px;
        font-size: 12px;
        text-align: center;
    }

    .motorboat_operator_license .personal_info {
        position: absolute;
        width: 80%;
        top: 287px;
        left: 88px;
    }

    .motorboat_operator_license .license_number {
        position: absolute;
        width: 165px;
        top: 158px;
        left: 360px;
        text-align: center;
    }

    .motorboat_operator_license .address {
        position: absolute;
        width: 80%;
        top: 348px;
        left: 88px;
    }

    .motorboat_operator_license .birth_info {
        position: absolute;
        width: 80%;
        top: 403px;
        left: 88px;
    }

    .motorboat_operator_license .boat_type {
        position: absolute;
        width: 65%;
        top: 550px;
        left: 130px;
        text-align: center;
        font-size: 13px;
    }

    .motorboat_operator_license .issue_at {
        position: absolute;
        width: 200px;
        bottom: 258px;
        left: 160px;
        text-align: center;
        font-size: 13px;
    }

    .motorboat_operator_license .issue_month {
        position: absolute;
        width: 200px;
        bottom: 258px;
        right: 145px;
        text-align: center;
        font-size: 13px;
    }

    .motorboat_operator_license .validity_date {
        position: absolute;
        width: 200px;
        bottom: 239px;
        left: 160px;
        text-align: center;
        font-size: 13px;
    }

    .motorboat_operator_license .or_number {
        position: absolute;
        width: 200px;
        bottom: 207px;
        left: 202px;
        text-align: left;
        font-size: 13px;
    }

    .boat_captain {
        font-size: 12px;
    }

    .boat_captain .boat_captain_img {
        position: absolute;
        top: 70px;
        left: 356px;
        width: 134px;
        height: 127px;
    }

    .boat_captain .name {
        position: absolute;
        top: 127px;
        left: 48px;
        width: 300px;
        text-align: center;
        font-size: 15px;
    }

    .boat_captain .license_number {
        position: absolute;
        top: 255px;
        left: 234px;
        width: 130px;
        text-align: center;
        font-size: 13px;
    }

    .boat_captain .issue_date {
        position: absolute;
        top: 397px;
        left: 165px;
        width: 70px;
        text-align: center;
    }

    .boat_captain .issue_month {
        position: absolute;
        top: 397px;
        left: 234px;
        width: 180px;
        text-align: center;
    }

    .boat_captain .issue_year {
        position: absolute;
        top: 397px;
        left: 320px;
        width: 180px;
        text-align: center;
    }

    .boat_captain .validity_date {
        position: absolute;
        top: 415px;
        left: 270px;
        width: 180px;
        text-align: center;
    }

    .boat_captain .or_number {
        position: absolute;
        top: 620px;
        left: 120px;
        width: 180px;
        text-align: left;
    }

    .fishing_boat_license {
        font-size: 13px;
    }

    .fishing_boat_license .license_number {
        position: absolute;
        top: 97px;
        left: 93px;
        font-size: 13px;
        text-align: left;
        width: 130px;
    }

    .fishing_boat_license .transact_type {
        position: absolute;
        top: 97px;
        left: 300px;
        width: 200px;
        font-size: 10px;
        text-align: right;
        text-wrap: normal;
    }

    .fishing_boat_license .name {
        position: absolute;
        top: 123px;
        left: 95px;
        width: 300px;
        text-align: center;
        font-size: 13px;
    }

    .fishing_boat_license .boat_name {
        position: absolute;
        top: 162px;
        left: 95px;
        width: 300px;
        text-align: center;
        font-size: 12px;
    }

    .fishing_boat_license .address {
        position: absolute;
        top: 195px;
        left: 50px;
        width: 400px;
        text-align: center;
        font-size: 12px;
    }

    .fishing_boat_license .fishing_gear {
        position: absolute;
        top: 250px;
        left: 50px;
        width: 400px;
        text-align: center;
        font-size: 12px;
    }

    .fishing_boat_license .gross_tonnage {
        position: absolute;
        top: 295px;
        left: 129px;
        width: 60px;
        text-align: center;
        font-size: 12px;
    }

    .fishing_boat_license .net_tonnage {
        position: absolute;
        top: 295px;
        left: 318px;
        width: 60px;
        text-align: center;
        font-size: 12px;
    }

    .fishing_boat_license .issue_date {
        position: absolute;
        top: 490px;
        left: 65px;
        width: 70px;
        text-align: center;
    }

    .fishing_boat_license .issue_month {
        position: absolute;
        top: 490px;
        left: 140px;
        width: 180px;
        text-align: center;
    }

    .fishing_boat_license .or_date {
        position: absolute;
        top: 535px;
        left: 300px;
        width: 180px;
        text-align: center;
    }

    .fishing_boat_license .validity_date {
        position: absolute;
        top: 551px;
        left: 300px;
        width: 180px;
        text-align: center;
    }

    .fishing_boat_license .or_number {
        position: absolute;
        top: 650px;
        left: 102px;
        width: 180px;
        text-align: left;
    }

    .seaweed_farms {
        font-size: 12px;
    }

    .seaweed_farms .operator_img {
        position: absolute;
        top: 128px;
        left: 438px;
        width: 65px;
        height: 66px;
        text-align: center;
    }

    .seaweed_farms .license_number {
        position: absolute;
        top: 205px;
        left: 42px;
        width: 114px;
        text-align: center;
    }

    .seaweed_farms .name {
        position: absolute;
        top: 225px;
        left: 301px;
        width: 210px;
        text-align: center;
    }

    .seaweed_farms .address {
        position: absolute;
        top: 245px;
        left: -20px;
        width: 400px;
        text-align: center;
        font-size: 10px;
    }

    .seaweed_farms .fishing_gear {
        position: absolute;
        top: 263px;
        left: 10px;
        width: 400px;
        text-align: center;
        font-size: 10px;
    }

    .seaweed_farms .issue_date {
        position: absolute;
        top: 402px;
        left: 17px;
        width: 70px;
        text-align: center;
    }

    .seaweed_farms .issue_month {
        position: absolute;
        top: 402px;
        left: 53px;
        width: 180px;
        text-align: center;
    }

    .seaweed_farms .issue_year {
        position: absolute;
        top: 402px;
        left: 126px;
        width: 180px;
        text-align: center;
    }

    .seaweed_farms .validity_date {
        position: absolute;
        top: 440px;
        left: 30px;
        width: 180px;
        text-align: center;
    }

    .seaweed_farms .or_number {
        position: absolute;
        top: 687px;
        left: 75px;
        width: 180px;
        text-align: left;
    }


</style>
</header>
<body>


@if($data[0]['with_engine'] !== false)

    <div class="page">
        <div class="admeasurement">
            <img style="width: 815px; position: absolute; top: -45px; left: -45px;" src="{{ asset('image/forms/ADMEASUREMENT.jpg') }}" alt="">

            <div class="full_name">{{ $data[0]['full_name'] }}</div>
            <div class="barangay">PAGADIAN CITY</div>
            <div class="transact_date">{{ $data[0]['transact_date'] }}</div>
            <div class="address">{{ $data[0]['address'] }}</div>
            <div class="boat_desc">{{ $data[0]['name'] . ' / ' . $data[0]['color']}}</div>

            <div class="body_number">{{ $data[0]['body_number'] }}</div>
            <div class="license_number">{{ $data[2] }}</div>
            <div class="boat_type">{{ $data[0]['boat_type'] }}</div>

            <div class="fishing_gear">{{ $data[0]['fishing_gear'] }}</div>
            <div class="manning_crew">{{ $data[0]['manning_crew'] }}</div>

            <div class="length">{{ $data[0]['length'] }}</div>
            <div class="width">{{ $data[0]['width'] }}</div>
            <div class="dept">{{ $data[0]['dept'] }}</div>
            <div class="gross_tonnage">{{ $data[0]['gross_tonnage'] }}</div>
            <div class="net_tonnage">{{ $data[0]['net_tonnage'] }}</div>


            <table>
                <tr>
                    <th width="35.7%">{{ $data[0]['make_type'] }}</th>
                    <th width="29%">{{ $data[0]['engine_motor_no'] }}</th>
                    <th width="16%">{{ $data[0]['horsepower'] }}</th>
                    <th>{{ $data[0]['cylinder'] }}</th>
                </tr>

                @foreach($data[1] as $auxiliary)

                    <tr>
                        <th>{{ $auxiliary['make_type'] }}</th>
                        <th>{{ $auxiliary['engine_motor_no'] }}</th>
                        <th>{{ $auxiliary['horsepower'] }}</th>
                        <th>{{ $auxiliary['cylinder'] }}</th>
                    </tr>

                @endforeach

            </table>
        </div>
    </div>


    <div class="page">
        <div class="cert_numbers">
            <img style="width: 815px; position: absolute; top: -45px; left: -45px;" src="{{ asset('image/forms/CERTIFICATE OF NUMBER.jpg') }}" alt="">

            <div class="license_number">{{ $data[2] }}</div>

            <table class="table-1">
                <tr>
                    <th style="width: 34.3%">{{ $data[0]['name'] }}</th>
                    <th colspan="2">{{ $data[0]['body_number'] }}</th>
                </tr>

                <tr>
                    <th>PROTECTED AREA</th>
                    <th>{{ $data[0]['boat_type'] }}</th>
                    <th>PAGADIAN CITY</th>
                </tr>

                <tr>
                    <th>{{ date('F j, Y', strtotime($data[0]['birth_date'])) }}</th>
                    <th colspan="2">PAGADIAN CITY</th>
                </tr>
            </table>

            <table class="table-2">
                <tr>
                    <th style="width: 50%">{{ $data[0]['full_name'] }}</th>
                    <th style="width: 50%">{{ $data[0]['address'] }}</th>
                </tr>
            </table>

            <div class="permitted">PROTECTED AREA</div>

            <table class="table-3">
                <tr>
                    <th style="width: 28%">{{ $data[0]['length'] }}</th>
                    <th style="width: 21.5%">{{ $data[0]['width'] }}</th>
                    <th style="width: 20.3%">{{ $data[0]['dept'] }}</th>
                    <th style="width: 16%">{{ $data[0]['gross_tonnage'] }}</th>
                    <th>{{ $data[0]['net_tonnage'] }}</th>
                </tr>
            </table>

            <table class="table-4">
                <tr>
                    <th style="width: 37.2%">{{ $data[0]['make_type'] }}</th>
                    <th style="width: 25.2%">{{ $data[0]['engine_motor_no'] }}</th>
                    <th style="width: 24.5%">{{ $data[0]['horsepower'] }}</th>
                    <th style="width: 24.5%">{{ $data[0]['cylinder'] }}</th>
                </tr>

                @foreach($data[1] as $auxiliary)

                <tr>
                    <th>{{ $auxiliary['make_type'] }}</th>
                    <th>{{ $auxiliary['engine_motor_no'] }}</th>
                    <th>{{ $auxiliary['horsepower'] }}</th>
                    <th>{{ $auxiliary['cylinder'] }}</th>
                </tr>

                @endforeach

            </table>

            {{ $day = date('j', strtotime($data[0]['or_date'])) }}
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

            <div class="issue_at">PAGADIAN CITY</div>

            <div class="issue_date">{{ $day.$ordinal }}</div>

            <div class="issue_month">{{ date('F, Y', strtotime($data[0]['or_date'])) }}</div>

            <div class="validity_date">{{ date('F d, Y', strtotime($data[0]['validity_date'])) }}</div>

            <div class="or_number">{{ $data[0]['or_number'] }} <br> {{ $data[0]['or_number_2'] }}</div>

        </div>
    </div>



    <div class="page">
        <div class="inspection">
            <img style="width: 815px; position: absolute; top: -45px; left: -45px;" src="{{ asset('image/forms/INSPECTION.jpg') }}" alt="">

            <div class="full_name">{{ $data[0]['full_name'] }}</div>

            @if($data[0]['civ_stat'] === 'SNL')
                <div class="civil_status">SINGLE</div>
            @else
                <div class="civil_status">MARRIED</div>
            @endif

            <div class="age">{{ $data[7] }}</div>


            <div class="barangay">{{ $data[0]['brgy_desc'] }}</div>
            <div class="transact_date">{{ $data[0]['transact_date'] }}</div>
            <div class="address">{{ $data[0]['address'] }}</div>
            <div class="boat_desc">{{ $data[0]['name'] }}</div>

            <div class="body_number">{{ $data[0]['body_number'] }}</div>
            <div class="license_number">{{ $data[2] }}</div>
            <div class="boat_type">{{ $data[0]['boat_type'] }}</div>

            <div class="fishing_gear">{{ $data[0]['fishing_gear'] }}</div>
            <div class="manning_crew">{{ $data[0]['manning_crew'] }}</div>

            <div class="length">{{ $data[0]['length'] }}</div>
            <div class="width">{{ $data[0]['width'] }}</div>
            <div class="dept">{{ $data[0]['dept'] }}</div>
            <div class="gross_tonnage">{{ $data[0]['gross_tonnage'] }}</div>
            <div class="net_tonnage">{{ $data[0]['net_tonnage'] }}</div>

            <div class="boat_color">{{$data[0]['color']}}</div>


            <table>
                <tr>
                    <th width="13%">{{ $data[0]['horsepower'] }}</th>
                    <th width="25%">{{ $data[0]['engine_motor_no'] }}</th>
                    <th width="35.7%">{{ $data[0]['make_type'] }}</th>
                </tr>

                @foreach($data[1] as $auxiliary)

                    <tr>
                        <th>{{ $auxiliary['horsepower'] }}</th>
                        <th>{{ $auxiliary['engine_motor_no'] }}</th>
                        <th>{{ $auxiliary['make_type'] }}</th>
                    </tr>

                @endforeach

            </table>
    {{ $day = date('j', strtotime($data[0]['or_date'])) }}
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

            <div class="issue_date">{{ $day.$ordinal }}</div>

            <div class="issue_month">{{ date('F', strtotime($data[0]['or_date'])) }}</div>

            <div class="issue_year">{{ date('y', strtotime($data[0]['or_date'])) }}</div>

            <div class="validity_date">{{ date('F d, Y', strtotime($data[0]['validity_date'])) }}</div>

        </div>
    </div>


    <div class="page">
        <div class="motorboat_operator_license">
            <img style="width: 815px; position: absolute; top: -45px; left: -45px;" src="{{ asset('image/forms/MOTORBOAT_OPERATOR_LICENSE.jpg') }}" alt="">

            <div class="license_number">{{ $data[2] }}</div>

            <table class="personal_info">
                <tr>
                    <th style="width: 20%">{{ $data[0]['last_name'] }}</th>
                    <th style="width: 20%">{{ $data[0]['first_name'] }}</th>
                    <th style="width: 20%">{{ $data[0]['mid_name'] }}</th>
                </tr>
            </table>

            <div class="address">{{ $data[0]['address'] }}</div>

            <table class="birth_info">
                <tr>
                    <th style="width: 40.6%">{{ date('F d, Y', strtotime($data[0]['birth_date']))  }}</th>
                    <th style="width: 29.5%">PAGADIAN CITY</th>
                    <th>{{ date('F d, Y', strtotime($data[0]['or_date'])) }}</th>
                </tr>
            </table>

            <div class="boat_type">{{ $data[0]['boat_type'] }}</div>

            {{ $day = date('j', strtotime($data[0]['or_date'])) }}
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

            <div class="issue_at">PAGADIAN CITY</div>


            <div class="issue_month">{{ date('F Y', strtotime($data[0]['or_date'])) }}</div>


            <div class="validity_date">{{ date('F d, Y', strtotime($data[0]['validity_date'])) }}</div>

            <div class="or_number">{{ $data[0]['or_number'] }} <br> {{ $data[0]['or_number_2'] }}</div>

        </div>
    </div>



    <div class="page">
        <div class="boat_captain">
            <img style="width: 580px; height: 750px; position: absolute; top: -45px; left: -45px;" src="{{ asset('image/forms/BOAT_CAPTAIN.jpg') }}" alt="">

            <div class="name">{{ $data[4]['full_name'] }}</div>

            @if($data[4]['image_location'] !== null)

                <img class="boat_captain_img" src="{{ asset('image/captain_image/' . $data[4]['image_location']) }}">

            @endif

            <div class="license_number">{{ $data[2] }}</div>



            {{ $day = date('j', strtotime($data[0]['or_date'])) }}
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

            <div class="name">{{ $data[4]['full_name'] }}</div>
            <div class="issue_date">{{ $day.$ordinal }}</div>

            <div class="issue_month">{{ date('F' , strtotime($data[0]['or_date'])) }}</div>
            <div class="issue_year">{{ date('y' , strtotime($data[0]['or_date'])) }}</div>
            <div class="validity_date">{{ date('F d Y' , strtotime($data[0]['validity_date'])) }}</div>

            <div class="or_number">{{ $data[0]['or_number'] }} <br> {{ $data[0]['or_number_2'] }}</div>

        </div>
    </div>
@endif

<div class="page">
    <div class="fishing_boat_license">
        <img style="width: 590px; height: 800px; position: absolute; top: -45px; left: -45px;" src="{{ asset('image/forms/FISHING_BOAT_LICENSE.jpg') }}" alt="">

        {{ $day = date('j', strtotime($data[0]['or_date'])) }}
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


        <div class="license_number">{{ $data[2] }}</div>

        <div class="transact_type">{{ $data[3] }}</div>

        <div class="name">{{ $data[0]['full_name'] }}</div>

        <div class="boat_name">{{ $data[0]['name'] }}</div>

        <div class="address">{{ $data[0]['address'] }}</div>

        <div class="fishing_gear">{{ $data[0]['fishing_gear'] }}</div>

        <div class="gross_tonnage">{{ $data[0]['gross_tonnage'] }}</div>

        <div class="net_tonnage">{{ $data[0]['net_tonnage'] }}</div>

        <div class="issue_date">{{ $day.$ordinal }}</div>

        <div class="issue_month">{{ date('F Y' , strtotime($data[0]['or_date'])) }}</div>

        <div class="or_date">{{ date('F d Y' , strtotime($data[0]['or_date'])) }}</div>

        <div class="validity_date">{{ date('F d Y' , strtotime($data[0]['validity_date'])) }}</div>

        <div class="or_number">{{ $data[0]['or_number'] }} <br> {{ $data[0]['or_number_2'] }}</div>

    </div>
</div>



<div class="page">
    <div class="seaweed_farms">
        <img style="width: 590px; height: 800px; position: absolute; top: -45px; left: -45px;" src="{{ asset('image/forms/SEAWEED_FARMS.jpg') }}" alt="">

        {{ $day = date('j', strtotime($data[0]['or_date'])) }}
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


        @if($data[5] !== null)

            <img class="operator_img" src="{{ asset('image/operator_image/' . $data[5]['name']) }}" alt="">

        @endif



        <div class="license_number">{{ $data[2] }}</div>

        <div class="name">{{ $data[0]['full_name'] }}</div>

        <div class="boat_name">{{ $data[0]['name'] }}</div>

        <div class="address">{{ $data[0]['address'] }}</div>

        <div class="fishing_gear">NET{{ $data[0]['fishing_gear'] }}</div>


        <div class="issue_date">{{ $day.$ordinal }}</div>

        <div class="issue_month">{{ date('F' , strtotime($data[0]['or_date'])) }}</div>

        <div class="issue_year">{{ date('y' , strtotime($data[0]['or_date'])) }}</div>

        <div class="or_date">{{ date('F d Y' , strtotime($data[0]['or_date'])) }}</div>

        <div class="validity_date">{{ date('F d Y' , strtotime($data[0]['validity_date'])) }}</div>

        <div class="or_number">{{ $data[0]['or_number']  }} </br> {{ $data[0]['or_number_2'] }}</div>

    </div>
</div>


</body>
</html>

