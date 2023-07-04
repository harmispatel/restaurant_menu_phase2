@php
    $client_settings = getClientSettings();
@endphp

@extends('client.layouts.client-layout')

@section('title', __('Mail Forms'))

@section('content')

    {{-- Edit Modal --}}
    <div class="modal fade" id="editMailFormModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editMailFormModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMailFormModalLabel">{{ __('Edit Mail Form')}}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="mail_form_lang_div">
                </div>
                <div class="modal-footer">
                    <a class="btn btn-sm btn-success" onclick="updateMailForm()">{{ __('Update') }}</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Page Title --}}
    <div class="pagetitle">
        <h1>{{ __('Mail Forms')}}</h1>
        <div class="row">
            <div class="col-md-8">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('client.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('Mail Forms') }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    {{-- Mail Forms Section --}}
    <section class="section dashboard">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                       <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>Sr.</th>
                                    <th>Form Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($mail_forms) > 0)
                                    @foreach ($mail_forms as $mail_form)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if($mail_form->mail_form_key == 'orders_mail_form_client')
                                                    <strong>{{ __('Orders Mail Form Owner') }}</strong>
                                                @elseif($mail_form->mail_form_key == 'orders_mail_form_customer')
                                                    <strong>{{ __('Orders Mail Form Customer') }}</strong>
                                                @elseif ($mail_form->mail_form_key == 'check_in_mail_form')
                                                    <strong>{{ __('Check In Mail Form') }}</strong>
                                                @endif
                                            </td>
                                            <td>
                                                <a onclick="editMailForm({{ $mail_form->id }})" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                       </table>
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- <section class="general_info_main">
        <div class="sec_title">
            <h2>{{ __('Mail Forms')}}</h2>
        </div>
        <div class="site_info">
            <form id="mailForms" action="{{ route('design.mailFormUpdate') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label" for="orders_mail_form_client">{{ __('Orders Mail Form Owner') }}</label>
                            <textarea name="orders_mail_form_client" id="orders_mail_form_client" class="form-control editor">{{ (isset($client_settings['orders_mail_form_client']) && !empty($client_settings['orders_mail_form_client'])) ? $client_settings['orders_mail_form_client'] : '' }}</textarea>
                            <code>Tags : ({shop_logo}, {shop_name}, {firstname}, {lastname}, {order_id}, {order_type}, {payment_method}, {items}, {total})</code>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">
                        <div class="form-group">
                            <label class="form-label" for="orders_mail_form_customer">{{ __('Orders Mail Form Customer') }}</label>
                            <textarea name="orders_mail_form_customer" id="orders_mail_form_customer" class="form-control editor">{{ (isset($client_settings['orders_mail_form_customer']) && !empty($client_settings['orders_mail_form_customer'])) ? $client_settings['orders_mail_form_customer'] : '' }}</textarea>
                            <code>Tags : ({shop_logo}, {shop_name}, {firstname}, {lastname}, {order_id}, {order_type}, {order_status}, {payment_method}, {items}, {total}, {estimated_time})</code>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-label" for="check_in_mail_form">{{ __('Check In Mail Form') }}</label>
                            <textarea name="check_in_mail_form" id="check_in_mail_form" class="form-control editor">{{ (isset($client_settings['check_in_mail_form']) && !empty($client_settings['check_in_mail_form'])) ? $client_settings['check_in_mail_form'] : '' }}</textarea>
                            <code>Tags : ({shop_logo}, {shop_name}, {firstname}, {lastname}, {phone}, {passport_no}, {room_no}, {nationality}, {age}, {address}, {arrival_date}, {departure_date}, {message})</code>
                        </div>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-md-3">
                        <button class="btn btn-success">{{ __('Update')}}</button>
                    </div>
                </div>
            </form>
        </div>
    </section> --}}

@endsection


{{-- Custom JS --}}
@section('page-js')

    <script type="text/javascript">

        var editMailFormEditor;

        // Toastr Settings
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-bottom-right",
            timeOut: 4000
        }

        // Success Message
        @if (Session::has('success'))
            toastr.success('{{ Session::get('success') }}')
        @endif

        // Function for Edit Mail Form
        function editMailForm(mailFormID)
        {
            // Reset All Form
            $('#editMailFormModal #mail_form_lang_div').html('');

            // Clear all Toastr Messages
            toastr.clear();

            $('.ck-editor').remove();
            editMailFormEditor = "";

            $.ajax({
                type: "POST",
                url: "{{ route('mail.forms.edit') }}",
                dataType: "JSON",
                data: {
                    '_token': "{{ csrf_token() }}",
                    'id': mailFormID,
                },
                success: function(response)
                {
                    if (response.success == 1)
                    {
                        $('#editMailFormModal #mail_form_lang_div').html('');
                        $('#editMailFormModal #mail_form_lang_div').append(response.data);

                        // Description Text Editor
                        $('.ck-editor').remove();
                        editMailFormEditor = "";
                        var my_item_textarea = $('#mail_form_text')[0];
                        CKEDITOR.ClassicEditor.create(my_item_textarea,
                        {
                            toolbar: {
                                items: [
                                    'heading', '|',
                                    'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript', 'removeFormat', '|',
                                    'bulletedList', 'numberedList', 'todoList', '|',
                                    'outdent', 'indent', '|',
                                    'undo', 'redo',
                                    '-',
                                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                                    'alignment', '|',
                                    'link', 'insertImage', 'blockQuote', 'insertTable', 'mediaEmbed', 'codeBlock', 'htmlEmbed', '|',
                                    'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                                    'sourceEditing'
                                ],
                                shouldNotGroupWhenFull: true
                            },
                            list: {
                                properties: {
                                    styles: true,
                                    startIndex: true,
                                    reversed: true
                                }
                            },
                            'height':500,
                            fontSize: {
                                options: [ 10, 12, 14, 'default', 18, 20, 22 ],
                                supportAllValues: true
                            },
                            htmlSupport: {
                                allow: [
                                    {
                                        name: /.*/,
                                        attributes: true,
                                        classes: true,
                                        styles: true
                                    }
                                ]
                            },
                            htmlEmbed: {
                                showPreviews: true
                            },
                            link: {
                                decorators: {
                                    addTargetToExternalLinks: true,
                                    defaultProtocol: 'https://',
                                    toggleDownloadable: {
                                        mode: 'manual',
                                        label: 'Downloadable',
                                        attributes: {
                                            download: 'file'
                                        }
                                    }
                                }
                            },
                            mention: {
                                feeds: [
                                    {
                                        marker: '@',
                                        feed: [
                                            '@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes', '@chocolate', '@cookie', '@cotton', '@cream',
                                            '@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread', '@gummi', '@ice', '@jelly-o',
                                            '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding', '@sesame', '@snaps', '@soufflé',
                                            '@sugar', '@sweet', '@topping', '@wafer'
                                        ],
                                        minimumCharacters: 1
                                    }
                                ]
                            },
                            removePlugins: [
                                'CKBox',
                                'CKFinder',
                                'EasyImage',
                                'RealTimeCollaborativeComments',
                                'RealTimeCollaborativeTrackChanges',
                                'RealTimeCollaborativeRevisionHistory',
                                'PresenceList',
                                'Comments',
                                'TrackChanges',
                                'TrackChangesData',
                                'RevisionHistory',
                                'Pagination',
                                'WProofreader',
                                'MathType'
                            ]
                        }).then( editor => {
                            editMailFormEditor = editor;
                        });

                        $('#editMailFormModal').modal('show');
                    }
                    else
                    {
                        toastr.error(response.message);
                    }
                }
            });
        }



        // Update Mail Form By Language Code
        function updateByCode(next_lang_code)
        {
            var formID = "editMailFormsForm";
            var myFormData = new FormData(document.getElementById(formID));
            myFormData.set('mail_form_text',editMailFormEditor.getData());
            myFormData.append('next_lang_code',next_lang_code);

            // Clear all Toastr Messages
            toastr.clear();

            $.ajax({
                type: "POST",
                url: "{{ route('mail.forms.update.by.lang') }}",
                data: myFormData,
                dataType: "JSON",
                contentType: false,
                cache: false,
                processData: false,
                success: function (response)
                {
                    if(response.success == 1)
                    {
                        $('#editMailFormModal #mail_form_lang_div').html('');
                        $('#editMailFormModal #mail_form_lang_div').append(response.data);

                        // Description Text Editor
                        $('.ck-editor').remove();
                        editMailFormEditor = "";
                        var my_form_textarea = $('#mail_form_text')[0];
                        CKEDITOR.ClassicEditor.create(my_form_textarea,
                        {
                            toolbar: {
                                items: [
                                    'heading', '|',
                                    'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript', 'removeFormat', '|',
                                    'bulletedList', 'numberedList', 'todoList', '|',
                                    'outdent', 'indent', '|',
                                    'undo', 'redo',
                                    '-',
                                    'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
                                    'alignment', '|',
                                    'link', 'insertImage', 'blockQuote', 'insertTable', 'mediaEmbed', 'codeBlock', 'htmlEmbed', '|',
                                    'specialCharacters', 'horizontalLine', 'pageBreak', '|',
                                    'sourceEditing'
                                ],
                                shouldNotGroupWhenFull: true
                            },
                            list: {
                                properties: {
                                    styles: true,
                                    startIndex: true,
                                    reversed: true
                                }
                            },
                            'height':500,
                            fontSize: {
                                options: [ 10, 12, 14, 'default', 18, 20, 22 ],
                                supportAllValues: true
                            },
                            htmlSupport: {
                                allow: [
                                    {
                                        name: /.*/,
                                        attributes: true,
                                        classes: true,
                                        styles: true
                                    }
                                ]
                            },
                            htmlEmbed: {
                                showPreviews: true
                            },
                            link: {
                                decorators: {
                                    addTargetToExternalLinks: true,
                                    defaultProtocol: 'https://',
                                    toggleDownloadable: {
                                        mode: 'manual',
                                        label: 'Downloadable',
                                        attributes: {
                                            download: 'file'
                                        }
                                    }
                                }
                            },
                            mention: {
                                feeds: [
                                    {
                                        marker: '@',
                                        feed: [
                                            '@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes', '@chocolate', '@cookie', '@cotton', '@cream',
                                            '@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread', '@gummi', '@ice', '@jelly-o',
                                            '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding', '@sesame', '@snaps', '@soufflé',
                                            '@sugar', '@sweet', '@topping', '@wafer'
                                        ],
                                        minimumCharacters: 1
                                    }
                                ]
                            },
                            removePlugins: [
                                'CKBox',
                                'CKFinder',
                                'EasyImage',
                                'RealTimeCollaborativeComments',
                                'RealTimeCollaborativeTrackChanges',
                                'RealTimeCollaborativeRevisionHistory',
                                'PresenceList',
                                'Comments',
                                'TrackChanges',
                                'TrackChangesData',
                                'RevisionHistory',
                                'Pagination',
                                'WProofreader',
                                'MathType'
                            ]
                        }).then( editor => {
                            editMailFormEditor = editor;
                        });
                    }
                    else
                    {
                        $('#editMailFormModal').modal('hide');
                        $('#editMailFormModal #mail_form_lang_div').html('');
                        toastr.error(response.message);
                    }
                },
                error: function(response)
                {
                    if(response.responseJSON.errors)
                    {
                        $.each(response.responseJSON.errors, function (i, error) {
                            toastr.error(error);
                        });
                    }
                }
            });
        }



        // Function for Update Mail Form
        function updateMailForm()
        {
            var formID = "editMailFormsForm";
            var myFormData = new FormData(document.getElementById(formID));
            myFormData.set('mail_form_text',editMailFormEditor.getData());

            // Clear all Toastr Messages
            toastr.clear();

            $.ajax({
                type: "POST",
                url: "{{ route('mail.forms.update') }}",
                data: myFormData,
                dataType: "JSON",
                contentType: false,
                cache: false,
                processData: false,
                success: function (response)
                {
                    if(response.success == 1)
                    {
                        // $('#editMailFormModal').modal('hide');
                        toastr.success(response.message);
                    }
                    else
                    {
                        $('#editMailFormModal').modal('hide');
                        toastr.error(response.message);
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                },
                error: function(response)
                {
                    if(response.responseJSON.errors)
                    {
                        $.each(response.responseJSON.errors, function (i, error) {
                            toastr.error(error);
                        });
                    }
                }
            });

        }

    </script>

@endsection

