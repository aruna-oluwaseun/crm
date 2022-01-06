<?php
    $accepted_files = isset($accepted_files) ? $accepted_files : '';
    $upload_url = isset($upload_url) ? 'upload/'.$upload_url : 'upload';
    $related_id = isset($related_id) ? $related_id : '';

    if($related_id != '') {
        $upload_url = $upload_url.'/'.$related_id;
    }
?>
<div class="modal-content">
    <a href="#" class="close" data-dismiss="modal"><em class="icon ni ni-cross-sm"></em></a>
    <div class="modal-body modal-body-md">
        <h5 class="title mb-3">Upload File</h5>
        <div class="nk-upload-form">
            <form action="{{ url($upload_url) }}" method="POST" enctype="multipart/form-data" class="upload-zone small bg-lighter" {!! $accepted_files ? 'data-accepted-files="'.$accepted_files.'"' : '' !!} data-url="{{ $upload_url }}">
                @csrf

                <div class="dz-message" data-dz-message>
                    <span class="dz-message-text"><span>Drag and drop</span> file here or <span>browse</span></span>
                </div>

                {{--<div class="nk-modal-action justify-end">
                    <ul class="btn-toolbar g-4 align-center">
                        <li><a data-dismiss="modal" class="link link-primary">Cancel</a></li>
                        --}}{{--<li><button class="btn btn-primary" type="submit">Add Files</button></li>--}}{{--
                    </ul>
                </div>--}}
            </form>
        </div>


    </div>
</div><!-- .modal-content -->

@push('scripts')
    <script src="{{ asset('assets/js/admin/apps/uploads.js') }}"></script>
@endpush
