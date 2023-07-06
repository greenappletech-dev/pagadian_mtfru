<!doctype html>
<html lang="en">
<title>Taripa</title>

<style>

    @page {
        size: 8.5in 13in landscape;
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
        height: 816px;
    }

    .operator_id {
        position: absolute;
        top: 115px;
        right: 185px;
        font-size: 18px;
        font-weight: bold;
        width: 470px;
        text-align: right;
    }

    .address {

        position: absolute;
        top: 148px;
        right: 185px;
        width: 450px;
        text-align: right;
        font-weight: bold;
        font-size: 13px;
    }

    .mobile {

        position: absolute;
        top: 163px;
        right: 185px;
        width: 450px;
        text-align: right;
        font-weight: bold;
        font-size: 13px;


    }

    .body_number {

        position: absolute;
        top: 25px;
        right: 190px;
        color: #000;
        font-size: 68px;
        font-weight: bold;

    }

    .operator_image {

        position: absolute;
        top: 26px;
        right: 32px;
        width: 140px;
        height: 140px;
        border: 1px solid #000;

    }



</style>

<body>

    <div style="position:fixed; width: 100%; height: 100%;">


{{--        <img class="background" src="{{ asset('image/forms/taripa-2.jpg') }}" alt="">--}}


        <div class="body_number">{{ $data['body_number'] }}</div>


        <div class="operator_id">{{ $data['full_name'] }}</div>


        <div class="address">{{ $data['address1'] }}</div>

        <div class="mobile">{{ $data['mobile'] }}</div>


        <img class="operator_image" src="{{ asset('image/operator_image/' . $data['image']) }}" alt="">


    </div>



</body>
</html>
