@php
    $state = $getState();
    $url = Storage::url($state);
    $extension = pathinfo($state, PATHINFO_EXTENSION);
    $isPdf = strtolower($extension) === 'pdf';
@endphp

<div class="px-4 py-3">
    @if($state)
        @if($isPdf)
            <div class="flex items-center justify-center">
                <a 
                    href="{{ $url }}" 
                    target="_blank"
                    class="inline-flex items-center justify-center px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-colors"
                >
                    <x-heroicon-o-document class="w-5 h-5 mr-2" />
                    Lihat PDF
                </a>
            </div>
        @else
            <div class="relative group">
                <img 
                    src="{{ $url }}" 
                    alt="Preview"
                    class="w-20 h-20 object-cover rounded-lg shadow-sm cursor-pointer transition-transform hover:scale-105"
                    onclick="window.open('{{ $url }}', '_blank')"
                />
                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                    <div class="bg-black bg-opacity-50 rounded-lg p-2">
                        <x-heroicon-o-magnifying-glass-plus class="w-5 h-5 text-white" />
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="text-gray-400 text-sm">
            Tidak ada file
        </div>
    @endif
</div>