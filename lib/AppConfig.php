<?php

/**
 *
 * @author Pawel Rojek <pawel at pawelrojek.com>
 * @author Ian Reinhart Geiser <igeiser at devonit.com>
 *
 * This file is licensed under the Affero General Public License version 3 or later.
 *
 **/

namespace OCA\Drawio;

use OCP\IConfig;
use Psr\Log\LoggerInterface;


class AppConfig {

    private $predefDrawioUrl = "https://embed.diagrams.net";
    private $predefOfflineMode = "no";
    private $predefTheme = "kennedy"; //kennedy, min (=minimal), atlas, simple
    private $predefLang = "auto";
    private $predefAutosave = "yes";
    private $predefLibraries = "no";
    private $predefDarkMode = "auto";
    private $predefPreviews = "yes";

    private $appName;

    private $config;

    private $logger;

    // The config keys
    private $_drawioUrl = "DrawioUrl";
    private $_offlinemode = "DrawioOffline";
    private $_theme = "DrawioTheme";
    private $_lang = "DrawioLang";
    private $_autosave = "DrawioAutosave";
    private $_libraries = "DrawioLibraries";
    private $_darkmode = "DrawioDarkMode";
    private $_previews = "DrawioPreviews";
    private $_drawioConfig = "DrawioConfig";

    public function __construct($AppName,LoggerInterface $logger)
    {
        $this->appName = $AppName;

        $this->config = \OC::$server->getConfig();
        $this->logger = $logger;
    }

    public function SetDrawioUrl($drawio)
    {
        $drawio = strtolower(trim($drawio));
        if (strlen($drawio) > 0 && !preg_match("/^https?:\/\//i", $drawio)) $drawio = "http://" . $drawio;
        $this->logger->info("SetDrawioUrl: " . $drawio, array("app" => $this->appName));
        $this->config->setAppValue($this->appName, $this->_drawioUrl, $drawio);
    }

    public function GetDrawioUrl()
    {
        $val = $this->config->getAppValue($this->appName, $this->_drawioUrl);
        if (empty($val)) $val = $this->predefDrawioUrl;
        //default URL changed from draw.io to embed.diagrams.net #118
        if (in_array(strtolower($val), array("https://draw.io", "https://www.draw.io", "http://draw.io", "http://www.draw.io") )) $val = $this->predefDrawioUrl;
        return $val;
    }


    public function SetOfflineMode($offlinemode)
    {
        $offlinemode = (string)$offlinemode;
        $this->logger->info("SetOfflineMode: " . $offlinemode, array("app" => $this->appName));
        $this->config->setAppValue($this->appName, $this->_offlinemode, $offlinemode);
    }

    public function GetOfflineMode()
    {
        $val = $this->config->getAppValue($this->appName, $this->_offlinemode);
        if (empty($val)) $val = $this->predefOfflineMode;
        return $val;
    }

    public function SetTheme($theme)
    {
        $this->logger->info("SetTheme: " . $theme, array("app" => $this->appName));
        $this->config->setAppValue($this->appName, $this->_theme, $theme);
    }

    public function GetTheme()
    {
        $val = $this->config->getAppValue($this->appName, $this->_theme);
        if (empty($val)) $val = $this->predefTheme;
        return $val;
    }

    public function SetLang($lang)
    {
        $this->logger->info("SetLang: " . $lang, array("app" => $this->appName));
        $this->config->setAppValue($this->appName, $this->_lang, $lang);
    }

    public function GetLang()
    {
        $val = $this->config->getAppValue($this->appName, $this->_lang);
        if (empty($val)) $val = $this->predefLang;
        return $val;
    }

    public function SetAutosave($autosave)
    {
        $this->logger->info("SetAutosave: " . $autosave, array("app" => $this->appName));
        $this->config->setAppValue($this->appName, $this->_autosave, $autosave);
    }

    public function GetAutosave()
    {
        $val = $this->config->getAppValue($this->appName, $this->_autosave);
        if (empty($val)) $val = $this->predefAutosave;
        return $val;
    }

    public function SetLibraries($libraries)
    {
        $this->logger->info("SetLibraries: " . $libraries, array("app" => $this->appName));
        $this->config->setAppValue($this->appName, $this->_libraries, $libraries);
    }

    public function GetLibraries()
    {
        $val = $this->config->getAppValue($this->appName, $this->_libraries);
        if (empty($val)) $val = $this->predefLibraries;
        return $val;
    }

    public function SetDarkMode($darkmode)
    {
        $this->logger->info("SetDarkMode: " . $darkmode, array("app" => $this->appName));
        $this->config->setAppValue($this->appName, $this->_darkmode, $darkmode);
    }
    
    public function GetDarkMode()
    {
        $val = $this->config->getAppValue($this->appName, $this->_darkmode);
        if (empty($val))
        {
            if ($this->GetTheme() == "dark")
            {
                $val = "yes";
            }
            else
            {
                $val = $this->predefDarkMode;
            }
        }
        return $val;
    }

    public function SetPreviews($previews)
    {
        $this->logger->info("SetPreviews: " . $previews, array("app" => $this->appName));
        $this->config->setAppValue($this->appName, $this->_previews, $previews);
    }

    public function GetPreviews()
    {
        $val = $this->config->getAppValue($this->appName, $this->_previews);
        if (empty($val)) $val = $this->predefPreviews;
        return $val;
    }
    
    public function SetDrawioConfig($drawioConfig)
    {
        $this->logger->info("SetDrawioConfig: " . $drawioConfig, array("app" => $this->appName));
        // Check if the json is valid
        $val = json_decode($drawioConfig);
        $this->config->setAppValue($this->appName, $this->_drawioConfig, empty($val)? "" : $drawioConfig);
    }

    public function GetDrawioConfig()
    {
        $val = $this->config->getAppValue($this->appName, $this->_drawioConfig);
        
        if (empty(json_decode($val)))
        {
            return "{}";
        }
        else
        {
            return $val;
        }
    }

    public function GetAppName()
    {
        return $this->appName;
    }

     /**
     * Additional data about formats
     *
     * @var array
     */
    public $formats = [
            "drawio" => [ "mime" => "application/x-drawio", "type" => "text" ],
            "dwb" => [ "mime" => "application/x-drawio-wb", "type" => "text" ]
        ];

}
