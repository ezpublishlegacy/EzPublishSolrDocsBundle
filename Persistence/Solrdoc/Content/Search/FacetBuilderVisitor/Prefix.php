<?php
/**
 * File containing the Content Search handler class
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 */

namespace xrow\EzPublishSolrDocsBundle\Persistence\Solrdoc\Content\Search\FacetBuilderVisitor;

use xrow\EzPublishSolrDocsBundle\Persistence\Solrdoc\Content\Search\FacetBuilderVisitor;
use eZ\Publish\API\Repository\Values\Content\Query\FacetBuilder;
#use xrow\EzPublishSolrDocsBundle\Persistence\Solrdoc\Content\Query\FacetBuilder;
use eZ\Publish\API\Repository\Values\Content\Search\Facet;

/**
 * Visits the Field facet builder
 */
class Prefix extends FacetBuilderVisitor
{
    /**
     * CHeck if visitor is applicable to current facet result
     *
     * @param string $field
     *
     * @return boolean
     */
    public function canMap( $field )
    {
        return $field === 'ezf_sp_words';
        #return true;
    }

    /**
     * Map Solr facet result back to facet objects
     *
     * @param string $field
     * @param array $data
     *
     * @return Facet
     */
    public function map( $field, array $data )
    {
        return new \xrow\EzPublishSolrDocsBundle\Persistence\Solrdoc\Content\Facet\PrefixFacet(
                array(
                        'name'    => $field,
                        'entries' => $this->mapData( $data ),
                )
        );
        /*
        return new Facet\ContentTypeFacet(
            array(
                'name'    => $field,
                'entries' => $this->mapData( $data ),
            )
        );
        */
    }

    /**
     * Check if visitor is applicable to current facet builder
     *
     * @param FacetBuilder $facetBuilder
     *
     * @return boolean
     */
    public function canVisit( FacetBuilder $facetBuilder )
    {
        return $facetBuilder instanceof \xrow\EzPublishSolrDocsBundle\Persistence\Solrdoc\Content\Query\FacetBuilder\PrefixFacetBuilder;
    }

    /**
     * Map field value to a proper Solr representation
     *
     * @param FacetBuilder $facetBuilder;
     *
     * @return string
     */
    public function visit( FacetBuilder $facetBuilder )
    {
        $fieldpath="ezf_sp_words";
        if( $facetBuilder->name != "" )
        {
            $facetname="{!ex=dt key=" . $facetBuilder->name . "}" . $fieldpath;
        }
        
        return http_build_query(
                    array(
                        'facet.field'    => $facetname,
                        'f.' . $fieldpath . '.facet.limit'    => $facetBuilder->limit,
                        'f.' . $fieldpath . '.facet.mincount' => $facetBuilder->minCount,
                        'f.' . $fieldpath . '.facet.prefix'   => $facetBuilder->searchpart,
                    )
                );
    }
}

