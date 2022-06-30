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
            font-size: 12px;
        }

        main table thead tr th:first-child {
            padding-left: 10px;
        }

        main table thead tr th:last-child {
            padding-right: 10px;
        }

        main table tbody tr td {
            padding: 7px 2px;
            font-size: 10px;
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
{{--            <th>User ID: <span class="username">{{ Auth::user()->name }}</span></th>--}}
        </tr>
        <tr>
            <th class="city">Pagadian City</th>
            <th class="page-number">Page No: <span class="pagenum"></span></th>
        </tr>
        <tr>
            <th class="report-title">For Expiration and Expired Franchise</th>
            <th class="datetime">{{ date('m/d/Y h:i:s A') }}</th>
        </tr>
    </table>
</header>

<main>


    <table style="width: 100%">

        <thead>

        <tr>

{{--            <th style="text-align: left">LAST MTFRB CASE NO</th>--}}
{{--            <th style="text-align: center">BODY NUMBER</th>--}}
{{--            <th style="text-align: center">MAKE TYPE</th>--}}
{{--            <th style="text-align: center">ENGINE MOTOR</th>--}}
{{--            <th style="text-align: center">CHASSIS NO</th>--}}
{{--            <th style="text-align: center">PLATE NO</th>--}}
{{--            <th style="text-align: center">VALIDITY DATE</th>--}}
{{--            <th style="text-align: center">STATUS</th>--}}
            <th style="text-align: left"></th>
            <th style="text-align: left">BODY NUMBER</th>
            <th style="text-align: left">OPERATOR</th>
            <th style="text-align: left">ADDRESS</th>
            <th style="text-align: left">CONTACT #</th>
            <th style="text-align: center">MAKE TYPE</th>
            <th style="text-align: center">VALIDITY DATE</th>
            <th style="text-align: center">STATUS</th>
        </tr>


        </thead>

        <tbody>

        {{ $count = 1 }}

        @foreach($expired as $data)

            <tr>
                <td style="text-align: left">{{ $count }}</td>
                <td style="text-align: left">{{ $data['body_number'] }}</td>
                <td style="text-align: left">{{ $data['full_name'] }}</td>
                <td style="text-align: left">{{ $data['address1'] }}</td>
                <td style="text-align: left">{{ $data['mobile'] }}</td>
                <td style="text-align: center">{{ $data['make_type'] }}</td>
                <td style="text-align: center">{{ date('m/d/Y', strtotime($data['validity_date'])) }}</td>
                <td style="text-align: center">{{ $data['status'] }}</td>
            </tr>

            {{ $count++ }}


        @endforeach

        </tbody>

    </table>

</main>
</body>
</html>
