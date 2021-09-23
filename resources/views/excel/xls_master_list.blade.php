    <html>
<head>
    <title>Excel Report Export Sample</title>
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
                <th rowspan="2">SIDECAR NUMBER</th>
                <th rowspan="2">OPERATOR</th>
                <th rowspan="2">MTFRB CASE NO.</th>
                <th rowspan="2">ADDRESS</th>
                <th rowspan="2">CONTACT NO.</th>
                <th rowspan="2">DATE ISSUED</th>
                <th rowspan="2">EXPIRE ON</th>
                <th rowspan="2">STATUS</th>
                <th rowspan="2">DATE APPLY</th>
                <th rowspan="2">MAKE/TYPE</th>
                <th rowspan="2">ENGINE MOTOR NO</th>
                <th rowspan="2">CHASSIS NO.</th>
                <th rowspan="2">PLATE NO.</th>
                <th>DATE</th>
                <th colspan="5">TRANSACTION DETAILS</th>
            </tr>
            <tr>
                <th>SIGN</th>
                <th>CHARGES</th>
                <th>OR NO.</th>
                <th>ISSUED AT</th>
                <th>ISSUED ON</th>
                <th>AMOUNT</th>
            </tr>
        </thead>


        <tbody>
            @foreach($applications as $application)
                <tr>
                    <td>{{ $application['body_number'] }}</td>
                    <td>{{ $application['full_name'] }}</td>
                    <td>{{ $application['mtfrb_case_no'] }}</td>
                    <td>{{ $application['address1'] }}</td>
                    <td>{{ $application['mobile'] }}</td>
                    <td>{{ $application['date_issued'] == null ? '' : date('Y-m-d' , strtotime($application['date_issued']))}}</td>
                    <td>{{ $application['validity_date'] }}</td>
                    <td>{{ $application['transact_type'] }}</td>
                    <td>{{ $application['transact_date'] == null ? '' : $application['transact_date'] }}</td>
                    <td>{{ $application['make_type'] }}</td>
                    <td>{{ $application['engine_motor_no'] }}</td>
                    <td>{{ $application['chassis_no'] }}</td>
                    <td>{{ $application['plate_no'] }}</td>
                    <td>{{ $application['approve_date'] }}</td>
                    <td>{{ $application['charges'] }}</td>
                    <td>{{ $application['or_no'] }}</td>
                    <td>PAGADIAN CITY</td>
                    <td>{{ $application['payment_date'] }}</td>
                    <td>{{ $application['amount'] }}</td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>


</html>
