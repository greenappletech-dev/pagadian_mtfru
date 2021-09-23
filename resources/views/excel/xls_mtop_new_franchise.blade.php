<html>
<head>
    <title>MTOP New Franchise Report</title>
</head>

<style>

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table thead tr th {
        border: 1px solid #000;
    }

    table thead tr th:nth-child(2),
    :nth-child(3),
    :nth-child(4)
    {
        text-align: left;
    }

    table tbody tr td:nth-child(3),
    table tbody tr td:nth-child(5),
    table tbody tr td:nth-child(6),
    table tbody tr td:nth-child(7),
    table tbody tr td:nth-child(8),
    table tbody tr td:nth-child(9) {
        text-align: center;
    }

</style>

<body>

<table style="width: 100%">

    <thead>

            <tr>
                <th style="font-weight: bold">{{ 'FROM: ' . date('m/d/Y', strtotime($from)) . ' TO: ' . date('m/d/Y', strtotime($to)) }}</th>
            </tr>

            <tr>
                <th>SIDECAR NUMBER</th>
                <th>OPERATOR</th>
                <th>ADDRESS</th>
                <th>CONTACT #</th>
                <th>DATE ISSUED</th>
                <th>DATE APPLY</th>
                <th>DATE OF PAYMENT</th>
                <th>DATE COMPLETED</th>
                <th>MAKE/TYPE</th>
                <th>REMARKS</th>
            </tr>

    </thead>

    <tbody>

        @foreach($array['report'] as $key=>$value)

            <tr>
                <td>{{ $key }}</td>
                <td>{{ $value['full_name'] }}</td>
                <td>{{ $value['address'] }}</td>
                <td>{{ $value['mobile'] }}</td>
                <td>{{ $value['date_registered'] }}</td>
                <td>{{ $value['transact_date'] }}</td>
                <td>{{ $value['payment_date'] }}</td>
                <td>{{ $value['approve_date'] }}</td>
                <td>{{ $value['make_type'] }}</td>
                <td>{{ $value['status'] }}</td>
            </tr>

        @endforeach

    </tbody>

</table>

</body>

</html>
