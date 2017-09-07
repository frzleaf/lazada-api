<?php

/**
 * Created by PhpStorm.
 * User: balol
 * Date: 9/7/2017
 * Time: 2:43 PM
 */

namespace ApiLazada\Product;


use ApiLazada\Request;

/**
 *
 * Class GetProducts
 * @package ApiLazada\Product
 *
 * @property string CreatedAfter
 * @property string CreatedBefore
 * @property string UpdatedAfter
 * @property string UpdatedBefore
 * @property string Search
 * @property string Filter
 * @property string Limit
 * @property integer Options
 * @property integer Offset
 * @property string[] SkuSellerList
 */
class GetProducts extends Request
{
}