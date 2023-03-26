@extends('layouts.admin')
@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Sửa thông tin sản phẩm
        </div>
        <div class="card-body">
            <form action="{{route('admin.product.update', $product->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="name">Tên sản phẩm</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{$product->name}}">
                            @error('name')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="form-group col-6">
                                <label for="price">Giá</label>
                                <input class="form-control" type="text" name="price" id="price" value="{{$product->price}}">
                                @error('price')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                            <div class="form-group col-6">
                                <label for="new_price">Giá khuyến mãi</label>
                                <input class="form-control" type="text" name="new_price" id="new_price" value="{{$product->new_price}}">
                                @error('new_price')
                                <small class="text-danger">{{$message}}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="quantity">Số lượng</label>
                            <input class="form-control" type="text" name="quantity" id="quantity" value="{{$product->quantity}}">
                            @error('quantity')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="description">Mô tả sản phẩm</label>
                            <textarea name="description" class="form-control" id="description" cols="30" rows="8">{{$product->description}}</textarea>
                        </div>
                    </div>
                </div>


                <div class="form-group">
                    <label for="intro">Chi tiết sản phẩm</label>
                    <textarea name="product_detail" class="form-control" id="product_detail" cols="30" rows="5">{{$product->product_detail}}</textarea>
                </div>
                <div class="form-group">
                    <label for="image">Ảnh minh họa</label>
                    <div>
                        <input type="file" name="image" id="image" accept="image/gif, image/jpeg, image/png" onchange="loadFile(event)">
                        <div class="avatar-img">
                            <img id="image-show" src="{{asset('images/'.$product->image)}}" alt="Ảnh minh họa">
                        </div>
                    </div>
                    @error('image')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="cat_id">Danh mục</label>
                    <select class="form-control" id="" name="cat_id">
                        @foreach($cat_list as $key=>$item)
                        <option value="{{$key}}" {{$product->cat_id == $key ? 'selected' : ''}} >{{$item}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="brand_id">Thương hiệu</label>
                    <select class="form-control" id="brand-id" name="brand_id">
                        @foreach($brands as $item)
                        <option value="{{$item->id}}" {{$product->brand_id == $item->id ? "selected" : ''}}>{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </form>
        </div>
    </div>
</div>
@endsection