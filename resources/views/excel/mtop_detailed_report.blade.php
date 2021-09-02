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

<table>

    <thead>


        <tr>
            <th>APPLICATION TYPE</th>
            <th>BODY NUMBER</th>
            <th>OPERATOR</th>
            <th>ADDRESS</th>
            <th>CONTACT #</th>
        </tr>



    </thead>

    <tbody>


    {{ $application_type = ''}}


        @foreach($array as $key=>$value)


            @if($application_type != $value[4])

                <tr>
                    <td>{{ $value[4] }}</td>
                </tr>

                {{ $application_type = $value[4] }}
            @endif

            <tr>
                <td></td>
                <td>{{ $value[0] }}</td>
                <td>{{ $value[1] }}</td>
                <td>{{ $value[2] }}</td>
                <td>{{ $value[3] }}</td>
            </tr>


        @endforeach

    </tbody>

    <tfoot>

    </tfoot>

</table>

</body>

</html>
