@extends('layouts.app')

@section('content')
<div class="container">
	<h3  style="float: left; margin-top: 8px;">
		Manage users:
	</h3>	

	<div  align="right" style="margin-right: 30px;" >
	
		<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newUser">New User <i class="fa fa-plus" aria-hidden="true"></i>
		</button>
		
		</div>
	
    <div class="row col-md-12 container">
<!--TABLE-->		
		<table class="table">
			<tr  style="background-color: gray; color: white;">
				<th>Name</th>
				<th>Email</th>
				<th>Permission</th>
				<th>Status</th>
				<th>Edit</th>
				<th>Edit status</th>
			</tr>
			@foreach($users as $dataUser)
			@if($dataUser->id == $user->id)
			<tr style="background-color: white; color: gray;">
				<td>{{$dataUser->name}}</td>	
				<td>{{$dataUser->email}}</td>
				<td>
					@if($dataUser->permission == 1)
						<span>Administrator</span>
					@else
						<span">Common</span>
					@endif
				</td>
				<td>
					@if($dataUser->status == 'Active')
						<span class="label label-success">Active</span>
					@else
						<span class="label label-default">Disabled</span>
					@endif	
				</td>
				<td>
					<button type="button" class="btn btn-info" data-toggle="modal" data-target="#editUser{{$dataUser->id}}">
						<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
					</button>
					
<!--MODAL EDIT USER-->		
					<div class="modal fade" id="editUser{{$dataUser->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h3 class="modal-title" id="myModalLabel">Edit User{{$dataUser->name}}</h3>
								</div>
								<form action="/user/update" method="post">
								{{ csrf_field() }}
									<div class="modal-body">
										<div class="form-group">
											<input type="hidden" name="id" value="{{$dataUser->id}}">
											<label>Name</label>
											<input type="text" name="name" class="form-control" value="{{$dataUser->name}}" placeholder="Name" autofocus>
											<label>Email</label>
											<input type="email" name="email" class="form-control" value="{{$dataUser->email}}" placeholder="Email">
											<br>
											
											@if($dataUser->id != $user->id)
											@if($dataUser->permission == 1)
											<label>Administrator?</label>
											<div class="radio">
											  	<label><input type="radio" name="permission" value="1" checked>Yes</label>&nbsp;
											  	<label><input type="radio" name="permission" value="0" >No</label>
											</div>
											@else
											<div class="radio">
											  	<label><input type="radio" name="permission" value="1">Yes</label>&nbsp;
											  	<label><input type="radio" name="permission" value="0" checked>No</label>
											</div>
											@endif
											@endif
											
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
<!--//MODAL EDIT USER-->		
				</td>
				<td>
					
				</td>
			</tr>
			@endif
			@endforeach
			@foreach($users as $dataUser)
			@if($dataUser->id != $user->id)
			<tr style="background-color: white; color: gray;">
				<td>{{$dataUser->name}}
					
				</td>
				<td>{{$dataUser->email}}</td>
				<td>
					@if($dataUser->permission == 1)
						<span>Administrator</span>
					@else
						<span>Common</span>
					@endif
				</td>
				<td>
					@if($dataUser->status == 'Active')
						<span class="label label-success">Active</span>
					@else
						<span class="label label-default">Disabled</span>
					@endif	
				</td>
				<td>
					<button type="button" class="btn btn-info" data-toggle="modal" data-target="#editUser{{$dataUser->id}}">
						<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
					</button>

<!--MODAL EDIT USER-->		
					<div class="modal fade" id="editUser{{$dataUser->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
						<div class="modal-dialog" role="document">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									<h3 class="modal-title" id="myModalLabel">Edit User{{$dataUser->id}}</h3>
								</div>
								<form action="/user/update" method="post">
								{{ csrf_field() }}
									<div class="modal-body">
										<div class="form-group">
											<input type="hidden" name="id" value="{{$dataUser->id}}">
											<label>Name</label>
											<input type="text" name="name" class="form-control" value="{{$dataUser->name}}" placeholder="Name" autofocus>
											<label>Email</label>
											<input type="email" name="email" class="form-control" value="{{$dataUser->email}}" placeholder="Email">
											<br>
											
											@if($dataUser->id != $user->id)
											@if($dataUser->permission == 1)
											<label>Administrator</label>
											<div class="radio">
											  	<label><input type="radio" name="permission" value="1" checked>Yes</label>&nbsp;
											  	<label><input type="radio" name="permission" value="0" >No</label>
											</div>
											@else
											<div class="radio">
											  	<label><input type="radio" name="permission" value="1">Yes</label>&nbsp;
											  	<label><input type="radio" name="admin" value="0" checked>Não</label>
											</div>
											@endif
											@endif
											
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
<!--//MODAL EDIT USER-->	

				</td>
				<td>
					@if($dataUser->status == 'Active')
					<form action="user/disable" method="post">
						{{ csrf_field() }}
						<input type="hidden" value="{{ $dataUser->id }}" name="id">
						<input type="submit" value="Disable ):" class="btn btn-danger">
					</form>
					@else
					<form action="user/reactivate" method="post">
						{{ csrf_field() }}
						<input type="hidden" value="{{ $dataUser->id }}" name="id">
						<input type="submit" value="Activate (:" class="btn btn-success">
					</form>
					@endif
				</td>
			</tr>
			@endif
			@endforeach
		</table>
<!--//TABLE-->
	</div>

	<!--MODAL NEW USER-->
	<div class="modal fade" id="newUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h3 class="modal-title" id="myModalLabel">Register new user</h3>
				</div>
				<form action="/user/register" method="post">
				{{ csrf_field() }}
					<div class="modal-body">
						<div class="form-group">
							<label>Name</label>
							<input type="text" name="name" class="form-control" placeholder="Name" autofocus>
							<label>Email</label>
							<input type="email" name="email" class="form-control" placeholder="Email">
							<label>Senha</label>
							<input type="password" name="password" class="form-control" placeholder="Senha">
							<label>Confirme a Senha</label>
							<input type="password" name="password_confirmation" class="form-control" placeholder="Senha">
							<br>
							
							<label>Administrator</label>
							<div class="radio">
							  	<label><input type="radio" name="permission" value="1">Sim</label>&nbsp;
							  	<label><input type="radio" name="permission" value="0" checked>Não</label>
							</div>
							
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
						<input type="submit" class="btn btn-primary" value="Confirmar">
					</div>
				</form>
			</div>
		</div>
	</div>	
<!--//MODAL NEW USER-->

</div>
@endsection
