<html>
<head>
    <title>MTOP New Franchise Report</title>
</head>

<style>

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table thead tr th {
        border: 1px solid #000;
    }

</style>

<body>

<table style="width: 100%">

    <thead>

            <tr>
                <th style="font-weight: bold">{{ 'As of: ' . date('F d, Y') }}</th>
            </tr>

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


        @foreach($array as $report)

            <tr>
                <td style="text-align: center">{{ $report[0] }}</td>

                @if($report[1] != null)

                    <td>
                        {{ $report[1]['full_name'] }}
                    </td>

                    <td>
                        {{ $report[1]['address'] }}
                    </td>

                    <td style="text-align: center">
                        {{ $report[1]['mobile'] }}
                    </td>

                    <td style="text-align: center">
                    @if($report[1]['transact_date'] != null && $report[1]['full_name'] != null)
                        {{
                            date('m/d/Y', strtotime($report[1]['transact_date']))
                        }}
                    @endif
                    </td>

                    <td style="text-align: center">
                    @if($report[1]['trnx_date'] != null && $report[1]['full_name'] != null)
                        {{
                            date('m/d/Y', strtotime($report[1]['trnx_date']))
                        }}
                    @endif
                    </td>

                    <td style="text-align: center">
                    @if($report[1]['approve_date'] != null && $report[1]['full_name'] != null)
                        {{
                            date('m/d/Y', strtotime($report[1]['approve_date']))
                        }}
                    @endif
                    </td>

                    <td style="text-align: center">
                        {{ $report[1]['make_type'] }}
                    </td>

                    <td style="text-align: center">

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

                    </td>

                @endif

            </tr>

        @endforeach

    </tbody>

</table>

</body>

</html>
