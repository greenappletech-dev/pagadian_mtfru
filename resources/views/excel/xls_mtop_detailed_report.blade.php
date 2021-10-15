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
        <th style="font-weight: bold">{{ 'FROM: ' . date('m/d/Y', strtotime($from)) . ' TO: ' . date('m/d/Y', strtotime($to)) }}</th>
        <th style="font-weight: bold">@if($barangay != null) {{ $barangay }} @else ALL BARANGAY @endif</th>
    </tr>
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
    {{ $array_count = count($array) }}
    {{ $x = 0 }}
    {{ $totals = 0 }}


    @foreach($array as $key=>$value)

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

</body>

</html>
