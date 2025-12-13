@hasanyrole('admin', 'superadmin')
    @extends('tyro-dashboard::layouts.admin')
@else
    @extends('tyro-dashboard::layouts.user')
@endhasanyrole