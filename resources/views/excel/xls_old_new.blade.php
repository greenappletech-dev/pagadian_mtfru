<html>
<head>
    <title>Old New Franchise</title>
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

<table>

    {{ $count = count($array) }}
    {{ $total_old = 0 }}
    {{ $total_new = 0 }}
    {{ $grand_total = 0 }}

    <thead>


            <tr>
                <th style="font-weight: bold">{{ 'FROM: ' . date('m/d/Y', strtotime($from)) . ' TO: ' . date('m/d/Y', strtotime($to)) }}</th>
            </tr>


            <tr>
                <th>MAKE TYPE</th>
                <th>{{ $array[$count - 1][0] }}</th>
                <th>{{ $array[$count - 1][1] }}</th>
                <th>TOTAL</th>
            </tr>



    </thead>

    <tbody>

            @foreach($array as $key=>$value)

                    @if($key != $count - 1)

                            <tr>
                                <td>{{ $value[0] }}</td>
                                <td>{{ $value[1] }}</td>
                                <td>{{ $value[2] }}</td>
                                <td>{{ $value[3] }}</td>
                            </tr>

                        {{ $total_old += $value[1] }}
                        {{ $total_new += $value[2] }}
                        {{ $grand_total += $value[3] }}

                    @endif


            @endforeach

    </tbody>

    <tfoot>
        <tr>
            <td>TOTAL: </td>
            <td>{{ $total_old }}</td>
            <td>{{ $total_new }}</td>
            <td>{{ $grand_total }}</td>
        </tr>
    </tfoot>

</table>

</body>

</html>
