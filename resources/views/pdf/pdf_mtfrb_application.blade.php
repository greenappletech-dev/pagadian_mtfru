<html>
<title>MTFRB Application Form</title>
<header>
    <style>
        img {

        }

        span {
            font-family: Arial, sans-serif;
            font-weight: bold;
        }

         .operator_name {
            width: 350px;
            text-align: center;
            position: absolute;
            top: 103px;
            left: -20px;
             font-size: 20px;
        }

         .mtfrb_case_no {
             width: 190px;
             text-align: center;
             position: absolute;
             top: 101px;
             right: -20px;
             font-size: 22px;
         }

         .body_number {
             width: 190px;
             text-align: center;
             position: absolute;
             top: 153px;
             right: -20px;
             font-size: 30px;
         }

         .address {
             width: 767px;
             text-align: center;
             position: absolute;
             top: 362px;
             left: -20px;
             font-size: 18px;
         }

         .make_type {
             width: 178px;
             text-align: center;
             position: absolute;
             top: 472px;
             left: -20px;
             font-size: 18px;
         }

         .engine_motor_no {
             width: 178px;
             text-align: center;
             position: absolute;
             top: 472px;
             left: 176px;
             font-size: 18px;
         }

        .chassis_no {
            width: 178px;
            text-align: center;
            position: absolute;
            top: 472px;
            left: 373px;
            font-size: 18px;
        }

        .plate_no {
            width: 178px;
            text-align: center;
            position: absolute;
            top: 472px;
            right: -23px;
            font-size: 18px;
        }

        .date_signed {
            width: 283px;
            text-align: center;
            position: absolute;
            top: 682px;
            left: 178px;
            font-size: 15px;
        }

        .name_signature {
            width: 350px;
            text-align: center;
            position: absolute;
            top: 753px;
            right: -25px;
            font-size: 15px;
        }

        .declaration {
            width: 393px;
            text-align: center;
            position: absolute;
            top: 880px;
            left: 2px;
            font-size: 15px;
        }

        .declaration_signature {
            width: 350px;
            text-align: center;
            position: absolute;
            top: 965px;
            right: -25px;
            font-size: 15px;
        }

        .series-year {
            position: absolute;
            bottom: 75px;
            left: 42px;
            font-size: 14px;
        }

        .chairman {
            position: absolute;
            bottom: 20px;
            right: 10px;
            font-size: 13px;
        }




    </style>
</header>
<body>

    <img style="width: 815px; position: absolute; top: -45px; left: -45px;" src="{{ asset('image/forms/MTFRB_FORM_1.jpg') }}" alt="">

    <span class="operator_name">{{ $data[0]['full_name'] }}</span>
    <span class="mtfrb_case_no">{{ $data[0]['mtfrb_case_no'] }}</span>
    <span class="body_number">{{ $data[0]['body_number'] }}</span>
    <span class="address">{{ $data[0]['address'] }}</span>

    <span class="make_type">{{ $data[0]['make_type'] }}</span>
    <span class="engine_motor_no">{{ $data[0]['engine_motor_no'] }}</span>
    <span class="chassis_no">{{ $data[0]['chassis_no'] }}</span>
    <span class="plate_no">{{ $data[0]['plate_no'] }}</span>

    <span class="date_signed">{{ date('F d, Y', strtotime($data[0]['transact_date'])) }}</span>
    <span class="name_signature">{{ $data[0]['full_name'] }}</span>

    <span class="declaration">{{ $data[0]['full_name'] }}</span>
    <span class="declaration_signature">{{ $data[0]['full_name'] }}</span>
    <span class="series-year">{{ date('Y', strtotime($data[0]['transact_date'])) }}</span>


</body>
</html>

