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
        text-align: left;
    }


    table tbody tr td:nth-child(9) {
        text-align: left;
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
            <th style="width: 10%">BODY #</th>
            <th>TRADE NAME</th>
            <th>ADDRESS</th>
            <th>ENGINE MAKE</th>
            <th>HP</th>
            <th>REMARKS</th>
            <th>ENGINE #</th>
            <th>GEAR USE</th>
            <th style="text-align: right">OR DATE</th>
            <th style="text-align: right; width: 8%">OR A</th>
            <th style="text-align: right; width: 8%">OR B</th>
            <th style="text-align: right; width: 8%">OR C</th>
        </tr>
        </thead>

        <?php
            $totals = 0;
        ?>

        <tbody>

        @foreach($generated_report as $report)
            <tr>
                <td>{{ $report['full_name'] }}</td>
                <td>{{ $report['body_number'] }}</td>
                <td>{{ $report['boat_name'] }}</td>
                <td style="text-align: left">{{ $report['address'] }}</td>
                <td>{{ $report['make_type'] }}</td>
                <td>{{ $report['horsepower'] }}</td>
                <td>{{ $report['transact_type'] }}</td>
                <td>{{ $report['engine_motor_no'] }}</td>
                <td>{{ $report['fishing_gear'] }}</td>
                <td style="text-align: right">{{ date('m/d/Y', strtotime($report['or_date'])) }}</td>
                <td style="text-align: right">{{ $report['or_number'] }}</td>
                <td style="text-align: right">{{ $report['or_number_2'] }}</td>
                <td style="text-align: right">{{ $report['or_number_3'] }}</td>
            </tr>
            <tr>
                <th colspan="10" style="text-align: right; border-top: 1px solid #000">TOTALS PER OR:</th>
                <th style="text-align: right; border-top: 1px solid #000">{{ number_format($report['A'], 2) }}</th>
                <th style="text-align: right; border-top: 1px solid #000">{{ number_format($report['B'], 2) }}</th>
                <th style="text-align: right; border-top: 1px solid #000">{{ number_format($report['C'], 2) }}</th>
            </tr>
            <tr>
                <th style="text-align: right" colspan="10">TOTAL PER TRANSACTION:</th>
                <th style="text-align: right" colspan="3">{{ number_format($report['total'], 2) }}</th>
            </tr>


            {{ $totals += $report['A'] + $report['B'] + $report['C'] }}
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="10" style="text-align: right">GRAND TOTAL:</th>
                <th colspan="3" style="text-align: right">{{ number_format($totals, 2) }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>


