@forelse($trades as $key => $trade)
    <tr>
        <td>{{ strtoupper($trade->ref) }}</td>
        <td>
            <a href="{{ route('admin.user.details', $trade->user->id) }}">
                <img src="{{ Config::getFile('user', $trade->user->image, true) }}" alt="" class="image-table">
                <span>
                    {{ $trade->user->username }}
                </span>
            </a>
        </td>
        <td>{{ $trade->currency }}</td>
        <td>{{ Config::formatter($trade->current_price) }}</td>

        <td>
            @if ($trade->trade_type == 'buy')
                <i class="fas fa-arrow-alt-circle-up text-success"></i>
                {{ $trade->trade_type }}
            @else
                <i class="fas fa-arrow-alt-circle-down text-danger"></i>
                {{ $trade->trade_type }}
            @endif
        </td>

        <td>
            {{ $trade->trade_stop_at }}
        </td>

        <td>
            @if ($trade->profit_type == '+')
                <span class="text-success font-weight-bolder">{{ __('+' . $trade->profit_amount) }}</span>
            @elseif($trade->profit_type == '-')
                <span class="text-danger font-weight-bolder">{{ __('-' . $trade->loss_amount) }}</span>
            @endif
        </td>

        <td>
            <form action="{{ route('admin.trade.result-mode', $trade->id) }}" method="POST">
                @csrf
                <select name="result_mode" class="form-control form-control-sm"
                    onchange="toggleTradeWinAmount(this)" @if ($trade->status) disabled @endif>
                    <option value="default" @if (($trade->result_mode ?? 'default') === 'default') selected @endif>{{ __('Default') }}</option>
                    <option value="force_win" @if (($trade->result_mode ?? 'default') === 'force_win') selected @endif>{{ __('Force Win') }}</option>
                    <option value="force_loss" @if (($trade->result_mode ?? 'default') === 'force_loss') selected @endif>{{ __('Force Loss') }}</option>
                </select>
                <div class="trade-win-amount-wrapper mt-2 @if (($trade->result_mode ?? 'default') !== 'force_win') d-none @endif">
                    <input type="number" name="force_profit_amount" class="form-control form-control-sm"
                        step="0.01" min="0"
                        value="{{ old('force_profit_amount', $trade->force_profit_amount) }}"
                        placeholder="{{ __('Win Amount') }}"
                        @if (($trade->result_mode ?? 'default') !== 'force_win' || $trade->status) disabled @endif>
                </div>
                @if (!$trade->status)
                    <button type="submit" class="btn btn-sm btn-primary mt-2">{{ __('Save') }}</button>
                @endif
            </form>
        </td>

        <td>
            @if ($trade->force_profit_amount !== null)
                <span class="font-weight-bolder">{{ Config::formatter($trade->force_profit_amount) }}</span>
            @else
                <span class="text-muted">{{ __('Auto') }}</span>
            @endif
        </td>

        <td>
            @if ($trade->status)
                <span class="text-success "><i class="far fa-check-circle font-weight-bolder"></i></span>
            @else
                <span class="text-danger "><i class="fas fa-spinner fa-spin font-weight-bolder"></i></span>
            @endif
        </td>

    </tr>
@empty
    <tr>
        <td class="text-center" colspan="100%">
            {{ __('No Trades Found') }}
        </td>
    </tr>
@endforelse
