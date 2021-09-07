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

</style>

<body>

<table style="width: 100%">

    <thead>

            <tr>
                <th style="font-weight: bold">{{ 'FROM: ' . date('m/d/Y', strtotime($from)) . ' TO: ' . date('m/d/Y', strtotime($to)) }}</th>
                <th style="font-weight: bold">@if($barangay != null) {{ $barangay }} @else ALL BARANGAY @endif</th>
            </tr>

            <tr>
                <th style="width: 9%">SIDECAR #</th>
                <th style="width: 15%;">OPERATOR</th>
                <th>ADDRESS</th>
                <th style="width: 10%;">CONTACT #</th>
                <th style="width: 7%;">DATE APPLY</th>
                <th style="width: 8%;">DATE OF PAYMENT</th>
                <th style="width: 8%;">DATE COMPLETED</th>
                <th style="width: 10%;">MAKE/TYPE</th>
                <th style="width: 8%;">REMARKS</th>
            </tr>

    </thead>

    <tbody>

    @foreach($array as $key=>$value)

        <tr>
            <td style="text-align: center">{{ $value['body_number'] }}</td>
            <td>{{ $value['full_name'] }}</td>
            <td>{{ $value['address'] }}</td>
            <td style="text-align: center">{{ $value['mobile'] }}</td>
            <td style="text-align: center">{{ $value['transact_date'] }}</td>
            <td style="text-align: center">{{ $value['trnx_date'] }}</td>
            <td style="text-align: center">{{ $value['approve_date'] }}</td>
            <td style="text-align: center">{{ $value['make_type'] }}</td>
            <td style="text-align: center">

                @switch($value['status'])

                    @case(1)

                    PENDING
                    @break

                    @case(2)

                    FOR PAYMENT
                    @break

                    @case(3)

                    FOR APPROVAL
                    @break

                    @default

                    APPROVED

                @endswitch

            </td>
        </tr>

    @endforeach

    </tbody>

</table>

</body>

</html>
