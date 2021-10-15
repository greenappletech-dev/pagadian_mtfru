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
            font-size: 11px;
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
            <th class="report-title">Detailed Report Per Transaction</th>
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
            <th style="text-align: center">APPLICATION TYPE</th>
            <th style="text-align: center">APPLICATION DATE</th>
            <th style="text-align: center">BODY #</th>
            <th style="text-align: center">MAKE TYPE</th>
            <th style="text-align: center">STATUS</th>
            <th style="text-align: center">OPERATOR</th>
            <th style="text-align: center">ADDRESS</th>
            <th style="text-align: center">CONTACT #</th>
            <th style="text-align: center">OR NUMBER</th>
            <th style="text-align: center">OR DATE</th>
            <th style="text-align: center">AMOUNT</th>
        </tr>



        </thead>

        <tbody>


        {{ $application_type = ''}}
        {{ $count = 0 }}
        {{ $array_count = count($generated_report) }}
        {{ $x = 0 }}
        {{ $totals = 0 }}


        @foreach($generated_report as $key=>$value)

            @if($application_type != $value[6])


                @if($count > 1)

                    <tr style="font-size: 13px; font-weight: bold;">
                        <td colspan="9" style="border-top: 1px solid #000; border-bottom: 1px solid #000">
                            TOTAL COUNT: {{ $count }}
                        </td>
                        <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; text-align: center">
                            TOTAL AMOUNT:
                        </td>
                        <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; text-align: right">
                            {{ number_format($totals, 2) }}
                        </td>
                    </tr>

                @endif


                <tr>
                    <td colspan="11" style="padding-top: 10px; font-size: 13px; font-weight: bold;">

                        @if($value[6] == 'renewal')

                            RENEWAL

                        @elseif($value[6] == 'dropping')

                            DROPPING

                        @elseif($value[6] == 'change_unit')

                            CHANGE UNIT

                        @elseif($value[6] == 'new')

                            NEW

                        @endif

                    </td>


                </tr>

                {{ $totals = 0 }}
                {{ $count = 0 }}
                {{ $application_type = $value[6] }}


            @endif

            <tr>
                <td style="font-size: 10px; font-weight: bold">@if(!empty($value[13])) {{ $value[13] }} @endif</td>
                <td style="padding: 5px 0; text-align: center">{{ date('m/d/Y', strtotime($value[8])) }}</td>
                <td style="padding: 5px 0;">{{ $value[0] }}</td>
                <td>{{ $value[10] }}</td>
                <td>{{ $value[11] }}</td>
                <td>{{ $value[1] }}</td>
                <td>{{ $value[2] }}</td>
                <td>{{ $value[3] }}</td>
                <td style="text-align: center;">{{ $value[4] }}</td>
                <td style="text-align: center;">{{ $value[12] == null ? '' : date('m/d/Y', strtotime($value[12])); }}</td>
                <td style="text-align: right">{{ number_format($value[5], 2) }}</td>
            </tr>

            {{ $totals += $value[5] }}
            {{ $count++ }}
            {{ $x++ }}



            @if($x == $array_count)

                <tr style="font-size: 13px; font-weight: bold;">
                    <td colspan="9" style="border-top: 1px solid #000; border-bottom: 1px solid #000">
                        TOTAL COUNT: {{ $count }}
                    </td>
                    <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; text-align: center">
                        TOTAL AMOUNT:
                    </td>
                    <td style="border-top: 1px solid #000; border-bottom: 1px solid #000; text-align: right">
                        {{ number_format($totals, 2) }}
                    </td>
                </tr>

            @endif


        @endforeach

        </tbody>

    </table>
</main>
</body>
</html>


