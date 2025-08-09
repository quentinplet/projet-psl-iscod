@props(['disabled' => false, 'errors', 'label' => false])

<?php
$errorClasses = 'border-red-600 focus:border-red-600 ring-1 ring-red-600 focus:ring-red-600';
$defaultClasses = '';
$successClasses = 'border-emerald-500 focus:border-emerald-500 ring-1 ring-emerald-500 focus:ring-emerald-500';

$attributeName = preg_replace('/(\w+)\[(\w+)]/', '$1.$2', $attributes['name']);
?>

@if ($label)
    <label>{{ $label }}</label>
@endif
<select {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge([
        'class' =>
            'border-2 border-gray-300 focus:border-purple-500 focus:outline-none focus:ring-purple-500 rounded-md w-full p-2 bg-white' .
            ($errors->has($attributeName) ? $errorClasses : (old($attributes['name']) ? $successClasses : $defaultClasses)),
    ]) }}>
    {{ $slot }}
</select>
@error($attributeName)
    <small class="text-red-600">{{ $message }}</small>
@enderror
