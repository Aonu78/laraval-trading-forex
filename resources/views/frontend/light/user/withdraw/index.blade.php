@extends(Config::theme() . 'layout.auth')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="sp_site_card">
                <div class="card-header">
                    <h4 class="mb-0">
                        {{ __('Current Balance: ') }} <span
                            class="color-change">{{ Config::formatter(auth()->user()->balance, 2) }}</span>
                    </h4>
                    <p class="mb-0 mt-2">
                        {{ __('Freeze Balance: ') }} <span class="color-change">{{ Config::formatter(auth()->user()->freeze_balance, 2) }}</span>
                    </p>
                    <p class="mb-0 mt-2">
                        {{ __('Contact Your Mentor') }}
                    </p>
                </div>
                <div class="card-body">
                    @if (auth()->user()->is_account_freeze)
                        <div class="alert alert-danger">
                            {{ __('Your account is frozen. You cannot request a withdrawal right now.') }}
                        </div>
                    @endif
                    <form action="" method="post">
                        @csrf

@if(isset($hasPending) && $hasPending)
    <div class="alert alert-warning mb-3">
        {{ __('You have pending withdrawal request(s). Please wait 10-20 minutes for processing.') }}
    </div>
    <div class="table-responsive mb-4">
        <h6 class="mb-2">Recent Pending Withdrawals:</h6>
        <table class="table table-sm">
            <thead>
                <tr>
                    <th>{{ __('Trx') }}</th>
                    <th>{{ __('Date') }}</th>
                    <th>{{ __('Method') }}</th>
                    <th>{{ __('Getable Amount') }}</th>
                    <th>{{ __('Status') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingWithdraws->take(5) as $withdraw)
                    <tr>
                        <td>{{ $withdraw->trx }}</td>
                        <td>{{ $withdraw->created_at->format('d M Y') }}</td>
                        <td>{{ optional($withdraw->withdrawMethod)->name ?? 'N/A' }}</td>
                        <td>{{ number_format($withdraw->total, 2) }}</td>
                        <td><span class="badge badge-warning">{{ __('Pending') }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center">{{ __('No pending found') }}</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <a href="{{ route('user.withdraw.pending') }}" class="btn btn-sm btn-outline-warning mb-3">{{ __('View All Pending') }}</a>
@endif

<div class="form-group">
                            <label for="">{{ __('Withdraw Method') }}</label>
                            <select name="method" id="" class="form-select">
                                <option value="" selected>{{ __('Select Method') }}</option>
                                @foreach ($withdraws as $withdraw)
                                    <option value="{{ $withdraw->id }}"
                                        data-url="{{ route('user.withdraw.fetch', $withdraw->id) }}">
                                        {{ $withdraw->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="row appendData"></div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6 withdraw-ins">
            <div class="sp_site_card">
                <div class="card-header">
                    <h4 class="mb-0">{{ __('Withdraw Instruction') }}</h4>
                </div>
                <div class="card-body">
                    <p class="instruction"></p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(function() {
            'use strict'

            $('select[name=method]').on('change', function() {
                if ($(this).val() == '') {
                    $('.appendData').addClass('d-none');
                    $('.instruction').text('');
                    return;
                }

                @if (auth()->user()->is_account_freeze)
                    $('.appendData').addClass('d-none');
                    return;
                @endif

                $('.appendData').removeClass('d-none');
                getData($('select[name=method] option:selected').data('url'))
            })

            $(document).on('keyup', '.amount', function() {
                const withdraw_charge_type = $('.withdraw_charge_type').text();

                if ($(this).val() == '') {
                    $('.final_amo').val(0);
                    return
                }

                const charge = $('.charge').val();

                if (withdraw_charge_type.localeCompare("percent") == 1) {
                    let percentAmount = Number.parseFloat($(this).val()) - Number.parseFloat((charge * $(
                        this).val()) / 100);

                    $('.final_amo').val(percentAmount.toFixed(2));
                    return
                }
                if (withdraw_charge_type.localeCompare("fixed") == 1) {

                    let totalAmount = Number.parseFloat($(this).val()) - Number.parseFloat(charge);

                    $('.final_amo').val(totalAmount).toFixed(2);
                }



            })

            function getData(url) {
                $.ajax({
                    url: url,
                    method: "GET",
                    success: function(response) {

                        $('.instruction').html(response.instruction)
                        let html = `

                                <div class="col-md-12 mb-3 mt-3">
                                    <label for="">{{ __('Withdraw Amount') }} <span class="sp_text_danger">*</span></label>
                                    <input type="text" name="amount" class="form-control amount" required>
                                    <p class="text-small color-change mb-0 mt-1"><span>{{ __('Min Amount ') }}  ${Number.parseFloat(response.min_withdraw_amount).toFixed(2)}</span> <span>{{ __('& Max Amount') }} ${Number.parseFloat(response.max_withdraw_amount).toFixed(2)}</span></p>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label>{{ __('Withdraw Charge') }}</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control charge" value="${Number.parseFloat(response.charge).toFixed(2)}" required disabled>
                                        <div class="input-group-text sp_bg_main text-white border-0">
                                            <span class="withdraw_charge_type">${response.type}<span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="">{{ __('Getable Amount') }} <span class="sp_text_danger">*</span></label>
                                    <input type="text" name="final_amo" class="form-control final_amo" required readonly>
                                </div>

                                <div class="col-md-12 mb-3 d-none">
                                    <label for="">{{ __('Account Email / Wallet Address') }} <span class="sp_text_danger">*</span></label>
                                    <input type="text" name="email" class="form-control" value="{{ auth()->user()->email }}" required>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">{{ __('Currency') }}</label>
                                    <select name="currency" class="form-select">
                                        <option value="{{ Config::config()->currency }}" selected>{{ Config::config()->currency }}</option>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">{{ __('Account Holder Name') }}</label>
                                    <input type="text" name="account_holder_name" class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">{{ __('Bank Name') }}</label>
                                    <input type="text" name="bank_name" class="form-control">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="">{{ __('Bank Account Number') }}</label>
                                    <input type="text" name="bank_account_number" class="form-control">
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="">{{ __('IFSC Code') }}</label>
                                    <input type="text" name="ifsc_code" class="form-control">
                                </div>

                              

                                <div class="col-md-12 mt-2">
                                   <button class="btn sp_theme_btn w-100" type="submit" {{ auth()->user()->is_account_freeze ? 'disabled' : '' }}>{{ __('Withdraw Now') }}</button>
                                </div>
                   `;

                        $('.appendData').html(html);
                    }
                })
            }
        })
    </script>
@endpush
