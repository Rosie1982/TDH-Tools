<?php

/**
 * Description of RGB
 *
 * Mutli value item
 * - Store RGB Colour Values
 *
 * @author jc166922
 */
class RGB extends Object {

    public static function create()
    {

        if (func_num_args() == 1)
        {
            if (self::isRGB(func_get_arg(0))) return self::createFromRGB(func_get_arg(0));
            if (is_string(func_get_arg(0))) return self::createFromString(func_get_arg(0));
            if (func_get_arg(0) instanceof RGB) return self::createFromRGB(func_get_arg(0));
            if (is_array(func_get_arg(0)))  return self::createFromArray(func_get_arg(0));
        }


        if (func_num_args() == 3)
        {
            if (is_int(func_get_arg(0)) && is_int(func_get_arg(1)) && is_int(func_get_arg(2)))
                return self::createFromRGBValues(func_get_arg(0),func_get_arg(1),func_get_arg(2)); // Three parameters and they are all ints
        }


        return RGB::ColorBlack();

    }


    public static function createFromString($src)
    {
        if (util::contains($src, " "))
        {
            // space delimited values
            $split = explode(" ",$src);
            return self::createFromRGBValues(trim($split[0]),trim($split[1]),trim($split[2]));

        }

        if (util::contains($src, ","))
        {
            // comma delimited values
            $split = explode(",",$src);
            return self::createFromRGBValues(trim($split[0]),trim($split[1]),trim($split[2]));
        }


        $rgb_array = color::name($src);
        return self::createFromRGBValues($rgb_array[color::$RED],$rgb_array[color::$GREEN],$rgb_array[color::$BLUE]);
    }


    public static function createFromRGB(RGB $src)
    {
        $result = new RGB();
        $src instanceof RGB;
        $src->copy($result);  // return a  new RGB with same Property Values
        return $result;
    }

    public static function createFromRGBValues($r,$g,$b)
    {
        $result = new RGB();

          $result->Red( is_int($r) ? $r : 0 );
        $result->Green( is_int($g) ? $g : 0 );
         $result->Blue( is_int($b) ? $b : 0 );

        return $result;
    }


    public static function createFromArray($arr)
    {
        if (count($arr) != 3) return RGB::ColorBlack();

        $result = new RGB();

        $red   = null;
        $green = null;
        $blue  = null;

        if (array_key_exists("R",   $arr) && is_null($red) ) $red = $arr['R'];
        if (array_key_exists("r",   $arr) && is_null($red) ) $red = $arr['r'];
        if (array_key_exists("red", $arr) && is_null($red) ) $red = $arr['red'];
        if (array_key_exists("RED", $arr) && is_null($red) ) $red = $arr['RED'];
        if (array_key_exists("Red", $arr) && is_null($red) ) $red = $arr['Red'];
        if (is_null($red)) $red = $arr[0];  // maybe 3 values RGB

        if (array_key_exists("G",     $arr) && is_null($green) ) $green = $arr['G'];
        if (array_key_exists("g",     $arr) && is_null($green) ) $green = $arr['g'];
        if (array_key_exists("green", $arr) && is_null($green) ) $green = $arr['green'];
        if (array_key_exists("GREEN", $arr) && is_null($green) ) $green = $arr['GREEN'];
        if (array_key_exists("Green", $arr) && is_null($green) ) $green = $arr['Green'];
        if (is_null($green)) $green = $arr[1]; // maybe 3 values RGB

        if (array_key_exists("B",    $arr) && is_null($blue) ) $blue = $arr['B'];
        if (array_key_exists("b",    $arr) && is_null($blue) ) $blue = $arr['b'];
        if (array_key_exists("blue", $arr) && is_null($blue) ) $blue = $arr['blue'];
        if (array_key_exists("BLUE", $arr) && is_null($blue) ) $blue = $arr['BLUE'];
        if (array_key_exists("Blue", $arr) && is_null($blue) ) $blue = $arr['Green'];
        if (is_null($blue)) $blue = $arr[2]; // maybe 3 values RGB

        $result->Red($red);
        $result->Green($green);
        $result->Blue($blue);

        return $result;
    }


    public function __construct($red = -1,$green = -1,$blue = -1) {
        parent::__construct();
        $this->Red($red);
        $this->Green($green);
        $this->Blue($blue);
    }

    public function __destruct() {
        parent::__destruct();
    }

    public function Red($value = null)
    {
        if (func_num_args() == 0) return $this->getProperty();
        $this->setProperty($value);
    }

    public function Green($value = null)
    {
        if (func_num_args() == 0) return $this->getProperty();
        $this->setProperty($value);
    }

    public function Blue($value = null)
    {
        if (func_num_args() == 0) return $this->getProperty();
        $this->setProperty($value);
    }

    public function Alpha($value = null)
    {
        if (func_num_args() == 0) return $this->getProperty();
        $this->setProperty($value);
    }

    /*
     * setRGB Red Green Blue
     */
    public function setRGB($red,$green,$blue) {
        $this->Red($red);
        $this->Green($green);
        $this->Blue($blue);
    }

    public function __toString() {
        return $this->asFormattedString("{Red} {Green} {Blue}");
    }

    public function asHex()
    {
        return sprintf("%02X%02X%02X", $this->Red(), $this->Green(), $this->Blue());
    }



    public static function transparent()
    {
        return new RGB(-1,-1,-1);
    }

    public static function ColorBlack()
    {
        return new RGB(0,0,0);
    }

    public static function ColorWhite()
    {
        return new RGB(255,255,255);
    }

    public static function ColorRed()
    {

        return new RGB(255,0,0);

    }

    public static function ColorGreen()
    {
        return new RGB(0,255,0);
    }


    public static function ColorBlue()
    {
        return new RGB(0,0,255);
    }



    /*
     * return: Array of RGB where keys are values inside the min to max range
     */
    public static function Ramp($min,$max,$buckets = 10, $indexed_color_gradient = null)
    {

        if (is_null($indexed_color_gradient)) $indexed_color_gradient = SELF::GradientBlueGreenRed();


        $imin = min($min , $max);  // make sure min and max are right way round
        $imax = max($min , $max);
        $istep = ($imax - $imin) / $buckets;

        $result = array();
        for ($index = $imin; $index <= $imax; $index += $istep)
        {
            $color_index = ($index / $imax) * 255;  // convert $index to a percent of 255
            $result["$index"] =  $indexed_color_gradient[$color_index];
        }

        return $result;

    }



    public static function hsv2RGB($H, $S, $V)
    {
        $rgb = self::HSV_TO_RGB($H, $S, $V);

        // sprint_r($rgb);

        return new RGB($rgb['R'], $rgb['G'], $rgb['B']);

    }


    /*
     * Return :: Array H S L   values for this RGB
     */
    public function asHSL()
    {
        $r = $this->Red();
        $g = $this->Green();
        $b = $this->Blue();

       $var_R = ($r / 255);
       $var_G = ($g / 255);
       $var_B = ($b / 255);

       $var_Min = min($var_R, $var_G, $var_B);
       $var_Max = max($var_R, $var_G, $var_B);
       $del_Max = $var_Max - $var_Min;

       $v = $var_Max;

       if ($del_Max == 0) {
          $h = 0;
          $s = 0;
       } else {
          $s = $del_Max / $var_Max;

          $del_R = ( ( ( $var_Max - $var_R ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
          $del_G = ( ( ( $var_Max - $var_G ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
          $del_B = ( ( ( $var_Max - $var_B ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;

          if      ($var_R == $var_Max) $h = $del_B - $del_G;
          else if ($var_G == $var_Max) $h = ( 1 / 3 ) + $del_R - $del_B;
          else if ($var_B == $var_Max) $h = ( 2 / 3 ) + $del_G - $del_R;

          if ($h < 0) $h++;
          if ($h > 1) $h--;
       }

       return array($h, $s, $v);
    }



    public static function hsl2rgb($h, $s, $v) {
        if($s == 0) {
            $r = $g = $B = $v * 255;
        } else {
            $var_H = $h * 6;
            $var_i = floor( $var_H );
            $var_1 = $v * ( 1 - $s );
            $var_2 = $v * ( 1 - $s * ( $var_H - $var_i ) );
            $var_3 = $v * ( 1 - $s * (1 - ( $var_H - $var_i ) ) );

            if       ($var_i == 0) { $var_R = $v     ; $var_G = $var_3  ; $var_B = $var_1 ; }
            else if  ($var_i == 1) { $var_R = $var_2 ; $var_G = $v      ; $var_B = $var_1 ; }
            else if  ($var_i == 2) { $var_R = $var_1 ; $var_G = $v      ; $var_B = $var_3 ; }
            else if  ($var_i == 3) { $var_R = $var_1 ; $var_G = $var_2  ; $var_B = $v     ; }
            else if  ($var_i == 4) { $var_R = $var_3 ; $var_G = $var_1  ; $var_B = $v     ; }
            else                   { $var_R = $v     ; $var_G = $var_1  ; $var_B = $var_2 ; }

            $r = $var_R * 255;
            $g = $var_G * 255;
            $B = $var_B * 255;
        }
        return array(intval($r), intval($g), intval($B));

    }


   /*
    * Colour Ramps provided by
    * http://paulbourke.net/texture_colour/colourramp/
    *
    * http://paulbourke.net/texture_colour/colourramp/ramp1.gif
    *
    *
    * 255 colours index 0 to 255 - foir a gradient  BlueGreenRed
    *
    */
    public static function GradientBlueGreenRed()
    {

$text = <<<TEXT
   0   0   0 255     64   0 255 254    128   1 255   0    192 255 252   0
   1   0   3 255     65   0 255 249    129   5 255   0    193 255 247   0
   2   0   7 255     66   0 255 246    130   9 255   0    194 255 244   0
   3   0  11 255     67   0 255 241    131  13 255   0    195 255 239   0
   4   0  15 255     68   0 255 238    132  17 255   0    196 255 236   0
   5   0  19 255     69   0 255 233    133  21 255   0    197 255 231   0
   6   0  23 255     70   0 255 230    134  25 255   0    198 255 228   0
   7   0  27 255     71   0 255 225    135  29 255   0    199 255 223   0
   8   0  31 255     72   0 255 222    136  33 255   0    200 255 220   0
   9   0  35 255     73   0 255 217    137  37 255   0    201 255 215   0
  10   0  39 255     74   0 255 214    138  41 255   0    202 255 212   0
  11   0  43 255     75   0 255 209    139  45 255   0    203 255 207   0
  12   0  47 255     76   0 255 206    140  49 255   0    204 255 204   0
  13   0  51 255     77   0 255 201    141  53 255   0    205 255 199   0
  14   0  55 255     78   0 255 198    142  57 255   0    206 255 196   0
  15   0  59 255     79   0 255 193    143  61 255   0    207 255 191   0
  16   0  63 255     80   0 255 190    144  66 255   0    208 255 188   0
  17   0  67 255     81   0 255 185    145  70 255   0    209 255 183   0
  18   0  71 255     82   0 255 182    146  74 255   0    210 255 180   0
  19   0  75 255     83   0 255 177    147  78 255   0    211 255 175   0
  20   0  79 255     84   0 255 174    148  82 255   0    212 255 172   0
  21   0  83 255     85   0 255 169    149  86 255   0    213 255 167   0
  22   0  87 255     86   0 255 166    150  90 255   0    214 255 164   0
  23   0  91 255     87   0 255 161    151  94 255   0    215 255 159   0
  24   0  95 255     88   0 255 158    152  98 255   0    216 255 156   0
  25   0  99 255     89   0 255 153    153 102 255   0    217 255 151   0
  26   0 103 255     90   0 255 150    154 106 255   0    218 255 148   0
  27   0 107 255     91   0 255 145    155 110 255   0    219 255 143   0
  28   0 111 255     92   0 255 142    156 114 255   0    220 255 140   0
  29   0 115 255     93   0 255 137    157 118 255   0    221 255 135   0
  30   0 119 255     94   0 255 134    158 122 255   0    222 255 132   0
  31   0 123 255     95   0 255 129    159 126 255   0    223 255 127   0
  32   0 127 255     96   0 255 126    160 129 255   0    224 255 123   0
  33   0 132 255     97   0 255 122    161 134 255   0    225 255 119   0
  34   0 135 255     98   0 255 118    162 137 255   0    226 255 115   0
  35   0 140 255     99   0 255 114    163 142 255   0    227 255 111   0
  36   0 143 255    100   0 255 110    164 145 255   0    228 255 107   0
  37   0 148 255    101   0 255 106    165 150 255   0    229 255 103   0
  38   0 151 255    102   0 255 102    166 153 255   0    230 255  99   0
  39   0 156 255    103   0 255  98    167 158 255   0    231 255  95   0
  40   0 159 255    104   0 255  94    168 161 255   0    232 255  91   0
  41   0 164 255    105   0 255  90    169 166 255   0    233 255  87   0
  42   0 167 255    106   0 255  86    170 169 255   0    234 255  83   0
  43   0 172 255    107   0 255  82    171 174 255   0    235 255  79   0
  44   0 175 255    108   0 255  78    172 177 255   0    236 255  75   0
  45   0 180 255    109   0 255  74    173 182 255   0    237 255  71   0
  46   0 183 255    110   0 255  70    174 185 255   0    238 255  67   0
  47   0 188 255    111   0 255  66    175 190 255   0    239 255  63   0
  48   0 191 255    112   0 255  61    176 193 255   0    240 255  59   0
  49   0 196 255    113   0 255  57    177 198 255   0    241 255  55   0
  50   0 199 255    114   0 255  53    178 201 255   0    242 255  51   0
  51   0 204 255    115   0 255  49    179 206 255   0    243 255  47   0
  52   0 207 255    116   0 255  45    180 209 255   0    244 255  43   0
  53   0 212 255    117   0 255  41    181 214 255   0    245 255  39   0
  54   0 215 255    118   0 255  37    182 217 255   0    246 255  35   0
  55   0 220 255    119   0 255  33    183 222 255   0    247 255  31   0
  56   0 223 255    120   0 255  29    184 225 255   0    248 255  27   0
  57   0 228 255    121   0 255  25    185 230 255   0    249 255  23   0
  58   0 231 255    122   0 255  21    186 233 255   0    250 255  19   0
  59   0 236 255    123   0 255  17    187 238 255   0    251 255  15   0
  60   0 239 255    124   0 255  13    188 241 255   0    252 255  11   0
  61   0 244 255    125   0 255   9    189 246 255   0    253 255   7   0
  62   0 247 255    126   0 255   5    190 249 255   0    254 255   3   0
  63   0 252 255    127   0 255   1    191 254 255   0    255 255   0   0
TEXT;



        return self::GradientText2IndexedArray($text);

    }


    private static function GradientText2IndexedArray($text)
    {

        $result = array();
        foreach (explode("\n",$text) as $line)
        {

            $col = array();
            $col[] = substr($line, 0,16);
            $col[] = substr($line,19,16);
            $col[] = substr($line,38,16);
            $col[] = substr($line,57,16);

            foreach ($col as $key => $rgb_raw)
            {
                $index = trim(substr($rgb_raw,1,3));
                $r = trim(substr($rgb_raw,5,3));
                $g = trim( substr($rgb_raw,9,3));
                $b = trim(substr($rgb_raw,13,3));

                $result[$index] = new RGB($r,$g,$b);
            }
        }

        ksort($result);

        return  $result;
    }


    public static function ReverseGradient($gradient = null)
    {
        if (is_null($gradient)) $gradient = self::GradientBlueGreenRed();
        return array_reverse($gradient);
    }


    /*
     *
     *
     * http://paulbourke.net/texture_colour/colourramp/02.dat
     */
    public static function GradientBlueRed()
    {

$text = <<<TEXT
   0   0   0 255     64  63   0 191    128 127   0 127    192 191   0  63
   1   0   0 254     65  64   0 190    129 128   0 126    193 192   0  61
   2   1   0 253     66  66   0 189    130 129   0 124    194 193   0  61
   3   2   0 252     67  67   0 188    131 130   0 123    195 194   0  59
   4   3   0 250     68  67   0 186    132 132   0 123    196 196   0  59
   5   4   0 249     69  68   0 185    133 133   0 122    197 197   0  57
   6   5   0 248     70  70   0 184    134 134   0 120    198 198   0  57
   7   6   0 247     71  71   0 183    135 135   0 119    199 199   0  55
   8   7   0 247     72  71   0 183    136 135   0 119    200 199   0  55
   9   8   0 246     73  72   0 182    137 136   0 118    201 200   0  53
  10   9   0 245     74  74   0 181    138 137   0 116    202 201   0  53
  11  10   0 244     75  75   0 180    139 138   0 115    203 202   0  51
  12  11   0 242     76  75   0 178    140 140   0 115    204 204   0  51
  13  12   0 241     77  76   0 177    141 141   0 114    205 205   0  49
  14  13   0 240     78  78   0 176    142 142   0 112    206 206   0  49
  15  14   0 239     79  79   0 175    143 143   0 111    207 207   0  47
  16  15   0 239     80  79   0 175    144 143   0 111    208 207   0  47
  17  16   0 238     81  80   0 174    145 144   0 110    209 208   0  45
  18  17   0 237     82  82   0 173    146 145   0 108    210 209   0  45
  19  18   0 236     83  83   0 172    147 146   0 107    211 210   0  43
  20  19   0 234     84  83   0 170    148 148   0 107    212 212   0  43
  21  20   0 233     85  84   0 169    149 149   0 106    213 213   0  41
  22  21   0 232     86  86   0 168    150 150   0 104    214 214   0  41
  23  22   0 231     87  87   0 167    151 151   0 103    215 215   0  39
  24  23   0 231     88  87   0 167    152 151   0 103    216 215   0  39
  25  24   0 230     89  88   0 166    153 152   0 102    217 216   0  37
  26  25   0 229     90  90   0 165    154 153   0 100    218 217   0  37
  27  26   0 228     91  91   0 164    155 154   0  99    219 218   0  35
  28  27   0 226     92  91   0 162    156 156   0  99    220 220   0  35
  29  28   0 225     93  92   0 161    157 157   0  98    221 221   0  33
  30  29   0 224     94  94   0 160    158 158   0  96    222 222   0  33
  31  30   0 223     95  95   0 159    159 159   0  95    223 223   0  31
  32  31   0 223     96  95   0 159    160 159   0  95    224 223   0  30
  33  33   0 222     97  96   0 158    161 160   0  94    225 224   0  29
  34  33   0 221     98  98   0 157    162 161   0  92    226 225   0  28
  35  35   0 220     99  99   0 156    163 162   0  91    227 226   0  27
  36  35   0 218    100  99   0 154    164 164   0  91    228 228   0  26
  37  37   0 217    101 100   0 153    165 165   0  90    229 229   0  25
  38  37   0 216    102 102   0 152    166 166   0  88    230 230   0  24
  39  39   0 215    103 103   0 151    167 167   0  87    231 231   0  23
  40  39   0 215    104 103   0 151    168 167   0  87    232 231   0  22
  41  41   0 214    105 104   0 150    169 168   0  86    233 232   0  21
  42  41   0 213    106 106   0 149    170 169   0  84    234 233   0  20
  43  43   0 212    107 107   0 148    171 170   0  83    235 234   0  19
  44  43   0 210    108 107   0 146    172 172   0  83    236 236   0  18
  45  45   0 209    109 108   0 145    173 173   0  82    237 237   0  17
  46  45   0 208    110 110   0 144    174 174   0  80    238 238   0  16
  47  47   0 207    111 111   0 143    175 175   0  79    239 239   0  15
  48  47   0 207    112 111   0 143    176 175   0  79    240 239   0  14
  49  49   0 206    113 112   0 142    177 176   0  78    241 240   0  13
  50  49   0 205    114 114   0 141    178 177   0  76    242 241   0  12
  51  51   0 204    115 115   0 140    179 178   0  75    243 242   0  11
  52  51   0 202    116 115   0 138    180 180   0  75    244 244   0  10
  53  53   0 201    117 116   0 137    181 181   0  74    245 245   0   9
  54  53   0 200    118 118   0 136    182 182   0  72    246 246   0   8
  55  55   0 199    119 119   0 135    183 183   0  71    247 247   0   7
  56  55   0 199    120 119   0 135    184 183   0  71    248 247   0   6
  57  57   0 198    121 120   0 134    185 184   0  70    249 248   0   5
  58  57   0 197    122 122   0 133    186 185   0  68    250 249   0   4
  59  59   0 196    123 123   0 132    187 186   0  67    251 250   0   3
  60  59   0 194    124 123   0 130    188 188   0  67    252 252   0   2
  61  61   0 193    125 124   0 129    189 189   0  66    253 253   0   1
  62  61   0 192    126 126   0 128    190 190   0  64    254 254   0   0
  63  63   0 191    127 127   0 127    191 191   0  63    255 255   0   0
TEXT;


        return self::GradientText2IndexedArray($text);

    }

    /*
     *
     *
     * http://paulbourke.net/texture_colour/colourramp/04.dat
     */
    public static function GradientRainbow()
    {

$text = <<<TEXT
   0   0   0 255     64  63   0 191    128 127   0 127    192 191   0  63
   1   0   0 254     65  64   0 190    129 128   0 126    193 192   0  61
   2   1   0 253     66  66   0 189    130 129   0 124    194 193   0  61
   3   2   0 252     67  67   0 188    131 130   0 123    195 194   0  59
   4   3   0 250     68  67   0 186    132 132   0 123    196 196   0  59
   5   4   0 249     69  68   0 185    133 133   0 122    197 197   0  57
   6   5   0 248     70  70   0 184    134 134   0 120    198 198   0  57
   7   6   0 247     71  71   0 183    135 135   0 119    199 199   0  55
   8   7   0 247     72  71   0 183    136 135   0 119    200 199   0  55
   9   8   0 246     73  72   0 182    137 136   0 118    201 200   0  53
  10   9   0 245     74  74   0 181    138 137   0 116    202 201   0  53
  11  10   0 244     75  75   0 180    139 138   0 115    203 202   0  51
  12  11   0 242     76  75   0 178    140 140   0 115    204 204   0  51
  13  12   0 241     77  76   0 177    141 141   0 114    205 205   0  49
  14  13   0 240     78  78   0 176    142 142   0 112    206 206   0  49
  15  14   0 239     79  79   0 175    143 143   0 111    207 207   0  47
  16  15   0 239     80  79   0 175    144 143   0 111    208 207   0  47
  17  16   0 238     81  80   0 174    145 144   0 110    209 208   0  45
  18  17   0 237     82  82   0 173    146 145   0 108    210 209   0  45
  19  18   0 236     83  83   0 172    147 146   0 107    211 210   0  43
  20  19   0 234     84  83   0 170    148 148   0 107    212 212   0  43
  21  20   0 233     85  84   0 169    149 149   0 106    213 213   0  41
  22  21   0 232     86  86   0 168    150 150   0 104    214 214   0  41
  23  22   0 231     87  87   0 167    151 151   0 103    215 215   0  39
  24  23   0 231     88  87   0 167    152 151   0 103    216 215   0  39
  25  24   0 230     89  88   0 166    153 152   0 102    217 216   0  37
  26  25   0 229     90  90   0 165    154 153   0 100    218 217   0  37
  27  26   0 228     91  91   0 164    155 154   0  99    219 218   0  35
  28  27   0 226     92  91   0 162    156 156   0  99    220 220   0  35
  29  28   0 225     93  92   0 161    157 157   0  98    221 221   0  33
  30  29   0 224     94  94   0 160    158 158   0  96    222 222   0  33
  31  30   0 223     95  95   0 159    159 159   0  95    223 223   0  31
  32  31   0 223     96  95   0 159    160 159   0  95    224 223   0  30
  33  33   0 222     97  96   0 158    161 160   0  94    225 224   0  29
  34  33   0 221     98  98   0 157    162 161   0  92    226 225   0  28
  35  35   0 220     99  99   0 156    163 162   0  91    227 226   0  27
  36  35   0 218    100  99   0 154    164 164   0  91    228 228   0  26
  37  37   0 217    101 100   0 153    165 165   0  90    229 229   0  25
  38  37   0 216    102 102   0 152    166 166   0  88    230 230   0  24
  39  39   0 215    103 103   0 151    167 167   0  87    231 231   0  23
  40  39   0 215    104 103   0 151    168 167   0  87    232 231   0  22
  41  41   0 214    105 104   0 150    169 168   0  86    233 232   0  21
  42  41   0 213    106 106   0 149    170 169   0  84    234 233   0  20
  43  43   0 212    107 107   0 148    171 170   0  83    235 234   0  19
  44  43   0 210    108 107   0 146    172 172   0  83    236 236   0  18
  45  45   0 209    109 108   0 145    173 173   0  82    237 237   0  17
  46  45   0 208    110 110   0 144    174 174   0  80    238 238   0  16
  47  47   0 207    111 111   0 143    175 175   0  79    239 239   0  15
  48  47   0 207    112 111   0 143    176 175   0  79    240 239   0  14
  49  49   0 206    113 112   0 142    177 176   0  78    241 240   0  13
  50  49   0 205    114 114   0 141    178 177   0  76    242 241   0  12
  51  51   0 204    115 115   0 140    179 178   0  75    243 242   0  11
  52  51   0 202    116 115   0 138    180 180   0  75    244 244   0  10
  53  53   0 201    117 116   0 137    181 181   0  74    245 245   0   9
  54  53   0 200    118 118   0 136    182 182   0  72    246 246   0   8
  55  55   0 199    119 119   0 135    183 183   0  71    247 247   0   7
  56  55   0 199    120 119   0 135    184 183   0  71    248 247   0   6
  57  57   0 198    121 120   0 134    185 184   0  70    249 248   0   5
  58  57   0 197    122 122   0 133    186 185   0  68    250 249   0   4
  59  59   0 196    123 123   0 132    187 186   0  67    251 250   0   3
  60  59   0 194    124 123   0 130    188 188   0  67    252 252   0   2
  61  61   0 193    125 124   0 129    189 189   0  66    253 253   0   1
  62  61   0 192    126 126   0 128    190 190   0  64    254 254   0   0
  63  63   0 191    127 127   0 127    191 191   0  63    255 255   0   0
TEXT;

        return self::GradientText2IndexedArray($text);

    }


    /*
     *
     *
     * http://paulbourke.net/texture_colour/colourramp/14.dat
     */
    public static function GradientYellowOrangeRed()
    {

$text = <<<TEXT
   0 255 255   0     64 255 191   0    128 255 127   0    192 255  63   0
   1 255 254   0     65 255 190   0    129 255 126   0    193 255  61   0
   2 255 253   0     66 255 189   0    130 255 124   0    194 255  61   0
   3 255 252   0     67 255 188   0    131 255 123   0    195 255  59   0
   4 255 250   0     68 255 186   0    132 255 123   0    196 255  59   0
   5 255 249   0     69 255 185   0    133 255 122   0    197 255  57   0
   6 255 248   0     70 255 184   0    134 255 120   0    198 255  57   0
   7 255 247   0     71 255 183   0    135 255 119   0    199 255  55   0
   8 255 247   0     72 255 183   0    136 255 119   0    200 255  55   0
   9 255 246   0     73 255 182   0    137 255 118   0    201 255  53   0
  10 255 245   0     74 255 181   0    138 255 116   0    202 255  53   0
  11 255 244   0     75 255 180   0    139 255 115   0    203 255  51   0
  12 255 242   0     76 255 178   0    140 255 115   0    204 255  51   0
  13 255 241   0     77 255 177   0    141 255 114   0    205 255  49   0
  14 255 240   0     78 255 176   0    142 255 112   0    206 255  49   0
  15 255 239   0     79 255 175   0    143 255 111   0    207 255  47   0
  16 255 239   0     80 255 175   0    144 255 111   0    208 255  47   0
  17 255 238   0     81 255 174   0    145 255 110   0    209 255  45   0
  18 255 237   0     82 255 173   0    146 255 108   0    210 255  45   0
  19 255 236   0     83 255 172   0    147 255 107   0    211 255  43   0
  20 255 234   0     84 255 170   0    148 255 107   0    212 255  43   0
  21 255 233   0     85 255 169   0    149 255 106   0    213 255  41   0
  22 255 232   0     86 255 168   0    150 255 104   0    214 255  41   0
  23 255 231   0     87 255 167   0    151 255 103   0    215 255  39   0
  24 255 231   0     88 255 167   0    152 255 103   0    216 255  39   0
  25 255 230   0     89 255 166   0    153 255 102   0    217 255  37   0
  26 255 229   0     90 255 165   0    154 255 100   0    218 255  37   0
  27 255 228   0     91 255 164   0    155 255  99   0    219 255  35   0
  28 255 226   0     92 255 162   0    156 255  99   0    220 255  35   0
  29 255 225   0     93 255 161   0    157 255  98   0    221 255  33   0
  30 255 224   0     94 255 160   0    158 255  96   0    222 255  33   0
  31 255 223   0     95 255 159   0    159 255  95   0    223 255  31   0
  32 255 223   0     96 255 159   0    160 255  95   0    224 255  30   0
  33 255 222   0     97 255 158   0    161 255  94   0    225 255  29   0
  34 255 221   0     98 255 157   0    162 255  92   0    226 255  28   0
  35 255 220   0     99 255 156   0    163 255  91   0    227 255  27   0
  36 255 218   0    100 255 154   0    164 255  91   0    228 255  26   0
  37 255 217   0    101 255 153   0    165 255  90   0    229 255  25   0
  38 255 216   0    102 255 152   0    166 255  88   0    230 255  24   0
  39 255 215   0    103 255 151   0    167 255  87   0    231 255  23   0
  40 255 215   0    104 255 151   0    168 255  87   0    232 255  22   0
  41 255 214   0    105 255 150   0    169 255  86   0    233 255  21   0
  42 255 213   0    106 255 149   0    170 255  84   0    234 255  20   0
  43 255 212   0    107 255 148   0    171 255  83   0    235 255  19   0
  44 255 210   0    108 255 146   0    172 255  83   0    236 255  18   0
  45 255 209   0    109 255 145   0    173 255  82   0    237 255  17   0
  46 255 208   0    110 255 144   0    174 255  80   0    238 255  16   0
  47 255 207   0    111 255 143   0    175 255  79   0    239 255  15   0
  48 255 207   0    112 255 143   0    176 255  79   0    240 255  14   0
  49 255 206   0    113 255 142   0    177 255  78   0    241 255  13   0
  50 255 205   0    114 255 141   0    178 255  76   0    242 255  12   0
  51 255 204   0    115 255 140   0    179 255  75   0    243 255  11   0
  52 255 202   0    116 255 138   0    180 255  75   0    244 255  10   0
  53 255 201   0    117 255 137   0    181 255  74   0    245 255   9   0
  54 255 200   0    118 255 136   0    182 255  72   0    246 255   8   0
  55 255 199   0    119 255 135   0    183 255  71   0    247 255   7   0
  56 255 199   0    120 255 135   0    184 255  71   0    248 255   6   0
  57 255 198   0    121 255 134   0    185 255  70   0    249 255   5   0
  58 255 197   0    122 255 133   0    186 255  68   0    250 255   4   0
  59 255 196   0    123 255 132   0    187 255  67   0    251 255   3   0
  60 255 194   0    124 255 130   0    188 255  67   0    252 255   2   0
  61 255 193   0    125 255 129   0    189 255  66   0    253 255   1   0
  62 255 192   0    126 255 128   0    190 255  64   0    254 255   0   0
  63 255 191   0    127 255 127   0    191 255  63   0    255 255   0   0
TEXT;

        return self::GradientText2IndexedArray($text);

    }


    public static function cast($src)
    {
        $src instanceof RGB;
        return $src;
    }


    /*
     *
     *
     * http://paulbourke.net/texture_colour/colourramp/14.dat
     */
    public static function GradientGreenBeige()
    {

$text = <<<TEXT
   0   0 159   0     64 150 210   0    128 200 220  49    192 225 220 109
   1   2 160   0     65 152 210   0    129 200 220  49    193 225 220 110
   2   4 161   0     66 155 211   0    130 200 220  50    194 226 220 111
   3   7 162   0     67 157 212   0    131 201 220  51    195 226 220 112
   4   9 163   0     68 160 213   0    132 201 220  52    196 226 220 113
   5  11 163   0     69 162 214   0    133 202 220  53    197 227 220 114
   6  14 164   0     70 164 214   0    134 202 220  54    198 227 220 115
   7  16 165   0     71 167 215   0    135 202 220  55    199 228 220 116
   8  18 166   0     72 169 216   0    136 203 220  56    200 228 220 117
   9  21 167   0     73 171 217   0    137 203 220  57    201 228 220 118
  10  23 167   0     74 174 218   0    138 204 220  58    202 229 220 119
  11  25 168   0     75 176 218   0    139 204 220  59    203 229 220 120
  12  28 169   0     76 178 219   0    140 204 220  60    204 230 220 121
  13  30 170   0     77 180 220   0    141 205 220  61    205 230 220 122
  14  32 170   0     78 180 220   1    142 205 220  62    206 230 220 123
  15  35 171   0     79 180 220   2    143 206 220  63    207 231 220 124
  16  37 172   0     80 181 220   3    144 206 220  64    208 231 220 125
  17  40 173   0     81 181 220   4    145 206 220  65    209 231 220 126
  18  42 174   0     82 182 220   5    146 207 220  66    210 232 220 127
  19  44 174   0     83 182 220   6    147 207 220  67    211 232 220 128
  20  47 175   0     84 182 220   7    148 208 220  68    212 233 220 129
  21  49 176   0     85 183 220   8    149 208 220  69    213 233 220 129
  22  51 177   0     86 183 220   9    150 208 220  69    214 233 220 130
  23  54 178   0     87 184 220  10    151 209 220  70    215 234 220 131
  24  56 178   0     88 184 220  10    152 209 220  71    216 234 220 132
  25  58 179   0     89 184 220  11    153 209 220  72    217 235 220 133
  26  61 180   0     90 185 220  12    154 210 220  73    218 235 220 134
  27  63 181   0     91 185 220  13    155 210 220  74    219 235 220 135
  28  65 181   0     92 186 220  14    156 211 220  75    220 236 220 136
  29  68 182   0     93 186 220  15    157 211 220  76    221 236 220 137
  30  70 183   0     94 186 220  16    158 211 220  77    222 237 220 138
  31  72 184   0     95 187 220  17    159 212 220  78    223 237 220 139
  32  75 185   0     96 187 220  18    160 212 220  79    224 237 220 140
  33  77 185   0     97 188 220  19    161 213 220  80    225 238 220 141
  34  80 186   0     98 188 220  20    162 213 220  81    226 238 220 142
  35  82 187   0     99 188 220  21    163 213 220  82    227 239 220 143
  36  84 188   0    100 189 220  22    164 214 220  83    228 239 220 144
  37  87 189   0    101 189 220  23    165 214 220  84    229 239 220 145
  38  89 189   0    102 190 220  24    166 215 220  85    230 240 220 146
  39  91 190   0    103 190 220  25    167 215 220  86    231 240 220 147
  40  94 191   0    104 190 220  26    168 215 220  87    232 240 220 148
  41  96 192   0    105 191 220  27    169 216 220  88    233 241 220 149
  42  98 192   0    106 191 220  28    170 216 220  89    234 241 220 150
  43 101 193   0    107 191 220  29    171 217 220  89    235 242 220 150
  44 103 194   0    108 192 220  29    172 217 220  90    236 242 220 151
  45 105 195   0    109 192 220  30    173 217 220  91    237 242 220 152
  46 108 196   0    110 193 220  31    174 218 220  92    238 243 220 153
  47 110 196   0    111 193 220  32    175 218 220  93    239 243 220 154
  48 112 197   0    112 193 220  33    176 219 220  94    240 244 220 155
  49 115 198   0    113 194 220  34    177 219 220  95    241 244 220 156
  50 117 199   0    114 194 220  35    178 219 220  96    242 244 220 157
  51 120 199   0    115 195 220  36    179 220 220  97    243 245 220 158
  52 122 200   0    116 195 220  37    180 220 220  98    244 245 220 159
  53 124 201   0    117 195 220  38    181 220 220  99    245 246 220 160
  54 127 202   0    118 196 220  39    182 221 220 100    246 246 220 161
  55 129 203   0    119 196 220  40    183 221 220 101    247 246 220 162
  56 131 203   0    120 197 220  41    184 222 220 102    248 247 220 163
  57 134 204   0    121 197 220  42    185 222 220 103    249 247 220 164
  58 136 205   0    122 197 220  43    186 222 220 104    250 248 220 165
  59 138 206   0    123 198 220  44    187 223 220 105    251 248 220 166
  60 141 207   0    124 198 220  45    188 223 220 106    252 248 220 167
  61 143 207   0    125 199 220  46    189 224 220 107    253 249 220 168
  62 145 208   0    126 199 220  47    190 224 220 108    254 249 220 169
  63 148 209   0    127 199 220  48    191 224 220 109    255 249 220 169
TEXT;

        return self::GradientText2IndexedArray($text);

    }


    public static function GradientNames()
    {




    }


    public static function isRGB($src)
    {
        if ($src instanceof RGB) return true;
        return false;
    }


    
    
    /**
     *
     * @param type $ramp
     * @param type $class
     * @param type $id
     * @param type $unique_values  - array of value to colour unique values to be prefixed before scale
     * @return string 
     */
    public static function RampDisplay($ramp,$class = "ColorKey",$id = "ColorKey",$unique_values = null)
    {
        if (is_null($class)) $class = $class = "ColorKey";
        if (is_null($id)) $id = $id = "ColorKey";

        
        $keys = array_keys($ramp);
        
        $first = $keys[0]; $last = $keys[count($keys) - 1];
        
        $first_rgb = $ramp[$first];  $last_rgb = $ramp[$last]; 
        
        $result  = '<div  id="'.$id.'" class="'.$class.'">';

        if (!is_null($unique_values) && is_array($unique_values))
        {
            foreach ($unique_values as $value => $colour) 
            {
                $result .= '<div class="'.$class.'_unique_value" id="'.$id.'_'.$value.'"  style="padding-left:3px; float: left; height: 100%; background-color: '.$colour.' ; color: white;"> '.$value.' '."</div>";
            }
            
        }
        
        
        
        $result .= '<div class="'.$class.'_first_value" id="'.$id.'_first_value"  style="width: 65px; padding-left:5px; padding-right:5px; float: left; height: 100%; background-color: #' . $first_rgb->asHex() . ';">'. number_format($first, 3)."</div>";
        
        foreach ($ramp as $value => $rgb) 
        {    
            $rgb instanceof RGB;
            $result .= '<div class="'.$class.'_swatch" id="'.$id.'_'.$value.'"  style="float: left; width: 2px; height: 100%; background-color: #' . $rgb->asHex() . ';">&nbsp;</div>';
        }
        $result .= '<div class="'.$class.'_last_value" id="'.$id.'_last_value" style="padding-right:3px; float: left; height: 100%; background-color: #' . $last_rgb->asHex() . ';">'. number_format($last, 3)."</div>";
        
        $result .= "</div>";
        
        return $result;
        
    }
    
    
    


}

?>