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
            <th style="width: 10%">DATE</th>
            <th style="width: 15%">TAX DATE</th>
            <th>OPERATOR</th>
            <th style="width: 10%">OR NUMBER</th>
            <th style="width: 15%">MTFRB CASE NO</th>
            <th style="width: 10%">VALIDITY DATE</th>
        </tr>
    </thead>

    <tbody>
        @foreach($data as $charge)
            <tr>
                <td>{{ $charge->trnx_date }}</td>
                <td>{{ $charge->inc_desc }}</td>
                <td style="text-align: left">{{ $charge->operator }}</td>
                <td>{{ $charge->or_number }}</td>
                <td>{{ $charge->mtfrb_case_no }}</td>
                <td>{{ $charge->validity_date }}</td>
            </tr>
        @endforeach
    </tbody>

</table>

</body>

</html>
