@extends('layouts.admin')

@section('title','上传内容')

@section('scripts')
    <script>
        {{-- 如果有的话，在此输出前端立即需要的数据 --}}
    </script>
    @dist('vendor')
    @dist('WoXuanWang.upload.list')
@endsection
