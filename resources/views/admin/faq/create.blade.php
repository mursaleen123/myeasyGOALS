@extends('admin.layouts.app')
@section('content')
    <style>
        .toggle-input {
            display: none;
        }

        .toggle-label {
            display: inline-block;
            width: 50px;
            height: 30px;
            background-color: gray;
            border-radius: 15px;
            position: relative;
            cursor: pointer;
        }

        .toggle-input:checked+.toggle-label {
            background-color: #009ef7;
        }

        .toggle-label:before {
            content: '';
            position: absolute;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            background-color: white;
            top: 2px;
            left: 2px;
            transition: 0.2s;
        }

        .toggle-input:checked+.toggle-label:before {
            transform: translateX(20px);
        }
    </style>
    <!--begin::Content-->
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div id="kt_content_container" class="container-xxl">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <!--begin::Card-->
                <div class="card">
                    <!--begin::Card header-->
                    <div class="card-header border-0 pt-6">
                        <!--begin::Card title-->
                        <div class="card-title">
                            Create New Faq
                        </div>
                        <!--End::Card title-->
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar">
                            <!--begin::Toolbar-->
                            <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                                <!--begin::Add user-->
                                <a href="{{ route('get.faq.index') }}" class="btn btn-primary">
                                    Back
                                </a>
                                <!--end::Add user-->
                            </div>
                            <!--end::Toolbar-->
                        </div>
                        <!--end::Card toolbar-->
                    </div>
                    <!--end::Card header-->
                    {!! Form::open(['route' => 'store.faq', 'method' => 'POST', 'class' => 'w-100 position-relative mb-3']) !!}
                    <!--begin::Card body-->
                    <div class="card-body pt-0">
                        <!--begin::Input group-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg-12 fv-row">
                                <!--begin::Label-->
                                <label class="col-lg-12 col-form-label required fw-bold fs-6">Question:</label>
                                <!--end::Label-->
                                {!! Form::text('question', null, ['placeholder' => 'Write Faq Question Here?', 'class' => 'form-control']) !!}
                            </div>
                            <!--end::Col-->
                        </div>


                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg-12 fv-row">
                                <!--begin::Label-->
                                <label class="col-lg-12 col-form-label required fw-bold fs-6">Answer:</label>
                                {!! Form::textarea('answer', null, [
                                    'placeholder' => 'Faq Answer Goes here',
                                    'class' => 'form-control',
                                    'style' => 'height: 200px;',
                                ]) !!}
                                </br>
                                <!--end::Label-->
                            </div>
                            <!--end::Col-->
                        </div>
                        <!--end::Input group-->
                        <!--begin::toogle group-->
                        <div class="row">
                            <!--begin::Col-->
                            <div class="col-lg-12 fv-row d-flex align-items-center">
                                <!--begin::Label-->
                                <label class="col-form-label required fw-bold fs-6" for="toggle">Active:</label>
                                <!--end::Label-->

                                <!--begin::Toggle Button-->
                                <div class="form-check form-switch">
                                    <input type="checkbox" id="toggle" class="form-check-input toggle-input"
                                        value="1" name="is_active">
                                    <label class="form-check-label toggle-label" for="toggle"></label>
                                </div>
                                <!--end::Toggle Button-->
                            </div>
                            <!--end::Col-->
                        </div>


                        <!--end::toogle group-->
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
            $("#FaqNav").addClass('show');
            $("#addFaq").addClass('active');
        });
    </script>
    <script>
        const toggle = document.getElementById('toggle');
        toggle.addEventListener('change', function() {
            if (this.checked) {
                this.value = '1';
            } else {
                this.value = '0';
            }
        });
    </script>
@endsection
