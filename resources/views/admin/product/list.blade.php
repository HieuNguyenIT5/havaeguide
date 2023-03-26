@extends('layouts.admin')
@section('content')
<div id="content" class="container-fluid">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{session('success')}}
    </div>
    @endif
    @if(session('danger'))
    <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        {{session('danger')}}
    </div>
    @endif
    <div class="card">
        <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
            <h5 class="m-0 ">Danh sách sản phẩm</h5>
            <div class="form-search form-inline">
                <form action="#">
                    <input type="text" name="keyword" class="form-control form-search" placeholder="Tìm kiếm">
                    <input type="submit" class="btn btn-primary">
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="analytic">
                <a href="{{route('admin.product.index')}}" class="text-primary">Tất cả<span class="text-muted">({{$count['all_pro']}})</span></a>
                <a href="{{route('admin.product.list.status', 'active')}}" class="text-primary">Hoạt động<span class="text-muted">({{$count['pro_active']}})</span></a>
                <a href="{{route('admin.product.list.status', 'del')}}" class="text-primary">Ẩn<span class="text-muted">({{$count['pro_remove']}})</span></a>
            </div>
            <form action="{{route('admin.product.action')}}">
                @csrf
            <div class="form-action form-inline py-3">
                <select class="form-control mr-1" id="" name="act">
                    <option >Chọn</option>
                    @foreach($list_act as $key=>$item)
                        <option value="{{$key}}">{{$item}}</option>
                    @endforeach
                </select>
                <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
            </div>
            <table class="table table-striped table-checkall">
                <thead>
                    <tr>
                        <th scope="col">
                            <input name="checkall" type="checkbox">
                        </th>
                        <th scope="col">#</th>
                        <th scope="col">Ảnh</th>
                        <th scope="col">Tên sản phẩm</th>
                        <th scope="col">Giá bán</th>
                        <th scope="col">Danh mục</th>
                        <th scope="col">Ngày tạo</th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col">Tác vụ</th>
                    </tr>
                </thead>
                <tbody>
                    @if($products->total() > 0)
                    @php
                    $count = 0;
                    @endphp
                    @foreach($products as $product)
                    @php
                    $count++;
                    @endphp
                    <tr class="">
                        <td>
                            <input type="checkbox" name="list_check[]" value="{{$product->id}}">
                        </td>
                        <td>{{$count}}</td>
                        <td><img style="width:80px; height:80px" src="{{asset('images/'.$product->image)}}" alt=""></td>
                        <td><a href="#">{{$product->name}}</a></td>
                        <td>{{number_format($product->price, 0, '.', ',').' VND'}}</td>
                        <td>{{$product->cat_name}}</td>
                        <td>{{$product->created_at}}</td>
                        @if($product->status == "Còn hàng")
                        <td><span class="badge badge-success p-2">{{$product->status}}</span></td>
                        @else
                        <td><span class="badge badge-danger p-2">{{$product->status}}</span></td>
                        @endif
                        <td>
                            @if($product->deleted_at == null)
                            <a href="{{route('admin.product.edit', $product->id)}}" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Chỉnh sửa"><i class="fa fa-edit"></i></a>

                            <a href="{{ route('admin.product.remove', $product->id) }}" onclick="return confirm('Bạn có chắc chắn muốn ẩn sản phẩm này không?')" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Vô hiệu hóa"><i class="fa-solid fa-eye"></i></a>
                            @else
                            <a href="{{ route('admin.product.restore', $product->id) }}" onclick="return confirm('Bạn có hiển thị lại sản phẩm này không?')" class="btn btn-warning btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Khôi phục"><i class="fa-solid fa-eye-slash"></i></a>
                            <a href="{{ route('admin.product.delete', $product->id) }}" onclick="return confirm('Bạn có chắc chắn muốn xóa vĩnh viễn sản phẩm này không?')" class="btn btn-danger btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Xóa vĩnh viễn"><i class="fa-solid fa-trash"></i></a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td colspan="9">Không tìm thấy bản ghi nào</td>
                    </tr>
                    @endif
                </tbody>
            </table>
            </form>
            <div>
                {{$products->links()}}
            </div>
        </div>
    </div>
</div>
@endsection
