<html>
<title>FVR MBOL Form</title>
<header>
    <style>
        img {
            height: 1055px;
        }

        body {
            font-family: Arial, sans-serif;
            font-weight: bold;
        }

        .transaction_type {
            text-align: center;
            width: 200px;
            position: absolute;
            top: 180px;
            right: 10px;
            font-size: 12px;
        }

        .table-1 {
            position: absolute;
            width: 63%;
            top: 214px;
            left: 260px;
            font-size: 12px;
            border-collapse: collapse;
        }

        .table-1 tr th {
            text-align: left;
        }


        .table-a {
            position: absolute;
            top: 250px;
            left: 255px;
            width: 25%;
            border-collapse: collapse;
        }

        .table-a tr th {
            text-align: center;
            font-size: 13px;
            padding-bottom: 2px;
        }

        .table-a-2 {
            position: absolute;
            top: 439px;
            left: 257px;
            border-collapse: collapse;
        }

        .table-a-2 tr th {
            text-align: center;
            font-size: 13px;
        }

        .table-a-3 {
            position: absolute;
            top: 514px;
            left: 255px;
            width: 25%;
            border-collapse: collapse;
        }

        .table-a-3 tr th {
            text-align: center;
            font-size: 13px;
            padding-bottom: 2px;
        }

        .body_number {
            position: absolute;
            top: 570px;
            left: 263px;
            width: 300px;
            font-size: 15px;
        }

        .table-b {
            position: absolute;
            top: 611px;
            left: 263px;
            border-collapse: collapse;
        }

        .table-b tr th {
            text-align: left;
            font-size: 13px;
            padding-bottom: 2px;
        }

        .license_number {
            position: absolute;
            bottom: 242px;
            left: 263px;
        }

        .license_number_slide {
            text-align: center;
            width: 300px;
            position: absolute;
            bottom: 127px;
            left: 87px;
            transform: rotate(-22deg);
        }



    </style>
</header>
<body>

<img style="width: 815px; position: absolute; top: -45px; left: -45px;" src="{{ asset('image/forms/MBOL FORM.jpg') }}" alt="">

<span class="transaction_type">{{ $data[3] }}</span>

<table class="table-1">
    <tr>
        <th>{{ $data[0]['full_name'] }}</th>
    </tr>

    <tr>
        <th>{{ $data[0]['name'] . ' / ' . $data[0]['color'] }}</th>
    </tr>
</table>

<table class="table-a">
    <tr>
        <th>{{ $data[0]['length'] }}</th>
    </tr>

    <tr>
        <th>{{ $data[0]['width'] }}</th>
    </tr>

    <tr>
        <th>{{ $data[0]['dept'] }}</th>
    </tr>

    <tr>
        <th>{{ $data[0]['gross_tonnage'] }}</th>
    </tr>

    <tr>
        <th>{{ $data[0]['net_tonnage'] }}</th>
    </tr>

    <tr>
        <th>
            @if(!empty($data[0]['make_type']))
                {{ $data[0]['make_type'] }}
            @else
                NA
            @endif
        </th>
    </tr>

    <tr>
        <th>
            @if(!empty($data[0]['horsepower']))
                - {{ $data[0]['horsepower'] }} -
            @else
                NA
            @endif
        </th>
    </tr>

    <tr>
        <th>
            @if(!empty($data[0]['engine_motor_no']))
                {{ $data[0]['engine_motor_no'] }}
            @else
                NA
            @endif
        </th>
    </tr>

    <tr>
        <th>
            @if(!empty($data[0]['make_type']))
                - {{ $data[0]['cylinder'] }} -
            @else
                NA
            @endif
        </th>
    </tr>

    <tr>
        <th>{{ count($data[1]) }}</th>
    </tr>
</table>

@if(count($data[1]) !== 0 )

{{ $counter = 1 }}

<table class="table-a-2">
    <tr>
        @foreach($data[1] as $auxiliary)

            <th>
                {{$auxiliary['make_type'] }}
            </th>

            {{$counter = $counter + 1}}

        @endforeach
    </tr>

    {{$counter = 1}}
    <tr>
        @foreach($data[1] as $auxiliary)

            <th>
                - {{ $auxiliary['horsepower'] }} -
            </th>

            {{$counter = $counter + 1}}

        @endforeach
    </tr>

    {{$counter = 1}}
    <tr>
        @foreach($data[1] as $auxiliary)

            <th>
                {{ $auxiliary['engine_motor_no'] }}
            </th>

            {{$counter = $counter + 1}}

        @endforeach
    </tr>

    {{$counter = 1}}
    <tr>
        @foreach($data[1] as $auxiliary)

            <th>
                - {{ $auxiliary['cylinder'] }} -
            </th>

            {{$counter = $counter + 1}}

        @endforeach
    </tr>

</table>

@endif


<table class="table-a-3">
    <tr>
        <th>{{ $data[0]['boat_type'] }}</th>
    </tr>
    <tr>
        <th>{{ $data[0]['fishing_gear'] }}</th>
    </tr>
    <tr>
        <th>{{ $data[0]['manning_crew'] }}</th>
    </tr>
</table>


<div class="body_number">{{ $data[0]['body_number'] }}</div>

<table class="table-b">
    <tr>
        <th>{{ $data[0]['full_name'] }}</th>
    </tr>

    <tr>
        <th>{{ $data[0]['address'] }}</th>
    </tr>

    <tr>
        <th>{{ date('F d, Y' , strtotime($data[0]['birth_date'])) }}</th>
    </tr>

    <tr>
        <th>PAGADIAN CITY</th>
    </tr>
</table>

<span class="license_number">{{ $data[2] }}</span>
<span class="license_number_slide">{{ $data[2] }}</span>


</body>
</html>

