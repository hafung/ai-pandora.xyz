<?php

return [

    'accepted'             => ':attribute必须接受',
    'active_url'           => ':attribute必须是一个合法的 URL',
    'after'                => ':attribute 必须是 :date 之后的一个日期',
    'after_or_equal'       => ':attribute 必须是 :date 之后或相同的一个日期',
    'alpha'                => ':attribute只能包含字母',
    'alpha_dash'           => ':attribute只能包含字母、数字、中划线或下划线',
    'alpha_num'            => ':attribute只能包含字母和数字',
    'array'                => ':attribute必须是一个数组',
    'before'               => ':attribute 必须是 :date 之前的一个日期',
    'before_or_equal'      => ':attribute 必须是 :date 之前或相同的一个日期',
    'between'              => [
        'numeric' => ':attribute 必须在 :min 到 :max 之间',
        'file'    => ':attribute 必须在 :min 到 :max KB 之间',
        'string'  => ':attribute 必须在 :min 到 :max 个字符之间',
        'array'   => ':attribute 必须在 :min 到 :max 项之间',
    ],
    'boolean'              =>':attribute字符必须是 true 或false, 1 或 0 ',
    'confirmed'            => ':attribute 二次确认不匹配',
    'date'                 => ':attribute 必须是一个合法的日期',
    'date_format'          => ':attribute 与给定的格式 :format 不符合',
    'different'            => ':attribute 必须不同于 :other',
    'digits'               => ':attribute必须是 :digits 位.',
    'digits_between'       => ':attribute 必须在 :min 和 :max 位之间',
    'dimensions'           => ':attribute具有无效的图片尺寸',
    'distinct'             => ':attribute字段具有重复值',
    'email'                => ':attribute必须是一个合法的电子邮件地址',
    'exists'               => '选定的 :attribute 是无效的.',
    'file'                 => ':attribute必须是一个文件',
    'filled'               => ':attribute的字段是必填的',
    'image'                => ':attribute必须是 jpeg, png, bmp 或者 gif 格式的图片',
    'in'                   => '选定的 :attribute 是无效的',
    'in_array'             => ':attribute 字段不存在于 :other',
    'integer'              => ':attribute 必须是个整数',
    'ip'                   => ':attribute必须是一个合法的 IP 地址。',
    'json'                 => ':attribute必须是一个合法的 JSON 字符串',
    'max'                  => [
        'numeric' => ':attribute 的最大长度为 :max 位',
        'file'    => ':attribute 的最大为 :max',
        'string'  => ':attribute 的最大长度为 :max 字符',
        'array'   => ':attribute 的最大个数为 :max 个.',
    ],
    'mimes'                => ':attribute 的文件类型必须是 :values',
    'not_in'               => '选定的 :attribute 是无效的',
    'numeric'              => ':attribute 必须是数字',
    'present'              => ':attribute 字段必须存在',
    'regex'                => ':attribute 格式是无效的',
    'required_if'          => ':attribute 字段是必须的当 :other 是 :value',
    'required_unless'      => ':attribute 字段是必须的，除非 :other 是在 :values 中',
    'required_with'        => ':attribute 字段是必须的当 :values 是存在的',
    'required_with_all'    => ':attribute 字段是必须的当 :values 是存在的',
    'required_without'     => ':attribute 字段是必须的当 :values 是不存在的',
    'required_without_all' => ':attribute 字段是必须的当 没有一个 :values 是存在的',
    'same'                 => ':attribute和:other必须匹配',
    'size'                 => [
        'numeric' => ':attribute 必须是 :size 位',
        'file'    => ':attribute 必须是 :size KB',
        'string'  => ':attribute 必须是 :size 个字符',
        'array'   => ':attribute 必须包括 :size 项',
    ],
    'string'               => ':attribute 必须是一个字符串',
    'timezone'             => ':attribute 必须是个有效的时区.',
    'unique'               => ':attribute 已存在',
    'url'                  => ':attribute 无效的格式',

    'required' => ':attribute 不能为空',
    'min' => [
        'numeric' => ':attribute 的最小长度为 :min 位',
        'file' => ':attribute 大小至少为 :min KB',
        'string' => ':attribute 的最小长度为 :min 字符',
        'array' => ':attribute 至少有 :min 项',
    ],

// works either
//    'custom' => [
//        'searchQuery' => [
//            'required' => '搜索词不能为空。',
//            'min' => '搜索词至少需要 :min 个字符。',
//        ],
//    ],

    'template_required' => '请选择一个模板。',
    'options_required' => '模板对应的选项未选择。',
    'options_array' => '选项必须是数组格式。',
//    'options_min' => '请至少选择一个选项。',
//    'options_min' => '还有模板选项未选择/填写。',
    'options_min' => '还有模板_选项未选择/填写。',
    'field_required' => ':field 字段是必填的。',
    'field_invalid' => '所选的 :field 无效。',

    'attributes' => [
        'name' => '名称',
        'username' => '用户名',
        'email' => '邮箱',
        'first_name' => '名',
        'last_name' => '姓',
        'password' => '密码',
        'password_confirmation' => '确认密码',
        'city' => '城市',
        'country' => '国家',
        'address' => '地址',
        'phone' => '电话',
        'mobile' => '手机',
        'age' => '年龄',
        'sex' => '性别',
        'gender' => '性别',
        'day' => '天',
        'month' => '月',
        'year' => '年',
        'hour' => '时',
        'minute' => '分',
        'second' => '秒',
        'title' => '标题',
        'inputText' => '输入的文本',
        'description' => '描述',
        'excerpt' => '摘要',
        'date' => '日期',
        'time' => '时间',
        'available' => '可用的',
        'size' => '大小',
        ##以下是自定义的！
        'question' => '问题',
        'customPrompt' => '自定义提示词',
        'searchQuery' => '查询的内容',
        'telephone' => '手机号',
        'type' => '类型',
        'area_phone_number' => '手机区号',
        'student_info_id' => '学生ID',
        'lesson_appointment_id' => '预约课程ID',
        'version' => '版本',
        'interface_type' => '设备类型',
        'parent_id' => '家长ID',
        'old_password' => '旧密码',
        'new_password' => '新密码',
        'confirm_password' => '确认密码',
        'auth_code' => '验证码',
        'country_id' => '国家ID',
        'wechat' => '微信',
        'chinese_name' => '中文名',
        'english_name' => '英文名',
        'province_id' => '省份ID',
        'city_id' => '城市ID',
        'customer_id' => '用户ID',
        'birthday' => '生日',
        'course_id' => '课程ID',
        'page' => '页',
        'perPage' => '每页',
        'feedback_type' => '反馈类型',
        'content' => '内容',
        'room_id' => '房间ID',
        'token' => 'Token凭证',
    ],


];