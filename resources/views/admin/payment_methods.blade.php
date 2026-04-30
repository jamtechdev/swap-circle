@extends('layout.admin.list_master')
@section('content')
    <style>
        .btn-light{
          padding-left:10px;
        }
    </style>
    <div class="content-body">
        <div class="container-fluid">
            <div class="page-titles mb-n5">
				<ol class="breadcrumb">
                    @section('titleBar')
                    <span class="ml-2">Swap Offers</span>
                    @endsection
				</ol>
            </div>
            <!-- row -->

            <div class="row">
                <div class="col-12">
                    <a class="btn <?php if($filter == '') { echo 'btn-primary'; }  else { echo "btn-info"; }?>" href="swap_offers" style="color: white; margin-bottom: 20px;">All</a>
                    <a class="btn <?php if($filter == 'Active') { echo 'btn-primary'; }  else { echo "btn-info"; }?>" href="swap_offers?filter=Active" style="color: white; margin-bottom: 20px;">Active</a>
                    <a class="btn <?php if($filter == 'Inactive') { echo 'btn-primary'; }  else { echo "btn-info"; }?>" href="swap_offers?filter=Inactive" style="ccolor: white; margin-bottom: 20px;">Inactive</a>
                    <a class="btn <?php if($filter == 'Deleted') { echo 'btn-primary'; }  else { echo "btn-info"; }?>" href="swap_offers?filter=Deleted" style="color: white; margin-bottom: 20px;">Deleted</a>     
                    <br>

                    <div class="card">
                        <div class="card-body">
                        <legend style="float: right;"><a style="float: right;" class="btn btn-primary" href="{{url('/admin/payment_methods_add')}}"> Add Payment Method </a></legend>
                            <div class="table-responsive">
                                <table id="example" class="table dt-responsive nowrap display min-w850">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($get_data as $key => $data)
                                        <tr class="odd gradeX">
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $data->name }}</td>
                                            <td>{{ $data->type }}</td>
                                            <td>
                                                @if ($data->status=='Active')
                                                <span class="btn btn-success">Active</span>
                                                @elseif ($data->status=='Inactive')
                                                <span class="btn btn-warning">Inactive</span>
                                                @else 
                                                <span class="btn btn-danger">Deleted</span>
                                                @endif
                                            </td>

                                            <td>
                                                <a class="btn btn-secondary" href="{{route('payment_methods_edit',[$data->payment_method_id])}}"> 
                                                    <i class="fa fa-edit"></i> 
                                                </a>
                                                @if ($data->status=='Active')
                                                <a class="btn btn-warning" href="{{url('/admin/payment_methods_update/' . $data->payment_method_id.'/Inactive')}}"> 
                                                    <i class="fa fa-times"></i> 
                                                </a>
                                                @elseif ($data->status=='Inactive')
                                                <a class="btn btn-success" href="{{url('/admin/payment_methods_update/' . $data->payment_method_id.'/Active')}}"> 
                                                    <i class="fa fa-check"></i> 
                                                </a>
                                                @endif

                                                @if ($data->status=='Pending' || $data->status=='Deleted')
                                                <a class="btn btn-warning" href="{{url('/admin/payment_methods_update/' . $data->payment_method_id.'/Inactive')}}"> 
                                                    <i class="fa fa-times"></i> 
                                                </a>

                                                <a class="btn btn-success" href="{{url('/admin/payment_methods_update/' . $data->payment_method_id.'/Active')}}"> 
                                                    <i class="fa fa-check"></i> 
                                                </a>
                                                @endif

                                                @if ($data->status!='Deleted')
                                                <a class="btn btn-danger" href="{{url('/admin/payment_methods_delete/' . $data->payment_method_id)}}"> 
                                                    <i class="fa fa-trash"></i> 
                                                </a>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
          	</div>
        </div>
    </div>
@endsection