import google.generativeai as genai
import PIL.Image
import json
import os

# Konfigurasi API
API_KEY = "AIzaSyBnEeK2x66gsAr0ypwF_RQVbYkDdMR91XQ"
genai.configure(api_key=API_KEY)

def extract_toll_amount(file_path):
    """
    Fungsi untuk membaca gambar struk dan mengambil angka Tarif Tol.
    Mengembalikan JSON dengan jumlah_toll, status, dan confidence_level.
    """
    try:
        # Gunakan model Flash (Gratis, Cepat, Akurat untuk OCR)
        model = genai.GenerativeModel('gemini-1.5-flash')

        # Load file gambar
        sample_file = PIL.Image.open(file_path)

        # Prompt spesifik untuk Agent
        prompt = """
        Lihat gambar struk tol ini dengan teliti.
        Ekstrak nominal harga tarif tol yang dibayarkan.
        Cari teks "Rp" yang sejajar dengan kata "GOL", "TARIF", atau "TOTAL".
        Abaikan Sisa Saldo, Saldo Kartu, atau nomor seri kartu (SN).
        Kembalikan HANYA angka saja tanpa karakter lain, seperti "22000" untuk Rp 22.000.
        Jika tidak yakin, kembalikan "uncertain".
        """

        # Proses ke Gemini
        response = model.generate_content([prompt, sample_file])

        # Membersihkan hasil (menghilangkan karakter non-digit)
        raw_result = response.text.strip()

        if raw_result.lower() == "uncertain":
            return json.dumps({
                "jumlah_toll": None,
                "status": "error",
                "confidence_level": "low"
            })

        numeric_result = ''.join(filter(str.isdigit, raw_result))

        if not numeric_result:
            return json.dumps({
                "jumlah_toll": None,
                "status": "error",
                "confidence_level": "low"
            })

        # Tentukan confidence level berdasarkan panjang response atau logika sederhana
        confidence = "high" if len(numeric_result) >= 4 else "low"  # Asumsi tarif tol minimal 4 digit

        return json.dumps({
            "jumlah_toll": int(numeric_result),
            "status": "success",
            "confidence_level": confidence
        })

    except Exception as e:
        return json.dumps({
            "jumlah_toll": None,
            "status": "error",
            "confidence_level": "low"
        })

# Contoh penggunaan di project kamu:
# path = "path/ke/file/upload/user.png"
# print(extract_toll_amount(path))
