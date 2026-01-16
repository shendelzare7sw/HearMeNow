@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full bg-app-darker border border-gray-700 text-white placeholder-gray-500 rounded-lg shadow-sm focus:border-brand-orange focus:ring-brand-orange']) }}>