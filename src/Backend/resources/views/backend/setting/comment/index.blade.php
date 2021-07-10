@extends('mymo::layouts.backend')

@section('title', $title)

@section('content')

{{ Breadcrumbs::render('manager', [
        'name' => trans('mymo::app.comment_setting'),
        'url' => route('admin.setting.comment')
    ]) }}

<div class="mymo__utils__content">
    <form method="post" action="{{ route('admin.setting.comment.save') }}" class="form-ajax">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="mb-0 card-title font-weight-bold">{{ $title }}</h5>
                    </div>

                    <div class="col-md-6">
                        <div class="btn-group float-right">
                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> @lang('mymo::app.save')</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">

                <div class="row">
                    <div class="col-md-12">
                        @php
                            $comment_able = get_config('comment_able');
                        @endphp
                        <div class="form-group">
                            <label class="col-form-label" for="comment_able">@lang('mymo::app.comment_able')</label>
                            <select name="comment_able" id="comment_able" class="form-control">
                                <option value="1" @if($comment_able == 1) selected @endif>@lang('mymo::app.enabled')</option>
                                <option value="0" @if($comment_able == 0) selected @endif>@lang('mymo::app.disabled')</option>
                            </select>
                        </div>

                        @php
                        $comment_type = get_config('comment_type');
                        @endphp
                        <div class="form-group">
                            <label class="col-form-label" for="comment_type">@lang('mymo::app.comment_type')</label>
                            <select name="comment_type" id="comment_type" class="form-control">
                                <option value="facebook" @if($comment_type == 'facebook') selected @endif>@lang('mymo::app.facebook_comments')</option>
                                <option value="site" @if($comment_type == 'site') selected @endif>@lang('mymo::app.site_comments_system')</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="col-form-label" for="comments_per_page">@lang('mymo::app.comments_per_page')</label>

                            <input type="number" name="comments_per_page" class="form-control" id="comments_per_page" value="{{ get_config('comments_per_page') }}" autocomplete="off">
                        </div>

                        @php
                            $comments_approval = get_config('comments_approval');
                        @endphp
                        <div class="form-group">
                            <label class="col-form-label" for="comments_approval">@lang('mymo::app.comments_approval')</label>

                            <select name="comments_approval" id="comments_approval" class="form-control">
                                <option value="auto" @if($comments_approval == 'auto') selected @endif>@lang('mymo::app.auto')</option>
                                <option value="manual" @if($comments_approval == 'manual') selected @endif>@lang('mymo::app.manual')</option>

                            </select>
                        </div>

                    </div>
                </div>


            </div>
        </div>
    </form>
</div>
@endsection
