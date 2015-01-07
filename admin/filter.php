<?php

require_once(dirname(__FILE__)."/../controller/database.php");

class Filter{
    
    public static function sqlEscape($text){
        return self::encodeSql($text);
    }
    
    private static function encodeSql($string){
        DbConnection::getConnection();
        return mysql_real_escape_string(self::escape_utf8($string));
    }
    
    private static function escape_utf8($text) {
	$text = str_replace("&", "&amp;", $text);
	$text = str_replace("–", "&ndash;", $text);
        $text = str_replace("—", "&mdash;", $text);
        $text = str_replace("¡", "&iexcl;", $text);
        $text = str_replace("¿", "&iquest;", $text);
        $text = str_replace('"', "&quot;", $text); 
        $text = str_replace("“", "&ldquo;", $text);
        $text = str_replace("”", "&rdquo;", $text);
        $text = str_replace("‘", "&lsquo;", $text);
        $text = str_replace("’", "&rsquo;", $text);
        $text = str_replace("«", "&laquo;", $text);
        $text = str_replace("»", "&raquo;", $text);
        $text = str_replace("  "," &nbsp;",$text);
        //simboli
        $text = str_replace("¢", "&cent;", $text);
        $text = str_replace("©", "&copy;", $text);
        $text = str_replace("÷", "&divide;", $text);
        $text = str_replace(">", "&gt;", $text);
        $text = str_replace("<", "&lt;", $text);
        $text = str_replace("µ", "&micro;", $text);
        $text = str_replace("·", "&middot;", $text);
        $text = str_replace("¶", "&para;", $text);
        $text = str_replace("±", "&plusmn;", $text);
        $text = str_replace("€", "&euro;", $text);
        $text = str_replace("£", "&pound;", $text);
        $text = str_replace("®", "&reg;", $text);
        $text = str_replace("§", "&sect;", $text);
        $text = str_replace("™", "&trade;", $text);
        $text = str_replace("¥", "&yen;", $text);
        //diacritici
        $text = str_replace("á", "&aacute;", $text);
        $text = str_replace("Á", "&Aacute;", $text);
        $text = str_replace("à", "&agrave;", $text);
        $text = str_replace("À", "&Agrave;", $text);
        $text = str_replace("â", "&acirc;", $text);
        $text = str_replace("Â", "&Acirc;", $text);
        $text = str_replace("å", "&aring;", $text);
        $text = str_replace("Å", "&Aring;", $text);
        $text = str_replace("ã", "&atilde;", $text);
        $text = str_replace("Ã", "&Atilde;", $text);
        $text = str_replace("ä", "&auml;", $text);
        $text = str_replace("Ä", "&Auml;", $text);
        $text = str_replace("æ", "&aelig;", $text);
        $text = str_replace("Æ", "&AElig;", $text);
        $text = str_replace("ç", "&ccedil;", $text);
        $text = str_replace("Ç", "&Ccedil;", $text);
        $text = str_replace("é", "&eacute;", $text);
        $text = str_replace("É", "&Eacute;", $text);
        $text = str_replace("è", "&egrave;", $text);
        $text = str_replace("È", "&Egrave;", $text);
        $text = str_replace("ê", "&ecirc;", $text);
        $text = str_replace("Ê", "&Ecirc;", $text);
        $text = str_replace("ë", "&euml;", $text);
        $text = str_replace("Ë", "&Euml;", $text);
        $text = str_replace("í", "&iacute;", $text);
        $text = str_replace("Í", "&Iacute;", $text);
        $text = str_replace("ì", "&igrave;", $text);
        $text = str_replace("Ì", "&Igrave;", $text);
        $text = str_replace("î", "&icirc;", $text);
        $text = str_replace("Î", "&Icirc;", $text);
        $text = str_replace("ï", "&iuml;", $text);
        $text = str_replace("Ï", "&Iuml;", $text);
        $text = str_replace("ñ", "&ntilde;", $text);
        $text = str_replace("Ñ", "&Ntilde;", $text);
        $text = str_replace("ó", "&oacute;", $text);
        $text = str_replace("Ó", "&Oacute;", $text);
        $text = str_replace("ò", "&ograve;", $text);
        $text = str_replace("Ò", "&Ograve;", $text);
        $text = str_replace("ô", "&ocirc;", $text);
        $text = str_replace("Ô", "&Ocirc;", $text);
        $text = str_replace("ø", "&oslash;", $text);
        $text = str_replace("Ø", "&Oslash;", $text);
        $text = str_replace("õ", "&otilde;", $text);
        $text = str_replace("Õ", "&Otilde;", $text);
        $text = str_replace("ö", "&ouml;", $text);
        $text = str_replace("Ö", "&Ouml;", $text);
        $text = str_replace("ú", "&uacute;", $text);
        $text = str_replace("Ú", "&Uacute;", $text);
        $text = str_replace("ù", "&ugrave;", $text);
        $text = str_replace("Ù", "&Ugrave;", $text);
        $text = str_replace("û", "&ucirc;", $text);
        $text = str_replace("Û", "&Ucirc;", $text);
        $text = str_replace("ü", "&uuml;", $text);
        $text = str_replace("Ü", "&Uuml;", $text);
        $text = str_replace("ÿ", "&yuml;", $text);
	
        return $text;
    }
}