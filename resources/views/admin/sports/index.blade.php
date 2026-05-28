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
                                    <th>@lang('Slug')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Order')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($sports as $item)
                                    <tr>
                                        <td data-label="@lang('SL')">{{ $sports->firstItem() + $loop->index }}</td>
                                        <td data-label="@lang('Name')">
                                            @if($item->image)
                                                <img src="{{ asset('assets/images/sports/' . $item->image) }}" alt="{{ $item->name }}" style="height: 30px; margin-right: 10px;">
                                            @endif
                                            {{__($item->name)}}
                                        </td>
                                        <td data-label="@lang('Slug')">{{__($item->slug)}}</td>
                                        <td data-label="@lang('Status')">
                                            @if ($item->status == 1)
                                                <span class="badge badge--success">@lang('Enabled')</span>
                                            @else
                                                <span class="badge badge--danger">@lang('Disabled')</span>
                                            @endif
                                        </td>
                                        <td data-label="@lang('Order')">{{$item->order}}</td>
                                        <td data-label="@lang('Action')">
                                            <a href="{{ route('admin.sports.edit', $item->id) }}" class="icon-btn" title="@lang('Edit')"><i class="la la-pencil-alt"></i></a>
                                            <form action="{{ route('admin.sports.destroy', $item->id) }}" method="POST" style="display:inline;">
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
                @if($sports->hasPages())
                    <div class="card-footer py-4">
                        {{ $sports->links('admin.partials.paginate') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.sports.create') }}" class="btn btn--primary"><i class="las la-plus"></i>@lang('Add Sport')</a>
    <form action="" method="GET" class="form-inline float-sm-right bg--white">
        <div class="input-group has_append">
            <input type="text" name="search" class="form-control" placeholder="@lang('Sport Name')" value="{{ request()->search }}">
            <div class="input-group-append">
                <button class="btn btn--primary" type="submit"><i class="las la-search"></i></button>
            </div>
        </div>
    </form>
@endpush
