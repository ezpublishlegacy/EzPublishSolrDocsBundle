<?php
/**
 * File containing the MapLocation criterion visitor class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 */

namespace xrow\EzPublishSolrDocsBundle\Persistence\Solrdoc\Content\Search\CriterionVisitor;

use xrow\EzPublishSolrDocsBundle\Persistence\Solrdoc\Content\Search\CriterionVisitor;
use xrow\EzPublishSolrDocsBundle\Persistence\Solrdoc\Content\Search\FieldMap;
use eZ\Publish\API\Repository\Values\Content\Query\CustomFieldInterface;

/**
 * Visits the MapLocation criterion
 */
abstract class MapLocation extends CriterionVisitor
{
    /**
     * Field map
     *
     * @var \xrow\EzPublishSolrDocsBundle\Persistence\Solrdoc\Content\Search\FieldMap
     */
    protected $fieldMap;

    /**
     * Name of the field type that criterion can handle
     *
     * @var string
     */
    protected $typeName = "ez_geolocation";

    /**
     * Create from content type handler and field registry
     *
     * @param \xrow\EzPublishSolrDocsBundle\Persistence\Solrdoc\Content\Search\FieldMap $fieldMap
     */
    public function __construct( FieldMap $fieldMap )
    {
        $this->fieldMap = $fieldMap;
    }

    /**
     * Get field type information
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Query\CustomFieldInterface $criterion
     * @return array
     */
    protected function getFieldTypes( CustomFieldInterface $criterion )
    {
        return $this->fieldMap->getFieldTypes( $criterion );
    }
}
