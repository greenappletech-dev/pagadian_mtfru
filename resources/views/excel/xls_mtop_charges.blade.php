<html>
<head>
    <title>Operator Annual Tax</title>
</head>

<style>

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table thead tr th {
        border: 1px solid #000;
    }
</style>

<body>


<table width="100%">
    <thead>
        <tr>
            <th>{{ $data[0]['full_name'] }}</th>
        </tr>
        <tr>
            <th style="width: 10%">BODY NUMBER</th>
            <th style="width: 10%">DATE</th>
            <th>TAX YEAR</th>
            {{--                <th>OPERATOR</th>--}}
            <th>OR NUMBER</th>
            <th>AMOUNT</th>
            {{--                <th>MTFRB CASE NO</th>--}}
            {{--                <th>VALIDITY DATE</th>--}}
        </tr>
    </thead>

    <tbody>
    @foreach($data as $value)
        <tr>
            <td>{{ $value['body_number'] }}</td>
            <td >{{ $value['trnx_date'] }}</td>
            <td>{{ $value['inc_desc'] }}</td>
            {{--                    <td>{{ $value['operator'] }}</td>--}}
            <td>{{ $value['or_number'] }}</td>
            <td>{{ $value['ln_amnt'] }}</td>
            {{--                    <td>{{ $charge['mtfrb_case_no'] }}</td>--}}
            {{--                    <td>{{ $charge['validity_date'] }}</td>--}}
        </tr>
    @endforeach
    </tbody>
</table>

</body>

</html>
