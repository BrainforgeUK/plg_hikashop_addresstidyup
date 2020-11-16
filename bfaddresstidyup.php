<?php
/**
 * @package   Address tidyup for Hikashop by BrainforgeUK
 * @version   0.0.1
 * @author    https://www.brainforge.co.uk
 * @copyright Copyright (C) 2020 Jonathan Brain. All rights reserved.
 * @license   GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Plugin\CMSPlugin;

class plgHikashopBfaddresstidyup extends CMSPlugin
{
	public function onBeforeAddressCreate(&$address, &$do)
	{
		$this->tidyAddress($address);
	}

	public function onBeforeAddressUpdate(&$address, &$do)
	{
		if ($this->params->get('onupdatetidyup'))
		{
			$this->tidyAddress($address);
		}
	}

	protected function tidyAddress(&$address)
    {
		$address->address_post_code = trim(strtoupper($address->address_post_code));

        foreach(array('address_firstname', 'address_middle_name', 'address_lastname',
					'address_street', 'address_street2', 'address_city') as $field)
        {
            if (empty($address->$field))
            {
                continue;
			}
			$address->$field = strtolower(trim($address->$field));

			foreach(array(' '=>' ', ','=>', ', '\.'=>'. ', '-'=>'-', ':'=>':', '\+'=>'+') as $from=>$to)
			{
				$parts = preg_split('/\s*' . $from . '+\s*/', $address->$field);
				foreach($parts as &$part)
                {
                    $part = ucfirst($part);
                }
				$address->$field = implode($to, $parts);
			}
        }
    }
}