@php $editing = isset($order) @endphp

<div class="flex flex-wrap">
    <x-inputs.group class="w-full">
        <x-inputs.text
            name="code"
            label="Code"
            value="{{ old('code', ($editing ? $order->code : '')) }}"
            maxlength="255"
            placeholder="Code"
            required
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.select name="product_id" label="Product" required>
            @php $selected = old('product_id', ($editing ? $order->product_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Product</option>
            @foreach($products as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.select name="user_id" label="User" required>
            @php $selected = old('user_id', ($editing ? $order->user_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the User</option>
            @foreach($users as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.select name="payment_id" label="Payment" required>
            @php $selected = old('payment_id', ($editing ? $order->payment_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Payment</option>
            @foreach($payments as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.text
            name="quantity"
            label="Quantity"
            value="{{ old('quantity', ($editing ? $order->quantity : '')) }}"
            maxlength="255"
            placeholder="Quantity"
            required
        ></x-inputs.text>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.number
            name="total"
            label="Total"
            value="{{ old('total', ($editing ? $order->total : '')) }}"
            max="255"
            step="0.01"
            placeholder="Total"
            required
        ></x-inputs.number>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.date
            name="date"
            label="Date"
            value="{{ old('date', ($editing ? optional($order->date)->format('Y-m-d') : '')) }}"
            max="255"
            required
        ></x-inputs.date>
    </x-inputs.group>

    <x-inputs.group class="w-full">
        <x-inputs.select name="status" label="Status">
            @php $selected = old('status', ($editing ? $order->status : 'Menunggu Pembayaran')) @endphp
            <option value="Menunggu Konfirmasi" {{ $selected == 'Menunggu Konfirmasi' ? 'selected' : '' }} >Menunggu konfirmasi</option>
            <option value="Pembayaran Dikonfirmasi" {{ $selected == 'Pembayaran Dikonfirmasi' ? 'selected' : '' }} >Pembayaran dikonfirmasi</option>
            <option value="Menunggu Pembayaran" {{ $selected == 'Menunggu Pembayaran' ? 'selected' : '' }} >Menunggu pembayaran</option>
            <option value="Dibatalkan" {{ $selected == 'Dibatalkan' ? 'selected' : '' }} >Dibatalkan</option>
            <option value="Selesai" {{ $selected == 'Selesai' ? 'selected' : '' }} >Selesai</option>
        </x-inputs.select>
    </x-inputs.group>
</div>
