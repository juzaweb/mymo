@extends('mymo::layouts.backend')

@section('content')

    @do_action('backend.dashboard.view')

    <div class="row mt-3">
        <div class="col-md-6">
            <h5>@lang('mymo::app.new_users')</h5>
            <table class="table" id="users-table">
                <thead>
                <tr>
                    <th data-formatter="index_formatter" data-width="5%">#</th>
                    <th data-field="name">@lang('mymo::app.name')</th>
                    <th data-field="created" data-width="30%" data-align="center">@lang('mymo::app.created_at')</th>
                </tr>
                </thead>
            </table>
        </div>

        <div class="col-md-6">
            <h5>@lang('mymo::app.notifications')</h5>

            <table class="table" id="users-notification">
                <thead>
                    <tr>
                        <th data-formatter="index_formatter" data-width="5%">#</th>
                        <th data-field="subject" data-formatter="subject_formatter">@lang('mymo::app.subject')</th>
                        <th data-field="created" data-width="30%" data-align="center">@lang('mymo::app.created_at')</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <script type="text/javascript">

        function index_formatter(value, row, index) {
            return (index + 1);
        }

        function subject_formatter(value, row, index) {
            return '<a href="'+ row.url +'" data-turbolinks="false">'+ value +'</a>';
        }

        var table1 = new MymoTable({
            table: '#users-table',
            page_size: 5,
            url: '{{ route('admin.dashboard.users') }}',
        });

        var table2 = new MymoTable({
            table: '#users-notification',
            page_size: 5,
            url: '{{ route('admin.dashboard.notifications') }}',
        });
    </script>
@endsection