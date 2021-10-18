<html>
<title>PDF Report</title>
<head>
    <style>
        @page {
            margin: 0cm 0cm;
        }

        .pagenum:before {
            content: counter(page);
        }

        /** Define now the real margins of every page in the PDF **/
        body {
            margin: 130px 20px 30px 20px;
            font-family: Arial, sans-serif;
        }

        /* header */

        header  {
            position: fixed;
            padding: 50px 50px 0px 20px;
            height: 75px;
        }

        header .table-header tr th{
            text-align: left;
            padding: 1px 0px;
            font-size: 13px;
            font-weight: normal;
        }

        header .table-header tr .company {
            font-weight: bold;
            font-size: 20px;
        }

        header .table-header tr .report-title {
            font-weight: bold;
        }


        /* main */

        main {
            font-size: 10px;
        }

        main table thead {
            border: 1px solid #000;
        }

        main table thead tr th,
        main table tbody tr td {
            padding: 2px 5px;
            font-weight: normal;
        }

        main table thead tr th:nth-child(2),
        main table thead tr th:nth-child(6),
        main table tbody tr td:nth-child(2),
        main table tbody tr td:nth-child(6)
        {
            width: 10%;
            text-align: left;
        }

        main table thead tr th:nth-child(1),
        main table thead tr th:nth-child(3),
        main table tbody tr td:nth-child(1),
        main table tbody tr td:nth-child(3) {
            width: 15%;
            text-align: left;
        }




        main table thead tr th:nth-child(4),
        main table thead tr th:nth-child(5),
        main table thead tr th:nth-child(7),
        main table thead tr th:nth-child(8),
        main table thead tr th:nth-child(9),
        main table thead tr th:nth-child(10),
        main table thead tr th:nth-child(11),
        main table tbody tr td:nth-child(4),
        main table tbody tr td:nth-child(5),
        main table tbody tr td:nth-child(7),
        main table tbody tr td:nth-child(8),
        main table tbody tr td:nth-child(10),
        main table tbody tr td:nth-child(11)
        {
            width: 8%;
            text-align: center;
        }


        main table tbody tr td:nth-child(9) {
            text-align: right;
        }




    </style>
</head>
<body>
<!-- Define header and footer blocks before your content -->
<header>
    <table class="table-header" width="100%;">
        <tr>
            <th width="75%" class="company">MTFRU</th>
            <th>User ID: <span class="username">{{ Auth::user()->name }}</span></th>
        </tr>
        <tr>
            <th class="city">Pagadian City</th>
            <th class="page-number">Page No: <span class="pagenum"></span></th>
        </tr>
        <tr>
            <th style="font-weight: bold">{{ 'FROM: ' . date('m/d/Y', strtotime($from)) . ' TO: ' . date('m/d/Y', strtotime($to)) }}</th>
            <th style="font-weight: bold">@if($barangay != null) {{ $barangay->brgy_desc }} @else ALL BARANGAY @endif</th>
        </tr>
    </table>
</header>

{{--<footer>--}}

{{--</footer>--}}

<!-- Wrap the content of your PDF inside a main tag -->

<main>
    <table width="100%">
        <thead>
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
                    <td>{{ $report->or_number }} <br> {{ $report->or_number_2 }}</td>
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
                <td style="text-align: right">{{ number_format($totals, 2) }}</td>
                <td colspan="3"></td>
            </tr>
        </tfoot>
    </table>
</main>
</body>
</html>


