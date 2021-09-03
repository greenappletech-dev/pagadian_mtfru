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
            margin: 130px 50px 30px 50px;
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
            font-size: 13px;
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
            <th class="report-title">Total Tricycle Count Per Make/Type</th>
            <th class="datetime">{{ date('m/d/Y h:i:s A') }}</th>
        </tr>
    </table>
</header>

{{--<footer>--}}

{{--</footer>--}}

<!-- Wrap the content of your PDF inside a main tag -->
<main>
    <table style="width: 100%">

        {{ $count = count($generated_report) }}
        {{ $total_old = 0 }}
        {{ $total_new = 0 }}
        {{ $grand_total = 0 }}

        <thead>


        <tr>
            <th>MAKE TYPE</th>
            <th style="text-align: center">{{ $generated_report[$count - 1][0] }}</th>
            <th style="text-align: center">{{ $generated_report[$count - 1][1] }}</th>
            <th style="text-align: center">TOTAL</th>
        </tr>



        </thead>

        <tbody>

        @foreach($generated_report as $key=>$value)

            @if($key != $count - 1)

                <tr>
                    <td>{{ $value[0] }}</td>
                    <td style="text-align: center">{{ $value[1] }}</td>
                    <td style="text-align: center">{{ $value[2] }}</td>
                    <td style="text-align: center">{{ $value[3] }}</td>
                </tr>

                {{ $total_old += $value[1] }}
                {{ $total_new += $value[2] }}
                {{ $grand_total += $value[3] }}

            @endif


        @endforeach

        </tbody>

        <tfoot style="border-top: 1px solid #000;">
            <tr style="">
                <td style="padding: 10px 0; font-weight: bold">TOTAL: </td>
                <td style="padding: 10px 0; text-align: center; font-weight: bold">{{ $total_old }}</td>
                <td style="padding: 10px 0; text-align: center; font-weight: bold">{{ $total_new }}</td>
                <td style="padding: 10px 0; text-align: center; font-weight: bold">{{ $grand_total }}</td>
            </tr>
        </tfoot>

    </table>
</main>
</body>
</html>


