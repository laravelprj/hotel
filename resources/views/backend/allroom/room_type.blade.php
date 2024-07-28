@extends('admin.admin_dashboard')
@section('backend') 

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">

        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">

                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Room Type List</li>
                </ol>
            </nav>
        </div>
          <div class="ms-auto">
            <div class="btn-group">
                <a href="{{ route('room.type.add') }}" class="btn btn-primary px-5"> Add Room Type </a>

            </div>
        </div>

    </div>
    <!--end breadcrumb-->
    <h6 class="mb-0 text-uppercase">Room Type List  </h6>
    <hr/>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Sl</th>
                            <th>Image</th>
                            <th>Name</th> 
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                       @foreach ($allData as $key=> $item ) 
                        <tr>
                            <td>{{ $key+1 }}</td>
                            @php
                                $room = App\Models\Room::where('roomtype_id',$item->id)->get();
                                // dd($item->type->image);
                            @endphp
                            <td> 
                            <img src="{{ (!empty($item->type->image)) ? url($item->type->image) :
                             url('upload/no-image.jpg') }}" alt="" style="width: 50px; height:30px;" >   </td>
                            <td>{{ $item->name }}
                            </td> 
                            <td>
                                @foreach ($room as $roo) 
                                <a href="{{ route('room.edit',$roo->id) }}" class="btn btn-warning px-3 radius-30"> Edit</a>
                                <a href=" {{ route('room.delete',$roo->id) }}" class="btn btn-danger px-3 radius-30" id="delete"> Delete</a>
                                @endforeach  
                            </td>
                        </tr>
                        @endforeach 

                    </tbody>

                </table>
            </div>
        </div>
    </div>

    <hr/>

</div>




@endsection