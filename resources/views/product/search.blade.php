@extends('layouts.app')
@section('pageTitle', 'Pesquisa de products')
@section('content')
<div class="container">

		<h3 style="float: left; margin-top: 8px">
			Products found:
		</h3>	
	<div class="row" align="right">		
		<a href="/product" style="float: right; ">
			<button class="btn btn-default" >
				Back
			</button>
		</a>
	</div>
    <div class="row col-md-10 col-md-offset-1 container">
		<table class="table">
			<tr style="background-color: gray; color: white;">
				<th>Name</th>
				<th>Description</th>
				<th>Cost</th>
				<th>Quantity</th>
				<th>Supplier</th>
				<th>Ações</th>
			</tr>
			@foreach($products as $product)
			@if($product->quantity > 0)
			<tr style="background-color: white; color: gray;">
				<td>{{$product->name}}</td>
				<td>{{$product->description}}</td>
				<td>R${{$product->cost}}</td>
				<td>{{$product->quantity}}</td>
				<td>
					@foreach($suppliers as $supplier)
						@if($product->supplier_id == $supplier->id)
							{{$supplier->name}}
						@endif
					@endforeach
				</td>
				<td>
					<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#baixa_product{{$product->id}}">
						<i class="fa fa-minus-square" aria-hidden="true"></i>
					</button>
					<div class="modal fade" id="baixa_product{{$product->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
					<button type="button" class="btn btn-info" data-toggle="modal" data-target="#edit_product{{$product->id}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>

					<!-- MODAL PARA A EDIÇÃO DE PRODUctS -->
					<div class="modal fade" id="edit_product{{$product->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
				</td>
			</tr>
			@endif

			@endforeach
		</table>
	</div>
	<div class="row">
		<div class="row col-md-10 col-md-offset-1">
			<h3 id="SoldOut">
				&nbsp;&nbsp;&nbsp;Sold Out:
			</h3>	
			</div>
	    <div class="row  col-md-10 col-md-offset-1 container">
		<table class="table">
						<tr style="background-color: gray; color: white;">
							<th>Name</th>
							<th>Description</th>
							<th>Cost</th>
							<th>Quantity</th>
							<th>Supplier</th>							
							<th>Ações</th>
						</tr>
						@foreach($products as $product)
						@if($product->quantity == 0)
						<tr style="background-color: white; color: gray;">
							<td>{{$product->name}}</td>
							<td>{{$product->description}}</td>
							<td>R${{$product->cost}}</td>
							<td>{{$product->quantity}}</td>
							<td>
								@foreach($suppliers as $supplier)
									@if($product->supplier_id == $supplier->id)
										{{$supplier->name}}
									@endif
								@endforeach
							</td>
							<td>
								<button type="button" class="btn btn-info" data-toggle="modal" data-target="#edit_product_esgotado{{$product->id}}">
									<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
								</button>

								<!-- MODAL PARA A EDIÇÃO DE PRODUctS -->
								<div class="modal fade" id="edit_product_esgotado{{$product->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
							</td>
						</tr>
						@endif

						@endforeach
					</table>
	</div>

	<div class="modal fade" id="SoldOut" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h3 class="modal-title" id="myModalLabel">Products SoldOut</h3>
				</div>

					
				
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					<button type="button" class="btn btn-primary" data-dismiss="modal">Confirm</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="new_product" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
							<label>supplier</label>
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
</div>
@endsection
