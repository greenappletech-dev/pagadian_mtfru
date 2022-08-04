<!doctype html>
<html lang="en">
<title>Taripa</title>

<style>

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
        height: 795px;
    }

    .operator_id {
        position: absolute;
        top: 145px;
        left: 310px;
        font-size: 20px;
        font-weight: bold;
        width: 470px;
        text-align: center;
    }

    .address {

        position: absolute;
        top: 145px;
        right: 29px;
        width: 300px;
        text-align: center;
        font-weight: bold;

    }

    .body_number {

        position: absolute;
        top: 45px;
        right: 180px;
        color: white;
        font-size: 50px;
        font-weight: bold;

    }

    .operator_image {

        position: absolute;
        top: 25px;
        right: 33px;
        width: 102px;
        height: 98px;

    }



</style>

<body>

    <div style="position:fixed; width: 100%; height: 100%;">



        <img class="background" src="{{ asset('image/forms/taripa-combine.jpg') }}" alt="">


        <div class="body_number">{{ $data['body_number'] }}</div>


        <div class="operator_id">{{ $data['full_name'] }}</div>


        <div class="address">{{ $data['address1'] }}</div>


        <img class="operator_image" src="{{ asset('image/operator_image/' . $data['image']) }}" alt="">


    </div>



</body>
</html>
