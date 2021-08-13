<html>
<title>PDF Report</title>

<style>

    body {
        font-family: Arial, sans-serif;
    }

    .in_charge {
        position: absolute;
        bottom: 300px;
        right: 0;
        text-align: center;
    }

    .in_charge_name {
        font-weight: bold;
    }

    .in_charge_title {
        font-size: 13px;
    }

    table {
        position: absolute;
        bottom: 270px;
        border-collapse: collapse;
        width: 100%;
    }

    table thead tr th {
        text-align: left;
        border: 2px solid #000;
        font-weight: normal;
        font-size: 13px;
        padding: 5px;
    }
</style>
<body>

    <div class="in_charge">
        <span class="in_charge_name">ANALIZA M. CARUMBA</span><br>
        <span class="in_charge_title">MTFRU-FVR In-charge</span>
    </div>

    <table>
        <thead>
            <tr>
                <th colspan="3" style="text-align: center">
                    City of Pagadian<br>
                    MTFRU Office
                </th>
            </tr>

            <tr>
                <th>
                    Name of Operator:
                </th>
                <th colspan="2" style="font-weight: bold; font-size: 15px">
                    {{ $tricycle->operator }}
                </th>
            </tr>

            <tr>
                <th>
                    Address:
                </th>
                <th colspan="2" style="font-weight: bold; font-size: 15px">
                    {{ $tricycle->address }}
                </th>
            </tr>

            <tr>
                <th>
                    Motor Sidecar No.
                </th>
                <th style="font-weight: bold; font-size: 15px">
                    {{ $tricycle->body_number }}
                </th>
                <th rowspan="3" style="text-align: center; font-size: 12px">
                    <span style="display: block; margin-top: 25px; margin-bottom: 5px; text-decoration: underline;">{{ $tricycle->operator }}</span>
                    <span>Operator Name</span>
                </th>
            </tr>

            <tr>
                <th>
                    Date Register
                </th>
                <th style="font-weight: bold; font-size: 15px">
                    {{  date('m/d/Y') }}
                </th>
            </tr>

            <tr>
                <th>
                    Cellphone Number:
                </th>
                <th style="font-weight: bold; font-size: 15px">
                    {{ $tricycle->mobile }}
                </th>
            </tr>


        </thead>
    </table>
</body>
</html>


