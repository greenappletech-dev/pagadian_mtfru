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
            <th class="report-title">List of Tricycles</th>
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
            <th>Operator</th>
            <th>Body Number</th>
            <th>Make Type</th>
            <th>Engine No</th>
            <th>Chassis No</th>
            <th>Plate No</th>
        </tr>
        </thead>

        <tbody>
        @foreach($tricycles as $tricycle)
            <tr>
                <td>{{ $tricycle->operator }}</td>
                <td>{{ $tricycle->body_number }}</td>
                <td>{{ $tricycle->make_type }}</td>
                <td>{{ $tricycle->engine_motor_no }}</td>
                <td>{{ $tricycle->chassis_no }}</td>
                <td>{{ $tricycle->plate_no }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</main>
</body>
</html>


