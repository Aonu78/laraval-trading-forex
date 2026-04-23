@extends('backend.layout.master')

@section('element')
    <div class="row withdraw-all-row">
        <div class="col-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header text-right">
                    <form action="">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search Trx">
                            <a href="javascript:void(0)"
                                class="btn btn-sm btn-outline-secondary daterange-btn btn-d icon-left btn-icon filterData"><i class="fas fa-calendar"></i> {{ __('Filter By Date') }}
                            </a>
                            <div class="input-group-append">
                                <button class="btn btn-sm btn-primary"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table student-data-table m-t-20">
                            <thead>
                                <tr>
                                    <th>{{ __('User') }}</th>
                                    <th>{{ __('Trx') }}</th>
                                    <th>{{ __('Withdraw Amount') }}</th>
                                    <th>{{ __('Charge') }}</th>
                                    <th>{{ __('User Will Get') }}</th>
                                    <th>{{ __('Charge Type') }}</th>
                                    <th>{{ __('Withdraw Date') }}</th>
                                    <th>{{ __('status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody id="filter_data">
                                @forelse ($data['withdraws'] as $key => $withdrawlog)
                                    <tr>
                                        <td>
                                            <img src="{{ Config::getFile('user', $withdrawlog->user->image, true) }}"
                                                alt="" class="image-table">
                                            <span>
                                                <a href="{{ route('admin.user.details', $withdrawlog->user->id) }}">
                                                    {{ $withdrawlog->user->username }}
                                                </a>
                                            </span>
                                        </td>
                                        <td>{{ $withdrawlog->trx }}</td>
                                        <td>
                                            {{ Config::formatter($withdrawlog->withdraw_amount) }}
                                        </td>
                                        <td>
                                            {{ Config::formatter($withdrawlog->withdraw_charge) }}
                                        </td>
                                        <td>
                                            {{ Config::formatter($withdrawlog->total) }}

                                        </td>
                                        <td>
                                            {{ ucwords($withdrawlog->withdrawMethod->type) }}
                                        </td>

                                        <td>
                                            {{ $withdrawlog->created_at->format('d , M Y') }}
                                        </td>

                                        <td>
                                            @if ($withdrawlog->status == 1)
                                                <span class="badge badge-success">{{ __('Success') }}</span>
                                            @elseif($withdrawlog->status == 2)
                                                <span class="badge badge-danger">{{ __('Rejected') }}</span>
                                            @else
                                                <span class="badge badge-warning">{{ __('Pending') }}</span>
                                            @endif
                                        </td>
	                                        <td>
	                                            <button class="btn btn-sm btn-outline-primary details"
	                                                data-user_data="{{ json_encode($withdrawlog->proof) }}"
	                                                data-transaction="{{ $withdrawlog->trx }}"
	                                                data-provider="{{ $withdrawlog->user->username }}"
	                                                data-email="{{ $withdrawlog->user->email }}"
	                                                data-method_name="{{ $withdrawlog->withdrawMethod->name }}"
	                                                data-withdraw_method_id="{{ $withdrawlog->withdraw_method_id }}"
	                                                data-withdraw_amount="{{ $withdrawlog->withdraw_amount }}"
	                                                data-currency="{{ $withdrawlog->currency }}"
	                                                data-account_holder_name="{{ $withdrawlog->account_holder_name }}"
	                                                data-bank_name="{{ $withdrawlog->bank_name }}"
	                                                data-bank_account_number="{{ $withdrawlog->bank_account_number }}"
	                                                data-ifsc_code="{{ $withdrawlog->ifsc_code }}"
	                                                data-date="{{ __($withdrawlog->created_at->format('d F Y')) }}"><i
	                                                    class="fas fa-eye"></i></button>
	                                            @if ($withdrawlog->status == 0)
	                                                <button class="btn btn-sm btn-outline-info edit-request"
	                                                    data-url="{{ route('admin.withdraw.request.update', $withdrawlog) }}"
	                                                    data-user_data="{{ json_encode($withdrawlog->proof) }}"
	                                                    data-withdraw_method_id="{{ $withdrawlog->withdraw_method_id }}"
	                                                    data-withdraw_amount="{{ $withdrawlog->withdraw_amount }}"
	                                                    data-currency="{{ $withdrawlog->currency }}"
	                                                    data-account_holder_name="{{ $withdrawlog->account_holder_name }}"
	                                                    data-bank_name="{{ $withdrawlog->bank_name }}"
	                                                    data-bank_account_number="{{ $withdrawlog->bank_account_number }}"
	                                                    data-ifsc_code="{{ $withdrawlog->ifsc_code }}"><i
	                                                        class="fas fa-edit"></i></button>
	                                                <button class="btn btn-sm btn-outline-success accept"
	                                                    data-url="{{ route('admin.withdraw.accept', $withdrawlog) }}"><i
	                                                        class="fas fa-check"></i></button>
                                                <button class="btn btn-sm btn-outline-danger reject"
                                                    data-url="{{ route('admin.withdraw.reject', $withdrawlog) }}"><i
                                                        class="fas fa-times"></i></button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center" colspan="100%">{{ __('No Data Found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($data['withdraws']->hasPages())
                    <div class="card-footer">
                        {{ $data['withdraws']->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>




    <!-- Modal -->
    <div class="modal fade" id="details" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">


            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Withdraw Details') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="withdraw-details">

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Close') }}</button>

                </div>
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="accept" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">

            <form action="" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Withdraw Accept') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>{{ __('Are you sure to Accept this withdraw request') }}?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Accept') }}</button>

                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal -->
	    <div class="modal fade" id="reject" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">

            <form action="" method="post">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Withdraw Reject') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group col-md-12">

                            <label for="">{{ __('Reason Of Reject') }}</label>
                            <textarea name="reason_of_reject" id="" cols="30" rows="10" class="form-control"> </textarea>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-danger">{{ __('Reject') }}</button>

                    </div>
                </div>
            </form>
        </div>
	    </div>

	    <div class="modal fade" id="editRequest" tabindex="-1" role="dialog" aria-hidden="true">
	        <div class="modal-dialog modal-lg" role="document">
	            <form action="" method="post">
	                @csrf
	                <div class="modal-content">
	                    <div class="modal-header">
	                        <h5 class="modal-title">{{ __('Edit Withdraw Request') }}</h5>
	                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	                            <span aria-hidden="true">&times;</span>
	                        </button>
	                    </div>
	                    <div class="modal-body">
	                        <div class="row">
	                            <div class="col-md-6">
	                                <div class="form-group">
	                                    <label>{{ __('Withdraw Method') }}</label>
	                                    <select name="withdraw_method_id" class="form-control" required>
	                                        @foreach (\App\Models\WithdrawGateway::where('status', 1)->orderBy('name')->get() as $method)
	                                            <option value="{{ $method->id }}">{{ $method->name }}</option>
	                                        @endforeach
	                                    </select>
	                                </div>
	                            </div>
	                            <div class="col-md-6">
	                                <div class="form-group">
	                                    <label>{{ __('Withdraw Amount') }}</label>
	                                    <input type="number" step="0.00000001" min="0" name="withdraw_amount"
	                                        class="form-control" required>
	                                </div>
	                            </div>
	                            <div class="col-md-6">
	                                <div class="form-group">
	                                    <label>{{ __('Currency') }}</label>
	                                    <input type="text" name="currency" class="form-control">
	                                </div>
	                            </div>
	                            <div class="col-md-6">
	                                <div class="form-group">
	                                    <label>{{ __('Proof Email') }}</label>
	                                    <input type="text" name="proof_email" class="form-control">
	                                </div>
	                            </div>
	                            <div class="col-md-6">
	                                <div class="form-group">
	                                    <label>{{ __('Account Holder Name') }}</label>
	                                    <input type="text" name="account_holder_name" class="form-control">
	                                </div>
	                            </div>
	                            <div class="col-md-6">
	                                <div class="form-group">
	                                    <label>{{ __('Bank Name') }}</label>
	                                    <input type="text" name="bank_name" class="form-control">
	                                </div>
	                            </div>
	                            <div class="col-md-6">
	                                <div class="form-group">
	                                    <label>{{ __('Bank Account Number') }}</label>
	                                    <input type="text" name="bank_account_number" class="form-control">
	                                </div>
	                            </div>
	                            <div class="col-md-6">
	                                <div class="form-group">
	                                    <label>{{ __('IFSC / Swift Code') }}</label>
	                                    <input type="text" name="ifsc_code" class="form-control">
	                                </div>
	                            </div>
	                            <div class="col-md-12">
	                                <div class="form-group">
	                                    <label>{{ __('Account Information') }}</label>
	                                    <textarea name="account_information" rows="3" class="form-control"></textarea>
	                                </div>
	                            </div>
	                            <div class="col-md-12">
	                                <div class="form-group mb-0">
	                                    <label>{{ __('Note') }}</label>
	                                    <textarea name="note" rows="3" class="form-control"></textarea>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                    <div class="modal-footer">
	                        <button type="button" class="btn btn-secondary"
	                            data-dismiss="modal">{{ __('Close') }}</button>
	                        <button type="submit" class="btn btn-info">{{ __('Update Request') }}</button>
	                    </div>
	                </div>
	            </form>
	        </div>
	    </div>
@endsection


@push('script')
    <script>
        $(function() {
            'use strict'

            $(document).on('click','.details' ,function() {
                const modal = $('#details');

                let html = `
                
                    <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                               {{ __('Withdraw Method Email') }}
                                <span>${$(this).data('user_data').email}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ __('Withdraw Account Information') }}
                                <span>${$(this).data('user_data').account_information}</span>
                            </li> 
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ __('Transaction Id') }}
                                <span>${$(this).data('transaction')}</span>
                            </li>  
                            
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ __('User Name') }}
                                <span>${$(this).data('provider')}</span>
                            </li>  
                            
                            
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ __('User Email') }}
                                <span>${$(this).data('email')}</span>
                            </li> 
                            
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ __('Withdraw Method') }}
                                <span>${$(this).data('method_name')}</span>
                            </li> 
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ __('Withdraw Date') }}
                                <span>${$(this).data('date')}</span>
                            </li> 
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ __('Note For Withdraw') }}
                                <span>${$(this).data('user_data').note}</span>
                            </li>
                            
                        </ul>
                
                
                `;

                modal.find('.withdraw-details').html(html);

                modal.modal('show');
            })

            $(document).on('click','.accept' ,function() {
                const modal = $('#accept');

                modal.find('form').attr('action', $(this).data('url'));
                modal.modal('show');
            })

	            $(document).on('click', '.reject' ,function() {
	                const modal = $('#reject');

	                modal.find('form').attr('action', $(this).data('url'));
	                modal.modal('show');
	            })

	            $(document).on('click', '.edit-request', function() {
	                const modal = $('#editRequest');
	                const userData = $(this).data('user_data') || {};

	                modal.find('form').attr('action', $(this).data('url'));
	                modal.find('select[name=withdraw_method_id]').val($(this).data('withdraw_method_id'));
	                modal.find('input[name=withdraw_amount]').val($(this).data('withdraw_amount'));
	                modal.find('input[name=currency]').val($(this).data('currency'));
	                modal.find('input[name=proof_email]').val(userData.email || '');
	                modal.find('input[name=account_holder_name]').val($(this).data('account_holder_name'));
	                modal.find('input[name=bank_name]').val($(this).data('bank_name'));
	                modal.find('input[name=bank_account_number]').val($(this).data('bank_account_number'));
	                modal.find('input[name=ifsc_code]').val($(this).data('ifsc_code'));
	                modal.find('textarea[name=account_information]').val(userData.account_information || '');
	                modal.find('textarea[name=note]').val(userData.note || '');
	                modal.modal('show');
	            })

	        })
	    </script>
@endpush



@push('external-style')
    <link rel="stylesheet" href="{{ Config::cssLib('backend', 'daterangepicker.css') }}">
@endpush

@push('external-script')
    <script src="{{ Config::jsLib('backend', 'moment.js') }}"></script>
    <script src="{{ Config::jsLib('backend', 'daterangepicker.min.js') }}"></script>
@endpush

@push('style')
    <style>
        .ranges {
            padding: 10px !important;
            margin-top: 10px !important;
        }

        .daterangepicker .ranges li.active {
            background-color: #6777ee !important;
        }

        .daterangepicker .ranges li:hover {
            background-color: #f5f5f5 !important;
            color: #6777ee;
        }

        #overlay {
            position: fixed;
            top: 0;
            z-index: 100;
            width: 100%;
            height: 100%;
            display: none;
            background: rgba(0, 0, 0, 0.6);
        }

        .cv-spinner {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .spinner {
            width: 60px;
            height: 60px;
            border: 4px #ddd solid;
            border-top: 4px #068cfa solid;
            border-radius: 50%;
            animation: sp-anime 0.8s infinite linear;
        }

        @keyframes sp-anime {
            100% {
                transform: rotate(360deg);
            }
        }

        .is-hide {
            display: none;
        }
    </style>
@endpush



@push('script')
    <script>
        $(function() {
            'use strict'

            $('.daterange-btn').daterangepicker({
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')]
                },
                startDate: moment().subtract(29, 'days'),
                endDate: moment()
            }, function(start, end) {
                $('.daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format(
                    'MMMM D, YYYY'))
            });


            $('.ranges ul li').each(function(index) {
                $(this).on('click', function() {
                    let key = $(this).data('range-key')
                    $("#overlay").fadeIn(300);
                    $.ajax({
                        url: "{{ route('admin.withdraw.filter') }}",
                        data: {
                            key: key
                        },
                        method: "GET",
                        success: function(response) {

                            $('#filter_data').html(response);
                        },
                        complete: function() {
                            $("#overlay").fadeOut(300);
                        }

                    })


                })
            })


            $(document).on('click', '.applyBtn', function() {

                let date = $('.drp-selected').text()

                let key = 'Custom Range'

                $("#overlay").fadeIn(300);

                $.ajax({
                    url: "{{ route('admin.withdraw.filter') }}",
                    data: {
                        key: key,
                        date: date
                    },
                    method: "GET",
                    success: function(response) {

                        $('#filter_data').html(response);
                    },
                    complete: function() {
                        $("#overlay").fadeOut(300);
                    }

                })
            })
        })
    </script>
@endpush
