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

    'accepted'        => ':attribute는 받아들여야 한다.',
    'accepted_if'     => ':other가:value일 때:attribute는 수락해야 합니다.',
    'active_url'      => ':attribute는 유효한 URL이어야 합니다.',
    'after'           => ':attribute는 다음 날짜이어야 합니다.',
    'after_or_equal'  => ':attribute는 다음과 같은 날짜여야 합니다.',
    'alpha'           => ':attribute는 알파벳만 포함할 수 있습니다.',
    'alpha_dash'      => ':attribute는 문자, 숫자, 중간 줄 또는 밑줄만 포함할 수 있습니다.',
    'alpha_num'       => ':attribute는 문자와 숫자만 포함할 수 있습니다.',
    'array'           => ':attribute는 배열이어야 합니다.',
    'before'          => ':attribute는: date 이전 날짜여야 합니다.',
    'before_or_equal' => ':attribute는: date 이전 또는 동일한 날짜여야 합니다.',
    'between'         => [
        'numeric' => ':attribute는:min에서:max 사이여야 합니다.',
        'file'    => ':attribute는:min에서:max KB 사이여야 합니다.',
        'string'  => ':attribute는:min에서:max 사이에 있어야 합니다.',
        'array'   => ':attribute는:min에서:max 항목 사이에 있어야 합니다.',
    ],
    'boolean'          => ':attribute 문자는 true 또는 false이어야 합니다.',
    'confirmed'        => ':attribute 2차 확인 불일치.',
    'current_password' => '비밀번호가 올바르지 않습니다.',
    'date'             => ':attribute는 유효한 날짜여야 합니다.',
    'date_equals'      => ':attribute는:date와 같아야 합니다.',
    'date_format'      => ':attribute가 주어진 형식:format과 일치하지 않습니다.',
    'declined'         => ':attribute는 거부되어야 합니다.',
    'declined_if'      => ':other가:value.일 때:attribute는 거부되어야 합니다.',
    'different'        => ':attribute는:other.',
    'digits'           => ':attribute는 반드시: digits 자리수입니다.',
    'digits_between'   => ':attribute는:min과:max 비트 사이여야 합니다.',
    'dimensions'       => ':attribute에 잘못된 그림 크기가 있습니다.',
    'distinct'         => ':attribute 필드에 중복된 값이 있습니다.',
    'email'            => ':attribute는 유효한 이메일 주소여야 합니다.',
    'ends_with'        => ':attribute는:values로 끝나야 합니다.',
    'exists'           => '선택된: attribute는 유효하지 않습니다.',
    'file'             => ':attribute는 파일이어야 합니다.',
    'filled'           => ':attribute의 필드는 필수입니다.',
    'gt'               => [
        'numeric' => ':attribute는:value보다 커야 합니다.',
        'file'    => ':attribute는:value KB보다 커야 합니다.',
        'string'  => ':attribute:value 를 초과해야 합니다 . ',
        'array'   => ':attribute는:value 항목을 초과해야 합니다 . ',
    ],
    'gte' => [
        'numeric' => ':attribute는:value보다 크거나 같아야 합니다 . ',
        'file'    => ':attribute는 value KB보다 크거나 같아야 합니다 . ',
        'string'  => ':attribute는 value 문자보다 크거나 같아야 합니다 . ',
        'array'   => ':attribute는:value 항목보다 크거나 같아야 합니다 . ',
    ],
    'image'    => ':attribute는 이미지 형식이어야 합니다 . ',
    'in'       => '선택된: attribute는 유효하지 않습니다 . ',
    'in_array' => ':attribute 필드가 없습니다:other . ',
    'integer'  => ':attribute는 정수여야 합니다 . ',
    'ip'       => ':attribute는 유효한 IP 주소여야 합니다 . ',
    'ipv4'     => ':attribute는 유효한 IPv4 주소여야 합니다 . ',
    'ipv6'     => ':attribute는 유효한 IPv6 주소여야 합니다 . ',
    'json'     => ':attribute는 유효한 JSON 문자열이어야 합니다 . ',
    'lt'       => [
        'numeric' => ':attribute는:value보다 적어야 합니다 . ',
        'file'    => ':attribute는:value KB보다 작아야 합니다 . ',
        'string'  => ':attribute는 value보다 작아야 합니다 . ',
        'array'   => ':attribute는:value 항목보다 작아야 합니다 . ',
    ],
    'lte' => [
        'numeric' => ':attribute는:value 보다 적거나 같아야 합니다',
        'file'    => ':attribute는: value KB보다 적거나 같아야 합니다 . ',
        'string'  => ':attribute는 value 문자보다 적거나 같아야 합니다 . ',
        'array'   => ':attribute는:value 항목보다 적거나 같아야 합니다 . ',
    ],
    'max' => [
        'numeric' => ':attribute의 최대 길이는:max 자릿수 . ',
        'file'    => ':attribute의 최대 크기는:max KB . ',
        'string'  => ':attribute의 최대 길이는:max 문자입니다 . ',
        'array'   => ':attribute 최대값은:max 항목 . ',
    ],
    'mimes'     => ':attribute의 파일 형식은:values . ',
    'mimetypes' => ':attribute의 파일 형식은 다음과 같아야 합니다 . values . ',
    'min'       => [
        'numeric' => ':attribute의 최소 길이는:min 자리수 . ',
        'file'    => ':attribute의 크기는 최소한:min KB . ',
        'string'  => ':attribute의 최소 길이는:min 문자입니다 . ',
        'array'   => ':attribute 최소:min 항목 . ',
    ],
    'multiple_of'          => ':attribute는 value의 배수여야 합니다 . ',
    'not_in'               => '선택된: attribute는 유효하지 않습니다 . ',
    'not_regex'            => ':attribute 형식이 잘못되었습니다 . ',
    'numeric'              => ':attribute는 숫자여야 합니다 . ',
    'password'             => '비밀번호 오류 . ',
    'present'              => ':attribute 필드가 있어야 합니다 . ',
    'prohibited'           => ':attribute 필드는 금지되어 있습니다 . ',
    'prohibited_if'        => ':attribute 필드는 금지되어 있습니다:other는:value . ',
    'prohibited_unless'    => ':attribute 필드는 금지되어 있습니다:other는:values입니다 . ',
    'prohibits'            => ':attribute 필드는 사용할 수 없습니다:other . ',
    'regex'                => ':attribute 형식이 잘못되었습니다 . ',
    'required'             => ':attribute 필드는 필수입니다 . ',
    'required_if'          => ':attribute 필드 필수:other 예:value . ',
    'required_unless'      => ': attribute 필드는 필수입니다 .:other가:values에 있어야 합니다 . ',
    'required_with'        => ':attribute 필드가 필수:values가 존재합니다 . ',
    'required_with_all'    => ':attribute 필드가 필수:values가 존재합니다 . ',
    'required_without'     => ':attribute 필드는 필수입니다:values는 존재하지 않습니다 . ',
    'required_without_all' => ':attribute 필드는 필수입니다 . values는 존재합니다 . ',
    'same'                 => ':attribute와:other는 일치해야 합니다 . ',
    'size'                 => [
        'numeric' => ':attribute는 반드시:size 자리수여야 합니다 . ',
        'file'    => ':attribute 必须是 :size KB。',
        'string'  => ':attribute는 반드시:size 글자여야 합니다 . ',
        'array'   => ':attribute는 다음과 같은 항목을 포함해야 합니다 . ',
    ],
    'starts_with' => ':attribute는:values로 시작해야 합니다 . ',
    'string'      => ':attribute는 문자열이어야 합니다 . ',
    'timezone'    => ':attribute는 유효한 시간대여야 합니다 . ',
    'unique'      => ':attribute가 이미 존재합니다 . ',
    'uploaded'    => ':attribute 업로드 실패 . ',
    'url'         => ':attribute 형식이 잘못되었습니다 . ',
    'uuid'        => ':attribute는 유효한 UUID여야 합니다 . ',

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

    'custom' => [
        'attribute - name' => [
            'rule - name' => 'custom - message',
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

    'attributes' => [
        'descriptions . en . title'    => '영문 제목',
        'descriptions . zh_cn . title' => '중국어 제목',

        'tax_rate' => [
            'name' => '세금명',
            'type' => '유형',
            'rate' => '세율',
        ],
    ],

];
