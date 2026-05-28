@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('SL')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Symbol')</th>
                                    <th>@lang('Current Price')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($cryptocurrencies as $item)
                                    <tr>
                                        <td data-label="@lang('SL')">{{ $cryptocurrencies->firstItem() + $loop->index }}</td>
                                        <td data-label="@lang('Name')">
                                            @if($item->image)
                                                <img src="{{ asset('assets/images/cryptocurrencies/' . $item->image) }}" alt="{{ $item->name }}" style="height: 30px; margin-right: 10px;">
                                            @endif
                                            {{__($item->name)}}
                                        </td>
                                        <td data-label="@lang('Symbol')"><strong>{{ $item->symbol }}</strong></td>
                                        <td data-label="@lang('Current Price')">{{ number_format($item->current_price, 8) }}</td>
                                        <td data-label="@lang('Status')">
                                            @if ($item->status == 1)
                                                <span class="badge badge--success">@lang('Active')</span>
                                            @else
                                                <span class="badge badge--danger">@lang('Inactive')</span>
                                            @endif
                                        </td>
                                        <td data-label="@lang('Action')">
                                            <a href="{{ route('admin.cryptocurrencies.edit', $item->id) }}" class="icon-btn" title="@lang('Edit')"><i class="la la-pencil-alt"></i></a>
                                            <form action="{{ route('admin.cryptocurrencies.destroy', $item->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="icon-btn bg--danger" title="@lang('Delete')" onclick="return confirm('@lang('Are you sure?')')"><i class="la la-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{__($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($cryptocurrencies->hasPages())
                    <div class="card-footer py-4">
                        {{ $cryptocurrencies->links('admin.partials.paginate') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.cryptocurrencies.create') }}" class="btn btn--primary"><i class="las la-plus"></i>@lang('Add Cryptocurrency')</a>
    <form action="{{ route('admin.cryptocurrencies.updatePrices') }}" method="POST" style="display:inline;">
        @csrf
        <button type="submit" class="btn btn--info"><i class="las la-sync"></i>@lang('Update Prices')</button>
    </form>
@endpush
