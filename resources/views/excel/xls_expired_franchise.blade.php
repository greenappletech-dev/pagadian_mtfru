<table class="table-header">
    <tr>
        <th class="company" colspan="9">MTFRU</th>

    </tr>
    <tr>
        <th class="city" colspan="8">Pagadian City</th>
        <th>User ID: <span class="username">{{ Auth::user()->name }}</span></th>
    </tr>
    <tr>
        <th class="report-title" colspan="8">For Expiration and Expired Franchise</th>
        <th class="datetime">{{ date('m/d/Y h:i:s A') }}</th>
    </tr>
</table>
<table style="width: 100%">
    <thead>

        <tr>
            <th style="text-align: left"></th>
            <th style="text-align: left">BODY NUMBER</th>
            <th style="text-align: left">OPERATOR</th>
            <th style="text-align: left">ADDRESS</th>
            <th style="text-align: left">CONTACT #</th>
            <th style="text-align: center">MAKE TYPE</th>
            <th style="text-align: center">VALIDITY DATE</th>
            <th style="text-align: center">STATUS</th>
        </tr>


    </thead>

    <tbody>

    {{ $count = 1 }}

    @foreach($expired as $data)

        <tr>
            <td style="text-align: left">{{ $count }}</td>
            <td style="text-align: left">{{ $data['body_number'] }}</td>
            <td style="text-align: left">{{ $data['full_name'] }}</td>
            <td style="text-align: left">{{ $data['address1'] }}</td>
            <td style="text-align: left">{{ $data['tel_num'] }}</td>
            <td style="text-align: center">{{ $data['make_type'] }}</td>
            <td style="text-align: center">{{ date('m/d/Y', strtotime($data['validity_date'])) }}</td>
            <td style="text-align: center">{{ $data['status'] }}</td>
        </tr>

        {{ $count++ }}


    @endforeach

    </tbody>

</table>
