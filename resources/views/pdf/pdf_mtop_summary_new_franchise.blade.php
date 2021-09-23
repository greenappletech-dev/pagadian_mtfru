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
            margin: 145px 50px 30px 50px;
            font-family: Arial, sans-serif;
        }

        /* header */

        header  {
            position: fixed;
            padding: 50px 50px 0px 50px;
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
            font-size: 15px;
        }

        main table {
            border-collapse: collapse;
        }

        main table thead {
            border: 1px solid #000;
        }

        main table thead tr th {
            padding: 5px 0px;
            text-align: left;
            font-weight: normal;
        }

        main table thead tr th:first-child {
            padding-left: 10px;
        }

        main table thead tr th:last-child {
            padding-right: 10px;
        }

        main table tbody tr td {
            padding: 2px 0px;
        }

        main table tbody tr td:first-child {
            padding-left: 10px;
        }

        main table tbody tr td:last-child {
            padding-right: 10px;
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
            <th class="report-title">Summary New Franchise By Month</th>
            <th class="datetime">{{ date('m/d/Y h:i:s A') }}</th>
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


    <table style="width: 100%">

        <thead>

        <tr>
            <th style="text-align: left">MONTH/S</th>
            <th style="text-align: center">APPLICATION</th>
            <th style="text-align: center">PAYMENT</th>
            <th style="text-align: center">PENDING</th>
            <th style="text-align: center">COMPLETED</th>
        </tr>


        </thead>

        {{ $total_application = $total_payment = $total_pending = $total_complete = 0 }}

        <tbody>


        @foreach($generated_report as $key=>$value)

            <tr>
                <td style="text-align: left">{{ date('F', strtotime($key)) }}</td>
                <td style="text-align: center">{{ $value['application'] }}</td>
                <td style="text-align: center">{{ $value['payment'] }}</td>
                <td style="text-align: center">{{ $value['pending'] }}</td>
                <td style="text-align: center">{{ $value['completed'] }}</td>
            </tr>

            {{ $total_application += $value['application'] }}
            {{ $total_payment += $value['payment'] }}
            {{ $total_pending += $value['pending'] }}
            {{ $total_complete += $value['completed'] }}


        @endforeach



        </tbody>

        <tfoot style="padding: 10px 0; border-top: 1px solid #000">
        <tr style="font-weight: bold">
            <td style="text-align: left">TOTAL:</td>
            <td style="text-align: center">{{ $total_application }}</td>
            <td style="text-align: center">{{ $total_payment }}</td>
            <td style="text-align: center">{{ $total_pending }}</td>
            <td style="text-align: center">{{ $total_complete }}</td>
        </tr>
        </tfoot>

    </table>

</main>
</body>
</html>


