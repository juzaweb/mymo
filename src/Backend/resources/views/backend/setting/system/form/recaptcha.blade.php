<form method="post" action="{{ route('admin.setting.save') }}" class="form-ajax">
    <input type="hidden" name="form" value="recaptcha">
    <h5>@lang('mymo::app.google_recaptcha')</h5>
    @php
    $google_recaptcha = get_config('google_recaptcha');
    @endphp
    <div class="form-group">
        <label class="col-form-label" for="google_recaptcha">@lang('mymo::app.google_recaptcha')</label>
        <select name="google_recaptcha" id="google_recaptcha" class="form-control">
            <option value="1" @if($google_recaptcha == 1) selected @endif>@lang('mymo::app.enabled')</option>
            <option value="0" @if($google_recaptcha == 0) selected @endif>@lang('mymo::app.disabled')</option>
        </select>
    </div>

    <div class="form-group">
        <label class="col-form-label" for="google_recaptcha_key">@lang('mymo::app.google_recaptcha_key')</label>

        <input type="text" name="google_recaptcha_key" class="form-control" id="google_recaptcha_key" value="{{ get_config('google_recaptcha_key') }}" autocomplete="off">
    </div>

    <div class="form-group">
        <label class="col-form-label" for="google_recaptcha_secret">@lang('mymo::app.google_recaptcha_secret')</label>

        <input type="text" name="google_recaptcha_secret" class="form-control" id="google_recaptcha_secret" value="{{ get_config('google_recaptcha_secret') }}" autocomplete="off">
    </div>

    <div class="row">
        <div class="col-md-6"></div>

        <div class="col-md-6">
            <div class="btn-group float-right">
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-save"></i> @lang('mymo::app.save')
                </button>

                <button type="reset" class="btn btn-default">
                    <i class="fa fa-refresh"></i> @lang('mymo::app.reset')
                </button>
            </div>
        </div>
    </div>
</form>