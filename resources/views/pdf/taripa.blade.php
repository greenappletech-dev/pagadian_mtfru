<!doctype html>
<html lang="en">
<title>Taripa</title>

<style>

    @page {
        size: 9in 13in landscape;
        margin: 0;
    }

    @media print {

        img {
            width: 100%;
            height: 100%;

        }

    }


    * {

        margin: 0;
        font-family: Consolas, sans-serif;

    }

    .background {
        position: absolute;
        top: 0;
        width: 100%;
        height: 793px;
    }

    .operator_id {
        position: absolute;
        top: 103px;
        right: 210px;
        font-size: 18px;
        font-weight: bold;
        width: 470px;
        text-align: right;
    }

    .address {

        position: absolute;
        top: 134px;
        right: 215px;
        width: 450px;
        text-align: right;
        font-weight: bold;
        font-size: 13px;
    }

    .mobile {

        position: absolute;
        top: 150px;
        right: 215px;
        width: 450px;
        text-align: right;
        font-weight: bold;
        font-size: 13px;


    }

    .body_number {

        position: absolute;
        top: 15px;
        right: 210px;
        color: #fff;
        font-size: 70px;
        font-weight: bold;

    }

    .operator_image {

        position: absolute;
        top: 7px;
        right: 17px;
        width: 180px;
        height: 153px;

    }



</style>

<body>

    <div style="position:fixed; width: 100%; height: 100%;">



        <img class="background" src="{{ asset('image/forms/taripa-2.jpg') }}" alt="">


        <div class="body_number">{{ $data['body_number'] }}</div>


        <div class="operator_id">{{ $data['full_name'] }}</div>


        <div class="address">{{ $data['address1'] }}</div>

        <div class="mobile">{{ $data['mobile'] }}</div>


        <img class="operator_image" src="{{ asset('image/operator_image/' . $data['image']) }}" alt="">


    </div>



</body>
</html>
