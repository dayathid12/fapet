# TODO: Modify Surat Tugas PDF Route to Use no_surat_tugas Parameter

## Completed Steps
- [x] Update routes/web.php to change route parameter from {record} to {no_surat_tugas}
- [x] Modify PdfController::generateSuratTugas to accept $no_surat_tugas and find Perjalanan by no_surat_tugas
- [x] Update SuratTugasResource download action to use no_surat_tugas parameter and make it visible only when no_surat_tugas is set

## Summary
The route has been changed from /surat-tugas/{id}/pdf to /surat-tugas/{no_surat_tugas}/pdf. The controller now looks up the Perjalanan record by the no_surat_tugas column instead of using model binding by ID. The Filament resource action has been updated accordingly.

# TODO: Auto-generate no_surat_tugas in format {number}/UN6.4.2.1/KP.00/{year}

## Completed Steps
- [x] Add auto-generation logic in Perjalanan model boot method to create no_surat_tugas if empty

## Summary
The no_surat_tugas field will now be automatically generated in the format "1/UN6.4.2.1/KP.00/2025" (incrementing number per year) when creating a new Perjalanan record, if not provided.
