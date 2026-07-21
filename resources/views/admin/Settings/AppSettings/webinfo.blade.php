<div class="row">
    <div class="col-12">
        <form method="post" action="{{route('updatewebinfo')}}" id="appinfoform" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-row">
                <div class="form-group col-md-6">
                    <h5 class="text-{{$text}}">Website Name</h5>
                    <input type="text" name="site_name" class="form-control bg-{{$bg}} text-{{$text}}" value="{{$settings->site_name}}" required>
                </div>
                <div class="form-group col-md-6">
                    <h5 class="text-{{$text}}">Website Title</h5>
                    <input type="text" name="site_title" class="form-control bg-{{$bg}} text-{{$text}}" value="{{$settings->site_title}}" required>
                </div>
                <div class="form-group col-md-6">
                    <h5 class="text-{{$text}}">Website Keywords</h5>
                    <input type="text" name="keywords" class="form-control bg-{{$bg}} text-{{$text}}" value="{{$settings->keywords}}" required>
                </div>
                <div class="form-group col-md-6">
                    <h5 class="text-{{$text}}">Website Url (https://yoursite.com)</h5>
                    <input type="text" placeholder="https://yoursite.com" name="site_address" class="form-control bg-{{$bg}} text-{{$text}}" value="{{$settings->site_address}}" required>
                </div>
                <div class="form-group col-md-12">
                    <h5 class="text-{{$text}}">Website Description</h5>
                    <textarea name="description" class="form-control bg-{{$bg}} text-{{$text}}" rows="4">{{ $settings->description }}</textarea>
                </div>


                <div class="form-group col-md-12">
                    <h5 class="text-{{$text}}">Payment Note</h5>
                    <textarea name="network_note" class="form-control bg-{{$bg}} text-{{$text}}" rows="4">{{ $settings->network_note }}</textarea>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-12">
                    <h5 class="text-{{$text}}">Announcement</h5>
                    <textarea name="update" class="form-control bg-{{$bg}} text-{{$text}}" rows="2">{{ $settings->newupdate }}</textarea>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <h5 class="text-{{$text}}">Telegram Channel</h5>
                    <input type="text" name="telegram_channel" class="form-control bg-{{$bg}} text-{{$text}}" value="{{ $settings->telegram_channel ?? '' }}" placeholder="e.g., @YourChannel">
                </div>
                <div class="form-group col-md-6">
                    <h5 class="text-{{$text}}">Admin Telegram</h5>
                    <input type="text" name="admin_telegram" class="form-control bg-{{$bg}} text-{{$text}}" value="{{ $settings->admin_telegram ?? '' }}" placeholder="e.g., @AdminHandle">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <h5 class="text-{{$text}}">Withdrawal Percentage (%)</h5>
                    <input type="number" name="withdrawal_percentage" class="form-control bg-{{$bg}} text-{{$text}}" value="{{ $settings->withdrawal_percentage ?? '0.00' }}" step="0.01" min="0" max="100" placeholder="e.g., 5.00" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <h5 class="text-{{$text}}">Logo (Recommended size; max width, 200px and max height 100px.)</h5>
                    <input name="logo" class="form-control bg-{{$bg}} text-{{$text}}" type="file">
                </div>
                <div class="form-group col-md-6">
                    <h5 class="text-{{$text}}">Favicon (Recommended type: png, size: max width, 32px and max height 32px.)</h5>
                    <input name="favicon" class="form-control bg-{{$bg}} text-{{$text}}" type="file">
                </div>
            </div>

            <div class="mt-3 form-row">
                <input type="submit" class="px-5 btn btn-primary btn-lg" value="Update">
            </div>
        </form>
    </div>
</div>
