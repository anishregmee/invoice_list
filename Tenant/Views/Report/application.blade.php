@extends('layouts.tenant')
@section('title', 'Report Application')
@section('breadcrumb')
    @parent
    <li><a href="{{url('tenant/clients')}}" title="All Clients"><i class="fa fa-users"></i> Clients</a></li>
    <li>Notes</li>
@stop

@section('content')
  
  	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="box box-default">
					<div class="box-header">
						<div class="box-title col-md-12">
							<h1>Application - <small>Invoice Reports</small></h1>
							<hr>
							<section class="small">
						        {!! Form::open(['class'=>'form-inline']) !!}
						              
						            <div class="form-group">
							            {!! Form::label('filter_id', 'Filter By:') !!}
							                <select name="filter_id", class="form-control">
							                    <option value="1">College Name</option>
							                    <option value="2">Email</option>
							                    <option value="3">Phone</option>
							                </select>
						            </div>
						            
							        <div class="form-group">
							            {!! Form::label('search_for', 'Search for:') !!}
							            {!! Form::text('search_for', '',['placeholder'=>'search here..', 'class'=>'form-control'] ) !!}
							        </div>
						              
						            <div class="form-group">
						                {!! Form::submit('Search', ['class'=>'btn btn-success']) !!}
						            </div>
						            {!! Form::close() !!}
		        			</section>
		        			<br>
							<h2>College Invoice</h2>
							<hr>
						</div>
					</div>
					<div class="box-body">
						<table class="table table-striped table-bordered">
							<thead>
				              <tr>
				                <th>Id</th>
				                <th>Invoice Date</th>
				                <th>Client Name</th>
				                <th>Invoice To</th>
				                <th>Institute Name</th>
				                <th>Course Name</th>
				                <th>Intake</th>
				                <th>Commission</th>
				                <th>GST</th>
				                <th>Outstanding</th>
				                <th>Processing</th>
				              </tr>
				            </thead>
				            <tbody>
				              <tr>
				                <td>Col001</td>
				                <td>12/10/2014</td>
				                <td>Jenish Maskey</td>
				                <td>University Of Ballart</td>
				                <td>University Of Ballart</td>
				                <td>Bachelors of IT</td>
				                <td>1</th>
				                <td>8000</td>
				                <td>500</td>
				                <td>200</td>
				                <td>
				                	<a href="{{ route('report.application')}}" title="view"><i class="processing btn btn-primary btn-sm glyphicon glyphicon-eye-open" data-toggle="tooltip" data-placement="top" title="View"></i></a>
                    				<a href="#" title="edit"><i class="processing btn btn-primary btn-sm glyphicon glyphicon-edit" data-toggle="tooltip" data-placement="top" title="Edit"></i></a>
                    				<a href="#" title="delete"><i class="processing btn btn-primary btn-sm glyphicon glyphicon-trash" data-toggle="tooltip" data-placement="top" title="Delete"></i></a>
                    				<a href="#" title="make payment"><i class="processing btn btn-primary btn-sm glyphicon glyphicon-piggy-bank" data-toggle="tooltip" data-placement="top" title="Make Payment"></i></a>
                    				<a href="#" title="print invoice"><i class="processing btn btn-primary btn-sm glyphicon glyphicon-print" data-toggle="tooltip" data-placement="top" title="Print Invoice"></i></a>
                    				<a href="#" title="email invoice"><i class="processing btn btn-primary btn-sm glyphicon glyphicon-envelope" data-toggle="tooltip" data-placement="top" title="Email Invoice"></i></a>
				                </td>
				              </tr>
				              <tr>
				                <td>Col002</td>
				                <td>13/10/2014</td>
				                <td>Eccha Dangol</td>
				                <td>Expert Education</td>
				                <td>TAFE</td>
				                <td>Diploma of Design</td>
				                <td>2</th>
				                <td>3000</td>
				                <td>300</td>
				                <td>1000</td>
				                <td>
				                	<a href="{{ route('report.application')}}" title="view"><i class="processing btn btn-primary btn-sm glyphicon glyphicon-eye-open" data-toggle="tooltip" data-placement="top" title="View"></i></a>
                    				<a href="#" title="edit"><i class="processing btn btn-primary btn-sm glyphicon glyphicon-edit" data-toggle="tooltip" data-placement="top" title="Edit"></i></a>
                    				<a href="#" title="delete"><i class="processing btn btn-primary btn-sm glyphicon glyphicon-trash" data-toggle="tooltip" data-placement="top" title="Delete"></i></a>
                    				<a href="#" title="make payment"><i class="processing btn btn-primary btn-sm glyphicon glyphicon-piggy-bank" data-toggle="tooltip" data-placement="top" title="Make Payment"></i></a>
                    				<a href="#" title="print invoice"><i class="processing btn btn-primary btn-sm glyphicon glyphicon-print" data-toggle="tooltip" data-placement="top" title="Print Invoice"></i></a>
                    				<a href="#" title="email invoice"><i class="processing btn btn-primary btn-sm glyphicon glyphicon-envelope" data-toggle="tooltip" data-placement="top" title="Email Invoice"></i></a>
				                </td>
				              </tr>
				            </tbody>
						</table>	
					</div>
				</div>
			</div>
		</div>
	</div>
@stop