<?php namespace colori {


  class CSSFormats {
    private const NUMBER_EXP = '(?:\\-|\\+)?(?:[0-9]+(?:\\.[0-9]+)?|\\.[0-9]+)(?:(?:e|E)(?:\\-|\\+)?[0-9]+)?';

    public static function RegExp(string $id): string {
      return match($id) {
        'number' => self::NUMBER_EXP,
        'percentage' => self::NUMBER_EXP . '%',
        'numberOrPercentage' => self::NUMBER_EXP . '%?',
        'angle' => self::NUMBER_EXP . '(?:deg|grad|rad|turn)?'
      };
    }

    public static function formats(): array {
      return array(
        array(
          'id' => 'hex',
          'syntaxes' => array(
            // #abc or #ABC
            '/^#([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})$/',
            // #abcd or #ABCD
            '/^#([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})([a-fA-F0-9]{1})$/',
            // #aabbcc or #AABBCC
            '/^#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})$/',
            // #aabbccdd or #AABBCCDD
            '/^#([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})([a-fA-F0-9]{2})$/'
          )
        ), array(
          'id' => 'rgb',
          'syntaxes' => array(
            // rgb(255, 255, 255) (spaces not required)
            '/^rgba?\\(('.self::RegExp('number').'), ?('.self::RegExp('number').'), ?('.self::RegExp('number').')\\)$/',
            // rgba(255, 255, 255, .5) or rgba(255, 255, 255, 50%) (spaces not required)
            '/^rgba?\\(('.self::RegExp('number').'), ?('.self::RegExp('number').'), ?('.self::RegExp('number').'), ?('.self::RegExp('numberOrPercentage').')\\)$/',
            // rgb(100%, 100%, 100%) (spaces not required)
            '/^rgba?\\(('.self::RegExp('percentage').'), ?('.self::RegExp('percentage').'), ?('.self::RegExp('percentage').')\\)$/',
            // rgba(100%, 100%, 100%, .5) or rgba(100%, 100%, 100%, 50%) (spaces not required)
            '/^rgba?\\(('.self::RegExp('percentage').'), ?('.self::RegExp('percentage').'), ?('.self::RegExp('percentage').'), ?('.self::RegExp('numberOrPercentage').')\\)$/',
            // rgb(255 255 255)
            '/^rgba?\\(('.self::RegExp('number').') ('.self::RegExp('number').') ('.self::RegExp('number').')\\)$/',
            // rgba(255 255 255 / 50%) or rgba(255 255 255 / .5)
            '/^rgba?\\(('.self::RegExp('number').') ('.self::RegExp('number').') ('.self::RegExp('number').') ?\\/ ?('.self::RegExp('numberOrPercentage').')\\)$/',
            // rgb(100% 100% 100%)
            '/^rgba?\\(('.self::RegExp('percentage').') ('.self::RegExp('percentage').') ('.self::RegExp('percentage').')\\)$/',
            // rgba(100% 100% 100% / 50%) or rgba(100% 100% 100% / .5)
            '/^rgba?\\(('.self::RegExp('percentage').') ('.self::RegExp('percentage').') ('.self::RegExp('percentage').') ?\\/ ?('.self::RegExp('numberOrPercentage').')\\)$/'
          )
        ), array(
          'id' => 'hsl',
          'syntaxes' => array(
            // hsl(<angle>, 100%, 100%)
            '/^hsla?\\(('.self::RegExp('angle').'), ?('.self::RegExp('percentage').'), ?('.self::RegExp('percentage').')\\)$/',
            // hsla(<angle>, 100%, 100%, .5) or hsla(<angle>, 100%, 100%, 50%)
            '/^hsla?\\(('.self::RegExp('angle').'), ?('.self::RegExp('percentage').'), ?('.self::RegExp('percentage').'), ?('.self::RegExp('numberOrPercentage').')\\)$/',
            // hsl(<angle> 100% 100%)
            '/^hsla?\\(('.self::RegExp('angle').') ('.self::RegExp('percentage').') ('.self::RegExp('percentage').')\\)$/',
            // hsla(<angle> 100% 100% / .5) or hsl(<angle> 100% 100% / 50%)
            '/^hsla?\\(('.self::RegExp('angle').') ('.self::RegExp('percentage').') ('.self::RegExp('percentage').') ?\\/ ?('.self::RegExp('numberOrPercentage').')\\)$/'
          )
        ), array(
          'id' => 'hwb',
          'syntaxes' => array(
            // hwb(<angle>, 100%, 100%)
            '/^hwba?\\(('.self::RegExp('angle').'), ?('.self::RegExp('percentage').'), ?('.self::RegExp('percentage').')\\)$/',
            // hwba(<angle>, 100%, 100%, .5) or hsla(<angle>, 100%, 100%, 50%)
            '/^hwba?\\(('.self::RegExp('angle').'), ?('.self::RegExp('percentage').'), ?('.self::RegExp('percentage').'), ?('.self::RegExp('numberOrPercentage').')\\)$/',
            // hwb(<angle> 100% 100%)
            '/^hwba?\\(('.self::RegExp('angle').') ('.self::RegExp('percentage').') ('.self::RegExp('percentage').')\\)$/',
            // hwba(<angle> 100% 100% / .5) or hsl(<angle> 100% 100% / 50%)
            '/^hwba?\\(('.self::RegExp('angle').') ('.self::RegExp('percentage').') ('.self::RegExp('percentage').') ?\\/ ?('.self::RegExp('numberOrPercentage').')\\)$/'
          )
        ), array(
          'id' => 'lab',
          'syntaxes' => array(
            // lab(300% 25 40)
            '/^lab\\(('.self::RegExp('percentage').') ('.self::RegExp('number').') ('.self::RegExp('number').')\\)$/',
            // lab(300% 25 40 / .5)
            '/^lab\\(('.self::RegExp('percentage').') ('.self::RegExp('number').') ('.self::RegExp('number').') ?\\/ ?('.self::RegExp('numberOrPercentage').')\\)$/'
          )
        ), array(
          'id' => 'lch',
          'syntaxes' => array(
            // lch(300% 25 <angle>)
            '/^lch\\(('.self::RegExp('percentage').') ('.self::RegExp('number').') ('.self::RegExp('angle').')\\)$/',
            // lch(300% 25 <angle> / .5)
            '/^lch\\(('.self::RegExp('percentage').') ('.self::RegExp('number').') ('.self::RegExp('angle').') ?\\/ ?('.self::RegExp('numberOrPercentage').')\\)$/'
          )
        ), array(
          'id' => 'oklab',
          'syntaxes' => array(
            // oklab(50% -25 40)
            '/^oklab\\(('.self::RegExp('percentage').') ('.self::RegExp('number').') ('.self::RegExp('number').')\\)$/',
            // oklab(50% -25 40 / .5)
            '/^oklab\\(('.self::RegExp('percentage').') ('.self::RegExp('number').') ('.self::RegExp('number').') ?\\/ ?('.self::RegExp('numberOrPercentage').')\\)$/'
          )
        ), array(
          'id' => 'oklch',
          'syntaxes' => array(
            // oklch(50% 25 <angle>)
            '/^oklch\\(('.self::RegExp('percentage').') ('.self::RegExp('number').') ('.self::RegExp('angle').')\\)$/',
            // oklch(50% 25 <angle> / .5)
            '/^oklch\\(('.self::RegExp('percentage').') ('.self::RegExp('number').') ('.self::RegExp('angle').') ?\\/ ?('.self::RegExp('numberOrPercentage').')\\)$/'
          )
        ), array(
          'id' => 'color',
          'syntaxes' => array(
            // color(display-p3 -0.6112 1.0079 -0.2192)
            '/^color\\(([a-zA-Z0-9_-]+?) ('.self::RegExp('number').') ('.self::RegExp('number').') ('.self::RegExp('number').')\\)$/',
            // color(display-p3 -0.6112 1.0079 -0.2192 / .5)
            '/^color\\(([a-zA-Z0-9_-]+?) ('.self::RegExp('number').') ('.self::RegExp('number').') ('.self::RegExp('number').') ?\\/ ?('.self::RegExp('numberOrPercentage').')\\)$/'
          )
        ), array(
          'id' => 'name',
          'syntaxes' => array(
            // white or WHITE or WhiTe
            '/^[A-Za-z]+$/'
          )
        )
      );
    }
  }


}