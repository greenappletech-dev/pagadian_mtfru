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
            top: 215px;
            left: 25px;
            padding-top: 15px;
            box-sizing: border-box;
        }

        .admeasurement .barangay {
            text-align: center;
            width: 170px;
            position: absolute;
            top: 195px;
            left: 337px;
            padding-top: 15px;
            box-sizing: border-box;
        }

        .admeasurement .transact_date {
            text-align: center;
            width: 170px;
            position: absolute;
            top: 195px;
            right: 38px;
            padding-top: 15px;
            box-sizing: border-box;
        }

        .admeasurement .address {
            width: 346px;
            position: absolute;
            top: 300px;
            right: 38px;
            padding-top: 15px;
            box-sizing: border-box;
        }

        .admeasurement .boat_desc {
            text-align: center;
            width: 300px;
            position: absolute;
            top: 302px;
            left: 30px;
            padding-top: 15px;
            box-sizing: border-box;
        }

        .admeasurement .body_number {
            text-align: center;
            width: 300px;
            position: absolute;
            top: 357px;
            left: 30px;
            padding-top: 15px;
            box-sizing: border-box;
        }

        .admeasurement .license_number {
            text-align: center;
            width: 300px;
            position: absolute;
            top: 400px;
            left: 30px;
            padding-top: 15px;
            box-sizing: border-box;
        }

        .admeasurement .fishing_gear {
            text-align: center;
            width: 300px;
            position: absolute;
            top: 437px;
            left: 30px;
            padding-top: 15px;
            box-sizing: border-box;
        }

        .admeasurement .manning_crew {
            text-align: center;
            width: 346px;
            position: absolute;
            top: 437px;
            right: 38px;
            padding-top: 15px;
            box-sizing: border-box;
        }

        .admeasurement .length {
            text-align: center;
            width: 120px;
            position: absolute;
            top: 498px;
            left: 24px;
            padding-top: 15px;
            box-sizing: border-box;
        }

        .admeasurement .width {
            text-align: center;
            width: 120px;
            position: absolute;
            top: 498px;
            left: 151px;
            padding-top: 15px;
            box-sizing: border-box;
        }

        .admeasurement .dept {
            text-align: center;
            width: 120px;
            position: absolute;
            top: 498px;
            left: 277px;
            padding-top: 15px;
            box-sizing: border-box;
        }

        .admeasurement .gross_tonnage {
            text-align: center;
            width: 120px;
            position: absolute;
            top: 498px;
            right: 180px;
            padding-top: 15px;
            box-sizing: border-box;
        }

        .admeasurement .net_tonnage {
            text-align: center;
            width: 120px;
            position: absolute;
            top: 498px;
            right: 40px;
            padding-top: 15px;
            box-sizing: border-box;
        }

        .admeasurement table {
            position: absolute;
            width: 92%;
            top: 593px;
            left: 24px;
        }

        .admeasurement table tr th {
            padding: 0;
            font-size: 13px;
            padding-bottom: 2px;
        }

    </style>
</header>
<body>

<div class="page">
    <div class="admeasurement">
        <img style="width: 815px; position: absolute; top: -45px; left: -45px;" src="{{ asset('image/forms/INSPECTION.jpg') }}" alt="">

        <span class="full_name">{{ $data[0]['full_name'] }}</span>
        <span class="barangay">{{ $data[0]['brgy_desc'] }}</span>
        <span class="transact_date">{{ $data[0]['transact_date'] }}</span>
        <span class="address">{{ $data[0]['address'] }}</span>
        <span class="boat_desc">{{ $data[0]['name'] . ' / ' . $data[0]['color']}}</span>

        <span class="body_number">{{ $data[0]['body_number'] }}</span>
        <span class="license_number">{{ $data[2] }}</span>
        <span class="boat_type">{{ $data[0]['boat_type'] }}</span>

        <span class="fishing_gear">{{ $data[0]['fishing_gear'] }}</span>
        <span class="manning_crew">{{ $data[0]['manning_crew'] }}</span>

        <span class="length">{{ $data[0]['length'] }}</span>
        <span class="width">{{ $data[0]['width'] }}</span>
        <span class="dept">{{ $data[0]['dept'] }}</span>
        <span class="gross_tonnage">{{ $data[0]['gross_tonnage'] }}</span>
        <span class="net_tonnage">{{ $data[0]['net_tonnage'] }}</span>


        <table>
            <tr>
                <th width="35.7%">{{ $data[0]['make_type'] }}</th>
                <th width="29%">{{ $data[0]['engine_motor_no'] }}</th>
                <th width="16%">{{ $data[0]['horsepower'] }}</th>
                <th>{{ $data[0]['cylinder'] }}</th>
            </tr>

            @if(count($data[1]) !== 0)

                @foreach($data[1] as $auxiliary)

                    <tr>
                        <th>{{ $auxiliary['make_type'] }}</th>
                        <th>{{ $auxiliary['engine_motor_no'] }}</th>
                        <th>{{ $auxiliary['horsepower'] }}</th>
                        <th>{{ $auxiliary['cylinder'] }}</th>
                    </tr>

                @endforeach

            @endif

        </table>
    </div>
</div>
</body>
</html>

