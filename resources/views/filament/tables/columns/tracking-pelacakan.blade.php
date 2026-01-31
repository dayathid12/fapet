@php
    $record = $getRecord();
    $tglInputSurgas = $record->tgl_input_surat_tugas;
    $tglUploadSurgas = $record->tgl_upload_surat_tugas;

    // Awaiting confirmation on this logic
    $sptjbExists = false; 
    $tglSptjb = null;

@endphp

<div class="flex space-x-2">
    {{-- Checklist 1: No. Surat Tugas --}}
    <div class="flex flex-col items-center justify-center p-2 rounded-lg w-32
        @if($record->no_surat_tugas) bg-green-100 dark:bg-green-800 @else bg-gray-100 dark:bg-gray-800 @endif">
        @if($record->no_surat_tugas)
            <x-heroicon-o-check-circle class="w-8 h-8 text-green-500"/>
            <span class="text-xs text-center mt-1 text-gray-600 dark:text-gray-300">
                @if($tglInputSurgas)
                    {{ \Carbon\Carbon::parse($tglInputSurgas)->format('d/m/y H:i') }}
                @else
                    -
                @endif
            </span>
        @else
            <x-heroicon-o-x-circle class="w-8 h-8 text-gray-400"/>
            <span class="text-xs text-center mt-1 text-gray-500 dark:text-gray-400">-</span>
        @endif
        <span class="text-xs font-medium text-center mt-1 text-gray-700 dark:text-gray-200">No. Surgas</span>
    </div>

    {{-- Checklist 2: Scan Surat Tugas --}}
    <div class="flex flex-col items-center justify-center p-2 rounded-lg w-32
        @if($record->upload_surat_tugas) bg-green-100 dark:bg-green-800 @else bg-gray-100 dark:bg-gray-800 @endif">
        @if($record->upload_surat_tugas)
            <x-heroicon-o-check-circle class="w-8 h-8 text-green-500"/>
            <span class="text-xs text-center mt-1 text-gray-600 dark:text-gray-300">
                @if($tglUploadSurgas)
                    {{ \Carbon\Carbon::parse($tglUploadSurgas)->format('d/m/y H:i') }}
                @else
                    -
                @endif
            </span>
        @else
            <x-heroicon-o-x-circle class="w-8 h-8 text-gray-400"/>
            <span class="text-xs text-center mt-1 text-gray-500 dark:text-gray-400">-</span>
        @endif
        <span class="text-xs font-medium text-center mt-1 text-gray-700 dark:text-gray-200">Scan Surgas</span>
    </div>

    {{-- Checklist 3: SPTJB --}}
    <div class="flex flex-col items-center justify-center p-2 rounded-lg w-32
        @if($sptjbExists) bg-green-100 dark:bg-green-800 @else bg-gray-100 dark:bg-gray-800 @endif">
        @if($sptjbExists)
            <x-heroicon-o-check-circle class="w-8 h-8 text-green-500"/>
            <span class="text-xs text-center mt-1 text-gray-600 dark:text-gray-300">
                @if($tglSptjb)
                    {{ \Carbon\Carbon::parse($tglSptjb)->format('d/m/y H:i') }}
                @else
                    -
                @endif
            </span>
        @else
            <x-heroicon-o-x-circle class="w-8 h-8 text-gray-400"/>
            <span class="text-xs text-center mt-1 text-gray-500 dark:text-gray-400">-</span>
        @endif
        <span class="text-xs font-medium text-center mt-1 text-gray-700 dark:text-gray-200">SPTJB</span>
    </div>
</div>
