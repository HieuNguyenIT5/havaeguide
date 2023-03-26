@extends('layouts.admin')
@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Thêm loại sản phẩm
        </div>
        <div class="card-body">
            <form action="{{url('admin/product/cat/store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name">Tên danh mục</label>
                    <input class="form-control" type="text" name="name" id="name">
                    @error('name')
                        <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group ">
                    <label for="">Mô tả</label>
                    <textarea name="description" id="description" class="form-control"></textarea>
                </div>
                <div class="form-group">
                    <label for="image">Ảnh minh họa</label>
                    <div>
                        <input type="file" name="image" id="image" accept="image/gif, image/jpeg, image/png" onchange="loadFile(event)">
                        <div class="avatar-img">
                        <img id="image-show" src="{{asset('images/image_blank.jpg')}}" alt="Ảnh minh họa">
                        </div>
                    </div>
                    @error('image')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="id_partent">Danh mục cha</label>
                    <select name="id_parent" class="form-control">
                        <option value="0">Danh mục gốc</option>
                        @foreach($cat_level as $key=>$item)
                        <option value="{{$key}}">{{$item}}</option>
                        @endforeach
                    </select>
                </div>
                
                <button type="submit" name="btnCreate" value="Thêm mới" class="btn btn-primary">Thêm mới</button>
            </form>
        </div>
    </div>
</div>
@endsection
