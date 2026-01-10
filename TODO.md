# TODO: Update Tanggal Penugasan Logic

## Tasks
- [x] Edit SPTJBUangPengemudiDetailsRelationManager.php to simplify afterStateUpdated for nomor_perjalanan field
  - Remove complex tipe_penugasan logic
  - Directly calculate dates from perjalanan->waktu_keberangkatan to perjalanan->waktu_kepulangan
  - Set tanggal_penugasan as comma-separated days (e.g., "1,2,3")
- [ ] Verify the change works as expected (manual testing after implementation)

# TODO: Fix Gemini Toll Receipt Extraction

## Tasks
- [x] Add Gemini API key configuration to config/services.php
- [x] Update extractAmountFromReceipt method to accept file path instead of UploadedFile and read from storage
- [x] Ensure GEMINI_API_KEY is set in .env file
- [x] Create TollOcrController with extract method for API endpoint
- [x] Add API route for OCR functionality
- [x] Add JavaScript to view for auto-filling toll amount field
- [ ] Test the toll receipt upload and auto-fill functionality
- [ ] Verify that saving the form stores the data correctly in the database
