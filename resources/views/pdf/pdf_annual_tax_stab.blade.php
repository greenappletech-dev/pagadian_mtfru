<html>
<title>PDF Report</title>

<style>

    body {
        font-family: Arial, sans-serif;
    }

    .in_charge {
        position: absolute;
        top: 170px;
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
<table>
    <thead>
        <tr>
            <th colspan="3" style="text-align: center;">
                City of Pagadian<br>
                MTFRU Office<br>
                <strong style="font-size: 20px">{{ $stab_info->inc_desc }}</strong>
            </th>
        </tr>

        <tr>
            <th>
                Name of Operator:
            </th>
            <th>
                Body Number
            </th>
            <th>
                Validity Date
            </th>
        </tr>

        <tr>
            <th style="font-weight: bold; font-size: 15px">
                {{ $stab_info->full_name }}
            </th>
            <th style="font-weight: bold; font-size: 15px">
                {{ $stab_info->body_number }}
            </th>
            <th style="font-weight: bold; font-size: 15px">
                {{ date('m/d/Y', strtotime($stab_info->validity_date)) }}
            </th>
        </tr>
    </thead>
</table>

<div class="in_charge">
    <span class="in_charge_name">ANALIZA M. CARUMBA</span><br>
    <span class="in_charge_title">MTFRU-FVR In-charge</span>
</div>
</body>
</html>


