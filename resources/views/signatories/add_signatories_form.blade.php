@foreach ($formData as $data)
    <form method="POST" action="{{ url('/save-signatories/' . $data->id) }}">

        @csrf

        <input type="hidden" name="id" value="{{ $data->id }}">

        <div class="picture-container" style="position: relative; text-align: center;">
            <div class="admeasurement_certificate" style="display: inline-block;">
                <img src="{{ asset('image/forms/ADMEASUREMENT.jpg') }}" alt="" style="max-width: 75%; max-height: 100%; vertical-align: middle;">
                <input style="position: absolute; top: 70%; left: 57%;" type="text" value="{{ $data->ac_officer }}" name="ac_officer" placeholder="Admeasurement Officer" class="user-input">
                <input style="position: absolute; top: 88%; left: 57%;" type="text" value="{{ $data->ac_approved }}" name="ac_approved" placeholder="Approved" class="user-input">
                <input style="position: absolute; top: 88%; left: 45%;" type="text" value="{{ $data->ac_noted }}" name="ac_noted" placeholder="Noted By" class="user-input">
                <input style="position: absolute; top: 88%; left: 33%;" type="text" value="{{ $data->ac_verified }}" name="ac_verified" placeholder="Verified By" class="user-input">
            </div>
        </div>

        <div class="picture-container" style="position: relative; text-align: center;">
            <div class="certificate_of_number" style="display: inline-block;">
                <img src="{{ asset('image/forms/CERTIFICATE OF NUMBER.jpg') }}" alt="" style="max-width: 75%; max-height: 100%; vertical-align: middle;">
                <input style="position: absolute; top: 88%; left: 57%;" type="text" value="{{ $data->cn_approved }}" name="cn_approved" placeholder="Approved" class="user-input">
                <input style="position: absolute; top: 88%; left: 45%;" type="text" value="{{ $data->cn_noted }}" name="cn_noted" placeholder="Noted By" class="user-input">
                <input style="position: absolute; top: 88%; left: 33%;" type="text" value="{{ $data->cn_recommending }}" name="cn_recommending" placeholder="Recommending" class="user-input">
            </div>
        </div>

        <div class="picture-container" style="position: relative; text-align: center;">
            <div class="special_certification_of_inspection" style="display: inline-block;">
                <img src="{{ asset('image/forms/INSPECTION.jpg') }}" alt="" style="max-width: 75%; max-height: 100%; vertical-align: middle;">
                <input style="position: absolute; top: 77.5%; left: 55%;" type="text" value="{{ $data->sc_noted }}" name="sc_noted" placeholder="Noted By" class="user-input">
                <input style="position: absolute; top: 88%; left: 45%;" type="text" value="{{ $data->sc_approved }}" name="sc_approved" placeholder="Approved By" class="user-input">
                <input style="position: absolute; top: 77%; left: 33%;" type="text" value="{{ $data->sc_inspected }}" name="sc_inspected" placeholder="Inspection" class="user-input">
            </div>
        </div>

        <div class="picture-container" style="position: relative; text-align: center;">
            <div class="motorboat_operator_license" style="display: inline-block;">
                <img src="{{ asset('image/forms/MOTORBOAT_OPERATOR_LICENSE.jpg') }}" alt="" style="max-width: 75%; max-height: 100%; vertical-align: middle;">
                <input style="position: absolute; top: 83.5%; left: 50%;" type="text" value="{{ $data->mo_approved }}" name="mo_approved" placeholder="Approved By" class="user-input">
                <input style="position: absolute; top: 83.5%; left: 35%;" type="text" value="{{ $data->mo_recommending }}" name="mo_recommending" placeholder="Recommending" class="user-input">
            </div>
        </div>

        <div class="picture-container" style="position: relative; text-align: center;">
            <div class="boat_captain" style="display: inline-block;">
                <img src="{{ asset('image/forms/BOAT_CAPTAIN.jpg') }}" alt="" style="max-width: 75%; max-height: 100%; vertical-align: middle;">
                <input style="position: absolute; top: 83.3%; left: 54%;" type="text" value="{{ $data->bc_approved }}" name="bc_approved" placeholder="Approved By" class="user-input">
                <input style="position: absolute; top: 77.5%; left: 33.5%;" type="text" value="{{ $data->bc_recommending }}" name="bc_recommending" placeholder="Recommending" class="user-input">
            </div>
        </div>

        <div class="picture-container" style="position: relative; text-align: center;">
            <div class="fishing_boat_license" style="display: inline-block;">
                <img src="{{ asset('image/forms/FISHING_BOAT_LICENSE.jpg') }}" alt="" style="max-width: 75%; max-height: 100%; vertical-align: middle;">
                <input style="position: absolute; top: 82.3%; left: 56%;" type="text" value="{{ $data->cf_approved }}" name="cf_approved" placeholder="Approved By" class="user-input">
                <input style="position: absolute; top: 76.5%; left: 33%;" type="text" value="{{ $data->cf_recommending }}" name="cf_recommending" placeholder="Recommending" class="user-input">
            </div>
        </div>

        <div class="picture-container" style="position: relative; text-align: center;">
            <div class="seaaweed_farms" style="display: inline-block;">
                <img src="{{ asset('image/forms/SEAWEED_FARMS.jpg') }}" alt="" style="max-width: 75%; max-height: 100%; vertical-align: middle;">
                <input style="position: absolute; top: 76.2%; left: 58%;" type="text" value="{{ $data->sf_recommending }}" name="sf_recommending" placeholder="Recommending" class="user-input">
                <input style="position: absolute; top: 87%; left: 47%;" type="text" value="{{ $data->sf_approved }}" name="sf_approved" placeholder="Approved By" class="user-input">
                <input style="position: absolute; top: 75%; left: 30.5%;" type="text" value="{{ $data->sf_verified }}" name="sf_verified" placeholder="Verified by" class="user-input">
            </div>
        </div>

        <div class="picture-container" style="position: relative; text-align: center;">
            <div class="pump_boat_registration" style="display: inline-block;">
                <img src="{{ asset('image/forms/MBOL FORM.jpg') }}" alt="" style="max-width: 75%; max-height: 100%; vertical-align: middle;">
                <input style="position: absolute; top: 87%; left: 57%;" type="text" value="{{ $data->pb_certified }}" name="pb_certified" placeholder="Certified by" class="user-input">
            </div>
        </div>

        <div style="text-align: center;">
            <button type="submit" style="padding: 10px 20px; font-size: 16px;">SAVE</button>
        </div>

        @if (session('success'))
            <div class="alert alert-success" style="margin-top: 20px;">
                {{ session('success') }}
            </div>
        @endif

</form>
@endforeach

