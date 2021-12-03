<html>
<title>FVR AFFIDAVIT FORM</title>
<header>
</header>


<style>

    body {
        font-family: Arial, sans-serif;
        font-weight: bold;
    }

    .full_name {
        position: absolute;
        top: 176px;
        left: 120px;
        width: 460px;
        text-align: center;
    }

    .address {
        position: absolute;
        top: 217px;
        left: 109px;
        width: 380px;
        font-size: 12px;
    }

    table {
        position: absolute;
        top: 325px;
        right: 150px;
        width: 257px;
        border-collapse: collapse;
    }

    table tr th {
        font-size: 12px;
        padding-top: 4px;
        padding-bottom: 6px;
    }

    .engine {
        position: absolute;
        top: 503px;
        font-size: 12px;
        border-collapse:  collapse;
    }

    .engine tr td {
        width: 25%;
        padding-bottom: 8px;
    }

    .owner {
        position: absolute;
        top: 786px;
        right: 230px;
        text-align: center;
        width: 300px;
        font-size: 12px;
    }

    .day {
        position: absolute;
        top: 715px;
        right: 152px;
        text-align: center;
        font-size: 12px;
    }

    .month {
        position: absolute;
        top: 728px;
        left: 209px;
        text-align: center;
        width: 180px;
        font-size: 12px;
    }

    .ctc_no {
        position: absolute;
        bottom: 173px;
        left: 450px;
        font-size: 12px;
    }

    .ctc_date {
        position: absolute;
        bottom: 158px;
        left: 190px;
        font-size: 12px;
        width: 125px;
        text-align: center;
    }

    .issue_at {
        position: absolute;
        bottom: 158px;
        left: 338px;
        font-size: 12px;
        width: 125px;
        text-align: center;
    }

</style>


<?php

use Carbon\Carbon;

$engineArr = array();

    /* push first the primary engine and loop to the auxiliary engine after */

    $na = 'NA';

    array_push($engineArr, [
        'make_type'             => empty(!$data['application']['make_type']) ? '<td>' . $data['application']['make_type'] . '</td>' : '<td>NA</td>',
        'horsepower'            => empty(!$data['application']['hoursepower']) ? '<td>' . $data['application']['hoursepower'] . '</td>' : '<td>NA</td>',
        'engine_motor_no'       => empty(!$data['application']['engine_motor_no']) ? '<td>' . $data['application']['engine_motor_no'] . '</td>' : '<td>NA</td>',
        'color'                 => empty(!$data['application']['color']) ? '<td>' . $data['application']['color'] . '</td>' : '<td>NA</td>',
        'cylinder'              => empty(!$data['application']['cylinder']) ? '<td>' . $data['application']['cylinder'] . '</td>' : '<td>NA</td>',
    ]);

    /* get auxiliary engine */

    if($data['auxiliary_engine'])
    {
        foreach ($data['auxiliary_engine'] as &$auxEngine) {
            $engineArr[0]['make_type'] .= '<td>:' . $auxEngine->make_type . '</td>';
            $engineArr[0]['horsepower'] .= '<td>:' . $auxEngine->horsepower . '</td>';
            $engineArr[0]['engine_motor_no'] .= '<td>:' . $auxEngine->engine_motor_no . '</td>';
            $engineArr[0]['cylinder'] .= '<td>:' . $auxEngine->cylinder . '</td>';
        }
    }

    $month = Carbon::parse($data['application']['or_date'])->format('F, Y');
    $day = Carbon::parse($data['application']['or_date'])->format('jS');
    $ctcIssuedDate = Carbon::parse($data['application']['ctc_date'])->format('F d, Y');

?>


<body>

<img style="width: 815px; position: absolute; top: -45px; left: -45px;" src="{{ asset('image/forms/AFFIDAVIT OF OWNERSHIP.jpg') }}" alt="">

<div class="full_name">{{ $data['application']['full_name'] }}</div>
<div class="address">{{ $data['application']['address'] }}</div>

<div class="lawful_owner" style="position: absolute; top: 280px; right: 150px; text-align: center; width: 233px; font-size: 12px;">{{ $data['application']['name'] }}</div>


<table>
    <tr>
        <th>{{ $data['application']['name'] }}</th>
    </tr>
    <tr>
        <th>{{ $data['application']['boat_type'] }}</th>
    </tr>
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

    <table class="engine">

        @foreach($engineArr as $engine)
            <tr>
                {!! $engine['make_type'] !!}
            </tr>
            <tr>
                {!! $engine['engine_motor_no'] !!}
            </tr>
            <tr>
                {!! $engine['color'] ?? ''  !!}
            </tr>
            <tr>
                {!! $engine['horsepower'] !!}
            </tr>
        @endforeach

    </table>
</table>

<div class="owner" style="">{{ $data['application']['full_name'] }}</div>
<div class="day" style="">{{ $day }}</div>
<div class="month" style="">{{ $month }}</div>

{{-- CTC INFORMATION --}}

<div class="ctc_no">{{ $data['application']['ctc_no'] }}</div>
<div class="ctc_date">{{ $ctcIssuedDate }}</div>
<div class="issue_at">{{ $data['comp_address'] }}</div>







</body>
</html>

