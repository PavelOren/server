<?php
/**
 * @package api
 * @subpackage enum
 */
class KalturaConditionType extends KalturaDynamicEnum implements MatchConditionType
{
	public static function getEnumClass()
	{
		return 'MatchConditionType';
	}

	public static function getDescriptions()
	{
		$descriptions = array(
			MatchConditionType::MATCH => 'Match at least one field value',
			MatchConditionType::MATCH_ALL => 'Match all field values.',
			);
		
		return self::mergeDescriptions(self::getEnumClass(), $descriptions);
	}
}