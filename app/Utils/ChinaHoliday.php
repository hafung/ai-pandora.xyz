<?php
declare (strict_types=1);

namespace App\Utils;

use DateTime;
use Exception;

/**
 * 这个类专门用于判断国内的节假日/周末/工作日
 */
class ChinaHoliday {

    const HOLIDAY = 1;
    const WORKDAY = 2;
    const WEEKEND = 3;

    private static $holiday = [
        '2023' => ['0102', '0123', '0124', '0125', '0126', '0127', '0405', '0501', '0502', '0503', '0622', '0623', '0929', '1002', '1003', '1004', '1005', '1006'],
        '2024' => [
            '0101', '0210', '0211', '0212', '0213', '0214', '0215', '0216', '0217',
            '0404', '0405', '0406', '0501', '0502', '0503', '0504', '0505',
            '0608', '0609', '0610', '0915', '0916', '0917', '1001', '1002', '1003', '1004', '1005', '1006', '1007'
        ],
    ];

    // 休息日中的工作日(调休日), 调休本身就一定是在周末
    private static $workday = [
        '2023' => ['0128', '0129', '0423', '0506', '0625', '1007', '1008'],
        '2024' => ['0204', '0218', '0407', '0428', '0511', '0914', '0929', '1012'],
    ];

    /**
     * 是否为国内的工作日
     * @param string|int $datetime 任意格式时间字符串或时间戳(默认为当前时间)
     * @return bool 是返回True,否则返回False
     */
    public static function isWorkday($datetime = null): bool {
        return self::getDateType($datetime) === self::WORKDAY;
    }

    /**
     * 是否为国内的节假日
     * @param string|int $datetime 任意格式时间字符串或时间戳(默认为当前时间)
     * @return bool 是返回True,否则返回False
     */
    public static function isHoliday($datetime = null): bool {
        return self::getDateType($datetime) === self::HOLIDAY;
    }

    /**
     * @throws Exception
     */
    public static function isFridayOrSaturday($datetime): bool {

        $dateTime = new DateTime($datetime);

        // 获取星期几，格式化为数字（1 表示星期一，7 表示星期日）
        $dayOfWeek = $dateTime->format('N');

        // 判断是否是星期五（5）或星期六（6）
        return $dayOfWeek == 5 || $dayOfWeek == 6;
    }

    public static function getDateType($datetime = null): int {

        $y = TimeHelper::format('Y', $datetime);
        $md = TimeHelper::format('md', $datetime);

        if (array_key_exists($y, self::$holiday) && in_array($md, self::$holiday[$y])) {
            return self::HOLIDAY;
        }

        if ((array_key_exists($y, self::$workday) && in_array($md, self::$workday[$y])) || TimeHelper::isWeekday($datetime)) {
            return self::WORKDAY;
        }

        return self::WEEKEND;
    }

    /**
     * @throws Exception
     */
    public static function getDateTypeForHotel($datetime): int {

        $date = new DateTime($datetime);

        $date->modify('+1 day');

        $newDatetime = $date->format('Y-m-d H:i:s');

        return self::getDateType($newDatetime);

    }
}
