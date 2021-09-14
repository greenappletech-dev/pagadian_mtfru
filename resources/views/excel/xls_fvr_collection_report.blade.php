<html>
<title>PDF Report</title>
<style>
    /* main */

    table {
        font-size: 10px;
    }

    table thead {
        border: 1px solid #000;
    }

    table thead tr th,
    table tbody tr td {
        padding: 2px 5px;
        font-weight: normal;
    }

    table thead tr th:nth-child(2),
    table thead tr th:nth-child(6),
    table tbody tr td:nth-child(2),
    table tbody tr td:nth-child(6)
    {
        width: 10%;
        text-align: left;
    }

    table thead tr th:nth-child(1),
    table thead tr th:nth-child(3),
    table tbody tr td:nth-child(1),
    table tbody tr td:nth-child(3) {
        width: 20%;
        text-align: left;
    }




    table thead tr th:nth-child(4),
    table thead tr th:nth-child(5),
    table thead tr th:nth-child(7),
    table thead tr th:nth-child(8),
    table thead tr th:nth-child(9),
    table thead tr th:nth-child(10),
    table thead tr th:nth-child(11),
    table tbody tr td:nth-child(4),
    table tbody tr td:nth-child(5),
    table tbody tr td:nth-child(7),
    table tbody tr td:nth-child(8),
    table tbody tr td:nth-child(10),
    table tbody tr td:nth-child(11)
    {
        width: 8%;
        text-align: center;
    }


    table tbody tr td:nth-child(9) {
        text-align: right;
    }




</style>
<body>
    <table width="100%">
        <thead>
        <tr>
            <th style="font-weight: bold">{{ 'FROM: ' . date('m/d/Y', strtotime($from)) . ' TO: ' . date('m/d/Y', strtotime($to)) }}</th>
            <th style="font-weight: bold">@if($barangay != null) {{ $barangay }} @else ALL BARANGAY @endif</th>
        </tr>
        <tr>
            <th>NAME</th>
            <th>TRADE NAME</th>
            <th>ADDRESS</th>
            <th>ENGINE MAKE</th>
            <th>HORSEPOWER</th>
            <th>OR NUMBER</th>
            <th>OR DATE</th>
            <th>REMARKS</th>
            <th>AMOUNT COLLECTED</th>
            <th>ENGINE NUMBER</th>
            <th>GEAR USE</th>
        </tr>
        </thead>

        {{ $totals = 0 }}

        <tbody>
        @foreach($generated_report as $report)
            <tr>
                <td>{{ $report->full_name }}</td>
                <td>{{ $report->boat_name }}</td>
                <td style="text-align: left">{{ $report->address }}</td>
                <td>{{ $report->make_type }}</td>
                <td>{{ $report->horsepower }}</td>
                <td>{{ $report->or_number }}</td>
                <td>{{ $report->or_date }}</td>
                <td>{{ $report->transact_type }}</td>
                <td>{{ number_format($report->collection, 2) }}</td>
                <td>{{ $report->engine_motor_no }}</td>
                <td>{{ $report->fishing_gear }}</td>
            </tr>

            {{ $totals += $report->collection }}
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="8">TOTAL</td>
                <td>{{ number_format($totals, 2) }}</td>
                <td colspan="3"></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>


