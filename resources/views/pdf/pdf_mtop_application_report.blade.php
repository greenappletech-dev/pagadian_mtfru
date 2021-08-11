<html>
<title>MTFRB Application Form</title>
<head>
    <style>

        .pagenum:before {
            content: counter(page);
        }

        /** Define now the real margins of every page in the PDF **/
        body {
            margin: 80px 5px 30px 5px;
            font-family: Arial, sans-serif;
        }

        /* header */

        header  {
            position: fixed;
            padding: 0 5px 0 5px;
            height: 45px;
        }

        header .table-header tr th{
            text-align: left;
            padding: 1px 0;
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

        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
        }

        table {
            border-collapse: collapse;
        }

        table th {
            text-align: left;
            padding: 5px 0;
        }

        table td {
            font-size: 12px;
        }

    </style>
</head>

<body>

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

    <main>

        <table style="width: 100%">
            {{ $temp_value = 0 }}
            {{ $totals = 0 }}
            {{ $grand_total = 0 }}

            <tr>
                <th width="10%">MTFRB Case #</th>
                <th width="14%">Transaction Date</th>
                <th width="25%">Name</th>
                <th>Body #</th>
                <th>Make Type</th>
                <th>Engine #</th>
                <th>Chassis #</th>
                <th>Plate #</th>
                <th style="text-align: center">Status</th>
            </tr>

            @foreach($mtop_applications as $data)

                @if($temp_value !== $data['application_id'])

                    <tr>
                        <td style="padding-top: 10px;">{{ $data['mtfrb_case_no'] }}</td>
                        <td style="padding-top: 10px;">{{ $data['transact_date'] }}</td>
                        <td style="padding-top: 10px;">{{ $data['full_name'] }}</td>
                        <td style="padding-top: 10px;">{{ $data['body_number'] }}</td>
                        <td style="padding-top: 10px;">{{ $data['make_type'] }}</td>
                        <td style="padding-top: 10px;">{{ $data['engine_motor_no'] }}</td>
                        <td style="padding-top: 10px;">{{ $data['chassis_no'] }}</td>
                        <td style="padding-top: 10px;">{{ $data['plate_no'] }}</td>
                        <td style="padding-top: 10px; text-align: center;">
                            @if($data['status'] === 1)
                            {{ 'FOR PAYMENT' }}
                            @elseif($data['status'] === 2)
                            {{ 'FOR APPROVAL' }}
                            @else
                            {{ 'APPROVED' }}
                            @endif
                        </td>
                    </tr>

                    {{ $temp_value = $data['application_id'] }}

                    <tr>
                        <td colspan="8" style="font-weight: bold; padding: 5px 0;">CHARGES</td>
                    </tr>

                    @foreach($mtop_applications as $details)

                        @if((int)$temp_value === (int)$details['mtop_application_id'])
                            <tr>
                                <td colspan="8">{{ $details['name'] }}</td>
                                <td style="text-align: right;">{{ number_format($details['price'], 2) }}</td>
                            </tr>

                            {{ $totals += $details['price'] }}
                        @endif

                    @endforeach

                    <tr>
                        <td colspan="8" style="border-top: 1px solid #000; border-bottom: 1px solid #000; padding: 5px 0; font-weight: bold;">TOTAL: </td>
                        <td style="border-top: 1px solid #000; border-bottom: 1px solid #000;  padding: 5px 0; text-align: right;">{{ number_format($totals, 2) }}</td>
                    </tr>
                    {{ $grand_total += $totals }}
                    {{$totals = 0}}

                @endif

            @endforeach

            <tr>
                <td colspan="8" style="font-weight: bold;  padding: 5px 0;">GRAND TOTAL: </td>
                <td style="text-align: right; font-weight: bold;">{{ number_format($grand_total, 2) }}</td>
            </tr>

        </table>

    </main>

</body>
</html>

