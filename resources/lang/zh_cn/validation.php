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

    'accepted'             => ':attribute 必须接受。',
    'accepted_if'          => '当:other为:value时，:attribute 必须接受。',
    'active_url'           => ':attribute 必须是一个有效的 URL。',
    'after'                => ':attribute 必须是 :date 之后的一个日期。',
    'after_or_equal'       => ':attribute 必须是 :date 之后或相同的一个日期。',
    'alpha'                => ':attribute 只能包含字母。',
    'alpha_dash'           => ':attribute 只能包含字母、数字、中划线或下划线。',
    'alpha_num'            => ':attribute 只能包含字母和数字。',
    'array'                => ':attribute 必须是一个数组。',
    'before'               => ':attribute 必须是 :date 之前的一个日期。',
    'before_or_equal'      => ':attribute 必须是 :date 之前或相同的一个日期。',
    'between'              => [
        'numeric' => ':attribute 必须在 :min 到 :max 之间。',
        'file'    => ':attribute 必须在 :min 到 :max KB 之间。',
        'string'  => ':attribute 必须在 :min 到 :max 个字符之间。',
        'array'   => ':attribute 必须在 :min 到 :max 项之间。',
    ],
    'boolean'              => ':attribute 字符必须是 true 或 false。',
    'confirmed'            => ':attribute 二次确认不匹配。',
    'current_password'     => '密码不正确。',
    'date'                 => ':attribute 必须是一个有效的日期。',
    'date_equals'          => ':attribute 必须相同于 :date。',
    'date_format'          => ':attribute 与给定的格式 :format 不符合。',
    'declined'             => ':attribute 必须被拒绝。',
    'declined_if'          => '当:other为:value.时，:attribute 必须被拒绝。',
    'different'            => ':attribute 必须不同于 :other。',
    'digits'               => ':attribute 必须是 :digits 位数。',
    'digits_between'       => ':attribute 必须在 :min 和 :max 位之间。',
    'dimensions'           => ':attribute 具有无效的图片尺寸。',
    'distinct'             => ':attribute 字段具有重复值。',
    'email'                => ':attribute 必须是一个有效的电子邮件地址。',
    'ends_with'            => ':attribute 必须以 :values 结束。',
    'exists'               => '选定的 :attribute 是无效的。',
    'file'                 => ':attribute 必须是一个文件。',
    'filled'               => ':attribute 的字段是必填的。',
    'gt'                   => [
        'numeric' => ':attribute 必须大于 :value。',
        'file'    => ':attribute 必须大于 :value KB。',
        'string'  => ':attribute 必须超过 :value 个字符。',
        'array'   => ':attribute 必须超过 :value 项。',
    ],
    'gte'                  => [
        'numeric' => ':attribute 必须大于或相同于 :value。',
        'file'    => ':attribute 必须大于或相同于 :value KB。',
        'string'  => ':attribute 必须超过或相同于 :value 个字符。',
        'array'   => ':attribute 必须超过或相同于 :value 项。',
    ],
    'image'                => ':attribute 必须是图片格式。',
    'in'                   => '选定的 :attribute 是无效的。',
    'in_array'             => ':attribute 字段不存在于 :other。',
    'integer'              => ':attribute 必须是个整数。',
    'ip'                   => ':attribute 必须是一个有效的 IP 地址。',
    'ipv4'                 => ':attribute 必须是个有效的 IPv4 地址。',
    'ipv6'                 => ':attribute 必须是个有效的 IPv6 地址。',
    'json'                 => ':attribute 必须是个有效的 JSON 字符串。',
    'lt'                   => [
        'numeric' => ':attribute 必须少于 :value。',
        'file'    => ':attribute 必须少于 :value KB。',
        'string'  => ':attribute 必须少于 :value 个字符。',
        'array'   => ':attribute 必须少于 :value 项。',
    ],
    'lte'                  => [
        'numeric' => ':attribute 必须少于或等于 :value。',
        'file'    => ':attribute 必须少于或等于 :value KB。',
        'string'  => ':attribute 必须少于或等于 :value 个字符。',
        'array'   => ':attribute 必须少于或等于 :value 项。',
    ],
    'max'                  => [
        'numeric' => ':attribute 的最大值为 :max 。',
        'file'    => ':attribute 的大小最多为 :max KB。',
        'string'  => ':attribute 的最大长度为 :max 字符。',
        'array'   => ':attribute 最多为 :max 项。',
    ],
    'mimes'                => ':attribute 的文件类型必须是 :values。',
    'mimetypes'            => ':attribute 的文件类型必须是 :values。',
    'min'                  => [
        'numeric' => ':attribute 的最小值为 :min。',
        'file'    => ':attribute 的大小至少为 :min KB。',
        'string'  => ':attribute 的最小长度为 :min 字符。',
        'array'   => ':attribute 至少有 :min 项。',
    ],
    'multiple_of'          => ':attribute 必须是 :value 的倍数。',
    'not_in'               => '选定的 :attribute 是无效的。',
    'not_regex'            => ':attribute 的格式是无效的。',
    'numeric'              => ':attribute 必须是数字。',
    'password'             => '密码错误。',
    'present'              => ':attribute 字段必须存在。',
    'prohibited'           => ':attribute 字段是被禁止的。',
    'prohibited_if'        => ':attribute 字段是被禁止的当:other为:value。',
    'prohibited_unless'    => ':attribute 字段是被禁止的除非 :other 属于 :values。',
    'prohibits'            => ':attribute 字段是被禁止的 :other。',
    'regex'                => ':attribute 格式是无效的。',
    'required'             => '请输入 :attribute',
    'required_if'          => ':attribute 字段是必须的当 :other 是 :value。',
    'required_unless'      => ':attribute 字段是必须的，除非 :other 是在 :values 中。',
    'required_with'        => ':attribute 字段是必须的当 :values 是存在的。',
    'required_with_all'    => ':attribute 字段是必须的当 :values 是存在的。',
    'required_without'     => ':attribute 字段是必须的当 :values 是不存在的。',
    'required_without_all' => ':attribute 字段是必须的当 没有一个 :values 是存在的。',
    'same'                 => ':attribute和:other必须匹配。',
    'size'                 => [
        'numeric' => ':attribute 必须是 :size 位数。',
        'file'    => ':attribute 必须是 :size KB。',
        'string'  => ':attribute 必须是 :size 个字符。',
        'array'   => ':attribute 必须包括 :size 项。',
    ],
    'starts_with'          => ':attribute 必须以 :values 开始。',
    'string'               => ':attribute 必须是一个字符串。',
    'timezone'             => ':attribute 必须是个有效的时区。',
    'unique'               => ':attribute 已存在。',
    'uploaded'             => ':attribute 上传失败。',
    'url'                  => ':attribute 无效的格式。',
    'uuid'                 => ':attribute 必须是个有效的 UUID。',

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
        'descriptions.en.title'      => '英文标题',
        'descriptions.zh_cn.title'   => '中文标题',
        'descriptions.en.summary'    => '英文副标题',
        'descriptions.zh_cn.summary' => '中文副标题',

        'tax_rate'                 => [
            'name' => '税种名称',
            'type' => '类型',
            'rate' => '税率',
        ],
    ],

];
