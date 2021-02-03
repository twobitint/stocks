<li class="row fw-bold border-top m-0" style="padding-top: 10px; padding-bottom: 10px;">
    <div class="col-5 ps-0" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
        @if ($position->symbol)
            <span class="badge" style="width: 60px; background-color: #{{ $position->color }};">
                {{ $position->symbol }}
            </span>
        @endif
        <span style="font-size: 0.7em;">
            {{ $position->description }}
        </span>
    </div>
    <div class="col-2 text-end">
        {{ App\Util::money($position->value) }}
    </div>
    @if (!is_null($position->today_gain_dollar))
        <div class="col-2 text-end">
            @if ($position->today_gain_dollar >= 0)
                <span style="color: #137333;">
                    {{ App\Util::money($position->today_gain_dollar) }}
                </span>
            @else
                <span style="color: #a50e0e;">
                    {{ App\Util::money($position->today_gain_dollar) }}
                </span>
            @endif
        </div>
        <div class="col-3 text-end pe-0">
            @if ($position->today_gain_percent >= 0)
                <span class="badge"
                    style="background-color: #e6f4ea; color: #137333; margin-top: -0.25em; font-size: 1rem;">
                    <i class="fas fa-arrow-up"></i>
                    {{ number_format(abs($position->today_gain_percent), 2) }}%
                </span>
            @else
                <span class="badge"
                    style="background-color: #fce8e6; color: #a50e0e; margin-top: -0.25em; font-size: 1rem;">
                    <i class="fas fa-arrow-down"></i>
                    {{ number_format(abs($position->today_gain_percent), 2) }}%
                </span>
            @endif
        </div>
    @endif
</li>
