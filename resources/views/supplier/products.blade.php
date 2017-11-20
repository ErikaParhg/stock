@extends('layouts.app')
@section('pageTitle', 'Products')
@section('content')
<div class="container">

	<script>
		function goBack() {
		    window.history.back();
		}
	</script>

	<div class="row" align="right">
		<h3 style="float: left; margin-top: 8px">
		 {{$bySupplier->name}} ({{$bySupplier->cnpj}})	
		</h3>	
		<a href="#ProductSoldOut">
			<button class="btn btn-info" style="float: right; margin-right: 6px;">
				Sold out
			</button>
		</a>

		<button class="btn btn-default" style="float: right; margin-right: 6px; margin-bottom: 10px" onclick="goBack()">
			Back
		</button>

	</div>
    <div class="row container panel col-md-10 col-md-offset-1">
    	<div class="row">
			<h3 id="´" style="float: left; margin-top: 8px;">
				&nbsp;&nbsp;&nbsp;Products:
			</h3>	
		</div>
		<div class="row" style="margin-left: 0px">
		<table class="table">
			<tr>
				<th>Name</th>
				<th>Description</th>
				<th>Cost</th>
				<th>Quantity</th>
				<th>Ações</th>
			</tr>
			@foreach($products as $product)
			@if($product->quantity > 0)
			<tr>
				<td>{{$product->name}}</td>
				<td>{{$product->description}}</td>
				<td>R${{$product->cost}}</td>
				<td>{{$product->quantity}}</td>
				<td>
					<button type="button" class="btn btn-info" data-toggle="modal" data-target="#unavailableProduct{{$product->id}}">
						Unavailable
					</button>

<!-- MODAL Unavailable PRODUCTS -->					
				<div class="modal fade" id="unavailableProduct{{$product->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
									<h3 class="modal-title" id="myModalLabel">Unavailable product</h3>
								</div>
								<form action="/product/debit" method="post">
								{{ csrf_field() }}
									<div class="modal-body">
										<div class="form-group">
											<input type="hidden" name="id" value="{{$product->id}}">
											<label>Quantity</label>
											<input type="number" name="quantity" min="0" max="{{$product->quantity}}" class="form-control" value="0">
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
<!--//MODAL Unavailable PRODUCTS -->	
					<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#editProduct{{$product->id}}">Edit</button>

<!-- MODAL EDIT PRODUCTS -->
					<div class="modal fade" id="editProduct{{$product->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
									<h3 class="modal-title" id="myModalLabel">Edit product</h3>
								</div>
								<form action="/product/edit" method="post">
								{{ csrf_field() }}
									<div class="modal-body">
										<div class="form-group">
											<input type="hidden" name="id" value="{{$product->id}}">
											<label>Name</label>
											<input type="text" name="name" class="form-control" value="{{$product->name}}" placeholder="Name" autofocus>
											<label>Description</label>
											<input type="text" name="description" class="form-control" value="{{$product->description}}" placeholder="Description">
											<label>Cost</label>
											<input type="text" name="cost" class="form-control" value="{{$product->cost}}" placeholder="Cost">
											<label>Quantity</label>
											<input type="number" name="quantity" min="0" class="form-control" value="{{$product->quantity}}">
											<label>supplier</label>
											<select name="supplier" class="form-control">
												@foreach($suppliers as $supplier)
												<option value="{{$supplier->id}}" {{$product->supplier_id == $supplier->id ? "selected" : ""}}>
													{{$supplier->name}} ({{$supplier->cnpj}})
												</option>
												@endforeach
												
											</select>
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
<!-- //MODAL EDIT PRODUCTS -->
				</td>
			</tr>
			@endif

			@endforeach
		</table>
	</div>
</div>
<!-- PRODUCTS SOLD OUT -->	
	<div class="row container panel col-md-10 col-md-offset-1">
		<div class="row">
			<h3 id="ProductSoldOut" style="float: left; margin-top: 8px;">
				&nbsp;&nbsp;&nbsp;Sold out:
			</h3>	
			</div>
	    <div class="row" style="margin-left: 0px">
					<table class="table">
						<tr>
							<th>Name</th>
							<th>Description</th>
							<th>Cost</th>
							<th>Quantity</th>
							<th colspan="3">Actions</th>
						</tr>
						@foreach($products as $product)
						@if($product->quantity == 0)
						<tr>
							<td>{{$product->name}}</td>
							<td>{{$product->description}}</td>
							<td>R${{$product->cost}}</td>
							<td>{{$product->quantity}}</td>
							<td>
								<button type="button" class="btn btn-info" data-toggle="modal" data-target="#editProductSoldOut{{$product->id}}">
									<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
								</button>

<!-- MODAL EDIT PRODUCTS -->
								<div class="modal fade" id="editProductSoldOut{{$product->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal" aria-label="Close">
													<span aria-hidden="true">&times;</span>
												</button>
												<h3 class="modal-title" id="myModalLabel">Edit product</h3>
											</div>
											<form action="/product/edit" method="post">
											{{ csrf_field() }}
												<div class="modal-body">
													<div class="form-group">
														<input type="hidden" name="id" value="{{$product->id}}">
														<label>Name</label>
														<input type="text" name="name" class="form-control" value="{{$product->name}}" placeholder="Name" autofocus>
														<label>Description</label>
														<input type="text" name="description" class="form-control" value="{{$product->description}}" placeholder="Description">
														<label>Cost</label>
														<input type="text" name="cost" class="form-control" value="{{$product->cost}}" placeholder="Cost">
														<label>Quantity</label>
														<input type="number" name="quantity" min="0" class="form-control" value="{{$product->quantity}}">
														<label>Supplier</label>
														<select name="supplier" class="form-control">
															@foreach($suppliers as $supplier)
															<option value="{{$supplier->id}}" {{$product->supplier_id == $supplier->id ? "selected" : ""}}>
																{{$supplier->name}} ({{$supplier->cnpj}})
															</option>
															@endforeach
															
														</select>
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
<!--//Modal edit Product-->
							</td>
						</tr>
						@endif

						@endforeach
					</table>
	</div>
<!--Modal Product sold out-->
	<div class="modal fade" id="ProductsSoldOut" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h3 class="modal-title" id="myModalLabel">Products sold out</h3>
				</div>

					
				
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<button type="button" class="btn btn-primary" data-dismiss="modal">Confirm</button>
				</div>
			</div>
		</div>
<!--//Modal Product sold out-->

	</div>
<!--Modal NEW Product-->
	<div class="modal fade" id="newPoduct" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h3 class="modal-title" id="myModalLabel">Register new product</h3>
				</div>
				<form action="/product/insert" method="post">
				{{ csrf_field() }}
					<div class="modal-body">
						<div class="form-group">
							<label>Name</label>
							<input type="text" name="name" class="form-control" placeholder="Name" autofocus>
							<label>Description</label>
							<input type="text" name="description" class="form-control" placeholder="Description">
							<label>Cost</label>
							<input type="text" name="cost" class="form-control" placeholder="Cost">
							<label>Quantity</label>
							<input type="number" name="quantity" min="0" class="form-control">
							<label>Supplier</label>
							<select name="supplier" class="form-control">
								<option value="" selected>Select</option>

								@foreach($suppliers as $supplier)
								<option value="{{$supplier->id}}">
									{{$supplier->name}} ({{$supplier->cnpj}})
								</option>
								@endforeach

							</select>
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

<!--..Modal NEW product-->
</div>
@endsection
