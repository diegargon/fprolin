<?php
/*
*  @copyright Copyright @ 2022 - 2023 Diego Garcia
*/

class Frontend
{

    private array $cfg;
    private array $lng;

    public function __construct(array $cfg, array $lng)
    {
        $this->cfg = $cfg;
        $this->lng = $lng;
    }

    function showPage(array $data)
    {
        //pr_dbg($data);
        $head = '';
        $body = '';
        $footer = '';

        if (!empty($data['css']) && is_array($data['css'])) {
            foreach ($data['css'] as $css_data) {
                $head .= $this->cssFile($css_data);
            }
        }
        if (!empty($data['script_link']) && is_array($data['script_link'])) {
            foreach ($data['script_link'] as $script) {
                $head .= $this->addScriptLink($script);
            }
        }
        if (!empty($data['script_file']) && is_array($data['script_file'])) {
            foreach ($data['script_file'] as $script) {
                $head .= $this->addScriptFile($script);
            }
        }


        if (!empty($data['load_tpl']) and is_array($data['load_tpl']) && count($data['load_tpl']) > 0) {

            foreach ($data['load_tpl'] as $tpl_data) {
                //TODO resolv multiple father/childs levels
                // pr_dbg($tpl_data);
                if (!empty($tpl_data['tpl_father']) && !empty($tpl_data['tpl_spot'])) {
                    if (isset($data['load_tpl'][$tpl_data['tpl_father']][$tpl_data['tpl_spot']])) {
                        $data['load_tpl'][$tpl_data['tpl_father']][$tpl_data['tpl_spot']] .= $this->getTpl($tpl_data['tpl'], $tpl_data);
                    } else {
                        $data['load_tpl'][$tpl_data['tpl_father']][$tpl_data['tpl_spot']] = $this->getTpl($tpl_data['tpl'], $tpl_data);
                    }
                }
            }
            //pr_dbg($data);
            foreach ($data['load_tpl'] as $tpl_data) {
                if (!empty($tpl_data['tpl']) && empty($tpl_data['tpl_father']) && empty($tpl_data['tpl_spot'])) {
                    //  pr_dbg($tpl_data);
                    $body .= $this->getTpl($tpl_data['tpl'], $tpl_data);
                }
            }
        }

        echo $this->main_struct($head, $body, $footer);
    }

    function getTpl(string $tpl, array $tdata = [])
    {
        $lng = $this->lng;
        $cfg = $this->cfg;
        $tpl_file = '';

        if (!empty($tdata['plugin'])) {
            $tpl_file .= 'plugins/' . $tdata['plugin'] . '/';
        }

        $tpl_file .= 'tpl/' . $this->cfg['theme'] . '/' . $tpl . '.tpl.php';

        if (!file_exists($tpl_file)) {
            echo "error gettpl";
            return false;
        }

        ob_start();
        include($tpl_file);

        return ob_get_clean();
    }

    function cssFile(array $css_data)
    {
        $css_file = 'plugins/' . $css_data['plugin'] . '/tpl/' . $this->cfg['theme'] . '/css/' . $css_data['name'] . '.css';
        if (!file_exists($css_file)) {
            return false;
        }
        $css_file .= '?nocache=' . time(); //TODO: To Remove: avoid cache css while dev
        $css_file = '<link rel="stylesheet" href="' . $css_file . '">' . "\n";

        return $css_file;
    }

    function addScriptLink(string $script)
    {
        return '<script src="' . $script . '"></script>' . "\n";
    }

    function addScriptFile(array $script)
    {
        $script_file = 'plugins/' . $script['plugin'] . '/tpl/' . $this->cfg['theme'] . '/js/' . $script['name'] . '.js';
        return '<script src="' . $script_file . '"></script>' . "\n";
    }


    function main_struct(string $head, string $body, string $footer)
    {
        return '<!DOCTYPE html>' . "\n" .
            '<html lang="' . $this->cfg['lang'] . '">' . "\n" .
            '<head>' . "\n" .
            '    <meta charset="' . $this->cfg['charset'] . '" />' . "\n" .
            '    <meta name="viewport" content="width=device-width, initial-scale=1.0" />' . "\n" .
            '    <link rel="shortcut icon" href="favicon.ico" />' . "\n" .
            '    <meta name="referrer" content="never">' . "\n" .
            '    <title>' . $this->cfg['web_title'] . '</title>' . "\n" .
            '' .     $head . '' . "\n" .
            '</head>' . "\n" .
            '<body>' . "\n" .
            '' .     $body . '' . "\n" .
            '<footer>' . $footer . '</footer>' . "\n" .
            '</body>' . "\n" .
            '</html>' . "\n";
    }
}
