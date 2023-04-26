<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'             => ':attribute Harus diterima.',
    'accepted_if'          => 'sambil:other bagi :value Waktu，:attribute Harus diterima.',
    'active_url'           => ':attribute Harus berupa URL yang valid.',
    'after'                => ':attribute Harus :date Tanggal setelah itu.',
    'after_or_equal'       => ':attribute Harus :date Setelah atau tanggal yang sama.',
    'alpha'                => ':attribute Hanya dapat berisi huruf.',
    'alpha_dash'           => ':attribute Hanya dapat berisi huruf, angka, tanda hubung, atau garis bawah.',
    'alpha_num'            => ':attribute Hanya dapat berisi huruf dan angka.',
    'array'                => ':attribute Harus berupa array.',
    'before'               => ':attribute Harus :date Tanggal sebelumnya.',
    'before_or_equal'      => ':attribute Harus :date Sebelum atau tanggal yang sama.',
    'between'              => [
        'numeric' => ':attribute Harus :min tiba :max Antara.',
        'file'    => ':attribute Harus :min tiba :max KB Antara.',
        'string'  => ':attribute Harus :min tiba :max Karakter.',
        'array'   => ':attribute Harus :min tiba :max antar item.',
    ],
    'boolean'              => ':attribute Karakter harus true atau false。',
    'confirmed'            => ':attribute Konfirmasi kedua tidak cocok.',
    'current_password'     => 'Kata sandi tidak salah.',
    'date'                 => ':attribute Harus tanggal valid.',
    'date_equals'          => ':attribute Harus sama dengan :date。',
    'date_format'          => ':attribute dengan format yang diberikan :format Ketidaksesuaian.',
    'declined'             => ':attribute Harus ditolak.',
    'declined_if'          => 'sambil :other bagi :value.Waktu，:attribute Harus ditolak.',
    'different'            => ':attribute Harus berbeda dari :other。',
    'digits'               => ':attribute Harus :digits Jumlah digit.',
    'digits_between'       => ':attribute Harus :min dan :max antar bit.',
    'dimensions'           => ':attribute Memiliki ukuran gambar yang tidak valid.',
    'distinct'             => ':attribute Bidang memiliki nilai duplikat.',
    'email'                => ':attribute Harus alamat email yang valid.',
    'ends_with'            => ':attribute Harus dimulai dengan :values Berakhirlah.',
    'exists'               => 'Dipilih :attribute tidak valid.',
    'file'                 => ':attribute Harus berupa file.',
    'filled'               => ':attribute diperlukan.',
    'gt'                   => [
        'numeric' => ':attribute Harus lebih besar dari :value。',
        'file'    => ':attribute Harus lebih besar dari :value KB。',
        'string'  => ':attribute Harus melebihi :value Karakter.',
        'array'   => ':attribute Harus melebihi :value Benda.',
    ],
    'gte'                  => [
        'numeric' => ':attribute Harus lebih besar dari atau sama dengan :value。',
        'file'    => ':attribute Harus lebih besar dari atau sama dengan :value KB。',
        'string'  => ':attribute Harus lebih dari atau sama dengan :value Karakter.',
        'array'   => ':attribute Harus lebih dari atau sama dengan :value Benda.',
    ],
    'image'                => ':attribute Harus dalam format gambar.',
    'in'                   => 'Dipilih :attribute tidak valid.',
    'in_array'             => ':attribute Bidang tidak ada :other。',
    'integer'              => ':attribute Harus berupa bilangan bulat.',
    'ip'                   => ':attribute Harus alamat IP yang valid.',
    'ipv4'                 => ':attribute Harus alamat IPv4 yang valid.',
    'ipv6'                 => ':attribute Harus alamat IPv6 yang valid.',
    'json'                 => ':attribute Harus berupa string JSON yang valid.',
    'lt'                   => [
        'numeric' => ':attribute Harus kurang dari :value。',
        'file'    => ':attribute Harus kurang dari :value KB。',
        'string'  => ':attribute Harus kurang dari :value Karakter.',
        'array'   => ':attribute Harus kurang dari :value Benda.',
    ],
    'lte'                  => [
        'numeric' => ':attribute Harus kurang dari atau sama dengan :value。',
        'file'    => ':attribute Harus kurang dari atau sama dengan :value KB。',
        'string'  => ':attribute Harus kurang dari atau sama dengan :value Karakter.',
        'array'   => ':attribute Harus kurang dari atau sama dengan :value Benda.',
    ],
    'max'                  => [
        'numeric' => ':attribute Panjang maksimum adalah :max Jumlah digit.',
        'file'    => ':attribute Ukurannya hingga :max KB。',
        'string'  => ':attribute Panjang maksimum adalah :max Watak.',
        'array'   => ':attribute Maksimal adalah :max Benda.',
    ],
    'mimes'                => ':attribute Jenis file harus :values。',
    'mimetypes'            => ':attribute Jenis file harus :values。',
    'min'                  => [
        'numeric' => ':attribute Panjang minimum adalah :min Jumlah digit.',
        'file'    => ':attribute Ukurannya setidaknya :min KB。',
        'string'  => ':attribute Panjang minimum adalah :min Watak.',
        'array'   => ':attribute Setidaknya :min Benda.',
    ],
    'multiple_of'          => ':attribute Harus :value kelipatan .',
    'not_in'               => 'Dipilih :attribute tidak valid.',
    'not_regex'            => ':attribute Format tidak valid.',
    'numeric'              => ':attribute Harus berupa angka.',
    'password'             => 'Kata sandi salah.',
    'present'              => ':attribute Bidang harus ada.',
    'prohibited'           => ':attribute Bidang dilarang.',
    'prohibited_if'        => ':attribute Bidang dilarang ketika :other bagi :value。',
    'prohibited_unless'    => ':attribute Bidang dilarang kecuali :other Termasuk :values。',
    'prohibits'            => ':attribute Bidang dilarang :other。',
    'regex'                => ':attribute Format tidak valid.',
    'required'             => ':attribute Bidang ini wajib diisi.',
    'required_if'          => ':attribute Bidang ini wajib diisi ketika :other ada :value。',
    'required_unless'      => ':attribute Bidang wajib diisi kecuali :other Ya :values Tengah.',
    'required_with'        => ':attribute Bidang ini wajib diisi ketika :values Ya ada.',
    'required_with_all'    => ':attribute Bidang ini wajib diisi ketika :values Ya ada.',
    'required_without'     => ':attribute Bidang ini wajib diisi ketika :values Tidak ada keberadaan.',
    'required_without_all' => ':attribute Bidang ini wajib diisi ketika Tidak satu :values Ya ada.',
    'same'                 => ':attribute dan :other Harus cocok.',
    'size'                 => [
        'numeric' => ':attribute Harus :size Jumlah digit.',
        'file'    => ':attribute Harus :size KB。',
        'string'  => ':attribute Harus :size Karakter.',
        'array'   => ':attribute Harus mencakup: :size Benda.',
    ],
    'starts_with'          => ':attribute Harus dimulai dengan :values Mulai.',
    'string'               => ':attribute Harus berupa tali.',
    'timezone'             => ':attribute Harus zona waktu yang valid.',
    'unique'               => ':attribute Sudah ada.',
    'uploaded'             => ':attribute Upload gagal.',
    'url'                  => ':attribute Format tidak valid.',
    'uuid'                 => ':attribute Itu harus menjadi salah satu yang efektif UUID。',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom'               => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes'           => [
        'descriptions.en.title'    => 'Judul bahasa Inggris',
        'descriptions.zh_cn.title' => 'Judul Cina',

        'tax_rate'                 => [
            'name' => 'Nama pajak',
            'type' => 'tipe',
            'rate' => 'tarif pajak',
        ],
    ],

];
