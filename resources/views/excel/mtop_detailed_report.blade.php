<html>
<head>
    <title>MTOP Detailed Report</title>
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
        <th colspan="7" style="font-weight: bold">{{ 'FROM: ' . date('m/d/Y', strtotime($from)) . ' TO: ' . date('m/d/Y', strtotime($to)) }}</th>
    </tr>
    <tr>
        <th>APPLICATION TYPE</th>
        <th>BODY #</th>
        <th>OPERATOR</th>
        <th>ADDRESS</th>
        <th>CONTACT #</th>
        <th>OR NUMBER</th>
        <th>AMOUNT</th>
    </tr>



    </thead>

    <tbody>


    {{ $application_type = ''}}
    {{ $count = 0 }}
    {{ $array_count = count($array) }}
    {{ $x = 0 }}
    {{ $totals = 0 }}


    @foreach($array as $key=>$value)

        @if($application_type != $value[6])


            @if($count > 1)

                <tr style="font-size: 13px; font-weight: bold;">
                    <td colspan="5" style="border-top: 1px solid #000; border-bottom: 1px solid #000">
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
                <td colspan="7" style="padding-top: 10px; font-size: 13px; font-weight: bold;">

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
            <td style="font-size: 10px; font-weight: bold">@if(!empty($value[8])) {{ $value[8] }} @endif</td>
            <td style="padding: 5px 0;">{{ $value[0] }}</td>
            <td>{{ $value[1] }}</td>
            <td>{{ $value[2] }}</td>
            <td>{{ $value[3] }}</td>
            <td>{{ $value[4] }}</td>
            <td style="text-align: right">{{ number_format($value[5], 2) }}</td>
        </tr>

        {{ $totals += $value[5] }}
        {{ $count++ }}
        {{ $x++ }}



        @if($x == $array_count)

            <tr style="font-size: 13px; font-weight: bold;">
                <td colspan="5" style="border-top: 1px solid #000; border-bottom: 1px solid #000">
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

</body>

</html>
