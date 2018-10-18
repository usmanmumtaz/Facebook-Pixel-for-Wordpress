<?php
/**
 * @package FacebookPixel
 */

namespace FacebookPixelPlugin\Core;

defined('ABSPATH') or die('Direct access not allowed');

use ReflectionClass;

class FacebookPixel {
  const ADDPAYMENTINFO = 'AddPaymentInfo';
  const ADDTOCART = 'AddToCart';
  const ADDTOWISHLIST = 'AddToWishlist';
  const COMPLETEREGISTRATION = 'CompleteRegistration';
  const CONTACT = 'Contact';
  const CUSTOMIZEPRODUCT = 'CustomizeProduct';
  const DONATE = 'Donate';
  const FINDLOCATION = 'FindLocation';
  const INITIATECHECKOUT = 'InitiateCheckout';
  const LEAD = 'Lead';
  const PAGEVIEW = 'PageView';
  const PURCHASE = 'Purchase';
  const SCHEDULE = 'Schedule';
  const SEARCH = 'Search';
  const STARTTRIAL = 'StartTrial';
  const SUBMITAPPLICATION = 'SubmitApplication';
  const SUBSCRIBE = 'Subscribe';
  const VIEWCONTENT = 'ViewContent';

  private static $pixelId = '';

  private static $pixelBaseCode = "
<!-- Facebook Pixel Code -->
<script type='text/javascript'>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');
</script>
<!-- End Facebook Pixel Code -->
";

  private static $pixelFbqCodeWithoutScript = "
  fbq('%s', '%s'%s%s);
";

  private static $pixelNoscriptCode = "
<!-- Facebook Pixel Code -->
<noscript>
<img height=\"1\" width=\"1\" style=\"display:none\" alt=\"fbpx\"
src=\"https://www.facebook.com/tr?id=%s&ev=%s%s&noscript=1\" />
</noscript>
<!-- End Facebook Pixel Code -->
";

  public static function initialize($pixel_id = '') {
    self::$pixelId = $pixel_id;
  }

  /**
   * Gets FB pixel ID
   */
  public static function getPixelId() {
    return self::$pixelId;
  }

  /**
   * Sets FB pixel ID
   */
  public static function setPixelId($pixel_id) {
    self::$pixelId = $pixel_id;
  }

  /**
   * Gets FB pixel base code
   */
  public static function getPixelBaseCode() {
    return self::$pixelBaseCode;
  }

  /**
   * Gets FB pixel init code
   */
  public static function getPixelInitCode($agent_string, $param = array(), $with_script_tag = true) {
    if (empty(self::$pixelId)) {
      return;
    }

    $code = $with_script_tag
      ? "<script type='text/javascript'>".self::$pixelFbqCodeWithoutScript."</script>"
      : self::$pixelFbqCodeWithoutScript;
    $param_str = $param;
    if (is_array($param)) {
      $param_str = json_encode($param, JSON_PRETTY_PRINT);
    }
    $agent_param = array('agent' => $agent_string);
    return sprintf(
      $code,
      'init',
      self::$pixelId,
      ', '.$param_str,
      ', '.json_encode($agent_param, JSON_PRETTY_PRINT));
  }

  /**
   * Gets FB pixel track code
   */
  public static function getPixelTrackCode($event, $param = array(), $with_script_tag = true) {
    if (empty(self::$pixelId)) {
      return;
    }

    $code = $with_script_tag
      ? "<script type='text/javascript'>".self::$pixelFbqCodeWithoutScript."</script>"
      : self::$pixelFbqCodeWithoutScript;
    $param_str = $param;
    if (is_array($param)) {
      $param_str = json_encode($param, JSON_PRETTY_PRINT);
    }
    $class = new ReflectionClass(__CLASS__);
    return sprintf(
      $code,
      $class->getConstant(strtoupper($event)) !== false ? 'track' : 'trackCustom',
      $event,
      ', '.$param_str,
      '');
  }

  /**
   * Gets FB pixel noscript code
   */
  public static function getPixelNoscriptCode($event = 'PageView', $cd = array()) {
    if (empty(self::$pixelId)) {
      return;
    }

    $data = '';
    foreach ($cd as $k => $v) {
      $data .= '&cd['.$k.']='.$v;
    }
    return sprintf(
      self::$pixelNoscriptCode,
      self::$pixelId,
      $event,
      $data);
  }

  /**
   * Gets FB pixel AddToCart code
   */
  public static function getPixelAddToCartCode($param = array(), $with_script_tag = true) {
    return self::getPixelTrackCode(
      self::ADDTOCART,
      $param,
      $with_script_tag);
  }

  /**
   * Gets FB pixel InitiateCheckout code
   */
  public static function getPixelInitiateCheckoutCode($param = array(), $with_script_tag = true) {
    return self::getPixelTrackCode(
      self::INITIATECHECKOUT,
      $param,
      $with_script_tag);
  }

  /**
   * Gets FB pixel Lead code
   */
  public static function getPixelLeadCode($param = array(), $with_script_tag = true) {
    return self::getPixelTrackCode(
      self::LEAD,
      $param,
      $with_script_tag);
  }

  /**
   * Gets FB pixel PageView code
   */
  public static function getPixelPageViewCode($param = array(), $with_script_tag = true) {
    return self::getPixelTrackCode(
      self::PAGEVIEW,
      $param,
      $with_script_tag);
  }

  /**
   * Gets FB pixel Purchase code
   */
  public static function getPixelPurchaseCode($param = array(), $with_script_tag = true) {
    return self::getPixelTrackCode(
      self::PURCHASE,
      $param,
      $with_script_tag);
  }

  /**
   * Gets FB pixel ViewContent code
   */
  public static function getPixelViewContentCode($param = array(), $with_script_tag = true) {
    return self::getPixelTrackCode(
      self::VIEWCONTENT,
      $param,
      $with_script_tag);
  }
}
