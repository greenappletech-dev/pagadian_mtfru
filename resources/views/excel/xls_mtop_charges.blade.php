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

    <table>
        <thead>
            <tr>
                <th style="width: 5%">BODY NUMBER</th>
                <th>DATE</th>
                <th>TAX YEAR</th>
                <th>OPERATOR</th>
                <th>OR NUMBER</th>
                <th>AMOUNT</th>
                <th>MTFRB CASE NO</th>
                <th>VALIDITY DATE</th>
            </tr>
        </thead>

        <tbody>
            @foreach($data as $charge)
                <tr>
                    <td style="text-align: center">{{ $charge['body_number'] }}</td>
                    <td style="text-align: center">{{ $charge['trnx_date'] }}</td>
                    <td>{{ $charge['inc_desc'] }}</td>
                    <td style="text-align: left">{{ $charge['operator'] }}</td>
                    <td>{{ $charge['or_number'] }}</td>
                    <td style="text-align: right">{{ $charge['amount'] }}</td>
                    <td>{{ $charge['mtfrb_case_no'] }}</td>
                    <td style="text-align: center">{{ $charge['validity_date'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>
