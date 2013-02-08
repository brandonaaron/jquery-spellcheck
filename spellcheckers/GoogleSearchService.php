<?php
/**
 *
 *
 * Author:      Chris 'CJ' Jones
 * Project:     GoogleSearchService
 * Date:        Wed Feb 29 11:15:30 2012
 * Version:     1
 */

class GoogleSearchResult {
  public $documentFiltering; // boolean
  public $searchComments; // string
  public $estimatedTotalResultsCount; // int
  public $estimateIsExact; // boolean
  public $resultElements; // ResultElementArray
  public $searchQuery; // string
  public $startIndex; // int
  public $endIndex; // int
  public $searchTips; // string
  public $directoryCategories; // DirectoryCategoryArray
  public $searchTime; // double
}

class ResultElement {
  public $summary; // string
  public $URL; // string
  public $snippet; // string
  public $title; // string
  public $cachedSize; // string
  public $relatedInformationPresent; // boolean
  public $hostName; // string
  public $directoryCategory; // DirectoryCategory
  public $directoryTitle; // string
}

class DirectoryCategory {
  public $fullViewableName; // string
  public $specialEncoding; // string
}

class GoogleSearchService extends SoapClient {

  private static $classmap = array(
                                    'GoogleSearchResult' => 'GoogleSearchResult',
                                    'ResultElement' => 'ResultElement',
                                    'DirectoryCategory' => 'DirectoryCategory',
                                   );

  public function __construct($wsdl = "http://www.soapclient.com/xml/googleSearch.wsdl", $options = array()) {
    foreach(self::$classmap as $key => $value) {
      if(!isset($options['classmap'][$key])) {
        $options['classmap'][$key] = $value;
      }
    }
    parent::__construct($wsdl, $options);
  }

  /**
   *  
   *
   * @param string $key
   * @param string $url
   * @return base64Binary
   */
  public function doGetCachedPage($key, $url) {
    return $this->__soapCall('doGetCachedPage', array($key, $url),       array(
            'uri' => 'urn:GoogleSearch',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param string $key
   * @param string $phrase
   * @return string
   */
  public function doSpellingSuggestion($key, $phrase) {
    return $this->__soapCall('doSpellingSuggestion', array($key, $phrase),       array(
            'uri' => 'urn:GoogleSearch',
            'soapaction' => ''
           )
      );
  }

  /**
   *  
   *
   * @param string $key
   * @param string $q
   * @param int $start
   * @param int $maxResults
   * @param boolean $filter
   * @param string $restrict
   * @param boolean $safeSearch
   * @param string $lr
   * @param string $ie
   * @param string $oe
   * @return GoogleSearchResult
   */
  public function doGoogleSearch($key, $q, $start, $maxResults, $filter, $restrict, $safeSearch, $lr, $ie, $oe) {
    return $this->__soapCall('doGoogleSearch', array($key, $q, $start, $maxResults, $filter, $restrict, $safeSearch, $lr, $ie, $oe),       array(
            'uri' => 'urn:GoogleSearch',
            'soapaction' => ''
           )
      );
  }

}

?>
