<?php
/**
 * RIOT. CORP BEST CORP.
 */

if(!defined("IN_MYBB"))
{
	die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

$plugins->add_hook("postbit", "evefitting_postbit");

function evefitting_info()
{
	return array(
		"name"			=> "EVE Fittings",
		"description"	=> "Attach a EVE XML fittings to a post!<br /><span style=\"color:red;font-weight:bold\">YOU MUST populate your own ".TABLE_PREFIX."invTypes table from CCP Static DataDump!</span>",
		"website"		=> "",
		"author"		=> "Tim Noise",
		"authorsite"	=> "http://drkns.net",
		"version"		=> "0.1",
		"guid" 			=> "",
		"compatibility" => "16*"
	);
}

function evefitting_install() {
    global $db;

    $icon = "R0lGODlhEAAQAPe4AI6Ojvf39/Hx8f////39/ZKSkujo6Pz8/GNjY3ByclBSUVJQUU1NT1ZZWJWV"
        ."lZmZmSksLICAgObm5y4yOSkqKJSUlERBRMvLy0VGR2NrbZCRkerr6z1ERoKKi+D29ZOYnJuYmFtk"
        ."Z0BHSPDw8FJPTJaWlvT9/ZOkpCs3PaSkpJqinsvT1NH6+FhdYo+UlYqRkhcWF3J4ed/h4jk6ObO4"
        ."ut3d3drv76769bKurL3Cxb3BwYGBge3//+///+z9/WdoZ66vsGZjYkRLS3R5em5ubmBlZWlsb7i4"
        ."uGhpaLW0sTQ/Q9HS0t719nZ4dsXHx9XU0sDAwZ2dncn58l1iYo2Ni9Dl5G1xcb/EyIqFg5OUkj0/"
        ."P2JjZDc4OR4fHHBzcjc5OMfb26uxtFVaWHV4en+BgE5NTCwqKeHh4eDg4JiYmHJ1c0pLS9ra2p2g"
        ."ond4eKGhoZmdoM3R0fz8/e3t7dHV2PP//3t+gbHY235+fcLc3nB3d4KZmJOdnNLPzjQvL3+Bgqur"
        ."q09RTdjV1dT6+naFhLPy84CChMv4+OH29+Hf3Sw0NklFRMrO0L3Exefn501UVzc7Puvr6/r//+X+"
        ."/p6joz1ITT4+Pg4ODyEhIrS6vNXV1VFZWZ2Xk7Ozs4GHiK6sq7a2tqKtrjs/PxwfHUVIS1hdX1BV"
        ."WOPk5MT6+Lq3t5ecnM///5GVl4mJiVdgYLK8u2FiZL/LyywvMHt4cykoJGFoaq+vr0dIR////wAA"
        ."AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA"
        ."AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA"
        ."AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA"
        ."AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH/C1hNUCBEYXRhWE1QPD94"
        ."cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1w"
        ."bWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS4w"
        ."LWMwNjAgNjEuMTM0Nzc3LCAyMDEwLzAyLzEyLTE3OjMyOjAwICAgICAgICAiPiA8cmRmOlJERiB4"
        ."bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8"
        ."cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5j"
        ."b20veGFwLzEuMC8iIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0v"
        ."IiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNl"
        ."UmVmIyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ1M1IFdpbmRvd3MiIHhtcE1N"
        ."Okluc3RhbmNlSUQ9InhtcC5paWQ6MDI3MEYwRjc4QzJDMTFFMTlEN0I4NUIwNzMxMDRENUQiIHht"
        ."cE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6MDI3MEYwRjg4QzJDMTFFMTlEN0I4NUIwNzMxMDRENUQi"
        ."PiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDowMjcwRjBGNThD"
        ."MkMxMUUxOUQ3Qjg1QjA3MzEwNEQ1RCIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDowMjcwRjBG"
        ."NjhDMkMxMUUxOUQ3Qjg1QjA3MzEwNEQ1RCIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRG"
        ."PiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PgH//v38+/r5+Pf29fTz8vHw7+7t7Ovq"
        ."6ejn5uXk4+Lh4N/e3dzb2tnY19bV1NPS0dDPzs3My8rJyMfGxcTDwsHAv769vLu6ubi3trW0s7Kx"
        ."sK+urayrqqmop6alpKOioaCfnp2cm5qZmJeWlZSTkpGQj46NjIuKiYiHhoWEg4KBgH9+fXx7enl4"
        ."d3Z1dHNycXBvbm1sa2ppaGdmZWRjYmFgX15dXFtaWVhXVlVUU1JRUE9OTUxLSklIR0ZFRENCQUA/"
        ."Pj08Ozo5ODc2NTQzMjEwLy4tLCsqKSgnJiUkIyIhIB8eHRwbGhkYFxYVFBMSERAPDg0MCwoJCAcG"
        ."BQQDAgEAACH5BAEAALgALAAAAAAQABAAAAj3AHEJxCWHhg0ek3r4MDGwIS4JWPYcQnWjECImVepI"
        ."GrghzBBXfGKBuZPnBKFVg3ANYBSDkicgqXCA0NAoiB4pLHAZyCAm0I9ZTxIlWeGF1iIVHlTKaEKC"
        ."Ex4/FhbQyQGHwYdQAk99ErTECoxLozDZudLGCCxccaj0qVFEkalSQkRwmaEGg6ZMtSCFcPHn1QtZ"
        ."j8oQQYAACq4OLSop2dQgS5dbhsi0cgAAUAAdE1BM+UJBy5oKY9y8iWLrDC5VpDhAMGNpy4MLcwII"
        ."EHBAIKsEohREAACgEwGHAp0g2VGgQIkjkYALHJEiDSg2jmorxxUAjYHf0wcGBAA7";

    $ih = fopen(MYBB_ROOT . 'images/attachtypes/16x16fitting.gif', 'w+');
    fwrite($ih, base64_decode($icon));
    fclose($ih);

    $db->insert_query('templates', array(
        'title' =>  'evefitting',
        'template' => '<fieldset>
            <legend><strong><a href="javascript:if(typeof CCPEVE !== \\\'undefined\\\') CCPEVE.showInfo({$eve_fit[\\\'ship_id\\\']})">{$eve_fit[\\\'ship\\\']}</a></strong> - <a href="javascript:if(typeof CCPEVE !== \\\'undefined\\\')CCPEVE.showFitting(\\\'{$eve_fit_dna}\\\')">{$eve_fit[\\\'name\\\']}</a></legend>
                <p>$eve_fit[description]</p>
                <p>
                    [Low Power Slots]<br />
                    {$eve_fit_low}
                </p>
                <p>
                    [Medium Power Slots]<br />
                    {$eve_fit_med}
                </p>
                <p>
                    [High Power Slots]<br />
                    {$eve_fit_hi}
                </p>
                <p>
                    [Rig Slots]<br />
                    {$eve_fit_rig}
                </p>
                    $eve_fit_subsystem
                </fieldset>',
        'sid' => '-2',
        'status' => '',
        'dateline' => '1335019954',
        'version' => '1600'
    ));

    $db->insert_query('templates', array(
        'title' =>  'evefitting_slot',
        'template' => '<a href="javascript:if(typeof CCPEVE !== \\\'undefined\\\') CCPEVE.showInfo($evefitting_slot_id)">{$evefitting_slot_name}</a><br />',
        'sid' => '-2',
        'status' => '',
        'dateline' => '1335019954',
        'version' => '1600'
    ));

    $db->insert_query('attachtypes', array(
        'name' => 'XML File',
        'mimetype' => 'application/xml',
        'extension' => 'xml',
        'maxsize' => '256',
        'icon' => 'images/attachtypes/16x16fitting.gif'
    ));
}

function evefitting_is_installed() {
    global $db;
    //if(!$db->table_exists('invTypes')) return false;

    $query = $db->simple_select('attachtypes', '*', "extension='xml'");
    if($db->num_rows($query) == 0) return false;

    $query = $db->simple_select('templates', '*', "title='evefitting' OR title='evefitting_slot'");
    if($db->num_rows($query) != 2) return false;

    if(!file_exists(MYBB_ROOT . 'images/attachtypes/16x16fitting.gif')) return false;

    return true;
}

function evefitting_uninstall() {
    global $db;
    unlink(MYBB_ROOT . 'images/attachtypes/16x16fitting.gif');

    $db->delete_query('attachtypes', "extension = 'xml'");
    $db->delete_query('templates', "title = 'evefitting'");
    $db->delete_query('templates', "title = 'evefitting_slot'");

    return true;
}


function evefitting_slot($slot) {
    global $templates;

    $t = '';
    foreach($slot as $s) {
        $evefitting_slot_name = $s['name'];
        $evefitting_slot_id = $s['id'];
        eval("\$t .= \"".$templates->get("evefitting_slot")."\";");
    }
    return $t;
}

function evefitting_dna_section($modules) {
    $mods = array();
    foreach($modules as $m) {
        if(!array_key_exists($m['id'], $mods)) {
            $mods[$m['id']] = 1;
        } else {
            $mods[$m['id']] += 1;
        }
    }
    $str = array();
    foreach($mods as $k => $v) {
        $str[] = $k . ';' . $v;
    }
    return implode(':', $str);
}

function evefitting_postbit($post) {
   global $gid, $mybb, $db, $templates, $attachcache, $lang, $eve_typeid_cache;//, $post;

    if(!$eve_typeid_cache) $eve_typeid_cache = array();

    foreach($attachcache as $k => $v) {
        foreach($v as $a) {
            if($a['pid'] != $post['pid']) continue;
            if($a['filetype'] != 'text/xml') continue;

            try {
                $xml = file_get_contents(MYBB_ROOT . 'uploads/' . $a['attachname']);
                $x = new SimpleXMLElement($xml);
            } catch (Exception $e) {
                continue;
            }

            foreach($x->fitting as $f) {
                $eve_fit = array(
                    'name' => (string)$f->attributes()->name,
                    'ship' => (string)$f->shipType->attributes()->value,
                    'description' => (string)$f->description->attributes()->value,
                    'low' => array(),
                    'med' => array(),
                    'hi'  => array(),
                    'rig' => array(),
                    'subsystem' => array()
                );

                if(!array_key_exists($eve_fit['ship'], $eve_typeid_cache)) {
                    $query = $db->simple_select('invTypes', '*', sprintf("typeName = '%s'", $db->escape_string($eve_fit['ship'])));
                    $eve_fit['ship_id'] =  $db->fetch_field($query, 'typeID');
                    $eve_typeid_cache[$eve_fit['ship']] = $eve_fit['ship_id'];
                } else {
                   $eve_fit['ship_id'] = $eve_typeid_cache[$eve_fit['ship']];
                }

                foreach($f->hardware as $h) {
                    $h =  $h->attributes();
                    $slot = explode(" ", $h->slot);
                    $name = (string)$h->type;
                    $eve_fit[$slot[0]][$slot[2]]['name'] = $name;

                    if(!array_key_exists($name, $eve_typeid_cache)) {
                        $query = $db->simple_select('invTypes', '*', sprintf("typeName = '%s'", $db->escape_string($name)));
                        $eve_fit[$slot[0]][$slot[2]]['id'] = $db->fetch_field($query, 'typeID');
                        $eve_typeid_cache[$name] = $eve_fit[$slot[0]][$slot[2]]['id'];
                    } else {
                        $eve_fit[$slot[0]][$slot[2]]['id'] = $eve_typeid_cache[$name];
                    }
                }

                //make some ship DNA :(
                $eve_fit_dna = $eve_fit['ship_id'];

                foreach($eve_fit['subsystem'] as $sub) {
                    $eve_fit_dna .= ':'. $sub['id'] .';1';
                }

                $eve_fit_dna = sprintf("%s:%s:%s:%s:%s:", $eve_fit_dna, evefitting_dna_section($eve_fit['low']), evefitting_dna_section($eve_fit['med']), evefitting_dna_section($eve_fit['hi']), evefitting_dna_section($eve_fit['rig']));

                $eve_fit_low = evefitting_slot($eve_fit['low']);
                $eve_fit_med = evefitting_slot($eve_fit['med']);
                $eve_fit_hi  = evefitting_slot($eve_fit['hi']);
                $eve_fit_rig = evefitting_slot($eve_fit['rig']);

                $eve_fit_subsystem = (count($eve_fit['subsystem']) > 0) ? "<p>[Subsystems]<br />".evefitting_slot($eve_fit['subsystem'])."</p>" : '';

                // wtf is this shit. 'its how its done'. i am disappoint.
                eval("\$post['message'] .= \"<br><br>  ".$templates->get("evefitting")."\";");
            }
        }
    }

    return $post;
}

?>
