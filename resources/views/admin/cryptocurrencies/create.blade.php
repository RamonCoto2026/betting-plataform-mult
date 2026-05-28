@extends('admin.layouts.app')

@section('panel')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">@lang('Add Cryptocurrency')</h5>
                </div>
                <form action="{{ route('admin.cryptocurrencies.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-control-label">@lang('Name') <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" placeholder="@lang('e.g. Bitcoin')" required>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">@lang('Symbol') <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="symbol" placeholder="@lang('e.g. BTC')" required>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">@lang('Binance Symbol') <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="binance_symbol" placeholder="@lang('e.g. BTCUSDT')" required>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">@lang('Image')</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">@lang('Description')</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">@lang('Minimum Bet') <span class="text-danger">*</span></label>
                                    <input type="number" step="0.00000001" class="form-control" name="min_bet" value="0.00001" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label">@lang('Maximum Bet') <span class="text-danger">*</span></label>
                                    <input type="number" step="0.00000001" class="form-control" name="max_bet" value="1000000" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">
                                <input type="checkbox" name="status" value="1" checked>
                                @lang('Enable for betting')
                            </label>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn--primary btn-block">@lang('Add Cryptocurrency')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
