@extends('admin.layouts.app')

<x-assets.datatables />


@push('page-css')
    
@endpush

@push('page-header')
<div class="col-sm-7 col-auto">
	<h3 class="page-title">Expense Reports</h3>
	<ul class="breadcrumb">
		<li class="breadcrumb-item"><a href="{{route('dashboard')}}">Dashboard</a></li>
		<li class="breadcrumb-item active">Generate Expense Reports</li>
	</ul>
</div>
<div class="col-sm-5 col">
	<a href="#generate_report" data-toggle="modal" class="btn btn-primary float-right mt-2">Generate Report</a>
</div>
@endpush

@section('content')
<div class="row">
	<div class="col-md-12">
	
		@isset($expenses)
            <!--  Sales Report -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="expenses-table" class="datatable table table-hover table-center mb-0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Details</th>
                                </tr>
                            </thead>
                            <tbody>
								@php
									$sum = 0;
								@endphp
                                @foreach ($expenses as $expense)
                                        <tr>
											<td>{{date_format(date_create($expense->created_at),"d M, Y")}}</td>
                                            <td>{{$expense->type}}</td>
                                            <td>{{AppSettings::get('app_currency', 'BDT')}} {{($expense->amount)}}</td>
											<td>{{$expense->details}}</td>
                                           <p hidden> {{$sum+= $expense->amount}}</p>
                                        </tr>
                                @endforeach
							
                            </tbody>
							<td class="float-right"><strong>Total </strong> {{ $sum}}</td>
                        </table>
                    </div>
                </div>
            </div>
            <!-- / sales Report -->
        @endisset
       
		
	</div>
</div>

<!-- Generate Modal -->
<div class="modal fade" id="generate_report" aria-hidden="true" role="dialog">
	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Generate Report</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<form method="post" action="{{route('expenses.report')}}">
					@csrf
					<div class="row form-row">
						<div class="col-12">
							<div class="row">
								<div class="col-6">
									<div class="form-group">
										<label>From</label>
										<input type="date" name="from_date" class="form-control from_date">
									</div>
								</div>
								<div class="col-6">
									<div class="form-group">
										<label>To</label>
										<input type="date" name="to_date" class="form-control to_date">
									</div>
								</div>
							</div>
						</div>
					</div>
					<button type="submit" class="btn btn-primary btn-block submit_report">Submit</button>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /Generate Modal -->
@endsection

@push('page-js')
<script>
    $(document).ready(function(){
        $('#expenses-table').DataTable({
			dom: 'Bfrtip',		
			buttons: [
				{
				extend: 'collection',
				text: 'Export Data',
				buttons: [
					{
						extend: 'pdf',
						exportOptions: {
							columns: "thead th:not(.action-btn)"
						}
					},
					{
						extend: 'excel',
						exportOptions: {
							columns: "thead th:not(.action-btn)"
						}
					},
					{
						extend: 'csv',
						exportOptions: {
							columns: "thead th:not(.action-btn)"
						}
					},
					{
						extend: 'print',
						exportOptions: {
							columns: "thead th:not(.action-btn)"
						}
					}
				]
				}
			]
		});
    });
</script>
@endpush