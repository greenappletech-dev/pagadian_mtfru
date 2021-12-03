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

<span class="transaction_type">{{ $data['transact_type'] }}</span>

<table class="table-1">
    <tr>
        <th>{{ $data['application']['full_name'] }}</th>
    </tr>

    <tr>
        <th>{{ $data['application']['name'] . ' / ' . $data['application']['color'] }}</th>
    </tr>
</table>

<table class="table-a">
    <tr>
        <th>{{ $data['application']['length'] }}</th>
    </tr>

    <tr>
        <th>{{ $data['application']['width'] }}</th>
    </tr>

    <tr>
        <th>{{ $data['application']['dept'] }}</th>
    </tr>

    <tr>
        <th>{{ $data['application']['gross_tonnage'] }}</th>
    </tr>

    <tr>
        <th>{{ $data['application']['net_tonnage'] }}</th>
    </tr>

    <tr>
        <th>
            @if(!empty($data['application']['make_type']))
                {{ $data['application']['make_type'] }}
            @else
                NA
            @endif
        </th>
    </tr>

    <tr>
        <th>
            @if(!empty($data['application']['horsepower']))
                - {{ $data['application']['horsepower'] }} -
            @else
                NA
            @endif
        </th>
    </tr>

    <tr>
        <th>
            @if(!empty($data['application']['engine_motor_no']))
                {{ $data['application']['engine_motor_no'] }}
            @else
                NA
            @endif
        </th>
    </tr>

    <tr>
        <th>
            @if(!empty($data['application']['make_type']))
                - {{ $data['application']['cylinder'] }} -
            @else
                NA
            @endif
        </th>
    </tr>

    <tr>
        <th>{{ count($data['auxiliary_engine']) }}</th>
    </tr>
</table>

@if(count($data['auxiliary_engine']) !== 0 )

{{ $counter = 1 }}

<table class="table-a-2">
    <tr>
        @foreach($data['auxiliary_engine'] as $auxiliary)

            <th>
                {{$auxiliary['make_type'] }}
            </th>

            {{$counter = $counter + 1}}

        @endforeach
    </tr>

    {{$counter = 1}}
    <tr>
        @foreach($data['auxiliary_engine'] as $auxiliary)

            <th>
                - {{ $auxiliary['horsepower'] }} -
            </th>

            {{$counter = $counter + 1}}

        @endforeach
    </tr>

    {{$counter = 1}}
    <tr>
        @foreach($data['auxiliary_engine'] as $auxiliary)

            <th>
                {{ $auxiliary['engine_motor_no'] }}
            </th>

            {{$counter = $counter + 1}}

        @endforeach
    </tr>

    {{$counter = 1}}
    <tr>
        @foreach($data['auxiliary_engine'] as $auxiliary)

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
        <th>{{ $data['application']['boat_type'] }}</th>
    </tr>
    <tr>
        <th>{{ $data['application']['fishing_gear'] }}</th>
    </tr>
    <tr>
        <th>{{ $data['application']['manning_crew'] }}</th>
    </tr>
</table>


<div class="body_number">{{ $data['application']['body_number'] }}</div>

<table class="table-b">
    <tr>
        <th>{{ $data['application']['full_name'] }}</th>
    </tr>

    <tr>
        <th>{{ $data['application']['address'] }}</th>
    </tr>

    <tr>
        <th>{{ date('F d, Y' , strtotime($data['application']['birth_date'])) }}</th>
    </tr>

    <tr>
        <th>PAGADIAN CITY</th>
    </tr>
</table>

<span class="license_number">{{ $data['license_num'] }}</span>
<span class="license_number_slide">{{ $data['license_num'] }}</span>


</body>
</html>

