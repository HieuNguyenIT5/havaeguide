@extends('layouts.admin')
@section('content')
<div id="content" class="container-fluid">
    <div class="card">
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
        <div class="card-header font-weight-bold">
            Thêm sản phẩm
        </div>
        <div class="card-body">
            <form action="{{route('admin.school.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="school_code">Mã trường</label>
                            <input class="form-control" type="text" name="school_code" id="product-code">
                            @error('school_code')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="school_name">Tên trường</label>
                            <input class="form-control" type="text" name="school_name" id="name">
                            @error('school_name')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="form-group col-6">
                                <label for="school_address">Địa chỉ</label>
                                <input class="form-control" type="text" name="school_address" id="school_address">
                                @error('school_address')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group col-6">
                                <label for="school_phone">Phone</label>
                                <input class="form-control" type="text" name="school_phone" id="school_phone">
                                @error('school_phone')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="school_email">Email</label>
                            <input class="form-control" type="text" name="school_email" id="school_email">
                            @error('school_email')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="school_website">Website</label>
                            <input class="form-control" type="text" name="school_website" id="school_website">
                            @error('school_website')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="school_description">Mô tả ngắn về trường</label>
                            <textarea id="school_description" name="school_description" class="form-control" cols="30" rows="11"></textarea>
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <label for="school_detail">Thông tin chi tiết trường</label>
                    <textarea name="school_detail" class="form-control" id="school_detail" cols="30" rows="5"></textarea>
                </div>
                <div class="form-group">
                    <label for="image">Logo trường</label>
                    <div>
                        <input type="file" name="school_image" id="school_image" accept="image/gif, image/jpeg, image/png" onchange="loadFile(event)">
                        <div class="avatar-img">
                            <img id="image-show" src="{{asset('images/image_blank.jpg')}}" alt="Ảnh minh họa">
                        </div>
                    </div>
                    @error('image')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="type_id">Hệ đào tạo</label>
                    <select class="form-control" id="" name="type_id">
                        <option value="">Chọn hệ đào tạo</option>
                        @foreach($types as $type)
                        <option value="{{$type->id}}">{{$type->type_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    @php
                            $url = "https://provinces.open-api.vn/api/p";
                            $data = file_get_contents($url);
                            $data = json_decode($data, true);
                    @endphp
                    <label for="area_id">Khu vực</label>
                    <select class="form-control" id="area_id" name="area_id">
                        <option value="">Chọn khu vực</option>
                        
                        @foreach($data as $item)
                        <option value="{{$item['code']}}">{{$item['name']}}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Thêm mới</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section("js")
<script>
    $(function(){
    $("#check_add_many").on("click", function() { 
        $("#add_many").toggle();
    });
});
</script>
@endsection
