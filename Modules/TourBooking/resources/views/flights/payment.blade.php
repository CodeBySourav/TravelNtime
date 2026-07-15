@extends('layout_inner_page')
@section('front-content')


<div class="container py-4">

    <div class="row justify-content-center">

        <div class="col-lg-7">

            <div class="card shadow">

                <div class="card-header bg-danger text-white">

                    <h4 class="mb-0">
                        Payment Summary
                    </h4>

                </div>

                <div class="card-body">

                    <table class="table">

                        <tr>
                            <th>Flight Fare</th>
                            <td class="text-end">
                                ₹{{ number_format($publishedFare,2) }}
                            </td>
                        </tr>

                        <tr>
                            <th>Meals</th>
                            <td class="text-end">
                                ₹{{ number_format($mealTotal,2) }}
                            </td>
                        </tr>

                        <tr>
                            <th>Baggage</th>
                            <td class="text-end">
                                ₹{{ number_format($baggageTotal,2) }}
                            </td>
                        </tr>

                        <tr>

                            <th>
                                Total
                            </th>

                            <th class="text-end text-danger">

                                ₹{{ number_format($grandTotal,2) }}

                            </th>

                        </tr>

                    </table>

                    <hr>

                    <h5 class="mb-3">
                        Passenger(s)
                    </h5>

                    @foreach($travellers['travellers'] as $traveller)

                        <div class="border rounded p-3 mb-3">

                            <strong>

                                {{ $traveller['title'] }}

                                {{ $traveller['first_name'] }}

                                {{ $traveller['last_name'] }}

                            </strong>

                            <br>

                            Meal :

                            {{ $traveller['meal_details']['AirlineDescription'] ?? 'None' }}

                            <br>

                            Baggage :

                            {{ $traveller['baggage_details']['Text']
                                ?? ($traveller['baggage_details']['Weight'] ?? 'Standard')
                            }}

                        </div>

                    @endforeach

                    <button
                        id="rzp-button"
                        class="btn btn-danger btn-lg btn-block">

                        Pay ₹{{ number_format($grandTotal,2) }}

                    </button>

                </div>

            </div>

        </div>

    </div>

</div>



<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>

var options = {

    key: "{{ config('services.razorpay.key') }}",

    amount: {{ session('flight.total_amount') * 100 }},

    currency: "INR",

    name: "Travel Booking",

    description: "Flight Booking",

    handler: function (response) {

        let form = document.createElement("form");

        form.method = "POST";

        form.action = "{{ route('flight.payment.success') }}";

        form.innerHTML = `
            @csrf

            <input type="hidden"
                   name="razorpay_payment_id"
                   value="${response.razorpay_payment_id}">
        `;

        document.body.appendChild(form);

        form.submit();

    }

};

var rzp = new Razorpay(options);

document.getElementById('rzp-button').onclick = function(e){

    rzp.open();

    e.preventDefault();

};

</script>
@endsection