(function (global, factory) {
  if (typeof define === "function" && define.amd) {
    define('element/locale/id', ['module', 'exports'], factory);
  } else if (typeof exports !== "undefined") {
    factory(module, exports);
  } else {
    var mod = {
      exports: {}
    };
    factory(mod, mod.exports);
    global.ELEMENT.lang = global.ELEMENT.lang || {};
    global.ELEMENT.lang.id = mod.exports;
  }
})(this, function (module, exports) {
  'use strict';

  exports.__esModule = true;
  exports.default = {
    el: {
      colorpicker: {
        confirm: 'Mengkonfirmasi',
        clear: 'kosong'
      },
      datepicker: {
        now: 'Sekarang',
        today: 'Hari Ini',
        cancel: 'Membatalkan',
        clear: 'kosong',
        confirm: 'Mengkonfirmasi',
        selectDate: 'Pilih Tanggal',
        selectTime: 'Pilih waktu',
        startDate: 'Tanggal mulai',
        startTime: 'waktu mulai',
        endDate: 'Tanggal Akhir',
        endTime: 'akhir zaman',
        prevYear: 'tahun sebelumnya',
        nextYear: 'Tahun setelah',
        prevMonth: 'Bulan lalu',
        nextMonth: 'Bulan depan',
        year: 'tahun',
        month1: 'Januari',
        month2: 'Februari',
        month3: 'Maret',
        month4: 'April',
        month5: 'Mungkin',
        month6: 'Juni',
        month7: 'Juli',
        month8: 'Agustus',
        month9: 'September',
        month10: 'Oktober',
        month11: 'November',
        month12: 'Desember',
        // week: '周次',
        weeks: {
          sun: 'hari',
          mon: 'satu',
          tue: 'dua',
          wed: 'tiga',
          thu: 'Empat',
          fri: 'Lima',
          sat: 'Enam'
        },
        months: {
          jan: 'Januari',
          feb: 'Februari',
          mar: 'Maret',
          apr: 'April',
          may: 'Mungkin',
          jun: 'Juni',
          jul: 'Juli',
          aug: 'Agustus',
          sep: 'September',
          oct: 'Oktober',
          nov: 'November',
          dec: 'Desember'
        }
      },
      select: {
        loading: 'Loading',
        noMatch: 'Tidak ada data yang cocok',
        noData: 'Tidak ada informasi',
        placeholder: 'Silakan pilih'
      },
      cascader: {
        noMatch: 'Tidak ada data yang cocok',
        loading: 'Loading',
        placeholder: 'Silakan pilih',
        noData: 'Tidak ada informasi'
      },
      pagination: {
        goto: 'menuju',
        pagesize: 'Item/Halaman',
        total: 'biasa {total} benda',
        pageClassifier: 'halaman'
      },
      messagebox: {
        title: 'cepat',
        confirm: 'Kamu yakin',
        cancel: 'Membatalkan',
        error: 'Data yang dimasukkan tidak sesuai dengan peraturan!'
      },
      upload: {
        deleteTip: 'Tekan tombol Delete untuk menghapus',
        delete: 'Menghapus',
        preview: 'Lihat gambar',
        continue: 'Lanjutkan mengunggah'
      },
      table: {
        emptyText: 'Tidak ada informasi yang tersedia',
        confirmFilter: 'Menyaring',
        resetFilter: 'Reset',
        clearFilter: 'semua',
        sumText: 'Jumlah' // to be translated
      },
      tree: {
        emptyText: 'Tidak ada informasi yang tersedia'
      },
      transfer: {
        noMatch: 'Tidak ada data yang cocok',
        noData: 'Tidak ada informasi',
        titles: ['Daftar 1', 'Daftar 2'], // to be translated
        filterPlaceholder: 'Masukkan kata kunci', // to be translated
        noCheckedFormat: '{total} Item', // to be translated
        hasCheckedFormat: '{checked}/{total} Diperiksa' // to be translated
      },
      image: {
        error: 'Beban gagal'
      },
      pageHeader: {
        title: 'kembali'
      },
      popconfirm: {
        confirmButtonText: 'Ya', // to be translated
        cancelButtonText: 'Tidak' // to be translated
      },
      empty: {
        description: 'Tidak ada informasi yang tersedia'
      }
    }
  };
  module.exports = exports['default'];
});
