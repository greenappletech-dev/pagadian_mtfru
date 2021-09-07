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
            font-size: 12px;
        }

        main table {
            border-collapse: collapse;
        }

        main table thead {
            border: 1px solid #000;
        }

        main table thead tr th {
            padding: 5px 0px;
            font-weight: normal;
        }

        main table thead tr th:first-child {
            padding-left: 10px;
        }

        main table thead tr th:last-child {
            padding-right: 10px;
        }

        main table tbody tr td {
            padding: 5px 0px 2px 0px;
            vertical-align: top;
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
            <th class="report-title">New Franchise</th>
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

        @foreach($generated_report as $key=>$value)

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

</main>
</body>
</html>


