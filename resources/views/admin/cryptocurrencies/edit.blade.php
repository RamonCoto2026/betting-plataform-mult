@extends('admin.layouts.app')

@section('panel')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">@lang('Edit Cryptocurrency')</h5>
                </div>
                <form action="{{ route('admin.cryptocurrencies.update', $crypto->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-control-label">@lang('Name') <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" value="{{ $crypto->name }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">@lang('Symbol') <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="symbol" value="{{ $crypto->symbol }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">@lang('Binance Symbol') <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="binance_symbol" value="{{ $crypto->binance_symbol }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">@lang('Image')</label>
                            @if($crypto->image)
                                <div class="mb-2">
                                    <img src="{{ asset('assets/images/cryptocurrencies/' . $crypto->image) }}" alt="{{ $crypto->name }}" style="max-height: 100px;">
                                </div>
                            @endif
                            <input type="file" class="form-control" name="image" accept="image/*">
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">@lang('Description')</label>
                            <textarea class="form-control" name="description" rows="3">{{ $crypto->description }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">@lang('Current Price')</label>
                                    <input type="text" class="form-control" value="{{ number_format($crypto->current_price, 8) }}" disabled>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">@lang('Last Updated')</label>
                                    <input type="text" class="form-control" value="{{ $crypto->last_updated ? $crypto->last_updated->format('Y-m-d H:i:s') : 'N/A' }}" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">@lang('Minimum Bet') <span class="text-danger">*</span></label>
                                    <input type="number" step="0.00000001" class="form-control" name="min_bet" value="{{ $crypto->min_bet }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">@lang('Maximum Bet') <span class="text-danger">*</span></label>
                                    <input type="number" step="0.00000001" class="form-control" name="max_bet" value="{{ $crypto->max_bet }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">
                                <input type="checkbox" name="status" value="1" {{ $crypto->status ? 'checked' : '' }}>
                                @lang('Enable for betting')
                            </label>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn--primary btn-block">@lang('Update Cryptocurrency')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
