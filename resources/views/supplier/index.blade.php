@extends('layouts.app')
@section('pageTitle', 'Suppliers')
@section('content')
<div class="container">
	<h3  style="float: left; margin-top: 8px">
		Manage suppliers:
	</h3>	
	<div class="row row col-md-7" align="right">		
		
		<button type="button" class="btn btn-default" data-toggle="modal" data-target="#newSupplier" href=""> New Supplier <i class="fa fa-plus" aria-hidden="true"></i>
		</button>
		
		</div>
    <div class="row">
    	<form action="supplier/search" method="get">
		{{ csrf_field()}}
			<div class="input-group" style="margin-right: 15px;">
				<input type="text" name="name" class="form-control" placeholder="Search supplier...">
				<span class="input-group-btn">
				<input type="submit" class="btn btn-info" type="button" value="Search"><i class='fa fa-search' aria-hidden='true'></i>
				</span>
				
			</div>
		</form>
		<br>
		<div class="container col-md-10 col-md-offset-1">
		<table class="table">
			<tr style="background-color: gray; color: white;">
				<th>Name</th>
				<th>CNPJ</th>
				<th>Address</th>
				<th></th>
				<th colspan="3">Ações</th>
			</tr>
			@foreach($suppliers as $supplier)
			<tr style="background-color: white; color: gray;">
				<td>{{$supplier->name}}</td>
				<td>{{$supplier->cnpj}}</td>
				<td colspan="2">{{$supplier->address}}</td>
				<td>
					<button type="button" class="btn btn-info" data-toggle="modal" data-target="#editSupplier{{$supplier->id}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
				</td>
<!-- MODAL EDIT suppliers -->
					<div class="modal fade" id="editSupplier{{$supplier->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
									<h3 class="modal-title" id="myModalLabel">Edit supplier</h3>
								</div>
								<form action="supplier/edit" method="post">
								{{ csrf_field() }}
									<div class="modal-body">
										<div class="form-group">
											<input type="hidden" name="id" value="{{$supplier->id}}">
											<label>Name</label>
											<input type="text" name="name" class="form-control" value="{{$supplier->name}}" placeholder="Name" autofocus>
											<label>CNPJ</label>
											<input type="text" name="cnpj" class="form-control" value="{{$supplier->cnpj}}" placeholder="CNPJ">
											<label>Address</label>
											<input type="text" name="address" class="form-control" value="{{$supplier->address}}" placeholder="Address">
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
										<input type="submit" class="btn btn-primary" value="Confirmar">
									</div>
								</form>
							</div>
						</div>
					</div>			
<!--//MODAL EDIT Supplier-->
				<td>
					<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteSupplier{{$supplier->id}}"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
				</td>						
<!--MODAL DELETE supplier-->
					<div class="modal fade" id="deleteSupplier{{$supplier->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
									<h3 class="modal-title" id="myModalLabel">Edit supplier</h3>
								</div>
								<form action="supplier/delete" method="post">
								{{ csrf_field() }}
									<div class="modal-body">
										<p>Do you want delete this supplier?
											<small>*Only deleted if you do not have products from this supplier)</small>
										</p>
									</div>
									<div class="modal-footer">
										<input type="hidden" name="id" value="{{$supplier->id}}">
										<button type="button" class="btn btn-default" data-dismiss="modal">No</button>
										<input type="submit" class="btn btn-primary" value="Yes">
									</div>
								</form>
							</div>
						</div>
					</div>
<!--//MODAL DELETE supplier-->
				<td>
					<a href="product/supplier/{{$supplier->id}}">
						<button type="button" class="btn btn-default">
							Products
						</button>
					</a>
				</td>
			</tr>

			@endforeach
		</table>
		</div>
	
	</div>
<!--MODAL NEW supplier-->
	<div class="modal fade" id="newSupplier" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h3 class="modal-title" id="myModalLabel">Register new supplier</h3>
				</div>
				<form action="supplier/insert" method="post">
				{{ csrf_field() }}
					<div class="modal-body">
						<div class="form-group">
							<label>Name</label>
							<input type="text" name="name" class="form-control" placeholder="Name" autofocus>
							<label>CNPJ</label>
							<input type="text" name="cnpj" class="form-control" placeholder="CNPJ">
							<label>Address</label>
							<input type="text" name="address" class="form-control" placeholder="Address">
						
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
						<input type="submit" class="btn btn-primary" value="Confirm">
					</div>
				</form>
			</div>
		</div>
	</div>	
<!--//MODAL NEW supplier-->
</div>
@endsection
