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
        top: 322px;
        right: 150px;
        width: 257px;
        border-collapse: collapse;
    }

    table tr th {
        font-size: 14px;
        padding-top: 4px;
        padding-bottom: 4px;
    }
</style>


<body>

<img style="width: 815px; position: absolute; top: -45px; left: -45px;" src="{{ asset('image/forms/AFFIDAVIT OF OWNERSHIP.jpg') }}" alt="">

<div class="full_name">{{ $data[0]['full_name'] }}</div>
<div class="address">{{ $data[0]['address'] }}</div>


<table>
    <tr>
        <th>{{ $data[0]['name'] }}</th>
    </tr>
    <tr>
        <th>{{ $data[0]['boat_type'] }}</th>
    </tr>
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
        <th>{{ $data[0]['make_type'] === '' ? '- NA - ' : $data[0]['make_type']}}</th>
    </tr>
    <tr>
        <th>{{ $data[0]['engine_motor_no'] === '' ? '- NA -' : $data[0]['engine_motor_no'] }}</th>
    </tr>
    <tr>
        <th>{{ $data[0]['color'] }}</th>
    </tr>
    <tr>
        <th>{{ $data[0]['horsepower'] === null ? '- NA -' : $data[0]['horsepower'] }}</th>
    </tr>
</table>
</body>
</html>

