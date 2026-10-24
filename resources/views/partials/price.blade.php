{{-- Param: $price (OTR), $disc (diskon cash), $big (opsional). OTR dicoret + harga cash jika ada diskon. --}}
@php($big = $big ?? false)
@if($price <= 0)
    <span class="{{ $big ? 'text-xl' : 'text-sm' }} font-extrabold text-honda">Hubungi kami</span>
@elseif($disc > 0)
    <div class="flex flex-wrap items-baseline gap-x-2">
        <s class="{{ $big ? 'text-base' : 'text-xs' }} font-semibold text-zinc-400">{{ \App\Support\Helpers::rp($price) }}</s>
        <span class="{{ $big ? 'text-xl' : 'text-base md:text-lg' }} font-extrabold text-honda">{{ \App\Support\Helpers::rp($price - $disc) }}</span>
    </div>
@else
    <span class="{{ $big ? 'text-xl' : 'text-base md:text-lg' }} font-extrabold text-honda">{{ \App\Support\Helpers::rp($price) }}</span>
@endif
