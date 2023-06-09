@extends('admin.layouts.app')
@section('content')
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div id="kt_content_container" class="container-xxl">

                <!--begin::Card-->
                <div class="card">
                    <!--begin::Card header-->
                    <div class="card-header border-0 pt-6">
                        <!--begin::Card title-->
                        <div class="card-title">
                            Disclaimer
                        </div>
                        <!--End::Card title-->
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar">
                            <!--begin::Toolbar-->
                            <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                                <!--begin::Add user-->
                                {{-- <a download href="{{ @asset('admin/assets/User-Guide/user-guide.pdf') }}"
                                    class="btn btn-primary">
                                    Download
                                    <svg width="25px" height="25px" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <title />
                                        <g id="Complete">
                                            <g id="download">
                                                <g>
                                                    <path d="M3,12.3v7a2,2,0,0,0,2,2H19a2,2,0,0,0,2-2v-7" fill="none"
                                                        stroke="#ffffff" stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" />

                                                    <g>

                                                        <polyline data-name="Right" fill="none" id="Right-2"
                                                            points="7.9 12.3 12 16.3 16.1 12.3" stroke="#ffffff"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" />

                                                        <line fill="none" stroke="#ffffff" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="2" x1="12"
                                                            x2="12" y1="2.7" y2="14.2" />

                                                    </g>

                                                </g>

                                            </g>

                                        </g>

                                    </svg>
                                </a> --}}
                                <!--end::Add user-->
                            </div>
                            <!--end::Toolbar-->
                        </div>
                        <!--end::Card toolbar-->
                    </div>
                    <!--end::Card header-->
                    {!! Form::open([
                        'route' => 'disclaimer.storeAndUpdate',
                        'method' => 'POST',
                        'class' => 'w-100 position-relative mb-3',
                    ]) !!}
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Input group-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg-12 fv-row">
                                <!--begin::Label-->

                                {{-- <label class="col-lg-12 col-form-label required fw-bold fs-6">Answer:</label> --}}
                                {!! Form::textarea('disclaimer', $disclaimer->disclaimer, [
                                    'placeholder' => '',
                                    'class' => 'form-control tinymce-editor',
                                    'id' => 'myTextarea',
                                    'style' => 'height: 200px;',
                                ]) !!}

                                </br>
                                <!--end::Label-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                    </div>

                    <!--end::Card body-->
                    <!--begin::Card footer-->
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <button type="submit" class="btn btn-primary px-6">Save Changes</button>
                    </div>
                    <!--end::Card footer-->
                    {!! Form::close() !!}
                </div>
                <!--end::Card-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Post-->
    </div>
    <!--end::Content-->
    <script>
        $(document).ready(function() {
            $("#disclaimer").addClass('show');
        });
    </script>
@endsection
