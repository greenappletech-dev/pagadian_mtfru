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
            font-size: 10px;
        }

        main table {
            border-collapse: collapse;
        }

        main table thead {
            border: 1px solid #000;
        }

        main table thead tr th:nth-child(1) {
            width: 5%;
        }

        main table thead tr th:nth-child(1),
        table tbody tr td:nth-child(1)
        {
            text-align: center;
        }

        main table thead tr th:nth-child(2),
        :nth-child(3),
        :nth-child(4)
        {
            text-align: left;
        }



        main table thead tr th,
        main table tbody tr td {
            padding: 5px 5px;
            font-weight: normal;
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
            <th class="report-title">New Franchise</th>
            <th class="datetime">{{ date('m/d/Y h:i:s A') }}</th>
        </tr>
        <tr>
            <th>{{ 'As of: ' . date('F d, Y') }}</th>
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
                <th>SIDECAR NUMBER</th>
                <th>OPERATOR</th>
                <th>ADDRESS</th>
                <th>CONTACT #</th>
                <th>DATE APPLY</th>
                <th>DATE OF PAYMENT</th>
                <th>DATE COMPLETED</th>
                <th>MAKE/TYPE</th>
                <th>REMARKS</th>
            </tr>


        </thead>

        <tbody>

            @foreach($generated_report as $report)

                <tr>
                    <td style="text-align: center">
                        {{ $report[0] }}
                    </td>

                    <td>
                        {{ $report[1] != null ? $report[1]['full_name'] : ''}}
                    </td>

                    <td>
                        {{ $report[1] != null ? $report[1]['address'] : ''}}
                    </td>

                    <td style="text-align: center">
                        {{ $report[1] != null ? $report[1]['mobile'] : ''}}
                    </td>

                    <td style="text-align: center">
                        @if($report[1] != null && $report[1]['transact_date'] != null)
                            {{
                                $report[1]['full_name'] != null
                                ? date('m/d/Y', strtotime($report[1]['transact_date']))
                                : ''
                            }}
                        @endif
                    </td>

                    <td style="text-align: center">
                        @if($report[1] != null && $report[1]['trnx_date'] != null)
                            {{
                                $report[1]['full_name'] != null
                                ? date('m/d/Y', strtotime($report[1]['trnx_date']))
                                : ''
                            }}
                        @endif
                    </td>

                    <td style="text-align: center">
                        @if($report[1] != null && $report[1]['approve_date'] != null)
                            {{
                                $report[1]['full_name'] != null
                                ? date('m/d/Y', strtotime($report[1]['approve_date']))
                                : ''
                            }}
                        @endif
                    </td>

                    <td style="text-align: center">
                         {{ $report[1] != null ? $report[1]['make_type'] : ''}}
                    </td>

                    <td style="text-align: center">

                        @if($report[1] != null)

                            @if($report[1]['full_name'] != null)

                                @switch($report[1]['status'])

                                    @case(1)

                                    PENDING

                                    @break

                                    @case(2)

                                    FOR PAYMENT

                                    @break

                                    @case(3)

                                    FOR APPROVAL

                                    @break

                                    @case(4)

                                    APPROVED

                                    @break

                                    @default

                                @endswitch

                            @endif

                        @endif

                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>

</main>
</body>
</html>


