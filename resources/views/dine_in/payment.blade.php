<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Love Bite - Dine In Token</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800 flex items-center justify-center min-h-screen p-4">

    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-br from-orange-500 to-amber-500 p-8 text-center text-white">
            <h1 class="display-font text-3xl mb-1">Order Placed!</h1>
            <p class="text-orange-100 text-sm font-medium">Your kitchen token is ready.</p>
            
            <div class="mt-6 mb-2">
                <p class="text-xs text-orange-200 uppercase tracking-widest font-bold mb-1">Token Number</p>
                <div class="bg-white rounded-2xl py-4 px-6 inline-block shadow-lg">
                    <span class="text-5xl font-black tracking-wider display-font text-orange-600">{{ $order->order_number }}</span>
                </div>
            </div>
            <p class="text-xs text-orange-100 mt-4">Please show this token when your food is served.</p>
        </div>

        <div class="p-6 space-y-6">
            <div>
                <h3 class="text-sm font-bold text-gray-900 mb-3 uppercase tracking-wider">Order Summary</h3>
                <div class="space-y-3">
                    @foreach($order->items as $item)
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600"><span class="font-bold text-gray-900">{{ $item->quantity }}x</span> {{ $item->food->name ?? 'Item' }}</span>
                            <span class="font-semibold text-gray-900">₹{{ number_format((float) ($item->price * $item->quantity), 0) }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 pt-4 border-t border-dashed border-gray-200 flex justify-between items-center">
                    <span class="text-sm text-gray-500 font-bold">Total Amount</span>
                    <span class="text-2xl font-black text-orange-600 display-font">₹{{ number_format((float) $order->total_amount, 0) }}</span>
                </div>
            </div>

            <div class="space-y-3 pt-2">
                @if($razorpayOptions)
                    <button id="rzp-button" class="w-full flex items-center justify-center gap-2 py-4 bg-gray-900 hover:bg-black text-white rounded-xl font-bold transition shadow-lg shadow-gray-900/20 active:scale-[0.98]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Pay ₹{{ number_format((float) $order->total_amount, 0) }} Now
                    </button>
                    <p class="text-center text-xs text-gray-500 font-medium">or pay via cash at the counter</p>

                    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
                    <script>
                        const rzpOptions = {!! json_encode($razorpayOptions) !!};
                        rzpOptions.handler = function (response) {
                            // Automatically submit success to backend
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '{{ route("dine-in.payment.success") }}';
                            
                            const csrf = document.createElement('input'); csrf.type = 'hidden'; csrf.name = '_token'; csrf.value = '{{ csrf_token() }}';
                            const orderId = document.createElement('input'); orderId.type = 'hidden'; orderId.name = 'order_id'; orderId.value = rzpOptions.pending_order_id;
                            const paymentId = document.createElement('input'); paymentId.type = 'hidden'; paymentId.name = 'payment_id'; paymentId.value = response.razorpay_payment_id;
                            const rzpOrderId = document.createElement('input'); rzpOrderId.type = 'hidden'; rzpOrderId.name = 'razorpay_order_id'; rzpOrderId.value = response.razorpay_order_id || '';
                            
                            form.append(csrf, orderId, paymentId, rzpOrderId);
                            document.body.appendChild(form);
                            form.submit();
                        };
                        
                        const rzp = new Razorpay(rzpOptions);
                        document.getElementById('rzp-button').onclick = function(e){
                            e.preventDefault();
                            rzp.open();
                        }
                    </script>
                @else
                    <div class="p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl text-center text-sm font-bold">
                        Payment Successful!
                    </div>
                @endif
                
                <a href="{{ route('dine-in.menu') }}" class="w-full flex items-center justify-center py-4 bg-orange-50 hover:bg-orange-100 text-orange-700 rounded-xl font-bold transition">
                    Order More Items
                </a>
            </div>
        </div>
    </div>

</body>
</html>
