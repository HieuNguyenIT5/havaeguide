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
            <h5 class="m-0 ">Danh sách thành viên</h5>
            <div class="form-search form-inline">
                <form action="">
                    <input type="" class="form-control form-search" name="keyword" placeholder="Tìm kiếm" value="{{request()->input('keyword')}}">
                    <input type="submit" name="" value="Tìm kiếm" class="btn btn-primary">
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="analytic">
                <a href="{{route('admin.product.cat.index')}}" class="text-primary">Tất cả<span class="text-muted">({{$count['all_cat']}})</span></a>
                <a href="{{route('admin.product.cat.list.status', 'active')}}" class="text-primary">Hoạt động<span class="text-muted">({{$count['cat_active']}})</span></a>
                <a href="{{route('admin.product.cat.list.status', 'del')}}" class="text-primary">Vô hiệu hóa<span class="text-muted">({{$count['cat_remove']}})</span></a>
            </div>
            <form action="{{url('admin/user/action')}}" method="">
                <div class="d-flex w-100 justify-content-between">
                    <div class="form-action form-inline py-3">
                        <select class="form-control mr-1" id="" name="act">
                            <option>Chọn</option>
                            @foreach($list_act as $key=>$act)
                            <option value="{{$key}}">{{$act}}</option>
                            @endforeach
                        </select>
                        <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
                    </div>
                    <div class="form-action form-inline py-3">
                        <a href="{{route('admin.product.cat.create')}}" class="btn btn-primary">Thêm mới</a>
                    </div>
                </div>

                <table class="table table-striped table-checkall">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" name="checkall">
                            </th>
                            <th scope="col">Ảnh minh họa</th>
                            <th scope="col">Tên danh mục</th>
                            <th scope="col" class="w-50">Description</th>
                            <th scope="col">Danh mục cha</th>
                            <th scope="col">Tác vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($cats->total() > 0)
                        @foreach($cats as $cat)
                        <tr>
                            <td>
                                <input type="checkbox" name="list_check[]" value="{{$cat->id}}">
                            </td>
                            <td><img style="width:80px; height:80px" src="{{asset('images/'.$cat->image)}}" alt=""></td>
                            <td>{{$cat->name}}</td>
                            <td>{{$cat->description}}</td>
                            @if($cat->id_parent == 0)
                            <td>Danh mục gốc</td>
                            @else
                            <td>{{$catName[$cat->id_parent]}}</td>
                            @endif
                            <td>
                                @if($cat->deleted_at == null)
                                <a href="{{route('admin.product.cat.edit', $cat->id)}}" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Chỉnh sửa"><i class="fa fa-edit"></i></a>
                                <a href="{{ route('admin.product.cat.remove', $cat->id) }}" onclick="return confirm('Bạn có chắc chắn muốn vô hiệu hóa danh mục này không?')" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Vô hiệu hóa"><i class="fa-solid fa-eye"></i></a>
                                @else
                                <a href="{{ route('admin.product.cat.restore', $cat->id) }}" onclick="return confirm('Bạn có muốn khởi động lại danh mục này không?')" class="btn btn-warning btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Khôi phục"><i class="fa-solid fa-eye-slash"></i></a>
                                <a href="{{ route('admin.product.cat.delete', $cat->id) }}" onclick="return confirm('Bạn có muốn xóa hẳn danh mục này không?')" class="btn btn-danger btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Xóa vĩnh viễn"><i class="fa-solid fa-trash"></i></a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="7">Không tìm thấy bản ghi nào</td>
                        </tr>
                        @endif

                    </tbody>
                </table>
            </form>
            {{ $cats->links() }}

        </div>
    </div>
</div>
@endsection