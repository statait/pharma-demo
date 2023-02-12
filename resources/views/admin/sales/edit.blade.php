@extends('admin.layouts.app')


@push('page-css')
    
@endpush

@push('page-header')
<div class="col-sm-12">
	<h3 class="page-title">View Sale</h3>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
		<li class="breadcrumb-item active">View Sale</li>
	</ul>
</div>
@endpush

@section('content')
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body custom-edit-service">
                <!-- Create Sale -->
                <form  method="POST" action="">
					@csrf
					<div class="row mb-3">
						<div class="col-1"><label>Date</label></div>
						<div class="col-3"><input value="{{ $salePharma->date }}" class="form-control mb-3 edit_date" type="date" id="date" name="date"></div>
					</div>
					<div class="input-field">
						<table class="table table-bordered" id="table_field">
							  <tr>
								  <th>Item Information</th>
								  <th>Stock</th> 
								  <th>Rate</th>
								  <th>Qty</th>
								  <th>Total</th>
								  {{-- <th>Add or Remove</th> --}}
							</tr>
							@foreach($saleItems as $item)
							<tr>
								  <td>
									<select id="item" name="item[]" class="form-control edit_item" required="">
										{{-- <option value="" selected="" disabled="">Select Product</option> --}}
											 <option value="{{ $item->product_id }}">{{ $item->product->purchase->product }}</option>	
											 
									</select>
		
								</td>
								  {{-- <td><input class="form-control" type="text" id="description" name="description[]" required=""></td> --}}
								  <td><input class="form-control stock" type="text" id="stock" name="stock[]" value="{{ $item->product->purchase->quantity }}" required="" readonly ></td>
								  
								  <td><input class="form-control unit_price" type="text" id="unit_cost" name="unit_cost[]" value="{{ $item->price }}" required=""></td>
								  <td><input class="form-control qty"  value="{{ $item->quantity }}" type="text" id="qty" name="qty[]" required=""></td>
								  <td><input class="form-control total" value="{{$item->total_price}}" type="text" id="amount" name="amount[]" value="0" readonly></td>
								  {{-- <td><input class="btn btn-warning" type="button" name="add" id="add" value="Add"></td> --}}
							</tr>
							@endforeach
						</table>
						
							<div class="row">
							<div class="col">
							</div>
		
							<div class="col-4 mt-4">
								<div class="row mb-3">
									<div class="col-4"><label>Sub Total</label></div>
									<div class="col"><span><input value="{{$salePharma->sub_total}}" class="form-control" type="text" name="subtotal" id="subtotal" readonly></span>
									</div>
								</div>
								<div class="row mb-3">
									<div class="col-4"><label>Discount (%)</label></div>
									<div class="col"><input value="{{$salePharma->discount_percentage}}" class="dper form-control" type="number" id="discount-percentage" name="dper">
									</div>
								</div>
								<div class="row mb-3">
									{{-- <div class="col-4"><label>VAT (%)</label></div>
									<div class="col"><input class="vper form-control" type="number" id="vat-percentage" name="">
									</div> --}}
								</div>
								<div class="row mb-3">
									<div class="col-4"><label>Discount (TK)</label></div>
									<div class="col"><input value="{{$salePharma->discount_flat}}" class="dflat form-control" name="dflat" type="number" id="discount-flat">
									</div>
								</div>
								<div class="row mb-3">
									<div class="col-4"><label>Grand Total</label></div>
									<div class="col"><input value="{{$salePharma->grand_total}}" class="form-control" type="text" name="grandtotal" id="grandtotal" readonly>
									</div>
								</div>
								<div class="row mb-3">
									<div class="col-4"><label>Paid Amount</label></div>
									<div class="col"><input value="{{$salePharma->paid}}" class="form-control" type="text" name="paidamount" id="paidamount">
									</div>
								</div>
								<div class="row mb-3">
									<div class="col-4"><label>Change Amount</label></div>
									<div class="col"><input value="{{$salePharma->due}}" class="form-control" type="text" name="dueamount" id="dueamount" readonly>
									</div>
								</div>
		
							
							</div>
						</div>
						
						{{-- <button type="submit" class="btn btn-primary btn-block">Save Changes</button> --}}
			
					</div>
				</form>
                <!--/ Create Sale -->
			</div>
		</div>
	</div>			
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
{{-- 
<script>
	$(document).ready(function(){
		var html='<tr><td><select id="item" name="item[]" class="form-control" required=""><option value="" selected="" disabled="">Select Product</option>@foreach($products as $product)<option data-tokens="{{ $product->purchase->product }}" value="{{ $product->id }}">{{$product->purchase->product }}</option>@endforeach</select></td><td><input class="form-control stock" type="text" id="stock" name="stock[]" required="" readonly></td><td><input class="form-control unit_price" type="text" id="unit_cost" name="unit_cost[]" required=""></td><td><input class="form-control qty" type="text" id="qty" name="qty[]" required=""><td><input class="form-control total" type="text" id="amount" name="amount[]" value="0" readonly></td></td><td><input class="btn btn-danger" type="button" name="remove" id="remove" value="remove"></td></tr>';
		var x =1;
	  $("#add").click(function(){
		$("#table_field").append(html);
	  });
	  $("#table_field").on('click', '#remove', function () {
    $(this).closest('tr').remove();
	});
	
	$("#table_field tbody").on("input", ".unit_price", function () {
                var unit_price = parseFloat($(this).val());
                var qty = parseFloat($(this).closest("tr").find(".qty").val());
                var total = $(this).closest("tr").find(".total");
                total.val(unit_price * qty);
				totalPrice();
            });
	$("#table_field tbody").on("input", ".qty", function () {
		var qty = parseFloat($(this).val());
		var unit_price = parseFloat($(this).closest("tr").find(".unit_price").val());
		var total = $(this).closest("tr").find(".total");
		total.val(unit_price * qty);
		totalPrice();
	});
	// $("#discount-percentage").on("input", ".dper", function () {
	// 	var discount_value = this.value;
	// 	var grandtotal = document.getElementById("grandtotal").value;
	// 	var discount = grandtotal - (discount_value / 100) * grandtotal;
	// 	$("#grandtotal").val(discount);
	// 	console.log(discount);
	// });
	function totalPrice(){
		var sum = 0;
	
		$(".total").each(function(){
		sum += parseFloat($(this).val());
		});
		$("#grandtotal").val(sum);
		$("#subtotal").val(sum);	
	}
	
	document.querySelector('#discount-percentage').addEventListener('input', function() {
		$("#discount-flat").val("");
 		var discount_value = this.value;
		var grandtotal = document.getElementById("subtotal").value;
		var discount = grandtotal - (discount_value / 100) * grandtotal;
		$("#grandtotal").val(discount);
		console.log(discount);
  // Now you can use the inputValue variable to access the value of the input element
	});
	document.querySelector('#discount-flat').addEventListener('input', function() {
		$("#discount-percentage").val("");
 		var discount_value = this.value;
		var grandtotal = document.getElementById("subtotal").value;
		var discount = grandtotal - discount_value;
		$("#grandtotal").val(discount);
		console.log(discount);
  // Now you can use the inputValue variable to access the value of the input element
	});

	// document.querySelector('#vat-percentage').addEventListener('input', function() {
 	// 	var vat_value = this.value;
	// 	var grandtotal = document.getElementById("subtotal").value;
	// 	var vat = ((vat_value / 100) * grandtotal) + parseInt(grandtotal);
	// 	$("#grandtotal").val(vat);
	// 	console.log(vat);
	// });

	$("#mySelect").change(function() {
      // get the selected option value
      var selectedOption = $(this).val();

      // make an AJAX request to the server
      $.get('/get-data', { option: selectedOption }, function(data) {
        // update the field with the response data
        $("#address").val(data.address);
		$("#phone").val(data.phone);
		console.log(data);
      });
    });

	$("#table_field tbody").on("change", "select[name='item[]']", function () {
		var product_id = $(this).val();
		var stock = $(this).closest("tr").find(".stock");
		$.get('/admin/get-data-stock', { option: product_id }, function(data) {
        // update the field with the response data
		console.log(data);
		if(data.quantity == null){
			stock.val(0);
		}else{
			stock.val(data.quantity);
		}
			
      });
		// price.val(product_id);
               
    });

	$("#table_field tbody").on("change", "select[name='item[]']", function () {
		var product_id = $(this).val();
		console.log(product_id);
		var price = $(this).closest("tr").find(".unit_price");
		$.get('/admin/get-price', { option: product_id }, function(data) {
        // update the field with the response data
		if(data.discount == null){
			price.val(data.discount);
		}else{
			price.val(data.price);
		}
			
      });
		// price.val(product_id);
               
    });

	document.querySelector('#paidamount').addEventListener('input', function() {
		$("#dueamount").val("");
 		var paidamount = this.value;
		var grandtotal = document.getElementById("grandtotal").value;
		var duetotal = grandtotal - paidamount;
		$("#dueamount").val(duetotal);
		console.log(discount);
  // Now you can use the inputValue variable to access the value of the input element
	});

	// $("select[name='item[]']").each(function() {
	// 	var selectedOption = $(this).val();
	// 	console.log('hello');
		
	// });
	// $(function() {
	// 	$('.selectpicker').selectpicker();
	// });

	});
</script> --}}

@endsection	


@push('page-js')
    
@endpush