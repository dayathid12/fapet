# TODO: Add Total Rincian Biayas Column to EntryPengeluaranResource

- [x] Modify getEloquentQuery method to include withSum for total_rincian_biayas (sum of all biaya from rincianBiayas)
- [x] Add new TextColumn in table method to display the total with label 'Total Rincian Biayas', money format IDR, sortable

**COMPLETED**: The Total Rincian Biayas column has been successfully added to the EntryPengeluaranResource table. The column displays the sum of all biaya from rincianBiayas relationship, formatted as IDR currency, and is sortable.
