@extends('mymo::layouts.backend')

@section('content')
    @php
        $type = $setting->get('type');
    @endphp

    @component('mymo::components.form_resource', [
        'method' => $model->id ? 'put' : 'post',
        'action' => $model->id ?
            route('admin.'. $type .'.taxonomy.update', [$taxonomy, $model->id]) :
            route('admin.'. $type .'.taxonomy.store', [$taxonomy])
    ])
        <div class="row">
            <div class="col-md-8">
                <input type="hidden" name="redirect" value="{{ path_url(route('admin.'. $type .'.taxonomy.index', [$taxonomy])) }}">

                @component('mymo::components.form_input', [
                    'name' => 'name',
                    'label' => trans('mymo::app.name'),
                    'value' => $model->name
                ])
                @endcomponent

                @component('mymo::components.form_textarea', [
                    'name' => 'description',
                    'label' => trans('mymo::app.description'),
                    'value' => $model->description
                ])
                @endcomponent

                @if(in_array('hierarchical', $setting->get('supports', [])))
                <div class="form-group">
                    <label class="col-form-label" for="parent_id">@lang('mymo::app.parent')</label>
                    <select name="parent_id" id="parent_id" class="form-control load-taxonomies" data-post-type="{{ $setting->get('post_type') }}" data-taxonomy="{{ $setting->get('taxonomy') }}" data-placeholder="{{ trans('mymo::app.parent') }}" data-explodes="{{ $model->id }}">
                        @if($model->parent)
                            <option value="{{ $model->parent->id }}" selected>{{ $model->parent->name }}</option>
                        @endif
                    </select>
                </div>
                @endif
            </div>
            @if(in_array('thumbnail', $setting->get('supports', [])))
            <div class="col-md-4">
                @component('mymo::components.form_image', [
                    'name' => 'thumbnail',
                    'label' => trans('mymo::app.thumbnail'),
                    'value' => $model->thumbnail
                ])@endcomponent
            </div>
            @endif
        </div>
    @endcomponent

@endsection