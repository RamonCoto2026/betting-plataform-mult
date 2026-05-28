@extends('admin.layouts.app')

@section('panel')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">@lang('Edit Sport')</h5>
                </div>
                <form action="{{ route('admin.sports.update', $sport->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-control-label">@lang('Sport Name') <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" value="{{ $sport->name }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">@lang('Slug') <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="slug" value="{{ $sport->slug }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">@lang('Icon')</label>
                            <input type="text" class="form-control" name="icon" value="{{ $sport->icon }}" placeholder="@lang('e.g. fas fa-football')">
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">@lang('Image')</label>
                            @if($sport->image)
                                <div class="mb-2">
                                    <img src="{{ asset('assets/images/sports/' . $sport->image) }}" alt="{{ $sport->name }}" style="max-height: 150px;">
                                </div>
                            @endif
                            <input type="file" class="form-control" name="image" accept="image/*">
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">@lang('Description')</label>
                            <textarea class="form-control" name="description" rows="4">{{ $sport->description }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">@lang('Display Order')</label>
                            <input type="number" class="form-control" name="order" value="{{ $sport->order }}">
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">
                                <input type="checkbox" name="status" value="1" {{ $sport->status ? 'checked' : '' }}>
                                @lang('Enable this sport')
                            </label>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn--primary btn-block">@lang('Update Sport')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
