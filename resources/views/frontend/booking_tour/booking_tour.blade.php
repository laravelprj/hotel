@extends('frontend.main_master')
@section('frontend')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
        <style>
            th, td {
  /* padding-top: 5px;
  padding-bottom: 3px; */
  padding-left: 5px;
  padding-right: 0px;
}
        </style>
        <!-- Inner Banner -->
        <div class="inner-banner inner-bg7">
            <div class="container">
                <div class="inner-title">
                    <ul>
                        <li>
                            <a href="index.html">Home</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li> Booking Tour</li>
                    </ul>
                    <h3>Booking Tour</h3>
                </div>
            </div>
        </div>
        <!-- Inner Banner End -->

        <!-- Checkout Area -->
		<section class="checkout-area pt-100 pb-70">
			<div class="container">
			<form action="{{ route('tour.store') }}" method="POST">
					@csrf
                    <div class="row">
                        <div class="col-lg-8">
							<div class="billing-details">
								<h3 class="title">Booking Tour</h3>

								<div class="row">

									<div class="col-lg-6 col-md-6">
										<div class="form-group">
											<label>Tour's name<span class="required">*</span></label>
											<input type="text" name="name" class="form-control">
										</div>
									</div>

									<div class="col-lg-6 col-md-6">
										<div class="form-group">
											<label>Total people <span class="required">*</span></label>
											<input type="text" name="persion" class="form-control">
										</div>
									</div>

									<div class="col-lg-6 col-md-6">
										<div class="form-group">
											<label>Email Address <span class="required">*</span></label>
											<input type="email" name="email" class="form-control">
										</div>
									</div>

									<div class="col-lg-6 col-md-6">
										<div class="form-group">
											<label>Phone <span class="required">*</span></label>
											<input type="text" name="phone" class="form-control">
										</div>
									</div>

                                    <div class="col-lg-6 col-md-6">
										<div class="form-group">
											<label>Check in <span class="required">*</span></label>
											<input autocomplete="off"  type="text" required name="check_in" id="check_in"
                                            class="form-control dt_picker" 
                                            value="{{ old('check_in') ? date('Y-m-d', strtotime(old('check_in'))) : '' }}" >
                                            <span class="input-group-addon"></span>
										</div>
                                    </div>
                                    
                                        <div class="col-lg-6 col-md-6">
                                            <div class="form-group">
                                                <label>Check out <span class="required">*</span></label>
                                                <input autocomplete="off"  type="text" required name="check_out" id="check_out"
                                                class="form-control dt_picker" 
                                                value="{{ old('check_out') ? date('Y-m-d', strtotime(old('check_out'))) : '' }}" >
                                                <span class="input-group-addon"></span>
                                            </div>
                                            </div>
                                    

                                    <label>Choose Rooms <span class="required">*</span></label>
                                    @php
                                    $room_ids = []; // Khởi tạo một mảng với một phần tử rỗng
                                    foreach ($rooms as $room) {
                                        $room_ids[] = $room->id; // Thêm phần tử vào mảng mà không cần sử dụng array_push()
                                    }
                                 
                                @endphp
                        <table>
                                    <tr>
                                        
                                    </tr>
                                    <tr>
                                        <th>Room name</th>
                                        <th>Room price</th>
                                        <th style="width: 120px">Quantity</th>
                                        <th style="width: 150px">Price</th>
                                    </tr>
                                    <div class="col-lg-12 col-md-12">
										<div class="row">
                                            
                                            @foreach ($rooms as $room)
                                       <tr><td id="{{ 'text'.$room->id }}"></td></tr>
                                    <tr>
                                            
                                            <td>
                                            {{-- <div class="col-lg-4 col-md-4"> --}}
                                            
                                            <div class="form-group" >
                                                <input readonly type="text" class="form-control" value=" {{ $room->type->name }}">
                                            </div>
                                            {{-- </div> --}}
                                            </td>
                                        <td>
                                        {{-- <div class="col-lg-4 col-md-4"> --}}
                                                <div class="form-group">
                                                    @php
                                                    $price = $room->discount ? ($room->price * (100 - $room->discount) / 100) : $room->price;
                                                @endphp
                                                
                                                <input readonly type="text" data-price ="{{ $price }}" id = '{{ $room->id }}' class="form-control" 
                                                value="Price: {{$price}} {{ $room->discount ? '(discount: '.$room->discount.'%)' : '' }}">
                                                </div>
                                        {{-- </div> --}}
                                         </td>
                                         <td>
                                        {{-- <div class="col-lg-2 col-md-2"> --}}
                                                <div class="form-group">
                                                    <select class="number_rooms" data-sum='{{ "sum".$room->id }}'  data-id = '{{ $room->id }}' name="{{ 'info_room['.$room->id.']' }}">
                                                        <option value="0">Quantity</option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                    </select>
                                                </div>
                                        {{-- </div> --}}
                                       </td>
                                                <td>
                                                {{-- <div class="col-lg-2 col-md-2"> --}}
                                                    <div class="form-group">
                                                    <input readonly type="text" class="price_children form-control" id="{{ "sum".$room->id }}" name="number" class="form-control" 
                                                    value=" 0 ">
                                                    </div>
                                                    {{-- </div> --}}
                                            </td>

                                    </tr>
                                
                                                @endforeach
                        </table> 
                                            
                                        </div>
									</div>
						</div>
                        
                        
                        <div class="col-lg-4">
                            <section class="checkout-area pb-70">
                                <div class="card-body">
                                      <div class="billing-details">
                                            <h3 class="title">Booking Summary</h3>
                                            <hr>
              
                                            <div style="display: flex">
                                                  <img style="height:100px; width:120px;object-fit: cover" src=" " alt="Images" alt="Images">
                                                  <div style="padding-left: 10px;">
                                                        <a href=" " style="font-size: 20px; color: #595959;font-weight: bold">Booking Tour</a>
                                                        
                                                  </div>
              
                                            </div>
              
                                            <br>
              
                                            <table class="table" style="width: 100%">
                                                   
                                                  <tr>
                                                        <td><p>Total Night :</p></td>
                                                        <td id="total_night" style="text-align: right"><p>0</p></td>
                                                        <input type="text" name="total_night"  hidden class="total_night" value="">
                                                  </tr>
                                                  <tr>
                                                        <td><p>Total Room</p></td>
                                                        <td style="text-align: right" id="number_of_rooms"><p>0</p></td>
                                                        <input type="text" name="number_of_rooms" hidden class="number_of_rooms" value="">


                                                  </tr>
                                               
                                                  <tr>
                                                        <td><p>Total</p></td>
                                                        <td style="text-align:right" id="total_price"> <p>Total</p></td>
                                                        <input type="text" name="total_price"  hidden class="total_price" value="">

                                                  </tr>
                                            </table>
              
                                      </div>
                                </div>
                          </section>

						</div>


						<div class="col-lg-8 col-md-8">
							<div class="payment-box">
                                <div class="payment-method">
                                    <p>
                                        <input type="radio" id="paypal" name="radio-group">
                                        <label for="paypal">PayPal</label>
                                    </p>
                                    <p>
                                        <input type="radio" id="cash-on-delivery" name="radio-group">
                                        <label for="cash-on-delivery">Cash On Delivery</label>
                                    </p>
                                </div>

                                <button type="submit" class="order-btn three">Submit</button>
                            </div>
						</div>
					</div>
			</form>
			</div>
		</section>
		<!-- Checkout Area End -->
        {{-- {{ $room_ids = $rooms->id }} --}}
<script>
    $(document).ready(function () {

        $('.number_rooms').change(function (e) { 
            e.preventDefault()
            var values = []
            var total=[]
            const val = $(this).val()
            var get_id = $(this).attr('data-id')
            var room_type_price = $('#'+get_id).attr('data-price')
            var sub = room_type_price*val
            var get_sum = $(this).attr('data-sum')
            var sub_total = $('#'+get_sum).val(sub)


            $('.number_rooms').each(function() {
                values.push(parseFloat($(this).val()) || 0); // Sử dụng || 0 để xử lý trường hợp NaN
            });
            var totalSum = values.reduce((total, current) => {
                return total + parseFloat(current); // Sử dụng parseFloat để chuyển đổi chuỗi thành số
            }, 0);


            $('.price_children').each(function(){
                total.push(parseFloat($(this).val()) || 0);
            })
            var totalPrice = total.reduce((total, current) => {
                return total + parseFloat(current); // Sử dụng parseFloat để chuyển đổi chuỗi thành số
            }, 0);
            


        $('#total_price').text(totalPrice)
        $('.total_price').val(totalPrice)
         
        $('#number_of_rooms').text(totalSum)
        $('.number_of_rooms').val(totalSum)
        
         
        });














        var check_in = $("#check_in").val();;
        var check_out = $("#check_out").val();;
        var room_ids = @json($room_ids);
          
          if (check_in != '' && check_out != ''){
             getAvaility(check_in, check_out, room_ids);
          }
   
          $("#check_out").on('change', function () {
             var check_out = $(this).val();
             var check_in = $("#check_in").val();
             if(check_in != '' && check_out != ''){
                getAvaility(check_in, check_out, room_ids);
             }
          });
    });

    function getAvaility(check_in, check_out, room_ids) {
          $.ajax({
             url: "{{ route('tour.check.room.availability') }}",
             data: {room_ids:room_ids, check_in:check_in, check_out:check_out},
             success: function(data){
                // console.log(data['total_nights']);
                $("#total_night").text(data['total_nights']);
                $(".total_night").val(data['total_nights']);


                Object.entries(data.available_room).forEach(function([key, value]) {
                    
                    $("#text" +key).html('Availability : <span class="text-success">'+value +' Rooms</span>');

                });
             
              
// /                / price_calculate(data['total_nights']);
             }
          });
       }
</script>


@endsection