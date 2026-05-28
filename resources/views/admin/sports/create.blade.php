@extends('admin.layouts.app')

@section('panel')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">@lang('Add New Sport')</h5>
                </div>
                <form action="{{ route('admin.sports.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-control-label">@lang('Sport Name') <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" placeholder="@lang('e.g. Football')" required>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">@lang('Slug') <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="slug" placeholder="@lang('e.g. football')" required>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">@lang('Icon') <span class="text-muted">(FontAwesome class)</span></label>
                            <input type="text" class="form-control" name="icon" placeholder="@lang('e.g. fas fa-football')">>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">@lang('Image')</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                            <small class="form-text text-muted">@lang('Recommended size: 300x300px')</small>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">@lang('Description')</label>
                            <textarea class="form-control" name="description" rows="4" placeholder="@lang('Sport description')"></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">@lang('Display Order') <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="order" value="0" required>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">
                                <input type="checkbox" name="status" value="1" checked>
                                @lang('Enable this sport')
                            </label>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn--primary btn-block">@lang('Add Sport')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
