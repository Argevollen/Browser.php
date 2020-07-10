<?php
/**
 * File: Browser.php
 * Author: Chris Schuld (http://chrisschuld.com/)
 * Last Modified: April 14th, 2020
 * @version 2.1
 *
 * Copyright 2019 Chris Schuld
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the "Software"),
 * to deal in the Software without restriction, including without
 * limitation the rights to use, copy, modify, merge, publish, distribute,
 * sublicense, and/or sell copies of the Software, and to permit persons to
 * whom the Software is furnished to do so, subject to the following
 * conditions:
 * 
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. 
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
 * CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * Typical Usage:
 *
 *   $browser = new Browser();
 *   if( $browser->getBrowser() == Browser::BROWSER_FIREFOX && $browser->getVersion() >= 2 ) {
 *    echo 'You have FireFox version 2 or greater';
 *   }
 *
 * User Agents Sampled from: http://www.useragentstring.com/
 *
 * This implementation is based on the original work from Gary White
 * http://apptools.com/phptools/browser/
 *
 */
class Browser
{
    private $_agent = '';
    private $_browser_name = '';
    private $_version = '';
    private $_platform = '';
    private $_os = '';
    private $_is_aol = false;
    private $_is_mobile = false;
    private $_is_tablet = false;
    private $_is_robot = false;
    private $_is_facebook = false;
    private $_aol_version = '';

    const BROWSER_UNKNOWN = 'unknown';
    const VERSION_UNKNOWN = 'unknown';

    const BROWSER_OPERA = 'Opera'; // http://www.opera.com/
    const BROWSER_OPERA_MINI = 'Opera Mini'; // http://www.opera.com/mini/
    const BROWSER_WEBTV = 'WebTV'; // http://www.webtv.net/pc/
    const BROWSER_EDGE = 'Edge'; // https://www.microsoft.com/edge
    const BROWSER_IE = 'Internet Explorer'; // http://www.microsoft.com/ie/
    const BROWSER_POCKET_IE = 'Pocket Internet Explorer'; // http://en.wikipedia.org/wiki/Internet_Explorer_Mobile
    const BROWSER_KONQUEROR = 'Konqueror'; // http://www.konqueror.org/
    const BROWSER_ICAB = 'iCab'; // http://www.icab.de/
    const BROWSER_OMNIWEB = 'OmniWeb'; // http://www.omnigroup.com/applications/omniweb/
    const BROWSER_FIREBIRD = 'Firebird'; // http://www.ibphoenix.com/
    const BROWSER_FIREFOX = 'Firefox'; // https://www.mozilla.org/en-US/firefox/
    const BROWSER_BRAVE = 'Brave'; // https://brave.com/
    const BROWSER_PALEMOON = 'Palemoon'; // https://www.palemoon.org/
    const BROWSER_ICEWEASEL = 'Iceweasel'; // http://www.geticeweasel.org/
    const BROWSER_SHIRETOKO = 'Shiretoko'; // http://wiki.mozilla.org/Projects/shiretoko
    const BROWSER_MOZILLA = 'Mozilla'; // http://www.mozilla.com/en-US/
    const BROWSER_AMAYA = 'Amaya'; // http://www.w3.org/Amaya/
    const BROWSER_LYNX = 'Lynx'; // http://en.wikipedia.org/wiki/Lynx
    const BROWSER_SAFARI = 'Safari'; // http://apple.com
    const BROWSER_IPHONE = 'iPhone'; // http://apple.com
    const BROWSER_IPOD = 'iPod'; // http://apple.com
    const BROWSER_IPAD = 'iPad'; // http://apple.com
    const BROWSER_CHROME = 'Chrome'; // http://www.google.com/chrome
    const BROWSER_ANDROID = 'Android'; // http://www.android.com/
    const BROWSER_GOOGLEBOT = 'GoogleBot'; // http://en.wikipedia.org/wiki/Googlebot
    const BROWSER_CURL = 'cURL'; // https://en.wikipedia.org/wiki/CURL
    const BROWSER_WGET = 'Wget'; // https://en.wikipedia.org/wiki/Wget
    const BROWSER_UCBROWSER = 'UCBrowser'; // https://www.ucweb.com/


    const BROWSER_YANDEXBOT = 'YandexBot'; // http://yandex.com/bots
    const BROWSER_YANDEXIMAGERESIZER_BOT = 'YandexImageResizer'; // http://yandex.com/bots
    const BROWSER_YANDEXIMAGES_BOT = 'YandexImages'; // http://yandex.com/bots
    const BROWSER_YANDEXVIDEO_BOT = 'YandexVideo'; // http://yandex.com/bots
    const BROWSER_YANDEXMEDIA_BOT = 'YandexMedia'; // http://yandex.com/bots
    const BROWSER_YANDEXBLOGS_BOT = 'YandexBlogs'; // http://yandex.com/bots
    const BROWSER_YANDEXFAVICONS_BOT = 'YandexFavicons'; // http://yandex.com/bots
    const BROWSER_YANDEXWEBMASTER_BOT = 'YandexWebmaster'; // http://yandex.com/bots
    const BROWSER_YANDEXDIRECT_BOT = 'YandexDirect'; // http://yandex.com/bots
    const BROWSER_YANDEXMETRIKA_BOT = 'YandexMetrika'; // http://yandex.com/bots
    const BROWSER_YANDEXNEWS_BOT = 'YandexNews'; // http://yandex.com/bots
    const BROWSER_YANDEXCATALOG_BOT = 'YandexCatalog'; // http://yandex.com/bots

    const BROWSER_SLURP = 'Yahoo! Slurp'; // http://en.wikipedia.org/wiki/Yahoo!_Slurp
    const BROWSER_W3CVALIDATOR = 'W3C Validator'; // http://validator.w3.org/
    const BROWSER_BLACKBERRY = 'BlackBerry'; // http://www.blackberry.com/
    const BROWSER_ICECAT = 'IceCat'; // http://en.wikipedia.org/wiki/GNU_IceCat
    const BROWSER_NOKIA_S60 = 'Nokia S60 OSS Browser'; // http://en.wikipedia.org/wiki/Web_Browser_for_S60
    const BROWSER_NOKIA = 'Nokia Browser'; // * all other WAP-based browsers on the Nokia Platform
    const BROWSER_MSN = 'MSN Browser'; // http://explorer.msn.com/
    const BROWSER_MSNBOT = 'MSN Bot'; // http://search.msn.com/msnbot.htm
    const BROWSER_BINGBOT = 'Bing Bot'; // http://en.wikipedia.org/wiki/Bingbot
    const BROWSER_VIVALDI = 'Vivaldi'; // https://vivaldi.com/
    const BROWSER_YANDEX = 'Yandex'; // https://browser.yandex.ua/

    const BROWSER_NETSCAPE_NAVIGATOR = 'Netscape Navigator'; // http://browser.netscape.com/ (DEPRECATED)
    const BROWSER_GALEON = 'Galeon'; // http://galeon.sourceforge.net/ (DEPRECATED)
    const BROWSER_NETPOSITIVE = 'NetPositive'; // http://en.wikipedia.org/wiki/NetPositive (DEPRECATED)
    const BROWSER_PHOENIX = 'Phoenix'; // http://en.wikipedia.org/wiki/History_of_Mozilla_Firefox (DEPRECATED)
    const BROWSER_PLAYSTATION = "PlayStation";
    const BROWSER_SAMSUNG = "SamsungBrowser";
    const BROWSER_SILK = "Silk";
    const BROWSER_I_FRAME = "Iframely";
    const BROWSER_COCOA = "CocoaRestClient";

    const PLATFORM_UNKNOWN = 'unknown';
    const PLATFORM_WINDOWS = 'Windows';
    const PLATFORM_WINDOWS_CE = 'Windows CE';
    const PLATFORM_APPLE = 'Apple';
    const PLATFORM_LINUX = 'Linux';
    const PLATFORM_OS2 = 'OS/2';
    const PLATFORM_BEOS = 'BeOS';
    const PLATFORM_IPHONE = 'iPhone';
    const PLATFORM_IPOD = 'iPod';
    const PLATFORM_IPAD = 'iPad';
    const PLATFORM_BLACKBERRY = 'BlackBerry';
    const PLATFORM_NOKIA = 'Nokia';
    const PLATFORM_FREEBSD = 'FreeBSD';
    const PLATFORM_OPENBSD = 'OpenBSD';
    const PLATFORM_NETBSD = 'NetBSD';
    const PLATFORM_SUNOS = 'SunOS';
    const PLATFORM_OPENSOLARIS = 'OpenSolaris';
    const PLATFORM_ANDROID = 'Android';
    const PLATFORM_PLAYSTATION = "Sony PlayStation";
    const PLATFORM_ROKU = "Roku";
    const PLATFORM_APPLE_TV = "Apple TV";
    const PLATFORM_TERMINAL = "Terminal";
    const PLATFORM_FIRE_OS = "Fire OS";
    const PLATFORM_SMART_TV = "SMART-TV";
    const PLATFORM_CHROME_OS = "Chrome OS";
    const PLATFORM_JAVA_ANDROID = "Java/Android";
    const PLATFORM_POSTMAN = "Postman";
    const PLATFORM_I_FRAME = "Iframely";
    const PLATFORM_FACEBOOK = 'Facebook Platform';
    const PLATFORM_GOOGLE_API = 'Google API';
    const PLATFORM_TWITTERBOOT = 'Twitter Boot';
    const PLATFORM_APACHE = 'Apache';

    // OS. Newly added obtained from http://user-agent-string.info/list-of-ua/os
    // UPDATED 31.12.2018
    const OPERATING_SYSTEM_UNKNOWN = 'unknown';
    
    //BLACKBERRY
    const OPERATING_SYSTEM_BLACKBERRY = 'BlackBerry';
    const OPERATING_SYSTEM_BLACKBERRY_TABLET_OS_1 = 'BlackBerry Tablet OS 1';
    const OPERATING_SYSTEM_BLACKBERRY_TABLET_OS_2 = 'BlackBerry Tablet OS 2';
    //WINDOWS
    const OPERATING_SYSTEM_WINDOWS = 'Windows';
    const OPERATING_SYSTEM_WINDOWS_CE = 'Windows CE';
    const OPERATING_SYSTEM_WINDOWS_10 = 'Windows 10';
    const OPERATING_SYSTEM_WINDOWS_8_1 = 'Windows 8.1';
    const OPERATING_SYSTEM_WINDOWS_RT = 'Windows RT';
    const OPERATING_SYSTEM_WINDOWS_8 = 'Windows 8';
    const OPERATING_SYSTEM_WINDOWS_7 = 'Windows 7';
    const OPERATING_SYSTEM_WINDOWS_VISTA = 'Windows Vista';
    const OPERATING_SYSTEM_WINDOWS_SERVER = 'Windows 2003 Server';
    const OPERATING_SYSTEM_WINDOWS_XP = 'Windows XP';
    const OPERATING_SYSTEM_WINDOWS_2000 = 'Windows 2000';
    const OPERATING_SYSTEM_WINDOWS_ME = 'Windows ME';
    const OPERATING_SYSTEM_WINDOWS_98 = 'Windows 98';
    const OPERATING_SYSTEM_WINDOWS_95 = 'Windows 95';
    const OPERATING_SYSTEM_WINDOWS_3 = 'Windows 3';
    const OPERATING_SYSTEM_WINDOWS_NT = 'Windows NT';
    const OPERATING_SYSTEM_WINDOWS_MOBILE = 'Windows Mobile';
    const OPERATING_SYSTEM_WINDOWS_PHONE_7 = 'Windows Phone 7';
    const OPERATING_SYSTEM_WINDOWS_PHONE_8 = 'Windows Phone 8';
    const OPERATING_SYSTEM_WINDOWS_PHONE_8_1 = 'Windows Phone 8.1';
    const OPERATING_SYSTEM_WINDOWS_PHONE_10 = 'Windows 10 Mobile';
    const OPERATING_SYSTEM_WINDOWS_10_IOT = 'Windows 10 IoT';
    //CONSOLES
    const OPERATING_SYSTEM_XMB = 'XrossMediaBar (Playstation 3|Playstation Portable)';
    const OPERATING_SYSTEM_LIVE_AREA = 'LiveArea (Playstation Vita)';
    const OPERATING_SYSTEM_ORBIS = 'Orbis OS (Playstation 4)';
    const OPERATING_SYSTEM_NINTENDO_DS = 'Nintendo DS';
    const OPERATING_SYSTEM_NINTENDO_3DS = 'Nintendo 3DS';
    const OPERATING_SYSTEM_NINTENDO_WII = 'Wii OS (Nintendo Wii)';
    const OPERATING_SYSTEM_NINTENDO_WIIU = 'Wii U OS (Nintendo Wii U)';
    const OPERATING_SYSTEM_XBOX = 'Xbox OS (Xbox (Original|360|One))';
    //MOBILE
    const OPERATING_SYSTEM_FIREFOX = 'FireFox OS';
    const OPERATING_SYSTEM_TIZEN_1 = 'Tizen 1';
    const OPERATING_SYSTEM_TIZEN_2 = 'Tizen 2';
    const OPERATING_SYSTEM_TIZEN_3 = 'Tizen 3';
    const OPERATING_SYSTEM_TIZEN_4 = 'Tizen 4';
    const OPERATING_SYSTEM_WEBOS = 'WebOS';
    const OPERATING_SYSTEM_LUNEOS = 'LuneOS';
    //ANDROID
    const OPERATING_SYSTEM_ANDROID = 'Android';
    const OPERATING_SYSTEM_ANDROID_1 = 'Android 1';
    const OPERATING_SYSTEM_ANDROID_1_5 = 'Android 1.5 Cupcake';
    const OPERATING_SYSTEM_ANDROID_1_6 = 'Android 1.6 Donut';
    const OPERATING_SYSTEM_ANDROID_2 = 'Android 2.0/1 Eclair';
    const OPERATING_SYSTEM_ANDROID_2_2 = 'Android 2.2 Froyo';
    const OPERATING_SYSTEM_ANDROID_2_3 = 'Android 2.3 Gingerbread';
    const OPERATING_SYSTEM_ANDROID_3 = 'Android 3 Honeycomb';
    const OPERATING_SYSTEM_ANDROID_4 = 'Android 4.0 Ice Cream Sandwich';
    const OPERATING_SYSTEM_ANDROID_4_1 = 'Android 4.1 Jelly Bean';
    const OPERATING_SYSTEM_ANDROID_4_2 = 'Android 4.2 Jelly Bean';
    const OPERATING_SYSTEM_ANDROID_4_3 = 'Android 4.3 Jelly Bean';
    const OPERATING_SYSTEM_ANDROID_4_4 = 'Android 4.4 KitKat';
    const OPERATING_SYSTEM_ANDROID_5_0 = 'Android 5.0 Lollipop';
    const OPERATING_SYSTEM_ANDROID_5_1 = 'Android 5.1 Lollipop';
    const OPERATING_SYSTEM_ANDROID_6_0 = 'Android 6 Marshmallow';
    const OPERATING_SYSTEM_ANDROID_7_0 = 'Android 7.0 Nougat';
    const OPERATING_SYSTEM_ANDROID_7_1 = 'Android 7.1 Nougat';
    const OPERATING_SYSTEM_ANDROID_8_0 = 'Android 8.0 Oreo';
    const OPERATING_SYSTEM_ANDROID_8_1 = 'Android 8.1 Oreo';
    const OPERATING_SYSTEM_ANDROID_9_0 = 'Android 9.0 Pie';
    const OPERATING_SYSTEM_ANDROID_TV = 'Android TV';
    //IOS
    const OPERATING_SYSTEM_IOS = 'iOS';
    const OPERATING_SYSTEM_IOS_4 = 'iOS 4';
    const OPERATING_SYSTEM_IOS_5 = 'iOS 5';
    const OPERATING_SYSTEM_IOS_6 = 'iOS 6';
    const OPERATING_SYSTEM_IOS_7 = 'iOS 7';
    const OPERATING_SYSTEM_IOS_8 = 'iOS 8';
    const OPERATING_SYSTEM_IOS_9 = 'iOS 9';
    const OPERATING_SYSTEM_IOS_10 = 'iOS 10';
    const OPERATING_SYSTEM_IOS_11 = 'iOS 11';
    const OPERATING_SYSTEM_IOS_12 = 'iOS 12';
    //MAC OS
    const OPERATING_SYSTEM_MAC = 'Mac OS';
    const OPERATING_SYSTEM_MAC_X = 'Mac OS X';
    const OPERATING_SYSTEM_MAC_X_10_3 = 'Mac OS X 10.3 Panther';
    const OPERATING_SYSTEM_MAC_X_10_4 = 'Mac OS X 10.4 Tiger';
    const OPERATING_SYSTEM_MAC_X_10_5 = 'Mac OS X 10.5 Leopard';
    const OPERATING_SYSTEM_MAC_X_10_6 = 'Mac OS X 10.6 Snow Leopard';
    const OPERATING_SYSTEM_MAC_X_10_7 = 'Mac OS X 10.7 Lion';
    const OPERATING_SYSTEM_MAC_X_10_8 = 'Mac OS X 10.8 Mountain Lion';
    const OPERATING_SYSTEM_MAC_X_10_9 = 'Mac OS X 10.9 Mavericks';
    const OPERATING_SYSTEM_MAC_X_10_10 = 'Mac OS X 10.10 Yosemite';
    const OPERATING_SYSTEM_MAC_X_10_11 = 'Mac OS X 10.11 El Capitan';
    const OPERATING_SYSTEM_MAC_X_10_12 = 'Mac OS X 10.12 El Sierra';
    const OPERATING_SYSTEM_MAC_X_10_13 = 'Mac OS X 10.13 El High Sierra';
    const OPERATING_SYSTEM_MAC_X_10_14 = 'Mac OS X 10.14 El Mojave';
    //LINUX
    const OPERATING_SYSTEM_LINUX = 'Linux';
    const OPERATING_SYSTEM_LINUX_ARCH = 'Linux (Arch Linux)';
    const OPERATING_SYSTEM_LINUX_CENTOS = 'Linux (CentOS)';
    const OPERATING_SYSTEM_LINUX_DEBIAN = 'Linux (Debian)';
    const OPERATING_SYSTEM_LINUX_FEDORA = 'Linux (Fedora)';
    const OPERATING_SYSTEM_LINUX_GENTOO = 'Linux (Gentoo)';
    const OPERATING_SYSTEM_LINUX_KANOTIX = 'Linux (Kanotix)';
    const OPERATING_SYSTEM_LINUX_KNOPPIX = 'Linux (Knoppix)';
    const OPERATING_SYSTEM_LINUX_LINSPIRE = 'Linux (Linspire)';
    const OPERATING_SYSTEM_LINUX_MAEMO = 'Linux (Maemo)';
    const OPERATING_SYSTEM_LINUX_MAGEIA = 'Linux (Mageia)';
    const OPERATING_SYSTEM_LINUX_MANDRIVA = 'Linux (Mandriva)';
    const OPERATING_SYSTEM_LINUX_MINT = 'Linux (Mint)';
    const OPERATING_SYSTEM_LINUX_REDHAT = 'Linux (RedHat)';
    const OPERATING_SYSTEM_LINUX_SLACKWARE = 'Linux (slackware)';
    const OPERATING_SYSTEM_LINUX_SUSE = 'Linux (Suse)';
    const OPERATING_SYSTEM_LINUX_UBUNTU = 'Linux (Ubuntu)';
    const OPERATING_SYSTEM_LINUX_VECTOR = 'Linux (VectorLinux)';
    const OPERATING_SYSTEM_LINUX_PCLINUX = 'Linux (PCLinuxOS)';
    const OPERATING_SYSTEM_LINUX_XUBUNTU = 'Linux (Xubuntu)';
    const OPERATING_SYSTEM_LINUX_ELEMENTARY_OS = 'Linux (elementary OS)';
    //other
    const OPERATING_SYSTEM_AIX = 'AIX';
    const OPERATING_SYSTEM_AMIGA = 'Amiga OS';
    const OPERATING_SYSTEM_AROS = 'AROS';
    const OPERATING_SYSTEM_BADA = 'Bada';
    const OPERATING_SYSTEM_BEOS = 'BeOS';
    const OPERATING_SYSTEM_BREW = 'Brew';
    const OPERATING_SYSTEM_CROME = 'Crome OS';
    const OPERATING_SYSTEM_DANGER_HIPTOP = 'Danger Hiptop';
    const OPERATING_SYSTEM_DRAGONFLY_BSD = 'DragonFly BSD';
    const OPERATING_SYSTEM_GNU = 'GNU OS';
    const OPERATING_SYSTEM_HAIKU = 'Haiku OS';
    const OPERATING_SYSTEM_HP = 'HP-UX';
    const OPERATING_SYSTEM_INFERNO = 'Inferno OS';
    const OPERATING_SYSTEM_IRIX = 'IRIX';
    const OPERATING_SYSTEM_JOLI = 'Joli OS';
    const OPERATING_SYSTEM_JVM = 'JVM (Java)';
    const OPERATING_SYSTEM_MEEGO = 'MeeGo';
    const OPERATING_SYSTEM_MINIX_3 = 'MINIX 3';
    const OPERATING_SYSTEM_MORPHOS = 'MorphOs';
    const OPERATING_SYSTEM_MSN_TV = 'MSN TV (WebTV)';
    const OPERATING_SYSTEM_APPLE_TV = 'Apple TV';
    const OPERATING_SYSTEM_NETBSD = 'NetBSD';
    const OPERATING_SYSTEM_OPENBSD = 'OpenBSD';
    const OPERATING_SYSTEM_OPENVMS = 'OpenVMS';
    const OPERATING_SYSTEM_OS2 = 'OS/2';
    const OPERATING_SYSTEM_OS2_WARP = 'OS/2 Warp';
    const OPERATING_SYSTEM_PALM = 'Palm OS';
    const OPERATING_SYSTEM_QNX = 'QNX x86pc';
    const OPERATING_SYSTEM_RISK = 'RISK OS';
    const OPERATING_SYSTEM_SAILFISH = 'Sailfish';
    const OPERATING_SYSTEM_SUNOS = 'Solaris';
    const OPERATING_SYSTEM_SYLLABLE = 'Syllable';
    const OPERATING_SYSTEM_SYMBIAN = 'Symbian OS';
    const OPERATING_SYSTEM_UBUNTU_TOUCH = 'Ubuntu Touch';
    const OPERATING_SYSTEM_FREEBSD = 'FreeBSD';
    const OPERATING_SYSTEM_OPENSOLARIS = 'OpenSolaris';
    const OPERATING_SYSTEM_NOKIA = 'Nokia';
    const OPERATING_SYSTEM_NOKIA_X = 'Nokia X';
    const OPERATING_SYSTEM_TRUEOS = 'TrueOS';
    const OPERATING_SYSTEM_KOLIBRIOS = 'KolibriOS';
    const OPERATING_SYSTEM_COS = 'COS (China Operating System)';
    const OPERATING_SYSTEM_ALIOS = 'AliOS';

    /**
     * Class constructor
     * @param string $userAgent
     */
    public function __construct($userAgent = '')
    {
        if ($userAgent != '') {
            $this->setUserAgent($userAgent);
        } else {
            $this->reset();
            $this->determine();
        }
    }

    /**
     * Reset all properties
     */
    public function reset()
    {
        $this->_agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $this->_browser_name = self::BROWSER_UNKNOWN;
        $this->_version = self::VERSION_UNKNOWN;
        $this->_platform = self::PLATFORM_UNKNOWN;
        $this->_os = self::OPERATING_SYSTEM_UNKNOWN;
        $this->_is_aol = false;
        $this->_is_mobile = false;
        $this->_is_tablet = false;
        $this->_is_robot = false;
        $this->_is_facebook = false;
        $this->_aol_version = self::VERSION_UNKNOWN;
    }

    /**
     * Check to see if the specific browser is valid
     * @param string $browserName
     * @return bool True if the browser is the specified browser
     */
    function isBrowser($browserName)
    {
        return (0 == strcasecmp($this->_browser_name, trim($browserName)));
    }

    /**
     * The name of the browser.  All return types are from the class contants
     * @return string Name of the browser
     */
    public function getBrowser()
    {
        return $this->_browser_name;
    }

    /**
     * Set the name of the browser
     * @param $browser string The name of the Browser
     */
    public function setBrowser($browser)
    {
        $this->_browser_name = $browser;
    }

    /**
     * The name of the platform.  All return types are from the class contants
     * @return string Name of the browser
     */
    public function getPlatform()
    {
        return $this->_platform;
    }

    /**
     * Set the name of the platform
     * @param string $platform The name of the Platform
     */
    public function setPlatform($platform)
    {
        $this->_platform = $platform;
    }

    /**
     * The version of the browser.
     * @return string Version of the browser (will only contain alpha-numeric characters and a period)
     */
    public function getVersion()
    {
        return $this->_version;
    }

    /**
     * Set the version of the browser
     * @param string $version The version of the Browser
     */
    public function setVersion($version)
    {
        $this->_version = preg_replace('/[^0-9,.,a-z,A-Z-]/', '', $version);
    }

    /**
     * The version of AOL.
     * @return string Version of AOL (will only contain alpha-numeric characters and a period)
     */
    public function getAolVersion()
    {
        return $this->_aol_version;
    }

    /**
     * Set the version of AOL
     * @param string $version The version of AOL
     */
    public function setAolVersion($version)
    {
        $this->_aol_version = preg_replace('/[^0-9,.,a-z,A-Z]/', '', $version);
    }

    /**
     * Is the browser from AOL?
     * @return boolean True if the browser is from AOL otherwise false
     */
    public function isAol()
    {
        return $this->_is_aol;
    }

    /**
     * Is the browser from a mobile device?
     * @return boolean True if the browser is from a mobile device otherwise false
     */
    public function isMobile()
    {
        return $this->_is_mobile;
    }

    /**
     * Is the browser from a tablet device?
     * @return boolean True if the browser is from a tablet device otherwise false
     */
    public function isTablet()
    {
        return $this->_is_tablet;
    }

    /**
     * Is the browser from a robot (ex Slurp,GoogleBot)?
     * @return boolean True if the browser is from a robot otherwise false
     */
    public function isRobot()
    {
        return $this->_is_robot;
    }

    /**
     * Is the browser from facebook?
     * @return boolean True if the browser is from facebook otherwise false
     */
    public function isFacebook()
    {
        return $this->_is_facebook;
    }

    /**
     * Set the browser to be from AOL
     * @param $isAol
     */
    public function setAol($isAol)
    {
        $this->_is_aol = $isAol;
    }

    /**
     * Set the Browser to be mobile
     * @param boolean $value is the browser a mobile browser or not
     */
    protected function setMobile($value = true)
    {
        $this->_is_mobile = $value;
    }

    /**
     * Set the Browser to be tablet
     * @param boolean $value is the browser a tablet browser or not
     */
    protected function setTablet($value = true)
    {
        $this->_is_tablet = $value;
    }

    /**
     * Set the Browser to be a robot
     * @param boolean $value is the browser a robot or not
     */
    protected function setRobot($value = true)
    {
        $this->_is_robot = $value;
    }

    /**
     * Set the Browser to be a Facebook request
     * @param boolean $value is the browser a robot or not
     */
    protected function setFacebook($value = true)
    {
        $this->_is_facebook = $value;
    }

    /**
     * Get the user agent value in use to determine the browser
     * @return string The user agent from the HTTP header
     */
    public function getUserAgent()
    {
        return $this->_agent;
    }

    /**
     * Set the user agent value (the construction will use the HTTP header value - this will overwrite it)
     * @param string $agent_string The value for the User Agent
     */
    public function setUserAgent($agent_string)
    {
        $this->reset();
        $this->_agent = $agent_string;
        $this->determine();
    }

    /**
     * Used to determine if the browser is actually "chromeframe"
     * @since 1.7
     * @return boolean True if the browser is using chromeframe
     */
    public function isChromeFrame()
    {
        return (strpos($this->_agent, "chromeframe") !== false);
    }

    /**
     * Returns a formatted string with a summary of the details of the browser.
     * @return string formatted string with a summary of the browser
     */
    public function __toString()
    {
        return "<strong>Browser Name:</strong> {$this->getBrowser()}<br/>\n" .
            "<strong>Browser Version:</strong> {$this->getVersion()}<br/>\n" .
            "<strong>Browser User Agent String:</strong> {$this->getUserAgent()}<br/>\n" .
            "<strong>Platform:</strong> {$this->getPlatform()}<br/>";
    }
    
    /**
    * UPDATE: Ivijan-Stefan Stipic
    * Returns a formatted array with a all informations of the browser.
    * @return array with a all informations of the browser
    */
    public function __toArray() {
        return array(
            'browser' => $this->getBrowser(),
            'version' => $this->getVersion(),
            'user_agent' => $this->getUserAgent(),
            'platform' => $this->getPlatform()
        );
    }

    /**
     * Protected routine to calculate and determine what the browser is in use (including platform)
     */
    protected function determine()
    {
        $this->checkPlatform();
        $this->checkBrowsers();
        $this->checkForAol();
        $this->checkOS();
    }

    /**
     * Protected routine to determine the browser type
     * @return boolean True if the browser was detected otherwise false
     */
    protected function checkBrowsers()
    {
        return (
            // well-known, well-used
            // Special Notes:
            // (1) Opera must be checked before FireFox due to the odd
            //     user agents used in some older versions of Opera
            // (2) WebTV is strapped onto Internet Explorer so we must
            //     check for WebTV before IE
            // (3) (deprecated) Galeon is based on Firefox and needs to be
            //     tested before Firefox is tested
            // (4) OmniWeb is based on Safari so OmniWeb check must occur
            //     before Safari
            // (5) Netscape 9+ is based on Firefox so Netscape checks
            //     before FireFox are necessary
            // (6) Vivaldi is UA contains both Firefox and Chrome so Vivaldi checks
            //     before Firefox and Chrome
            $this->checkBrowserWebTv() ||
            $this->checkBrowserBrave() ||
            $this->checkBrowserUCBrowser() ||
            $this->checkBrowserEdge() ||
            $this->checkBrowserInternetExplorer() ||
            $this->checkBrowserOpera() ||
            $this->checkBrowserGaleon() ||
            $this->checkBrowserNetscapeNavigator9Plus() ||
            $this->checkBrowserVivaldi() ||
            $this->checkBrowserYandex() ||
            $this->checkBrowserPalemoon() ||
            $this->checkBrowserFirefox() ||
            $this->checkBrowserChrome() ||
            $this->checkBrowserOmniWeb() ||

            // common mobile
            $this->checkBrowserAndroid() ||
            $this->checkBrowseriPad() ||
            $this->checkBrowseriPod() ||
            $this->checkBrowseriPhone() ||
            $this->checkBrowserBlackBerry() ||
            $this->checkBrowserNokia() ||

            // common bots
            $this->checkBrowserGoogleBot() ||
            $this->checkBrowserMSNBot() ||
            $this->checkBrowserBingBot() ||
            $this->checkBrowserSlurp() ||

            // Yandex bots
            $this->checkBrowserYandexBot() ||
            $this->checkBrowserYandexImageResizerBot() ||
            $this->checkBrowserYandexBlogsBot() ||
            $this->checkBrowserYandexCatalogBot() ||
            $this->checkBrowserYandexDirectBot() ||
            $this->checkBrowserYandexFaviconsBot() ||
            $this->checkBrowserYandexImagesBot() ||
            $this->checkBrowserYandexMediaBot() ||
            $this->checkBrowserYandexMetrikaBot() ||
            $this->checkBrowserYandexNewsBot() ||
            $this->checkBrowserYandexVideoBot() ||
            $this->checkBrowserYandexWebmasterBot() ||

            // check for facebook external hit when loading URL
            $this->checkFacebookExternalHit() ||

            // WebKit base check (post mobile and others)
            $this->checkBrowserSamsung() ||
            $this->checkBrowserSilk() ||
            $this->checkBrowserSafari() ||

            // everyone else
            $this->checkBrowserNetPositive() ||
            $this->checkBrowserFirebird() ||
            $this->checkBrowserKonqueror() ||
            $this->checkBrowserIcab() ||
            $this->checkBrowserPhoenix() ||
            $this->checkBrowserAmaya() ||
            $this->checkBrowserLynx() ||
            $this->checkBrowserShiretoko() ||
            $this->checkBrowserIceCat() ||
            $this->checkBrowserIceweasel() ||
            $this->checkBrowserW3CValidator() ||
            $this->checkBrowserCurl() ||
            $this->checkBrowserWget() ||
            $this->checkBrowserPlayStation() ||
            $this->checkBrowserIframely() ||
            $this->checkBrowserCocoa() ||
            $this->checkBrowserMozilla() /* Mozilla is such an open standard that you must check it last */);
    }

    /**
     * Determine if the user is using a BlackBerry (last updated 1.7)
     * @return boolean True if the browser is the BlackBerry browser otherwise false
     */
    protected function checkBrowserBlackBerry()
    {
        if (stripos($this->_agent, 'blackberry') !== false) {
            $aresult = explode('/', stristr($this->_agent, "BlackBerry"));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
                $this->_browser_name = self::BROWSER_BLACKBERRY;
                $this->setMobile(true);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the user is using an AOL User Agent (last updated 1.7)
     * @return boolean True if the browser is from AOL otherwise false
     */
    protected function checkForAol()
    {
        $this->setAol(false);
        $this->setAolVersion(self::VERSION_UNKNOWN);

        if (stripos($this->_agent, 'aol') !== false) {
            $aversion = explode(' ', stristr($this->_agent, 'AOL'));
            if (isset($aversion[1])) {
                $this->setAol(true);
                $this->setAolVersion(preg_replace('/[^0-9\.a-z]/i', '', $aversion[1]));
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is the GoogleBot or not (last updated 1.7)
     * @return boolean True if the browser is the GoogletBot otherwise false
     */
    protected function checkBrowserGoogleBot()
    {
        if (stripos($this->_agent, 'googlebot') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'googlebot'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion(str_replace(';', '', $aversion[0]));
                $this->_browser_name = self::BROWSER_GOOGLEBOT;
                $this->setRobot(true);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is the YandexBot or not
     * @return boolean True if the browser is the YandexBot otherwise false
     */
    protected function checkBrowserYandexBot()
    {
        if (stripos($this->_agent, 'YandexBot') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'YandexBot'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion(str_replace(';', '', $aversion[0]));
                $this->_browser_name = self::BROWSER_YANDEXBOT;
                $this->setRobot(true);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is the YandexImageResizer or not
     * @return boolean True if the browser is the YandexImageResizer otherwise false
     */
    protected function checkBrowserYandexImageResizerBot()
    {
        if (stripos($this->_agent, 'YandexImageResizer') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'YandexImageResizer'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion(str_replace(';', '', $aversion[0]));
                $this->_browser_name = self::BROWSER_YANDEXIMAGERESIZER_BOT;
                $this->setRobot(true);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is the YandexCatalog or not
     * @return boolean True if the browser is the YandexCatalog otherwise false
     */
    protected function checkBrowserYandexCatalogBot()
    {
        if (stripos($this->_agent, 'YandexCatalog') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'YandexCatalog'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion(str_replace(';', '', $aversion[0]));
                $this->_browser_name = self::BROWSER_YANDEXCATALOG_BOT;
                $this->setRobot(true);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is the YandexNews or not
     * @return boolean True if the browser is the YandexNews otherwise false
     */
    protected function checkBrowserYandexNewsBot()
    {
        if (stripos($this->_agent, 'YandexNews') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'YandexNews'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion(str_replace(';', '', $aversion[0]));
                $this->_browser_name = self::BROWSER_YANDEXNEWS_BOT;
                $this->setRobot(true);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is the YandexMetrika or not
     * @return boolean True if the browser is the YandexMetrika otherwise false
     */
    protected function checkBrowserYandexMetrikaBot()
    {
        if (stripos($this->_agent, 'YandexMetrika') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'YandexMetrika'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion(str_replace(';', '', $aversion[0]));
                $this->_browser_name = self::BROWSER_YANDEXMETRIKA_BOT;
                $this->setRobot(true);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is the YandexDirect or not
     * @return boolean True if the browser is the YandexDirect otherwise false
     */
    protected function checkBrowserYandexDirectBot()
    {
        if (stripos($this->_agent, 'YandexDirect') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'YandexDirect'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion(str_replace(';', '', $aversion[0]));
                $this->_browser_name = self::BROWSER_YANDEXDIRECT_BOT;
                $this->setRobot(true);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is the YandexWebmaster or not
     * @return boolean True if the browser is the YandexWebmaster otherwise false
     */
    protected function checkBrowserYandexWebmasterBot()
    {
        if (stripos($this->_agent, 'YandexWebmaster') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'YandexWebmaster'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion(str_replace(';', '', $aversion[0]));
                $this->_browser_name = self::BROWSER_YANDEXWEBMASTER_BOT;
                $this->setRobot(true);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is the YandexFavicons or not
     * @return boolean True if the browser is the YandexFavicons otherwise false
     */
    protected function checkBrowserYandexFaviconsBot()
    {
        if (stripos($this->_agent, 'YandexFavicons') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'YandexFavicons'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion(str_replace(';', '', $aversion[0]));
                $this->_browser_name = self::BROWSER_YANDEXFAVICONS_BOT;
                $this->setRobot(true);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is the YandexBlogs or not
     * @return boolean True if the browser is the YandexBlogs otherwise false
     */
    protected function checkBrowserYandexBlogsBot()
    {
        if (stripos($this->_agent, 'YandexBlogs') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'YandexBlogs'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion(str_replace(';', '', $aversion[0]));
                $this->_browser_name = self::BROWSER_YANDEXBLOGS_BOT;
                $this->setRobot(true);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is the YandexMedia or not
     * @return boolean True if the browser is the YandexMedia otherwise false
     */
    protected function checkBrowserYandexMediaBot()
    {
        if (stripos($this->_agent, 'YandexMedia') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'YandexMedia'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion(str_replace(';', '', $aversion[0]));
                $this->_browser_name = self::BROWSER_YANDEXMEDIA_BOT;
                $this->setRobot(true);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is the YandexVideo or not
     * @return boolean True if the browser is the YandexVideo otherwise false
     */
    protected function checkBrowserYandexVideoBot()
    {
        if (stripos($this->_agent, 'YandexVideo') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'YandexVideo'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion(str_replace(';', '', $aversion[0]));
                $this->_browser_name = self::BROWSER_YANDEXVIDEO_BOT;
                $this->setRobot(true);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is the YandexImages or not
     * @return boolean True if the browser is the YandexImages otherwise false
     */
    protected function checkBrowserYandexImagesBot()
    {
        if (stripos($this->_agent, 'YandexImages') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'YandexImages'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion(str_replace(';', '', $aversion[0]));
                $this->_browser_name = self::BROWSER_YANDEXIMAGES_BOT;
                $this->setRobot(true);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is the MSNBot or not (last updated 1.9)
     * @return boolean True if the browser is the MSNBot otherwise false
     */
    protected function checkBrowserMSNBot()
    {
        if (stripos($this->_agent, "msnbot") !== false) {
            $aresult = explode("/", stristr($this->_agent, "msnbot"));
            if (isset($aresult[1])) {
                $aversion = explode(" ", $aresult[1]);
                $this->setVersion(str_replace(";", '', $aversion[0]));
                $this->_browser_name = self::BROWSER_MSNBOT;
                $this->setRobot(true);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is the BingBot or not (last updated 1.9)
     * @return boolean True if the browser is the BingBot otherwise false
     */
    protected function checkBrowserBingBot()
    {
        if (stripos($this->_agent, "bingbot") !== false) {
            $aresult = explode("/", stristr($this->_agent, "bingbot"));
            if (isset($aresult[1])) {
                $aversion = explode(" ", $aresult[1]);
                $this->setVersion(str_replace(";", '', $aversion[0]));
                $this->_browser_name = self::BROWSER_BINGBOT;
                $this->setRobot(true);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is the W3C Validator or not (last updated 1.7)
     * @return boolean True if the browser is the W3C Validator otherwise false
     */
    protected function checkBrowserW3CValidator()
    {
        if (stripos($this->_agent, 'W3C-checklink') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'W3C-checklink'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
                $this->_browser_name = self::BROWSER_W3CVALIDATOR;
                return true;
            }
        } else if (stripos($this->_agent, 'W3C_Validator') !== false) {
            // Some of the Validator versions do not delineate w/ a slash - add it back in
            $ua = str_replace("W3C_Validator ", "W3C_Validator/", $this->_agent);
            $aresult = explode('/', stristr($ua, 'W3C_Validator'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
                $this->_browser_name = self::BROWSER_W3CVALIDATOR;
                return true;
            }
        } else if (stripos($this->_agent, 'W3C-mobileOK') !== false) {
            $this->_browser_name = self::BROWSER_W3CVALIDATOR;
            $this->setMobile(true);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is the Yahoo! Slurp Robot or not (last updated 1.7)
     * @return boolean True if the browser is the Yahoo! Slurp Robot otherwise false
     */
    protected function checkBrowserSlurp()
    {
        if (stripos($this->_agent, 'slurp') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'Slurp'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
                $this->_browser_name = self::BROWSER_SLURP;
                $this->setRobot(true);
                $this->setMobile(false);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is Brave or not
     * @return boolean True if the browser is Brave otherwise false
     */
    protected function checkBrowserBrave()
    {
        if (stripos($this->_agent, 'Brave/') !== false) {
            $aResult = explode('/', stristr($this->_agent, 'Brave'));
            if (isset($aResult[1])) {
                $aversion = explode(' ', $aResult[1]);
                $this->setVersion($aversion[0]);
                $this->setBrowser(self::BROWSER_BRAVE);
                return true;
            }
        } elseif (stripos($this->_agent, ' Brave ') !== false) {
            $this->setBrowser(self::BROWSER_BRAVE);
            // this version of the UA did not ship with a version marker
            // e.g. Mozilla/5.0 (Linux; Android 7.0; SM-G955F Build/NRD90M) AppleWebKit/537.36 (KHTML, like Gecko) Brave Chrome/68.0.3440.91 Mobile Safari/537.36
            $this->setVersion('');
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Edge or not
     * @return boolean True if the browser is Edge otherwise false
     */
    protected function checkBrowserEdge()
    {
        if ($name = (stripos($this->_agent, 'Edge/') !== false ? 'Edge' : ((stripos($this->_agent, 'Edg/') !== false || stripos($this->_agent, 'EdgA/') !== false) ? 'Edg' : false))) {
            $aresult = explode('/', stristr($this->_agent, $name));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
                $this->setBrowser(self::BROWSER_EDGE);
                if (stripos($this->_agent, 'Windows Phone') !== false || stripos($this->_agent, 'Android') !== false) {
                    $this->setMobile(true);
                }
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is Internet Explorer or not (last updated 1.7)
     * @return boolean True if the browser is Internet Explorer otherwise false
     */
    protected function checkBrowserInternetExplorer()
    {
        //  Test for IE11
        if (stripos($this->_agent, 'Trident/7.0; rv:11.0') !== false) {
            $this->setBrowser(self::BROWSER_IE);
            $this->setVersion('11.0');
            return true;
        } // Test for v1 - v1.5 IE
        else if (stripos($this->_agent, 'microsoft internet explorer') !== false) {
            $this->setBrowser(self::BROWSER_IE);
            $this->setVersion('1.0');
            $aresult = stristr($this->_agent, '/');
            if (preg_match('/308|425|426|474|0b1/i', $aresult)) {
                $this->setVersion('1.5');
            }
            return true;
        } // Test for versions > 1.5
        else if (stripos($this->_agent, 'msie') !== false && stripos($this->_agent, 'opera') === false) {
            // See if the browser is the odd MSN Explorer
            if (stripos($this->_agent, 'msnb') !== false) {
                $aresult = explode(' ', stristr(str_replace(';', '; ', $this->_agent), 'MSN'));
                if (isset($aresult[1])) {
                    $this->setBrowser(self::BROWSER_MSN);
                    $this->setVersion(str_replace(array('(', ')', ';'), '', $aresult[1]));
                    return true;
                }
            }
            $aresult = explode(' ', stristr(str_replace(';', '; ', $this->_agent), 'msie'));
            if (isset($aresult[1])) {
                $this->setBrowser(self::BROWSER_IE);
                $this->setVersion(str_replace(array('(', ')', ';'), '', $aresult[1]));
                if (stripos($this->_agent, 'IEMobile') !== false) {
                    $this->setBrowser(self::BROWSER_POCKET_IE);
                    $this->setMobile(true);
                }
                return true;
            }
        } // Test for versions > IE 10
        else if (stripos($this->_agent, 'trident') !== false) {
            $this->setBrowser(self::BROWSER_IE);
            $result = explode('rv:', $this->_agent);
            if (isset($result[1])) {
                $this->setVersion(preg_replace('/[^0-9.]+/', '', $result[1]));
                $this->_agent = str_replace(array("Mozilla", "Gecko"), "MSIE", $this->_agent);
            }
        } // Test for Pocket IE
        else if (stripos($this->_agent, 'mspie') !== false || stripos($this->_agent, 'pocket') !== false) {
            $aresult = explode(' ', stristr($this->_agent, 'mspie'));
            if (isset($aresult[1])) {
                $this->setPlatform(self::PLATFORM_WINDOWS_CE);
                $this->setBrowser(self::BROWSER_POCKET_IE);
                $this->setMobile(true);

                if (stripos($this->_agent, 'mspie') !== false) {
                    $this->setVersion($aresult[1]);
                } else {
                    $aversion = explode('/', $this->_agent);
                    if (isset($aversion[1])) {
                        $this->setVersion($aversion[1]);
                    }
                }
                return true;
            }
        }
        /* 
         * UPDATE: Ivijan-Stefan Stipic
         * -Test versions for IE8,9,10,11...
         * -Facebook, Google, Twitter platforms
         */
        else if( stripos($this->_agent,'trident') !== false && stripos($this->_agent,'windows') !== false && stripos($this->_agent,'rv') !== false ) {
            $aversion = explode(' ',stristr($this->_agent,'rv:'));
            $this->setVersion(str_replace('rv:','',$aversion[0]));
            $this->setBrowser(self::BROWSER_IE);
            return true;
        }
        else if( stripos($this->_agent,'facebook') !== false && stripos($this->_agent,'externalhit') !== false) {
            $aversion = explode(' ',stristr($this->_agent,'facebookexternalhit/'));
            $this->setPlatform(self::PLATFORM_FACEBOOK);
            $this->setVersion(str_replace('facebookexternalhit/','',$aversion[0]));
            $this->setBrowser(self::BROWSER_FACEBOOK);
            return true;
        }
        else if( stripos($this->_agent,'Google-HTTP-Java-Client') !== false) {
            $aversion = explode(' ',stristr($this->_agent,'Google-HTTP-Java-Client/'));
            $this->setPlatform(self::PLATFORM_GOOGLE_API);
            $this->setVersion(str_replace('Google-HTTP-Java-Client/','',$aversion[0]));
            $this->setBrowser(self::BROWSER_GOOGLEBOT);
            return true;
        }
        else if( stripos($this->_agent,'Twitterbot') !== false) {
            $aversion = explode('/',$this->_agent);
            $this->setPlatform(self::PLATFORM_TWITTERBOOT);
            $this->setVersion($aversion[1]);
            $this->setBrowser(self::BROWSER_TWITTER);
            return true;
        }
        else if( stripos($this->_agent,'Jakarta') !== false && stripos($this->_agent,'HttpClient') !== false ) {
            $aversion = explode('/',$this->_agent);
            $this->setPlatform(self::PLATFORM_APACHE);
            $this->setVersion($aversion[1]);
            $this->setBrowser(self::BROWSER_APACHE_CLIENT);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Opera or not (last updated 1.7)
     * @return boolean True if the browser is Opera otherwise false
     */
    protected function checkBrowserOpera()
    {
        if (stripos($this->_agent, 'opera mini') !== false) {
            $resultant = stristr($this->_agent, 'opera mini');
            if (preg_match('/\//', $resultant)) {
                $aresult = explode('/', $resultant);
                if (isset($aresult[1])) {
                    $aversion = explode(' ', $aresult[1]);
                    $this->setVersion($aversion[0]);
                }
            } else {
                $aversion = explode(' ', stristr($resultant, 'opera mini'));
                if (isset($aversion[1])) {
                    $this->setVersion($aversion[1]);
                }
            }
            $this->_browser_name = self::BROWSER_OPERA_MINI;
            $this->setMobile(true);
            return true;
        } else if (stripos($this->_agent, 'opera') !== false) {
            $resultant = stristr($this->_agent, 'opera');
            if (preg_match('/Version\/(1*.*)$/', $resultant, $matches)) {
                $this->setVersion($matches[1]);
            } else if (preg_match('/\//', $resultant)) {
                $aresult = explode('/', str_replace("(", " ", $resultant));
                if (isset($aresult[1])) {
                    $aversion = explode(' ', $aresult[1]);
                    $this->setVersion($aversion[0]);
                }
            } else {
                $aversion = explode(' ', stristr($resultant, 'opera'));
                $this->setVersion(isset($aversion[1]) ? $aversion[1] : '');
            }
            if (stripos($this->_agent, 'Opera Mobi') !== false) {
                $this->setMobile(true);
            }
            $this->_browser_name = self::BROWSER_OPERA;
            return true;
        } else if (stripos($this->_agent, 'OPR') !== false) {
            $resultant = stristr($this->_agent, 'OPR');
            if( preg_match('/Version\/(10.*)$/',$resultant,$matches) ) {
                $this->setVersion($matches[1]);
            }
            else if (preg_match('/\//', $resultant)) {
                $aresult = explode('/', str_replace("(", " ", $resultant));
                if (isset($aresult[1])) {
                    $aversion = explode(' ', $aresult[1]);
                    $this->setVersion($aversion[0]);
                }
            }
            if (stripos($this->_agent, 'Mobile') !== false) {
                $this->setMobile(true);
            }
            $this->_browser_name = self::BROWSER_OPERA;
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Chrome or not (last updated 1.7)
     * @return boolean True if the browser is Chrome otherwise false
     */
    protected function checkBrowserChrome()
    {
        if (stripos($this->_agent, 'Chrome') !== false) {
            $aresult = preg_split('/[\/;]+/', stristr($this->_agent, 'Chrome'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
                $this->setBrowser(self::BROWSER_CHROME);
                //Chrome on Android
                if (stripos($this->_agent, 'Android') !== false) {
                    if (stripos($this->_agent, 'Mobile') !== false) {
                        $this->setMobile(true);
                    } else {
                        $this->setTablet(true);
                    }
                }
                return true;
            }
        }
        return false;
    }


    /**
     * Determine if the browser is WebTv or not (last updated 1.7)
     * @return boolean True if the browser is WebTv otherwise false
     */
    protected function checkBrowserWebTv()
    {
        if (stripos($this->_agent, 'webtv') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'webtv'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
                $this->setBrowser(self::BROWSER_WEBTV);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is NetPositive or not (last updated 1.7)
     * @return boolean True if the browser is NetPositive otherwise false
     */
    protected function checkBrowserNetPositive()
    {
        if (stripos($this->_agent, 'NetPositive') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'NetPositive'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion(str_replace(array('(', ')', ';'), '', $aversion[0]));
                $this->setBrowser(self::BROWSER_NETPOSITIVE);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is Galeon or not (last updated 1.7)
     * @return boolean True if the browser is Galeon otherwise false
     */
    protected function checkBrowserGaleon()
    {
        if (stripos($this->_agent, 'galeon') !== false) {
            $aresult = explode(' ', stristr($this->_agent, 'galeon'));
            $aversion = explode('/', $aresult[0]);
            if (isset($aversion[1])) {
                $this->setVersion($aversion[1]);
                $this->setBrowser(self::BROWSER_GALEON);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is Konqueror or not (last updated 1.7)
     * @return boolean True if the browser is Konqueror otherwise false
     */
    protected function checkBrowserKonqueror()
    {
        if (stripos($this->_agent, 'Konqueror') !== false) {
            $aresult = explode(' ', stristr($this->_agent, 'Konqueror'));
            $aversion = explode('/', $aresult[0]);
            if (isset($aversion[1])) {
                $this->setVersion($aversion[1]);
                $this->setBrowser(self::BROWSER_KONQUEROR);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is iCab or not (last updated 1.7)
     * @return boolean True if the browser is iCab otherwise false
     */
    protected function checkBrowserIcab()
    {
        if (stripos($this->_agent, 'icab') !== false) {
            $aversion = explode(' ', stristr(str_replace('/', ' ', $this->_agent), 'icab'));
            if (isset($aversion[1])) {
                $this->setVersion($aversion[1]);
                $this->setBrowser(self::BROWSER_ICAB);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is OmniWeb or not (last updated 1.7)
     * @return boolean True if the browser is OmniWeb otherwise false
     */
    protected function checkBrowserOmniWeb()
    {
        if (stripos($this->_agent, 'omniweb') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'omniweb'));
            $aversion = explode(' ', isset($aresult[1]) ? $aresult[1] : '');
            $this->setVersion($aversion[0]);
            $this->setBrowser(self::BROWSER_OMNIWEB);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Phoenix or not (last updated 1.7)
     * @return boolean True if the browser is Phoenix otherwise false
     */
    protected function checkBrowserPhoenix()
    {
        if (stripos($this->_agent, 'Phoenix') !== false) {
            $aversion = explode('/', stristr($this->_agent, 'Phoenix'));
            if (isset($aversion[1])) {
                $this->setVersion($aversion[1]);
                $this->setBrowser(self::BROWSER_PHOENIX);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is Firebird or not (last updated 1.7)
     * @return boolean True if the browser is Firebird otherwise false
     */
    protected function checkBrowserFirebird()
    {
        if (stripos($this->_agent, 'Firebird') !== false) {
            $aversion = explode('/', stristr($this->_agent, 'Firebird'));
            if (isset($aversion[1])) {
                $this->setVersion($aversion[1]);
                $this->setBrowser(self::BROWSER_FIREBIRD);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is Netscape Navigator 9+ or not (last updated 1.7)
     * NOTE: (http://browser.netscape.com/ - Official support ended on March 1st, 2008)
     * @return boolean True if the browser is Netscape Navigator 9+ otherwise false
     */
    protected function checkBrowserNetscapeNavigator9Plus()
    {
        if (stripos($this->_agent, 'Firefox') !== false && preg_match('/Navigator\/([^ ]*)/i', $this->_agent, $matches)) {
            $this->setVersion($matches[1]);
            $this->setBrowser(self::BROWSER_NETSCAPE_NAVIGATOR);
            return true;
        } else if (stripos($this->_agent, 'Firefox') === false && preg_match('/Netscape6?\/([^ ]*)/i', $this->_agent, $matches)) {
            $this->setVersion($matches[1]);
            $this->setBrowser(self::BROWSER_NETSCAPE_NAVIGATOR);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Shiretoko or not (https://wiki.mozilla.org/Projects/shiretoko) (last updated 1.7)
     * @return boolean True if the browser is Shiretoko otherwise false
     */
    protected function checkBrowserShiretoko()
    {
        if (stripos($this->_agent, 'Mozilla') !== false && preg_match('/Shiretoko\/([^ ]*)/i', $this->_agent, $matches)) {
            $this->setVersion($matches[1]);
            $this->setBrowser(self::BROWSER_SHIRETOKO);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Ice Cat or not (http://en.wikipedia.org/wiki/GNU_IceCat) (last updated 1.7)
     * @return boolean True if the browser is Ice Cat otherwise false
     */
    protected function checkBrowserIceCat()
    {
        if (stripos($this->_agent, 'Mozilla') !== false && preg_match('/IceCat\/([^ ]*)/i', $this->_agent, $matches)) {
            $this->setVersion($matches[1]);
            $this->setBrowser(self::BROWSER_ICECAT);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Nokia or not (last updated 1.7)
     * @return boolean True if the browser is Nokia otherwise false
     */
    protected function checkBrowserNokia()
    {
        if (preg_match("/Nokia([^\/]+)\/([^ SP]+)/i", $this->_agent, $matches)) {
            $this->setVersion($matches[2]);
            if (stripos($this->_agent, 'Series60') !== false || strpos($this->_agent, 'S60') !== false) {
                $this->setBrowser(self::BROWSER_NOKIA_S60);
            } else {
                $this->setBrowser(self::BROWSER_NOKIA);
            }
            $this->setMobile(true);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Palemoon or not
     * @return boolean True if the browser is Palemoon otherwise false
     */
    protected function checkBrowserPalemoon()
    {
        if (stripos($this->_agent, 'safari') === false) {
            if (preg_match("/Palemoon[\/ \(]([^ ;\)]+)/i", $this->_agent, $matches)) {
                $this->setVersion($matches[1]);
                $this->setBrowser(self::BROWSER_PALEMOON);
                return true;
            } else if (preg_match("/Palemoon([0-9a-zA-Z\.]+)/i", $this->_agent, $matches)) {
                $this->setVersion($matches[1]);
                $this->setBrowser(self::BROWSER_PALEMOON);
                return true;
            } else if (preg_match("/Palemoon/i", $this->_agent, $matches)) {
                $this->setVersion('');
                $this->setBrowser(self::BROWSER_PALEMOON);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is UCBrowser or not
     * @return boolean True if the browser is UCBrowser otherwise false
     */
    protected function checkBrowserUCBrowser()
    {
        if (preg_match('/UC ?Browser\/?([\d\.]+)/', $this->_agent, $matches)) {
            if (isset($matches[1])) {
                $this->setVersion($matches[1]);
            }
            if (stripos($this->_agent, 'Mobile') !== false) {
                $this->setMobile(true);
            } else {
                $this->setTablet(true);
            }
            $this->setBrowser(self::BROWSER_UCBROWSER);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Firefox or not
     * @return boolean True if the browser is Firefox otherwise false
     */
    protected function checkBrowserFirefox()
    {
        if (stripos($this->_agent, 'safari') === false) {
            if (preg_match("/Firefox[\/ \(]([^ ;\)]+)/i", $this->_agent, $matches)) {
                $this->setVersion($matches[1]);
                $this->setBrowser(self::BROWSER_FIREFOX);
                //Firefox on Android
                if (stripos($this->_agent, 'Android') !== false || stripos($this->_agent, 'iPhone') !== false) {
                    if (stripos($this->_agent, 'Mobile') !== false || stripos($this->_agent, 'Tablet') !== false) {
                        $this->setMobile(true);
                    } else {
                        $this->setTablet(true);
                    }
                }
                return true;
            } else if (preg_match("/Firefox([0-9a-zA-Z\.]+)/i", $this->_agent, $matches)) {
                $this->setVersion($matches[1]);
                $this->setBrowser(self::BROWSER_FIREFOX);
                return true;
            } else if (preg_match("/Firefox$/i", $this->_agent, $matches)) {
                $this->setVersion('');
                $this->setBrowser(self::BROWSER_FIREFOX);
                return true;
            }
        } elseif (preg_match("/FxiOS[\/ \(]([^ ;\)]+)/i", $this->_agent, $matches)) {
            $this->setVersion($matches[1]);
            $this->setBrowser(self::BROWSER_FIREFOX);
            //Firefox on Android
            if (stripos($this->_agent, 'Android') !== false || stripos($this->_agent, 'iPhone') !== false) {
                if (stripos($this->_agent, 'Mobile') !== false || stripos($this->_agent, 'Tablet') !== false) {
                    $this->setMobile(true);
                } else {
                    $this->setTablet(true);
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Firefox or not (last updated 1.7)
     * @return boolean True if the browser is Firefox otherwise false
     */
    protected function checkBrowserIceweasel()
    {
        if (stripos($this->_agent, 'Iceweasel') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'Iceweasel'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
                $this->setBrowser(self::BROWSER_ICEWEASEL);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is Mozilla or not (last updated 1.7)
     * @return boolean True if the browser is Mozilla otherwise false
     */
    protected function checkBrowserMozilla()
    {
        if (stripos($this->_agent, 'mozilla') !== false && preg_match('/rv:[0-9].[0-9][a-b]?/i', $this->_agent) && stripos($this->_agent, 'netscape') === false) {
            $aversion = explode(' ', stristr($this->_agent, 'rv:'));
            preg_match('/rv:[0-9].[0-9][a-b]?/i', $this->_agent, $aversion);
            $this->setVersion(str_replace('rv:', '', $aversion[0]));
            $this->setBrowser(self::BROWSER_MOZILLA);
            return true;
        } else if (stripos($this->_agent, 'mozilla') !== false && preg_match('/rv:[0-9]\.[0-9]/i', $this->_agent) && stripos($this->_agent, 'netscape') === false) {
            $aversion = explode('', stristr($this->_agent, 'rv:'));
            $this->setVersion(str_replace('rv:', '', $aversion[0]));
            $this->setBrowser(self::BROWSER_MOZILLA);
            return true;
        } else if (stripos($this->_agent, 'mozilla') !== false && preg_match('/mozilla\/([^ ]*)/i', $this->_agent, $matches) && stripos($this->_agent, 'netscape') === false) {
            $this->setVersion($matches[1]);
            $this->setBrowser(self::BROWSER_MOZILLA);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Lynx or not (last updated 1.7)
     * @return boolean True if the browser is Lynx otherwise false
     */
    protected function checkBrowserLynx()
    {
        if (stripos($this->_agent, 'lynx') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'Lynx'));
            $aversion = explode(' ', (isset($aresult[1]) ? $aresult[1] : ''));
            $this->setVersion($aversion[0]);
            $this->setBrowser(self::BROWSER_LYNX);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Amaya or not (last updated 1.7)
     * @return boolean True if the browser is Amaya otherwise false
     */
    protected function checkBrowserAmaya()
    {
        if (stripos($this->_agent, 'amaya') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'Amaya'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
                $this->setBrowser(self::BROWSER_AMAYA);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is Safari or not (last updated 1.7)
     * @return boolean True if the browser is Safari otherwise false
     */
    protected function checkBrowserSafari()
    {
        if (
            stripos($this->_agent, 'Safari') !== false
            && stripos($this->_agent, 'iPhone') === false
            && stripos($this->_agent, 'iPod') === false
        ) {

            $aresult = explode('/', stristr($this->_agent, 'Version'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
            } else {
                $this->setVersion(self::VERSION_UNKNOWN);
            }
            $this->setBrowser(self::BROWSER_SAFARI);
            return true;
        }
        return false;
    }

    protected function checkBrowserSamsung()
    {
        if (stripos($this->_agent, 'SamsungBrowser') !== false) {

            $aresult = explode('/', stristr($this->_agent, 'SamsungBrowser'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
            } else {
                $this->setVersion(self::VERSION_UNKNOWN);
            }
            $this->setBrowser(self::BROWSER_SAMSUNG);
            return true;
        }
        return false;
    }

    protected function checkBrowserSilk()
    {
        if (stripos($this->_agent, 'Silk') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'Silk'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
            } else {
                $this->setVersion(self::VERSION_UNKNOWN);
            }
            $this->setBrowser(self::BROWSER_SILK);
            return true;
        }
        return false;
    }

    protected function checkBrowserIframely()
    {
        if (stripos($this->_agent, 'Iframely') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'Iframely'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
            } else {
                $this->setVersion(self::VERSION_UNKNOWN);
            }
            $this->setBrowser(self::BROWSER_I_FRAME);
            return true;
        }
        return false;
    }

    protected function checkBrowserCocoa()
    {
        if (stripos($this->_agent, 'CocoaRestClient') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'CocoaRestClient'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
            } else {
                $this->setVersion(self::VERSION_UNKNOWN);
            }
            $this->setBrowser(self::BROWSER_COCOA);
            return true;
        }
        return false;
    }

    /**
     * Detect if URL is loaded from FacebookExternalHit
     * @return boolean True if it detects FacebookExternalHit otherwise false
     */
    protected function checkFacebookExternalHit()
    {
        if (stristr($this->_agent, 'FacebookExternalHit')) {
            $this->setRobot(true);
            $this->setFacebook(true);
            return true;
        }
        return false;
    }

    /**
     * Detect if URL is being loaded from internal Facebook browser
     * @return boolean True if it detects internal Facebook browser otherwise false
     */
    protected function checkForFacebookIos()
    {
        if (stristr($this->_agent, 'FBIOS')) {
            $this->setFacebook(true);
            return true;
        }
        return false;
    }

    /**
     * Detect Version for the Safari browser on iOS devices
     * @return boolean True if it detects the version correctly otherwise false
     */
    protected function getSafariVersionOnIos()
    {
        $aresult = explode('/', stristr($this->_agent, 'Version'));
        if (isset($aresult[1])) {
            $aversion = explode(' ', $aresult[1]);
            $this->setVersion($aversion[0]);
            return true;
        }
        return false;
    }

    /**
     * Detect Version for the Chrome browser on iOS devices
     * @return boolean True if it detects the version correctly otherwise false
     */
    protected function getChromeVersionOnIos()
    {
        $aresult = explode('/', stristr($this->_agent, 'CriOS'));
        if (isset($aresult[1])) {
            $aversion = explode(' ', $aresult[1]);
            $this->setVersion($aversion[0]);
            $this->setBrowser(self::BROWSER_CHROME);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is iPhone or not (last updated 1.7)
     * @return boolean True if the browser is iPhone otherwise false
     */
    protected function checkBrowseriPhone()
    {
        if (stripos($this->_agent, 'iPhone') !== false) {
            $this->setVersion(self::VERSION_UNKNOWN);
            $this->setBrowser(self::BROWSER_IPHONE);
            $this->getSafariVersionOnIos();
            $this->getChromeVersionOnIos();
            $this->checkForFacebookIos();
            $this->setMobile(true);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is iPad or not (last updated 1.7)
     * @return boolean True if the browser is iPad otherwise false
     */
    protected function checkBrowseriPad()
    {
        if (stripos($this->_agent, 'iPad') !== false) {
            $this->setVersion(self::VERSION_UNKNOWN);
            $this->setBrowser(self::BROWSER_IPAD);
            $this->getSafariVersionOnIos();
            $this->getChromeVersionOnIos();
            $this->checkForFacebookIos();
            $this->setTablet(true);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is iPod or not (last updated 1.7)
     * @return boolean True if the browser is iPod otherwise false
     */
    protected function checkBrowseriPod()
    {
        if (stripos($this->_agent, 'iPod') !== false) {
            $this->setVersion(self::VERSION_UNKNOWN);
            $this->setBrowser(self::BROWSER_IPOD);
            $this->getSafariVersionOnIos();
            $this->getChromeVersionOnIos();
            $this->checkForFacebookIos();
            $this->setMobile(true);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Android or not (last updated 1.7)
     * @return boolean True if the browser is Android otherwise false
     */
    protected function checkBrowserAndroid()
    {
        if (stripos($this->_agent, 'Android') !== false) {
            $aresult = explode(' ', stristr($this->_agent, 'Android'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
            } else {
                $this->setVersion(self::VERSION_UNKNOWN);
            }
            if (stripos($this->_agent, 'Mobile') !== false) {
                $this->setMobile(true);
            } else {
                $this->setTablet(true);
            }
            $this->setBrowser(self::BROWSER_ANDROID);
            return true;
        }
        return false;
    }

    /**
     * Determine if the browser is Vivaldi
     * @return boolean True if the browser is Vivaldi otherwise false
     */
    protected function checkBrowserVivaldi()
    {
        if (stripos($this->_agent, 'Vivaldi') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'Vivaldi'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
                $this->setBrowser(self::BROWSER_VIVALDI);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is Yandex
     * @return boolean True if the browser is Yandex otherwise false
     */
    protected function checkBrowserYandex()
    {
        if (stripos($this->_agent, 'YaBrowser') !== false) {
            $aresult = explode('/', stristr($this->_agent, 'YaBrowser'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
                $this->setBrowser(self::BROWSER_YANDEX);

                if (stripos($this->_agent, 'iPad') !== false) {
                    $this->setTablet(true);
                } elseif (stripos($this->_agent, 'Mobile') !== false) {
                    $this->setMobile(true);
                } elseif (stripos($this->_agent, 'Android') !== false) {
                    $this->setTablet(true);
                }

                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the browser is a PlayStation
     * @return boolean True if the browser is PlayStation otherwise false
     */
    protected function checkBrowserPlayStation()
    {
        if (stripos($this->_agent, 'PlayStation ') !== false) {
            $aresult = explode(' ', stristr($this->_agent, 'PlayStation '));
            $this->setBrowser(self::BROWSER_PLAYSTATION);
            if (isset($aresult[0])) {
                $aversion = explode(')', $aresult[2]);
                $this->setVersion($aversion[0]);
                if (stripos($this->_agent, 'Portable)') !== false || stripos($this->_agent, 'Vita') !== false) {
                    $this->setMobile(true);
                }
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if the browser is Wget or not (last updated 1.7)
     * @return boolean True if the browser is Wget otherwise false
     */
    protected function checkBrowserWget()
    {
        if (preg_match("!^Wget/([^ ]+)!i", $this->_agent, $aresult)) {
            $this->setVersion($aresult[1]);
            $this->setBrowser(self::BROWSER_WGET);
            return true;
        }
        return false;
    }
    /**
     * Determine if the browser is cURL or not (last updated 1.7)
     * @return boolean True if the browser is cURL otherwise false
     */
    protected function checkBrowserCurl()
    {
        if (strpos($this->_agent, 'curl') === 0) {
            $aresult = explode('/', stristr($this->_agent, 'curl'));
            if (isset($aresult[1])) {
                $aversion = explode(' ', $aresult[1]);
                $this->setVersion($aversion[0]);
                $this->setBrowser(self::BROWSER_CURL);
                return true;
            }
        }
        return false;
    }

    /**
     * Determine the user's platform (last updated 2.0)
     */
    protected function checkPlatform()
    {
        if (stripos($this->_agent, 'windows') !== false) {
            $this->_platform = self::PLATFORM_WINDOWS;
        } else if (stripos($this->_agent, 'iPad') !== false) {
            $this->_platform = self::PLATFORM_IPAD;
        } else if (stripos($this->_agent, 'iPod') !== false) {
            $this->_platform = self::PLATFORM_IPOD;
        } else if (stripos($this->_agent, 'iPhone') !== false) {
            $this->_platform = self::PLATFORM_IPHONE;
        } elseif (stripos($this->_agent, 'mac') !== false) {
            $this->_platform = self::PLATFORM_APPLE;
        } elseif (stripos($this->_agent, 'android') !== false) {
            $this->_platform = self::PLATFORM_ANDROID;
        } elseif (stripos($this->_agent, 'Silk') !== false) {
            $this->_platform = self::PLATFORM_FIRE_OS;
        } elseif (stripos($this->_agent, 'linux') !== false && stripos($this->_agent, 'SMART-TV') !== false) {
            $this->_platform = self::PLATFORM_LINUX . '/' . self::PLATFORM_SMART_TV;
        } elseif (stripos($this->_agent, 'linux') !== false) {
            $this->_platform = self::PLATFORM_LINUX;
        } else if (stripos($this->_agent, 'Nokia') !== false) {
            $this->_platform = self::PLATFORM_NOKIA;
        } else if (stripos($this->_agent, 'BlackBerry') !== false) {
            $this->_platform = self::PLATFORM_BLACKBERRY;
        } elseif (stripos($this->_agent, 'FreeBSD') !== false) {
            $this->_platform = self::PLATFORM_FREEBSD;
        } elseif (stripos($this->_agent, 'OpenBSD') !== false) {
            $this->_platform = self::PLATFORM_OPENBSD;
        } elseif (stripos($this->_agent, 'NetBSD') !== false) {
            $this->_platform = self::PLATFORM_NETBSD;
        } elseif (stripos($this->_agent, 'OpenSolaris') !== false) {
            $this->_platform = self::PLATFORM_OPENSOLARIS;
        } elseif (stripos($this->_agent, 'SunOS') !== false) {
            $this->_platform = self::PLATFORM_SUNOS;
        } elseif (stripos($this->_agent, 'OS\/2') !== false) {
            $this->_platform = self::PLATFORM_OS2;
        } elseif (stripos($this->_agent, 'BeOS') !== false) {
            $this->_platform = self::PLATFORM_BEOS;
        } elseif (stripos($this->_agent, 'win') !== false) {
            $this->_platform = self::PLATFORM_WINDOWS;
        } elseif (stripos($this->_agent, 'Playstation') !== false) {
            $this->_platform = self::PLATFORM_PLAYSTATION;
        } elseif (stripos($this->_agent, 'Roku') !== false) {
            $this->_platform = self::PLATFORM_ROKU;
        } elseif (stripos($this->_agent, 'iOS') !== false) {
            $this->_platform = self::PLATFORM_IPHONE . '/' . self::PLATFORM_IPAD;
        } elseif (stripos($this->_agent, 'tvOS') !== false) {
            $this->_platform = self::PLATFORM_APPLE_TV;
        } elseif (stripos($this->_agent, 'curl') !== false) {
            $this->_platform = self::PLATFORM_TERMINAL;
        } elseif (stripos($this->_agent, 'CrOS') !== false) {
            $this->_platform = self::PLATFORM_CHROME_OS;
        } elseif (stripos($this->_agent, 'okhttp') !== false) {
            $this->_platform = self::PLATFORM_JAVA_ANDROID;
        } elseif (stripos($this->_agent, 'PostmanRuntime') !== false) {
            $this->_platform = self::PLATFORM_POSTMAN;
        } elseif (stripos($this->_agent, 'Iframely') !== false) {
            $this->_platform = self::PLATFORM_I_FRAME;
        }
    }

    /**
     * Determine the user's operating system (last updated 2.0)
     */
    protected function checkOS()
    {
        $os_array   =   array(
            //nokia X
            '/nokia_x/i' => self::OPERATING_SYSTEM_NOKIA_X,
            //AliOS
            '/aliyunos|yunos/i' => self::OPERATING_SYSTEM_ALIOS,
            //android
            '/android (.*)adt/i' =>  self::OPERATING_SYSTEM_ANDROID_TV,
            '/android 9\.0/i' =>  self::OPERATING_SYSTEM_ANDROID_9_0,
            '/android 8\.1/i' =>  self::OPERATING_SYSTEM_ANDROID_8_1,
            '/android 8\.0/i' =>  self::OPERATING_SYSTEM_ANDROID_8_0,
            '/android 7\.1/i' =>  self::OPERATING_SYSTEM_ANDROID_7_1,
            '/android 7\.0/i' =>  self::OPERATING_SYSTEM_ANDROID_7_0,
            '/android 6\.0/i' =>  self::OPERATING_SYSTEM_ANDROID_6_0,
            '/android 5\.1/i' =>  self::OPERATING_SYSTEM_ANDROID_5_1,
            '/android 5\.0/i' =>  self::OPERATING_SYSTEM_ANDROID_5_0,
            '/android (4\.4|kitkat)/i' =>  self::OPERATING_SYSTEM_ANDROID_4_4,
            '/android 4\.3/i' =>  self::OPERATING_SYSTEM_ANDROID_4_3,
            '/android 4\.2/i' =>  self::OPERATING_SYSTEM_ANDROID_4_2,
            '/android (4\.1|jelly bean)/i' =>  self::OPERATING_SYSTEM_ANDROID_4_1,
            '/android (4\.0|ice cream sandwich)/i' =>  self::OPERATING_SYSTEM_ANDROID_4,
            '/android (3|honeycomb)/i' =>  self::OPERATING_SYSTEM_ANDROID_3,
            '/android (2\.3|gingerbread)/i' =>  self::OPERATING_SYSTEM_ANDROID_2_3,
            '/android (2\.2|froyo)/i' =>  self::OPERATING_SYSTEM_ANDROID_2_2,
            '/android (2\.(0|1)|eclair)/i' =>  self::OPERATING_SYSTEM_ANDROID_2,
            '/android (1\.6|donut)/i' =>  self::OPERATING_SYSTEM_ANDROID_1_6,
            '/android (1\.5|cupcake)/i' =>  self::OPERATING_SYSTEM_ANDROID_1_5,
            '/android 1\.0/i' =>  self::OPERATING_SYSTEM_ANDROID_1,
            '/android/i' =>  self::OPERATING_SYSTEM_ANDROID,
            //consoles
            '/playstation (3|portable)/i' =>  self::OPERATING_SYSTEM_XMB,
            '/playstation vita/i' =>  self::OPERATING_SYSTEM_LIVE_AREA,
            '/playstation 4/i' =>  self::OPERATING_SYSTEM_ORBIS,
            '/nintendo 3ds/i' =>  self::OPERATING_SYSTEM_NINTENDO_3DS,
            '/nintendo ds/i' =>  self::OPERATING_SYSTEM_NINTENDO_DS,
            '/nintendo wiiu/i' =>  self::OPERATING_SYSTEM_NINTENDO_WIIU,
            '/nintendo wii/i' =>  self::OPERATING_SYSTEM_NINTENDO_WII,
            '/xbox|(windows NT 6\.1; trident)|xbmc/i' =>  self::OPERATING_SYSTEM_XBOX,
            //windows
            '/windows nt (6\.4|10.0)/i' => self::OPERATING_SYSTEM_WINDOWS_10,
            '/windows nt 6\.3/i' =>  self::OPERATING_SYSTEM_WINDOWS_8_1,
            '/windows nt 6\.2; arm; trident/i' =>  self::OPERATING_SYSTEM_WINDOWS_RT,
            '/windows nt 6\.2/i' =>  self::OPERATING_SYSTEM_WINDOWS_8,
            '/windows nt 6\.1/i' =>  self::OPERATING_SYSTEM_WINDOWS_7,
            '/windows nt 6\.0/i' =>  self::OPERATING_SYSTEM_WINDOWS_VISTA,
            '/windows nt 5\.2/i' =>  self::OPERATING_SYSTEM_WINDOWS_SERVER,
            '/windows (nt 5\.1|xp)/i' =>  self::OPERATING_SYSTEM_WINDOWS_XP,
            '/windows (nt 5\.0|2000)/i' =>  self::OPERATING_SYSTEM_WINDOWS_2000,
            '/windows nt 4/i' =>  self::OPERATING_SYSTEM_WINDOWS_NT,
            '/windows ce/i' =>  self::OPERATING_SYSTEM_WINDOWS_CE,
            '/win98|windows[ _]98/i' =>  self::OPERATING_SYSTEM_WINDOWS_98,
            '/win95|windows[ _]95/i' =>  self::OPERATING_SYSTEM_WINDOWS_95,
            '/windows me|win 9x/i' =>  self::OPERATING_SYSTEM_WINDOWS_ME,
            '/win16|windows[ _]3/i' =>  self::OPERATING_SYSTEM_WINDOWS_3,
            '/windows phone os 7/i' =>  self::OPERATING_SYSTEM_WINDOWS_PHONE_7,
            '/windows phone 10/i' =>  self::OPERATING_SYSTEM_WINDOWS_PHONE_10,
            '/windows phone 8.1/i' =>  self::OPERATING_SYSTEM_WINDOWS_PHONE_8_1,
            '/windows phone 8/i' =>  self::OPERATING_SYSTEM_WINDOWS_PHONE_8,
            '/windows (mobile|phone)/i' =>  self::OPERATING_SYSTEM_WINDOWS_MOBILE,
            '/windows iot 10/i' => self::OPERATING_SYSTEM_WINDOWS_10_IOT,
            '/win/i' =>  self::OPERATING_SYSTEM_WINDOWS,
            //IOS
            '/os 12/i' =>  self::OPERATING_SYSTEM_IOS_12,
            '/os 11/i' =>  self::OPERATING_SYSTEM_IOS_11,
            '/os 10/i' =>  self::OPERATING_SYSTEM_IOS_10,
            '/os 9/i' =>  self::OPERATING_SYSTEM_IOS_9,
            '/os 8/i' =>  self::OPERATING_SYSTEM_IOS_8,
            '/os 7/i' =>  self::OPERATING_SYSTEM_IOS_7,
            '/os 6/i' =>  self::OPERATING_SYSTEM_IOS_6,
            '/os 5/i' =>  self::OPERATING_SYSTEM_IOS_5,
            '/os 4/i' =>  self::OPERATING_SYSTEM_IOS_4,
            '/iphone|ipod|ipad|(x11;.*;.linux)/i' =>  self::OPERATING_SYSTEM_IOS,
            //mac
            '/mac os x 10[\._]14/i' =>  self::OPERATING_SYSTEM_MAC_X_10_14,
            '/mac os x 10[\._]13/i' =>  self::OPERATING_SYSTEM_MAC_X_10_13,
            '/mac os x 10[\._]12/i' =>  self::OPERATING_SYSTEM_MAC_X_10_12,
            '/mac os x 10[\._]11/i' =>  self::OPERATING_SYSTEM_MAC_X_10_11,
            '/mac os x 10[\._]10/i' =>  self::OPERATING_SYSTEM_MAC_X_10_10,
            '/mac os x 10[\._]9/i' =>  self::OPERATING_SYSTEM_MAC_X_10_9,
            '/mac os x 10[\._]8/i' =>  self::OPERATING_SYSTEM_MAC_X_10_8,
            '/mac os x 10[\._]7/i' =>  self::OPERATING_SYSTEM_MAC_X_10_7,
            '/mac os x 10[\._]6/i' =>  self::OPERATING_SYSTEM_MAC_X_10_6,
            '/mac os x 10[\._]5/i' =>  self::OPERATING_SYSTEM_MAC_X_10_5,
            '/mac os x 10[\._]4/i' =>  self::OPERATING_SYSTEM_MAC_X_10_4,
            '/mac os x 10[\._]3/i' =>  self::OPERATING_SYSTEM_MAC_X_10_3,
            '/macintosh|mac os x/i' =>  self::OPERATING_SYSTEM_MAC_X,
            '/macos/i' =>  self::OPERATING_SYSTEM_MAC,
            //Linux
            '/elementary/i' => self::OPERATING_SYSTEM_LINUX_ELEMENTARY_OS,
            '/xubuntu/i' => self::OPERATING_SYSTEM_LINUX_XUBUNTU,
            '/pclinuxosx/i' =>  self::OPERATING_SYSTEM_LINUX_PCLINUX,
            '/vectorlinux/i' =>  self::OPERATING_SYSTEM_LINUX_VECTOR,
            '/ubuntu/i' =>  self::OPERATING_SYSTEM_LINUX_UBUNTU,
            '/suse/i' =>  self::OPERATING_SYSTEM_LINUX_SUSE,
            '/slackware/i' =>  self::OPERATING_SYSTEM_LINUX_SLACKWARE,
            '/red hat modified/i' =>  self::OPERATING_SYSTEM_LINUX_REDHAT,
            '/mint/i' =>  self::OPERATING_SYSTEM_LINUX_MINT,
            '/mandriva/i' =>  self::OPERATING_SYSTEM_LINUX_MANDRIVA,
            '/mageia/i' =>  self::OPERATING_SYSTEM_LINUX_MAGEIA,
            '/maemo/i' =>  self::OPERATING_SYSTEM_LINUX_MAEMO,
            '/linspire/i' =>  self::OPERATING_SYSTEM_LINUX_LINSPIRE,
            '/knoppix/i' =>  self::OPERATING_SYSTEM_LINUX_KNOPPIX,
            '/kanotix/i' =>  self::OPERATING_SYSTEM_LINUX_KANOTIX,
            '/gentoo/i' =>  self::OPERATING_SYSTEM_LINUX_GENTOO,
            '/fedora/i' =>  self::OPERATING_SYSTEM_LINUX_FEDORA,
            '/debian/i' =>  self::OPERATING_SYSTEM_LINUX_DEBIAN,
            '/centos/i' =>  self::OPERATING_SYSTEM_LINUX_CENTOS,
            '/arch linux/i' =>  self::OPERATING_SYSTEM_LINUX_ARCH,
            //other
            '/rim tablet os 1/i' =>  self::OPERATING_SYSTEM_BLACKBERRY_TABLET_OS_1,
            '/rim tablet os 2/i' =>  self::OPERATING_SYSTEM_BLACKBERRY_TABLET_OS_2,
            '/blackberry/i' =>  self::OPERATING_SYSTEM_BLACKBERRY,
            '/freebsd/i' =>  self::OPERATING_SYSTEM_FREEBSD,
            '/openbsd/i' =>  self::OPERATING_SYSTEM_OPENBSD,
            '/netbsd/i' =>  self::OPERATING_SYSTEM_NETBSD,
            '/opensolaris/i' =>  self::OPERATING_SYSTEM_OPENSOLARIS,
            '/sunos/i' =>  self::OPERATING_SYSTEM_SUNOS,
            '/warp/i' =>  self::OPERATING_SYSTEM_OS2_WARP,
            '/os\/2/i' =>  self::OPERATING_SYSTEM_OS2,
            '/heiku/i' =>  self::OPERATING_SYSTEM_HAIKU,
            '/beos/i' =>  self::OPERATING_SYSTEM_BEOS,
            '/firefox\//i' =>  self::OPERATING_SYSTEM_FIREFOX,
            '/aix/i' =>  self::OPERATING_SYSTEM_AIX,
            '/amiga/i' =>  self::OPERATING_SYSTEM_AMIGA,
            '/aros/i' =>  self::OPERATING_SYSTEM_AROS,
            '/bada/i' =>  self::OPERATING_SYSTEM_BADA,
            '/brew/i' =>  self::OPERATING_SYSTEM_BREW,
            '/cros/i' =>  self::OPERATING_SYSTEM_CROME,
            '/danger hiptop/i' =>  self::OPERATING_SYSTEM_DANGER_HIPTOP,
            '/dragonfly/i' =>  self::OPERATING_SYSTEM_DRAGONFLY_BSD,
            '/gnu/i' =>  self::OPERATING_SYSTEM_GNU,
            '/hp-ux/i' =>  self::OPERATING_SYSTEM_HP,
            '/inferno/i' =>  self::OPERATING_SYSTEM_INFERNO,
            '/irix/i' =>  self::OPERATING_SYSTEM_IRIX,
            '/jolicloud/i' =>  self::OPERATING_SYSTEM_JOLI,
            '/java/i' =>  self::OPERATING_SYSTEM_JVM,
            '/meego/i' =>  self::OPERATING_SYSTEM_MEEGO,
            '/minix 3/i' =>  self::OPERATING_SYSTEM_MINIX_3,
            '/morphos/i' =>  self::OPERATING_SYSTEM_MORPHOS,
            '/webtv/i' =>  self::OPERATING_SYSTEM_MSN_TV,
            '/appletv/i' => self::OPERATING_SYSTEM_APPLE_TV,
            '/openvms/i' =>  self::OPERATING_SYSTEM_OPENVMS,
            '/palm os/i' =>  self::OPERATING_SYSTEM_PALM,
            '/qnx x86pc/i' =>  self::OPERATING_SYSTEM_QNX,
            '/risc os|risk os/i' =>  self::OPERATING_SYSTEM_RISK,
            '/sailfish/i' =>  self::OPERATING_SYSTEM_SAILFISH,
            '/syllable/i' =>  self::OPERATING_SYSTEM_SYLLABLE,
            '/symbos/i' =>  self::OPERATING_SYSTEM_SYMBIAN,
            '/tizen 4/i' =>  self::OPERATING_SYSTEM_TIZEN_4,
            '/tizen 3/i' =>  self::OPERATING_SYSTEM_TIZEN_3,
            '/tizen 2/i' =>  self::OPERATING_SYSTEM_TIZEN_2,
            '/tizen/i' =>  self::OPERATING_SYSTEM_TIZEN_1,
            '/ubuntu; (mobile|tablet)/i' =>  self::OPERATING_SYSTEM_UBUNTU_TOUCH,
            '/webos/i' =>  self::OPERATING_SYSTEM_WEBOS,
            '/luneos/i' => self::OPERATING_SYSTEM_LUNEOS,
            '/nokia/i' =>  self::OPERATING_SYSTEM_NOKIA,
            '/trueos/i' => self::OPERATING_SYSTEM_TRUEOS,
            '/kolibri/i' => self::OPERATING_SYSTEM_KOLIBRIOS,
            '/cos/i' => self::OPERATING_SYSTEM_COS,

            '/linux/i' =>  self::OPERATING_SYSTEM_LINUX,
        );
        foreach($os_array as $regex => $value){ 
            if(preg_match($regex, $this->_agent)){
                $this->_os = $value;
                return;
            }
        } 
    }

    /**
     * The name of the operating system.  All return types are from the class contants
     * @return string Name of the operating system
     */
    public function getOS()
    {
        return $this->_os;
    }

    /**
     * Set the name of the operating system
     * @param string $os The name of the operating system
     */
    public function setOS($os)
    {
        $this->_os = $os;
    }
}
