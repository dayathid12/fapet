# TODO List for Peminjaman Status Page Updates

## Task Overview
Update the stepper logic in `resources/views/peminjaman-status.blade.php` to reflect specific stages: Pengajuan, Keputusan, and Penugasan based on the provided requirements.

## Requirements Breakdown
1. **Pengajuan (Step 1)**: If status is "Menunggu Persetujuan", show "Permohonan sedang diproses".
2. **Keputusan (Step 3)**: 
   - If status is "Terjadwal", show check icon with "Permohonan Disetujui".
   - If status is "Ditolak", show red cross icon.
3. **Penugasan (Step 4)**: 
   - If departure time equals today, show green icon with "Sedang melakukan pelayanan".
   - If return time is today or later, show blue checklist icon with "Pelayanan selesai".

## Implementation Steps
- [ ] Update Step 1 (Pengajuan) logic to display "Permohonan sedang diproses" when status is "Menunggu Persetujuan".
- [ ] Update Step 3 (Keputusan) logic to show appropriate icons and text based on status.
- [ ] Update Step 4 (Penugasan) logic with date-based conditions using Carbon for icons and text.
- [ ] Ensure all changes are properly integrated into the existing stepper structure.
- [ ] Test the updated logic to verify correct display based on different statuses and dates.

## Files to Modify
- `resources/views/peminjaman-status.blade.php`

## Notes
- Use Carbon for date comparisons in Step 4.
- Maintain existing styling and structure.
- Ensure icons and text match the requirements exactly.
