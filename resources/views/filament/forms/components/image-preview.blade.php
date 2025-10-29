<x-filament-forms::field-wrapper
    :id="$getId()"
    :label="$getLabel()"
    :label-sr-only="$isLabelHidden()"
    :helper-text="$getHelperText()"
    :hint="$getHint()"
    :hint-color="$getHintColor()"
    :hint-icon="$getHintIcon()"
    :required="$isRequired()"
    :state-path="$getStatePath()"
>
    @if ($getState())
        <div style="border: 2px solid #e5e7eb; border-radius: 8px; padding: 10px; background-color: #f9fafb; display: inline-block;">
            <img
                src="{{ asset('storage/' . $getState()) }}"
                alt="Foto Kendaraan"
                style="max-width: 200px; max-height: 200px; border-radius: 4px; display: block;"
            >
        </div>
    @else
        <div style="border: 2px dashed #d1d5db; border-radius: 8px; padding: 20px; background-color: #f9fafb; text-align: center; color: #6b7280; font-style: italic;">
            Foto kendaraan akan muncul otomatis
        </div>
    @endif
</x-filament-forms::field-wrapper>
