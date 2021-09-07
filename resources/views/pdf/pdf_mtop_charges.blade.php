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
            font-size: 11px;
        }

        main table thead {
            border: 1px solid #000;
        }

        main table thead tr th {
            padding: 5px 0px;
            text-align: center;
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
            text-align: center;
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
            <th class="report-title">Operator's Annual Tax</th>
            <th class="datetime">{{ date('m/d/Y h:i:s A') }}</th>
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
                <th style="width: 10%">DATE</th>
                <th style="width: 15%">TAX DATE</th>
                <th style="text-align: left">OPERATOR</th>
                <th style="width: 10%">OR NUMBER</th>
                <th style="width: 15%">MTFRB CASE NO</th>
                <th style="width: 10%">VALIDITY DATE</th>
            </tr>
        </thead>

        <tbody>
            @foreach($mtop_charges as $charge)
                <tr>
                    <td>{{ $charge->trnx_date }}</td>
                    <td>{{ $charge->inc_desc }}</td>
                    <td style="text-align: left">{{ $charge->operator }}</td>
                    <td>{{ $charge->or_number }}</td>
                    <td>{{ $charge->mtfrb_case_no }}</td>
                    <td>{{ $charge->validity_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</main>
</body>
</html>


