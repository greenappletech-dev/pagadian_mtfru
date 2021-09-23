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
        <th style="font-weight: bold">{{ 'FROM: ' . date('m/d/Y', strtotime($from)) . ' TO: ' . date('m/d/Y', strtotime($to)) }}</th>
        <th style="font-weight: bold">@if($barangay != null) {{ $barangay }} @else ALL BARANGAY @endif</th>
    </tr>
    <tr>
        <th style="text-align: left">MONTH/S</th>
        <th style="text-align: center">APPLICATION</th>
        <th style="text-align: center">PAYMENT</th>
        <th style="text-align: center">PENDING</th>
        <th style="text-align: center">COMPLETED</th>
    </tr>


    </thead>

    {{ $total_application = $total_payment = $total_pending = $total_complete = 0 }}

    <tbody>

    @foreach($array as $key=>$value)

        <tr>
            <td style="text-align: left">{{ date('F', strtotime($key)) }}</td>
            <td style="text-align: center">{{ $value['application'] }}</td>
            <td style="text-align: center">{{ $value['payment'] }}</td>
            <td style="text-align: center">{{ $value['pending'] }}</td>
            <td style="text-align: center">{{ $value['completed'] }}</td>
        </tr>

        {{ $total_application += $value['application'] }}
        {{ $total_payment += $value['payment'] }}
        {{ $total_pending += $value['pending'] }}
        {{ $total_complete += $value['completed'] }}


    @endforeach

    </tbody>

    <tfoot style="border-top: 1px solid #000">
    <tr style="font-weight: bold">
        <td style="text-align: left">TOTAL:</td>
        <td style="text-align: center">{{ $total_application }}</td>
        <td style="text-align: center">{{ $total_payment }}</td>
        <td style="text-align: center">{{ $total_pending }}</td>
        <td style="text-align: center">{{ $total_complete }}</td>
    </tr>
    </tfoot>

</table>

</body>

</html>
