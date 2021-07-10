@extends('mymo::layouts.backend')

@section('content')
    <div class="row mb-3">
        <div class="col-md-12">
            <div class="float-right">
                <div class="btn-group">

                </div>
            </div>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-12">
            <form method="get" class="form-inline" id="form-search">

                <div class="form-group mb-2 mr-1">
                    <label for="search" class="sr-only">@lang('mymo::app.search')</label>
                    <input name="search" type="text" id="search" class="form-control" placeholder="@lang('mymo::app.search')" autocomplete="off">
                </div>

                <div class="form-group mb-2 mr-1">
                    <label for="status" class="sr-only">@lang('mymo::app.status')</label>
                    <select name="status" id="status" class="form-control">
                        <option value="">--- @lang('mymo::app.status') ---</option>
                        <option value="approved">@lang('mymo::app.approved')</option>
                        <option value="deny">@lang('mymo::app.deny')</option>
                        <option value="pending">@lang('mymo::app.pending')</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary mb-2"><i class="fa fa-search"></i> @lang('mymo::app.search')</button>
            </form>
        </div>
    </div>

    <div class="table-responsive mb-5">
        <table class="table mymo-table">
            <thead>
                <tr>
                    <th data-width="3%" data-field="state" data-checkbox="true"></th>
                    <th data-field="author">@lang('mymo::app.author')</th>
                    <th data-width="30%" data-field="content">@lang('mymo::app.content')</th>
                    <th data-width="15%" data-field="post">@lang('mymo::app.post')</th>
                    <th data-width="10%" data-field="status" data-align="center" data-formatter="status_formatter">@lang('mymo::app.status')</th>
                    <th data-width="15%" data-field="created_at">@lang('mymo::app.created_at')</th>
                </tr>
            </thead>
        </table>
    </div>

    <script type="text/javascript">
        function status_formatter(value, row, index) {
            if (value == 1) {
                return '<span class="text-success">@lang('mymo::app.approved')</span>';
            }
            return '<span class="text-danger">@lang('mymo::app.deny')</span>';
        }

        var table = new MymoTable({
            url: '{{ route('admin.comments.get-data') }}',
            action_url: '{{ route('admin.comments.bulk-actions') }}'
        });
    </script>
@endsection