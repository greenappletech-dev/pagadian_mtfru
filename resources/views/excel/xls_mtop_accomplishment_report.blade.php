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

<?php
    $count = 0;
    $transaction_type = array('N' => 'NEW', 'R' => 'RENEWAL', 'CU' => 'CHANGE UNIT', 'T' => 'TRANSFER', 'R|CU' => 'RENEWAL & CHANGE UNIT', 'T|CU' => 'TRANSFER & CHANGE UNIT');
?>

<table style="width: 100%">

    <thead>

        <tr>
            <th style="font-weight: bold">{{ 'FROM: ' . date('m/d/Y', strtotime($from)) . ' TO: ' . date('m/d/Y', strtotime($to)) }}</th>
        </tr>

        <tr>
            <th style="text-align: center; width: 3%">No.</th>
            <th style="text-align: center; width: 8%">MOTOR SIDECAR</th>
            <th style="text-align: left">NAME OF OPERATOR</th>
            <th style="text-align: left">ADDRESS</th>
            <th style="text-align: left; width: 8%">CELL NO</th>
            <th style="text-align: left; width: 8%">MAKE/TYPE</th>
            <th style="text-align: center; width: 8%">DATE OF APPLICATION</th>
            <th style="text-align: center; width: 8%">DATE OF PAYMENT</th>
            <th style="text-align: center; width: 5%">STATUS OF TRANSACTION</th>
        </tr>

    </thead>

    <tbody>

        @foreach($transaction_type as $trans_key => $trans_value)

            <?php $count = 0;  ?>

            <tr>
                <td style="font-size: 15px; font-weight: bold; border-bottom: 1px solid #000; padding-top: 5px; padding-bottom: 5px;" colspan="9">
                    {{ $trans_value }}
                </td>
            </tr>

            @if(isset($array[$trans_key]))

                @foreach($array[$trans_key] as $value)

                    <?php $count++;  ?>

                    <tr>
                        <td style="text-align: center">{{ $count }}</td>
                        <td style="text-align: center">{{ $value['body_number'] }}</td>
                        <td>{{ $value['full_name'] }}</td>
                        <td>{{ $value['address'] }}</td>
                        <td>{{ $value['mobile'] }}</td>
                        <td>{{ $value['make_type'] }}</td>
                        <td style="text-align: center">{{ date_format(new DateTime($value['transact_date']), 'm/d/Y') }}</td>
                        <td style="text-align: center">{{ date_format(new DateTime($value['payment_date']), 'm/d/Y') }}</td>
                        <td style="text-align: center">{{ $value['transact_type'] }}</td>
                    </tr>

                @endforeach

            @endif

            <tr>
                <td style="text-align: center; font-weight: bold; font-size: 13px;" colspan="9">TOTAL COUNT: {{ $count }}</td>
            </tr>

        @endforeach

    </tbody>

</table>

</body>

</html>
