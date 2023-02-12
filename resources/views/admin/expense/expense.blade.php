@extends('admin.layouts.app')

<x-assets.datatables />

@push('page-css')
    
@endpush

@push('page-header')
<div class="col-sm-7 col-auto">
	<h3 class="page-title">Expense</h3>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
		<li class="breadcrumb-item active">Expenses</li>
	</ul>
</div>
<div class="col-sm-5 col">
	<a href="#add_expense" data-toggle="modal" class="btn btn-primary float-right mt-2">Add Expense</a>
</div>
@endpush

@section('content')
<div class="row">
	<div class="col-sm-12">
		<div class="card">
			<div class="card-body">
				<div class="table-responsive">
					<table id="expense-table" class="datatable table table-striped table-bordered table-hover table-center mb-0">
						<thead>
							<tr style="boder:1px solid black;">
								<th>Date</th>
								<th>Type</th>
								<th>Amount</th>
								<th class="text-center action-btn">Actions</th>
							</tr>
						</thead>
						<tbody>
												
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>			
</div>

<!-- Add Modal -->
<div class="modal fade" id="add_expense" aria-hidden="true" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Add Expense</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="POST" action="{{route('expenses.store')}}">
					@csrf
					<div class="row form-row">
						<div class="col-6">
							<div class="form-group">
								<label>Date</label>
								<div class="col"><input class="form-control" type="date" id="date" name="date" required=""></div>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Expense Type</label>
								<div class="controls">
									<select name="type" class="form-control" required="">
										<option value="" selected="" disabled="">Select Option</option>		
										<option value="Rent">Rent</option>						
										<option value="Electricity">Electricity</option>	
										<option value="Conveyance">Conveyance</option>	
										<option value="Personal">Personal</option>						
										<option value="Store">Store</option>	
										<option value="Entertaintment">Entertaintment</option>	
										<option value="Others">Others</option>	
									</select>
								{{-- @error('category_id') 
								 <span class="text-danger">{{ $message }}</span>
								 @enderror  --}}
								 </div>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Amount</label>
								<input type="number" name="amount" class="form-control" required="">
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Details</label>
								<input type="text" name="details" class="form-control">
							</div>
						</div>
					</div>
					<button type="submit" class="btn btn-primary btn-block">Save Changes</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /ADD Modal -->

<!-- Edit Details Modal -->
<div class="modal fade" id="edit_expense" aria-hidden="true" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Edit Expense</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="post" action="{{route('expenses.update')}}">
					@csrf
					@method("PUT")
					<div class="row form-row">
						<div class="col-6">
							<input type="hidden" name="id" id="edit_id">
							<div class="form-group">
								<label>Date</label>
								<div class="col"><input class="form-control edit_date" type="date" id="edit_date" name="date" required=""></div>
							</div>
						</div>
						<div class="col-6">
							<div class="form-group">
								<label>Expense Type</label>
								<div class="controls">
									<select name="type" class="form-control edit_type" required="" id="edit_type">
										<option value="" selected="" disabled="">Select Option</option>		
										<option value="Rent">Rent</option>						
										<option value="Electricity">Electricity</option>	
										<option value="Conveyance">Conveyance</option>	
									</select>
								{{-- @error('category_id') 
								 <span class="text-danger">{{ $message }}</span>
								 @enderror  --}}
								 </div>
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Amount</label>
								<input type="number" id="edit_amount" name="amount" class="form-control edit_amount" required="">
							</div>
						</div>
						<div class="col-12">
							<div class="form-group">
								<label>Details</label>
								<input type="text" id="edit_details" name="details" class="form-control edit_details">
							</div>
						</div>
					</div>
					<button type="submit" class="btn btn-primary btn-block">Save Changes</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /Edit Details Modal --> 
@endsection

@push('page-js')
<script>
    $(document).ready(function() {
        var table = $('#expense-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{route('expenses.index')}}",
            columns: [
                {data: 'created_at',name: 'created_at'},
				{data: 'type', name: 'type'},
				{data: 'amount', name: 'amount'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
        $('#expense-table').on('click','.editbtn',function (){
            $('#edit_expense').modal('show');
            var id = $(this).data('id');
            var date = $(this).data('date');
			var type = $(this).data('type');
            var amount = $(this).data('amount');
			var details = $(this).data('details');
            $('#edit_id').val(id);
            $('.edit_date').val(date);
			$('#edit_type').val(type);
            $('.edit_amount').val(amount);
			$('.edit_details').val(details);
        });
        //
    });
</script> 
@endpush