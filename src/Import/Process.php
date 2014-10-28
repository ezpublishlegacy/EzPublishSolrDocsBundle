<?php 
namespace xrow\EzPublishSolrDocsBundle\src\Import;

use \eZ\Publish\API\Repository\Values\Content\Location;


class Process implements Importing
{
    protected $location;
    protected $ContentType;
    protected $source;
    protected $repository;
    
    function __construct( $location, $ContentType, ImportSource $source, $repository )
    {
        $this->source = $source;
        $this->location = $location;
        $this->ContentType = $ContentType;
        $this->i_repository = $repository;

    }
    function validate( )
    {
        return $this->source->validateImport( );
    }
    
    function import( ) {
        #$this->stopReplication();
        $importstartzeit=microtime(true);
        $from=0;
        $till=$this->source->limit();
        
        $this->source->rewind();
        if( $this->source->offset() <= $this->source->count())
        {
            $from=$this->source->offset();
        }
        if( ( $from + $this->source->limit() ) > $this->source->count())
        {
            $till=$this->source->count();
        }
        else
        {
            $till=$from + $this->source->limit();
        }
        for ($i = $from; $i < $till; $i++)
        {
            $this->source->toKey($i);
            $this->importEntry($this->source->current());
        }

        $durationInMilliseconds = (microtime(true) - $importstartzeit) * 1000;
        $timing = number_format($durationInMilliseconds, 3, '.', '') . "ms";
        if($durationInMilliseconds > 1000)
        {
            $timing = number_format($durationInMilliseconds / 1000, 1, '.', '') . "sec";
        }
        echo "\nDauer Import: " . $timing . "\n";
        #$this->startReplication();
    }
    
    private function importEntry( $entry )
    {
        $repository = $this->i_repository;
        $contentService = $repository->getContentService();
        $contentCreateStruct = $contentService->newContentCreateStruct( $this->ContentType, 'ger-DE' );
        $contentCreateStruct_mapped = self::mapClass($entry, $contentCreateStruct);
        $draft = $contentService->createContent( $contentCreateStruct_mapped, array( $this->location ) );
        echo ".";
    }
    
    function mapClass( $entry, $contentCreateStruct )
    {
        #$classDef = array();
        foreach( $this->ContentType->fieldDefinitions as $field )
        {
            if( array_key_exists($field->identifier, $entry) )
            {
                $contentCreateStruct->setField( $field->identifier, $entry[$field->identifier] );
            }
            #$classDef[] = array( "id" => $field->identifier, "required" => $field->isRequired, "ezident" => $field->fieldTypeIdentifier  );
        }
        return $contentCreateStruct;
    }
    
}