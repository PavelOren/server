<?php
/**
 * @package plugins.elasticSearch
 * @subpackage api.objects
 */
class KalturaESearchCaptionItem extends KalturaESearchEntryAbstractNestedItem
{

	/**
	 * @var KalturaESearchCaptionFieldName
	 */
	public $fieldName;

	private static $map_between_objects = array(
		'fieldName'
	);

	private static $map_dynamic_enum = array();

	private static $map_field_enum = array(
		KalturaESearchCaptionFieldName::CONTENT => ESearchCaptionFieldName::CONTENT,
		KalturaESearchCaptionFieldName::START_TIME => ESearchCaptionFieldName::START_TIME,
		KalturaESearchCaptionFieldName::END_TIME => ESearchCaptionFieldName::END_TIME,
		KalturaESearchCaptionFieldName::LANGUAGE => ESearchCaptionFieldName::LANGUAGE,
		KalturaESearchCaptionFieldName::LABEL => ESearchCaptionFieldName::LABEL,
		KalturaESearchCaptionFieldName::CAPTION_ASSET_ID => ESearchCaptionFieldName::CAPTION_ASSET_ID,
	);

	protected function getMapBetweenObjects()
	{
		return array_merge(parent::getMapBetweenObjects(), self::$map_between_objects);
	}
	
	public function toObject($object_to_fill = null, $props_to_skip = array())
	{
		if (!$object_to_fill)
			$object_to_fill = new ESearchCaptionItem();
		return parent::toObject($object_to_fill, $props_to_skip);
	}
	
	protected function doFromObject($srcObj, KalturaDetachedResponseProfile $responseProfile = null)
	{
		$this->fieldName = self::getApiFieldName($srcObj->getFieldName());
		return parent::doFromObject($srcObj, $responseProfile);
	}

	protected static function getApiFieldName ($srcFieldName)
	{
		foreach (self::$map_field_enum as $key => $value)
		{
			if ($value == $srcFieldName)
			{
				return $key;
			}
		}
		
		return null;
	}
	
	protected function getItemFieldName()
	{
		return $this->fieldName;
	}

	protected function getDynamicEnumMap()
	{
		return self::$map_dynamic_enum;
	}

	protected function getFieldEnumMap()
	{
		return self::$map_field_enum;
	}

}
