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
            padding: 4px 0px;
            vertical-align: top;
        }

        main table tbody tr td:first-child {
            padding-left: 10px;
        }

        main table tbody tr td:last-child {
            padding-right: 10px;
        }

        main {
            page-break-after: always;
        }

        .summary {
            position: absolute;
            top: 30%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
        }

        .summary tbody tr td {
            border: 1px solid #000;
            font-size: 12px;
            font-weight: bold;
            vertical-align: middle;
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
                <th class="report-title">Monthly Accomplishment Report</th>
                <th class="datetime">{{ date('m/d/Y h:i:s A') }}</th>
            </tr>
            <tr>
                <th style="font-weight: bold">{{ 'FROM: ' . date('m/d/Y', strtotime($from)) . ' TO: ' . date('m/d/Y', strtotime($to)) }}</th>
                <th style="font-weight: bold"></th>
            </tr>
        </table>
    </header>

    {{--<footer>--}}

    {{--</footer>--}}

    <!-- Wrap the content of your PDF inside a main tag -->

    <?php
        $count = 0;
        $transaction_type = array('N' => 'NEW', 'R' => 'RENEWAL', 'CU' => 'CHANGE UNIT', 'T' => 'TRANSFER', 'R|CU' => 'RENEWAL & CHANGE UNIT', 'T|CU' => 'TRANSFER & CHANGE UNIT');
    ?>


    <main>

        <table class="summary">
            <thead>
                <tr>
                    <th colspan="2" style="text-align: center">
                        <span style="font-size: 15px; font-weight: bold">Monthly Accomplishment Report</span><br>
                        <span style="font-size: 13px; font-weight: normal">(Summary per Transaction)</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaction_type as $key => $value)

                    <tr>
                        <td style="width: 30%">{{ $value }}</td>
                        <td style="text-align: center">{{ isset($generated_report[$key]) ? count($generated_report[$key]) : 0 }}</td>
                    </tr>

                @endforeach
            </tbody>
        </table>

    </main>


    <main>
        <table style="width: 100%">

            <thead>
                <tr>
                    <th style="text-align: center; width: 3%">No.</th>
                    <th style="text-align: center; width: 8%">MOTOR SIDECAR</th>
                    <th style="text-align: left">NAME OF OPERATOR</th>
                    <th style="text-align: left">ADDRESS</th>
                    <th style="text-align: left; width: 8%">CELL NO</th>
                    <th style="text-align: left; width: 8%">MAKE/TYPE</th>
                    <th style="text-align: center; width: 8%">DATE OF APPLICATION</th>
                    <th style="text-align: center; width: 8%">DATE OF PAYMENT</th>
                    <th style="text-align: center; width: 5%">STATUS OF TRANSACTION</th>
                </tr>
            </thead>

            <tbody>

                @foreach($transaction_type as $trans_key => $trans_value)]]

                    <?php $count = 0;  ?>

                    <tr>
                        <td style="font-size: 15px; font-weight: bold; border-bottom: 1px solid #000; padding-top: 5px; padding-bottom: 5px;" colspan="9">
                            {{ $trans_value }}
                        </td>
                    </tr>

                    @if(isset($generated_report[$trans_key]))

                        @foreach($generated_report[$trans_key] as $value)

                            <?php $count++;  ?>

                            <tr>
                                <td style="text-align: center">{{ $count }}</td>
                                <td style="text-align: center">{{ $value['body_number'] }}</td>
                                <td>{{ $value['full_name'] }}</td>
                                <td>{{ $value['address'] }}</td>
                                <td>{{ $value['mobile'] }}</td>
                                <td>{{ $value['make_type'] }}</td>
                                <td style="text-align: center">{{ date_format(new DateTime($value['transact_date']), 'm/d/Y') }}</td>
                                <td style="text-align: center">{{ date_format(new DateTime($value['payment_date']), 'm/d/Y') }}</td>
                                <td style="text-align: center">{{ $value['transact_type'] }}</td>
                            </tr>

                        @endforeach

                    @endif

                @endforeach

            </tbody>

        </table>

    </main>



</body>
</html>


