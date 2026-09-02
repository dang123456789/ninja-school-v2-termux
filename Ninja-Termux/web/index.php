<?php
/** Adminer - Compact database management
* @link https://www.adminer.org/
* @author Jakub Vrana, https://www.vrana.cz/
* @copyright 2007 Jakub Vrana
* @license https://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
* @license https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
* @version 6.0.1
*/namespace
Adminer;const
VERSION="6.0.1";error_reporting(24575);set_error_handler(function($ad,$cd){return!!preg_match('~^Undefined (array key|offset|index)~',$cd);},E_WARNING|E_NOTICE);$Fd=!preg_match('~^(unsafe_raw)?$~',ini_get("filter.default"));if($Fd||ini_get("filter.default_flags")){foreach(array('_GET','_POST','_COOKIE','_SERVER')as$X){$Hl=filter_input_array(constant("INPUT$X"),FILTER_UNSAFE_RAW);if($Hl)$$X=$Hl;}}$_COOKIE=array_filter($_COOKIE,'is_scalar');if(function_exists("mb_internal_encoding"))mb_internal_encoding("8bit");function
connection($g=null){return($g?:Db::$instance);}function
adminer(){return
Adminer::$instance;}function
driver(){return
Driver::$instance;}function
connect(){$Vb=adminer()->credentials();$I=Driver::connect($Vb[0],$Vb[1],$Vb[2]);return(is_object($I)?$I:null);}function
idf_unescape($u){if(!preg_match('~^[`\'"[]~',$u))return$u;$Jf=substr($u,-1);return
str_replace($Jf.$Jf,$Jf,substr($u,1,-1));}function
q($Q){return
connection()->quote($Q);}function
idx($Ba,$x,$k=null){return($Ba&&array_key_exists($x,$Ba)?$Ba[$x]:$k);}function
number($X){return
preg_replace('~[^0-9]+~','',$X);}function
int_type(){return'(tiny|small|medium|big)?int(eger|\d)?';}function
number_type(){return'(^('.int_type().'|decimal|numeric|number|real|(binary_|half_|scaled_)?float\d?|(binary_)?double( precision)?|(small)?money)$)';}function
text_type(){return'char|text'.(JUSH=="sql"?'|enum|set':'');}function
is_searchable(array$m,array$X){if(!isset($m["privileges"]["where"]))return
false;$U=$m["type"];$Dj=$X["val"];$Sa='binary$|bytea|raw|image|bfile|^vector$'.(JUSH=="mssql"?'|^timestamp$':'|^bit').(JUSH=="oracle"?'|^blob|^long|rowid':'');if(preg_match("~$Sa~",$U))return
false;if(preg_match(number_type(),$U)){$ih='-?\d+(\.\d+)?';return(bool)preg_match('~^'.$ih.(preg_match('~IN$~',$X["op"])?"( *, *$ih)*":'').'$~',$Dj);}if(preg_match('~^(small)?date|^timestamp~',$U))return(bool)preg_match('~^\d+-\d+-\d+~',$Dj);if(preg_match('~^time~',$U))return(bool)preg_match('~^\d+:\d+~',$Dj);if(preg_match('~^bool~',$U)||(JUSH=="mssql"&&$U=="bit"))return(bool)preg_match('~^(t|f|true|false|[01])$~i',$Dj);return
true;}function
remove_slashes(array$em,$Fd=false){$I=array();foreach($em
as$x=>$X)$I[stripslashes($x)]=(is_array($X)?remove_slashes($X,$Fd):($Fd?$X:stripslashes($X)));return$I;}function
bracket_escape($u,$La=false){static$ol=array(':'=>':1',']'=>':2','['=>':3','"'=>':4','='=>':5');return
strtr($u,($La?array_flip($ol):$ol));}function
url_escape($Q){static$ol=array();if(!$ol){$ol=array(' '=>'+');foreach(str_split("\"'<>#%&+=?".ini_get("arg_separator.input"))as$eb)$ol[$eb]=sprintf('%%%02X',ord($eb));for($s=0;$s<256;$s++){if($s<32||$s>126)$ol[chr($s)]=sprintf('%%%02X',$s);}}return
strtr((string)$Q,$ol);}function
min_version($hm,$eg="",$g=null){$g=connection($g);$Sj=$g->server_info;if($eg&&preg_match('~([\d.]+)-MariaDB~',$Sj,$A)){$Sj=$A[1];$hm=$eg;}return$hm&&version_compare($Sj,$hm)>=0;}function
charset(Db$f){return(min_version("5.5.3",0,$f)?"utf8mb4":"utf8");}function
ini_set($Fh,$Y){return(function_exists('ini_set')?\ini_set($Fh,$Y):false);}function
ini_bool($df){$X=ini_get($df);return(preg_match('~^(on|true|yes)$~i',$X)||(int)$X);}function
ini_bytes($df){$X=ini_get($df);switch(strtolower(substr($X,-1))){case'g':$X=(int)$X*1024;case'm':$X=(int)$X*1024;case'k':$X=(int)$X*1024;}return$X;}function
max_input_vars($J,$Uh){$ig=(int)ini_get("max_input_vars");return($ig?(int)floor(($ig-$Uh)/$J):0);}function
max_input_vars_error(){$df="max_input_vars";return
lang(0,"<b>$df = ".ini_get($df)."</b>");}function
sid(){static$I;if($I===null)$I=(SID&&!($_COOKIE&&ini_bool("session.use_cookies")));return$I;}function
set_password($gm,$N,$V,$E){$_SESSION["pwds"][$gm][$N][$V]=($_COOKIE["adminer_key"]&&is_string($E)?array(encrypt_string($E,$_COOKIE["adminer_key"])):$E);}function
get_password(){$I=get_session("pwds");if(is_array($I))$I=($_COOKIE["adminer_key"]?decrypt_string($I[0],$_COOKIE["adminer_key"]):false);return$I;}function
get_val($G,$m=0,$Gb=null){$Gb=connection($Gb);$H=$Gb->query($G);if(!is_object($H))return
false;$J=$H->fetch_row();return($J?$J[$m]:false);}function
get_vals($G,$d=0){$I=array();$H=connection()->query($G);if(is_object($H)){while($J=$H->fetch_row())$I[]=$J[$d];}return$I;}function
get_key_vals($G,$g=null,$Vj=true){$g=connection($g);$I=array();$H=$g->query($G);if(is_object($H)){while($J=$H->fetch_row()){if($Vj)$I[$J[0]]=$J[1];else$I[]=$J[0];}}return$I;}function
get_rows($G,$g=null,$l="<p class='error'>"){$Gb=connection($g);$I=array();$H=$Gb->query($G);if(is_object($H)){while($J=$H->fetch_assoc())$I[]=$J;}elseif(!$H&&!$g&&$l&&(defined('Adminer\PAGE_HEADER')||$l=="-- "))echo$l.adminer()->error()."\n";return$I;}function
unique_array($J,array$w){foreach($w
as$v){if(preg_match("~^(PRIMARY|UNIQUE)$~",$v["type"])&&!$v["partial"]){$I=array();foreach($v["columns"]as$x){if(!isset($J[$x]))continue
2;$I[$x]=$J[$x];}return$I;}}}function
escape_key($x){if(preg_match('(^([\w(]+)('.str_replace("_",".*",preg_quote(idf_escape("_"))).')([ \w)]+)$)',$x,$A))return$A[1].idf_escape(idf_unescape($A[2])).$A[3];return
idf_escape($x);}function
where(array$Z,array$n=array()){$I=array();foreach((array)$Z["where"]as$x=>$X){$x=bracket_escape($x,true);$d=escape_key($x);$m=idx($n,$x,array());$_d=$m["type"];$pf=$m&&(is_blob($m)||preg_match('~binary~',$_d));$I[]=$d.($pf&&!is_utf8($X)?" = ".driver()->quoteBinary($X):(JUSH=="sql"&&$_d=="json"?" = CAST(".q($X)." AS JSON)":(JUSH=="pgsql"&&preg_match('~^jsonb?$~',$m["full_type"])?"::jsonb = ".q($X)."::jsonb":(JUSH=="sql"&&is_numeric($X)&&preg_match('~\.~',$X)?" LIKE ".q($X):(JUSH=="mssql"&&strpos($_d,"datetime")===false?" LIKE ".q(preg_replace('~[_%[]~','[\0]',$X)):" = ".unconvert_field($m,q($X)))))));if(JUSH=="sql"&&preg_match('~char|text~',$_d)&&preg_match("~[^ -@]~",$X))$I[]="$d = ".q($X)." COLLATE ".charset(connection())."_bin";}foreach((array)$Z["null"]as$x)$I[]=escape_key($x)." IS NULL";return
implode(" AND ",$I);}function
where_columns(array$n){$I=array();foreach((array)$_GET["null"]as$x)$I[$x]=true;foreach((array)$_GET["where"]as$x=>$X){$x=bracket_escape($x,true);foreach($n
as$C=>$m){if($x==$C||strpos($x,idf_escape($C))!==false)$I[$C]=true;}}return$I;}function
where_check($X,array$n=array()){parse_str($X,$hb);remove_slashes(array(&$hb));return
where($hb,$n);}function
where_link($s,$d,$Y,$Ch="="){$_h=($Y!==null?$Ch:"IS NULL");return"&where[$s][col]=".url_escape($d).($_h!=first(adminer()->operators())?"&where[$s][op]=".url_escape($_h):"")."&where[$s][val]=".url_escape($Y);}function
convert_fields(array$e,array$n,array$M=array()){$I="";foreach($e
as$x=>$X){if($M&&!in_array(idf_escape($x),$M))continue;$Ca=convert_field($n[$x]);if($Ca)$I
.=", $Ca AS ".idf_escape($x);}return$I;}function
cookie_path(){return
strtr(preg_replace('~\?.*~','',$_SERVER["REQUEST_URI"]),array(";"=>"%3B",","=>"%2C"));}function
cookie($C,$Y,$Tf=2592000){header("Set-Cookie: $C=".rawurlencode($Y).($Tf?"; expires=".gmdate("D, d M Y H:i:s",time()+$Tf)." GMT":"")."; path=".cookie_path().(HTTPS?"; secure":"").($C=="adminer_import"?"":"; HttpOnly")."; SameSite=lax",false);}function
get_url($Pl,$Nb){$http_response_header=null;$bd=array();set_error_handler(function($ad,$l)use(&$bd){$bd[]=preg_replace('~^file_get_contents\([^)]*\):\s*~','',$l);return
true;});$I=file_get_contents($Pl,false,$Nb);restore_error_handler();$xe=(function_exists('http_get_last_response_headers')?http_get_last_response_headers():$http_response_header);return
array($I,(preg_match('~^HTTP/[\d.]+ (\d+)~',idx($xe,0,''),$A)?$A[1]:''),(array)$xe,($I===false?implode("\n",$bd):''),);}function
get_settings($Qb){parse_str($_COOKIE[$Qb],$Wj);return$Wj;}function
get_setting($x,$Qb="adminer_settings",$k=null){return
idx(get_settings($Qb),$x,$k);}function
save_settings(array$Wj,$Qb="adminer_settings"){$Y=http_build_query($Wj+get_settings($Qb));cookie($Qb,$Y);$_COOKIE[$Qb]=$Y;}function
restart_session(){if(!ini_bool("session.use_cookies")&&(!function_exists('session_status')||session_status()==PHP_SESSION_NONE))session_start();}function
stop_session($Od=false){$Sl=ini_bool("session.use_cookies");if(!$Sl||$Od){session_write_close();if($Sl&&ini_set("session.use_cookies",'0')===false)session_start();}}function&get_session($x){return$_SESSION[$x][DRIVER][SERVER][$_GET["username"]];}function
set_session($x,$X){$_SESSION[$x][DRIVER][SERVER][$_GET["username"]]=$X;}function
auth_url($gm,$N,$V,$j=null){$Ol=remove_from_uri(implode("|",array_keys(SqlDriver::$drivers))."|username|ext|".($j!==null?"db|":"").($gm=='mssql'||$gm=='pgsql'?"":"ns|").session_name());preg_match('~([^?]*)\??(.*)~',$Ol,$A);return"$A[1]?".(sid()?SID."&":"").($_GET["ext"]?"ext=".url_escape($_GET["ext"])."&":"").($gm!="server"||$N!=""?url_escape($gm)."=".url_escape($N)."&":"")."username=".url_escape($V).($j!=""?"&db=".url_escape($j):"").($A[2]?"&$A[2]":"");}function
is_ajax(){return($_SERVER["HTTP_X_REQUESTED_WITH"]=="XMLHttpRequest");}function
redirect($ag,$B=null){if($B!==null){restart_session();$_SESSION["messages"][preg_replace('~^[^?]*~','',($ag!==null?$ag:$_SERVER["REQUEST_URI"]))][]=$B;}if($ag!==null){if($ag=="")$ag=".";header("Location: $ag");exit;}}function
query_redirect($G,$ag,$B,$aj=true,$jd=true,$ud=false,$bl=""){if($jd){$qk=microtime(true);$ud=!connection()->query($G);$bl=format_time($qk);}$jk=($G?adminer()->messageQuery($G,$bl,$ud):"");if($ud){adminer()->error
.=adminer()->error().$jk.script("messagesPrint();")."<br>";return
false;}if($aj)redirect($ag,$B.$jk);return
true;}class
Queries{static$queries=array();static$start=0;}function
queries($G){if(!Queries::$start)Queries::$start=microtime(true);Queries::$queries[]=(driver()->delimiter!=';'?$G:(preg_match('~;$~',$G)?"DELIMITER ;;\n$G;\nDELIMITER ":$G).";");return
connection()->query($G);}function
apply_queries($G,array$T,$dd='Adminer\table'){foreach($T
as$R){if(!queries("$G ".$dd($R)))return
false;}return
true;}function
queries_redirect($ag,$B,$aj){$Ui=implode("\n",Queries::$queries);$bl=format_time(Queries::$start);return
query_redirect($Ui,$ag,$B,$aj,false,!$aj,$bl);}function
format_time($qk){return
lang(1,max(0,microtime(true)-$qk));}function
relative_uri($Ol=''){return
preg_replace_callback('~^[^?]*~',function($A){return
str_replace(":","%3A",$A[0]);},preg_replace('~^[^?]*/([^?]*)~','\1',($Ol?:$_SERVER["REQUEST_URI"])));}function
remove_from_uri($bi=""){return
substr(preg_replace("~(?<=[?&])($bi".(SID?"":"|".session_name()).")=[^&]*&~",'',relative_uri()."&"),0,-1);}function
get_files($C,$jc=false){$Bd=$_FILES[$C];if(!$Bd)return
null;foreach($Bd
as$x=>$X)$Bd[$x]=(array)$X;$I=array();foreach($Bd["error"]as$x=>$l){if($l)return$l;$o=$Bd["name"][$x];$jl=$Bd["tmp_name"][$x];$Lb=file_get_contents($jc&&preg_match('~\.gz$~',$o)?"compress.zlib://$jl":$jl);if($jc){$qk=substr($Lb,0,3);if(function_exists("iconv")&&preg_match("~^\xFE\xFF|^\xFF\xFE~",$qk))$Lb=iconv("utf-16","utf-8",$Lb);elseif($qk=="\xEF\xBB\xBF")$Lb=substr($Lb,3);}$I[]=array($o,$Lb);}return$I;}function
get_file($x,$jc=false,$pc=""){$Ed=get_files($x,$jc);if(!is_array($Ed))return$Ed;$I='';foreach($Ed
as$Bd){$Lb=$Bd[1];$I
.=$Lb;if($pc)$I
.=(preg_match("($pc\\s*\$)",$Lb)?"":$pc)."\n\n";}return$I;}function
upload_error($l){$qg=($l==UPLOAD_ERR_INI_SIZE?ini_get("upload_max_filesize"):0);return($l?lang(2).($qg?" ".lang(3,$qg):""):lang(4));}function
is_utf8($X){return(preg_match('~~u',$X)&&!preg_match('~[\0-\x8\xB\xC\xE-\x1F]~',$X));}function
format_number($X){return
strtr(number_format($X,0,".",lang(5)),preg_split('~~u',lang(6),-1,PREG_SPLIT_NO_EMPTY));}function
format_status(array$S,$x){$X=idx($S,$x,'?');if(!is_numeric($X))return
h($X);if($X<0)return'?';$za=($x=="Rows"&&(JUSH=="sqlite"||$S["Engine"]==(JUSH=="pgsql"?"table":"InnoDB")));return($za?"~ ":"").format_number($X);}function
friendly_url($X){return
preg_replace('~\W~i','-',$X);}function
table_status1($R,$vd=false){$I=table_status($R,$vd);return($I?reset($I):array("Name"=>$R));}function
column_foreign_keys($R){$I=array();foreach(adminer()->foreignKeys($R)as$p){foreach($p["source"]as$X)$I[$X][]=$p;}return$I;}function
fields_from_edit(){$I=array();foreach((array)$_POST["field_keys"]as$x=>$X){if($X!=""){$X=bracket_escape($X);$_POST["function"][$X]=$_POST["field_funs"][$x];$_POST["fields"][$X]=$_POST["field_vals"][$x];}}foreach((array)$_POST["fields"]as$x=>$X){$C=bracket_escape($x,true);$I[$C]=array("field"=>$C,"full_type"=>"","type"=>"","privileges"=>array("insert"=>1,"update"=>1,"where"=>1,"order"=>1),"null"=>true,"auto_increment"=>($C==driver()->primary),);}return$I;}function
dump_headers($Ie,$Ng=false){$I=adminer()->dumpHeaders($Ie,$Ng);$Wh=$_POST["output"];if($Wh!="text"||$I=="tar"){$Cb=($Wh!="text"&&$Wh!="file"&&preg_match('~^[0-9a-z]+$~',$Wh)?".$Wh":"");header("Content-Disposition: attachment; filename=".adminer()->dumpFilename($Ie).".$I$Cb");}session_write_close();if(!ob_get_level())ob_start(null,4096);ob_flush();flush();return$I;}function
dump_csv(array$J){$zl=$_POST["format"]=="tsv";foreach($J
as$x=>$X){if(preg_match('~["\n]|^0[^.]|\.\d*0$|'.($zl?'\t':'[,;]|^$').'~',$X))$J[$x]='"'.str_replace('"','""',$X).'"';}echo
implode(($_POST["format"]=="csv"?",":($zl?"\t":";")),$J)."\r\n";}function
parse_csv($Yb,$Lj){$I=array();preg_match_all('~(?>"[^"]*"|[^"\r\n]+)+~',$Yb,$gg);foreach($gg[0]as$J){preg_match_all("~((?>\"[^\"]*\")+|[^$Lj]*)$Lj~",$J.$Lj,$hg);$I[]=$hg[1];}return$I;}function
csv_value($X){return(preg_match('~^".*"$~s',$X)?str_replace('""','"',substr($X,1,-1)):$X);}function
apply_sql_function($r,$d){return($r?($r=="unixepoch"?"DATETIME($d, '$r')":($r=="count distinct"?"COUNT(DISTINCT ":strtoupper("$r("))."$d)"):$d);}function
get_temp_dir(){return
ini_get("upload_tmp_dir")?:sys_get_temp_dir();}function
file_open_lock($o){if(is_link($o))return;$q=@fopen($o,"c+");if(!$q)return;@chmod($o,0660);if(!flock($q,LOCK_EX)){fclose($q);return;}return$q;}function
file_write_unlock($q,$cc){rewind($q);fwrite($q,$cc);ftruncate($q,strlen($cc));file_unlock($q);}function
file_unlock($q){flock($q,LOCK_UN);fclose($q);}function
first(array$Ba){return
reset($Ba);}function
password_file($h){$o=get_temp_dir()."/adminer.key";if(!$h&&!file_exists($o))return'';$q=file_open_lock($o);if(!$q)return'';$I=stream_get_contents($q);if(!$I){$I=rand_string();file_write_unlock($q,$I);}else
file_unlock($q);return$I;}function
rand_string(){return(function_exists('random_bytes')?bin2hex(random_bytes(16)):md5(uniqid(strval(mt_rand()),true)));}function
select_value($X,$_,array$m,$Zk){if(is_array($X)){$I="";if(array_filter($X,'is_array')==array_values($X)){$Af=array();foreach($X
as$W)$Af+=array_fill_keys(array_keys($W),null);foreach(array_keys($Af)as$zf)$I
.="<th>".h($zf);foreach($X
as$W){$I
.="<tr>";foreach(array_merge($Af,$W)as$Zl)$I
.="<td>".select_value($Zl,$_,$m,$Zk);}}else{foreach($X
as$zf=>$W)$I
.="<tr>".($X!=array_values($X)?"<th>".h($zf):"")."<td>".select_value($W,$_,$m,$Zk);}return"<table>$I</table>";}if(!$_)$_=adminer()->selectLink($X,$m);if($_===null){if(is_mail($X))$_="mailto:$X";if(is_url($X))$_=$X;}$X=driver()->value($X,$m);$I=adminer()->editVal($X,$m);if($I!==null){if(!is_utf8($I))$I="\0";elseif($Zk!=""&&is_shortable($m))$I=shorten_utf8($I,max(0,+$Zk));else$I=h($I);}return
adminer()->selectVal($I,$_,$m,$X);}function
is_blob(array$m){return
preg_match('~blob|bytea|raw|file'.(JUSH=="mssql"?'|binary|image':'').'~',$m["type"])&&!in_array($m["type"],idx(driver()->structuredTypes(),lang(7),array()));}function
is_mail($Rc){$Ea='[-a-z0-9!#$%&\'*+/=?^_`{|}~]';$Ec='[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';$si="$Ea+(\\.$Ea+)*@($Ec?\\.)+$Ec";return
is_string($Rc)&&preg_match("(^$si(,\\s*$si)*\$)i",$Rc);}function
is_url($Q){$Ec='[a-z0-9]([-a-z0-9]{0,61}[a-z0-9])';return
preg_match("~^((https?):)?//($Ec?\\.)+$Ec(:\\d+)?(/.*)?(\\?.*)?(#.*)?\$~i",$Q);}function
is_shortable(array$m){return!preg_match('~'.number_type().'|date|time|year~',$m["type"]);}function
host_port($N){return(preg_match('~^(:([^:].*)|(\[(.+)\]|(([^:]+://)?[^:]+))(:(\d+))?)$~',$N,$A)?array($A[4].$A[5],$A[2].$A[8]):array($N,''));}function
count_rows($R,array$Z,$qf,array$ge){$G=" FROM ".table($R).($Z?" WHERE ".implode(" AND ",$Z):"");return($qf&&(JUSH=="sql"||count($ge)==1)?"SELECT COUNT(DISTINCT ".implode(", ",$ge).")$G":"SELECT COUNT(*)".($qf?" FROM (SELECT 1$G GROUP BY ".implode(", ",$ge).") x":$G));}function
slow_query($G){$j=adminer()->database();$cl=adminer()->queryTimeout();$bk=driver()->slowQuery($G,$cl);$g=null;if(!$bk&&support("kill")){$g=connect();if($g&&($j==""||$g->select_db($j))){$Bf=get_val(connection_id(),0,$g);echo
script("const timeout = setTimeout(() => { ajax('".js_escape(ME)."script=kill', function () {}, 'kill=$Bf&token=".get_token()."'); }, 1000 * $cl);");}}ob_flush();flush();$I=@get_key_vals(($bk?:$G),$g,false);if($g){echo
script("clearTimeout(timeout);");ob_flush();flush();}return$I;}function
get_token(){$Xi=rand(1,1e6);return($Xi^$_SESSION["token"]).":$Xi";}function
verify_token(){list($kl,$Xi)=explode(":",$_POST["token"]);return($Xi^$_SESSION["token"])==$kl&&in_array($_SERVER["HTTP_SEC_FETCH_SITE"],array("","same-origin"));}function
compress_alphabet(){return
strtr(implode(range('"','~')),"'\\","!\n");}function
decompress_string($Q,$vc=""){$xa=array_flip(str_split(compress_alphabet()));$y=strlen($Q);$cm=($y?13*($y-1)/2-$xa[$Q[0]]:0);$Sa="";$nj=0;$oj=0;for($s=1;$s<$y;$s+=2){$nj=($nj<<13)+$xa[$Q[$s]]*93+$xa[$Q[$s+1]];$oj+=13;while($oj>=8&&$cm>=8){$oj-=8;$cm-=8;$Sa
.=chr($nj>>$oj);$nj&=(1<<$oj)-1;}}if($Sa=="")return"";if($vc!=""&&function_exists('inflate_init'))return
inflate_add(inflate_init(ZLIB_ENCODING_RAW,array('dictionary'=>$vc)),$Sa,ZLIB_FINISH);return($vc==""&&function_exists('gzinflate')?gzinflate($Sa):inflate($Sa,$vc));}function
inflate($Sa,$vc=""){$Qf=array(3,4,5,6,7,8,9,10,11,13,15,17,19,23,27,31,35,43,51,59,67,83,99,115,131,163,195,227,258);$Rf=array(0,0,0,0,0,0,0,0,1,1,1,1,2,2,2,2,3,3,3,3,4,4,4,4,5,5,5,5,0);$zc=array(1,2,3,4,5,7,9,13,17,25,33,49,65,97,129,193,257,385,513,769,1025,1537,2049,3073,4097,6145,8193,12289,16385,24577);$Ac=array(0,0,0,0,1,1,2,2,3,3,4,4,5,5,6,6,7,7,8,8,9,9,10,10,11,11,12,12,13,13);$I=$vc;$F=0;do{$Gd=inflate_bits($Sa,$F,1);$U=inflate_bits($Sa,$F,2);if(!$U){$F=($F+7)&~7;$y=inflate_bits($Sa,$F,16);$F+=16;$I
.=substr($Sa,$F>>3,$y);$F+=$y<<3;}else{if($U==1){$Yf=array_merge(array_fill(0,144,8),array_fill(0,112,9),array_fill(0,24,7),array_fill(0,8,8));$Bc=array_fill(0,30,5);}else{$Xf=inflate_bits($Sa,$F,5)+257;$_c=inflate_bits($Sa,$F,5)+1;$Ih=array(16,17,18,0,8,7,9,6,10,5,11,4,12,3,13,2,14,1,15);$Cg=array_fill(0,19,0);$Bg=inflate_bits($Sa,$F,4)+4;for($s=0;$s<$Bg;$s++)$Cg[$Ih[$s]]=inflate_bits($Sa,$F,3);$Dg=inflate_table($Cg);$Sf=array();while(count($Sf)<$Xf+$_c){$Ak=inflate_symbol($Sa,$F,$Dg);if($Ak==16)$Sf=array_merge($Sf,array_fill(0,inflate_bits($Sa,$F,2)+3,end($Sf)));elseif($Ak==17)$Sf=array_merge($Sf,array_fill(0,inflate_bits($Sa,$F,3)+3,0));elseif($Ak==18)$Sf=array_merge($Sf,array_fill(0,inflate_bits($Sa,$F,7)+11,0));else$Sf[]=$Ak;}$Yf=array_slice($Sf,0,$Xf);$Bc=array_slice($Sf,$Xf);}$Zf=inflate_table($Yf);$Dc=inflate_table($Bc);while(($Ak=inflate_symbol($Sa,$F,$Zf))!=256){if($Ak<256)$I
.=chr($Ak);else{$y=$Qf[$Ak-257]+inflate_bits($Sa,$F,$Rf[$Ak-257]);$Cc=inflate_symbol($Sa,$F,$Dc);$ph=strlen($I)-$zc[$Cc]-inflate_bits($Sa,$F,$Ac[$Cc]);for($s=0;$s<$y;$s++)$I
.=$I[$ph+$s];}}}}while(!$Gd);return($vc==""?$I:substr($I,strlen($vc)));}function
inflate_bits($Sa,&$F,$Sb){$I=0;for($s=0;$s<$Sb;$s++){$I+=((ord($Sa[$F>>3])>>($F&7))&1)<<$s;$F++;}return$I;}function
inflate_table(array$Sf){$R=array();$rb=0;for($Ta=1;$Ta<=max($Sf);$Ta++){foreach($Sf
as$Ak=>$y){if($y==$Ta){$R[$Ta][$rb]=$Ak;$rb++;}}$rb<<=1;}return$R;}function
inflate_symbol($Sa,&$F,array$R){$rb=0;$Ta=0;do{$rb=($rb<<1)+inflate_bits($Sa,$F,1);$Ta++;}while(!isset($R[$Ta][$rb]));return$R[$Ta][$rb];}function
script($fk,$nl="\n"){return"<script".nonce().">$fk</script>$nl";}function
script_src($Pl,$mc=false){return"<script src='".h($Pl)."'".nonce().($mc?" defer":"")."></script>\n";}function
nonce(){return' nonce="'.get_nonce().'"';}function
on($ed,$oe,$_a=null){$Aa=array();foreach(array_slice(func_get_args(),2)as$X)$Aa[]=json_encode($X,256);return" data-on$ed='".str_replace(array('&','<',"'"),array('&amp;','&lt;','&#039;'),"$oe(".implode(", ",$Aa).")")."'";}function
input_hidden($C,$Y=""){return"<input type='hidden' name='".h($C)."' value='".h($Y)."'>\n";}function
input_token(){return
input_hidden("token",get_token());}function
target_blank(){return' target="_blank" rel="noreferrer noopener"';}function
h($Q){return
str_replace(array('&','<','"',"'","\0"),array('&amp;','&lt;','&quot;','&#039;','&#0;'),$Q);}function
nl_br($Q){return
str_replace("\n","<br>",$Q);}function
checkbox($C,$Y,$kb,$Ff="",$c="",$pb="",$Hf=""){$I="<input type='checkbox' name='$C' value='".h($Y)."'".($kb?" checked":"").($Ff==""&&$pb?" class='$pb'":"").($Hf?" aria-labelledby='$Hf'":"").$c.">";return($Ff!=""?"<label".($pb?" class='$pb'":"").">$I".h($Ff)."</label>":$I);}function
optionlist($Gh,$Ij=null,$Tl=false){$I="";foreach($Gh
as$zf=>$W){$Hh=array($zf=>$W);if(is_array($W)){$I
.='<optgroup label="'.h($zf).'">';$Hh=$W;}foreach($Hh
as$x=>$X)$I
.='<option'.($Tl||is_string($x)?' value="'.h($x).'"':'').($Ij!==null&&($Tl||is_string($x)?(string)$x:$X)===$Ij?' selected':'').'>'.h($X);if(is_array($W))$I
.='</optgroup>';}return$I;}function
html_select($C,array$Gh,$Y="",$c="",$Hf=""){static$Ff=0;$Gf="";if(!$Hf&&substr($Gh[""],0,1)=="("){$Ff++;$Hf="label-$Ff";$Gf="<option value='' id='$Hf'>".h($Gh[""]);unset($Gh[""]);}return"<select name='".h($C)."'".($Hf?" aria-labelledby='$Hf'":"")."$c>".$Gf.optionlist($Gh,$Y)."</select>";}function
html_radios($C,array$Gh,$Y="",$Lj=""){$I="";foreach($Gh
as$x=>$X)$I
.="<label><input type='radio' name='".h($C)."' value='".h($x)."'".($x==$Y?" checked":"").">".h($X)."</label>$Lj";return$I;}function
confirm($B=""){return
on('click','confirmClick',$B?:lang(8));}function
print_fieldset($t,$Pf,$km=false){echo"<fieldset><legend>","<a href='#fieldset-$t' class='toggle'>$Pf</a>","</legend>","<div id='fieldset-$t'".($km?"":" class='hidden'").">\n";}function
bold($Va,$pb=""){return($Va?" class='active $pb'":($pb?" class='$pb'":""));}function
js_escape($Q){return
str_replace("<","\\x3C",addcslashes($Q,"\r\n'\\"));}function
js_escape_re($Q){return
addcslashes(preg_quote($Q,"/"),"\r\n");}function
pagination_href($D){return
remove_from_uri("page|next").($D?"&page=$D".($_GET["next"]!=""?"&next=".url_escape($_GET["next"]):""):"");}function
pagination($D,$Zb){return" ".($D==$Zb?($D?"<b>".($D+1)."</b>":$D+1):'<a href="'.h(pagination_href($D)).'">'.($D+1)."</a>");}function
hidden_fields(array$Qi,array$Me=array(),$Gi=''){$I=false;foreach($Qi
as$x=>$X){if(!in_array($x,$Me)){if(is_array($X))hidden_fields($X,array(),$x);else{$I=true;echo
input_hidden(($Gi?$Gi."[$x]":$x),$X);}}}return$I;}function
hidden_fields_get(){echo(sid()?input_hidden(session_name(),session_id()):''),($_GET["ext"]?input_hidden("ext",$_GET["ext"]):""),(isset($_GET[DRIVER])?input_hidden(DRIVER,SERVER):""),input_hidden("username",$_GET["username"]);}function
on_upload_progress(&$Nl){$Nl=(ini_bool("session.upload_progress.enabled")&&ini_get("session.upload_progress.name")?rand_string():"");return($Nl?on('submit','uploadProgress',ME."upload=$Nl",SESSION_NAME."=$Nl"):"");}function
file_input($c,$nj=""){$kg="max_file_uploads";$lg=ini_get($kg);$qg="upload_max_filesize";$rg=ini_bytes($qg);$Di=ini_bytes("post_max_size");if($Di&&$Di<$rg){$qg="post_max_size";$rg=$Di;}$sg=ini_get($qg);return(ini_bool("file_uploads")?"<input type='file'$c".on('change','fileChange',(int)$lg,lang(9,"$kg = $lg"),$rg,lang(9,"$qg = $sg")).">$nj":lang(10));}function
enum_input($U,$c,array$m,$Y,$Uc=""){preg_match_all("~'((?:[^']|'')*)'~",$m["length"],$gg);$Gi=($m["type"]=="enum"?"val-":"");$kb=(is_array($Y)?in_array("null",$Y):$Y===null);$I=($m["null"]&&$Gi?"<label><input type='$U'$c value='null'".($kb?" checked":"")."><i>$Uc</i></label>":"");foreach($gg[1]as$X){$X=stripcslashes(str_replace("''","'",$X));$kb=(is_array($Y)?in_array($Gi.$X,$Y):$Y===$X);$I
.=" <label><input type='$U'$c value='".h($Gi.$X)."'".($kb?' checked':'').'>'.h(adminer()->editVal($X,$m)).'</label>';}return$I;}function
input(array$m,$Y,$r,$Ja=false,$Ll=false){$C=h(bracket_escape($m["field"]));echo"<td class='function'>";if(is_array($Y)&&!$r)$r="json";$xf=($r=="json"||preg_match('~^jsonb?$~',$m["full_type"]));if($xf&&$Y!=''&&(JUSH!="pgsql"||$m["type"]!="json")&&(is_array($Y)||!$_POST["save"]))$Y=json_encode(is_array($Y)?$Y:json_decode($Y),128|64|256);$mj=(JUSH=="mssql"&&$Ll&&$m["auto_increment"]);if($mj&&!$_POST["save"])$r=null;$ae=(isset($_GET["select"])||$mj?array("orig"=>lang(11)):array())+adminer()->editFunctions($m);$Zc=driver()->enumLength($m);if($Zc){$m["type"]="enum";$m["length"]=$Zc;}$c=" name='fields[$C]".($m["type"]=="enum"||$m["type"]=="set"?"[]":"")."'".($Ja?" autofocus":"");echo
driver()->unconvertFunction($m)." ";$R=$_GET["edit"]?:$_GET["select"];if($m["type"]=="enum")echo
h($ae[""])."<td>".adminer()->editInput($R,$m,$c,$Y);else{$qe=(in_array($r,$ae)||isset($ae[$r]));$Hd=0;foreach($ae
as$x=>$X){if($x===""||!$X)break;$Hd++;}echo(count($ae)>1?"<select name='function[$C]'".on('change','functionChange').on_help_value('^SQL$').">".optionlist($ae,$r===null||$qe?$r:"")."</select>":h(reset($ae)))."<td".($Hd&&count($ae)>1?on('input','skipOriginal',$Hd):"").">";$ff=adminer()->editInput($R,$m,$c,$Y);if($ff!="")echo$ff;elseif(preg_match('~bool~',$m["type"]))echo"<input type='hidden'$c value='0'>"."<input type='checkbox'".(preg_match('~^(1|t|true|y|yes|on)$~i',$Y)?" checked":"")."$c value='1'>";elseif($m["type"]=="set")echo
enum_input("checkbox",$c,$m,(is_string($Y)?explode(",",$Y):$Y));elseif(is_blob($m)&&ini_bool("file_uploads"))echo"<input type='file' name='fields-$C'>";elseif($xf)echo"<textarea$c cols='50' rows='12' class='jush-json'>".h($Y).'</textarea>';elseif(($Yk=preg_match('~text|lob|memo~i',$m["type"]))||preg_match("~\n~",$Y)){if($Yk&&JUSH!="sqlite")$c
.=" cols='50' rows='12'";else{$K=min(12,substr_count($Y,"\n")+1);$c
.=" cols='30' rows='$K'";}echo"<textarea$c>".h($Y).'</textarea>';}else{$Bl=driver()->types();$tg=(!preg_match('~int~',$m["type"])&&preg_match('~^(\d+)(,(\d+))?$~',$m["length"],$A)?((preg_match("~binary~",$m["type"])?2:1)*$A[1]+($A[3]?1:0)+($A[2]&&!$m["unsigned"]?1:0)):($Bl[$m["type"]]?$Bl[$m["type"]]+($m["unsigned"]?0:1):0));if(JUSH=='sql'&&min_version(5.6)&&preg_match('~time~',$m["type"]))$tg+=7;echo"<input".((!$qe||$r==="")&&preg_match('~^'.int_type().'$~',$m["type"])&&!preg_match('~\[]~',$m["full_type"])?" type='number'":"")." value='".h($Y)."'".($tg?" data-maxlength='$tg'":"").(preg_match('~char|binary~',$m["type"])&&$tg>20?" size='".($tg>99?60:40)."'":"")."$c>";}echo
adminer()->editHint($R,$m,$Y),(count($ae)>1?script("fire(qs('select', qsl('td').previousSibling), 'change');",""):"");}}function
process_input(array$m){$u=bracket_escape($m["field"]);$r=idx($_POST["function"],$u);if($r=="orig")return(preg_match('~^CURRENT_TIMESTAMP~i',$m["on_update"])?idf_escape($m["field"]):false);if($r=="NULL")return"NULL";if(is_blob($m)&&ini_bool("file_uploads")){$Bd=get_file("fields-$u");if(!is_string($Bd))return
false;return
driver()->quoteBinary($Bd);}$Y=idx($_POST["fields"],$u);if($Y===null)return
false;if($m["type"]=="enum"||driver()->enumLength($m)){$Y=idx($Y,0);if($Y=="orig"||!$Y)return
false;if($Y=="null")return"NULL";$Y=substr($Y,4);}if($m["auto_increment"]&&$Y=="")return
null;if($m["type"]=="set")$Y=implode(",",(array)$Y);if($r=="json"){$Y=json_decode($Y,true);if(!is_array($Y))return
false;return$Y;}return
adminer()->processInput($m,$Y,$r);}function
search_tables(){$_GET["where"][0]["val"]=$_POST["query"];$Kj="<ul>\n";foreach(table_status('',true)as$R=>$S){$C=adminer()->tableName($S);if(isset($S["Engine"])&&$C!=""&&(!$_POST["tables"]||in_array($R,$_POST["tables"]))){$H=connection()->query("SELECT".limit("1 FROM ".table($R)," WHERE ".implode(" AND ",adminer()->selectSearchProcess(fields($R),array())),1));if(!$H||$H->fetch_row()){$Mi="<a href='".h(ME."select=".url_escape($R)."&where[0][op]=".url_escape($_GET["where"][0]["op"])."&where[0][val]=".url_escape($_GET["where"][0]["val"]))."'>$C</a>";echo"$Kj<li>".($H?$Mi:"<p class='error'>$Mi: ".adminer()->error())."\n";$Kj="";}}}echo($Kj?"<p class='message'>".lang(12):"</ul>")."\n";}function
on_help($Yk,$Yj=0){return
on('mouseover','helpMouseover',$Yk,$Yj).on('mouseout','helpMouseout');}function
on_help_value($hj="",$lj=""){return
on('mouseover','helpValueMouseover',$hj,$lj).on('mouseout','helpMouseout');}function
edit_form($R,array$n,$J,$Ll,$l='',$G='',$bl=''){$Gk=adminer()->tableName(table_status1($R,true));page_header(($Ll?lang(13):lang(14)),$l,array("select"=>array($R,$Gk)),$Gk);adminer()->editRowPrint($R,$n,$J,$Ll,$G,$bl);if($J===false){echo"<p class='error'>".lang(15)."\n";return;}echo"<form action='' method='post' enctype='multipart/form-data' id='form'>\n";$Pc=false;$rm=($Ll&&!isset($_GET["select"])?where_columns($n):array());$Ob=(count($rm)!=count($n));if(!$Ob)$rm=array();if(!$n)echo"<p class='error'>".lang(16)."\n";else{echo"<table class='layout nowrap'".on('keydown','editingKeydown').">\n";$Ja=!$_POST;foreach($n
as$C=>$m){echo"<tr".($rm[$C]?on('change','whereChange'):"")."><th>".adminer()->fieldName($m);$k=idx($_GET["set"],bracket_escape($C));if($k===null){$k=$m["default"];if($m["type"]=="bit"&&preg_match("~^b'([01]*)'\$~",$k,$ij))$k=$ij[1];if(JUSH=="sql"&&preg_match('~binary~',$m["type"]))$k=bin2hex($k);}$Y=($J!==null?($J[$C]!=""&&JUSH=="sql"&&preg_match("~enum|set~",$m["type"])&&is_array($J[$C])?implode(",",$J[$C]):(is_bool($J[$C])?+$J[$C]:$J[$C])):(!$Ll&&$m["auto_increment"]?"":(isset($_GET["select"])?false:$k)));if(!$_POST["save"]&&is_string($Y))$Y=adminer()->editVal($Y,$m);if(($Ll&&!isset($m["privileges"]["update"]))||$m["generated"])echo"<td class='function'><td>".select_value($Y,'',$m,null);else{$Pc=true;$r=($_POST["save"]?idx($_POST["function"],bracket_escape($C),""):($Ll&&preg_match('~^CURRENT_TIMESTAMP~i',$m["on_update"])?"now":($Y===false?null:($Y!==null?'':'NULL'))));if(!$_POST&&!$Ll&&$Y==$m["default"]&&preg_match('~^[\w.]+\(~',$Y))$r="SQL";if(preg_match("~time~",$m["type"])&&preg_match('~^CURRENT_TIMESTAMP~i',$Y)){$Y="";$r="now";}if($m["type"]=="uuid"&&$Y=="uuid()"){$Y="";$r="uuid";}if($Ja!==false)$Ja=($m["auto_increment"]||$r=="now"||$r=="uuid"?null:true);input($m,$Y,$r,$Ja,$Ll);if($Ja)$Ja=false;}}if(!fields($R)&&driver()->primary!="")echo"<tr>"."<th><input name='field_keys[]'".on('input','fieldChange').">"."<td class='function'>".html_select("field_funs[]",adminer()->editFunctions(array("null"=>isset($_GET["select"]))))."<td><input name='field_vals[]'>";echo"</table>\n";}echo"<p>\n";if($Pc){echo"<input type='submit' value='".lang(17)."'>\n";if(!isset($_GET["select"])&&$Ob){$wc=($rm&&($l!=""||adminer()->error!="")?" disabled":"");echo"<input type='submit' name='insert' value='".($Ll?lang(18):lang(19))."' title='Ctrl+Shift+Enter'$wc".($Ll?on('click','ajaxForm',lang(20)):"").">\n";}}echo($Ll?"<input type='submit' name='delete' value='".lang(21)."'".confirm().">\n":"");if(isset($_GET["select"]))hidden_fields(array("check"=>(array)$_POST["check"],"clone"=>$_POST["clone"],"all"=>$_POST["all"]));echo
input_hidden("referer",(isset($_POST["referer"])?$_POST["referer"]:$_SERVER["HTTP_REFERER"])),input_hidden("save",1),input_token(),"</form>\n";}function
repeat_pattern($si,$y){return
str_repeat("$si{0,65535}",$y/65535)."$si{0,".($y%65535)."}";}function
shorten_utf8($Q,$y=80,$xk=""){if(!preg_match("(^(".repeat_pattern("[\t\r\n -\x{10FFFF}]",$y).")($)?)u",$Q,$A))preg_match("(^(".repeat_pattern("[\t\r\n -~]",$y).")($)?)",$Q,$A);return
h($A[1]).$xk.(isset($A[2])?"":"<i>…</i>");}function
icon($He,$C,$Ge,$el,$c=""){return"<button ".($C?"type='submit' name='$C'":"draggable='true' tabindex='-1'")." title='".h($el)."' class='icon icon-$He".($C?"":" jsonly")."'$c><span>$Ge</span></button>";}function
copy_icon(){$Rb=lang(22);return"<a href='' class='jsonly icon-copy' title='$Rb'><span>$Rb</span></a>";}if(isset($_GET["file"])){if(substr(VERSION,-4)!='-dev'){if($_SERVER["HTTP_IF_MODIFIED_SINCE"]){header("HTTP/1.1 304 Not Modified");exit;}header("Expires: ".gmdate("D, d M Y H:i:s",time()+365*24*60*60)." GMT");header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");header("Cache-Control: immutable");}ini_set("zlib.output_compression",'1');if($_GET["file"]=="default.css"){header("Content-Type: text/css; charset=utf-8");echo
decompress_string('*c0=@iDWB2P?H*{U)^:;B/4!N2Ch9&hJv;rrHHN,,V&KA"nRfwb9E:tfItOm[T$"DXBX~p!.VU_tTHo)6Y?9q/$mNiohTvI>+a<Y{uWk}`:y,3U4,>E(&Rg+L!L
o2PEgsnloQe<:k0oib.Mj<,:^!s
zL
u)CIc01D]MByKv5]ERUpyZdlppD_9oR;P9hVvq0j@^d:4.VmF0)NWe(|3H6L6o0Ws>bwAO`m9rG2Wt;hhRg!_Vf4_nB|@W/dk68Q<R1B`1Dm8:;z,2
U)U-,adrWa=3ExJ-QhN)_F%%ndS%Ly!><d61_"qU8+_TBHX@<(PwklcY!u8hbgk-
[;Rh`$j<M_/v1L?D1XOE!*3"aaCtLgr:&VCx-w"#!BYQEeE<?Ub!+aqVSz#me-7jAZ&6o[("Z/yYlM,,wx$LBFo4W,*sEWn"i_Dff8!3g>THyt3FDVLmGrq+phsrc9%K8ON1DXS`8$MRiPoh2TUidX#(7Q"{]y^3OK78@
M)W+3D81"Xj{qO"5CA8MS-J<&Xp_$*vN4|H#V:%`!.1
M+_Qf
`08<KQReU[2vX<g$^DbKIY*I/x
~,"#:J<<j(P[w8x_[QP`MX*51&AacRfmtF~1idOxM:15UQ]83D1aNR|ozOBHe8j34;O0RJ4YKp
E}#/y/De`cUg=;L9"ubwq!e5xFb4]pIeWpJ+]mJ$C}gDN;ZPE}3?;lR(K}z$7d1s-+vTIb(&1oZf;ND&DmhH
FOmYM
:5g$~pF%`*@4N]#@(O9`7y^$+`_ZCee5*+jIBh"5F
NwDj_!KW?!^.S^t]Y/)HK!um+_t:F7/>nk.4D=`Jk0Clho;IVM6:CSv^L;xB5uOq56C%O3B!ficBp/KWq)3qA)(sYQgvgp6<``h/x`L>N([yIQli+hUjv[[JHB1.BTXl+:d/!,zG&w?O}SzpV1`V4"Qu4Rz9@Egf)lmuP.Aiy_&`|8f+]FT7;uC=uf2$E9bgx1C8>#q8,hG8v@4I<I@(l
v>.
:YL5-(r"`K6.~f&P^Myh7>[z!x^f0AMi6xzDMd73~7Z+)5KR8=MA`b[nn"uUvEiuh_Rfa,^Qq1nBjy,Kh`CTX.wqQ1404HL=}>FjM^S:NKdKxX9hq6.hRIwn)Gt8osSL0u|l~B4/Sb^CG,cE^>Z3Hx|E5E{)uQ=J:RSnj9.,:Cx"::K(cXR
7!#MVx=^O"bN]yRVe_HFP99cSu[0y9l_pC!D
(N=,E14cE_FOQ4R,(D#>YD6+2pR/VrcJmW-iU-dl8e)A]=-RdIIZ4gwunIHQ1XBL_M#NE7DYF
NpUw%|k?cB/A/=3of|MsQ_iwT)DE)g@?30(ib#9;Y+`i<et(_M$eNd2E8//^OAwvQ4"U93&T`

4fB)KLP@Se+3.w
&e$|&;T<<
s7mC)6Q!3I;tCB@~T]Kw`0YX@F.n[CIQr~qJOj&A9&0Bn+WqTPT|k"O?=](`H)rC:C=d?0pzFYd(E@%bbi@P6j#)OQUC&+f-3+1FcZ.hg|<odB*#,-(G_;woYyY|GWh+_Hq.44TA`Edl+EUeeR6yV:H!D;RGedp#wJ#b%A[lB=Ifg_);]
y:_oKqH;VX9:?gc/P;d5X/T<b{g{OW1V>y[!Cu+%Z{DE[KNknQT/Jmp*&hZ-)et0h&VK/SPyg!,03;4A"~#62Z-N0VSB2(D(?/-Nr2qMLlq._crv+GfrNyE"$_W[g%34NR/{Y>FNog!1-it]?w.IH,_TmX#(_h+BUmhIn!5_
f<
<
x4=DsHYS@?2$^GRCbPx+l{5wem:Bk(d~#/hD#GHL&+DB1W<@!5Xq*#b>#"%W-2[nuo]J^=<t?CtFNLX^e_rF%o.)iR2wO*U!.DMk^AS"a$Fgd**`]RR6WEWzuY2;X[_JRaDqf7gnwSn>cC",R?x>K$Cx;kLcAPp
;4?M1]eW=Dd]Z~:oE|R";R+Y-L9I_;=&N;D~OO+s5dOjJ9ux>+eniq`wc2AfcXktmq3q.`H(;"b$!UD/YDo#$PI0dYV{BN],D=#(GUVzv^SbCH&!@|Z+S{o+v@<znTX%JxoK?l-E]BPP"`-)Yc,f=o]mO>rk?TnOmVi7Ec>.?|
19?m{
TZKdL!-BZ<O".
s+0(}nl.r82Z?U*fR<Zw49fU]tI$
9B,K4^5w[CaYp7rc-i#vm8Lw?}GUd]mL/_lG1]>L%NNH)P0bIim$d^&@YjyE?a&QcraXpWDe$.q^QZQjxhZ
bNR},ActZ}q"<0nu:,vQJq[WT@"M"eDHhIt9+i0.bfX:uMJFSo#Y(lJ676xE9d68q7lEfW1@),NP<je?!|F4b!517)m
x9Lar;e*diQ+u
ZT)@cy$/#O+Gv5K>*G<@23Xi_uN&,pd8TM
,XXdbFnVf]a`LxaS[<HR<3e!)MB[Q0P[w
!-MFKJ&2aVZ($f[bVS/m7i/CpZ"`y>-K&ZymMfS0JM6T/Y$&1>:"c63yZOmOqpH
<wUYgg7vJ2N9$N)3AKK<@1[n)y:Tl7@b@7.n%p:+3ddJy*0kY<MrA=CKSR.)6S~Ktrr_PHHf,Av^d,+f)C<tKm_Ava0KTs"*Uo#8[c|$guw7xw2s#_ScNgL4]vb)|x]yFG^fwZ@z)=nh-ug8#R%KS7cgo/{1-:4yiwjsywhF5!9ocMn?LB}oDB7XwfcfhK|bk5i7#ccm@Anyml[yg4cKsk~
X@yz!K}Mc:mb~EwAN"-WSa[q{O@Teflrpr^"pT7[0pkL+stz#soJ8lo>$&+M2xrbmw2BO+!GP<bG{YgS,3V
jo07hOdffL~8X6iBDE6]!J
],GMWsh`ml^i/NH^=+vYZZwZ*H=L3DK-rsIz>k$gp@uJBB2O63Ez0k+|P^hWHpUT,7Z/yzqMadT_U|
I&{[hRt>>i(kn#ZU#p!9z

^!f1GQjAs>%^r{86s,j_wgd?V6-:chvEK_#1v<,9wwsG<gM%9HiqZ
32:+#)c>sArZ&R#4@6p,COs9U,uC/I>+L%X/DQq6#u9Tn5HK>c8@)u`]poz)nPtkrv>noRP+s-61Fu:{6NUGR8?=JF_>Jb+yfLMG(&Dk,__$pB3|$b"3+^/G?ei0NPGnntjA^8t}tnhrJVBbV0IE=Id>bblad!nkq,
MpzBIrIhoo(rdc@FH0EC7.[:Cp(]+ol$kZlQdcG_0F%Qk]D8`m1PiY&t{=>mw]Y=AA]lgUEi9F"bsQ(;l=gnQ"lrwCthbg^#wd&:6]Cm=Q-<[UA;=x-UY[J:^a@mVkSjjH|<-k5OVZ|uwV5X=a(U;j$OUKR`KY>N,/~[|(r/c.r8ip!#O[$n
S1VIxh@0KKo+(ovB/;^tZ9-J
Mgk#7pNK3#$[{j$n_CO[r[i8/GM6xs!6{4Cg^E*A|,T)j6QQ8`Z/-8"%?=f0hJ<@ROf-wtSr|k5"-pKHrk9AuZ/8lkvXT>^][bg0?m-jv41:J[?6ri024s^;;qb(Zg~D^yAEgW#!HlRk_:EH}RCa2@xDWJ?6E?#3PA69bnJ`kfu0IDFV0jrB*)5xUq;,B+Fh)[!TqvcXCZaURXM1shnu^!2[y<pNK6Xx5kph56*xCx?EE^Jr[8ay1@<h}34w!$d[~.<]iuq,<C8K=00)(&tm"?3UXD^rN57uKf%hTAPa_lZiO+]Ix7vZTko0v6{RG`Y/70C)dnHt<2qxr$WpyXPr{ACS`?d^bU`<8]9yb7Vy[4twKycWKuVV}1,8O`hl0,=KN`y_vFU0~g<Yekq92?(Bsy#)B`ulH+U$`a7P0:]&i-u=O#(_+e@ACi0IAP;RFw/2c!}PX?0N+oFn}3^Z:dM+cuRko+}(RrhTmY7"@&UJ.HJW#]ZP2>HpZLV=SbZu9iiK(y02[[863NgF"CzIzE<T@1{[>;=De`gP,@wnm&:,#3@hV8
"k&"YA>,yfhwNk
M4<CxBHL`p&Iq23!!a,$^ka+mw(J(7)0O($O[bEMXx5h#cUD&;";.d+S7EUaT<uU5>*K`!-Z8@Jq3#8jw]SvmbV#0qe6UXmyAxShwmy`i-JU_
Gu%ivGkHu(_88t$&B-=NPXH"-:DI)p<0??N!weQ+W0oR]g~hW-MZy
)Eo1".&)vZ"4T"(fQ=V
}an+Re3EgLm:-hdohpG)
y*n:RYd7Krx@>PqZY0:P-;fa)L/Rc=uw4=l-@nEG[<t~n%4G)a&jaBy^#_5Iw~(3BTjO@|^?4J*oYSP{&:D?h_*M^QGLGG)D8:Y7jELo%;F(^lTSZUL{Nzc{W9L8U+9t&SYS@!DatruEJilUD4.H);rdiox=MWQT*z,(Y{;W4,MeNwpW4/
u_4ukG]2"Gl._
@Y/@m95%,wze9[#gp9:B##HcLFf+-7r"iG,JK3T_u7W`mM!^Ow.yF`(;ofa7/y.x~wA');}elseif($_GET["file"]=="dark.css"){header("Content-Type: text/css; charset=utf-8");echo
decompress_string(')OsbOb3V?!K0U*,j#-$TY2N&[`b!>wsTd_N`GuxPN9GOol*1@VDLlh_fdc430fu#lZ-r!f<.+=s=X(J2e>*"$r2geZo4@leYjQ1%,Ya^fK)KWrns9HN3Za[M&Ua[o)7sBH/u8kXg}4drw:$n$88?$
q.DLTGX#<D1t"V<MYp_Ma&R!lNy=^42%5+QTJ"M_zEIVt2b&@<iW5HXxa7"+HENrVp[-(?;l^q7O9Hb]:Sr
,WOw[;eXJ3/AYxWiY8v=afr;mm
2j7~=*!Bp~Z"dLH|e`)gkNjaXDNCg,tOd/Bee9aAhUna-ZLB;OF8<%r2e1x*xX$ZiG_Ot<kzJ%FMb$)(Q`hL2F*U3b$cI[XzX_yVm!=X`6&,RA>7e!9gn|F:S?FGgzw]+AWONX6E]$Hu$5^-Av"t[SRPD-dDP9jn"tZoFsSBWi!U
]MxVmGbSp6ix~D-FZ7DoJXY/zE9!l0/]_ZhqV=[.*yn"zS|U3V:p0%cK5pT+2_?0*<"/w-9$DgzF7#yWi<W,3"4>QoJftal+Tm>(PeM9JHTs;vxkWm9$<A7*iHsBl8Ig]>qQ38jy4P@0/ej$G,X[`Y>gf_|8q*^2Dnu#YI<#>h+;DK|$/DDimVm(m`WCVEYX1jS%84q"FCpAaU/4Yf
Q<ovd>ujL>jlSK$ADUHDsn1a>o@
;@5f]$+ZQNcbu-^=v>xaijt5[sMndunEa-5T28EWI"G!j1uhd)s:ch9c-:STXv8Dq82x=D]meVP[+d`LIY+k0"G?9H47
NBubq<z`![Z&|@7?P6j_[UcU{fnW0X^j_=5(,s<ii_zJS27M>X{xnK3M[W-rsA0k}H{mrK*vZ2&pNC@DA0;NWwLj&)j-eg5PfwA;O70]r,58hd_Eqn{Y@Ws+We9XpZFh)z(-@LIrbPy8da(hAcZV#?1X}E7dx7tw`28WL.XVqgdV!&yvq?3hO5.EHdr-kP>4[llRl9i0C+sj[+"u^v6Y#jXxd');}elseif($_GET["file"]=="functions.js"){header("Content-Type: text/javascript; charset=utf-8");echo
decompress_string('(c4]`nsZ51ptW"t=*e+fx8n;ZVpb*]5K9W0*X6<mF3cq94l$pSe;A:p"0[OR1M@Fe528)9BGzmIM4eqOXr
t-kGb7>xmUy,#qQ
Qi[%Bgn%?$*5y~:g3LZc=(@..96q]HnPQU;tmMa!b`B<0fGbMY#w$7y8<aLKliH1BiPJN|sh#~w?%N@k_UbGv&u_n?ogSa)**=ywVcjY?Ktf=!"EY$n#y44}_oJTFj?$5.jmJP#55l_&((HbO/c,1?yNW:XSA4V).+97wNo.GcWVUP@$0%Em_Tp&h^C$ckooJjrXD{.|Ews"l1_B>$X)U)FYUs-SMBe`E<.1w]=xN?+w:atRAu9@28?cFy6t0nVm=*PMYYT5c:pQ0`UuR^"GtbY%YF>6BUD}x@)A^,D[.qZthMKtOz=(pI`auj/O1Jk%HeF%q2;XksE`/(cTMgj)`{.1@_;~Q;jS[7L2t<Q)1OMYXmBt_Pm6rH41m1m1w*Xrv;7<jlw-N%1VnggNm",]yz*-q,4gXmszw_t7u)7*!+V3p($:y-4!X]E.WlaX(n=4YfX&nmY<nYqz*X]B3[x&26,cda&+tvYGpYraYMoASiO>WVTSl2E(OUN%pJ#,xrYI_ZIfi/E*^0Kw-GrEi}Z-b_sC8kgWwG5tu*?rmkZDwC3LGv0aC%sv(=mr`u?H>{<D%;jFe0&G[?yarP[l:w"]GrFY8WWaEsjWD?%4g:@L:lKiCci-GHrF;Mm:F912#:bK2I%OkTR$WTA%`wpr@.+r9A5YC/L/E69vki^G(@a.apfZ*,AU!)t:*4g1<n$J0r:y9Z=|Cfcm8".,8BCpH?2T"mGMXII^$-`
87**P^:)u
k&/ff,WfVk^=Z5)INMeH)L2LK#+4&%avx>1rm6t!D,?CM[
vKlhK1M^6R5ZafEBRfCH!s2s89pE[yix:0<]pjSEtL^,m=iG%:;UkP|7Z?U:wqHDPQ|&E@>0Xlxb2W@Uz#x*yPK(-S)g!NJ&Bi
,vccMpKkkyQewf^uDsh)AI>!b2G2Ux1`F`4Blk,[H47:s{D+2knk&]_$`5+wo(pT^{(<U<:rC~>HZ&GG?oFL7!/nYsIKI3Hse>L|F3S&r1Bmrf3EHGCk3?gbVv!]PQ0I"jNTjX@(pev5"`HRyFwm
1H?gHgB"&_+hZ,}"%EVROBp-S(zur6Yc|N}ciPtYCY.Jzdf-v6ev&vX>e#AY1HTxDCUu-_x[RS,/P-br8<{ZNRJGUT^)StkdFYLploZh:oKx2b"%?kwwh=7CZXa(X^mLWJxXve:jE/D6GICdQ<!G<i<O#qW[2>qU0jb5GL49o]L%:H|JH
P!!*t
%9P:ClE?9nbSQo,!-Ip%0-m=[6gO&9mWIIHU6M-77E)<VwCQ(Nrq
AGP+CF,uL6;vXHY;"`8/ElB)
q?>4:c{I%7|!nf)i>gLYbnEyh*x*/*gwnowQWFs$PXb:Y6?lLp~`L-XALvv@%0Z9A0;>bOq%,3d;e"5"cVIs<h`:Msu$-Ri-ZjFFQ*T6Gj@OJVZh:]1[P<&YCj;KRIhR@3n5d%M/*F^e;b{IT78mDf55
m+?T!@VheIk>8v<e+)?bZ@9(d>JKRTL>8oAK;!r"f#XH,cp5Y%5N9l0|<?PV[6*Rg~@}sKAKVAW$(Tn:%:7rsO>E8c<2xSoq
yLSG&!lF*<GvX/@"^dB>ex}D^*zs+h@udp6=K&}0ivV.~8OfgXq>Z3feK7i%av1!sdGJ4O-qC]C<4Ow
H8PMyI&;nrTr5b|94:lbLKNT?,jCS:Am_(m"4Hcs<yvPye/J!a@g*1&?d2;K:ut5[7|x0$Ae>ckXR1u<r(QGeVmJDz%$)HEX%JY2)Y2w`2m8LG0oj_O,T5
fYeA?G_TD=lkmJbZ&K*+rW6:kT;f"l;xgTu
[VCSJ!8zx(ZfY/]M;Qt@C}6t8M^V-y0>N(:p
ZWvJ9>o.dre.N]4@}-vI2;Sgp4^(d$BO$/lNiXTfVHY>j,"sbT#69v`S-iq.a./w+&x)|1BQsTG_g$_]A:/"l4Z]B_ecPKGLku+6;NTvoU+-*47WaN-<0#E$%"j9-]CXLgPn.Y~31p`KXhkh=Af(.BtWvB<lg@pC=/.IjMLdt$V$&49q>QBg/:@igTQCo^R/7`/VmCN><6{6TB1)6ll/*7yLK/h]dE~^VkTSAXD<Dj)]`(jEr77!yN0&@&F0dLUIt6mfCd|R+kuG2yHU[OIHq#YaS&u7,[dBn+
vpX3=v1I!Lg%k)tyCMS9DnkXx3H42V6tk(azrZRLR5F8836s7GDw/ia
=SP?gS9p".6$PEe+KZsh8dC/C?S]5d2aR#d+`mrV1JYyx]6<u1S>lkU]i{HWFDx-c0[Na?BZ/Do:E#qrOe.
k/"+6SDC#_&FHj*(YLxqeICmV&Q(oa1]6iuy#S=erqcz9NIO6#=_rrSQ8I%L(ZwZmxx=+0&jMbnMqylVT9s8##Zs@vA_Ig[zm`Igbx
7Pb/r!)IBNr)g!?>B&G^DbmPk2B6?c>A|@+X{i&1D.~MfbML8*yp>`J]@i[ykiuBt.d#0]HU>/"l,JgbOq,U;SJ;*%fp,Di3p.dE]cQW](C;33pp_^($9Oz9EDQUoS51:eLt^x;.ZII5C&oJLK=/qQgAfXpC>?rDAbv;)$1.M
"&wfo/zPi7h3uXhB-c|Xl&8=;44G_ZD9~6I>Ts-F$f*9ZeLT*$ad22.Z+clP
C6.8F5FenWh^[wK?_|l/16BdKybmhuR0ZnQv1m7ZT%NL3,A$F`/d+I?heklRwB:x.*m>-*rQ8O?ydvPzq{%u#5HaKB#@5g3)v(I?"K
Zv)1:ViVIrsrr-m4Aj?+{poG<,d46>,geSr/pncK?CU1(NJRC*JRQ(VR!)4v@Y=
g"lP:MM[riZs1i2/+Fhi9t..Iyz@Ff8L$K?vS!+RL0zKTIk&8HLd~_aFhD_2KuS+w,"`6o~[7Scxio?+I6)j"NG2$f54Jr;-fY[ku!O2m8Ao9q]ALiN#c8xw@6Q=J%+?P]MsK(RT|ubj02>TIgsTW^AJ{qAyW@_LGdCeme[YVj<Yiir7j8,!gt
&,PbF[
2sV?w;wW_3ca8w@%J-EW`+SX6&}olP~$Cd!$B5u8[Gyg!O{d/dq1Ba/#!UBaD#K
nvfZ(@A!1&sT,@mOi04:n9LSk<@l6rk#8ak:.h{T"5N:}*CDm9H_-j:GsuVSuNJx2?~"-s(0,)d^5PY^[snXE,]=+)Fv}XF044mD(I-<gI/71G
=l`h
1S$BUygwvG;[U[&9j*RRP8$v9M:ocgfD+K{_?_L8{P|+E8B@}0z=`N7U
9g!"_[308`61[9nIQ"!KmQ#+N_-<mu]d*oZBRt11wxZ~a^9Pd/>h@]l3VsJdt@vY95cpgK[z7yG^LEYO&[,:315Rg~bepw;qO4dD>`F76,%2hvua`"$ig9O]b&T9yTQi
CS3B5LrbX(zMq9zmUld[U`X_,"JA<%<SA9rv53MdJW*1X_eQ8[%l-+9:In&&BB=Ur%T-Uey.4KR>O.jCI-Zp.g1
0[8+c<ke:Ahh(>jS2O8k5L$w9M<KvaDxsaZlKV_Z!fhMp+.n,y}:[.SL=+0(b]T4Jb(]ZQ{a2x%Cp^K-NJ*pDA@v]2/Dt/K167[j9y1[f8]^;dbHC2p`]3{T$ioCS.Kf4rEIDB(RDuL$1<uFS^rY5n!F_U4.LYvD~66f~PPV9>A]w.f7gd9a<8-NuA7.5"1Z/f7j7K5`E)DZ+yJN}.lR~#;5.2[tSX:q:d805hBZL2/G2IGpv%4UIcJ_R>C]9$(uUe:i0tgLb!y?
b(K&[-cX]J^T+re0Ji#,;b/-AcpQlc[oR*nJ"UccBF:rx@-
c[mVDmqwB0`ro-73sZ@^x:c/]-+vB;T{4:x/4zb5vP)p/rv~CH>18aJ{2[[$>h0GK/O+>a:t9NE;FLx0rqw@^%5Hd&x#vAj0*//
a__KIZRVq4XF[k<]N>$]hrcn_yO~4AHGqq#VGk,kPy17tPJB2fWt
7x|GF#%8rxD
6FQQ%#TfUfw;4)rC1!XSVu[T(?;VMRv%Tt;rgQGA;+c?jrV)M2?,!g#KW5aGlX<;pWp=!qR=01.I]V{-$FQQgv6A
%52ZZP"kDy_)%A3!lLJ:ZEs9Ow1d)y:B!;%aaD772hwI]68L!8H@=kh_^y$e/a_~<5BlpOF,J,#B-cI[#=.6S9=*?lU9T7={Y,Z.]=hIljYkdl@OjJ5N@cDY3NUPZ)`Mb1=5=M4-^M&@=1y>7K6s[P"NO#ql$(G#nXM?@S<%,6XG.(8IT1[43YU]m__D1urqdCL3bxvl/{kG3/2rcdqY$)"Radh*v53V73rmRLO7ow.87G2$1ZM%CWLYN?yPv0vRbnw7-WXUtP*p:U*IC+VGIa2kcBX^*;
uPSS<=3[]hsY$9+T<
>GC0VCcx_qaKd,dm&wK%KF]T<@rt|<}lUEewI#/k]iq5mY22z5AGogJ=c01s~(yC
SmMe)d7#if;9hf+$6&E`YwGH0"t*eHR4kIW~19o#dY)=oU?dD1`@YuR<koMX9rdpU`jLMMVaH7Ci3z+{Cc@J5Q<45m2=yF^8T$0"L6J%GE7Uq|IMktC]9.4JjO9F&:8z8W]G#xO+mCQ%jTgWbMS">"@
pKV|_VL/O=b[NMLKt%MIq]v5HNs=82`fY)aUECQ
5lxZ]582f+DpNarDjgk$OGj$y4hqw~ZJH%fh4ZpF4}F&K/YaIQHNw8)~KRGS4-^<L=qIt<>8(W1f6VpcW4"R;zAW`W1en2_!NsW7D!7QFm5hjVVOf@A.Xz;CHH&mDUJj9j1<U`nlbH&84rRZg;2"cA.|2vg>ZueJ+#Na3iF6l;2pm:@!GUj1)oZP-z0u%=.Uo4A5
~n0Q^ai^YQcV3`rB6I_)!VnV399sz_^d,@Bq1;bh64X*CG4HQs3vLFca.5T0Z!2Y-uRI-["DaCDKbbgECul[;csN]_Ey0,RB5
Pm6n!-,:[o0^FZEtMp-"Jtk?SOi4^eUb.]Qj_s2ujifT7JyVMgRs5dk#yLUUL83hYfUv;f7"GI9sFC=MCJ//iP7gD(ZLNP6p+Bcu=!X"#]iorAt+d>t,.s`W!Krw>W.WErw$3tt(gh]y"dB;UTX6&HH8WbM^~$7F(q{t"18h_1K=[UgR*0Y^J(vaH/kl?1JNQ[c@y2i_yl`?}JH_2Uh;RWOOL&hl9ihqZ35.2tMZudaAiPP`L$bL>4zTW@:^if-"U]g%di{/I5&cwlhj(x7aFUnC6Y9n]h^ev4|?Z4p&Ivd*nc@]C:yG=$o
NfH[.ioSaHegA3~3ay:u6KxD9aoY#@5M~PIt@<LC<ak_ze>t$JJGt%0k76PG<)<EfE,U,IX3}v!:THoFtk.;v5#p9uQB,@~@OSMy[,E/oS}B7J`<S$P-%SCh~;Na)AdX,cp&zWJ?A]!+R>TPm)NUH[+!t6/IfH*Rr]DpgDP&aCq^mmz6{g`/VLAv6j[K`HuslRZh=`S@*>%D@lj/~r=C&Ufd7>:SdZ6;*]SPbM3rv*vX.
(FiCICLD2!7
iRaCu
LXNHeQ!dk..:D1oiTkyrAO{?=R/<>&Jk@ku9
SGX*l,KIlE9B2OOl&@KWTpIQW*poq26IK*x,Jz`vgK#*#Ck34>/Tll]-thO2ys5"tc2g+7K|h;BC.boA`yMm?Bm^S7b>;a#Z%8#:2eW!I>#)-hy{#LhJxG?X0]s,*%(7$M_6AKd*=*EkJ#[DQrWqgQ[W9#T@+hOknMxexrv~8"82MvrP,CpFsMG,LNEkj3w#8fy#Btsh6|Ui*r%2aoq$Q"8JUyeNd?o?u1Uic{Me?{+1Ekt0?m/B(r/?wf<B]s/3hkPu[s,s@Wm.1md;Gam9#x<d4Ir[6-QZf8@PX!90.0D]*wrgIt_=fOehTG4y(UiD1Z9Xe>i->z?n`?#X337>B3Y]8QuK8>E|^h%g>x6_Zcu:Tm8}*uaur,4Du"2@J$(tpDWl!)Y_EP);&M_/_6)U8d]@2{dy5Oz#Hz1v)i`Y94mhM|:A#lyw^FZ0JSxW&p<42De2P|5}de;/JL`/Z?5Uh
*mXgA-pO:1+.uGbCLs;iJ3Zev_>FEI_BZ&f.Am.>/]E<jbRb)07K@w,adoWyAU2}2Vd_@z[eXpM>vE(prv@D32uv3x#o8MDDmO*/>RP(,m_3j^iz"e<@$JLxuv1?^")f3q.0l2yn"j"AFo]i5]9}1pn!`^nj4AFPVB]NU3*c5QEEJE(@!+T!b.BNFF?mfBsd79OyZe
aka944t5M#MS}G]k(0KO680r+d1T.&6LlO2N])/8Zrz=hl7<cI5V_s
L
`AcDsJi:@T<y;CuWH)KyQHGn=R[/t,lL-$]?y^&I9A`F"?2VY=QT(FloWr^3!e!lR`>.Z6Ha23"q4FL/5TKe&d9RH]DmV6%<C>mnsebK9P+w!a?Svkj>n`HBw8fgbyYIkb=8oWpt*MZ9Z!]27.1sp;CHrik`VxiJEe=!tnC5sFy|;2B+Ue"r[1,$20!ie4"BVmhj_iHvPZ.(9;akk.D4q6TQ.}6,^}lrQWQ$R!+
/F({6j_uvDSI*O`4MI-_:9Zrn{bwBnpK2LPJB$i)IiNgC
@50WIy5?wtat)&&%%xE@rOHjmp7O[Eaq=}N2]IxGI
o][Z5FZu0AXgCSfn@IE2?wa!SlKklr[+Oa(YDX)!tIUEkQIR+_Ui[-_bAzi#+Xa|AEQps;[sJH
$^uh/H(@+h4@F[^9`M,"&#^pt
EgwPaGJc4Onr(V>6YvJ<r*V>jf%wNL|n9wDYf
<D
Pq0)u8cU:OuGS!iZ@"-?.eUksaI==#_@eSF76j?P!r#ydHf&]e8{B~p*Lp:nHR3,CW%FCbR4Xq
%7qsKkZ.KKu&vMrv-Lsf6>*QhYu?%qX+a?m!;BC
nnxSI"5q$8kO19TpwN;DXA)yu>}uS@1fdFX0D)Sda0gde>E[b@K+TC++V6|hK-tx*e?q^;rb,CGZX9RDr8IRdECcbmmw"IT"yFY0gr60X[<%HDsh_3q0X=jW.:Yl]G;^iLP:]_MNt`Q8M(XO1vpTM8I(pSLJh;pv"370P,S`A_SpUGb>IB0"^$M#%wbZ_=djJdemd&F,,8NEr@T4`O|kJ[c-5h8Al5qAkw:&*K<+rdo$FA
,5kLHX?788Ybxkl5Wlqw6w1DeUCj2m
D3gH>R`[7aV
@<[#E(GJ=7W(zY
4?R1_0JrZ;63>[-F(bU8Yuo_+sG*hd]MW%IhUhsok(bN45@"B0cyG%mwM6:m<1Di^_.t8A$&cH+I#[_!VVp8n_7jOLnWvZ2(=~TxPbap)z&uQ8b=VsEG;.e8A*j{<<15wr7QcvSG:^E1Z{a
/(]6TtG?jV^s1_[*viX?h6xKr>g+,Rw`8~NlXAdTS{&e`)-}(."sC9)`R_1%trHGmvT*]Xs72SbxNVLg%S`N]FBV!&yp-:[wb,uWbtYU;qFrdt2=xsOCkia5[sie--3O(&6MnUi:qb<*4M%|%u.t);,K`o@[[iq)a#LUV3#z<&hLJGrK9%&tf;SdfA@Y@7&yBOn:%L`]L7lsaMsVh#,[i/<7nJ$0;z_t."Ib.pC#Fm4@CMs[!Fi~i"[Gs8M04[3`iwOS,KD2,/$ZwH2$r4xrVVj2wOvD/:)pW-hub{0UOF>"Q}5/I?d{m`]^5!L#>ls/
;M
q}k~etVzE8&y7u6[,}^5)ID;/C<y0)SoF%hyN/>S>bJ9DsM)Fc9,o50Y<@S+k~]Ho];b%j-cq|*?[dT0j*]^joITx#n}SDybuvx]
>ukH?G%,U-S>t^>"T5chmVek@Aev]YV#4vx"co+vpWoi&l~3$_ZJf"N0?@BpPY"Z,]+_4ISx{K>iW&.8Wrjr?lDv}V*?w5o5w89mUbHEEgEL^nCo[`q>TX8">Wj;}?g.
/mg52/B,(Zk[$tH
x,<)RN@q*M,CY{#=j^RWOQC;l/&HYXJSw>ck;$Uc9Wb/jwDq1&^?*z.50R&z!H7^=XS#[o:17}&%5;M`-74`7
v
3deblxV^R27g2pl^nghb95eqoxT|(b7+81]a$lNFH
Da*3o$EN/"iC8!R&G*R7D!)Ux8XlBD1>P8LHD]E5M~]fNb9b)~x&uU:@jGOZhB^PD4^@L
(r,`c0nuS*idc59H%N&WDghd$5t*3|>du0us,S0QLr3a_W4iO&`#Z43XdROry7D8A1PVwDk]@GkPJ(^w>br43#IW&f%kTb
5QOEGCF:{&@X)Tp]#Tswm&}-*w!m|3vB#Eg8;>h@tqVk];%L(!g]:Iwq*tX)HQr
84r$9,<;Rbj(c2BOw"=s(L&tW/L-a+m!YvcQ`?#je=e4X=g
C4at-
)MDmofI]@Ob2~TS[8EAVwhomg-y%:VmFRR-3OYA=iNXe[Lc%0Uza!RMlt(9H!gfjA$c1Cy+IQ`K^&f{bZD"^u3sJ$H&QCOlDU"L!wbx0Cf5NXkS`4)AyKnCG>EVvFl11gv-]4awq6:rAR"=[/cg%
x3VRF2kl3WCOGv6m"@8FDi$Z!KMgIEu4GHwGNnVh_c]T9*Y3#?2."{"<SA[_;.J;)iZRiYr%WNQ}=$E7E-AU`pF8o"f!ZZ!W>&E{YR<{!LfNonCX`p:%G>^p`lkwSut2+u&U7}e;s"K3R.Ocgs/!X1Zmy+3u5v&my,%
W"4`
*#UFq9y/(LS?&enjH;,oV^az%[lmbY@)XSe3$@NEe!$+rog,APWNMAE73>ibeDT!P[OLd&(&u8Q(=hDfpKjaqx!(Fq(+)_trik+G<B=ZzN5S)SH:i0CiSp1"kP|-_+$.9*>gp.S*=7eX]r]1sJL[;ma`l$R7Pd,h?/o1su0%hBl8n)Wc8k?w|<.Qf>=N4h&,VA}S1K2TW.8OZ1M
"dcTW4wc]HV`Cn-r8syx,QJI>>vINc"vvi+teT?,w*(FAckxCD4];5f<<]/eGuf@iQ@UgxWSq&Mh`lP!LQx/JTd:bg;r]Q0^>+O+o!#g%f5^}@`9/F/20@J9xA$3^)C^#x2i7S_gKlb5zbbl?pAyIGMMH/<-CT6$Pbk#p1RM8kvDPG*H]8l-v[Ae-"zf8[dMgvLKl/^#k-^Va>{w{"&9;.h6N"UR=$>L#;RAm4j!q2J,I3I%Zy1mH.C9o:7Dw-L&_,k3U8zjE("l(7hg}p0B},4`|6cy-qx_i>YYy(tbLYCLX`AeygWj)5!C.e<t9I;)-2Rp*I]
N=2K6l_o|q<EcX0$s8XO3KpWb!F7^b[g3qw$-C8`BaYC>M^p@Z/Q~>9PA?7A1UHVkDews(0HWaeUv;DJ/6}
{:ME,X/O#R7Fynw"bYIbq"&_F2@An$gh`qe:b-Z[.Ayh*GC0io.XQ?Yx(a9W-jO_uJaw^VX-(w?c!`U]x%@_@BiGT-HrY)Xm0tq"4M5Gn-cmAs~^Y-u`PD|05McY*mD%Pb(l`>0l
NLHSaU1Wj7YF/m#n#{e%?Wft6j_[f>p|LGx*bRI`t`nHP2wL8rEf*tYZa$1>r:e%7_E`W=Z3e1S`al/J(q1t`d`@&?pz$]-nFCr*xVieUC,c;6Wjt3>Q4&D~unS#kw,QBB25Y*U-&a/*+PK)]b"?+*j5:vaBQT(#Q:IiD3R]Q9!:k6iX+T`oI#,2DHPS
o.!eGpM+`&f
NfI+?i{hKNhD/v!`r4P?kgk))n9ILb%@r-9Xh*LL"sto.tqc%F@.TYxsI3KKH-1dC.8h@6^Bt(((PH3Rn!yaJJH_GbSHaBSl}/0*,s:aF%pb%b77lsb=r9PX&bPpz;>WW4V`>k7h^kjy<T[J>RPHpR.pmVi8rU98@VpAG#1q7nSJ!2/;B_PdwHyO:EBS>K=;B(/8%_-X!3=Z6;25ZF.gxs,!"Y__y:^l`M2J3NkbIbBr,-ep7-T?GXl(vjPY8@N25SpY<hnQ`Kw*148C^"L%{xp,7
EMpogj@gW0vmaN;X3aoP)5^gIGf]x$&lTpN[mHD)G`=(#WU;=VB,LT,#0Tf<a1A!
snQGO[-,!YZX
)L81:-ad0^LY6yeMHqbc>5ZhmW>l{7DXB#hN#et.U)k6jtuhE-BQ6Y3#Eq/:xIAZ#`[;5^>c"73qlTg:M?%<eP.,f;&=mqDT/PcU<h!gTRH?AL.XGZTx12q@WB
0o_>5`SI2,Far8R`*;UuKNlnvb=qEG@~V.h^E,o<SyT`&7(}X9s"0}[xo,aGDFtm>yA|^|JYV=lFry5s-XT7>C;/6?75pO[e4#E?`NYYN:M#t.u..6I+ip*4IZa~fi;t*X(p4"+dZg)Q0y.p4:Ndm2^p:ZE-(@$2bg7#<;2"W|9EPA&hlKYF1<mI*{+}w^O1b30eE/]~m#G2D3nDZfmw,3F^:OsHkw2]swQL>SU`x`2{2}5f&~FL*#BD
`Aeg(#VV6Gq/]vz5+(
J_E|
+cDM1?xXas/v
z(&cn|mIlLN6N~FA@>z#bEd^xt-f>TPk2dY@Pu^Zl|*.#BnU![JC&~w[yy7Id#HM2(S]BRmBs4V4j}@H@eNk&9.68P(eVN1DT|gf1J]"xo3M81Q&,v^I:_m~D.Zux>Nn`qKsn[W|BgXR-55Hy2r@n?XmXYd,kpxJnr:f={m55(=vc@NXGib5!?s%P5tClNAb&-hp7(6WtA=#5=Kmt4b
dbM>+COBJ1V=s/&bS1O@8n;FbTWM3yAufg=T+3TzCk``F!`}=6OmWJCEwpqz_r+o+4q,,|n-a.DUQP,0P{kcoAh)j`-;]yP+&7tL=Dh``!+u(I/C`BQm#o[r@Q&ORSla6?#O8GtI9V:"K%RYp4^."tuI(f2-h(-k6Kk@z),6H(`PJ}#;w?ITXkchRwjEeF6O@H&h+SbMa;c}v*NWc1NsRYtt6d<_GZ&23T2fz&v~T$l6cG:!g~GQ+GBt@F6>:mJ(4@2]QK`8&,q~`J"I_K0)!*vbYw4^Q
5,N6bng@)f/|+,ME`S-+$5#ZvQEH=""5-Byy#.HLf8AP%PX^ooAs*v0UM=S]i>Z/?yGDens3g:5pvBTFh&XS?*s/PgL_itx"Qc;aZXsn7sQexxJB&^"R>JIFUFZWLwa2poH*^p?/+D)lMd;Hz%Kb=GL;Fi!Mv@`@jiQscMOxq^<Kr2`xr)Re?V6B+dwZ?+0rt@e~2^-]nPhFU<(SR:GvF%K0UEf]o8K!^;?<nX$(M?-sdF+`]FU<XZ.2RV2!+tC`%2QOn<xdP"u;+`<ni?C]lb_Uo7;fNz6l3tSiCndy%r0n,p+{:@/VQkM+YY>b?O3fOBX_HgB[Y~nytM;c^uRq!#E"e7N-3jLu
;VI*iSWo@#z74aHUWN$5Ae3pE?B/R[~pvck%*<>GjvkUO6+<x!zJ(s|pE5WP0y%MRTuu/Im%u)yv;
>JmnU"d!yu
!f_je5y&GmX-uR*&2R==f~,kEoq.L!7cQ$vC^B27ln(5w^k`CNwsf/DhmpSJ^k05%6`Af0T{/66-2
A.=%Ay?awi?j:_#9Ln
&$JDZHpMm3#//h7(vehN[P^lS!V=#?x*.p/it[X0zl>v[(edj6)e6#)p![fHG%Xtc%Z#,Aj*{I~<.!w31a3.3R]3<q{)pW(ii(9)|9qmMcT#D
_EjWOyB>ckls!V;]{0},eesMVSu[ie
-qdmZ&Md2|HvuZg"D/ZA%tKk[H_]5YQoA[h2`l2x!e=.x^+N.rS}Ilebss6W3hX4AL<R9=UzX(L$m4*c_XxrLK,-:rs=2Um~"q.|N#^qw=4x&VPwID8&Zy.QEk
gt"o-9a`!+UsQLn8Q-"CdfEe9tFR;`>mp]|LNQ~QP#
or,@nb`Jt{Wi`&?X^u.V<G=#Dq]mW@X78_)]ZtK|JLU1mkS1SFjqe:(sABYvb<N(^e5aq_MujJ(ZGx%2i]S3-2KZgMypu4:Fe41O;CG"K!pk7b5Pb-ZHI+!w*EQwbc2r4Sn*9OkxDPk`K:kLFlSZ"0/]HY?Gj5/32}x}"4imyEHN^Z3|t3h"G6Tu5+ff4a-T(Rdzej=
>P1c,>th9H6
U<8Ow.r6fx[Hk(0Rr^+b`k&.7@@OMW:ceDrw?#i%UtWqN[m@aFO1R^v2fiM}<hxd)dDmyR^LW7bSn"UDcxAI2&oF6e3ze{/[Ac:Q8N]JI)]#(oQxb$o3+8f#vu&_pl#PO}T&0~e```-ZYi-j=,N:kvx8ClPBgPv<K<Srul5ARawoX(D$<`C!$WbGN!J
Dbj|D|!}#[d?6T3
=x0to?ROEawT6klea?ft;+
:K"SX<
Av3^y]eq2LA6$X8,a3?B#{p~RB9nlAXFstZ:[&8en-fDU5(#:f
:o9Bb$B[?WEaxJcRYr;BeB)&thsm~O4E!MUx!YdMfek"*eQP8?ZSFcLWD,u;3x4C?QX:x&a%Zs6jwq@:O%c0K!{8|"{NTSgC%q/1;"6r?50SOmB8|3X^d)21D]@GmDo/&wsn"Y}YeZh-X<,)XCp*)F:Wh2Fy>b,"*14
QJ~h<n8&Yt|@^;2XypTc&4`;dlFeBj3;1m`#&V)hXSa<x>].#9c6/Z#XmFC2FJSqXXrnn
fp2
E2Kolq{=7N}ih$O(phw<<f<qkRC_g3L+C7~P*p
GZQQSj6,&wDEf|M#=0TWxHh>nz"Uwh[-sDcJZ?52t`57+Uq;jqA;6eH}VUpLcWF[Jj#WGd]]^^OL@1%G@,=41](X([EDul(l-^?tVV/F6ZIl<}7ovdvP^A5/LP+ux0$FEsf[ZV[uby0;@GgQRWwi7>O:A}x,:b-c/AUdDV4v>;A+"z(TNgCUki_ebp!UK&[;;e48owPa#*mT^i-H*)CEEWoA;}w,^mkA*^D1jBWC%"3(QY+sa2hyjl%P-<QW7^!i48.L:Q/K4>SQTQL[(!hQe99EZu=QFv"3i5&GOp5,e4RLcbUE&ZKt3uY#R*:pR6&T-.Y)y]$)Nu`p0<jDc:)-)l1dyO0R&2R`ErK.JiOoF`4!u!5rj!qoi[aE,K#lByC#"LG,]_999r"T;!:mK%Mnsf&u+->ogGBu.Xlnt51LAz$,8_IX^l<(%(7="1Um$~9!@tJ9)Bv0p{HUW`=CwDs8.Zw{^_>[UtB+taXwtY7dxfh--~eKMTke4(nwa[>:M#)Rkjp.6`EhDi"_A&,3*@7Be0/[QnO`,q2T2T$cCYymS>aI
R,{la[5<`A3Rz#+
4WjAUTz9cST`609eJpA^@4?bJ6F:y)H5Ia!jNNUjm;=N:[HEW,7oxR9L{@U]NO7`r,+t`VT?YiJVKWf`vK6c``fyz4{jj(JPeJC+RI.RxsVlNG-T(t{/LK5yCjRLhx;=;!Ow94>^;^dq.[j5Th<H0n4cGm=u23tA=QOAdiQcE');}elseif($_GET["file"]=="jush.js"){header("Content-Type: text/javascript; charset=utf-8");echo
decompress_string(')hk^;xqDE.!v]yOYu=(i
Y)vs-KL22cTG!n"$h%T*V$&4/u<1-IIwU*8#JP`gsN$1g@6[stXya!^Sy)jW?D](JXw9HStw:`r/M`2pKZwzg3N$:QH#e"4tutpAp-7|E)c
l2yvhh%sdsumvY1&rnK|j1B-yt[j?(S=bZyMYQ,MY5T2;Tu$rbz#j5!#l[aqAfHbcBM&+~xdunM$gwyN$!%y-;WU$fcAq(7tu:e3TOXyrmB}1xB95$J.D9G$lYcP*!K[>2*Yof;_)H6;sbZ<I[XHRFD)ctKmHTnr^U
ij-V|Mq>![hvbcsvmDb9!R6yjqS7QPUnd&ne>)0fp1UA`z(O8v6
;wb3YoY>.R|Oi.4yoQ6L_V+#Ot
qg)k_(]8rcr:y]UcGpYE[W7|uT5AtR(av^I]c:4i:/jm9OZE6-.3o"k<v*iSd<t@B?l|v^l9HQ04x0Z%/fdA$HIyyVc64K?LlCFrCZQr"DCSSVy
SpLT@@&8Qlq`aj+|)t)+eh-uwS,xMA(4Out:,pLUwi<lXFZFJ8a
`g[4BX1vX`8X2$k
@zZg=JVaO;q`oB)->-=3s3IgyeRn-"3/-a+dq",
pac^X?u/6<9SEDwqHc%{Y3!ECbthO1<lmjTp;L:T=.f1j7on[LyrQqR(B#Yv4H:,/.0vak4BNV3U6LBoM;J]],7HFvGH&(jG&bxgbe.4`]eR

EnKyO+n|e&#gAwq}jXf![%;J]_M?tcJnh97d=|l|8zIYN7xL=NQVPM$;a;?*I#e!@#tNut_7UliTKILmx/$Lhsj~^N#-M3XxiQVEe?0<+pCnuuI7eCRi
-?g
a.msAWfjO2$"XwoN$K,?h!(tJU(j^$QIJPtl8oGfIgDN2Q;^5Ba^1dAb.#[/+a6j3`,SiBS]NObmY@oMNPSz&#F<(r(5rh>?G]U[Rc-x{uu%I7Es|<+qPV)aEAz9,"n90492P@<Be^:<`kksvl:=Yn5=>>LlMBm?Pa6wFe,Y^7x.%k#Sr8INmLPAUo/CG0++|T?;2?XEh%eMoY>FL1b<JcAW[>UY)&avS+,0OnC@iy;U4)
S^M`12NFv3s4.T98uyZt4g
v$IZ()dsW3/E9[_#Z82/v[5<AqbLId6yS2+nb?0$P4vog2*jtNSd!dB^vihx&LgO}JI@&rpHd#><&tEp7fne_Mn!2=3HdLT
E4AR<xJ&xH{W`+>!Yue"zfJ74d2F}2&;+%O^d,P)J"FMS9b08>zF,bXM~K-]b+Ltf^k<uOk4EUD,C[&IrZl?^,d$=KQ^=KiUDG[^Ej7D&P@)Vvk1)"m_z^h%6=M0`Z<;G4F4eSvB2B2J+=p:l,yV/e)x[i:2cr-U;)G?301d~8g(QIgYlGOP?JE)h_T.33E$DNL!Ne)YWA;T]KIk[>PBZ7v;5DEspHsx}#FpxdyB*
8E{iK)J=j;txWL*Bh+QB=peDHYq$Obc&}N=W`3z?c.$N-N,D7_Gr.1KN>ur.RNUpY(UCFT9[^^F;3j:]."+IU2MMs+MWXluX7V&deAx[&e+rCJJ
W-MKE0T:GN*/>Eg8QfWEn)xp[kWtjF{C:0,`v9AxNo%5`@,OxM6j:%
"+$z[{A}4kHEMp"~R1<EVclaFalN%7E%8W+[#7kk*p8$b*]dR>2]N6dnQUZ|i4Ehy!OMO2+mw/XZ0kYVdvF$RLLIcdZ>@i9_Z^os;~=X;|%0.Ktg;toG-EmkB$gY2O2Supr-yRokj31_7S0gOA+=%*p,q51aYEN4"~@`5=>Ln=kyNPh#)"8_!9u-+e9I=.N8+/S:2qJP@3C/%A5A(S3~:HDNX5ZOHt6DNu4:V3c)0Wea;7U`O$XG-X`M-!DDYsoU]F3Y6=-NpX7i->$7V-g<poC}A_34k+0??><o,hAj$_X}MBM4EPx!h|MR52]>:bg6N%lM.)v:I50_WRTK+CB}?ZhVJ!<m-QleCu^7ym;^+w&o2n)adR,F8SfoivA*^b0?#4mZ8,k+V3qWuz@pGjl/=PH?eTda>VH5%V_kT1+F-N*XyjTy",#
&JS:#X;CAq4AIZY(,o:B?<P0NPTTsw+].vsaQ,GZLK"Rf,7`daopujpBSZurLd
/"#?I)pwQGvCG%NacLA3k`"<C"e"n_T=};lH;pUs;>@c-nf>ifJ8(-?T(yiV*;se9rlaY.ZwJ?VSe*~K)A=dvRPOz]6$-qde
?~TMax(qCj6q3uY$0vBw"WApnD@;O3odW0=JLnxBy?C5N|67N]:2/hl4e"!o[>4qWO:^uC1/k$t2JFMkK+663epxS^2ZM#6be;_^NlV2]9AP^mM#qBKL8AxlY@gU7z"gSV=8&Bk`jeJ+DubV"&:5X^G4g9**c&wW!C;Akl`qlE3/T`s$:=j`bG$B/EhaxyXRd1_RC3Dzpfq9tCLpsW?LB
tRql$cIQMZd<F]Dr>Yg5&"Yc_))[Dqj$fbMF!>dVfi-)K._d[e24F4/p9L#5)`UD-Q*<P89}T=Nn#8(|mQUYy:jUiX:/%S*3OghvA,CeiTC(n^w4.Es>j[LW&[6O:ZPwx%n^=a.`Fc?uM1^R(_p(1RtMl~e(Z?bH7ppQ*Ii8oUIB0*)$]=)E;L+l!0u0tx-k@q"{K
;gP0#:"g(i@7VtC7YVw5qNhWN~>"K%%MK^CQ0xS^kWjQWZjxaI]TH`I~j-YMSM@HdNa-U%$Lvc.%S.k)mciue/bmj}l@Ob1d;[D-("[H;UCa95IGkrp>@;l3#Ss-Y/UlrX4HOJo#[7BiAXuUeP]k%Jht;O!6/B_S6vZbS~otk/e0d-3w=tLe]+8nBPy+-*ABq$#N<+TK04^j@6`NM`Z
y9U@k*,7Q?#*>|dVCFe&Z0-hd*sl$JiW/<k%_kFVXsoLsfc17*j!99K5(r>;aQ(]ae*J:lvSNRVVPYE]Dti*2:yHEj8I^09{HXUt:lYvTa_bq?QV&TAhXH:i2E)Y(;hbtmwZ%ko?<JKL[1KCSB:Ddb#ZvxuIS>Uw=L5(=S,BjS>:c1PM.KXI8H/JVo])pUmo(9;@9]j]DN.0oy"KCNJa^xc
Ltj8[Vs$jCqEA?%5lKm2A)v#1?d0mU^^12Tm(
Y*6i,aM2)#9S4,
^^}#BopY9#}IOCA<,fSF1<$W
L5m:!OQOZ>Z><X#>p/SRpyF/
ARNgpq=<[GrUr7uGS2P!h$|.!gQTGg?jjo.q$:NlfH9[w4+a>PJOid>10FhSnO&9RUh5JFbF?.E-!8hQ~^di|Q.]Q
}H/nUr1/1jF,@62RZHIP.9"kjw9sGIT&eH.tP&]20:o-IG`M(XSryi#b-3Cin-PRK7t!<I5&DkOu3ag=#)f<uZB),^f9G6soT>_j%KeGp9QgWcw6Xc+-0_i`6KH0kBk#|gXL{ZsE=]sErnh
J-+Ot/|R77CO(<@;sHAiX[sP(`JG>^v1(9~^M2/+`e&DYTO1_w^YlAB)=4Lr!l)I8?mj{?aNUyD,X02BRwr7v1Q2,)|hWlt]u=`AT8~/%R54mg!!aMhUH%0`>K6nU1CN~!CU#Y/A-NAUBM"M?W[gI$K)Zs$[K>[-G*%r*xcK:s*+77Y?Y.>
=qq?uuJZ1B}>u!-DJ=]:J[]>Ce/)YMid3Af+{Ui6kUhQ
>WB&eJ"nL>uQ#Y7VC-Q`H!;9L&aE.0J{/{jKO27s.#*t/~p]`3q3!voT-E:RczO}83P5E/^vgBb>y;Svl&gr#oSQ$f.6Z
"^B~TqU$)"JMLA8d??Qc>Qx[vQH"iI:j^5`uPq(ui4/+ngP""e!lZ#&kCC(E^`#O;5$|b/c
]:)$[-4()m$cG9$pCm-HZ~K[vp!hk=D"R/5Rk.Gkm~Mccw8h[/4Zc"ZVojM=RC,36A%,5
Ls8<Rdu1rF8OK}5,.mt~l35On`(dm/,Igk"Y(dy6z)`%.EejrD9xmBsoG`eHg$R|,k^VP{Lmg=i"X~gIgPOmGRg2^NPQ280.9)h;pn>$gYH1bJn+HG,gFTycr3cL12_&PMQ?5~1w8&?^MC_;sTB
e8`{=H^ut5A%E1?m;cO0k2<:y>I:e?^+qTj|eE&WKhq`"zHB#Ii2dES=vRY`K,o!+jB^413re>x"1c;/(ddrIbrayv@SLTBGvsq&/[]mXtUH=WKV@T]/?eZ{nf,iLJ)>R-i]C)u.@5Bm-~GQsN)TpKJJFyV=[_
*+rF[.{`K4u5OHNP7-Anl!,@t-T1e2=DSiiYpZ!1L0*jQ;60q.j9ay,a#oFs(ZpI]Q]XvvYMM"+fb`#([qPh_5[rFNrF:1~btFwfi[3I;S
w218^?[^U$QIgl1S*c_/^?))Z7[}k^mz!%M~kbD)4)w><Ro$0cUZ92wXC;:0nslp*Dm
)sV`92h|<4&s_.=L
4GibLmM&apO7o=j>W0YNt+i;@bP#asaZ]:y<Q&961vqkppbE;4
c`9]>@,=-@cKYu_ZU>f-B:03p->l-{u]?9%&;6WOXcp`G|6znr
fMUf;2E;EDWZe`j9=DC9]TNu3[&T.sp:4)f4!GmJBDI2$JqPgu3"_1
Ik#quX-y7[;2TtxGwz!P/?-v5oFDHeR-181ep}2Qlih`,)ob1F1efldF8<G*CT2$u7R;Kx*v@|DSQHNV47T/]y,/d!pS<<t"3t:.C50,Qj:F@ouEL?bCLcbHF-Qz-Mc8HnYLKR[Y?F<5YybujT0]NNDBAd>T7$e;#O&O5f3+S]HmF#U4b9SEx
M`*i<|[kX]@-U
jhId?vC|^[4sy&^K!1aHks+uM{ET^[<v7FRXysQavj?}TnFTwo,6r=,]
ltP`,b}3`o!O42*Z1B8gvRW)N4<qOA)q@=*b6A8u14`pW_^an4@RY#oVCG3[Enkp})iu<]#-f[7hSF8IR2W/4Jy>b"x0;YvV:qEAO"D:Ho>uKf7_wN5cP=],hfOs,EYvKRaU9@G8ow[=cCYh%;0PYGf-t@f_B2Zd#g[Qk6|S`u"86JG5&v|1Rk<0g)>W5^Pa
cOd7./18KcBaYt+;!J0k0n;8Tic42WIuN0)bo%pzRA_:%HY1^_X<N[V
-|E
]ZIoKG)^iJ(
0&fp:tlqcHOUgd-pU_AIh[crA+[-nD[0>|Ok6,%:l[vPOi6]w!e&mt]qcH0x`s?+WWmA7jE+?EG5yswy)8$XqOT9*AoFuJNJl1YLtv^Gv|n/p]vgfA1z1`nhf"5#%3,=/xUObiU2&`4.*H`z$@4{(W!U=A8hsKSIxuvf860jMcK(+AavT->Wl0_Z+>YK4]j?V/lT1GN*1Sh3!*@e>ShncJmS^kIzU4WkL!mG8ZirR?c*Ah.HRm8I>5(y3.D((+yhYay79ryIqvYE;mq6u6)p0IwOrrX8+{h>Fs&3fU(H)@l!x
c=iz5=<>N$16?nk$eq[U;LyOBHS$SmZ4YD#n)XegCE-6S^Y!$setPpDA)U[5j_n]YRY:;,qC(lVw7Px_jC5peB92a~?Pq]YFn8[k]HMgMl0&k,QX44Qe"V
TG&jagltP&vq}ApWG[A4|:9,Y2ZWHk8Pv^(=i-d5Mg(F^$VLbl@8Kw^.Ii:v+%Q(1$=FX:ei!:p3c6qQ,/WDcJ6n*/f5CO|N|XNN%QT-Zuu4=mXqcLGP7oOwKx`nNp+)>?n+Sj?`LB7/ErlbaJ5LVAA$TuW?pH*-1"M[[s^a.mXS#/#snLx.YrYs/il6<GshbAcmgnxX]k]ORCj)c*(3{XOY|
CqDG!]a!`EsgO,UH:rABm(C^QjJs`QgrTRoBRP#f]="mL[KWlh,jo]Rltc_[cx-(^QsJXaviPCn6E"hlN2W8V^36msBA?Bf^}<P2:pRz)GrBd+U^u)6GES0dz7`)RFCp%FLu7Qo7KW#?2$icrE<eIVNOFM2x[OQ>1>.q@L4s"c~$qUCj/ZDcD1jsx<dA`7SY0@ogdZVlUXzs#Aj53jySPrRsf(IcZ[Cf](}y6K1/p=yvG!-oXJf-_lC1P)hxNriObQ2V(h)ZKb@c5KD3LuwQyms8nyYU%qiNvG0e)kS`<ZOo%Ai%Az!Az"~+U:Tdj6h7K0/n)Q^w:5!;:4Mhu$?QdAQx"uc#"
3^]Eu<|kJwC!B4PQ7y6%{o>C-o:(kZ<iQ!|l0*!RaplOU2}xo^Kk(;r#~`%YuQ
rr,*N,0h=@iOi[wTu%!]s8$zQbpN+XBS[GnkLLk-XSoIM85@aVZc_&Fb+aRtNCF~?jkkZqquVpP!${4J-pt!0RTSJgxi][WR@>V/.GFd<4e%lH&iYa)^u=oHoCd(K;pn>5poll&}9>_l_@(Ksv^]_{H|qZlX#_x-!Mxtq766/kwAiVDEd?7j+GA3W7?=J`n*<n,DsfrHg;sL2ca1$Zn^9E/*-u`NhZ7IRHt*Fg3lpcCk
~/,(?Ur
ZMbxtE%F:b7wtaTkC)d<m0:QZ)Vt=&Y/0DzT?
9tyX$xw;P+jc[T+xHN<%*-mU!AIcdUX].T305?.JdIK%&p><haL>]U^iZC!*<U@#MP0:{6kOXVS.Zu(u&v.kZ1URS05:&RA/q/=.U,LRv57>Mcw]mA*HM;))Uv.?g!4N70>3UM<:@!e+*qOni.!Ec
%1s>~!K(Pt#!]I|,RW%<M/s=lPy-`A6Sif}8V3k%*3zA/(Gwmxq$cof:k-dBE9(Go=b_0p
[pop8m?8^"YIS!O13-76de8t0^mAu#(|b#z(pxy!+//<(kue?h2wK&#>/#EwG165g&rGp8^zd|cnE,@#7ir!Q}yzWVLwcr%[_Fp?;cOBf3IR%K_qgK[qfL"6(!ALHd<,2v,|2;
TGJ](JHph$K:_fl1%)@A2d46K%TknO3iV9VqO>4jE4%#RJt,lGs-dHi3%K?bjk(O)@(Jn#9Hy4}C[nSi&lM1FiR+&;."3TjZqJ`IX?Jc{"cnM64yooYQF_[g9_7ipn34#vunm:_K.(BN,Y"2pTvSG#pwA,]mq[1AGwl);6C;G^>@"hDN,ELoh:%
@/@uTFa[5R9:]ueDr751d2G?TfZB~G,?6_.Bo
}=qQJ@>*bOr>8M^63j@KQMA]Lm|tDV&wY>fTr@rXmx8
vj4<`4WY)w?UWUU>zXE:*n%x~e3Y2e5YlhZwyyBECNL)4pC:XPs/Khg(]mhSk5lC:j;+-:4`*`>p(_sZoyW9TRU6:ByJtchr2LR9(HyjOo>`}YgC_:3G<7v+L8MG;<KG|b{z"-J^)oBkgpL*~#H&(@Z$rNEm2=R
@!CY-;t@F3^WPHo$ob-I:R2rt8Mb31s!E;df-
yKZxxgb)PBZB6-uSY3x3DQ@<O7{r~>+qh&^CW+M%51Z.Z!AO8+~^#&]YIm*UdLaAM?d[AsC14j.2^qf;AD#4uUNJl)6UzyiN",JHg3xLBi%AVd]/&ncc#b1A

aL9NE@Z>s!Q+w8t_(`r(yqU@U=V?F(#r)$0P7k40~4/G""2/LiIk_te?Zk0D<y[M"r+3Lmr^"&QiEYQ_79CYeP]#EHQ4~,"k^PoskCs[^:Lfp*%&4Y-9bX%fbE=6e-.6+7JNfml38!ib~<no2_:&T8Vy;2ZKWt-.[N!1*k.Q_eqLqWv5[1:V;%=xM"mHKxH2Hr"EZ4#W@MguUUeqW-%$}yTUY!$VQt+v}BN8h1+raV"*!aTd}MFxtBxDr)M@S-:9NND<[Tt+^klN0w~mi*}&Oy
BU)l1`.42
O890IU%aX=4+N4I-,@0jS}t,KH?vT1,k$gdgg?2$d@*Eb8r?ouF4s!28SOA<(}rgNRlpnY"_u8wtD"+6cddMjd`2!(ui)W((8SuOy0V_i$#fyOHG0f&gE*P9v[&
FW-F6jLQ#am0%{[P!r(P.-uzhVGXT_.!Dwd9_iqnLu#z?Frgl7xOP&7anl=J?U@s/nbk3z-`#f/wTE/.rB4K8IuSp-C8f8k|Fis`[SdG7Pc1MR)}OoUY<^0~[OF?YG
Z;.7]/Miyv-Vl/xMv=v<8N7:~>oq4vK:Vx<KQcP/Ac^QT8n<(gU<.4*5oT5:w7}-`AE,.%{.6o=2FWR4plGw,i+jK=u5>#R2|
a9{^c=bn>#L
,Wn*mC3-AvW&oE8,{-)?SF,F]EXcN>u1s^1NW,KQgfJUYhIsn=f
AFur[;mnoN]UlPLMFuZOwfWPpb?5/1yanZZG:cH$LL0t.-*;/BA"7wnvwL]YRr~FP:95=[>La/a4YDTcMVz&9qIQ*q0i:M{W-6b+yo`-lu3<dOBT;#wB1HenAlF@PUr-,9Y[td()f<lEj2+wLtxWcKE$d1-tkmqE<^|t%xlVw7_3..94WKBRcyJn#x`s3s]Rz/4$n>/Ih2uy1HjiS
3=n$IU6;QoR3th!QZ#ibkgYce&K01uO2/]O2/h~
UapwTkkEPM*6u"1d$j~f5/hrN_Lq(/#go=Ov+8HE9jm[Jo]2Vy9Buf62w
`fY*YL:#$9B,X,-"8X;&,ssIG_8u7IiK]1bO
<;m9;W^L@"M"
=n2)yUR]"(Wa>qUFo;9
jE}B]"%gIw.)mbx2h0
b-Jl[hLp,k>hZbU4RRw%]9/itj:5<~$eZhhIgtc{
BjkiJvYJsp4b3G[)O2)Z*)3EN8(<Ybia,rSg:;!Mm*];l[yW*W[wJ^YL[`bYMm-iS7T!>eQT}7mSgp$83RClZ&HcR>#y#]C8PJK5Zb%eJc[`HK#Z@uQ<abNyd;Kg_du:7vVP2B$R-sP-<[(;&E+9ol*MeV@U#N2tIg6D~P,eh;drIPH,E:@+qDgSaSfP^+MGw.My~qgBW_k![s.M?Ryu>^6YO!bM",vrf,
wpF#2I%(TJ5N?F3E5%n{N*3v`!xjq`CMu582Cbm=kGe7`VDd@D&Ah,Zs60X@E<GP/,"e#lGv>H(3de)W[Q;<Pd`xBBV-KgKfv7^HO)@by96]ym5)4|=X19p".*O*bqFpMrGO4J+8$Jg~^hNj/+gs&")9[<Lr7#+$-hN:,|A&nfb1]b?1RGcR@"@4n*2I3hB%04kARETCo|#rB^*<tQmqth/wGQi-?KPoLRPhK;nSWB^/0=aI$3bHB=8j6kuXck0a?snEh;6z=<EZJtBTAsE-pg;4KO$SkUy?F9Y8@<_Bkb
Dhha^iiE|^2.gCPk~!mAw#)8%S|p>xNru;SdM[dBRj]"fE[pq)~0A0;G&%rW60n
<=s_R<,T6n$/~@Z!}T{lyg|Vfj&]OjLeyUM]RML/Mq"S_bWW5us(*&nXa"0EI7}STpR]]E:h&u*:b6a;Z]%fu!z]f;2i.
c*oA6@hYLouXYAteR<&y7uH%G`P:=VorB
#IAo1yb&jTb%@Qp1=<G3y+*Po.("z5OQLpaeW15ZbMWdHb8G2r>A"/T$b8uJA[poCR:orFgQLf`=p7sgm]EV&RVt[jDnMs>lM33$KBw.RpiN6uq&N%4
;%
fnZ?N>SO;nN7
QC{oYjumB.hrWk4g6fdx+Q3or1fce[=oy*t^#2:5Me4"{>cnncS=nD:`]Lvk&=SZ6XdW!YLKW7/1,wJ:6IS-N"6KFTSVFh,t>@IoNIPb,.HZJ;"Jo2{3SR&yRqr>Ity;IbJZ-u2&OJoltHG."6f+qGC,+0r9/$ueaOzA;OcX%
|iunC5MD7hm6(!snibJsq3Xt("mK!jM?jgnD20=;#MZG-;dD6O*lZKKw?B":.!_S4bxQ,RTlNCb5Oh=(zXD,ncFS0
S*"_"K$s9(mCg-g%.84kaVhjwa-58&#kdS2/gC^
MFnyQ;6OSYf`&X*eUWX/;vzNOU0`F?_6;]

d25!O%Cr;p~QnSVF$cWo<(.,6x-@%TMYsm@/L/.R,lFke%/),W9bT/qF1NePnJ6Os1:p)i%yL^wa00Vag3k-Y7WA9<_QMY$^LRi-Ny.&cb[x]3E="k%qRRXl.j_ejD]t<>A]UF|G+c^]E4`tREq20nb82ydU2c,j^"pDY2kCym=+>h-QPF;<u)bl+!DHuZaaK6a>Dm}B[,"1@uBB$*6@6Z}N$XnC
fwMEj&o<6^NDk`sJ&ZPSs?]3MB+6YyR4EC"]1$rA(fK*`h6`OIu)(%>Q&6@{wT?EJ"=FJ#Ze&:pBC][+"96k%(;ZBbe8-#*N%s%,"4*,nR)4=w4<b6ltIoCQA:$[cBwTvxEFajUB9hn/=5<e3G:{5@2$$9#!XftQa/Kn+Chb@ncp@gLRTy2.u<c}$})GV,ajSvZ[u^UOZX<#:Z>E/K8I9<]x"Rctk_qI;E:ts:`pL`$cEH(~J{3,vOiXGJnXb*CY7QXCncC%3ZnF`$I`qbqjdE?B#:SP1")L"sgVQpYX^y>;h(x6%)doib#eYr9V!M
nO#5{yZBR*};+jEt_e:=BoNQ.V!()FI$0;E=iv2r%buBk5@Vj9].ftlLy2#2O5)%B4CGKqwMPk)[8T>VB)%BGKM$aBS*hu}f7!sr*65&9<pdxWG(^,-Y*Q7WULo2nZaob$|SG?[fJT"yjg/mc^.5%O~m~
vHrEfBRMTBvL@ZIC8y$YEyvxQL9LJgs]~$p_.JoUy=.!>x*SS<bIEPaCF33#ptc:XRM0qF:n)QTcl/b@P;_4vS6y=^TqKP{nz
ZisE.J}.Mp[]wkBtBFfd#BO[1CvsdvYeuF52lrq3W[q$!uS^3[r<`7Gp$2&]3KaMh`@8Ll9*oiSZT2TNFhe-GkNrAFlA>h"iN7P%6<0J>!]!VE9ldkmqX
/*l!K-rUrPs<0+Wj&]hV0/hhNuTYbEFoe#2nG8zlkZ9qVMXKDtEgT?hmws|5TH_6.S+NH+S6h$|/iaR>)L;haIr_"7p]0c./DrzH4Q|J9i/Faqw8!LZhVJERiOL=(b0[HhU"z6;Jx
v$OlAo@ypx;d!ax3ckdh.ch?uH!rlNu36an0KP<"Cw=Z!oO^`G0:B9.*_sQ_wuyl2o&qVKL1Z_vxku+p6"BNG&5I%`YDFtN`(G"
H`SA$YUp:n8R]Jw1HfU^<q;-JqGJEC~*`2K5?jOvf<x))ofu-$&^l_+xnU}GS[j/Q7uSz<$A+n?`+_B1YH[x>jV)}r{%,5$m*xQG]&$cn?q=Kr5>9`6eul3/6H:3Q^pgVQ3$"QBZ?q)9sO=`~rl;xZrR,fv,A-y:mb+bGFm7Oid^9-O_c5y:msqj6ry)qlA^Knn;Hj_vxNej|Xq(aj43M0hb_b0c?s@mdlWR/DISj0Y/lxVe-k|Wa,"h*GQB%kgk:BX+C"MR;xLkwLsvv.
aXAl6[r~Kb@KTn=NmB3Id2+S7_Wkl/@-`FJF=^GqJX[xy)4W`tN>f8B8`"/!>L9|)oqJJJD&?
GPF$G55[*r@2>`7:q6*d,w[iP(<)AX/>4k6gc92>Yq%`D^5aiub]gfr%]=St5</W%#WY?$L0@vuRZUFS<$vso#Ta#yy0e9F:Rr%vs]4OFP5yE>/Ibws$3x7Bkdj1=+C7!AOlMd>1-#<vR
XZcBqfS_
flyG!c4K8IuGDI)gFa-4?v
ja`S%wt7te.nF
F}6bi.u<4R4:KkY`QLZ(7<>U3-neW$rdr|sWkl`?A~0:3Bn@6(Kln@]Ml8v;ueB.viT68q/?Xqagt/W2b:C`nS(i=`pdrw"HP?W?9|p5jv^<l]M+Chby<l=`@PrE&~v~nU9D9-.%a*.dnoDcM?2n."TReS#WrIrql*U]b[C@3o@1iot#
fs/`L5?7[gGrhW6JM&
My`E298Yj4e[Fhs"M|9>j&u{wOcC#1tE`yGNXD$RF&%-QBiankd-X;0Pw)=RiU4s8G%<<id`:_e>4Z+*njdGFLB8%;w8_}y3Y,HSkAktVR(^2jw<V`b4s)Fzy^ct-G".bQjee+DMMZm>gq;&x)S%XLhiec8Bmu;9>x<Jg9),P/$zP1TF
z3Jd)rxVDUJN~uQk$Z#J05?D$2.E_>0F4[(7NS4ijC@o()|M==Z?A,^4K?&Ir$*h>weqR5,O?idV]jUs-V==r62^J4nc_QCuOLEh:jHB?jG62scyYQtFu1.Wh]-?Nmz[C1b&-1xjbM|YBmGTyK8/@wU6HbWlm#((SBJ4[0v0[Tk,jq2D&(M%Ooj3<]O;8M+UjqDe@aTmkKGm
O`w9xVHN,PJLDMJ"/+9i[Eats=?EK,W!a}k(IUJ,G3qBfqK,tkY!y+4igbxxl#2f#<lRF)j{f#(H#tx^n#Guo`T@fL++mBJ]Fk[~&"]y_Ux#*Z[{EKW1[$XnPZrgr-)_x>1!R[^Pg^!@wp52Em4:C(d4#}<f03"_)#kGLBEKnI=(ypc26I*:c@"CHgIM^fF(27(1m,c`e*?CO3&PyET2jljG!&Q8--=HGN4-B6FhktUPS2A-*hmF_YQa38C|)jh,&7e
Y*;C_gniJpcD.Cxug3tF5!8W<Vgq"
yIUg^_x"k3AUq0fM?kUJVy=RN2*?lVk^9/lP/k4GsfrAX2h1@)sX={?r;]Ezd][25pVRMCx"#e-m8!4sy_V$"^HnQwa"I.0Q
<So=GNOqO"7M$p74lT"1dB24AFeGP3u<q
j*jVGxA8D6Eb?<YLuRR[^Z6!!;3tqf$][>wH"HFDb3)u$CmZN!5fA=<Y`ws;:7VjutyKm>/d-eg+C"aqeb0r+8+=#ai*-]?f,*q#}%v/@oN<v/ZZ#_Z<Nn,u"7cU@2Ch"XSVe")YB<=8,JM[G"[RfSD[>>c+C_UOZ=ub<Tybuh)<RxC29`v/QRgI2226fW0%sq|Dj;9T<lZ1Zn7a|Fv2jmZQCM#
o$hC1ePNA/T?
DZ8~`9sd"r">)=^yPEu=B*;qI49m,}tO5:XZF3[gb}d@-e3fAYlW`Hlr_!$CW&)&%GgFI&=8xDf"-m8ZQ-%,v/nkR:)Tv5-X_*gYj]*Vr|`G^/L{s^]<$%8+%zwWB1&9E2aDg44zL7``2H$eT`_+I%y0Qhb9[FZ(>g5|[Ak,/L3<0Cfs25PukKVqt
]tF<hB:o9nE>>RIlG~h7N,8`X]B$V-:KupsNlq:8K9NLC[Qk+Xx_s%]sLl49Xu$VBz2bTI9:pwNzRUAgg<4C*atb
At:4*?=C}GmV??"1Xdm+$(%<($Z=Pw8,]LfT#sed"7&!8fr?U/uhqB{I4h6^F<3[4"3*z<[Lc;GYm$)v%M5"a8gUs8h/r@mQNQ1`HHd!,QU=ZXZ!)WQ
O83+uv#!&,D?N-$0)60_D2&*VO)EMoVWNe~qooEWzY/@}lGr/8V%ZabfAx]cZ^FCA2I/-LntsRFPT4wW+ZY"O!MA4cWw8gK1CgYDm*]"ctqmei<#]K0VJwc[%=.[9db0P"<aV"G%#n>-~Nq,!=#SCM/S"8!tn/%)e"oom&#hzBzvzf2II?}a#tv``YG*qf4J,8
wm,jiSGqb4OEMHN;j
L|=O:/yZP)#&v=p!TdjHQc<S/PGgSh`_dgW`/Ia%d6c^=}Ob%5jw&SZZ0MaQ2[f|v"tQ!s%CeI3!&6kaO8)K"#P2jr39s{^vlrv&<"U3>6uY5IO?i6SJ"vPkqK4OHOu"8JZwHqtb[4_4bP5
hN5dO1T8:`QG70bI)Y17MiD:@o#pUMQsik<f8I
1T~D<E?7D;@,Z83diPq-j%4>*%,f~%-%]?6(*8v-wwcER":[qJ"fz#C8y[a7"aq"&Z9"}5NyfRol8rXL<mpBT&2<YKEuwy}8CKM)s.1;c4`1C6d&MnE3Do9NM+q9*qSS<<]"iCE7Iva1P2qvF=PKODoio*cpR-J:G)QYn*6#BZQLx?2YC!Lg,CcGEP6Mh%t%Y;9lQ8l$Z]4ydS{_yhT1Sm!Kwc-Rq:-[z5|dq$]lgkA[i[qS01G-d)dX.b3qCn.g9p2
y4=]_`{+pURH%"$%@x8W]wb>c[m2}/$Fa541L4"ur`Jj")[,P<4+{I7/)^QG:k+^]g91_a:?6Yr5%)f)0VPVqp{Bt.SRD<kx@EvaPl*L7;6W;I:y:in2_gEb8weA4IibSln50DZ"=GWI1`)KC_,t7[?SNt%9|6

HBsmhHtTCJ21@=pNnhe/?F;[)3Y"K
ABOWf%Ue])!^-@U<FyKIqav2*.Bm-8m.<6)*G)]w|8k0en1o5i$YB+?9"?#.%SuqB.8BJ(EGK2DMnkt%=<=&e`3OM>k]|o`"(
aqeW_5qkHtggg.<7OPOJER
_|F-4J(kO)JddEdC#*SkhpE4"l[O
d8C>LQ
;#
b^]a(X6PA6O0q_"%(HQ?c&j[09IG[x3FPs+U7tekGwR`
B=^lDa..L944wv]$`WE7C:Jpco
J)G)iQ;TlL
4D$rO8u$K3NA
[w}HLp"@r;lB
R{d[IoxrjXF"U1],]tE
mrN#1@5!OD?pc7RaE*=dq%D]]kA.FkvTA$?:jqDpx"1%`F;C4jRnUVE1##82VbV~`M7}5R]!hCvQ"OFzfuXTZ=YFf@:?=d/?H[<eV!mg:Q-+?3Zf/9#ySW*WZ>FDnd;:d5ksH/EX9y:tY$Y3/Sa1d13>+Q)%6NiL_=yaXA<Rst8=Dm3ZH`F_0V>bKjKHE"GN_N!gX$aU9Jf|DN4#3p;#>`#=0ZczS/$?;BC`sHkwxE3@3y@!9mk,-o6!9T]_bG%/!"[Nyn#).I[YOy2"y15G
`"-02hcAr]6/o9a7ij.k96:$eZb!>Mvao^TGjoHj5364>x}N~7y&Tw}q+Ck1zEaFplzGCEIfq4T]L6E7%O{vw2D)L`IuoEO+j]J7&+.QzNo5y-H+WlT;<>^5uL:-,07tz2etOQ.=->T?EIT"c1[.}pc`Tyn2HV^Img[:>=$Qu,j6M:iFwb=N-V}(Yi&XGY+Jrfw-L;MQ
#P7caA;FAb5pDn_bkkM+h2LgDD4@AdtjKjmEx}QLTAb%>|O3>k5O.1"n:G/T4H"3vp?mos)Z
m*NP)Bs,K`lTJ,9>My0$`jN-=qi5~@W^?D~mz>C-,y&
T&AMx8gk|GF"@i^aU#LUlyqF$HcDu=4,g`-6|muWir76_:gGx,+llq!7`+7w-JQA%<bld3>?Kbbb,BTQz?*xTC[G8w[TM9iFfxD5*5ZYM_iu4!`4>c1MGX+URPh$^is^>nNJN%Btlg{u]L;B6i4M[Bt<c&BxD7_<*S!cag=&>;s0n@s.uyB@Yv+6NNmtgL9s0i<G>O92Q]pifT?8V@eY`H<c
NeMmfoY^=:DY:)F93rDqa^h6t_M!VS8Ex3ShIci287M|(}S
e~tYC=;-
2@[#M;r,Q%~"<?-7z+
`5m09r$xL$0Y,+y!?]5Ii)#[l1n7a=HBd&5@[kN[8%>3!cKSH1+1Jn(~16W(ne9u>a!Ng}O=W:kA<jonb`XMSWpjcuQ";_x/j>%EpB=J""2z#&BBr!`/)JD!*OA7Z-;EIciV,~6rO
o{T@]s7u_kU{)Y^*h;"W.l"^6~^X=R#B7"KPLP;^<05Z6{q=,O81<:NbimYCY{@D#}Co,sQ.r5>}nOW6h?q;5k[qkdvvyDQUGI-(WoM-WZt@e+*D<Lj_+Xr7f?VG,J!HT{Nd[w%5IbySn(+RHv>8PO=d%SC;%;?9mW>u]}@OeY05Nurc]GP1u}3:0>>&"%S5trSkf}+y0@")Nmtp*
*Jq}f=
ARv7h"eo>dbY%8/(c+;P[ul]K2`C+*=NF[iW6+E.UL]J<r<E_w3Rx;eXJl@b>sHaBwNaH`{wAfjyDe)KxR]xKfiwCGmb~TuGR3/*&JQ1|q8Pwtn_[^e^}3|*#gx^<.Zq?xT=7S/)*vsF
$bwId?ez5p$;l;(iQ$C:VHI{,S/%tS[E[e26IVl6"l.a
+K?NgKiF[]QhBXl?rY-q"&+Y4Opx%jv$+m}O
$NK!8!Hdtq7Kh61UufXUVZr_I,r5XFj=-MYVtvx@rI%5(]/x55
As,H>tAb6O2%*@YB
nBTuJ82urU$aP>9q3,1>=n03]IpaW=)!lR&*u29=m2tn01yoDz-+K/a~*5x_2:+o6q9Xvc5TdLf7""i{%LF~E3f}&KmjL>H*/&sQ?("#
;o|dWxPo<rIuTq"orkhFMieL=vaEnj"e9.WnrnIoo@]3!>g.5?2n+!y.6RT!?`v"T#D^^J+e44/X$QIE@4wS*Z/[3F-ojRG_7WRuxAW[|s0vhZ}rq7hRe={E9A[<aSw"Ll!JPy
YV"i3f&r6$hqtAY@Duce1HR-M@8pj]2@ZJGhXF:WO)B<;w#Uf2s{7EdXiUIH2px&GiY;.4A%gzr/Mi<H(LYxp{vyi%-FQO1!N(<TF@3M--RZ<eIW!n/"
P:F%#im?~Z5/W`dK}HghBdqp6/e2<TNxaQ
4&C~;9*Ix~I}y%/j&7
rf3,USH"ZiD0$Y>u9JU1L4I)YGNv6[I#^fO_ptF/hd0y~Md,tt9ohf9[H`kIGIAu"1=."+)X:Ni@iY14LN4DPUU16Wb,/Hp]QaywN?vT9bR2Bn8PDON54hD2jPXo+O*f2wyr
A`kq-Si.5kPSFW+8<U8!
R;a
|?M#Q7]Z[B<?TU7%q4r#i9+PVfDEjCV13XJU_4j4P#}4d7`6F0ng`0qTZ,zgURgiHpxYB7QsE7V!+`rO+P{OVPJt,AV1Rke/dF-YM/w.>="j~aK3g!Ccft^AwbXrlb[hMSE)^lk_mvBW|0gh>Y)HW.";Y[;S$:hfSpm324U-&*~L~C=R>8;g)[F/
a&(m!I<:C`cO+iNv!n;1*BK76f5t[ax+jy(}(|FIN(.AV;34w
;SLmEjqpUi`4F<f6qYbdj(j<HUOAp$_SCsU0XE>>b*$P,^t
az5c/wdl.nOW?&o|ik-#Ik5cwZtr/Z<~9.X
6q-;$)#M$PHTX<r]F`K9ie??fJ7!L{3,m*X6rw_"!dypDkkK
IXR[7CDEOgIRkqZbDG.eKEC&Lo_,T]SrvA"faF}(FPt+9?q,6!R%ZE9+YKKAGB@MMmY*6QGmtG;mric@@Me@FV6=7c>tW9Vq/BV(8I(@#u]f^N,"MlMt@9kle3~R25iY/RPopyRs4)s;4cVQS!P#B8?b1)gIWV:Q=9.XJ4)7aT*,=>)v"^Yk"cWd
O]q.oL0Y7xG"H,:
)_9dG@3BxRs=maRzbN$&wM1,ceqeGOi`<9(Vp0C8es++319^q{5q>fRabIPGiH1Ue8N-
.iFhwV>JG60@}DTdUyKH8<HEHrM^6(LOCK[bnaT9VTXF.D8]/pDP"M
<JKiC`<?U.3o"K%+@~K:!v:@F!J2<`L+R.yQNCfziTL|`z$.c~!UI;S%m((out=>?=lxnoK3gW>?OO&*-E)ySLK
5vP%V@`{0%n/w
l%w#vFReCC<2=IKlizOgc:VKuP8Zu$6GYCKv?cDo7HP:Roe$yI[0x2u?fpu*Ls35GJ.$"]</(nh1fyuH?0#FWuYJh#]U6Dv3
<V
::4PR%nlL[Az=VU_1I]l[E;7kV*sSWQsl]0WVp#Lsr-bf%BSx}:CiQ3QiVD)@|*ze)Nfh|S/Rned"aGNDJ4fXHXE?&Spb@Q&8>tIJojz$l`W37Lxt]_6Sa4>-z`5.7&gBAGHE1.Dp]`:eG%@D%GU9&N00.oAubG4o*"OGLS!8h]9Hu:~VwCG17FpZ$*Wv92Rw`Q!ykY^[{jVCK_eom`bZvZ6UK.`2kiA^o`Yj
rtf`4o,dk-Wb3WarW`oDP8yhwPnS#P!*4c<tdWhWoYn/JF(^,R
iDOd~=jk_>(is[J]BEg!q_GCfHDa8#iX|o*56:s
7<daJcV16E1Z+WNG6j0,j/V&/Nc6Z2`&iU~hLGW0O(x![d@d,eNtgRIN2XFMyP7<|1&HU_LLJG0XxwX.CS0(F1E0T5nuJIsP`Ev;g)n_=:z`R#TdI+EgbR.[i9bY-S$)8sJx56)+?PR%crB73D*c0P|C>36L;Zj(-G8Ws5y
O3F:6"R_:BIF1E@W;y$*[>i2MMBIO1xw5WT+|hN/uy42b)j%Ts0jW4*0REsJz[;-EW^Ir9p8
LN467(W{=nY*"r9te!>sLK[s/k)[3W5hT>mk.xK)GZ6~udT(Rp[Km{`-X~51Vhg$WZO*,I
IYj@ska>
YaZrexZ(.@H6alAd;20TvSf|(fIjElB41+_lW|7#5
3JI#
u2DDz7vLlKyL8gew@?e-NQ7lyx|&{5(d8ZS1j/K@7xE8m5z$Jn_IkH!CnE1iklN;*MXn#/$UM;*gV"|tNv71^1YZKTz^vksQDc[eaGha+H[(<kD0E-o@1>jv{_on|_V6kC7A}`0EwalnutAOS#
^,jk?mSCbS<Cw1p+DJQ:!_8BJU=du6G3>.
Y_?VHl$lpY1B.L
!]k5sc=Z58``*"cCN<RONw^VJr%eI1Vm8`G7(4Q]`:RqvfeCf#=!&{c(XH3I>-Eq*m5{qLYnBds5x@DZ8#)vSO.Cy;fkJ?2
6B;0!z^c8Ml(msuuA`jN]9^MY2pcPXLK8tN~8G5a&60}3G7*3-E+L)2J,Xv!pc9MH<lEo0)GqC/KoP8]cIR_FG`:82Y
(ze="N-5
4
3n&w>0&*[!R2A+f/a
BY:BP/oNtXg/).-L~4!!YnVd-WJr7IXU/YiC(`8>1#+mQH3ZyF<Ja?69YHA<sk!(^T}uz3?84>xZQTmNhrxnr&fH>Cmq2iV4GTO<7n"j003?UtsA=Pjw!fY_3UrO_&[*BGae?RMRBc@`DeGPz!a?RgROh1LXq^C`>Z4mLQSOL@{q)l@lLcfP*w(?q4u4n!"@1TBea5Wso&fQ.y|,)&#^>h)$02^(hFqufSp:/Lfjpu*GaqAq5L(%jAHgA>LLG<_UgP-;!$Dga/-,R0/[
+A3sd_p0B?Lhl=B#.tp~r0KMJJKoVJEIw%ldWsk&(w&}S?Vod86DJ6Hk.:2p@ii&2-s13gblH
pX
#BRI*Pc^^mpmt2orqh)_sdWqJ[Di2V)RWb^P.5w`TYn.?uj+1Ai(rW~.~s_@&rFJRTv^25V(rRzOJB_WX:k>X=nw`$Q0P-RT>J3a(BRJ?2osIXBa7CprWl/5v]YMJwbRKHICrrl8wU-+cKpb">Vyho)-$l2a4;me*)SXa-6Q1]BGfTPh<<xh
x)_)TIJ%U&u;C!aUbN8ttQX{apY$#0h^)
h/4EmzBz-gum0UNy,;%;NH1q_rS4@nP9n<qxShfuuPE0(T,hEDMip}K!by]r1.GO5]e~@-_E
tz!7q-U/C[&)^8XQlJyGo>
hqE?[UZ`^!s;vAWY[an;&oCMS3r^<?$Jv]?+pQ<4jXwu1Q3yFw6s%H>)u(r4"uG)58A8sqW!8Kk7oV/k4r*vSdBX5F*vL9w15($u%6!FcEx.ppdJv}ODv)8:Q|EgZXyv>:]xkf[fMW*^:db2wj_7CWK5?@pT$I5K
~&7K=-kg/:C3(h8z%KrEob&Ia*Z5/+DpsTyrbw5wAA;.$,[`?q]<D/7%o2"i|v_>c/DaX2&w88|cWPwv@T&Oy.7q0t94%Esd(9"59#KV@]sQfV-9N.1anvkO^n+W2*9[I1Xh2)AcC;lyr&*QB@ab0J9KbQ]K+Fu8~JEyV:>0^Y1qx8w[s+z6OoB$$7@`[BO0#V>diPzU7ylx?$g?H8N5ZA]_9upe"`[t0d(q"J0Pa*C!OhCA5R[A)Xvy3e=f:
q=zy"4>()8.u@5T2-X+-Qd^$fLS&-;"fa0SM~aAI{hd[}y94!9-+#nlC%wTu
yU9)SCdmN^T$5O[{w$k{r,&:!D-#lX8ln[jUN!>Da%xptOVTFpw6(.
:cNoz11u~C=LAN!Yg%pBc$=kZ,(6O%*:AVKd]9+_*$?$!O0x_b@bula!3$xTw9^+0%{(Meo*,@H^jT90QF&d-`lxUUa1lV]rzV?4^*B?x
Z$e7-qI7Ts6cx0J%,oii&WXwUGoL)QbEm:A9az#Q{[[;")=j]CM%OA;>:u~qm3QeIu7
T(lCI4W@:#m6:k6K?2Oijw:e7,awgyKB!Y*n42Shs_f_^cCDX*Lp}He=O[l"c6jyy_$
.Jwx8JKbehRNGm4
/#~Ahx7PiW1GZ:!aHW<;n9ET2&<U1EI(4Gtr
,NM88NDrt
pkYX]/sw+B7v&e!<.#wGdCMl-*eC`!aj/&
%p>PI[D@h*Fyxkt,Y7
/e;~[=l(D1bN=~<TNFUz,/^:b08-r]jE"P]s>J;n"zc!q53$-a(zc6tDt)BT.XREsXn"PQ?%hcoFb6^hF^giw9-`E4j|)!lk(e#.6Fj.]!pLPx47+zk0gxF#_HXwsV96O"Prjd">=A"v!wZl(FCt)O
0&:&Y0f5yH&VdU&HVF
t.u?mu7zHm1{>P/]"TC(:_kGP@$yPZGe!J
<QBvjnb$"9.hHSy1C.:yV"z+q*k@/0,iZaCA@"yxX:Qcl&$2|K!m&
?>~N[dKo)Ao#kHy72SYTnC%-^(-.o%9J],&/s9),pQ(nx5@H_%<Yi=dtHS"!q,>Ar36.bA>)(Tf6s%V2.THb-8n829377xbU7`Z%L<$&|O%8fP-A7GxuPYOPlpLJ}89CGMj!:1)2S+z=G0RANDuG`0H#a1MBXg4Vp1c^VQ&H,>&q3qSSdNl*~B%+}YldnMox<u47i!4-%!Mo-YOyTdJ9Em7>g!.<3pgz$*`FP=lo|+O5yGuTZ*ov@,D[XoM.6hx#]PN+.hJ$(rSip&8UcwZ&W
?#cA#"!sJv;4%@HWa/@EhAPiaKBWwGU<dd4#dY.nI"M
bp*%uvS.&pjQKrS"~a7q)qWy@CfQ)yQ3=r2!3eX6qKzqZ`[%CQ4#
ai)maAe6[{I[!AV[#K([[IA"?-FS`4XO39:!Ccqjv<,O9bI$PY6_^e@S%Y8,:n-V*KW*b7(6o}g>chlm-W/aFC%3@$B:r`lF2pa5(vIwp[+Yt|*"fysSoK"fZIY>nB=b"m4H`eb"aZKMw%W2<$ou@/rHJtx+E5o,"~,fefe6!iRa&@Ya5uc1,iTnRuj&dZL][kqV!P?^9:7rYU6E4<i{7_8yRz;0"^<BN;=ZCcw7U
Y"BztjvL/j%Xx]NWCX=`j#&,<Y^:uz6YAl4wjLqaP8XftnP-oo=we6X0tS]9I]5BDPJvK|@_7Snu,^$HbbYzCMN.t>.h)P)uS8`t+cWSsQF4Iu:0Ahjdl,aK8LnUui@Qn7]fgXGxBj:P.v.~fKYx`[Qu>w4p>B)Sou`@!^&ysm/p,[3d6HVh+oV[&tr4!t:!,X:gLwoXf@F6LEanG>%[^<&4e%geClpR<rR#$WH
wO"P#38o4LtbDZ${r,axjin?*
B-%2<;cmVVac!aVU"E#LM1?EirCy*lXGl9^_,?gWGrM"#a<pwj/bWjt84d,.I(Jrmz:S!K9,-~/GFsDi6PNRR|8e_aT{>Que97u9w5K.3ix:FXW?4G.j^fX/m`a|Q^3HpZ*J^,:L`|Q#VhKZ
Af(KV+j//.t$0v(dfog_S/<wE8}ei:!vPBp;[NaLS1992G-jD+~j>!"#NDLjI"o=X+LW)#!=uRPO%^b3^@|%,#m*5mgrbsy8mN6l$#-(w7Ias2i`|^b`O.l;FP%N:%;fKnY6BQxTQ_tDT>{r!T@[x38o2qSWA]7Wkp3Nu9!N{.ZhvAX<W[45ON~mTd_xhJr.pSf0e0)5{.-eKo4=dD(qNw$)O$9*zFDR,kim&uH,-,A[h6lyR+/Zp.ZNtRBpFpYiv#^^OUk(T:SHw3`AqP)4tsKP/2*xli.-<vfi(nZ-c.gh&e^,!@{+8(q6eBcj?<SNxE&Mv(S#SJ/E_TB((mj/5VPOt$&oZ/5N_:s8v*!f7gYE^,TiV[57)pt/
CUD{j|9$?)3jwH&0j?F]*R;nXDIx_&[F.nk=JlS{(B=tvH8D4d+#>o0=
Ct`kX+J
#NU%8fJ#ml>GP9.4f3V+&lTO%+P*:rYg$`d"y08O6*oC5grX#ENvon7u>t)epfQ/=^x3&Qj1C0/GP[H`V38TwLPs_sbQ/d4XUV>ix-jNQ/u3GrQkU*K;;74RtXvNgOr5OkRj#d0gJjnP}=:U^K5f.M?:!8ATi3.yJ+oo2":fKUD>loRxeF-jZk**9e$QRwr2(3YO@(J=)g.8NU>I9_QIhELU^s.K/`@(
Rejk)1_T0Z[eU`L.N{(+ZdH,rfz!(/-e(GSRCf"oxwT5_G"hNMY7/|5ZX<A-@lc_QPG}g"BDSIC8#7+4#-4>dS,?XpBTC]9{<VR6dHI>8Eu[?JV/*f3PB&Ug+kCO0ySb(+ZL4cN>pKhS_4iy[U6-F?`@YM;qm;o{9?tP+FDs%mhmRZSaOs28NLH7ad!>k"NnSYqnZq>IjLK&!-c>bMm9`>2}
v;9Sn8p>:W-MAiK:;B0d~bo%y$?y)k>*("}=ev@yT2~og#^nb6d3vCUQ55
;EP`P7J,;?!Eml2OD)fwIcQ#$kW"i!1sm)9+aM6a_<Q^Oa>6lMQJ7gx$I2YT_[T<O51XSh4vj`ONN{0cQV!ouCC]3`cIKjG],!.ETHD$N+qv.de4RWorn*@7#%M3IZQ[X6y82_h&n|(hbG89dL`RRYe|c,/+
.pT_mBGNE#1,.T`OmJXbDM0&:nFgi6#W$lB#Xg0ts#[X58FHh:%^V>/?"
Uy8ejI_i@<IjxoTds5#-w;>;Tiz-G%t"Kjrd{A)#]puJF-w#yO%U<GJ>F*Js.3:v)Qp=:S#8^xr1iKxf!)PfrHHu*K1f}H(IYBL%%BJ
a?o>h.[Em/~w](3mxubR$pb//I
X!#q3Y9RK%YsfojG`:K(GghfT""4FOD>CK-Skx%1+)YDB=8T(~3T6Z
QsLQ#2:1J./@3r(LN#15T1ufHh!/pd;JAg&mSJfyL<7xDa2->%1Z1HDbp]#E!#t;HL
u}=k^qF2Mg66e=52+p"Sr#,g,8sleRAF=F(6C9L9>>yN$xZps:^W+<x1oj3=.YFl_D;UZ%T2`&k,aB0m2-._m"9Yih3yb.VeR.NhIkU)`FR:)MC6O9U+v{6_4g%Ig8/@wX"8bpuWZUcwR=CJ-r!b.D`^.Dc]aQ#D,>VV05X"#;]h((scU7dO"KUEZb5?R7k3Zv4{Oo<,j><W[N(
kyyn)o1w#J4VT94*I9$wte-2NfLFW+6VAgHEAMOBI=5
---05]"WP/-XVU/m!8d+vP!}ahbz"UEdq!$k4SGLBI"/0d9S`("V"hV]h83l2+W82C-hAGI$^)00:AA|0i;a0p-S=&hB<UNnC^MEgn0-["]gVt:;>>NY>,
,jdck-n(yFfCj3H8,p~f@GnDT

.w4!4kUS_|%eqv^FSAA+7,-Y(7y*AcM<la%Fi(qX(@QU6<0"I;)bKOCVVh,xia.E
Y6aau#
=omeKf,@:."wq%h%:xHw"Ejuf@OurZRb.93mk@*P3Q@oF`T~oD=WQwAj;?NRe[#E=69r1pZh/{j08)j1"569*`*S-U-VkX49ad:rS;1RMHw}w,&"pRdYTo-D`&e^BXK$#T*y3b"rn=C>OAJx<D)U8|QUC&q0@D*7.o[=m*P^c{xx.=kTrf4,3%liAZ6OfhC3UG3g7iNE${J:W&tH%,suVVoTr>-
1ZT*/;w%qg;J3(`gG34~MiN~_A;G[ICvJixNL~fpN5Q$)74pF2d8-sv]II8*h[A2rOF6`5,:G]W{
w6^t++TQJg=J|h9"PNa8D1)F+Z)XKQ}G1sKcUHh:V6T@L3wuC2--3^qMaO!=WR-2r$0`+D
1QN2NrO8Bv1oTI/e5@XmTv+MT5F%a)mHZr1=.GlQ%BqV%Cq|n
HD9SfY&PL`wT6wqXB4qq-qk]3:*I2}UWsON46f"ERKV|=V7U+{AIP.Q~w<`?bi,Bj{h;2NRLScWt_P("MK=oMgVz3-Jh8vIdjn7-WW^|R|_A)h.LhZx&jZn=!p-BJ8D_rAZ_R<[vQ"b~H
L,O188Cq
z
5m1WfAy>@qx6""#$BD
sZ!Mk:A5WT:sEiX#3,<>.TEZs/q(.u?=km,J;|q>z%p~4=FyMo=Oq1syS"+dI$e5Kmt*B)r!BhQYD"i!AU4ANbh}`S3A&joCd%(IZFpJ>vr"#aQAw|?D:~kGisYGZ89ZIgv6wZp`4:M%5%dq]L@ZwF%C_uu-%DBH7j;8(YQs;jetp1v,yUn097:#k0:+U5VD9[D%0$X4/3H`O5
y)d)xc$0"QurN>Dh:e}.X9Hm)I9ULYS1"il?H$l6O:|WieqdpA.IHOZKV!)Qt3G"d*5WZ4,qd0R?{_TZeYA=Ihi"+D_O`9;Mlo"Z_-:6f+H8YN2yPo"RPn^1hG=_U/6_bc,d19NaFwelS15O
O33gia>(o;4qX-_tgdqjX(=RL+,|mqM_fbrze|88mC1"y}GMWMh{g7;ve9]YJW0[d(i^(rq1ZAn=p^4$gXVQZiezRzYbI3T^Ku4yldJ0oumV4kgajOfE:&`-NtGz;IJ%SY`%8-a1l9KyIy[50bI-,,b&!e9,Nr4~IJ"hSoQ}sK62@(vxY^d*.X*Y,>9k5nHoc(FMai<QM7GrEXuCav/6L0q3QC#2.e?A`d;]E33~w?vSVFT?h^T+1)9x/)To+3>">@oRc:aV
/Aq#m_B5}uwJOtwZM%I)p$ci>#t!=-})GNp;-#lL/VYNHg~xw&ES_BTez_fUmc8l{f7$8R~X%K0R*jgZ;$Ab4`RQe@XO
D~>%**3Tr"i7NI#Wqa^yQu"68}J`9Ud:Zb3+I+T|VcHd9j#yt~DF0tGmV%W+&V@v6hh)EiA),G>WtXUX2w!iD4LSBl;T(f)UkPHIjhS
$iV3b5o@*~SZm7UnrrI(]Y^;ZJ*x0lP@D..br>c6rH*u0RuG9%+b[Tu.1{Y8M>*[$1UGmO-5%?0^(OJM050Yo/;&eWsGsW%hMcA#>IcY<ti]=kh`.DV5s337R,YHb#k`/D)UUKP%&H0C&}-"
HR1mvfT-3KzXmLrdU3C`W5@O.Z
8zE)qVlD&A>}00*IWzKNjbNrg37(W]7M]W!fS.afRO(dm)Y,ULG6]#yoCP,0vppn<I8Z57Wr3msaAAE34)Sf=6fo7rXVDdyFC^$]U.^HsJU|wK^H*?9q$.sE(,h~-FyA3#c<K5N1fJd~DH<EsI0{E,_.c<3q9RK&Y3iWZ6
"Mq.wy2;,GmJq2G;,Z:cT#>lCRl+j
(iH9.<%$)j/:^y}gNLG?iHH4bjEy}djx<ESAT@gw99%wa_8kvFW2DhI*Ib?([l"kHM2oP1Dq:)in1>wgqB4I#mzqdCpfvBVS9xKf"i{)XE2nKh!NxpcQ?7WKVlIIK+4rr>(c0,JrIaKIVhw,K$IP!@Ik;5O2$Yg,^ingcG#bmbNKN5jSM#XeK&&_"iM(oJBf7dU;UjE"!s9CXc@%J^>HwUS[E^}Xq?|kGLRS|1?>7uKI1C0V12rvfM=->LbyQeRd4Qes$uzQuWrU|GwQz$-&*N9/yGoC[aQ9tb-F+@#<spPDo&`#*yK-rh~$!sKg(?
Y?7}-S8^$47
Y3u<[b2v])2<gL!MZw)0FoQwi]%8gUmK?qP4rwp8WZDSfPwXGspU5;q8(ZU$?kD@"=]j.9#YQIv~NSU]eQ`{IBG2gqVT2k4#qqZ/m6DWxs],M<m#Sw2~3x5N)ePDJ^L8vj&!1bXtyOUy+V5[pDr((A>7OdD`>yo&(rg5TM>I#e&RMQZlSLsN3M5%_F8>R2Xs`f?[sVI@d)S!<JtHv"wW"v
Poi<O5Bb|Q2s:M%L4n(%NX$G?QC`GW#nwu@uj)J4i7kkb3b*Y1xt`(Nf]G
4q@Ih.ymBgewCO=QlFZv""y5OP_@3k2[)KA`V{"oX@LW9_AyQ3wnJ4FTPYIq%o["Y3TZ.a4C=>&Q;]&B+Z<@_5N~ddBeIa
{%Tc!eVP,=q/f=AR;d
:B1%+m
(s7=s"[0n/^Ib@PYg*gtYj$r?ODjO%u<<(7h*]UQT/s)DQsrwdd
uIooT.k_Hdl9eXnZ>k-@z->!TZLGNFCt}
FmHAWg00Fkfw2i2x
fY&ODPIzg=c*AZci._,3]=/$IL]@=)FfJ_8E[_Z!x&d6&AV;f^2,!-?"7">`J-NOVEe|(uS:=Lj;$m<(-A!@8iq=k:6H_j_~_{0+:>_Hon1H$84L1f
y?f/EeQubj?5x<]^##vx
ar+.Oa
)KT4OcI"33i31-_=/("=w8F?o<VYhuK36;S_/fR&mbzd%MI@AjYQah!R*6ffqT.w=^R]<xaGfTXFOFfgd%I;D;)+ekq3a$lj./5Z[^gQ34}YI#`CICn"<(v<x_z.kZ#vPg_Cf({
@Db:sD1.
J{*S*tVnCL;4NFdOe1:l73`}?+TI
yE-g2-#MJy[SU3
cLv%6ec_5-K_u[nrIa,%9,R7V4>T3x>*yKQM^{@x8r."nQB;OdbUZ<5z1yD-;#N0BESjg)+ybjZ^e$$;5Q)
/L0,WhdK295p$?-LAu$hpdn}F9Hr6)VDGR<?-A.t
!qBN<F8*&xon=BhWFxdW61+qr$6.p-J$^?
p[3)/KsY!cqw.k*DKvta5)BVOAvy3lUO,l8Qp$"l5`j`k.b9l/2]J}mFgT5(?ZZ)*KV(j[dr@d/R8}mB&>c
jDF>][O~f]KBh^`C"DLLy*0M&Ri_I
;+FbF,]pl3Y:8NuNZuE1R}oU[jf2%[ngA4ZnZHka2yK=iQuPKHfc#QHv,4OI]AtkJO&F%8W
E}WC*|(2;eraK/Qul]TI.yr+gnooG2)FEqCT-WYg=1O]^#;z@BO)i;y:[KF.*]t"921VIi5GA2aY#"T(B"x{OZiQCILZ:uP_o8&{$Iy9=?[._vT:kmJ~TW%aHlqsfj.Wh$n?pXp+k4&3e*U?tL*
64,s3qRZfR;]1DQ/mZYD&(06w*+:E!7nfW)TU:[w[VOz
Y?40&lxhlWjedb4VT0$ihrIEwpKj-)$n#x$cV?#_9x@dn:`L~fZtO4rD%(j8tw,6/,7i]R*,1y(B7w9xN]lt`<x
]3;5i"ic96R$;TvyGcT2bna3A#n/pWANE$I@p?
$59ZI>X2SPgob7)hmf#7D."}XUkHa(P6G:kohNAOjC924=(Q7x>G@Si]a)=Z>nWSdG>K,f,u7p=V-WG:-.uY`"82GX::2ebS/.27h7+FS>$(s3TTNZ)g&:gLbYiZZ-N{6MjM0f+EK+:<KO<!pz/%c|Cd(?4tW+F%M=c4HEl6yt5g3s4f&#dtOSN<BOXzAu*w.>Y2x&@;0>:3vO.Q,"CyN?FkKR3-)N/!8fxOiqfwE4mr=6qPe~-6jPg1D-hwrQe[vA#8M`#AGMsP[y!"]A6-j49!$U8N&"T/ZGN8Sic0/l3"j{JgkkxsA
=@oR:[o[[g#Ky(YuHG3{5Y)iA[Fc
s.L35tGPrfrf[mbN,;HB!0*XBETbT=Y4Fx
;;b[!_P+h!"TmXTO[XCETrt?jv8oj[D3KxaetvP~]@OM954]CE7K_CYWvvo$Np:ONsrO.Dqy8Zlh(+`$+*GK6)M
bFiZg1)t<>wEe!-[<+)jFGAxDOQo1yW4@1>>=2t<ywD!
^xEsS!.(@>v0ARgy+8A3(q*%_lA^q;?F3-<*M"PZNE_AQ*a/vc&6}rM=[J]k|1Vr5&bj4yFijblHbq%fQ6XTq5}Gq@O)b)+)k#x9kO]mPHu5Emb+66WLB(ch^!&nfW,.jwFrAa1sk,z%{SD!"6!S}_
r]kw64_FW)3`,vooSsiO%g9#]j:gQ{i=L4Npf}v0?[!:KadR$Kk3OQr@-oI?p8Ul`bf4riMDEq.K0^>.GOpQ@QJImV5|_950.EU%]]W*USB<$Z3_JK<Y<I2>*8@jDopV`pbdJ
:Q_jw;$T"vI;Fb<Lg33tJ]iO60Y]CMgM_+1X.@xL7Sr;TeM=ZD%v2D4E6Zj_#sv2lb5c`j%*ZAp*hF:?g>(Ce0y^[dLEe
r4gQ,}p=x@P"lALxN%VH:M/B9`Mf*_c#e9qaTd2R[8nd-d$[$*8(ierT4KfEgO4:5d;4+F4Vu|ETNl?G+Flh2i<x%}cq6/LY"x#=/AbdD"@I,(y<.ZCM>!r+KmcLfXaVoXDiLO)mf`KX<N8H?.%V;{^U/`3a[4N(6FgDGqXV02TaQ]J^lAU3Z{k5;O9aFNKJ+($6w0P;,6)ufZjb+)VD
1BCrepJ-kc6J:`-3sZLPYGsY[n1_l&W@C5(!aD.m
vHMo(s<-dui|.s&[Jamw+vW_Wbq>fMH_
55O?ybD-vmf48qabK-(?z%dV]@VEiC2MSx
H6d
@"+cpkKIQ%$9@+!H6a5&.F6!N=&]K?@=KY5c;[6C-0U~v=<?)<SK&*5tbVW?9;nW0u>1M%7>A;"!F)FG;L)-^Xl`WTq^$PiRV[D?*DQi`!)Ocq5=GbYm,;)hTsI]oDY@3JXg4/)Idz9ilAL3fsu39UHer[!?/o=I*QeHT8,WxK50,0:[/+"368/S#+d7$tc)3?3XB>(Bw|c@Wm0Y7mZ5BP?-9Ta*7qvDAu-QLb;fobKgZ+Y(;1;l!jSIvVYM+./h.@^K"d^CFNRMa,u}S[[#FTJ
51dol|lJ;EQN7Q.%;7
I8v?~Ng%O)Z`c`#p#QC$;xa./LE2zb_`k6BV
N8:#SngNP
,]fPm,.h6`_hVNw%n#2u::koimF~wnBHCz@q
LT2_cSkl3A4CTQJiJbJn`sH#en.@l@pa:>,sA,mOPsF`x`0q,t9WKQo,{e8CvUJE@;ht)4,H"P8UGZ:<HT-OVa:H5:4_Z31f2E.#cU2j~*:_wN*.)&1uc_.eD;_#:qE-Fk@Y89bW>.<@q+8%9a8OFbx%J![@4VOG<,mnz[D[:[Ta$568mbu^`Ao=kER8zk(h6"&J?&J0`+I`.ObsgxLI
wS0prrE.5]*ucjmB(ITZ2X^w<lIohhB(Lz$A#JJeD/%_,K`c<=il%{TAk65!aj]4evb;Fgd.x7"@.`l%
eb`;13Z@41{PV/4?V)8;1WJRM=6X@.>e!Nned64$bE2iLB0.oe]D-*+VCb%u.aQiRS28+(7(:t!W)B-3XW(8pu}.Z!ueXq`=<Dg&N4~S0IsBd%EO!-4$7lDQYAFY=naQOCy>eR):/,XxNAXwSlt_o;~QS&f5g*:sdk7kXgam9,NZUhsO+&0CGINI/>uL3_
FgQ#>xk;,hjzY{gSmA]Wpm+ihzwvaf5K@Elpa@y#:y>j9W4%%hE$u2Ss/30oe)k.?lj((FL)t0VTXeK@!5:M#~r[c[`Bz%B9(]q^=kEZ(0xY8X[AC#k_4v`N4v_c3+UL*bia>#c5-@s|fhwmfsTb3ar{JKt9tPUNY;E_0]KgCdB8+]GpA7#Cv3F+G
:+H~_rt*."E^m*epuFyM4mJL/?W`+Hs.t0S>$6pBhfS8d(XWX46/sk&^@aAc[/SgFNKMju18Tp<lgLD89vJ.B%/Jq*-
H?SA:M?IJyQ3k7$eHlh{*lKRj[k_:s7-xl,UFU4O359d^$Oq=mS1XUjDr&bKotw0jpj5o_rBc2+BS3#pvdXQe$*C[$(ddo
<VSUVNs9p)HQpA_M1tHF+Bf"7>B!v
voF=#-=J9!gD9+*P*@!%u*Res&/a<`YxBA3eA5FEJG`A~k_i^*0*4m<@gC@!Qt9dju6MU6sSTg)K]KVW=f!nuABmx2!24vNPAsZgPXu7[Y]$?paE+6?l;Zz"j]%pad%q!N%)Xj|
o]IXb:i9*iEuR&E4"UrTWr5WX_n.*,xPZPZY>3ll>WJua+.U(j1VAP
tw@(+`5#bCTY$p$Q(FQ)r#
q[E+q8Qpc8;Ij3EMzs~$_H/B,8y+]&=12e/=|>[[rS(oBe0JhNfn@wh-$e"1DamGootBHnRI]R#*P_/*}2)lr)&Q_^M%p=h<D0/ui%mZx+QHUd~(#Y>;:u1fxbxfSU?E0/tqCe>&ASO#L1cc{1!3HbJv+M
?<ATExM0G(>cFdo@$1p7?@9@Kfu5JRtSZ>m}yD]&m?H#Q8x~9t`J9Ckrc|FPiKrm`+XPKr`oBA
Ch)#nLU>V]4H;j|)TLo:.D1y/VjYB;EnGP_JYx#
&l.k8?>c}h40Fy6yJ]jJim)Yb4V[d1MJ9+ZjOftKpO.fi15boq^T@KuM6D-fmu%S_bgp]Xo%L@h6:U-D7nH(F82cdESiPP!v8+lM
L(&ms[q)KIxKorFh)fLr98eJT{1rqc
l6v%D#,_{9=Ez2d>6K4O>u>!828=]w1G`=u?Re$?L83mPaYRnM..]h?lJ7ViUYUA=]LA~0,8K=?X/uYfO:%F(_~H.FCZdl}5p2&qw+gw,crjm+FiW<s]dQBr>Zv5N`Qlu6&Z,[s0Z[<mB>bs`81"<n<WD
j5!_GsqUdK4ce.w:HU~@
V:er^vM"#oRz6aQ
Ni]SJD9im^$i]jC$[@3N>S]LkzFuC:;^`Dk>s#4?J`Qj0_6Esp9+3=90eLK+8T+FMX)ksPJ%<eA&SqKJYEkVr~t`+;yq&i>]`Mg6_GYeK&FK[{a#Jtm"5p4h8#Fdm<4BKws`B><{(yu9J_IUAj(rC+Etq{jm^4Bh;A8aXzs#@G,5EH76Bp`M9}i<kJX^O~#B:.XKoh`NJg@oVJfHvK?(x[sWU#Mx+5yj40L*"g
z58p~=yn3TsB!V+
W/Ku>&zSx<jGiQZt/]Z).kq<MTvvui,9dHPrhA;uSrHc],5ht#0JW>kb`Cik2hpsxUn]~e[H<L}@>%uF,;MZu+wRg
Y0IPgrJ?,Kx3:O&AyTeWelHt881
lF]y|lW,ZVr%2/C
(QRw*iRP.b//9#-[VYUYT75C0bbm&FB5J7cTnaOk84U#XG%s`!kad_<*q$E7Qm+.,(.X
h:=#;"Pr1GWkHb,N3COA133&.#DiQh:PZs]W!>/5u*Y/:DW8*SG2la#s$*AyH@jKdNb/Ac.r)g`_?{$3Y^3X1+6@p;+d3007#p0@4N#U9_J@LM=`9_/4b4fyV@Gu=$tkm;"c]eKN&Wh
N,mB+KYWm0p?>cY57546x(C:N2.t1$`a=T+!o?Q!
l>(XMD~3G$"fN
P8uQCTqs?
pO|YpXr;MP3<A&PuE6_py<Q;%Y=R
[33<#{)X9g8l_nVO)90;<b>xU`)1<XoyT@
C$}.Fl])DWm6e#$F"9KIk*L>^=sX4QZZ}Y,;>R]4QsBFeid=S#ZBv0M%WiRJpcD.CL`jOl1y6U&)p"3WdjW^>=8JFC@ilFvgHA9b.C4S}sSl07`*-kmr}I-6<ccLj:29KWoy<G$y_1b$`W/!r;n^hMp9DD&FY8B0R1L@=0(J+/er(e]+6/-kR)rP>Pw@4GB;:bYPO*69E]Fh-="o]1|D=wN^3q3cYAXp4%3u`"jG!dR8}DxrsJAD?PQ.`[uH|>MB(MTkLs$Uw%Lf"=DrY8a=1<go*G{$+a?
}%_!u]vi~V]cdVWy1qz.p@ot~3t9wdQLf,!@0`B*3rcSl<0L-<KK!6Vk0=!4yD[Oz0khdsG[aE9yxb(I&F@q$Whk*])6F5E_YO3JVo1FUq=Ln:edNUN2~xCJ{HmrU/k1cS."(E0K`jbIswQ9fh^(WxT3D9Vj}>f9iK]eCLI%T!Scz#@rHf$yH$^3u<1,KQ1lQew;B6a+4Jwg?._7vN9kvJk8igR?""La5,lX?k%B,QITQk%H[TD,V2tO*dDL9J>rZj3lY;E0!;
"]+cMNNC7*?"DLJ*IC6fA{<H*(AbIFMRj22NL/FQK:[Z,H_RL$N)hB>yF[Dd
^3U!IRdE*SSd_RUcy&kSm)WS]2ZYEW8,z2>Mk2Am_6ln,R&6"PiTtI-2&,n$;gEK!I~qh_i[ZU/y*k}$G+7s;1C#1Cg)1`uMONI$?YYLNpm9{ve*?9&WWuu%f=gN7ln2EWOd@_G$xJ<jX
;ElUG4_w1ms6%J[P3a(uSakPopUd8
$oK.67(9*+#KP[SNG;nd,5?PLh8Gwg=d_:U_Px-#UOEU9b,_bxM#6F~#uN6L5@Fo.]cERD+j?*d7u<2fSX+8>jOjK3)>9+
2V7Eu8fcg8@Ew34*QH#y#QM@Huo_+o-4FeDb#j</T
w<FZP;9|-!;bU=^Y!Gt8o7`O$e+>)?kvE&*c%{BTjuojPlox$}`.]o[vlRDKD(+RhtR#%$K3-6O|4<8S%^&#Ari0Tu.Y8{Pnd-VeTk"Ol#Wx$PK/<b3Y&b15-b`"?2-U"m5#Th5,/~AV`#i4o$3=#%@_b!flM$v$w}RrDH
H,mb`q8enFNni[[S;<P4al"P"?=Qn^./:jqh^Oa
2FZ]u?}4XsIV@^xEC!Dpg
8wY"e9nh!d{#6lt"^A|3_#[/vIwKIn!NFP(-`#+`q6,!HkpDKRqaRN9oK`|ngfk
0j&7XU_e$wbB|2{5BaGZ,1xm>Dl9_!sav_]aZu%KwE?1{]GNKJOU8"{UM143v>GK$*{3[T#$5dw6iVz8tHtj,1~uSU77F?U=/pY*xBzGL-E2vP}.ag&/aX[-{Jc$JWBf#DY5F8!R..Ww&WcP|dq)&hIGQ5.!p[~Su-^gRVAY8:vE
%b?wv}^z8OuC/&&.T,NUpp-T2S]{XE2t+"o`PQ0QT4]/g9@@AVYfL{TO];Ay2M.U(J6Q-``Md-eXNw*sscJAx=NAN]-zv%/a-6`,=|QT2XPOA7y+o5
z>U?mCarf[<s.c/ZX<]P@aa4qg0y7^mZ}t2g.5S1D$Kxa%xBAQ
]m4"c+,W)Zh13wlY&7nHH&"sCrRcydbj
zFE<%ww]0OXs-U>wwcmZAA;v>Kst
DC%@I(63.<xS?@aZ=XxwwPy.uY90`c!ZU=uBvUOM-jsrl/DBn4P7*@D2TCGeZE]kmwqn*~uXa:?nX_wCO5Uxk#cF=HC4rNG&nLmf?s"
wD?lR<xcZc^}4Sw~[x^-Rm0Md__J_6U2SA$UK:+E,7@xyUrwZ,)fyxcd8*n|67
^vJw51L3OR/b}2q2o](vnt4uuB|mj(14kexz"B=p/*):A+K.SA=Yz0@Fksw1v9sq<Flos<0HfTwx>H?mjA+y,fCON;x`Ek!/T(RU1f.a"L61V7N+.6v)_crx+3.KJy?Vs+`NK&z/-kx]fw<^k>NRQvJj&%`+WyG16A%(1R3Bz1gJUvARwofl8fW-BPzkKe;rKQC-Z*Pp6p;xO<jLASTkdMAiK,bJ=&;hirZ`bVCxpqpy~^yI{Rs6a5{Mc/*hGx.LdyHy-+
tT=K78X{SR(3[r9U#o=8>$,|&D-/vcbuPkdJbgonq`*9mo*xRq-G*B@*1eYg,#PT"h33,>m#CdRzp@HzrpaBg.2/;("2^VHp%&x67TbpCdfJuLJT/}ANDA"U8%GWMCUt+x@mMsdy$`C+W,)#n:b[;@j-v"w^+5TK*/2qp4@1<>`=8_wqbPH=hNHkq<%~Sim}o7%(w0feb_GSbU!"2u=~MtBTGfr$.[hqYg162}Og<>uO)r8Vm`w.<@!,J*MM!IhUfbJ>23ZW@T!!h@KBB,HngKX;!H$]u?9"_b%2s?H6*nLF1C.(e5*u,pQ*k?HxP,T!Kn(JYyxicG!gWM4I9orhC)5=.Jy1%L?/*9>1ay8peAd+wyi?hIe;Y9]&S?a
^</n-FH]M@J|xldjW-f&![9u9mD=FFD;rG=%eg
wDteSywU4Me+4-?-1FIBO,W/7-%b{oIsO"<XfN4.*W+JCaeY)e!+sZ,1Gf>nkE)4MXbgJh@Im`JJn^DD4*G#NlUvvC",BN$5#;h)9DAN0h3dZs&SSM{<XI85l]FqK>O)$,O9<`1)l+(dsaU9mBDwWJa2*Uz0Vi4+eHB$$KG^Ggr
:FOI-BU468+aKM%dFLPbK4Fq3W9lX5.RAXOX0)^V]sCi4Lo40,~nJ;YK-t+6;dlP:0#mq+pM]OG)Q0[^^1bMXNo79Ao5,Od_T!kC<RF_QpW;QD,v$/~++,$MQH%-%lRDi)es<Qug7gQ!5ebb$lLyd:fHTiwb![~y7fm5k[}c&edE{m)CS5ssmM>i2bSEaHc<t2P)o<b>>xoX+F@hYSxo+QcE
#h_T`zFSr4c[e!9#:)rGCjx44wOyb!QuqT]o,H-w!u:HR6;2Hp+
+f-dLe%-&#%a@X-EKlP0gfrVv*rnW#iL:.**!$CM?IvH:/KQa_fuK}[n*1IbLN<A[a"J$o#v]tu/9<"SCE,t#5e)x(=&!t4ku{E[V`.2%pSB]?;[+1VeveOuk=>cR,7EZ4&LldHqh.KuR0rP:I%#$z!w*6#AU?MS2,JZBc13Bsypf+BdLa?{#`"hv%vG*?,#,UQ~p]Y;]^"9SYPd`]J7
ROXE!Hv2iVOcmBB5^vDQL[O/2iR08nWAY(/K~968QGr7<4P*vF:w?=VK
4)3<]/2e6CfLFHXakm.dYl<_PU&HgI]3m,tJ@1[uFJI`,CnOY%a1ZlCU^TDR5Z!3YhKdXl%:7"N&6&K;!T;/9?-&O"Gr,`NQ<J!2!<B|VNve.?!#3;O_vxwVK2%-MF;v(4kz%8y-Id(Q(mRsCm@2f``8j4ccD(94NH;Kv8Z8NOXoRi.r*kK_&AH)OqnMI;!`s/dFe-ufCLZ;XT?ztlU+(2XpUcgU=7<D#>pn%5S~P0FC7Y.[yEDn
qQ>$jr]XpseS
8QOYrJ3!P}b]uj7m:g,H.Z+*,_9anIv$"!?8b
hJnVr9<eB-93Zyg.6~^f%]9:J^^AoY[$FdYe.Ie{%1!7VW[;rQ:[&|gFgG=df9G":q6w6fy?5S8hv>h}LZRwTfS]s!i3lb-2^)mQ/wZbkS5|H-bzia8js2$A6%2>J"TMT`wnq#k6muEay[L-jF
7%wM`PsCwk|,nCztv?2`vrI*//DrDJ6Pi[yK[E(9<Y5W+G4cJA
5-wL&qrsO*6z)fEm
uM[iN%]S/Xeff.H@b^>5-WCZLU4o}:;</%XPL7ZCW2?$u/T]C3.@nhH4`b>"9cIw4lq(5yMgp:`r
.m"I#IOYQ;H1QGhLJe"%f(*FquYxoM4<<rqS%DSf)})!q*x,nEN.HW,Bo6#RwY<2ABN)f4V8:O"L1Lme*k,K#XOJfqDV6N$}L)52K!%B7)pqj_>
.IvXIGII<EWSnl/E#&I?%^2mTJj|MK1x;]*:7}?qdWDWGF[k%8ymub
jJpl|:sCJiLHYe!lTObQknFCog_>[pS->]k;tjMZu<ho,/OLo;%FoY(7ua"1i$MbzhA>eP8Ip[yfRc0?tH"CkPUD[VoZRy*_A?_^`2MaX8
l0%ZQ(T
N(o&60(QPhy)c2;}*N"`naUAs
aD#)LefO>VC@,:sn`~<LglN%(dKoLDYr((VG8Af$CM2(i|$=!B;|NLuq1#qR"2(y-Z;Q8POQD[myV&SE&L:095@F0@:bUZM+-~AD/wq^&kQBu,lH+1E[V~(8BKlKizSahL*RtNk)xojEZLUcvv5#1=f4Usg}Qkh
V:#k#
)=-6hyDhw:Nhw>L$1Oti,o()mJ1kigE;q7HNK<8,27Zut;>|d%S39No[24[=!=;b6OVX5VnE*A]wvVGWoZL|$&T"Ic,r`e5af-*V9
Vr_5Su.pONdGFExP]HdrlYk3k+Q&C).~SwG@d~U/Vz
7!8-Ph9b#f(CEB2.;4W`.IMqrWHKcm!1M]wQ5^Zi2g)i3V8kfdp8W-P+zPEqS+M&8Oi1X.B/[TXqqKc1%=b!`b4m"#n+A5KC:o1%
.*%W9("S[~d
fiwSo957_@D(%_H;_CHt7ro%sqH{xR,lWK^$yT2&Mc_j5#;,J"tdBV4d=E2c5CBl`o0=d-Q=-T+|mZ/5TLVOSk+irLWA3WM/TT_v$+.V9G6EK$.Anajbl58`-982&Go3"d74Gv&2g:CC.JZ3({p<0}4#Jw$V^;Qf-))Cp~Bw5v(;aU&|[)tCB^f/tD
.rEeF
>4SV1bsK~F,o|7Z1avF
8W)!@KwesVn[OhS`n%JYP"/;YE7he"sI-U{bdHea2d_.BCQ$J/48<]OZe[C(zWs,HoYCGNG,w>.
YD3/#)IBdlys:Ex&0QZ6l]"H:);P`C?CMy(3nejkR.>oywsK56Lp29$SKQAa5^{<.o:?QXVBaFH)f@@)YS9(>bwZA6qq`>N9f#yR$k?qpiK!Jp1#iW*OmiS$8ty5oi:&kNY
|<C3,;??)$d(l:EsB&Axx1|LfQ2KmWbR0m._sD1U&3;8<Xb"cLk[9v/,:drGg/_D,h~8ql
QDo5wxHX]l;WZbEDfc)$5b/IGv8,?FB-oW%xQ9SHO6#v9pE<tFnxM>;-q-(]qmt/f86cTIca+.wEwDtnaIh+pyomi>k,5RE`Eg=ll-9.eHa!dmat8WL@AQ]xe^xsJ.5PyXF0&ir@IL%!LZ+<,VBH$hUTM-P%ecxv;4H>MF5J:ERRh(
yVBsHk1Idf$w$ooE=9*ua-Z%~Y{@
0v@55@CpJ?km,"BDI4`L,ZR*;6L97nomHI<wP>cFMxL$J;<tWc>Z?aE!E}w.ZhG
5]fR$DRrA?S
^Ztw!sPKnawttTx8l(SkwPrZ8;2m@$HSmQ0OW(s<hMn1X;B!nXuz82>6)h9UZ2+XXoqyFHS3`7-|e.8TIa/8NuZ[!a3T@e?iWcJCy=XDpHI8enn1xojYom=G!&ds;?vIz"h@=@[x%7X0uz;:K60iNP)kC;NON9)0]OoX3Bd>9skq%h!|
M#l1_og)Uqz.#NM,lGyf@cX^a?Zs=gftk&61
T:cM^ctr*|IBKPc#VY6[Cwq,X[Cl)9(8N0c>D7sKeNwo&W/=k$1:&:lOOr4sxU`/=0J:"8Zs!MV&
:`c4?W%nIrTY4#1e]r`w1xWJ|Rx1yyxl#TRiQJ<L;CQW@OH49xP^H*bP*a/&WR9n@iY6~$1dzWQmT5Mg.pEs75Y/16.=Nv>Q14^i^vv?=gv<Ed_#$SCK`$35O63bONLh2`2HZ2^"m-aG%#8Rzg[rek-/pc0<UqR7KopV*v)AM]xLscrA933rL_WkF6<_Z,-7B,;w3-)9KwO:#Ausvpu,&T#-g`]Q)<h$"fuC8)G&&*dUOT:[,WfX[.QSQwHbH`];7Gt"GskTtwpI?hsA-,.U?05a]pW9vjR#VPxR60@$|xl@3E=L
y6qCwQ&[%$`8B4;{8xk>
"U?.vLp8x@2g}gxgbPcjVN7<z8]xPD!e+d1FeL</r/pHeKqVs3<tgQ;42y+&X%X*1fRc>,>]^9#_[&<N5ZUCp6|d@4=p)DE!5i|y&f98|*}vn,;$
<*E-:
7ng!
F?j-=D[M36qk"#qX}i$c37i8Qb"y84L]6SOt}8!.iUeLGJ~xg#mG:,Q6kI8ES)LtW$6gr-[$8j#MGc~#{[kH:>dk`-5:WyE;ntaD@QI94IyrN[<7}U5ZcWF+K[e,PiD/ca0(9P2ZBP7YIyKL<
(u!"1g)hA6zS]uComrv94eKi9
P8E>r-;sXbErN:zrCHp9vW~t_MHr0u}q*5=LQdN_CyH2
C>aN,:a%91.6b=kgQ~*4#TSQ$]%3s/.2iM:!SkG`;:3C*]^O:4^Af5Yj:3v|$~-=.X3VR$0iH})9R%HFM$B2]b@8uHf(k^h?cB-FWtsYY9dRvQ>s.7jT0:LNLv0`L.K>QopuMbN8_,&vOFEzIE+!`,me@EH%Pa0qqPJ6R`9Xu"FEd#!y-g^:cdbaPR^bjVi
q~[8wC@YWhCDii%4fv#M5U<xQdwZ8r.DDDo+pGnY:{jy-3D0@%V3Jp2Hc%dAca=`(cd&97,/
wh|8>(xM&vyo|/TQqq8F5;3KgDt%&-)O.Ev
Hifi8"j6gsat1-,R
%KkCmGW2b~x57Lr7S0Y9NE^PndG[pk*}8^?UBe_,/EAi(f-thpvRj$5yxlW^K&V7$7MSaQ,]N8<Dw*0/viXnF&Q-?97wT$bQ_u_k(@tEW%`+bY@^CjZf!0i)j^$Qydms6Y6
&Hv>v:Ds`7UfFsG(9l3~>kIuf5&.sE7?Gq6At3R)Jm.4cA+5"0yN!m81g|/L^YIb3o+|M5I?Pif8<StLIZaB:q^xX.";tk,wK}r=b23q5w7@OY:.Wh]B4vVGY@eu08pr0xtu;]%MOa7SAoT%Z|1l]m4%rA
x[P3WDPA4U">u#V:y+~xa5}ocg4`^7|?YX.Gq&T/8-+f*)sP4:e49PKY<be+Y?hD%yb@ma>VjJrXPE9.heOA2NF57+ep3lK#m-yF-iv4vvD:&65T@,h,|g&@*)(/[Ir=:5"cEJX&1&3=|Mr0ajw
p:~9D?Q<24PEaD5)7&a#QgRa5jJI*$z^+rVG!Gf&JIyC:AhG..fq}&Fm<GQ@JIwho?QIle;DF9YT5
gh}v{Gr#f%~mfs=v0Hda>Nck#CJMkeRy8$@97nE_)Q4ZoENJ/[?2=dXv=dO[4"`V?bv4et#)]*@tYYl&#;UqsPY&}K9^"q7.69@.S"(_sl5N<ZIijCNOsYfmPaG%Hls]Sh*f{:3oU^eJc(T2,Q;)eOR6.5kgmOB1`@G9cn3)m7Q]xYST;Wb=
?iKp`KO6-W$%yS:4Uinj8*dLix:Xm{>AN@do>|3ae]jiT^f(i1TA*L])P`3pN9-?WfnzDb=uYP0zF#=e[C!aOB3UR$*m:_Ra[?&c9r1Nf,@"Jk+P[Y4,[`5VdHN}M7:`2<6dNxIsmNC(=f%2xMSe&,W3,|c+q-F?D_8pR3F[.FcR-vwJ_,lb=NWl5".h@@^PNe!9yf(
YZ%-?dW<=|.X&Svs:h@<pryyffU:PlM
;{g)x/3B@2VF_@(d.1_+?ISb`uZqPsHaF1p30`J0PI6r<r7ZRe17;RIjZ+]r(FSG<vEZrYm,.3TS_DE-HYEheE@pHO=Q.{GwCPE#sB"K"vt@!S9ir5-Q];h[Wx$4o]b5$$DB:|F+td+gr5Ty[2-FPq!k;:2hXwY`Q5SJ
hDG<=g2/MvE6*ZgEgSK?(V-VP9;.+,<j
nv$V9sGGXIDzo"hT7a@YtvLs$}m]v{Fy@<RK)a?}#/02dPmjby3.=:@_9[2S1`NxQI<N?CUwG8JPQ+(;9quR)q9gy>_]#SM!#z.h55J
]F)rFc5h@tSfe2!5#
uk.vhpqg-[N8+N]lp^r`1c)XV[=Oq:j<Q2gt!|s[s/3
r39lNX#>Pm!U/9`%-$5d8;Yd#o)@0:rn"qV34"qdNwXgKQBec_%=3X1o]?AHtO2[%pQTDCSysrM07G*$YxnlZMGX#jBBjunYnzEqw/,XKkI,Vf"<sWiSrJTo;meK@`7Mik@NK{kg.wFIrc/[?~gn`[BWIt#xBJxnb>R+@rkLEjGB"g0x6:l}E#2/q+v<=WFms&wcBfEK)kX#>DIK&y0@Hc:J1%vc=oY~bc3/6[EO?L5M.xr[SCVF`Er/u>O%%)r~+_,7CAO],]6(sv>d4i.p5<3E
a!OB(v!30voSSsjA0EC-}o<RA>n;8$%7]-p"H&UFLqAZ9yXlZE=$Knx37osYAdl`I!kvHLU=Tj]o>L(%SS?<tf+DH&q`]qN(o^8C/BP
3%T@etZjYKu$1Z0>ifb@_r<0Db<FRnV/s&b1O*cG|A,_!JMyYp?Xc_|p+<!SN!
tV$0Z8>SQM(Cdi,|a+Pn={V6Xb;q_@,;h8h)GhICOQgPZ^Y9tDBsOh&F#[J^TtSEIpXTTY^8"zHa=`N^yt^A0m)zw?mBuDc`>xKso5CLn=Ajw.G0iPycquBe,^/p,#B)[WbAKPpWw7d"@>Bq%V%`Uj%^$z;!%HtQ!v4Q%0Ci+&e#S@R3!P;-p9qfZ"H_=c2]Xk#f=l=MJW$z.hN9WXI<BG-PKh8P;6b4t*5t`aX&rz@Ql^=J>JBYq
?vGOb~w~5`EioCNd:].Y7[dt6H1AA3I!Dp5bE~l=:I`1
,U/;+>C5^jOxuAHyqovFap`ilt9sp:
dlvx^V7{bQLE`0.4Xc@P>}T|9Lf!Egm:0*4BYSZd
==|,}`+wlKh.jJnY;V8SX%rb%v49F1fkUkzy*hJGF3?9`X[U#>D8s?>w8tY^mO3@Q%$h>&MXP&}m
#tW/=+7`@,#p%{CD2Q!#L4s!AVV}kfq6LhL;xpcDFt_=[e?p=/G`OzxY-lnc)vAmBNu41QlT.$!K>tU!<_
]c?vCtGNEMm>/_hF_*x21;pN%xoqCxF@s"Q]@WD3+Ixx!U#)x/auC=FJNS37u%E!X:!$@JFv6wL-&f;7qYnr}TSvx+ZUHJ7_t^/GkTddxuS=LHu_mO,*l8m-y=@KUUdWg&Cs#;/v7k2q|1W.#!#mk`3;c"N_+XyXLJ42s"lG+Gs:IGiPDShJlQAb0trJzuQk:vD+z6.xNu2G[vn]vn1a&AVo7kb&/@5UiBv>pL|=QJpX2X?VT<5#/Egy^=0L@sH,o8R?L2&2<J!L7<>LU&v2L[+kDaO-]bx9<%w/B_RlT6/!;7GZMQ$OXq
fci5e.)~=~^58tbjhYizTm+=SS1Tj9yT.<FeI7$u8lr[?6:+1OWMy!G>(
d4C]@0>Te{+ZW[Ryw167w#J[
hd&J
<.Qlp[CT-ejk?zGtdTSc0`nyQPJUV!_j-qD=::[+Q@txmy,EJ">B^p0w"Q@jBzhB"ul#
TsPrK+K*Owc$]R6XP0&N525i=<
:7B%v(Bwdk:NBe+jis4[KTu)bej#F43>(bh{8%!j
Q%SY0Q*Nuk^>ds4->:9GFHF5SUP+@Xkp!wYo`)!LsFscR%;>`by=FmWEgR0)g-x-C,G*&&QRfTL4sX95^cHbBJHKg1cEl,O`2B!e`vML7ObR&eThr(Aq^Tp#e+}qsF5.K;Cl;&koxSgr,?kUoX+T?(pBmFm/f:5$>*
9?kqg+CR(:MfpNO.7{7[Gc7u.2tOgU:;FE?AouJw<Z&@F?b)5h3Maf4dIE3Rd^b[H)T3>klo.3hKTIUi%!*yUTCtDm&gV/2Di!`W<!@5:@ZB78rLjHRC68-ZyVPkgyaH&(Y^#_=RY`>O[NuOfux{@3f
I*#+K:=ZUfCLgt7euq##(5p3bs)zSwXB6+6[<fo*^th"eQISHjM=h&Gw0qBLDLZNc|1RK_I!V+XNq_!G`@V*aLY$@(d#>G#GWcYx5Z%ILj<UZh[Q.=[k[DRPO]RlPHA[K"1q6raXsBLhlo]`Ux1#crn4Fl!$F|>3?ctB.%s>E.CruE;bLK]]MFyAA5R0q2:S!)kYOIvdd<+=_cEL/4D#b5%|Cs%UU!u$QJe.3,bB`H?]bRE]^/c1,wk1QQ4VL4s%m
adW;im4jiw:)eG7hu0s.v*TI.)+a$l[Z.C+4p0Lp!j
v^YSO5WrW3FeN4AYL2
waJDBHU{w!0tw1Fu`tV4v8v4;aGE_Agk/i9vH*l(Q+_"@w@C
j1?(Cb$`8]WI%o`E!c`(E]<
"__F/3+ON;;>9J>3
sN`n-.4&+
!$8^HAa.YQB;9%L$t?=-__;?gWWTyX`tgt"F3D*Mt/<}TH;aR5S5>n#do+2Smd4kU>U>kAH~drF$knqNHR>r?npLr1*FSZ3G5oUSA|)QB*jt7dl+^T;tm%prv@#DGM[A*qu2Vq7hq)
UY%4|(@uZu^sQ!^8:DaoSQc2)4O&ic^(z5_S^`N401|Iqk`P+m0JKy{=jLSs|K*"*EwA}Q,2c*yr&xB$Thgf&n*A(:pqn/%6|OCNv6W?x*:B*sU["_,<.X&k-0f-$uOax*c1Z7c8Ux
Ceg+?
:84tA1H
SmpUHfgbtHn1I44@+F/~OsNj&2U^+&k#i;j=J7`QD)&,i9AZ0tZe:W9^GdgX5-v,,RP=
gHF4(/U=HGWqL^(@g`/j1Gq<6wRk,#w6<iFP,0HMay)e_uok2Gkj8Nf.>+o^51Lv
_2auK+ky!n,;*a-{/_a$AG;{tEhs[K,;^{av4^t9oDnq*}$No~j5_pK7[.<k_oIy[Y
~H/mx73]9c.E|WM;{1LXA]~;gbX;QlLAV8|t4l{%ca,^CvIh/bUCOr,nq+(445X?1W[*lsBho_C
cLN@*rd+r7+LNs)yJHA9|v|u{CjK(,rX
H1l[(wGEYS:?X/uxn9F?SaH"L/0{V,spB7?_t%5v`!LG;.^3UNcz[[x4(kJg@$_4G-gVFDjJkeI.8Zt(G[9.1_F#ON5~=,8578n[n<bYEnOkYZS=Jx[Bt;!K`sJHX1_6<MG[N7V*rN4G_OUYftyA+Qa4bo`J:,A|"(%krgn?BH4&b5$k
xe=a)`)Aos;Ae7<`Sa`]"V8Jf
=&T`sa4
=y@a3DUc0f
sRc;YU1L0#ldkn;$g,juL<+BY^%"kJ,3Z}J~nZll"[nLQ<7Xs;ja%Yn_n,c4K*r?+=iC>7-^;bhd5,r)):&3&8+$E><kx[JH+Sn7Zh@EjO%>GGiQ0NtY+!VrI,n653ZhGnZp0
w|c[v?Qd3dWC^cNsy.Uz4)bRlfs1)r$<G2`ib=$9-xq,j_P-l]GXLZr|)T_gUv?I_MmmN*,WHn*uv:LW`zdTrsd/PTrM0WhtSJB*iw2
TKhjFJ1uJ$Y@7QYceJ<TEF3BWDIdr)T`!qQeC]ie>CglL+Z+;elz`jiprmC-cmaOlFm=wqF{+,:d`d2A4:rtc=)q3a4]#+X}bY`j[XD#]/#OGvrHnN!Y^u3hy*-$,:
aRga|7BvE[;FKRg1YqoirD2q*AJH?0GP>>17Ux+R[
](`#kww;@A,y>c:(LM-B(v,S)R~hkJSQ$=]8=yQ+TMOrOh?JPTKGQ#V8B%yF]KWLwJ2f%G7ox),J4@XS?1h`uGEvgJRY6NF`CpDm]"mZC=5C|4y
sEZ*x5<d{P=Sz-*X6EIB8I/o#xrt13k]jZ,lT^<S%;n;.dB9
y.eLj[D)Z%H?Zjt>m}3r#>c;?F=nPUq.ye[E9j2z/}nEb/P5Y0m>
|n<>[e8l38Ic1i*Lub{J_>c*D/t74_F^@uON~dCLN]nfs2lNp.A$7MBZSLDj#m~JuSnt1f(LLH[>l&Bb8T)Cc-%4Vo-"}8xR7l-RhYBADjdNq%ZHo7bv$QqfZr(R%FyCA56eCX35SI545JKahFQ"7[IM9vTXAbY4N[5_e/`]`6>jSKtG;rn`9F[]1:V,`WQevc::U6EFyT36{Q2"trjjl?SA%!g@P-K[2.OjckX*)/;5%E"?gOLb=m;(>=TcyblYXK,W5P"_FsP=9Gl`^&4T-BZ2b^+r}gl

R^sH;en;igG~j3Cz53ABD16U%jLyqAP@yv*qNrK?u.D;nDQr2BB:DjmNm`F)4ohQAOi@+#*;G)e_mI[[M
Cw:S,MhU7
mxUMHA@~9t_E?^sjC]W"`5wuH,_.xz,.R3hedbAq&znl:s<K<kJu$19pT:bXy;vd_tcZ`}n;=t;Z
hFtqEp~Zp].f0TBqAZv^CuK4?;mnu^B`)j`YM#Vr*`bNh.P17gOO:%f)dvUZiG^@Gf.]w1dr3Opbe);Cx^=@]N|rkk5JOV;Y,yrPJhhCQsSf`G~ug_92I_og3SG[zbHZ8Gk085w3j=5>~n,HBvDl}hdC5caxLe=p//B]3!`]eYZjdG!a$8|Xg06d]p]
u5H]rY_C?C[@h%Pe*5fGFCro)_fV!bvjHe2t3+A"sZ3sgir;n#wT+T$r}^uKC!qT
$QH|h
l]N`lFV[ubtrnL^
vRA>H(4^vp(Ot3ESlphOq/A$
-`G)]-i2O/^#VQ4W<xH#NfE4:vC_%#{Lw0$"ftq_jAXWaQ5$N&Nw+>[Pe>KO5ZP/v@v3]=sV94J><r]EH<Nv{Z+ma*C`=yBGKbxTWqZs(IQYR*J0pc9S-jch!Fyy_B8lm/`Q8%8lf^drDOSVqW8Dbb59b)gE3rpf%sgT)UJVVa,RT%S`"miD]gvRkkXDeRB<n^cwVfGM@Z4+3lQ[ug}j[]Y"g94rE+kw}4")8/U*tkbS05d`)4q3@sPF[`gwadsj5LG(WmpP9!>nqPt?tP4GkEt+Ek#Qqn50yO#Q,!QvYW0p$3JQ`c(G)nK(P!`@P]8`|PI.PF)uF:BseT)?w_*(%Xe&a,:Gs5dCP7`7CV{7-c3
Yrt@q-G(^]ZMKi/PFmkOhB3MF"?yFY8yow/i;OJxjW;bMHR,7k:JsoGG_+3LWx4xPaFdiL?U1BKNJho
{XI[+1-g^[UF9KcX~`F5&1//ms}u!_UKHeAshGO:+IPV`pg<mc6$l*wmUdbCtqg#9Ls6wYRUFyHo"KUc7/ALm#I347qr&tGh#M$
jBF.,3BD
2
9j:)7EncP,Y?Qi,>[%SvZudh%@%(GGq`U>Sh8G
0H~s:v/F=qAGKjhBNCh7suKCBs`@B/t_PN6#l4E%T1b7o!;xC/3CA;W7J/:s_rD-Vb51jjg8p-IWCTy!RJ66d6Ph9NTCjH_p5q9F/U+V:Zl-5"XkwDa8&
Dy@]FXG^h!v)&F^4gR~8%K3ZC+V3UAnT@dJT+>&>%wD2=":b<K)WhF>`0-20g]fVGF!L,G;.Db`Y1i?jGu(AAVNo,4x=b$6w):=m"waHu@,dk^AB;QTyJc~^>R~.Aok!MGS3
^RiPTLx29fh9"A?CK<"6ns^+[!j.NU#$ra=q"T<D@ou%Ay@wV&uq=NMW1;]?X=jOt(tQy"x<eKwLOPu[Hwggp(rS<U.4P6*kaltLYYuqT|&@Dcnr/>Yn^eN-Vi;l*W,3TX$<bV-9.esB8iY
/>$l[~1I3;F{>WG|^2$`*Y$jq)F,KF!rlqFQ?z1E]LOY]E3KrTS6"{dh!~*~GGn+h>eyfzaJ,+RpO]2aFYb&omLNlrn|#9fp_{l(k",Ky`7.^M!W*zn2UTRkZ3]d4~w8Q9.VvKoS:DG`v(yRq@6JZcV
y?:ni"e0fm>&swq1.|i^9GYhFw8r5~=|W3RL!|wiC},~_$B=gfT(.=w,?,0q9@Pe:Wl>C>El6@rf<};tO,bWlos?O5<3k?.W=&roADEHbKC1by&ND6e>#b<Mi4^HpDk5U1dMIlmQ9+Wkj;y*<eKl7,!c/
:+8Q6G?fW14m^0BlaY;J421f#oWlQ-
gsWn%8%km`zDk2=,AO6yCZq9Fqc=-Pwx<1(FWUI8GTu?<D.sW&.I*I`[Y#COR)8Kj.=FaQK]7$>/KK)51NMEhH6Y]B[rP&pOf78jCZflgQ=e{dkmVqrUUow4=lvHm&MkCLHOXV!/Od>nq68!ZB>#9K>qhJQH);*n
dw"V4~@@Y2[Zc?gH=#33^mE.gk]XJu)6A$
bPndByBJQ1A@!D!//FS2!#EUEW)Sif%5wPLIAu]DJGl&<Rw1%7OZ64?urGA-~8<N8e;4,W!j{39b-Z~dYDQT/A9
^2#vQ;MYEYj9d5-$IhcuT/{WEOj9V-8/:&u*|9-%/Z
6h.K02--Et"d%wCqOO1SPr;nSy"uSak9yTVfHE=f/@Y/d,:Z1x/PJ?=tb$8g2K*`VRKv)0D:.(Ic[AqfjTSwi}VB:"0YTZ^VaOUNQ`4A0UZTY(029P9Zp`V[/b
9=t"Hj$3I?#B==uCg?@Q@#~tp[NT<970]6HdUUDQ?9|F2901`HE
u9m-d>8C6ii&[A+OgB#,RBJIm[pMApVCOI+Zb=Sg?rVXq[g#vp[Q$)gS_r!%Ai]5=)AY];nSfPC[:W}sL2b!#akPv&W;O%UQ;aWpPUFkk;C;/$LvpCtD?8%(|
SeU.S&pE~?18.QW<*gd#X]Y9INk5%O:E
-0/;?53(mm/>HJyL;O4fQ;<uQ]?;o<%l
1<5cM="Y8j~AspZ0Nct.6Z3ie0@G;-oPo(vS?P=+m>x:u0D<Tr]tOHi]+pC-ar60A&W/t^HQ}LXrh]P)t+ALo)Yd_Ys*WTw>kx0_i=(")U}#7[BlGbrQKtR
A6A/WUBn6mKRG:dNXRqkPA>mu8e?X7sxe`K&"M-i/xwm:]9Q#]+p)OV(gb](A(PkV2AErH|wnS>=TBjN&Q&Fc4B7-e/DUZ(2WgiR2u^:?W-voxF9V@xmhgBSXYCBEb
g`?YW(aq.}EAr{az?c"PAg9DBa
6x^is?D]lf)$Muhr}m}p3wdU=@u
v_nCMiP1,TO?xC|C6^.r+@"U2*__[;8._k)53(S(d8gZ?>+<}9&>y<"?Z]fk2#[?;!K[]oa+073a|^)x;Ma:t*V>7ZP-uSf:dAKf;LE7H/MvQyhXew?.q1A%26k$RKb9)Rn6QMMNxjUs1"U2MfM)n)g,)WSNA"w2WP-NEfy256U1h-PCihzE}"xcv?!?#alB2kC#7+N(6ama0q+cqiKU}`mZb-F
`F&wr@==`YjngsG(l;%tmm]ZBwM^f<T!fV6*NgzQh5V8^n>8N(`r&F%
w]"_r`IK(h;.+>^C/
T_~oz@E1%oK9R=~ZuTNry;ni*O|b6q`v1Y`_R9W=%i$KP$.rv5O"<r(j~V[)%OC1Bn(-~%YV`)CRyp{`oQ]SJDdXl,8T}K0S3K./O
X"_d!gcU}7&vCkatlSXU1T2_`=!I.XR`-<AVb3=,2Ft]2Nzbq9AO+U{#(8GF.mjB{Lp0.S!pf:W7TyW*->[(7m?t1!rP,a5mrQ&tA4~sGLN5[$U@&X@0EPi-X1H-!M|M2u(mWUb:gDW>a"?EwFI26=[KFxT(s,3V>7YI<YeZFITEc+idu.k
FPiEhZTiWchZ[v0/L0UV(S
C!
Ga&R9JZXV1<%faX:-r3DE!d)d9YEq]8.V"z8Y;T*uD>$x$:S>D*bp;GoS:Z0^<mAov"#r4HQM&8:2KEDpop5@bYG_r$L;iX&7S5KN!!3b9}8SV(%n6h27_2#"p+Rb@w
cT6X@:64f@@#Ri[T!s+lZ<FpzX~kF?,]~eXE;VHVY%S2gT3UG^qB]2op=
*^KA~B3;WHb2VOYm$T>A,p]q(UdVmacUvXm[a&r:B,6$$]DrF7b1V
^C0!F]7I6a[A>I;R|ul8u9Wf)0M
h1.y#6qo}RUlZyoD`<>Z-Pup`#zihSK:mFAyfkLP*".+%):GiC$)}2m6=V%XmjN2vR7l=3p@[?&tn)+&Kk4eSBGC!O1#U.8htr~[nN2*$T}%Sf7B$[7f]BaSs^Rbqi`ktYO00R?A&;PN53V&rkLb8^"kl)efXpEoaUyfQ;FcHY2w-Bl!Ifh-<JqH9FPNKD<dMUy"(VD?Z&_S1=4F/VV`g=.EA=N#-l!;ooy]!RwW=Jg*>jj^ev~P+n:<W?|M$[SdHisK@T7p#s{MG)nMJHWN-x:"F=n_<I;FcI0hlA3N`M=5dqWuEQphcwu`/BMFw&
b(vsx=AZ5$L>YI%jZ/L7*d@GB-@5Dtn)Q2uoJAJzpyK{jPVrNYWhF0$Y3mLKm,Y0;@uSFd!*IfGfRpsj_TBnh#?ou*1MaiG{xCBdQ#u{vi.:qaxK=7%oY:K$8z^$PXA|%Cc?WBLW1pg~LFYa!zg(A82gbT-FK$8zt&PYA|%Cay+NLU1pujL;5qstV,Bk@#vKZSlz+T4&i#hkK#ZAZ4qWE0i!]>n/inbTCTK#9%yQNbT3Via,s#q-Gmc^`1mYvUIVJx@)mMhoM^5jbO1%srEpM(M"lVP`bNq`lImZ+si&,0X;I0GMu6%FJ}Uti:^ia-5hX"6?6Lp>lypB*1Nt.iY"?rn;GMmYyR5jbQ.TtAnApCI?t6X?cLK#UrIdHK4~s(J|uQt"Ovn#%Fai+TIk2;p4n]X;WHGgt5KxKsJuH1l3a2EX`yn5"+[$HPbTx.a~`ITs?r1fHFbTwkan`ITs?r1fHFbTwkan`ITrJtu)XabyQ{^8rOm
KSgo2%n=V/GFp)7<Kl)pBGjua1u(UWB)bQ24lkf(LVuY1abm[dH9p!1!b1JxBG_PR&w.q+ADKT=;(-F}N(q8$du(wF_DtcP#d?;
=HB[rE]Y*<G][sF:EpCTFu8K/>arC<_+-4[aA3<p?c`sup`ZWn^Jq
y.0ys.AFBMlzjXi-l69}cwGfv0G`ZaHQ.qH?0g67B[kgvh`s_>`[9<d&Vm@6*Tt6!WmRKPGm8pm+kw1<sGqKg?sySBoqkw1<sGqKg?sySBoqkw1<sEX9VkXuX_x=]f]NlznEVkXuX_x=]f]NlznEVkXuX_x9]f^1ldnAU(X-B]k2]d=.ldnAU(X-B]k2]d=.ltnAWdc(`RvYLG@s_nba69Mn`RvYLG@s_nba69MmJPvELGAV_>ba/DMmJPvELGAV_>ba/DMmJPvELGAV_^ba4sMnJPvULGAV_^ba4sMnJPvULGAV_^ba4sMmvTvMLGAV_Nba2-MmvTvMLGAV_Nba2-MmvTvMI^@=l|lEU1>w/tm<!Ym<iajRiee)kfNtkkN
JU7:H;5hw&1Mq$X`a2WwxVklIRi%A
g
M<FualqV1mpqX``OmyunUzIQiEAXi"M<;|alEb1kqTcaZ}my].0gXP`OU1_+kk3OhzA8g
*SFu*gER1Qq$TT`OWwR$UlIQhbA(i":#;malEb1OqTZ&`NAu_lUlIIj(A8]~;FgyalFzs+I)kk3Oh~@Tg|5TFu5hD-q4SS3]>SS0%Vq$MQB1JR?0B/Yy_iI:V%uMflAxyRVwh!wnEj1{t=i3Z{x{_xV$Iiqm>NyRV}h!5tIv1[ylhQq(mw_8V4Muq=ISyQ@[h!7zI^5iMh]Rq(W}a2U1umkkIRi%AXg
5tFu5hqV1Xq$Vz`ObxZ<UouUhRA8i">o;oKjEN1OqTY)`N&D_DUf;_g~A8G|01gvZsE"]?V#U-p~jP_TUi<"gz>OG
@igv/"D|
<a4RDp~TT_S?oAQh}IPF8@i
{1hDlak`11EvOTT_KAuA)khIPg?@i0W1dFsakQ%o[Kq
h6OI;rmDi+Gpn;I7Nm:xC4LEDD3jTtWTBlD7I>P>g?8`=yrMx8Jh=I,^Qki,DWkPE:Dwo"DmPEDP
G<^1*,DaQJ7@2d_;iR3]&$2%ko"|TZ0[@}0[FT?3R.B7r^sRl3;2][*|4*_WF?G/wv,lq$[Ud~/I2{46
N1(OAp
wnCX@kB&XRKr*)ie!h^4,=c[s9kXiL<f*R]mA:0xbT5,[X4DN7N#_IdtpQt|SZXVk^JwN7e[bfRJPbu)`Ju@s(E!kE$mI0UUa;VQnw/WE!ZQhmp]
Fr[O%q|<LJgkMZ|*"49"XR_jbV*bK3.qf99lExa@eM=@oK*E5B
wjDHEXs`Zd^l%>&]b2v3c*l;UV%gEQRLte07I^5.KimiRZL)H=#qr{a/I&5ov0o@Q9d@^>->LzpR!Gkxz(eC1s*?gKXXRyI&PpZQoZCqk
.wC<J_=z,6cKv.dewW>9$Rap>F%U.#HVpp);x@j3,dG&x_CiL[^,HfDkntS&l3v`,V
1_mt02lLnAka5@X+1x^QDyESfy"n1B-YXc`%T(sI037l[Eo`Z-Hi5xNKR`KLQ26$%SO:M<PR9
.;(p)?cn<qe@):))sMkEa&o*ZuJDV.$SFqnqPts^c_MHN7Hh&P-L.cLL=,c*|Ma%%"_Xx9E3o"Mq1f?[byxE+/aoK$S:"(rRQ8_oa(roziIqrd.rMe.w|(XqpR2BjU=2{PBxodgv$VzNj[95|hqnhZS8_.x(1K%@@:!L*GQt^SuE.qS$Ie2rx,z8!5i6g?5$4)3&[n)Xc2giFB1TUPAC^(k<R,UZSe*%.ck%$I+?CV7W!lGa*5/!oRPd`];LmC9",
nveFF=Ci73:T5735z2q5VM_gea<khy^r-xn"3QIC1fw*7Pa:u7gWd%E4A:^KY4|hn62q6A!S:=}[CLiR
JGIxox9Vq4@8GNyX71fc^8sLSX&xPD*2[BNMg2!,85g+Ca9)D8y~n+!hf:O9_I355Gv$8"e,%!eqoXq_N-XR?$Z@oJy;[H8YWA7oT9?G,q"Gd#P"L"-1&7c(6PSO_3%j,;0Zo.(zSM8eB{6:P3d<&M_8I^Ln,n@}:iQ=bit`#rXr/+O(mr-[u>NirraJWG(
d&.@I/
#$jD,GgMI!xiS8U1kHU@z&e-GOmh:j:lb
2foP#*dM5(,%x$hNp2LI86e1<,pU~y9O)6_C?:!-heN72Ri.$yK<F#Ab1$Jm%(fr,?XGEfw
`xD:rMC-.8xT?i|fi#)bkaQK,g:6(B6OQ455(i{.$Y0[u+L$1wGV~Ze[3;_*c&EdNC)vD-c/Xy3(.);R"TK-iY]v2fQ(5tc"-
gd92f-Stbpk;B
8pPoKIHVd$@,<;EXK<P,rgW#<[[%Fr2)59Z5g#U+~M[Pi79x3NVS(yNm7YlyK/a8@L+j6bn%0vg1:@F;#Pd=C1Z!d)S^*9,,(SJPmbE
#u5J6ASu:+e29L"8"-xl`&UuFKE&Q[jd.q1>!vYu;PNdr7XLg=
o6b+v7x*g03X-t-dwf((<O7ALnLP.nbrlxYpT4mONzvV5?;5Gv(Gws2ly)!cXQCA+T]sK>:|!ev-VX!tI-LX/FWs%9$9)R]7t^R#RzSovZu{wS>.):8`_lt.)p&o5=NN2DwdZB%
JJq{=;3ETE$L=7Vj#A#.98.nCIp;VLig({76!6&>=96b9W*]$1CU:TC6+A@~>:C>_n"Y(.%p^FC);d)
UAE/((m/?g#/tg7^vBizM/0"E)"4#Fb]WoD
2sG%G0%Dce2hyFn+ZU12hExa#u4?lDx}NkC#5o6ZwYQ+=xVc-/<(h6y%m&!0p%9nf3`Jy&jmM_vG;tSU:^u7.2.IEyYYt8
#*ng[-g:.SUhY&@=Z2l;@(mNnicxcrP"r7D-5!C9J^
olQw2;+K/qX+`$#c.Wegh=2OQ(,|1/');}elseif($_GET["file"]=="logo.png"){header("Content-Type: image/png");echo
base64_decode('iVBORw0KGgoAAAANSUhEUgAAADkAAAA5BAMAAAB+Np62AAAAMFBMVEUAAACDl60rTnZZdJNziaOerr60vszI0tr8jZH8c3X8SUr309T8Ly78Bgf8r7H6/PpDBKXXAAAAAXRSTlMAQObYZgAAAAlwSFlzAAALEwAACxMBAJqcGAAAAbRJREFUOI3VlM1OwkAQx/sGG0Xh7GwTz7b1AaRwNhqIRy4kPRKjpcc+geEJDHc1chYPfYJ6N7I+gJFQE+UjJIyzS6FqqzeN/A/dtr/Mzsx/PzRtlYSI0fd0Ju5+wDMhHjCTMIqaXoS9QWYw3iLlvRHtLMrwKqDnNLyM4m+lReizCOjXWCgqWdPzvLgJNgnvUGNPV6IVyc7cim2SrHKDMMN+L6DhTKgBDVhqCyPWFW3KwfpqwEOAXUembeYAtn0W3ssErN+RdbxBOcBYowrU2Di8VrEdWcQrx0QjqGlx3m5LUThK4DFRNhGy5lkwp2CVHZ9Qs2ICUY1cGmiUfj7zOnBTyYAdo6a8otjzR0X1UT3uSc97kiqfFzPrMqM39woVZcoUTOhCin7QL1IoJLAOKcrniyCXwUhRboBplTYPSrYJPJ3XLS6Wd8fJqmrqVm2r6vxtvz9T3kigm3bDzPvxxqmn3QDg1l7VcasbtgEpqg+X2133ixlVuTky0Sw7/8eNF+4ncPi1oyFYy4Pk2tz/TPFELrt0w6aX/S93FMPT5OwXUvcbnQl3rWTT1nIy78akqjRbPb0DRTX3Uyvxl2MAAAAASUVORK5CYII=');}exit;}if(preg_match('~^/[-\w.]~',$_SERVER["HTTP_X_FORWARDED_PREFIX"]))$_SERVER["REQUEST_URI"]=$_SERVER["HTTP_X_FORWARDED_PREFIX"].$_SERVER["REQUEST_URI"];define('Adminer\HTTPS',($_SERVER["HTTPS"]&&strcasecmp($_SERVER["HTTPS"],"off"))||ini_bool("session.cookie_secure"));ini_set("session.use_trans_sid",'0');ini_set("arg_separator.output","&");define('Adminer\SESSION_NAME',session_name());if(isset($_GET["upload"])){$Si=null;if(!defined("SID")&&$_COOKIE[SESSION_NAME]!=""){session_start();$Si=$_SESSION[ini_get("session.upload_progress.prefix").$_GET["upload"]];}header("Content-Type: application/json; charset=utf-8");echo
json_encode(isset($Si["bytes_processed"])?array($Si["bytes_processed"],$Si["content_length"]):array());exit;}if(function_exists('session_status')?session_status()==PHP_SESSION_NONE:!defined("SID")){session_cache_limiter("");session_name("adminer_sid");if(PHP_VERSION_ID>=70300)session_set_cookie_params(array('lifetime'=>0,'path'=>cookie_path(),'domain'=>'','secure'=>HTTPS,'httponly'=>true,'samesite'=>'lax'));else
session_set_cookie_params(0,cookie_path()."; SameSite=lax","",HTTPS,true);session_start();}if(function_exists("get_magic_quotes_gpc")&&get_magic_quotes_gpc()){$_GET=remove_slashes($_GET,$Fd);$_POST=remove_slashes($_POST,$Fd);$_COOKIE=remove_slashes($_COOKIE,$Fd);}if(function_exists("get_magic_quotes_runtime")&&get_magic_quotes_runtime())set_magic_quotes_runtime(false);if(function_exists('set_time_limit'))set_time_limit(0);ini_set("precision",'16');function
lang($u,$ih=null){$Aa=func_get_args();$Aa[0]=Lang::$translations[$u]?:$u;return
call_user_func_array('Adminer\lang_format',$Aa);}function
lang_format($pl,$ih=null){if(is_array($pl)){$F=($ih==1?0:(LANG=='cs'||LANG=='sk'?($ih&&$ih<5?1:2):(LANG=='fr'?(!$ih?0:1):(LANG=='pl'?($ih%10>1&&$ih%10<5&&$ih/10%10!=1?1:2):(LANG=='sl'?($ih%100==1?0:($ih%100==2?1:($ih%100==3||$ih%100==4?2:3))):(LANG=='lt'?($ih%10==1&&$ih%100!=11?0:($ih%10>1&&$ih/10%10!=1?1:2)):(LANG=='lv'?($ih%10==1&&$ih%100!=11?0:($ih?1:2)):(LANG=='ro'?(!$ih||($ih%100>0&&$ih%100<20)?1:2):(in_array(LANG,array('bs','hr','ru','sr','uk'))?($ih%10==1&&$ih%100!=11?0:($ih%10>1&&$ih%10<5&&$ih/10%10!=1?1:2)):1)))))))));$pl=$pl[$F];}$pl=str_replace("'",'’',$pl);$Aa=func_get_args();array_shift($Aa);$Sd=str_replace("%d","%s",$pl);if($Sd!=$pl)$Aa[0]=format_number($ih);return
vsprintf($Sd,$Aa);}function
langs(){return
array('en'=>'English','id'=>'Bahasa Indonesia','ms'=>'Bahasa Melayu','bs'=>'Bosanski','ca'=>'Català','cs'=>'Čeština','da'=>'Dansk','de'=>'Deutsch','et'=>'Eesti','es'=>'Español','fr'=>'Français','gl'=>'Galego','hr'=>'Hrvatski','it'=>'Italiano','lv'=>'Latviešu','lt'=>'Lietuvių','ro'=>'Limba Română','hu'=>'Magyar','nl'=>'Nederlands','no'=>'Norsk','uz'=>'Oʻzbekcha','pl'=>'Polski','pt'=>'Português','pt-br'=>'Português (Brazil)','sk'=>'Slovenčina','sl'=>'Slovenski','fi'=>'Suomi','sv'=>'Svenska','vi'=>'Tiếng Việt','tr'=>'Türkçe','bg'=>'Български','el'=>'Ελληνικά','ru'=>'Русский','sr'=>'Српски','uk'=>'Українська','he'=>'עברית','ar'=>'العربية','fa'=>'فارسی','hi'=>'हिन्दी','bn'=>'বাংলা','ta'=>'த‌மிழ்','th'=>'ภาษาไทย','ka'=>'ქართული','ja'=>'日本語','zh'=>'简体中文','zh-tw'=>'繁體中文','ko'=>'한국어',);}function
switch_lang(){echo"<form action='' method='post'>\n<div id='lang'>","<label>".lang(23).": ".html_select("lang",langs(),LANG,on('change','formSubmit'))."</label>"," <input type='submit' value='".lang(24)."' class='hidden'>\n",input_token(),"</div>\n</form>\n";}if(isset($_POST["lang"])&&verify_token()){cookie("adminer_lang",$_POST["lang"]);$_SESSION["lang"]=$_POST["lang"];redirect(remove_from_uri());}$ba="en";if(idx(langs(),$_COOKIE["adminer_lang"])){cookie("adminer_lang",$_COOKIE["adminer_lang"]);$ba=$_COOKIE["adminer_lang"];}elseif(idx(langs(),$_SESSION["lang"]))$ba=$_SESSION["lang"];else{$ka=array();preg_match_all('~([-a-z]+)(;q=([0-9.]+))?~',str_replace("_","-",strtolower($_SERVER["HTTP_ACCEPT_LANGUAGE"])),$gg,PREG_SET_ORDER);foreach($gg
as$A)$ka[$A[1]]=(isset($A[3])?$A[3]:1);arsort($ka);foreach($ka
as$x=>$Ti){if(idx(langs(),$x)){$ba=$x;break;}$x=preg_replace('~-.*~','',$x);if(!isset($ka[$x])&&idx(langs(),$x)){$ba=$x;break;}}}define('Adminer\LANG',$ba);class
Lang{static$translations;}Lang::$translations=(array)$_SESSION["translations"];if($_SESSION["translations_version"]!=LANG.
1398980565){Lang::$translations=array();$_SESSION["translations_version"]=LANG.
1398980565;}if(!Lang::$translations){Lang::$translations=get_translations(LANG);$_SESSION["translations"]=Lang::$translations;}function
get_compressed($If){switch($If){case"en":return'%X/+JbTA`*4G`o9f.84:KgZH*e8RK#uuZJZn4fLw*o[$x:`d:bV
pQiTEB]c.ysjv@Se]XIFX>UNibu#|g%=~)t<s][!N?*
m./b>_S;DCw
gJvF<pbA(l0jQsHFYX(R{fY9uc7_P1=,iiGX+ljrA[OZD3"Fs^d?G]hZtTYJUA|Q0r5uOQ[a:q{`?dQKpHuG~(~iD[#C+r<,f<pW`w]IuxaM/yFwzJwxnS8iq)MkXmSr(WTF5M?yXnwM`8I]7nEb4CP
Upe?EPts*F_UE)urj+*Cd0C9ITx$=NZjqJJKkv>bL]_2{_Gb6F#1"%$,UFR"%Ua(a?K2zEvwTgcXLmv;ODo.vmn0bD4U%5-0&xf<X=+Mryim?r}8@`s4c_#tJ
qkRjHQYpEGNQ*.<S$@nVU%dkGZXOyl#1,CV62pC-yCAONGfNPL>.lpen9u$s<kN<Zg`kmUE$Ro=*<2-wI]j)UqX_O72Gol6x(#N/L"3cXd/MtE<U6I{>BK>$J5V=60YMv*.7qog!=rrM>!W:CFXs%FT5:D8>)uNm/sqt^iV@|_-_>p6265L#p4@AK0ye}${=6?p/}FD8>f`wX<<_=5
D7W?;8?8.~vqnxRd:qAqVC>Tj;0!J.xmKr5W(w`K[WLkZ-I^pqbT^<6CPN<_;7cY`(o}8Wdt2^%,h``4P0ChV
KDq3]iZ9[.JI/lrWD{ZKq]`0nzf}2gN
=Sn#w,/P%3X&h~eQy)=MvW!WrG:CQS;I4y[]S,,28+1p#>LUjX^*8H=7r`?j+L5K5l#!p:?jhq><SV9#nG98`E`AO4$.ty&+]|aC/!G$>F0ZKNH?G[eO>AUcBVkK[>G.1s,#,]gU*@oN=d`vQ(o};4
{bZ+<@/><Q4IHcm7[Q.wpnvoS=ZOM-!B2LCN#?j$:D$j/iT5NrUCGEGd&;>9SX[^N]f
)5Zi&c2V&M54W?mcs./9_9IR3OsLOARvBdt^ve]m90
SxXLGb9xU76E84y~:rKdQQgbD1*)qyAGZ{5S@
2Y+3;DQy#m)!.+;Y1sIwerDHR,"G%p7o7,+jDy",01
T+@SY=Ei<TM0*C:[IJD#ldqhi_M4K`"K8QT1FEUA&,y6.mM?I@"jl-K.cn0%r/hwzFF#]bQH9i?Qke6kKC#VX:pZx4%8+FOFM$rbrr[q[oPD^=-?3^l9oQoqzoF_KfYjYJ>]U&}T,*MSBe]l0k?Hq:&Q&3%nHCpLFHPkNa:e-i3isE3>RRDZ>CJ[76}EP(^,8D);;pf:/nh
qUug;B)V-tE?
Ez
!qkuf4+
FVpr|mvH#%X@"V%^QlQ,YekTj:C1V8M27`LDuWs.^DAK?UJvhTg&.e8?XE(pQ@#^H"HZ/<X*K/1Ke9A;z-Rg<j3p6O"`x,{Ew>5&
2BazU#^c_[o)4@&j!]7Wo?C>9p.zKfLW*ii>mjQy2T+>M4?mR]VU6mq3E,iri?&~L/,;fW3S=2X#A
h$k8A4kO"1l.bvH=5S.okMTorfoAjt3Er[ho1CV345?}_s?"IGt0r!B[TuculVEEE96._9D3r%,wCTK:Sz0R#/G}WKT9=]h]oav4hZiL/7D3rtI<[MW{//7HlsXJEq:V"(`J>7/<t/:
En%9::"ak3Z=9ajlqG-L,v3?jA3EUsdA-`IJ=eh8%c?bA^6mSyE105Zu%meE29"785&WV0@yqd.<t,ITs11(x"BMrKcy47^#n!_rQ>gYU9I6(va/t]i1b%Of.&9ElFRTXRPxyeqn`Y0O:X;T?p*zE~b-EE>>;JGN6IM+w"xq)JU,aCSdo{Gy?2[d##0ZTus=N(+6Hl2{A{s_mtcl-!)gNa.;AsPnu,J7vbZ>m?_"oR$=9_rz"[ldo/X$@#8u#qZBRKs.oK$kagGT7%Sp.r-$I:+%Fj9cZdMwSKwBQA6>@0P<Hi::2JVdq@])TaERIP
n89Pz>pi<V[-h)T1}Xs[,.zTFFOjDg&A
*+xqwna(prLv/47riW_kBK>;*=fO=BA=#.@b:=[}L=[)?jNGLBVRH%GO:&`Hg>Il2//eaA(s+l)^jF.vr)OU]B7dQ5Yh4zhfWWIFycp4wW!n#W!3,R
iX^HHy~n_gL<F&Aw{!FwC"GEE5J/[.3kC%<L8RPvB:+D_+q
MY#&EUk`vJ3r5IAG`
FRycg+sA>&>0zNQ,[TSZs(1lE2z$;s_#
VQRcn#23Dmr<Eg0}0dRU5mIl9o2G@1pvVt8aVwfTxEXMD8>}8q`gM*bzynyXd(Os:/*bR1HVMi@uJ9oV.tp_b%rGNn3T55_$mhaLDZIw+_)e6xKg=jI&@bJ[P
(#3PBbMl,P7(d"PP432!=m6l)7F$$@iGT+c])Xlgmf8^YwSu335`t$Fj@_)U.r^)b5&1t`[%v2!;p>?`e1#8)dV22v);%#[v$8t4N*H"2"P
f<El0{8nQ5ChL42(^C6h`HD0HXa=*&.dj=ofM=2rYCe!"3.@G$TMjL_hs^FZ]#w:1tnYO:t2XGu-I/fv5sBUT"yfW$i6G}!8Li.`Zac}oI3o5q2mY"o|(5ts8+-l%b?jJS!y67TyPONbU
fF<!Fh_bPKGK4bXn1L3DIR=8rQRjo>6V&B`@7Lq%1WpT:<V*8*O=YUbO5@e9vzKE98s8I^L0VXK1XTLAxCK<.-Fc0*jpECxoL.tU6
[U.Dhj^I-_hKsHLXFl="r)CwT!N#f]JK6E]edEXZPk=2"qk0(<tKbl*}yYFx&S^14j4gPNus]4(ZmWV+Y6osR@u%ZJ)946n&Fl-/Ss@Y2VRx(Y)4?_8,M=uLq:8`a^qew<xnQWGi.D7J>vr-E}H529[?D|!GK`AKy%(8dma?Gy>%_39]su,A2$;2sV^HwKuJ#9gvu.d
WwmB-nmNfDP5w(>:IOom=yQcYIa3b&EeA9(:=>kOa@?Two^;LpFI1,)5QM9r8/N=Zc;*WvqO=kIEV]gb*4MP[kn<6#ABU(
@V9)q-jq}3==/%3=Nr;;kp^<OT824:%quh(hE-wN,1F"9RU*^1A=8B<sx5-2jDeh1FXZGM66h9
Dq=tX&hrXv!]?W/rl>Rbt>lP0htQI1Q
_9^DBBfmfm<(';case"id":return'*]^;Bcs+>&)krx(TCT5dgYyeTb#&E;HJpH-gcV4F;<EO]YN!Ox;fJiA5]jlT,$_),:x0/6Ahnvv[gy:^54;dE5~S7xr$u=j=pZnB~38d_<KyGyPj^V0r4CHCi#BdUFuH3w{d=^t,&<{HhP?_4=`K&RjLn
U*5W$KlQ)kVl.1=^N2P_"/=2h4<lrg0x85%.3#T&P*J7WNaBC!(eUDei%PXL`"]E>kaM^mAYu?`nA2XeM(Q=;N`&Y>AnnfmQlu#S_t^_O>jLVqpl9nfocHXD`LsUdkQ(7T?$yh`;8hY&4XRR9r(7}YFHa:l
-XF:-^Rt?a{*wZ*uZPbT-6eA:f=u;_0[V:FNGHF2f5%"hu1b_!%hwTctgFl2!v;`ZY.hDJt[2M&TRGg
E^WA(+70*a369pfckoUM}tH=b&wlkdcqaCN;eZ2,h(SB)56OSFpS5Y
lN)9;_]QXGkk@Mtfr:l;;s9v%$QgQT4@N:P45:879AW(Hhn2m6D)SXiG#RQefJaR!`yU)Ka$7I8w
x>?&^5=N]_^B#fZ7Qo?#Kr&>xoX_~u.6j96-<G]CHXZChAO#~]rB<I##s=l_,Zk$6)iby^%wAsk+0)0oj3ktuvv+}+[7kcU2~W
R5kE>Eif$h`x$W?4@M>BI3"-:{KG$MxZ%$VUAW:61g:YV%YRN!INMv9@Z`l_Y8U$7(.vBClKxD*)^+6["YP
+M_4Gqj11*k
[.3_-T#YN0G1@p"`wM&$7i6>d>h9-D.d384Z_~)x:1XR(Y`kz!9a#X_&
-ak5a
M!92ht=oqDYfcd$A.sM^Wtv$7v<;8uA
1@H-Y$=8`Pe.<Nv$^9oO+ZEQv;v_4-7GZJJr,VGrsZP1}J>^O,!Hb`1APGsAz"$k`elZ+pm7,#H-8$,H@2Z7`
%0Mv$+H?+--%<f;yBOlpH#PatwVh%80;jbqk)iE].vpOt$G8*#~lVxb]YZBO$;*AmtO%&b_mF&}d[n!_y02u<w"s>?R,alGG@"PMp@E82FW]sy+-C!GHS<!G2<tKy6<!)VNs8Zhd2=uJ0-}8g4VnGq"Ub6E.by=7_M]ByT*"FKh<<p"age6NzS$x_v}R.F%ANqT<588`]M~mn2
EsCI:#>x$i$KPolp?5UeqQ#v"+Y7p=lN]0Mn$g";;9nzIoEo66VW+}&v$nr!<]8-+UbrqolG#>=i/evz3,C$=p(~dAX=]<qx2"E^>#lITQ[-v5:.-)f:91[Y@BP5lKJAWnE4o#S;jHr2OpU>PNoy.gc1!#,vGX5#prVP0bU35-)19gZ;h{Gupi$uj6$SB"4HKINXgh/x/e"QHWIZ$Y*O,D:cs,R?<0M_SbY.];E7=)k`g>;`iS]c=%H85&B23Tj&ga.UQRu=>8_KPQZ3KOrx:=+!=ulaEZB>Un?jJ23Pf."D,l&9O="*wIMI+-;=:vlB(}9<5;)ENn!/Q<U/::4R,uomE(IxD*H8thS2r=Wx)s1F%&DNMoYe_~:]l{^l
/#hr!7b0HRy6D8bhD%-e<vNf0(;)kPq.$Q%u+@^mo=.A8%ju7@(u
DDW@?0OxS^P~(a]A-%y9c]6]^c*=L1i_IMlUbA)2F"%M(EJsg@<`#}K:0J)#h&/MX@&`
unz([%HkE<*UJJo(0`q(DkQV^gV,G`5XLG&ffaUBpdSY`P&.=ZF8PL4hX;.#,(_c<nCUZkU&L8Ae#@P8X-f_OwA*
qh*[)*D>-$/hb[ZYuz51n4v3=uE3/P=006V0T8EPY8`_cCF21LV/VR^;Z41>0i]"
kqt^o3fSFZ4Cn#3fXhM4`M-Mw:6.%.GA>FD:3f1U+[&+TqrExIi60tphi_t.or{Akl/MNZ2BYWVRF7d+7/bk#W3B)SWh*i-pkC19yDCaCdl`P>^5o`*McuQM{bv!o0R1LwI5m>jA;)wkaER)0J5LB5^-qXJvK89L1MJ={+NLf+N2,SPl9,@"1VghCQgN{7w"LQ0!@U6y&u#<*p7Fe[_$;AiH=]ARuY=_joAuMDD;!H?KDdIYBPcoD@&ei#LQEMQP.IUfm;$/-x7ZEIxw-A.)s;2Y??~?x4/Nfdr`IOI5>l>+9_JNE@d9v<M>0ckJ<VHp>3,0KcN<8aSI6(|aa,@N_%GFFek`n`K2</9=$@jrB6g7FN.Os2XNU9]
e8P=tun8A?=g[@&XppeRf4}U,0y<A8ZL,VXslgs@ZtDqD"p>{lA%N<)b+/9->>07yDY@LT0_)T~O`;@au^A?
E"*PlLa"9~54
e)KvcTnlfg1<e)xXPrQ?,E:wz;qgfj)f
b=(Li<dEY9UT>1rfGllE-5g}bE:"aSpVWLr>W}FyjRXjcrIFUjD~J0X1t6<9REMI!N6*@;
!03;9&}#xx4Je
GV[g46[*GN9kN:"Y:w9!<"p^bSjFTf6mfmYs?W.rz]oIpJ+.9[Ob{mf0{?B]&$i/[5;o>wGyT^~4zSwA<l
;[!uTUv0!4!|A*oDS~B*IkR|hNk:!Wx,O,p:I_YWWHTs!WphPjPFU9@K5-OV`(he>v9[O!s$d~bVCbu7j.+i*4A0v[>t"?hXrcg]xR/bl
Pbdi^u,|(|XY)9faX<c+9$DWM[(DN(cbI&Pn3jWa3?k4C{S~_Rb{>6N1mpqEkml>DW;ti)#m<lSVfY?8faFIgX
?@=Yzq;oP%KpH#lNhJi
_a_R^?;8=1x-=lZ:kdS,l!SnX@q;]>TChr9v3wA';case"ms":return'%s`@B6KWB&)kgn,<]vEsHGBJ,r~C]1Rai>CTb;x$j>OGHw@bHP29
]|W%ND##_?2x.t:LI3wwG=t*)&lc"dv~!P:UP.g7R67KXkVwG|V7OXLuY1wMdN5:!fTP^cO,:0:UxUuGH4fedxMKM<G@:q
}A?M`up"C!C?6My]V["D@mNZ}gK6=bL$a7tk2r=uvqKw7$|Z@Wp+(rq;gRIjOYVpa2H[ep[b!<%^t5K9~2+kOJ/tqwkJFFwN-%w1w?A*luBj$<jpM[b2KeYaP]751XGYvBA<sQ"N]1weGtE](IVDFXFoT,V>u.xYW;5[@STQ&GPS##U$T%e>^Qi_&,
Q6qv^E.0Y@ngG)_o!,B@3-s1-Ev_,MB$A&;Q.z##d?88Jr,6BJ8[8N!`vj5I[1_sg
8+C.KJ".*,5nnV(2v>VO-_-6O#nSQ#m0$Nui:!mD_utr8KD)ME,1i%N+$4tGMk5u<QH7"Gc6+)0e86ms()F(cTaFOBoQy/51cc`:(?R1>%G2d8eT@AjU&F*|$!
MRX]5&WuxZR,I#J?)wJVjaN&QY?s=$c#}OCpn[
>kAss6Z:REp((c;zC(HS-H6.L2twI^yL-
<~M#-9Wa%Q/A*{KK+DOl?`$M7rGAYF*0&1*R2I5T_GwO2o_q)g=|[q@$S)OfJ(5Wfy"Xcst5,?s-oDy]R9hP_>J[Br
/-&byLqb~GjEQt0-Dgpvqg#%f^_E)S}[^.DoI5u5`d{J["5LqlOw4`&C*yG_9-.48SM3e"48CB+,p%}:Di*e`d96^wx<kx|<Hi13S(o/V&5IwO/z)v~V8e^^]HYA"*u$MS!xqPcC9@|Q)-#++Nh7F9e+r@$p!Ac"lmg;#`[sGs6J!9"ARbZ#2Rf>r_b`OGii3C|C}ec8bSyA`mpX[cY]zdO$ZMppPI)EPE9.!x+sNt,IF-L5|X-&TP-gt?y2#d"=5mqy?As6WN,69SI0HUhVS2$-8!G=sQ{:x8657Rz=qOfcnj9OJGQ7-9-)IaU$[Z?Zo[]2[*/ECc,n(X`-$IR9vqcfmD9`h:^y+mk7}L^4e)[#=hVJ1TGUJ<cm2;VmCh@%T(o`
]Bh
w~2;B"iV[@Q}6Du}sA1p0]?=%2+w;zlUI8?><Z;l_)3Sa?eca)+Qbd0U$=NqTdh,o,yIfV*[,(4"V6fbo1-d[)0BRd?+9#6KSD*$X/3hL@#,K]?e08V-Z`z$L(iIV93l%
//0vTz?ITEZ$RU6.=,(+uM.8xPqu7|+WNW4&v9rW$#2}M3cR
<F
dyT<lL#K2VPI7.:AI,E=BbSMS&eMUSwrt!tk0&!4K%>S%ArS>WOJ*YTgqqC?YPv9vURRoi5+ONpJ7z*sJ*1(iH/SQr.m_3R~ZE?e?9bRRKMkmYAzOm(
f7/1NpWDXrbO"YUB)IlBH@;@$rhE$^p/ldVnxNb?$a%B`>#e@/ApB9/n)kS9]:q|n%WW3[PQUFOjE^K3B]%v;-OFvDB(HX@Pgqp;VEanDfya*&JSw2#1E.m>/XQ~M_4PH{NRi?Tf$;ni1hl{"!Wm6cbK]WeXwG5O$xnbORB-(a.%&>3KK$BJ3Z1LFZfLjj`vY8w4_d$l<MYwGw*&4>Nr+u6[Gv?TEq"wCd<l!$0%IVah(Zi$E/SaWEb`m/oIDba
,.va*.6{s5s6!xc/Mb[~kF`J<m:1505.E&,:iu@mM6u!M3/E7WD`pZQ[ED4]y8tRkPb<+74|TDNSHtcvx4j)[yD9SBBEO_6@D}Ye83/eDi0"ZFWmeU5Jb!?P?,b7X~g1+EdqOH*L5m3W5(D|PZ#s245a&ZdMIbGVg.hE]?t)TCLc826"r_H+tThg&;w^kM[<S"ls@EW]LsQ}&VuLPzNUR"%+ftTfnJT&YV4.2QP!(y(SXTc7sBB.h25mKdhT0blc%d^(Hf0AWK/,;S7+WM+qu8hdmrF&n4:T]FE(A-9re_MZTB[U&ECEAK@,sFR<QlA&z"B8dI9kGt@e]HsAPBtp%;bu,K)1rex;^mEa^/Doq:_T!..tYQT28!$q)tQvD3/Q!3sSV.)1p5"PUYCI
J;WEo9h#]:|+YiGx~*zBi[w=5E[t$5Oa{B&`Uo9"6ckwV*o,&le5mV=<{&R
1UErs8cjU=Qy3YI1W-]Dv3:vu)XX/=#Us_Kv>WQb!n=2B]>Ujw:B~1:[ToiUlUKON,k`r8Ny!B^rKI"I2E$c3.3Z]g#``MT!}LMDbgKM2@~c!cgWx=MY233kp*e0WdS/&.wMyy+vkvuibC#]}X3kb8bUzGo(XsH@;.3mGlJQ?Gt.RI|Jp:a9Z-nDA%FbfYz#:n2^x?%"9Bn_=t
8V&!0v9S]H+0<l:b';case"bs":return'$]^F;h".!/#/Rt^2c3W1wMc,4aXuU>WT<SvMA/k-d@<R:C&I#x$;v[]Rbe*9LBOLY2HHMYPPST`4uB=shuZE5=0<Q+f7HxqAJVkq,lwe8,,tDxC?_,*qLjp_|y3/X[/tUQrM5x_4WcnTU-]G"yv:+REdO@J1QM^8_9$J>J(![nXg$iGWCGQyvjffPH3jP]"`;s%$om%hd(VJxk4G0%sUq3aHI_qg-
n02Ew528^Vk3-AS/Om3L!.P)TwEx#`PLmI
9=9lEk@9_?%LoQ6p6[f$DpH&#lPmx#"umt?}2*/Y/t];7P.i
IcQA~sjSq2DLa/R#lSLm8v;0;_yTiH[_t90:]J5I#K/TFL{Hxpo)bO/xgqp-/i,sB0Oy%H@nsu_GUHBwrJQxNS;mBaM?]N]a{F14U<=cTS,QQu<,Ike[(TaKYs:Ap&;u~$ZNpe+,>8
T^T{:uq|)+)"tg9Ap/<hqMsi76gyG4j$V9PU)yD4+8*w>mE>#6o}7>>M2SO&xDJPNI^nQ"d11l-&)cWO1by[51*|%w+iPAeeY[IvbFD:?m^7aOjV]WW?;f#9b$X]U73=!L,i=**}31Dj
7V.>u]P5v=#%YCP(1g,-fh!wOk{bZn:#;Y%swT6K+hwNIuy5_^R1~
X?spFQM``>k-`=_J^/[8<
#b(^s0I%l7Y,^?p;-u{)Kw~A}P2!"D@8s9g)9VaoIX(T5xg(zV~CAv?15dqNC_k)>QJv^s*9&o-h1N6W:e3M|,wCMwh,I[7tM0|604-D;3yVB@#^nx3JG0;>0)q>4qot2ChgAUA6lO9g+.wdr<g#qSIpu#2tA^-bZ[Nfm8WS_j6iaW=vb$cG_@!PQ*?;jo/!IFhEP&sIIQ<Tc1b#NDU?hWS5WP,nOC;PhM"dghD4^V$1ks=SME{YyB=)#p_LF!A-965?(oIM}v6FBn:+ui"y3$2fKf2Fc(b4cE@M0WdWafd@JOPL#B#
"JC)K-:s,2jJFs?dH]Z,&6AlQ7"4L.%fBLgPpG]Y<=}">4PKu0Ds=mtAd)MA%57L!mR7"xDK:in.a/R-oG;)j;]m{Abn@$jl:so(no_Ct5}![k=qynJ=
kU2#/n
[l@hbw?!VjoK?Jvr/H1PQoWsTM
nUm`m
d&V-aqc0HfRYis"^hJ40dA?^fKicwC*+wc&%O9)0#@+1Cx4?c{EJ^Zt(w[NjRjfp2XI8a^5YG0yX(>"t:ol-3BtXSeOq3WN6"/q94o./@^;B<36a5La"/Tz$z$."*/@1miA_%x9$-dlH"yaB`D0]%0@]H<d}nqseFzf~f-B=
X%jF&_3[FGw:"GP_}>LUc&ohA`3[P4Y._Zd[Pi[JMq|iG6Dv7$?l:*kky>U_1i(X^-<>VLX,5ZJ4oZ!cw8U+Xj6LYG{d0[~55cfIe%GeRbJ6s
w*Lsg-4#%^
]?#ji{Y(m0i`FjiWBpGIN*R`r=vEjJ[$QWpJN9y#B9o-r{!?T2Jp]MVfD~MYg6NUBv39*BZ8ozlG8p
/m48x!xNK
Mpx2wK>&5pO1[Cm5KIo!%%ppz`%:s2b4qr}jQR";KWlkZb.S~Z0WT1/VBP^wgvPu3Tq/Pi?XuY}l7,%<RAa]6Pk&jSdu*GJGPn=w+ffDxpg:s_zc)fnyKK"[>C#d5SJ3pR+k#bRtl%NKt_By)r2L{n-na1
9pN)`[1
b7QQ^VT=q
qo:u<_(y4wO3jlljHU+f3.""wV>)jPWuX|V&,ZVMOJ_W`Jh7.T(BP;-,E4?_oXBH8og>"o*;3fRjq25755rs5js}Fq2ZIWMk+Uj!mOf246CtrlXccyg=xy@yy}!d!eU}p;WoRBb$a1Hly2s|-Y[5>wkpI(d*uM&b/L%](0j}eu^y[Wo,D)#V)hCX"CNIEMV"?6W2kQF#g1oZo;ST.DlUQu#:=Jt>VT$:;YxEZtg*F.[J3_hoa!uGS]epk1`d8T=on,#8:l9>`t4!`!"o5^8ty;S]aa8l">
17ah48+)"ET>["5:N"y]2iT(g-b#+>I)jpB82*hEk!a$Mbgg}q%5uCa<O9]v}CO4H/?Qnt!._`BT-dk41=&mV^M&50(gzQn40u=uy9QX{0q+6;VvVrtSp9Zg@Xy>JOp$"TV@H!y?0SS)gYf+2BM$I99;w7%)XbOOM$GgLbst_2wYx^DhW^;#
#bbe#S#/E>Xv1"lR+iI5pC1!V&vr>x
SvG1w-mtzEhnXQ"BJE[65-@f,7&1
CqZV0M/$WrXNVj.zVagi6h-A="L6$+1w9s#f/9=_iki<wfL+a5yqn;x4TqL}0&h52#-risw8D&cX,vmxLz^e@E-.E+!C`(*jY">Bj62y,W8JKIZ:2.+8Qw$CY,%mEHDYejHp=0i@sw2$RV,kgK3qil<5
z[4a6Zx5--[`eR+gj7y)^r6)j3WE:h>UZt-JELNj9@[2])N=yiJ.KrK*/2;SpByFO(zc`,hLG6tf5-H<XK5Vj:KEMNb[487D|e{hA$FYl
U2#-}o,wykmblAv1Wl3Y7
aSf&k4jr#.<nAyMtBMQ[z`RNZfsCywc=p4G2:pVG*1E.I(~PB>YPcbXmqq6Khf(2ypb/R`50uoB8(X$[GbP*YD[)Jhz4gVW*K={B`C%+p"z,<?r&rt-5<sNubFUhVkO0HY:AI8:J>?F.Vcb5]dg*NQj0UC~J*eC-BMN#)VMiAj6lh)vrL@t@OStR8O*l3"VQ6<ly3%nF7K]/KIP3x`T`:&7PvB2FFTsc}ZCG^+E5.+NAcBf:RR|G6!F*xe|<>ye_,asB|D!R&gxol9)5K;w!Z;;/D;@
w
/.^S1_VgSjL+8=A`?;Y4jCt4-hS$(1mx_QI2H,SB-WH6Al6TD1306I+AVp,WEy9[V[)(qvdPfjQg)`-(gbWBoOpcFdhU8&q&zGJkww[f13XNInE#^m@0`3A[_NtLW2wl$N,WGP.
;TZ<EsV/|BC=0)q22Bj6@W
<wn-6},Q9zP|16ikjC_V95A^y.Xl$dNiF{vF)fk0@I[)@^O&M46@#(5[7xfyUOmW@-eV$_eWsfY](_nn#Xd
?6/jipAj8K>3,W`@4:]bEDjy,sXmKlDzDH>!5kT`*K$kRo5BL=eJq#8OnK0^2r)r<"wFp:g[
yGyA<1v(aV>-!]y3;-3fTv`,~m.Nc_ET>02xdc*7<"RoM`M>t1+';case"ca":return'$]^K+;{D)@VINo3-X%B.EO+A4Z!WA&#Ai0~m,K=Gq=iEQ+-$S`[qvy_<NPmg[R=Okg0$.JSa*u[EP%mh=aYLW<nyFEf5Yuz:Wvyk"Sa98qN_6Y,spAKkpV2l]^YH
:IKKp4]kEH@ey&Aeb5QiT%Ef3AL=h|x1iDS{LPLS@VV,i#
Asr*],AR`]%^H46Z,z#k*suwo,6x[R[g@x,JMc_1nX<Z5AHI>
O0uOJ4&pJK{EPM+psF:2v5<PkHUYc3rNX:P)}$*g7wI&@s.z!va3qLH+{G~eB:*/LPlSaw8(ObS&UHDbC:1b)7XG%W!Y9XPx9/RXn()=#v|1;BJC<uNJ<9Et=h-d]E1bX)@k">N^uU3)tq4M3Xaro_*<kg_qDuwIy!+[0H-b[v{=&ik2GO$R)&~n[g/-bJ3ZOHsU_oznqJ[S00n)m-C5Zs>>!ZueNJA/GYCY].!8flOKCt(,zG|!&PN&eKvnm,KRB2fRKY^oBvS&VbL&
tTbu=aIanSJAs0?^_Mk!
G+Uo:Alp1Y&#ku"?5SvHu"bmX=x@=r%f^I)MsxSuQPWv$ZRj<x9X;Ch8@4_a1lo;H2Es}D]l@cMyZ)^N#4[TuCOn8f
g{d`qYLtCZgpNy]#;wiPJSs_`#Yvf3PO>3EloO
!h!rpa|sxp.-N)n!z/xo?aO8J
@`JvnPS_cd}xjW4^7cTto%)J~b(D{^`h$sb<WOIRWr1St/wHm?f/<$zQok[fv/Lg>WBdXO17iYt0rrJ!2Cb<Z@fAaEf<JDwd2@t
4!v8LeTW>t#>ss[k2+Z7kO7z&s7"9
12}n9Lt:Ge~C%Nbhebm7cAKq:ABG!;X"0%!Hi%CiFQ,V5GYhT&=X_4Nm<c1E/%It{=Yv^0a=6q
MklN@ky}G;,E$j<zV]gzwr,,M
RDsU3oCf:
l%xpP9R<&VTf`Y&?k`$Z2tHQZ=u4XhJtVBJwOyz"
REl-Y&ExF1&4`t>O0M[J&Af)1e4Yn<[chL$v6Qq_M:FtschN8!F@C&K77L;Y.>6+5QG@`>S7^[YA{:cHhv`VqD&3k#M0,f^N46MEYwIi"l5,zsIm+ES/d_wH{q2pHth+QkJYn$X3Gy)4]_pyh&MeMsw`#99%nnNHoVHMOe5v2%UxQBsrU?ptPR`p
sNL_H/OHx[SC948CM]L!$xcD&LmR*gySo.x_V!)SIQoicN4[nk>pteVkRn*9qa9Wx3@TXCW%JacE`du~D.B}k|T1&t,fCznZRc!$Q97E<<f;>O::^x6G%xy2q+Z?m9-NTZTDN6SSBMQ@deD$Rmph$</8[mgx
s4<9xiiSl5E!%JJh[4.^FI9qh-YN<eNl+)$i{es0<6^8;R=lRU~V097+JgyT!x.
<C"Rj>}$s237?2k9
m((H]S@c"y%H9qT39ic7P3lp-sEW,Dn,+>wOwJ`35etIN>dnCeiM`:Ql"%OZ?
q8Gz[:su>fGr!??}B_6z8ic)G"PR/VtU95dyJysxV&!$ODeQZG:`*H(3$b7(r77aSEy>z!$FQZ1f2q8=a#vi(Iw<lCdKl8SBdv?{vRhK=H(6>
a,$r,Qb8PUx*XFdU.<[naH4)eU5l6e:;lsqm$@f:1oUNS^wRVr5bg!wd_3=eCoft>]o_#(p!<X<4.=q7A|[P&`V)7yX4/^A.D!$w+Z0S)xJbOQa8#<7pSe%LP^VEVWEo:R"rU~s!$@1%O,L!7!;_;eU,Bci,Z0ZT%}u|^Db[6bcHm;$qwm3[e1"z>^W=7Bh;L)%4A`T<I)<ifocnEvwvfAo9p}E0nP)<Ng:TFbb6H4#M-Vaae=tk?%WE#e9o-j9GGv*-x:YJLD!)Nl;[(DZ.d)SC;ca<t*pzj;0-e!:J4="-K0*z0kANUxrv[xwK[w[5Nq%{uh?[BO;qvpLjsY5,V[bBUgbpmfqSRVDsBm;QpHA)$Los"e.n#e^K7nU!Y,xm-T>A4"DWL5[`aJekqe5G3Sqod%?u$)mlvzvXtrE:?O#F!}cs4h]w>V!,7FC$Ak_~q0#HFu2CF7X^g}mu_4[^sKtIck2Bdj2*^1suIC-1cX0[mza)SA
%!sr6t:kI$1IW5V;bwyjY@wp[!
nZOx%nut?$5fvXOnxSH@HOqAlWVg3__fZf+7R}Q$rRV4
*dhXxMS=UGP3;UQxDV(*<7&3V>A#gQp+J9_1:p#`
rEW&O;Nn7VZ)

n&u9P7+f`CNS$6>(kf^YLc%68Lm94S5)P#I^V|1@75?QPzT[vbE~-Ch-khf`^W^SUH99*kQB,{r#LGANO4DA
(e}$62[vFC<*CfAGVcm_QQA``@~FJE_`1?I:>[P1@]"LU]w$WANA{ViX7gz8CnvA`s"j7)
MR+|0%@T?xd{P[3M.=:&tRl*v[K9YmJ%lO,>cXrFU:?c8wa5OXZaMqlPuUEo&`nX[z#%%Q2;qE!|[@;"i(<Az!+;e]frXvq0-]8zC7lvgKM=DMHO:]V;C/wg;YZIR{lHY(m};EdN=rP1
Mm3kyT-27KW;eCK#aUOAhslWq9?Kf+"KS9iGFX5*L((u|W0s>"eJHFtsbA-rJE-!$[875#@SSh5auI^1v(_.AP.6)RdGkRc:$dd>#mUb*WCr-fSYxD2OXlYilETXz<9hHr`]@l"GHI9ovEN5j$8Bo[F:O_/>1*`l#e|?2GAIQaVtLs/s%1@5XJ`X}sK4Fq9^O=4j0%KVt6*IZ,9r%Q&p<SVXNb/20,hJ=Z=XH4<hw?[MISq&3Tu_q/@.y1HUky1dE9N&[wFgPW#""n?/]Qh+V/
$+_"R"9@L{7HF3w{`IsY>Q@(c%1m2&C3
Z>Y5ZR2uyEKN6J6C.f-IjuZq>G
(oIR-%O22TwIFs0l84t:t|67+LE
Rgt{jzr+Dvs=D
tbh]uuPGtp;uugjZ3)&@WrDh^V,VY$$ml-vw;w"L%(WN#~VBh!d.$71%F.=z)d
F_rqSld6-!.Q9vXg^*AR
Y}<[G#(nL6*TU8G3)5.@0I/yXS)JYSIog}7$A&;ghe*5d$4HBgQWz%xqvnCQ=d1I$W9B;]jK.BNlvGN_h)T9AX"TAhx6sL.jMpO1>cM&BFG7QaA}9^lu5=bMG09+';case"cs":return'-]^@)bS]40!4otc"
3UX6+&UeEb#u<U_fdCCOI_SuL$T28{(WV=Sp[+BCjM^f-2%k_Y73(e8"jlryr9GO$6eT=.w,4ubLU6^R]xLovZ;~=as4TX]d!Xp?2@a@ye
rjXf{ST!Y8`me[|voBhWWp62A,5AlwM&-ZHgo[&24BMW#ZK<-@Ed*;"Lz@fvIxbCceS;[V`vHh"h[Q6j`d%^R:*ef$@sQ%8e]I"x`g
D:y]9u(|yhVCT7.SbMtnlicNhC_J`vob!(k.I!6*+J[u`mMRRS?^AEPd5^cFf_re?dZB0OO#V]Ud?E@{DCR@XqRxfOYE
<>D4?K9O{JxHg*FAPhk?GL7C<:64Tq*
/?<TChgv;boPgfOdD@9Ix1dns0T5^ve1VZ"WL8Sgp)ib=pdvWh]`#mDy_!wQ^gLri9`M;A3%,c^9"+=TVb9FDp=X]p3BwwwN}aV*1F">0Wfoj[-HQ`d9A2YskL4K<i-p#Z|RK/ZCsL~T=D5xIv]0:6^LBsDTym:_saBz!R6dqg^bwuCj:AE[PGD*DZe1j[BeZ3-]
3z"[]0d"hZe$PBoUfrogUE?1E)r<=7gshxcq
dZd?ZoEu0U="<m(]}@`5>huuw(?nY6qi<abUyf?lF/em3AWL[lhG_[9V6FejrRX_A*)u>-;Ov<5lVvTfNu23~3v;/vI3"t(J:E5I,5GL+4FpBxN>YC|HJ)#0o:KwBHkBx?%>
)$e$cat]nL-%"Vi]SR)WFs=|`T;b8T?Z73=NPkL}E5k-^zR22s&6Jd[
h?w|x*$e*%><cr^xx%*4Y26(%hK+K&OeXNsb?JskbZF?!~W>j;d<3!OC(+o5LEf!Pm:<<FY+?_bRQS+]R(]@j=K
POHY8SOAvcy@RuqV2
iFKQG")9d"?^aQ!?
9,i.0]OccJ4_
,tW/"~D"0f_dlbfKIROxMKa*y1u21#8mg0YDEZ"j!-U7xW6;D0$#2I&!Uq+2u^/hOTlIL$T:kjJo0fDx)AU.=<suG]9Mht>%Q@05WG`|sp.>QYjy8g3<k4jpfXQoqf9;M8!S^jO^;znw053gu^kPl>30ty9dGI[0GTWUV@IQk4s|O+CC$WOFB(eS3[Qq]=bZdv0G2+%7MHh^q
aPT-^H/&i3*97c2mnA?f
AJ4$q].6lu?M;P@(5XrJOHh-GS&RV@sM|:]JiV~usB#
<HvaO
zMS=pt87pBDgC-%ru"_.ShVctqLNNHSyPJ<P^4z"l*uqIQ96$JlRNM+tw>F7,&/g;ptM>G&!Ujs`]_w)@fHTOF1TyISs4yvrLVDe@f|$u+F-cD(!GVyj6L]M#AWyo*%LK+JB![G[+Wv9"q~T/5QQYf4Igb~LP!t]p#ZnZDgMxPiSq
u<ACY$UC"wrHIw^#$7DCz:HI
oQ#CdxViO,RBo:.tLeY~&n@?)Q/2qy6isMB2TTxdT%crH&_2*VeW:70j`y/3!XNBB)@b4-9
o9H*++5}bCnFErrTmm/ZsgF8f[5
s]=;#2
AYje=m<m_0SfG/G!^AtAiu,6KGQOlP{<b;?utt~cE^6*sj>[qPd:6XmUsyrN;FU2w1+x2M%F2FU9[ynQL41oc&Bmz7
u+6$ChnnPVa`O1h?n?@0NWq.^.C5?k)h$sJh-:rHeDDT%
Yt9-bTqDQzxE>m+9+Rdz(9wK8Ew"kvD<*S_bN6Kq:rUL,Z*(P=-;&D^cc;H_=j:odc,@s5KhPk>,RBXc4vC1pBTEs5CG"7)"R081*]GX1~Q3[<Nxy*y7+"51twvZm(GZ8XR{/lkjon
=OyF
ynOIiYl;ZMI<IcEY2?oxs?/L#Tnk:*aU]eS6V+`J%#8e@b[hnSsU,UYm$jkHT`R"rkow/:wRv{g31`ptew]8y:Yhc-Rrn4sIA4%TZf3nH0/*l@&ud0t3H&dAb24X0R#aK@O50B_;TXuvbHh5r^gf9#_s`z?9?0e7&P(LA~fy>X3Uh
]fwYtnE<.q6*ke,."`7=eZj]kMN_MwEq3p-lmxd`dVNg&@[2tgVni[xv$bI60
[,b(/j9aMQBL5INWt&bAEmE}c3*G_Favif;n]mj,:l2!o,8$CBy1&CB&$%j#_)`/9:4LJv1xdCDgty/{%K>U;AOLiavS7gN9rSZcY]F6"J7qGXI,S0*AX%LG4](7K}]O8=Go3H9kh6x<iKBPM]9/41b6"u+/$%
IFmcTKG@CkT.V;!w^l72SHlloal99e5[.j8#Fe,R!$USt4pK3CG8eACBuG0BjGw&e;ks]MSkBMJrW&JY:G
FWJA>Icdt*BzUx5hs>;1H:2yxhm`M!owx,vF1ep`.fGk[9"r"6*^ie$pb@NJi!EELHCFUuH!6-a{q2)XmmAjb(=7?shiNU1:*qEt_?c{
c0:exg%O!8L2{rBnCiJ;<^pqTc<m;&B]$WGmWFM&":{-c5srysx^4D)&`*
SIrh0%80j:W]DIl{[_>s55;>W9xT3VepJoa4#vWt4X)~?w)D[h>%]GO]6KPk97FC&RVU[hRN2m)ek85x>oQ@*an:!zF<[a;P&g
"#n4i-snw]03[(:xIIg=@SZG2O)2AG3F+sv`jCBM|O&u1pZ[Le%
*w8".)mAuxz1+941DoII`;pY3o2C65;2tR}[N,,*mI44n)[P6t)p~4nwFnT1W+eY5[/u=(rXpV)W_hDv?-]#6,(w^+My@;9SyD{^n&NFOtV[V,y]T-RDS2!8p8LQ.%WOq1;YA=^X$(Wte3-A4@B2%,Y=]sCDp-YsaMFRo"o+{Gh8)8T7
4YoZG;E`C;aXoK,+Pf"f_[t9/d9&3`w7>C3<*;2]5(B?$-)"XzjHH%4p`y-lZ1662@iZw4>R1kimvD.$)D<Tbe:(BVV,(AaCl(qCoFNSYMbDmC%S/-6KP]">
G8H%H.`UAZ65(=3.ra|fXwvidmSBN)mtg6MJ!YnHPFxEN=Mb?Uh)j%6x}Ez?z8}s1T-O0sF=;!Fq%W{Su`aF5M-3XR8i1Ifu7eBJlQf:!Zl>7hEu?@!!XjW
#p;pzj]T%`)7j*wJ1>c__mJBMC~i(<G5hgx+dIz&_"GI%&_Lfy[k{!hYu#J^Cm+c"n|L4!A[+Y6f,#86jRwgKt1T<!;l#<pH6.`vL,I[".yl;+.V?R>%kwsLWv8S5Rf!SVU,3C+Sp_>u^$z,ttQ9XqbC#[U`C!sGP3i!8Kp[-&n(*8*H{`H=Wj~eo^E?[G0V%8oaO(}&zA$
n^|EB7)GL9bEH/L1l$+;5`1#c@269GA:"UIO#p<i3f7/@6w=t7n"eQ_/=e3j10vqv5,Go(b<WodOX(N*kt5o$-1"l#Jmv1Py,qI[>wt=VDWI<y|5U$:@XPKW!_K7Fq%9z0f9N<BHVg]n"R%+hrLTX>T*2RF9`EM?;;><WyG""';case"da":return',Zu;Bcs.!.A5*o=7eq137I8BTf2;9N<2+d6iJXD,Q9Q,1fqlHqoy:wJv1MTWpslL5?Hu<RngMmfYS(>_0bWG+vsInJDH.Kxs5V0dGC"ipZ}h`bI8|^U<yAvE[n[-KAWn~C?m%y%PcQ|mQSVB!s.Mn#+7,Pf
nf:w=uBT2b%spO+s2H]HE2aCO^]^d
d>2tOv9u,V^X18:&s[`am.mf=q9X=^sDt+b;2&bBeeA24)EcZyg_Z<WoK)#SBa6A5rbi@F]ncj918fF3N6TL;>p`pjmmwBcj0@z/nV0q-:=>p2~MaH:gVUzb&N$?le.X=[8a3_rlDJoH*qTNCU_iKn):Pq7tw!QT%HQ(&O6o2DTM2yO+RM4a|eFGNZI49Pd5.e9gPBb`j<vfYqj5asdmHb~T+^qjwfde&#`Aq&l]n72nx%qvR@VleW`fIxG`SlSul9mK`D
Dl^r8z;4.I^"99;xCH2Y:isSg?PNMnu8If9Qm4bOaKM`xj1Eyj6,55+;xjt-n0W2%C7Yd}"Rjw^7+B]Rl^7AT}wSUUM2k`l=p"Rlorw,x|E!J(cj`#TR3Cn_bV!>CDtKv1^#v~%#^="F;wL]Z7,`/1Dcqm!;U$Ej!qov0?B~Az)NfcPMf^.We"D0W/p@MPw~vXSSjtd"b<yL1^,6y(ptmMFcS<;GkScGvmr0>IPaEdrf7K&R0LQ|efj8eA6YmiIU3u$Ym7K@X5EU"-Z]t:.J4[4{");PuK#zM[:%-#B6riaSsDpGt8i{L]rrP,sDB-E;yhM694w>PUjVOU#Vufh|pwK0]#=my_mi]")/.Fdq/E.Q=VH>nP8L8]WaZ6%4O=ecg1#XsSC]i{o/pp"S;M6iy"Vo>p[-Z[HB`w(xD0<APL*2/71FI>tcLfche-G?6W&7"wn1CDrQs4Q"P-fEIjHjPZuR[i0q#p[mqsf9ab&j&D$m!Zu+S#n.MsM^B(-#r4HDN`wCD:btq&f!L(eFy1v]x[]&lbVN"Knzy-qsRjGRSRscm`4oq*_,o--@ol1!3rue;c/91P<U[Nb<kK^qE{EBm&uZXAd.[hp{@[4LmLQ(5ulOfg#{KSk~]~iS9Fy6.UDu8_Bp(2`:<Z;{y)+SSds<w`a=?]#=U{n](D^Zls+]Q.U?uNY[?OwsgNoG&V-lQ+fK7=yn]H8Y5m@IbfiLrGBygo&&G4R#-E!^Pqds(7=/(S7452jUQ_KQ
<iv5TF.m6<fTYcz1/MEcJ$I1Q4K2sgPfwS]wt:y_yOK=eSsp6T[el/S09AO`#$Mw8!CFSA#k!xQ@CWMkd$6yOI9[*yfx{g?$?S};r0mS
Dx^;h~,PJ!<zkK[e8c.67&.,+esunqLB!7TFdp;T,[x-?Z`x,eI*OqpfhpKSB.9aqq:iW94J,a8`Q2f:9WPy7cG#G<`p0"Hfmk^wE4w$MfA/XV5oT!3LM^Xk!797>/u[Y1dsLjdHj*#AjR`/sI$HZG.X%?(.%;KH`5F&7U%&B<OsYQQ]iUZU)[!Oe`Y1tMxxE13wqkE$N)r,g07-"[Vuehq7+|.
k>KhU|8n4N8y^Hmmy1ZO]$YGV[.8bAyH*4n_oC*vJ/N>OL>~uGROG3Lo?""s(=/kF^AVY1
R[(/su+a#&+l_1H>+@|C(g"%mOX8@Z+>T>7mU>3*$VB3j3mO4]c+Y`fxiQZyXT34`5*Z:;9L[0Dh+hWSe
Bx0(>x@$k3y"A`>[f@e=jA!+.SsjjR`9@mRUsx_bNWJl$:[Jf2>skq%IzxPK-?v&gdY+@h#p}B_<;
qC)S3@1%&bYliS(HK,ATZreAs(",;jL4^ZnF7"-lUN"0y#<P8Pd[x5ez#KL3lGyn$7,Unp:e.PX!XqKE{TXY;c]fJm$^v:)HpN[^

Eer?VeMH)OzeR9KDF1E@O)=3Ur^NjUAB<6cNhpuPF^5)wtgY
?)0|etxgEZD|Y=WO1?Ti-|R>T$xjepMRY|I2G4j1,zXmedbp/,<Ag?9V/Xp4n(=2i5Zut^c*0sTC!VV]8wKCXfj}7fg#,>j_I/b"$cNle>A
Z?:H8"391g<B9owX%=W>8Ro2K&/;,dR6623`R+@9Fi;4?qGwfOF1J)/LF<c8dg$0w+ek:09?WJM{vHpIISxD]`^!o$g<^2nO@iB
&G,:K]9krOP.>1ZLv335s?iLPsAQk*3(&7eW_!4fR3UXZtwbu|^_"jSj[/)[8qVcHWwv`^5!jo@`":KC5qNf,-XwF3#[GM;PrH;xVAUGm]l.p[:WG`P^ELHS2-PG8JG+ZZw{3ouE`x8qeLIQ
P.rvTv?k6h(WH<Hmngj@Tbm5F&rJq;I/*uCw!cPRR&5gRg|BXTMr.iVqgGx?ik*FE921`@r&Te+?<%4:u.(eGYLA)m2b#jV%v#BA_?fk[!hf$y4e#>}jfBQ:OuWGkd{^I,mEi:XO&[M<&p5)_[^"EMjByRiXp:,7]xm*[P*v{S>+]NDV;kPlQ"y)OhfuW-w=5+B9Zv2+TRJLtD:Y]`Dp][N3?&NZYyR!8d<*!2q0*)
6hh>_-*J1lt[!T_Kl!_Ab4&x]T-jf_U(+]NA`^b6;|l-CipV%5^[VLrv)cP)]sA.<k?.m#aAC;!Xi!?>-J50es000VFcr1?s<Zn")oc8X<[~hm+{/|1!KZ4|).d"vr2Qd?tVm,H2mnb-(.f,/T$tP@v?N)Iy#6D&?&^}xZtat|!:3qRm53Hr[al]IuuR`Z7.pi+WZ;ir3#6YE^82bv,$NLZ!SV=_Gaofl+0^m<k7Vv4mv54U9"^b5r1[Y:Mnyg8$';case"de":return',]^;Bbsp=(oo(-s;m#+eK.j:MTA8(4bJ>vCA8*?u~E#Gc<:6+Cle](gyOd)M>+[e^H2K
g@0kAm*iD8){`5M=LWJH
d]zjuy0CP&`wlPA1$<6Z|#KOprmH!38yIK2xdyK[;=DP1xr3"K:FoVmwvvpMpJyP_*"I~5:Cds|H$;
&x/`ZCt=b-V+`uaPjHGdUy5z7kTQa,K
7r){y$/V^9r?d~>Il
T~1)ymRqrAXGyuRg0bvZvIWuR&L2PYs~AhZf$3a.r*A6g=Ygv{Jj`H*kjH_3B
AoUX2U=B"h1r`(21&n+xZ<A;o}_U6KL<?DmXwYB0E<J|=Jp0tx*p*2WsU{NHEiDf!}S
kN^Qpbh1pZDn]a,j0]k.%&pBHxK*8#TxB=l&VcZzeZ,ynufWpJlNp@_&QQQgqku4Ba-CX^A*lmhJuRVCp)&),GsjejN2<Um-_tt
&./u`<_,6ZF->cP*_t>c*tK:Yr[|MCHC!r"?pR^So^jR6Va]y;=5Z<!SPBt]3=xTX@:nTrX8I}:hh7ueg8e=M1ixvr!NGnUM=]J+
n]=jr:bfIAB[v74&}]T5wtstsuT"Z,x1?oDi];QFoIG(CbJVv%.Djsq:4*mHh%Est7OV
x6>1g)4ea4L+rm#"/hCgEf1^lZw!"<S:F!1?r_1Dl_1+/t%h:8x*G0J_G2@h"v`%j*3S+3F)0^3,0oB<c8xFCt!W.1-1qpCR)~MkS#rwO=J9NCO~jH-f&5nmw,#TD?JDyL6.!WOBokVU:EbR0GaVI7nrE3&3QmY,gZICBud!>l9Rxca,$,qbhS`YH=fnH<9opF45-VJkQyD-xde_Mw3E?a@SGGv^hnukaJH.v
B^3+f]!C+#-)e4J
s@Ju!I[=5vfIU]pZygfVLMKS?32:/wf[g?ulM8W|uZ9o^vYZgrEUgj4GF5BLq3XB2pf|O[(7&kZ}%#QXF_#-Dm$O4%#?8"*=E&F*gsI<9,PzcBZv#r-foE@+[PH0AdJ&(k4pb1`^v8b3Jp#Rxmasax(1W^nkSRl]xV?n`X/b9Gj#!I8
$dxHp4axu-CP$NN/h6.Xm|o~klP<5"UE?XNy<uO{T5_=w%iKQ?d
NOETwYBs@Sh|Sr(",@tmr;HKRjmLV/A]M$wIr^0
Q/U`Dz@=l#:edRy06~9]bf=^PNhsy.<O]D@qwbR~5P>z=)z(0cnYG]<:xcMXM=35Z$LZn`ibu=>*KE@E@_I@g#XF($PC!C8FX+2-u9C@q
6HM&iedqgRoh"L-z":qJ6dP_
f###i/$HGO,:-5`P
;8xcpkyix[QV<1Xa.F
2h;E^4?N$y6)SIKq-1h;k)}4Gh:V`L},-1g(2Yk.HShbTCs80+-235Q^B&!Av3
w#)C"fVc6&6ELl#Z=?%:AYY8]F-COtl9jx?>fI.V4j.qn{#>A=?~#^mq`hJ?6-BfxTBV=eVg@Q5fLe.4?"_e7c:{>0>33&XW2z)9^@J"g+vg_84xleN<o12W,qUI1&F"g%Vhr*y+q?%3@[XU2G?m"/@b2r+0-8O%VYPR)k6>hU)5etM3vhe;r%G04Q8RDc-@yWhdh.Rm=2H1HP;Rp5;f!Q5@g!Uqr$qtj$]:)6vC.C3*iM[n-|
pQ;GA8B[0-%k;:iFU9h<+0:xmXW8&UsTEH=4i#p,3DH.UlT6[qt;gk%J`=edIA3Hvxx$pTc^=PW)W^]U]
I8rR+I
vmVP:kGE$W1Nnp/=Aab|V"*yC;e|O2a+MB/oH*IXN%JFf064,}]Of/yZ/`fOkJ]^4+l!2(V"nTOP[GrL5DD6`T/
8CXiw7msnq-7ku]eF9Rtf`3,=gU%6bS(]R*Of0d9_#RmsGdB,?1T(sU%k/(5I.1$9h`6
53%!oyo(6itHYr!Hz[1]H"Q.~E:+-i{=4ZzbSx,_uXj]G0<&BjN&6I!V|:Yah,AplKBjQJkE=oqBy%^3jDdIOo0<;E4:z]6G#(Q0XBv/Q^L=+`W].-U4/1l(LQ%39WPy~_BVwgzT9wTJF_]C2I|9a:z!&FFHBI02{,jcr>7Ta?8#tO&wGk6lxlP7]]B$w*Qp#i(j-ORT6#JgAq!47Pej]ke08h
?s2r1&dS$&?XBp*|G6@R8EwbOle`#=/x#n`t="3X!zRs:wdY#O
.5flF-u.&k{Ohs:KOBduJy"wIG
TY0zG1>;!Xo)DsW]PxdcLYuvI$TY`gXFk#).Q,5aCfh|=M(b%>JYdV1tUlxQm_"?9O)).KX+y`r|Pl">DKjvb_m~,l]ic=QL<iIQ>m#-W4:=VBc;$2n7Tvt,a^O*uifu;bLcA`GUF<&]/SG[eGQ]eM<y@%)Gu}vdC}<@NO[iADn_$U<dC@Ys_M>(s{JGP^d}wX?Xhe(vR&r#KnB}&_R
,-OOAlNFriM%Ugi$0XJsgYk&bf0^YF2
k=u`p@F
xBPZSN6D=7a`Z%Xqh@yi,L=6lo[WF3!J<4kP$Rpg45RfjoXiI*KSd_<KZTU,?Vq96IyV/-fjqQnq
Jy&i;Y"$kKWEi7}6[gGV^)yJ92QTIUh<3KWh%E~+
=CJ,y:[0%U_>&PXzhZd7#gn9T;D--6tu5bk+k~+)Hfh}X17cPa*>=PKm5
:?,%[aQI.;viQHkf:<Dci7j{j,V^$ci^/Lw}W:@zFMQ:KW.~>9Q2X&[Z#X)W51-)60Ge&MkuY@puNZ`e2e
1;tp64i8q9i1BAkY<!WN]+g@cwMj&$@F`CfMU9B-c(c&}=bo>TKQHToWpT;Id(j/&TWjm0DOd&^d7=CM(NmD#C`xsjSm14+>3WT[>G@W><SIfQH;3IC:8Kl2qZM.|u8#o-YODp{F:.lBN!DFCTKqBwTSHFT!|r>/v#TxlKk)<Y(cPJe56jwo],f&tn)<daLe[5?YwPmlj_1uT]v/J*EOM_p^O#Eh.c[`&:38/d"?#l96n]5t{[09p16B{M)fxrQdGaRowIIrzt$.zC<Um^(Q5bL0.ep!E?EK&Of/:>"qC):<?Dt;NjI[UZ)y<L`F@IA8ycsry]@;V6*0*k$(O.*G{Tj2>Uf$v^F[SIni=j4o6.f>7(2"g,.cf4)Q*h2U5/^3dmYA8fgHXe`0KX%";BA-U^9a3dV=v=*sL_U0~F06-wwX5
#45%
&8`
Fb8mwQ=i&kZX._r4X2>$;PHO0OV+g6xM.hPQ*pI8M[3a7IPaNz;.qHx-K$-L
uU(D|4GoR^10__F*^iqA,R-q>Mc""';case"et":return'$s`@ibOZ+:%!(tbEk%<,L7/miT<<}@N6;SM!uih(D]x*y
3ygC#*rtW1;xWq70XNXW(:~&8$jg35naXvmc=pI@(h=kA0tat<-
&k="b7uw4w"-!C7OTY)-rQbMHdWY-55,?]0:[RSX<[oW450VAvA1Xy?P~?msGl()v5cA{<(aYxJ=_nBjJll4ey!c/H~sk?O=Pb1/sBK)^u{]#5$?UxYvE9(5smM*k?>u2?T3|iO<|df%NAY]"t*ag(}1p^+^Fm@8zZ|2JCnkFp9yfy
gJo~xV1fhh!!hKVybNLWBAO8+n"ZqBnuHt-O!Q#G4N"+d?LG%nOm.:#B"$SONs%|odT}w)(jrm[w%?
nEiw*^lA#)c"P,~gO,YHFm^Nr,8E,$9Qj.wfo_M;Yj~J`dHD-C.fN;JPa*)up
zZXg5-uKue*R0B_XY/}1D)O`QftM6#_&QmKX|*0b7`}Yi*vY[?f7I"X0RZj8n!}u|w
HJ%]2_9mKCG@][KCV:N_/nR%7>3y=R!&4kq&M2yz!pt#+Ky{fim&ad5xi+90qsDE)NgGm%0v$dvVd42zZJ9}##"Ehl9kv^NPv;3&KiC,`,B!1`R!c=tqcK@R1xM{kP_K+ZTC#fpLABo}N5QybovkGbfPZzc$R52;+(URj~tJ3.Pa;wpTecWZ1ab8<GlZ/>DQhxkbY~dL9oDu+b*iWXB:a|TaTg![t5EhjoO?K/Qp]7HKg^Sd
X)qGSS"LyEYy7<_ICPfsd29xYMina4:2m2)Eg!FhKIhW;:BQERs.Jyui?$q+yRQ2V-hv)Z])nhsecJy:Q#v*/%UyPV{OR:m%?ymnB#7:=9VH?_tPi!/8jCCiWow,58gk<gMd0$-xU?y"Z8kJc+L-Q8F$%2rGuJrZcIWOKQ8CKjFEV?QRg[bQXw0"cLDZh"n0vb=,QU|W%wo%ev$PC)DBb6[C7,D?Db"Nnme*5/S8gK?c~f}8Eml0!$b%u[c"z.#*[*fXS9LHvPyN3Lfxi(5j>346ehP4C9mfPsAPl9!$wCJ
>([c0lxuTs~8ClXu[.y],DU^~S95El!xh_=k2nI/F"ZU,;!tn#0@H8;u8K4$0?"%8
NSZ?+Ovml.*?hF^[@q,WU]GQF@*w`W@:]mudAI?4^T2(un9`"ZZ]Oa."odVW%htI|Q%"t%boyMcYbXgQc&p%#;W=Z8m+9KN(vov79Pxjx=!pHY8P628VGK=:;C!.L1x/?8t]dd>J`[4UyVX,a!Y^/0/2VDZ*Z.e^WyG&%W:q|gB=@eJQx7XN%uzH/c
Y#hOk@M0>&d5Q:@~mVmL:UkBXq2;ga!oMsmk/)OKt+!Pj"$A]]T3
(Wfu.[FC^a8^%WV;ji|oAqc%&3OoI`,b/1,0LIX?UXe"Tjp3Pn[M;q_L-N7Wjf&*xD<Xm3Ur1
%9IE&(QHv=XxPk{GZNWktg4Z,hA.HgQApU;!]H596ZVkzA;`yb3]?l3vh#OQY(U]uurc*_uMQqu.Dn4Xz86iKw|E{;
LTS!!ZnEPpZ[$t)&c0x/B^b$;<?UYr9oHU3fMK%V?_&`E]Sv7>8
``ob;Gy}E,*sSOpUrMSfRbs;q)gm@+lb=Vl_wXp_G:-Lc.ty_4Y6_Hwt3Gh(EvD%OXy1=#9.5ut&&$
A_-6")OjwlE/Rpm]*(txgooA_;925XQ"^M
ppq=C(c!S0:YF:%Q3CtxGSaY
m^dW)Q5=<]k.ab!=OXBPi;,u/LdL/tD8_Vdc,@V
ZtMqySbDflSDG5=IPvJ?IE#UfQv.CEa4bw[Hj?aBh@#Ws*jE]IM?e!Z!7x%=4&9Q1*Etu,rLp?rZ@vV>|QMF6)i_WvT4a]#n0,<5N^}dgsy[a1YJ;pEi]d[M^eKB~XABvm[<QjUy/Vp3)Tgx
cJ(EvGS@QsI)5H=QJk
*d{[{nE,%Tg?+tK;%Z&g|:6Y]:.Nusb2EEY/8Y"n<7uV~<ki+-*v8Y61Fu_Ba6yYOQK%-g6+iV]8C9
._k(@DuFUzAu"">^w,HZeA=-24ImX[HY=@ZfuX_1`e05A>twir0E]MK`hcIO/W@RwBu<(gtQSWtK^e,Zt$n}eVp?F`)Yl^<7^
vhb!S`W2-j:XSUh4T=m
1@q`9Df}u3xi,BNo%k6nTYCH;q:w@axzlDc3Of^I7RmlX!Ya-Qa+H#Y!#8aGC>Z_xX.,w)(R1mHdje2ThK*?Z_8[!"-$dK6k]7f#:/@DHN^?0^0&>0C6V6h(E$PhD>FD@H0_.+CaOsaNV?`H]"j1v{1;nIbkD:;knoE1B=POQY_Rw*:[%zFZv>91cmT"p|(xQz,,:NpuLu+P",jvP5"@cMIXXWLo4E9=P;&1d
ckyX!~$mL:L|i^.^7pZhl1=3*zV7*HF1
?*gX;k7]/';case"es":return'%`G:_iE.!0!%bo7$2@JWHO[]$;xGF=}@f,UaBD^*;1r`MLC$~`9,}dGdR!U%VVh>RE*P2yrt72TZUc`q-OdO="chMsdbMyfH:1OAR8}vD-Msf(,q?IUt1"YK2Ui#PU$>fHfPg(]xF=dU5nB,?G}n<xYKH4URm:rI{?ixWUc"iD"A+`|.j&,d3n=LsX4k]V9+twvS(sPlXg+O-g-P@k|d"]sUYd#kuKc)ol|jiVZ>.N;WLtSEMI.-1lhb:EiGJvI)L+Z*#PhVCFmi26fkd4USoSJt-YLY8L#hnEl#7e-u_h):~nnVDtd-N</!s6lB%Y?xat0YdHm?=w-?rUMT,>zUjOG>rn5^gZD=@MrIeiXt8K[$_Sjw)RXLmRT;`k~=0OVcVuaf]NZCli%"0yxtCPb!.C343^~HO5g3&I}+w[GIF)ZeyW]`WX%S_Z{8#R9728-]zT!F1smXD<h7!H|F=`6MRV/tD2wW6tMmYlXt.C315-BR
`,(Brt:qC_9%AY/B8.LD
#^xr*:b.X_(epxfVD]4amO5h#dr!,B7Qts9XfBMaN.Vr;dPRH+|Rt9@K|D>4A-M6rNuY|M81EXg$r8wl`4uePkXBCKNbusk8in?MXTZQ)O!(vDopA$G"[N@$gfc)U@tF9]&L9WA8Y.+VA8
ah%fP`HO"Z9x*2F?dYofsR%Id5o3Pn]:HTc?-
8*Xnb"4qpkS{bO23XZfOP2WXC+w8L*!ZSKD.#c#pAFDh>{^u#):{fb"L_TNTVun<F_&<gt:|aG!jNb#fkwWjLFf^x-[8XQP}IAC}`>.`9<<2YNPhWpLIx(KC!W6-^O!9oQLhZ*g!N
Rfb1$T?o]ksET;u^G|KohW/:Xr-tl_><wwm*xkV]bGPoLHiL@C.{)36Y4J`F(~9A$Wcs`Zq|MTDoi9HCF<>wPmYi2z.*sYKmRb;w[|ysjVwQd#Ny(<**/y5Alor5n_WsOb`F$v-]@ACG
#A3t^[#TApc!E_<Y/y8JW${qsJx]JfC@^N_e`.@;nl@9y@a=g"5@hXkCsM;V.!7pf+
%eE}4L"YnJ>$=`rJ-!X8C-I/UV$rd7owulE*Q2WhnnH!@]=]j=Kpp>_k^qh0:&wrQ[T~$cyc&Z,PCHOC/Vul,j
?"nG6#SGpp~]t.%"[6eZb[StJ,Tft34`^U/`M!Vc5Of=ue@)69-Zkcc6=WQ7+,B/oSzyS.%7h]rXjC$o|Vct``[HU=kN?OM@0SW-+B_i52/x
5W1`C<q}=b7Mo4jeWtDGSS]Vt"35l0]t8%/rE7fxAc6w1}.Oc/RZEKG4?wcK@^CK7NWm-H/@52Hq960NeN5JgP
6
sdJB"r"x|lRfh24Ua,FaZo4)9"hbAASF_8=WvhM6S1]V6rZbI"edurN!tPlj|C7Yi<"F*ZVt_/TCc:)3
Y<+nBslAb0hibKY)#|MTfSx!Yr%Qn4Dy@P)OoW$^9>N7f6!eJKO!nah
4^n"Ke$LlhFu(N?#.Y+`#3r#BEJKIw&GVT@bP>AQTT6."ETz0]dRrJ0^4Um-vmMqqy,xtXy[2kYu2bKV
NKot/$4NX:|*Ga$O@Ak2f(Fh&Q~&,#Ytzp,?&fZ_43@[mVsXKlTb]:F[2cB/YyAfW`.:ya`=SAH)a6)$JTv>)Bvpu^smL[n];U@,oN2$R$`wgZF6h`F66F8ByP+J].)"@Nx9Yox?<tE@tbO@{65nyR&uxMqlec,^;f+f"y$PK<{e)Wc>>h,qq.u.e-UC*>%UQD*T_<X
>c.B6]3,A(f=jx{j~td6#HpK-4u8.MSviDHmpB8WY9wjK%NNnOTgUNO%C`,%!RGhOpw[?
9&r!=(
0g"VLSf#oOPxBR@qf{.-ED.r[(KAv;5*K)I7
vftr
PK.k;UiAp*rMS
^~WI$nQ~QgZqd;DZ><qyd}NzS*2]F%h8<sls<Fyy6AovZ
&[jn"|Z5X}$RQ6O~:k=4kI3)aU(w*Sl}>kxVl2j+Wa12V*GT4j?-(h)2Mkmem%B`E*0>ES;ZNNP_M;8v)jB,3E?O6A[T@
bv]tv>ehE4!U9wAdX=DV@83Bi1ZG="4A;oW6L=H1#9sZE+!oy?3if]pr$}YUBfY}]Y&(/e`2n+#!RR#H^=&~/Yp^T@U-(-?x*tv}*wxWC
5*t"fLTldu*lj2XK<`ZB70oST&sNkML7DET5wfkwKOa-T+Z*y^"A-iKeBXai#7*B<E_,Rae%yPPUJD8ZV}$~*HeZ`F!*IoM)DuRm$>OGBnO]tkTM=^k&*GpLyS-0hSexea0~HJ]B`qj+.|DrKNGc7MF?M4U;egROcdFu33qt"0!7hsmFEw8EI#sT!dq";9!=9,Bs$M0EB-u5qCHNUrQToiM1.;]HrLe)V]abZiaQ;?k,?mlt#Yn13^:mkWxOZAN(lR3|pKwTZZp_6bI{b=B]sVn^no0yLHAP2HV0CPd^@1UN3PR:ykR^Sf>7:4[}CCU$Ay(N0dI>;`wj3J-o
cH:@OnL<!XzTu?/(%A14PxRgtL&
dc_c0)LE^h7ijlJ=x3zH!)+gT:O@kZj@dbnh/A*:_bi=OJnfkaK.N_Ml5No]TMR:I;Rh^lLZ|*Ij:EomiJ9?IqB_NiLs/mB0.kux
KH!yDy/!?iYflPA+w>3Tl6p
Xc;,C;Mc-RSl
e6[8Qm3duV#ZYj2g`<:rI*2x/+%A
K*-#L7mUyC4R7jEh.c!049EIch-=rc5&ka[4IS^%iw==KrA$t(Fm+dg&!8(^u$&&+0]xJEqX[pl24*hNXAFSepJrQc?$3doGg0HdQ]:AbjGf^@n!B++kP>
?IU8G7B0i6<_24]K2Df=s]VG!)l/?yq^A/b<=;(Sq>2j/rK]8;GR/W8=f%)a"xp?QSjD@J#iANS
us16{PC:TuCe_`A`K_1P[S**lsIV)e0J1`=F;AdN,9D>6:x2u.laYs1xO1e>SL%bPc_xVu9p=lvK9`$nxqhQ7n"#"2ixo_n4,
k${6A@4;.0kjzdjj~FAe?I!Z7G&$(O{@u]$>;3F%PpVAN`,5#W9Sj6H]KsmirT(+z-)1npfJ<1ee"yAL0KTT0S_lk$^teAY6.<ynB"u/9[;i|g?NdI.&L#S?k
ShensH%1T*W9;"c6HvI?6On4`d8Wf,B22lidfqu#c5>jk+Td(';case"fr":return')ZuFD7oD)(oo("r4H2XeK7I&uj]g#.6DtO1%tcl%UK,10Y_q"[Mx>Kv/@pW+kiNe_<)nwRnl:])_r+^1t7D+FOdyA3qczF+LiJ8yED*2ra;V%*)Hc86J
n|aEC_JA&l1
&$X>,Q$3b
06Gf^%$+F7hAFUC_mN&&jpN[MqEusX@`hf9rZuT>DNn"!<K$`WHRK,gMK%voJ}UmCtA.j-mB-.6#waVB(3A6fEj9/&7/Y3ii0.JqbUw)cLl5-viB$O7Rk;3!k,=IaEX"71W*dc9(K--M_~n[l"d6_KK%$Xw@:y+"SGh;!c4g@i!+4IRL$RyS(Z"tG:[!"0$)=Kdn:yyliRT_Olynpwvtw."#4xeJH58(;2!89RoLONR??G37:ta.,RE+1)H;L^^3B-)Z7RmrJ.=AJ:K_6;x/nNSE^EB]L%`oV0ed=QWv(6!1)F<GoEl`WuBE*tsB6ZKG>Fw6aK"8JnAsylA7@HO*f^c7K.sdPWxHdzo~!7wDD{PQ@K]Iodc;*SHwpag:lkau+)2s*Qhg8ODe5,_Z9^
*N(1#s(!I.KDOB_>?bfT%f1m]7SJfTCmhANOJOIMkAJQ;i&ystu,=U4@7MO,1KY[9Z2fljAFpn0gfe?D"9|T$,X#9(4p7Rv!~pg,<]|?,a6Xz`W/%-g?Q,n=,lhPD<fi8Ka.FIb?R3b-PPdjo`H%P)0)RBl5tR&tDQYMN9/VObveN;tC8i!U,64"mu,<63j
3o.p&ve*jxQ1;S#b>N8S?y+71WwQ3/OYD2v`/vVQR9S1.R7?fiHyWvhXSj*N.9xBtIw-"M:M>(3lB%SctRxu#Bsb|eud_xvwe([@Q=
XA[ldh0Yq8;G*XmM+N.F43*l)v`W`]WCZ1pHbH)f7dU/n`oS&"%)*t(4kOp?shEoOl5B,d<6/~faTnyoct]xZS>;64e=0DEQ9[o
v=PDfI^GB#XN9q:zDeaJtd96BVe?4sld=TVu"y(R
xuKnk@@;mKF-V0g:Ir-IW<A1ADsNmQ0Y!mxDYEJ2!i5QMM/GSt{`n1WFt^faqjLp^qB$0:qp7q72l$q49P)0hZUg_&N9lk;1f]!Vm@C-@6J/8HtQ5fe#zuy]/*/2eXh-.w<,O.Fe%6FO0S;P=BhJ/fwGS>(
jR*N%<c%q,OcKi9!4]Y2Ok&B&BPn|v<)9$s8
Cv$E3]48*~AEb{
o39W`$m5rSul{im(h)$
r37LUp%D4stx5]pSQ)P7{Q3BG@VyUHfm>vU;SXRHcL<:;v!RKNZ=#*S1xkakdwB(q?EFRIcwjw="Dk;1-,zVxJ[SdJ$ELMS6TI>U}T~?H"tVhD$9jA@e]4|m?
yQJwlq#17ql+-2t.Eng7b!;
D`}0C43IrEh3q:+ep`q`XBSLf#k3F.EY/eC>-$z#(KLX&Dyy
[sQ8@68dV6&L?LE)*iL2@TY&ChQ6vY"VHz=z*BPbq[E5A)di9kdU0{sVF{C*pW^X,woBc/rebrv0Oi>u;-I34~**4vSXI%e},l;UPy$%BD3?46E{m58}CnU5+=/`T6JGp[I.c4nVieh4e%RZEnC1Oan0<.epRht**{dZkOlvRAt58R6Cvi-zQEfxUIRB>ciuExteNS$G4e${0^oVH)$`<u,x:G:s/@V,KJ<W#_G?b3%Ntx8[r"+9,.;jo%Yp!m(jC,Z]D;V2YSmcE`hKRP<hgUa9#

"
pFFg%xyGJe],UiPUsYk-%,qgson"&FJ$o[{q}SO&&+eT)uE"67-Z:7cKkN.]|yhwH0.lAS@jV#N_I9%"O4356LYv<F")
bj)`wCxp:up#u]Z.XdqMb:9E*!eu
8(vymwt,Y.|r;@t_ap?#vK=m#O{)pH2?T,RY:>Z7!d}xV,^s=8Z/VJ
p9oD=RJ}y8L5Fl,~MmHL2DwDt_q$kHg1"*cuVc3/L8-tO1)TRgR^6`;dOM.gpI)8J]>5)c1V.}C>]b1K$~/ghL$+A*.B"KlsEAfY?[0@>#*`MXSxNmTaNArwdn(`<8d)wYg*/5!R:9Vw;#x^Y8lmj&r9!lML:4%11g>D@vy1Wie%KUCE/.<i;1ROh"%OpZv1+|uPx
>293p3@Hn"cY:Xdk9h8l^K&FZ(y6;|x"/?y+lCl$p,S<!
sAv`l;g)AHylixP;uu<W.
aLUKK!*h0V/]&".YTb)lr.P`;cn^eN*b&E5nt5c8ISn<R?@SM<q~f/Sw86eXQM>vsk`Lm;-P$FE07W>Vp+J,8IHlDt-TI.X3Iu2m^pe}
GR*;4Ud/{/iIbt@F!S)QWKvXL6)U}qySTUg9{4A0n4&d3)cQj0,JN9EA#U6oA>FS_#uVVCz5@i@-*rJ
sgvur3XbQl/@L:rT}2DUkY=0-10ELQe[m/S#4&6jy5QmC8u0Hi^r5fn#;,^7(Dz2KY00h+9U`je*,k#rrHa$>DSe+2&UX]G>5I^mCkvlrif${ni0&!l-)H6tn9{w]`R
<XWPJ2=Lh8F;gBU06Wm$mT#1q(95fOz6-Y*fx$8GMOp]V9}8dUE<r(V0[b#8Um^%bJRr7XGt4e?Gw`v=cIR
bGgMIM(9u37f9d{q?unRuE9;r]3QSIXFJ.,:Nfxmd]K#1BJW2RvpPlY<8vE13E|V*H{Ft[cbCE.4KZYXn<ct36Lpll)=p%(Ri:Ru,I)MD7dISgUsgkOvS2EW2Vzx",YjDi4Zx=RgY-+oYb$41E@6x[]hMW=%m]K1K5Btm3|jd#WuJ^p+vg{^#u9GDs^5U,kvGhJ>}O7DK?EX%&%[G.rE;al7.>j?cX[Bmi)hSe*Ww?@*N%!BFB{X[+
QY9^"Ni",N"&C`l#vNr6/q?_I8eBrKYv!<%DLm[;@0cQ*}sBmDh>e_Nw,G
z3<O",4dPz"n9n4=c%Fd:ZFK=3&iIO?n[&Rl"m=G4;i*3uW)j9ii9<`wiQ>s9@GL&pYPG0msAWEsmPJWc=;-g$7KmF0U3g1czP.lxZ2LAC
]7b;Z~S/Qmfw2&IMuuL?`W<;,&cT4mpKm7m}m7tm`lP.Vo1D+X9H<K-s-T7@fo8sYvhkvW70FIeMD^0s,q7|XKQlWBn|[_(y"K8nlmdoq1&<KmKI[4^.pK:h7%Sgi2k:*!&FV205&D>r9d8tUEpR&Pyx^((F`HeqG?NJ4V;hpZ]rV
Jj;iZ$j1>P<@3&O%Y_Wx[AMaSZuU!d!~5hJWQN-f?s-6N`C"#(-[[AB>t!<5dk7wR`[|6&$!^#B~xd';case"gl":return'$]^KjbP.!E&4otbG1*@$StDP1
,<HtXVGRt87DrgJ9qNCq)"X49d!&l8mE!ioBI5hMpVxqjr|_C%mDdH)11j!@7c#pGrP9O&|w&+DG/wg>e*>6qTwv5C(>90Lf53^7q37rJJk
!N:xAb)Mv
C1-vHyNNQUUM0XJ4k_SW4Ic@
*O^ThADb%l&AXO/f@Sf)Md?Gj[xOt7p[MrZqSZ>zB8]Jo(ps6-x@6Mca0*k]3uLC2g?M/KLgpogk6[*ygw_>fLcCYDMvgM^LqwUn<nOeo(=b^$l@4&gRqtLYMcCGe953.m,0.4=e8)5-yEGJ_
Ji
r-Q$c!tl0fgo_QHIAJ_h98Lg2#^rAwqD1sKk$q$t&s@_8R!@?9hwm67GrdN>uj}$J@[dkXoG]R6L9q5NZI6H;YK(p)q(io7uyePq)K~qf;3l1wG("9>h3b=,^[e3GhL$TL0NLq]OR-J(DRrm.uO3_Bd1-t0<R"fy;+0,@7*oDx(7h@(NlAbOENhThuBNw4]x,-4Itr
+fZsZRmD={p)3F(so
ux.H6jd{XE/~&R"`SID7(M_v/>2H.ldfl"KXFg3-.mD2$i_n
%DjGL7~VGq]WoGj+pE$%JG=a`_,
CNKJcFa-_t8B+jBP26X8F3yheK{>o^@,`bik]<fn=Z-vIcI;`&vjJvx_+cmHemt^1.@V"fBwsRkd5]Ji&Sc2[1:FTQGEM&t`CmYSULx)Jp
L.xvB6*`%rTGmcH_>4#]SW!HNT_L5>kr!
9?U"ILV?0QwZQ{-!&C[SGD`fZMoz6Wf(q;B2,!+AqsB9^8r0/s+oK;r#vj"]N(5wIRuq_=Ex#HGy=ePd9ePwb/7m9s
Hhg[Zw>>ODZA;BKp$C6)#ax%jY$;}vG?<ek4{"g<lN.p>hX.EXO5W:;#U++xZOGr1JyM
w?-Z0>9V&_^P"`"(FEcb"=1lHGq6wBVDprLbsLF/V1;_:"<K.<
d<=5qBTRLX*Oc/p,DFouE82=d3+qIeoGpX"w)7*&BmT,F>mAm:cf;mS5iPH<GuA+We,QuO#A!g,04@a[hl%90nO8SJ]]Y2*B-)F^$$GeZZ}mC
>&ZK=&r*DT&`cl%(f8EBMb,-,$AtiF#N/_3P;RPC$ovIsX&C/j?R@3t,;FKiVqQ^A9MhEchgt?~FOlp=J)8h##</jBJm{&zMq(CD6E-%9w7u/^bMuf(q*=,ODE?Wayw8j^Hp0G~*Cf=V4;h&Rq3^23w%$iP80QLoeUzp6v0/h(Emn(MOSeG7QP`WDa}&a-E#~1ww#qE[n^xjGNlm&+wE7>/h@Tr(/R<fCc)l|5K@|A@0QRBz#*twjdu=]S$Vz!h*xt_/(9o$8cc,WX!5&!!G(sSa|#F!7tjMOYh#0cCf5ut-N+VX>ssx~teHqci@*:LMT-WH-<ESMuqOkJ}7G&"wtn?.pnFgq3]P!4yk>C!%Qc)AySO.{a0
ww`4ZSaC/QR>=TPP~VtxVI`V
1bxKMA"U([At`
:8SE/{F|VF!f2ocQ_Ogm9$*0=>!>DvxmN[HJ76;tYLLySsB=oF3_->#YbE1EOp4)XB#}LQ2a&YI_l4d(V1..tXR0:zMTiaQ<QC!PGZ*X-;9,i_/}
g1@a|e.Kjn^(#EC-[=fPjJcDwm1c3^%s3yEwnc{b`WH(hq(xK)t6AFcxWTRebJ74I(gJa`nB91}o;J*d8-&0aP6ds-,ZT0hbJ^[*H0"@1pbwt>amI>h7e?R-$0?-tX/AmaHg#N7L!Zd1v;jP2(Hi_?:T<,gu==<"rTV${&Cj5q#ILHp$YgEn"ui(!75(i9/-c9iMb^Mp{OdrBYaCy`uof]V!I]{0S9m&"
Mot8O=p6R`
>7ms8u@BeQPb*vjXq)V!)-oFPUW8a>pG*>T,nkN%L[iDfVh#10fKj:USm~wC$iBf%/m|5@bu3k8T32O:[
jc:qw~Y>REf5E{/eZQJBV&8E0IV.W$!}IaMghGU`5[cX=5WyfHAo`<#)ZU##u[<C!^n(ig!G.)7!neZ}(V%hxY^CP+Z&w&cs$kCt6;5.EH4K!TH2bkn9[3VuR,$B/2=!4]U$7!Q:S)c@9p5|0,6siIsN4+rJgp.RX26`m$dy#|Olh
mh>rjye}+-7]]"^5CMDV63uwJ+td[#*}/8!b=~)_A.g=l)B4`+ZCCsi2O"6kBvAX+R+-3$t
IT!&`#0+R[=0Nbk5MMpt24M2z#;`g%E9E3:{i,=TJv-$eoUm<h&GH|+HjQPMRlF*l$^hF%t2`+$uJ4fj>aFTfG!ns9$SKbOlk2Z[g0<e%a"r)Q[a*fmwKWAv/e.V8L)**#GCh>[)etG?5E=Nwn;*At!hs.*oF
&Wt>`MI{<2jsO5)z/xxtAODZQ:acx[d$]LBv[u

,Wq|%Dc8&7hm-y0#@MHZ<"m$IxTX*<(sU{Zj$F>[r0_M@{F4J<0$)jK4]~f.aY3@"EjE
}*FFk;7]D8qLB_<H&d9;9v{ORUUH!3,+6gYiS:{dC;s@`!%[*+3z%_Gww>.?4
Ls9d~rhkfJx!e8@!eX]<UWO][lp$T-wMb9Lc7?K^(0Dj7Zzx.m`-AiRHyvg_#QGk[t<JMv4>^:Hy+tLE_SFeOA6Q
_#;O."&ft4yF3qfuH5gZ_8FRW%F58~3y^
BTsi]gY5T>%:x=5eji`*WvK6Ddrk44L0eA
FxTO66d?Tlg,<2yr,M.YWraZ2gkw&?By.tA5Jp^3wS9d,Z2hMZ|9h4$t]6#J02Ui0"-MW!V&,;aQ.v_
8<t"TCD+a8;l^gJ*RlXdbNiX9dy,)NffY:Oy0!
2w

s>e[#yf_)GPzf*=aefD%>_<Wy/
LeuVAh~/"x3N4WYC=i5vmD)WI1-c;&3GN#JB80WH`,9(387#(G+5jcM_AQMeYk"B<EJ4`w=!0cPR;0-I
[F!Q=nyJG]KR6fiN`a_p"2/.+"ia;6B$H#$Y3ZH^XW,Z[X[4
%?6bNfeGQi]qLbx-0#F@lyKHTVH_m?3[pS}98]sct9J=X4,gywBV{Q/ZRL<ekIDP5(~4<WN1^0oyIQ-Z=:S[UetQ]nC%O+xv@.
`eyyI{=L)OxFQnoE';case"hr":return'&]^;:h".!/#/Rt[2tE/AoxDJ
e"OQAx.<VBe*KgWW%_"/0a+^dpXT)uKa.UY8GR*)q-q:]vT^@2KsYkHNEGcbUnAAH]p5jyfXA;mQ<Nh:+Fs0adkWI3wU4FXAYFrV=QDnM_oBsmLe]hS,jcc4gpLN=5^yMev+so:H:KN!KLks6z-6Rqgqg!eB_ja6D0uOvY?byxY_Cw2]NFI#sQmM>hnxbbkWCHv|p*bb/tMy"LAG>c]7sSH%"|4M[KTE22Lv6<%HwYs39g&%iW#L-yMqT6LS$/]4OcJpEY&er85?6lJ{E@DsIt3+=Fy~/J</!";P@yN?ND!+e;[clB;vvgD~:;!8u/&$sF/PUY
#w[Wa#udL`lTSuxmvMB<C$)T.t-oe1NGF.,CIuXKqQ9v,1YM"U$PXJAO2K^4xo(ic`?$P;?X&!{Ok/Q!cno?g#?9xN
tU(pxtI_>PcUS7mS*~MK+%B;%
p?=PYdR=7m)h?VsxkPQN^aG=vJLF65A$@
fE
Zs^_Ym9:]K=:b0d;7
0$~CoHqJ~-lEGw2]BG7OJlGlas&48F/yu&Lpa
;e7.;v$O$L%.Wm?&cE5Js-Fq5"uyMRNtl:~W4sFy^6,JuY)AwJ~LR4dP`s3,Uc3u.L41kI_LjN;L<r/g$Vy,JwLue;.5:CVli0`)P88&vm"!yJK
=e5hUV(SDs`&$dFm0)-PT"^5+j-Y/`
49oJA[]nB*:i8H;q2a&v+wdS_58ry7
p9qxjcIY""W3(PjF,j9-DL9i_Jc-NCXK}Lm$?u1!W+$4L^]w!:oMo59S?7,HHWOd{>2(SVGOcwI1rjg5X$R7X.MIWiRJ8C)_;
UavW!SH](.,;_8qFw,S@af`ob<G##^~RS#f
}vbi"h5=%TEFat|U#@8tm.gY/f|c
8=-&X",V]gPwD0uke8j~pVC/bQ1j7;[syB#c7d8_5mKSI/UZw
!"$mqNs&3E0:^|B+5^/!N=kw6_]V7#pB/^[s7GO%m5AW_-Q~Co8timh%>?l^e@f~M`%.ww,Ll4hi>xYlYZv3](,u/.eT=-H}]=f%6|Rn"]ng
8T&ixr&[|eZB|;55^E5k<EpxTPF)Eo]Ctm%!S`<q9nJ>7j23V
,vl%*ewC9wd(e;&e>K.J"Ua7qW^JXh|78A|DOg<Rd2:Ok<DrW:GR]SwhjA+86&c!=!DmS5q`p#O-_*X.`i|QjHGtWt)ysjmi|ClelqN<~Ya6vctX{L|>(8fjUq+tDB$(=$&=gY*s!iNf:35U2<+ph&K=3@[K,4"Wd"X4BOI0N&3:Ty3&!#o#o#MS@0/gR5am:nG]F.%)2M}bQ[+":.!l"W+IIL%FK!t()kPJ$2
miFf"=
&-pMkkItxtPM`!DJ1G3V;GB^FQHxGgJ!zpT]#ev*V1cT-X,wxoe"@o}cbm+8):<;E$5-i!Ro9O-f$4:K304sKN?Z*2Vq1.V3p<pwPiZJ@/*)/&o#bbEGu)+=#YdE`y(C{#hS64TuJ^RlV#=Zxfr^VS@+4Gcf"-<OM&Y3|>oV9/#st#zV@JnAD#_1ZqBC=h|_MWkc]-e0.2z*x[3jxr"0&MO+b!#ee2qa41k.JJwhA"-h)b"qV6QQCig2YxK7S_s2Y^Bd1!OqbHysg@<f1gD"?jJhY.yK=1J$(vwS4*J;]Umt^m4[1`,(=a!BbJ$"v!ov6"vNAx:BVyWS%0lv}wkJ!=#6{6;5Lsukg;
>3QBr/`Ki#g)yy/-/4$kKjNRg@wO,94Ob_85#-j
NMG=jzU~U!26u7#YPBj~^N"s%LQZB0Hl8~8bP+=U:9/KJMr|
KXJYES!>Ih0^Q,JK?RX;3%gX2i0^hO/0S95hmjv%]Awy9/}3#;Iycg0"4r2haJA&Lp-h]XhjK.!A@oyABqqFwVdFbvmK%l~(v=d6+n
7p/R*[chfgt+XLm,H&7BTd_?@|2CWvB>^~7:hJdRU;o_f_$SF(!!"3Y7AWl|2_t(rZ9MR<yX&0Ll>-
$Q,CwGQ]Zul^f"7x9":!y>:f|QdeeL~fke2hXT:5Wa/b/-+g5[Gm*4wB:NlReX1Ks#!Od]{7.H5H%)UF5v9]*xXgf[WD#h?xE&_0{LQdE#tv(TrO~+W.XZ_y)rL>OQIEv/bbw3Mu78&qn?hmM(dkpgkx;Sr95l7ebXF$u9pytw+t$HD.0Y]r*iY/G3bV!<TO@LMSx(X(%q@.}]5
qrVv>CN(`lFTW=DD+bk8K/I!Iav(T-TqO1,Lg
"HmOfH8oyW7Z6-JLSI|e:6~x;TZxrGqe2.u0_[a?v^F$zDYm=L8-+k-QoGqH8eCH3w=ON
nvMu?dT"91!dZ%<`Rh<-l"ZA/U7^YT+j}Y1-)y-8)rUH1DSZ)HpLDHu$Y/V%?E(5nFyfPl0>XE92>Z%,ySa;sf*vP:E/2(h<"EeBO)$yt1C2%79KKM/kc^"1Rt%QyMG"9/AH`n{9UVoklELR%2T4%"@uBd=iw.>uSB;M4w;ZF`Fm
((m=BA9;8P]=sFV*+1Q"s!cbXF&32
B3oyPG`XjzfbKinPip)!uF&7&3(u]OhM^TvS,yS#@-NZbfYMhhfY;mrr!x"Eq=%"(bF&]Rr#_42Zo}W~o@)`m9%V-[X>qW4K7EhAxLQCZ}x&FsG=C?>^Qze-AGtx(DiHO}t86TB@.n2d6H8/3PCq/:qE8Az#gybLjD/YwGXZHAWUU8Dio7fr6)2!^s1@V0E!d`%:IO>lZ53_&*6|(WXK0JU$m_T6O]$0xUBS[L>!k:2>F_0Z.3-3_|+}KH`Di,XXcaK7fq.X1J:VQ4vHVdJHpF+TW0km%%H8L4hAe|)&!8G{!s@`89S$+^3O7*JJj&i}sbHl
%oM9;59lAr1/n*LWvRu0s0(XFh&d/<Ln1UQL59x(0l/;U>wmdiEJT7`Grh+8v:pLq2*2(
%HXR/@g6`A*Y=(}4xI+b;m]lzbMU@4,@1DF!b(QsugqqVCj]QdFK.T.9@Y"Fh:m]{q|A6)Atipp8XK{/u5kHgr37kM"
;77W/+a^B;BN%
T2M^t22SzuORBuZD*KbP{G_KamV@_s,xD
^o2lq:}Me:mSw.86U(iRGN=]Hym?@s];,W
ji!L8n/Om8)DdekSjeH}P3sMq8K]Sxu*S]T;>Ez"&&T6^rpn/3x!K}#::Ud53@X63s$H';case"it":return'+]^@iaLZ;#>)ntd+jA`N-.^h(#%gA-&%$_LXr-1.vjRtR,oOi^?1jebuEYeUpB@LC;3E13#(Bn_lO7[S*ZqM<8l5rK3#<l|C1$cgOD,q!VG59cpBR(bVT1Q/oJkD9fVF@S8I[;$,8&gmF//#+NRC5Ywb)tUV0cPL{SQHw=VX?Ut@:B>(YGmGP[-fI%JB@ds#{qWhDh;v9M.0w>|c
1xV]?nBi8(1TA20KL-/flU,-$qoOJIaD+H*0+~qfmcxkN9R*S:IUkg"M
q2R(+r2FK!5oN=+5Y3K6MKKdWkVS,LMHaTDr}6k<XJcBKPPStIrWgxaC=]/%c,4d%oV*:DL:#x4Wg*A4V]A4R+1MOn$y7vuxL)Y)nOXJ:I*uqN%evUeb"c9xusKDLZ5!@dQN$6F`Ks<x)n%##KICG

la6<mV;#l3>$(M7&Z(uEcqk~PYtW"
`icsxi_en{HOmSTsC#@)8Np)(jsY;-)h19[,A^B!0+&ByabAu^sHxNExgvcAANV)Z3QnYvK{hH3cT~09=+Ls-!(Vl7cV#h
C1.D^2&y
k|e(fA"ysY0~B
E]ywVN:/%%0xNZ63,[Wff(J:$zip1g4}ed6@]rZJl@h5^(`dNn/**[@>%,Spm>3oU0L;rZ
PZ:g9&l"a/N80nnIzbg(hVMJ}!F3bRW"ZC1Y-v%jL%|G%g8f-]3owGdq=Z>l(?E
}oZ0.[qjT%6"2G)4MH"=dT|%hExBT!Pm005<gaCIII.o7dz2}#IaIt_+FbpUuvp>;`a&.b/d-`u^9$l=-R]a}^92bS;tfC!,8p^%(R/*E*jb8Kr/#d]Tq9^j%
K.C12&9^6FYu#+kO.##cqe:qh]o5$h`%,0L,Bf3-URA/B-9()e/X"45!af
NVo"DNWIo"Rd
eMYIx0t/6O1,r7G&>mX-.].u*YWT;7.X-q+Bme|j=<#xb3-Gn0$S;N8G)V>^}3=0=1MuM6~#7?3ZQD&-2r
!00<9|XXQ
%;LX-BJ3`1C%<<0jN#]{:{<3c!,<%DRq"J6o`ZG-eGHt6.V4;OjY*{HA$t:f%LJ%*0m(O}"1F2>tTE);;UwjpR0
V/QBQa_Qo(-&)8CQDME?A*QJ=O"x$-IoG1+G*n$<bm)q*fr8vf7c()yn2IqyyOJ#l<-MNv;<+R&.SwU~f,&>0@&HXZ#0XSEz-nx+;Xq[iJrS2<TE`KjptwX"llWe)K@,EBqP07yC"oJN;0ayXG!^)jPaWSXs4p&<Z1b0I=sb2.xv,^?bY5e{:aMru~fKMntZ^O([>>V;RS_*rid%e/n-vw.~.<"Buq=R5Sm*fO94-)xa9,VSW!Nsx:li6~SUcaGM04iXJt9h+HlKE>c""/Es<80ae>du:a>2h_n(9PIjEw:wBv8=-2i<6?oYaz!|?yHvre4d$se3I|Ey,.S/7w&U>W&YP*8?:Pk|qu%,.;ocru[01=2`S|"}ykxs:+"D9%jj*,U,9=B"np:QLIPOFMql@?L:$N1H.(tiJ)Z==Z;L!^WT/ptJ0to_;#E>*;Uch6%u;p[{xBWU:Duip%A}!T/>tX,he#NdL-#fs.$@q)PYuW
we:jC=8>Xu7ZmII:]_)K-n]fkig4qL[^ksWmZQB3A"AHcV-X6HDRZn{d.F$tTt@R@Od1?E]:Q4B6|Th(g)cc7M#/R3a(Fe~ffQc(wx[I<RW%`5L.AF/Oy%739eP`Zmb&=2$QKCvz"D^nztQ5kFJcGb+_0H4;0Q=iy_@m,[G2{kqCKYu3Vd}eC<GVPIe1>Tz#[pQy!j0o8I-D5,v4Nu@ys$1>p.9o.2AcKN$SK7e"GlU#Qkq3Zd5,E^t)N;-7Q9^l(q.S=yn_&<amnv?Z:g8S+$TcXs5Q*9S_n48!hN1^Wl,.m^")*B(6Z+yVNbI<DVHxjb=b0lAMQ:fbzmIEh`)QU[DjMD~gVh,j~mY7JxRWKVlAfJp.MNT<[V9:l`:N+12PkUeNcDl@E2Z,jYyT]V%6pBo0:OC-7(VyQE<>DlgZP@BY9h;4!-7+G1C>a"ODz_]J7:?:0Aa%EF8R(nLuQTJ={3`$F(KyR,{<lEN-%)|D&r&I|2-n
)PH,od8)&zm^GdLNskst2MMudh!ze3A)HS#9Gx8^NkH%4J9G601|oMRv*SO]c@5n$ZFWIQ:&)/aBur]PBm.&##uy3$O_Z;%lWvdG/T_p%))-73q=K@JPG~]gfZN[pTA&o
_N^ge?_&GVc.Rq2M2+FdfOocY&xQ
K&
nX!!<(oBOKwBQ#UGLPT|[|vx/Oa+xG!icc!:8}]##YV3R=TRg4cW[L,A>i6cN`^0uMU1Ow`BBI3K$@yV=P@rqJEpH@1`n[uRIZb~t&^"K&B,x"HG^DJ#`/l(inR}
y7TXxX3)2BHWCybi5s=Ej(`#QU&XvueFntLjw3Zwn=?lcseB~hT3j7q#G0.@Xg5@n:=U&B!&cB;R~-y_b+7Vl%-t6lFp8gG*BDX6v-DuR27kYwRh1;b9x[:TD:/a;KGZ4pz;R$<Z0Y,]:h_N*d4#KKhe=F{Fw$`
"91D{sq1rW]rSDj09ZFlK8v@#-l)Rp<as>ir9"g9Z8M80YS#8DWs,[`y=rn!oG7Ii3kBR%Bku0ru{xja!/aD%iN_konkcVa7CtfK+)4j1XPhF>p1mwl6E,~KzDsx]WrXY^Ysb46CB3V&2%!I}U/)ZCzg
56(3QW]`vVp~6/,cPP<#Hxnd`VH6aTJ`c9,AK/9_^m!%Zt/8(8ri,^QUJ&u~vU-m)B3([E8e"/"ynW#f5+K6%K#@XWt_e1n-yw""';case"lv":return')s`:X:wZ;0w_bm&5~P5+NO{.F)|Q69GfQ*.jg8tRhOQ]xp1^ZEPq1qo6Rj2N)Soa"-j=zLEy6HL9u/}DU@IHV^w0aEgc^_k?2c:fJ#:U&F)buFH`M;A<aRWrDRLHI,mc32It5H1Vvci&Z2N4R4N;XALczy^D2ZHXMo)y<jO>]agjD-.Fw:Kq@9ccQx(tw[5urji]r0+ctX/tV]?5HT{>e2jH&wUDcy)yjq<j[5hN|=EZEmoRxD)e4%tuKJQ]D[)7jk-KysK=CH:d*+`0CV!ybOE>DtA^jN|?a)#,h7@rbY,Eex-Yuw)!F,5F9F;+Kd{mSGg4J7b$1&gDC.Up};R5`m12Hu&%y>!R#w{VR&Y48392dqi=SH(TZ!I#yHdHG&a9FB30B5(65,kp^LL^"&&#tIKv.tPCy+/ah28&OZ<J*$CW^7LUIk9Ja$.lNi%%nNBG)e|*@yE;^
]XvO@f_Qkf3w,!sG)38!ewil-i.M[$!_4BRlJTtVWd_!myd`6,=)K2+dVAO[Kw<D1<1_NNX>`2o@jXs,.-,R.`eNQ$~gUgphn>EiMPRZj/sicYF/#`mw.(R]Y]b97l.t#L]-PsC.6Pa;o]^=`6f$=];"&H7Si_b+z3PKb@:64H[UW<~q&XZ;7qp6n-
lO39sU*_m~M@/ybCN1`C=BW!@+Qe_1-LMFJJn&W0q
@
CX.;"nudC$8
oh!kY_#Hbq/8+9fYe<>+H%iY9>u[S6y2W.ElTrJ>(QyZ%F^0_*kd%dZ?>]lT/!noOfwU7{d
m!MTGgvUJ^u"8(6,[)9AaLX/?CNd7nFm7B()N*;<?"r~!<B%B;IOkztJ^@gblU>m6F*W`/Y^[wbH68Ew1IORwL(cP,-yKnc.II,|7*5?.un(TTgGNdC(bu%g@(qyL*6fAnu8`Jrnn*Wjw<_yG6HPjG-+:A*x81Uqb?>yZf>-"Kinr1yQI(>R8AFq%auYi""sipRX@dt4$2dT:Q9fQx/waL<`"F+YoU::jnrC8fU2hEX]:)6kjYaOSieG3eK`1{rJRa7[A&8G].ksy{p_yqgU9_!H`0fKJ5hUN*NoD#[6Lt:[*Z@T6F08)ec*p5)u";;,5_t6op""iC@]Uoo^L}.N*d]d(-_G>LQ>w1?f9A
9
jQ!8&P6
xVbuXG|[^df[WD*b@5XU&pgCRD<n[*6j)<NrDdYD|OHNYOP7l_9r7@A3rbd=@)F2Z3`$e:IozFQFiIM9>&RF%*{ZR9gFDt{OTVHgTTps}n,8W?~yGHpa%nx@.HH3~yo0@"wSVReULacq,r._$1g(=,L-3D&(QmLf+"[Rj$Mv2t{E+Q"`1T*[[ebXF8t*zS%Tm">t`-Kr}NA8D1wx3f33*Qgw-moWXG"xGsL_:fkq]%9x*oEc0)_]%l[jUSVC
ggdd4|-H9kC{O|_}XDX1IS$Z!aSInAO1JlAxP7S/G1.OU!!vb7W0Ge[X^s^0i?ab8-]PW2.?J)L6!>N2&ESNd|c%RN_"GsvP+br.rnkm6f,f&_0]R
odu|G<r$l?,AT}]h*B[(8`"j-g(B=A)>KA>GZD?%-5"Ba8k]Z6hG%rjVvnKK$GXSO"Jbj:h1bg<3oik%1OB]c~@3O3R90`nsrZ_x9
"/!o5^xf`^#lr:XLR@Clq9O.SqYBB1Gw5K*RIjC5Y%88PC0w_6w%dU+[*fF;(Vp8
2Z-ZxWs!j30!BhP8x*B<5c}i
gx4Y1n8o,Cv^TpWgNl:1Rk$igIeLU+iyMg.xqie~"=9e8Hf">FFh`bS+C+!:5(b,5M-MN491O(3VXwctO*$)ibOd6b,`4K_4aSesB`pq%[D2j@8%Q-ZNks35I%WCO_"#@sajM$M>Nn$pMbVv?A*xtZd;@U%GBYYOM)Lf:=2xEjj:Q:)I&V$.(vN`C6O!n[vmw>:)J1gFKW&e)8:yohpFVe0o`-ppeNR_Ip`NFM0,*4v"_zudkBwR6(#+9#ZiV:D9z&^d/?Ln(D>&I?ez)^orsz(vPhpoPvdZ0M)ARz=*gR%<-_!mJ|UzlC+4ErPUmdF<P_.2Q
_D^>Se%XBgc_ZY&P?Am2!u<]We]mBa$D.FTGq]vP"EF;^`;=@gNT?YxAM!Qty"C>BW_@3"B{KM]Dk[X
$@cYpiMFC]fNcu51vV;[>Sy6!dT}l?MfCJwsf,8fdo`yU$-1@/+^q
QC
A-*
y.>bEDh-AAjC;D:T1cC2)=h`fc06D*lpHY>MAKT3|S4-9G@jVcn<Agpg<XO(%;jgyofJs,,(dhF7A7I225~*gQ9T<)oe2^VHtw0_}3N0cD&DR2K:^0{*6qw-29u_S:<maOmTA`p1VaQw[cuNxFa[ZhoqhX#ZS<)0?4
sIaBw0?f[Uu$0p#WFHx
NgQ9u;64cF,X4>dZRaJnj$2`61M93z9diecM_<r-9BOConhn0V`yG
C%(|qvqe3x9?gTu,M0j?l&nh+EM:WebW-SZ@NrjB=v2JppsE6U*it#a6h`0n@"&:Zr3Zdwe_t;k%Hy^M4H.{TfCnYcD
,gp[+b
v]I!u1k<P7>4D@gngKX-^D[b{E4YGRy>w4:MV;svS,!Ll"gOS`L#BsFB?s}/JGP%FV{95jmv(.sef?S>vvOMO>:-586Y+/Ym!O2-}[^+d)*0al5L}VfBX-yYc=D8W[V@f!7d($d84JY0CIrP?6S8(&dWx_4j?0sL5rq
`9u9vLdyJ)mWCG9WaTRdr35(ca}S2YC;vk|1fq#6y>J4-Su$-lz^mToeCv9.`.!.O$V4`
OKwl>5pY{dh_#M0,]P{)z:t[gBw]kr#q/#yoWsPYPn0jNKHG4+J-6y%QFGCY!tIUH;}v)*#8XlkFs"aS+.7ko_<[rRsf2_c4H@4s{f#5FR#p+s&qPw8s)Gz1io8><`nfy=O$h';case"lt":return'(s`;BcrZ+#?a.o=a?1i,hxv3z+%+`%%ItBr`ldV.W;}a?Lvs|XDba
Id7Q0JmwhyqW
0K0UF8:G)W!?.fPELudc,msNU`G_,}[=LnO5WL:k<$CwY{!"v.A@jv/Qsd
g3y;v_MVw]XhtHpEkkXqGMxiKA,t)<j$cY
tM)x+
sl%i-0e};^Gz:M,GcosSW-TrB,laZtN
`G8RQ^`V6-OK&W
NH,

mRfVvOKev^#.8:oTmFcJQ4k-Z5vY:MKt#fcck_gj?)[LdTHOGF=8H@pLH8Q:kFUuJ@c%x?e{eQy[8"&mKvZ&5:^FtFSK:1J31p:vy|Jzgm`{[wkpZAxUD82BN.deeFaQ.V0^p.ckEL`2n/reuP",B
TGqcbzaIT#s$eeCRyH5RW&+#iFB6N]`8xzUV
e0G8mT0#hC|=5%M_z-u&C(dfp.
y,_u.CE2o?R(1])Ho^Rzyc(BC4/rdoOO.(Pqd1nUw&At[UiuSy>xNhVbbZfXWXj!=DC[._Pu2dbNo[y5Nx*1UHu+q+DZr1%3ZyOpeWBH<>dkS`cHPNn><NRnVfn8ZX-(83w_WcKtX3$0>,ZL-fXb/FhVXh^ML`<0i+YHl@3usTg5E^?rU3y)+-+^2ov-JBE~!*PJu.qfUU+Zq?s/<hkw=.j>WYxDCDGh4=_cQ<kRC|^N"VbN%^@zO.0`B7oCLU*YsS"C,ZZ.k>XV`GU7>WA4TE*aKzK/?c0(`B8;P8A,
}_tsBAxB2:twiD+jD,)KG)8u1z(-yDY,LHV
Wq"4weuthS:(8?n15vU<:s(m>t~a(%7XJhyNEZ2%|,?7|yL2xb3!hoaMK:eiJ.0k,Vjd<9ZU~MEa7%<j[QR#rG[[_<wdH_z#l(g`fNzaR[#;lhcrK7):*;mZa;>c131><1#wQ#haNSu[_n,Sg#O1$+0oxq!
YaL])k!;N]?ra!7Lk1uD"WGw36SNcpx`_fBwH41$yMc@h(]!~C76f=SL$Hq[=%UJMG2Tixx4BW3mK?(2MmZlyVl#IWOE>W(hxhpYNAj$^q6sNwoj9-IrBoG%J]^/=ZXD]T}th+u
+Iw@`4?jFjztXOiJK-l:D.f4nW=.vm[OQDtJ7Xe"}k!kPTM=Y<EemRCOhAMN"U`XahW,iG&.)XsLvdG_#`esqJZUa(
_8,HkKHZMwSaJ$pjy
-/^0NpL#jmP:">v&9veg90
~f[S7:a-re%"R!Ww+8N
D<[pQ)w7s.d[7wC:{<!3PR(2;S^xDH;"j_{>~V*PYt@<vy3G
a::wi{3mR9:|*w!tc&5Efdf5c7.a)CI?:rW:4[,mAXq_?ii^n{2J.)TVD<Ce2"Jhu])"&cjFIl.C98kAR.&AQ$E~FXfKkn)mYW#N@fYD_-:!(Rg~si$MK|i->fOkgc]8ET]#Un(>,%/F>]8>33Us#:!Q0P#F)UpvFfhg.aGBL.<a`NUQULq,=u>Z&?Ox(:TdwBa=(.bNv,<VH=P
$HD^xuE1f^gmg1_.ow`.c,P>rQ+0Pa8+c[2>Nd5L7uxM;MapR/j
0^*fT9%p^k&~<>`.v"sh7Q:2$Hu{AeVI4^s-fM8{L^FF;"<(>MoT(<3j&M("1PW!"d,m&VT2/_I<ya.ZdyP<CC<#$V>Q`<aJw"suFm"mnBkVu}8[OJ#|A2>C:MvT]aK7Ah!_qD6.45I(C5>?l5Yog)@o$YXL4=Z%E*e=V~Z$jfgeX+w!(%ujiDaa]fn8AE4CVya}5/YF&gKEi7Uy2XEMP-Ee3MK<pzI#g*7wrk8O[oF6Vvgf4a7Yc7v+t7+pLY.g!04^@?P6)Ubg%&"I]+`
9Z1pSjZ4Mt5gEGxdIerw=HS:!z)HUII>hGoGN=s(;e<v(bUE^iJ,N"s/+6i!LE.jUx>cnWZ[p{N]0r:~@ma_h8g5Dw$A?QBPn$e]E31l(hTo;xpZ(y@(,?h0i|>W2C02=]Cs)4utEa(I2[,/Wy^:U/G6gGE4eEa,yYcs^V-bR2_*Ds^]&]ypk?&@^UC6xp/u]3OUN,o!W|18"gUrD>i^*jI8YB5L$!&p/xuFKN(dZ6]iwUx0TfrAyx8kQKt0sZT>PU+!,]>`NJRmHTr=ZlHQfBZe3{_18n*a2>VEa:[Je+;]nX/l">NmD:e)K!+P22(K#CKjsoq:>BP57%0%9k<AYcBM*WE@0L4DiP8o
#GTHuI02YN?HN3N3h*A,/iWnKZgSplG;)<ykfyT5ti(9u#|y7I`LcII"::B^GR9/}QFe2_T8?T5=:=NGb/JM%&@q(L/A}gbL%[O
GXKq8Gm"g
tNG+<d/mfp9,)V6Q.@gG&D</gM|%|)1!fjaD"6;@{S$K#Z1
HB_.wI$4jMnJ|itdp44YJ+g=?))-<3PXFci0@JCW(r
yKu_Nu1=8_V@*@/f8iT
k](i
3B$EoG!lDqUWfgT7
=Tlu&@)"GtJYuwj}C|CJ79$6?wU@jG9&AMZ}8ba$aL2gx,2L/)';case"ro":return'&]^;BbpD9(nk|u#ug-#.V%oVOKD(w2n#j$|3>snsBe3CshPed/+&!qjWy-?#@0(!M"]
00|Ej/_](:gVy>wWMX,t+nA](M2R
n]i~sxsf*eoPPWdH`Yc|y$"1tXNXJQf>K93hARfw:*z%#wCLm]yA[|t65IYDyEyK]nGo$Au7f(434}P-z)#?tHeU+2]o[qvi2*4iv^t7o6,[r2qD/$E$
G7LODj7tPA"Z
,xgK85=~8_:LO[!Y<g5LDnN:I#L7q2eHZHytBJDP8fBqyD;>%M8+Kojpltj[5~mz"F<WqJd(M9E`l[fbJ-7pg!2":xB`a8ebx_J8Gs[88CtMQT9}.dQd4I(SG*NEu?>yE2$g-y:EQ~rR?rXMPut.]qG0G5!,PvwDqe:eWvQot(<=]0<SRAhnDtN%dSS.%$NkPo)OEK1Afj6;RII8)l(_[_Nw=WAMe.=h:sOVPov!/T9c>w2DJWW6%7AD2:m5^)>mJ8=kD:+J-,Z1kK849T>V_
7=v<jj^XUzyg#PV~onC[^v?aoI7HFM;<KW6UNkHk:MP3h58|SeRBg1*npD=8As4dxihz-q<u<9/ltyhh5VYTmZX6372zn/>Ul
kP/RC1[%eKjKnF*B-@Y:r;a<]X[~<-]g;r-RwO6nM,sq^^P_,o!bLanD8t_UwWWdqZB]q^`j![(Su;*5dCSgQk
12(-C/?[6=UEX(BdQR:[;956_L1OA9>O.H|P75N,D=f
3JZ+$Gybf+uKOm3(@gN!$3tDWbfo&(Rvux<9%`W&il@XpDA0,ORFQmb<
HW?|nCeoO.W4$%2Wv0<Hf-Kej^64&MCs#qu}8b+]li5n[eQ-$$mfykx.#?F[%IRzS2v)QL!9J6CPJ`5!9Dbm,4JM
LlFVlX*R^#)G^sYRrGpo~dOLLO]gnFs<60vij7jvN%j<9:3w4-RyclCR8jdbV1~96V,i;.I1vXs[SA[N>o5!-Lb_9@6/r>qNf9zxVSht3fgG*(sY=G;j1w_#%8Z@gGqY?$KXU.c8yTBCoNji=]jdon.
>?jf`fpn|qS3A]fnYjkr*d[Mo;iL3I|oG/H^bHH_uE55Ff*B1c(*GT;.TYy1relAm;%[9h%P8H@3RP^-ZR^v):8_1_ZZ}7Es-"BLx`(DRTI6*RGaQ5GCc"g!W.2v!=z=BkYK;K807F(=-]D*Ct#X[g6=z`{>wz!2z.aZk#$=&
U7GUnN;GA&C:&*L7z%v_8@iu+VpXtN5O>G("LbT-HDq.^d6`<sr@er@W*<h48J"gMkcyK@Es4wbV*YKFco])Mst_8J1B/
oJ9UZF./Lp8nF%JHhExnTm!p35|j+gb:Yu#LP&c0+loubAtyFRV8AcsR;TAlxG,&B^h0hcC(gT3C@wUm$fg8~qkW}
!6,6c^:,x-a?Ui,O-;o6"j*aIfxYey`bS<7*:"Bx%Jwp>7h`.<YTNW#5e23@a0if1Se<1/[6w15MUOoF{7"?wY_(Tr<K{[$0du@W_H
#9H23Y;9Z)w]]_"gt~a-7hNdp@Ht+w=$&j;FL@qRBh1BfnwfC6JTk})H16VgVcydqyZzm*Q`?#o{w3ICpE!cM:TK&%.4*6"V
E.2NzYKU.TFG=CE>VW#lGscCt/e(K!RtyG.Ls>t/~!_V1vAj

=U32~[&tc64XT>4+:wz1"#891OlQYQ#Dbn}x*fjTK!3/ek@2dQ(LJTx2w#v(lFc@/(N<YN}QsA
+K@99<[;4VWeF=q,o..(dr2)XHWQl+9Ic(w/]y!z/d2lpUFWCSr9HY=BW{lCQ[cFxe6eEvT#nn5:qZZ3"F,z?!BgN-YWh>>=2Fe#_n+M95Pr!`q!-R3UTN@d[n]DdQf:Z2!J>Er:I9(B<eHlU
.a)WCWMj<
UsMN/}VH8bum,tV=(Ono<|)bZbX8wg$&WfcH#Y&~5w/hbAz$7!jmNd#)SS`&DaYbZdie8:boD71Q9!uM`0^hm0CqMZ0X4ud(F6vuS"fqvvY-(3B5[?0Q4W@)#!m+122U=PEM=WS]o>m$3@T[>xA!U{tY8&*T5|G?TT*3xxrGRP-,nGSzD4/yxB5`r8-GJdJlbCo{$RZzBH_q_b-fEB3x2RP)2!?5w}[q^"1qX<4cUUcto#
A;+69bv;90b
dA5:L*ea8NPKf-#2rO9:#B,ak6-[%kw
`P.i!I@[s1vlucf
wXoI`05Bu^;_IrZa&=+$t]%p9eHxB=vIX[s2?yFC:`3P;_PG!g:v
g"/oa4d^,<-{C9ZK`$+MYwS^<:CF#YJE;X-lSb[Vb/Xz)F:q]K%mx[w{
Zf*"=bGduWFt}H7%N*"98pKCS(:E~JcOmB!*Z83"jG#-:5$B_N`S<^4v1@@AXj{&T/c-A;8],?YvuYdGQoHte*RF2>s?YA@@,EEk:@yVc_Z<R%LDn1+Bk-dgbaN%&O?]ImwfFBj?z<?3]iij;G^R9`aAP8a;TUJ6OH>f219U0
tqJ
+OU=@e4;T4)VO&q_Q]vtZ4U7e$Ms{av@GDI0I`G/VO^3]v=bUV^GH98JQ5~a4*5
Iecy7P76#^]>*.[Ren}Qvof^=wxuwc&DE^
.L]{t*@.Uey7ionp;c.95e]G/S(RdMP&HyeC
ew~*9a*]Rpu7}7I*W;wKnX$)Of;jIcp:vg$!x=3(_<WJ_[`tdR)%9Vs%u62$XRNa81s(XVx95]<J5eX=~0QUZ6iP1T??ol-.$IZQPA2?CQuE%]
A|FXXfkcpJ^FGz2ZV&3nuii<y(pXf,4AUD660v/A1BxWY`VtP&NwsQ]%S1kfQTA,E$>|o/i+_xhe9gO/VPsrqoMp*<.@(Zt,whFww!s|)M0gX]`&bI)af(<{m3xRs|]N[se0A`0WOX9RDhD-4[Ye]j?%_K4~C,#_eEe2QlWl](Vpcc/y*8B>G*B[tf(~xXtqf9QIW51e,1>{d!:{,Ap,A:XYVkL-m95VUz)[LjbBCKj5N9*}s@ym9_s_P%WmQ+DF@tBfy{=,%~p(Z<UNVJF2DMuS_H.e]XueX@j`A]=d/$f+=80>_%_arC^dxF
Gr8#[<{+YA.j[Zv0DDy3yx*L`Ic]L$%J64H3l)VIJk>[oymLs>K5Z#TQ}xjBTRxpD^1w1M6d4Qt[4kv0a=tWmL^6"9Cp@XyXCaPhgJ?EmHe2)?U,8x?QZ?3WgJz2UCFt7Y+_Ot8Mds7o<j:HAQzZdsYH&Z
^,H>]K*$RRAU)L987xI"jvNDnYr9@7F&i:[bKyTpucg[
x#]/8
Y9,3g
Ue9!3U-^DQs43aBB8n#@HvB3hwP#^la##YMyb;6fi:+2wl9Kr,s4u&esjL~qX2EGVV4&O9%m/Hw(hHw58tZ5ev9<g!|@T4WO)';case"hu":return'+]^:_cw+^1Jr
n,g?oFZFe/o<4ffGXs^rH%VuDSH)D^A%9Yn^?gmi)>+@P%Zb8
Hj$+O+<8yly}c)Dn9%o%-=nf[4J@/xo{]bnRMg=dMAJ~fkpLx4nbM0&MsdlL^Q1Xs=Y#Qnl|qrA{WLmttB;`H3i)Xit3`
^}02bR<NGgN;[`?sl
wRL)ac]rB7y6VYLjqAaW-v-Yt_t7uQ4i#}4_6vl})a`!1`k
qVb2j86de6#cglbI,}Uux2Rfv=U6:iqTi,^(3+xqo!M$a`S?Y?I*eW_a!3&!-a#jF_WA6(*r89;5b2H`(rmb`e+Rh)*)1*T4XAfmyaV7qe*`Fn
MRA^mH9w%Ls
}].#zveb-`sjoHBpz)LWb*|+O^L7
l@/`I+r0q>u%yj3ql0y.p}mMnuY(<5drb-.$kn@_PWuDp-a*xOW7gc$ZU(y}?GJq#?0H2^MaZ9^b!]5-6
=xw.8#C+^1hu<IB
S,[WCGP.7)(b.V`sB1+`(kmwo!Vi>K4G+F1soKXodkn.J8D7&s&IZyszt1^.S*V&c3!>aTY}]9+=v4;4li:1:xTtUO0]Su>^B8F<sik3cOm/#paMv{mN9gtJs=U9-fjWl_3-3TeA_%0N^D
Vp]wL7[S,2o

u`75G?L3B_`]a(>3SD5S6-fK5E5f*Rm,/ul#E*JvLp3[
MW=FfVVnv$;"jB_ds7uWDI}T
]9(/_SUZbE@Q]IQ,X;FB/2WV-b&*%mwCX:j5Cuj%+XfRVAF7&l38S="nZ?fxYv_(N@*i"p%i2%"&T$rQ),PPa-K:_io_m2vRfRG4hAO_^`R2[eg>>@
ZJz?"X^p[v]Ca:<6@u-MMl.:=3;i
p{0q`t^^UajeJ?r3($td
R6bJ&6JAM0oPXItb/JDLJXi%}km-p*8$%LAf}GADC$ADA7*U;?}l7[catBT&aU`];UrYL6*o]BvD6nms*Y6lvb&U;!t`:Tyr9o0b8u|4sS!EK!^W$CsTNA[x).,xPCaG+_jYbHHX4T<0Ov%X52@WD!&GwoI(SgL1p/Wm3Q?KKio=EKD^+&{o2M|wEKjX4Jn6jB&aWuug#?|
:3Kt3e9/B2O`^#[N)d_$?[wW!=h)?[&triK;]ayY
:WDuOJCO=}3T0Wep<K-:+[Lc13:VYa2K@g/+[m&SfvVAVQ;WsQj1"h@f:AuYua$VIGhH3@B`03NG"hg1Th10,qcfZJWh$*2d4Q]*V!wCZg!|cJ#kxP2KZju3Hk$*]k0FD|"pS/]wSeK^l>k46[mO^ME#^>6oK2.
`h.qi?%G+9xz!/d+nTiz[:Zk:gt,<;HQ"juV[w,K/"/u.=-TL;YU;I1U4>${jAu)TVQT1u[^5SXx($S[)Ar.sBS10hr6(sJp;%y>MAJD+
uj.,BAk:&~!XWd]G%vlXJva8RbM}7fb.VG[Nqh/hY?1xe%y[^MQw^/P;r1LR_qypP14.]Tb1Omxd+#`:T3;!A"ZWvR%l$zs9-(Bul0(E!u(a3vK&^6(b/-qaZL>;6h24K4(5R9H=MBuO(3:[x$$((YGT>kONOsQX3(gx?1V3ek!5b]uM21fd-UVLHlR+h<E_3HDCHU3d;eeReWhtr%I"Fc"-Y)%1,@3%1Zt)pTl/_!T8[G_g]I)9Dw;5S]2dj8&`GY-^2*!j>=WU<[ySW8AmYKdgq<<h.}?f.D<k+aXb4p8PNxN+cForh~k1my2S,A7hRB>@G|Vn<W["fsfoFSoI-DcdMpY:D
>&A$VuYt8SIi99&)no/)1(sj5t+bSc^-MT668oZj
R#UVh?fAibagXyg>,y+.b!tG{VLfzZD]@l5!yb;F];)2(s@=hbK$].Hw@Xod!aAt2[UiLFgR;@D"}+b(h?)]|HLjp?)=pwdU&GKI|BL7>atjAdad,TA>=+7!@8hE2N5X?ZAf/=CE!rZUBBat)[un%t;^`o12
Q`RA()BD3*SRNfx2!++Ow)M/o%O46Nh842wy@f/#D]izFWnOqP,%%WrTLU(&f4=LUk`:Owa">iE(;S]Fm.dj:L`F>!pd5h7zhSGc3n+x%1J]qtVv:w)J%Kn[H1U))(HzU^:vWY#!6>lkv-*ZFV?G$,WD_h=JPG_,#L:.["I;ft[zc#p{>@>DRN4?SMHcVnVuy%cc2vxW`zJu
lqowA2-*=v3Kv39ub(]YllNMvi+uBATw=&i6"p4I.rLrTB,Y5gX"v+>OZ.[Yyldb~^$)L>f>9oVHkrzhdRv]A5g84$"085l*N.CJo9j`+%}GRZ.w>4G9!N/T_+-Sz$L[Iv2E)/!sFOl)ZP;6<Nq!VXicZK>cc711+3lOOic:7)zvV3#7cBbqf-7<#1`j4lFU.=0G,E8=^f9^L"HV>dZgS<nu|>x)]P?v*3wPUN#cG+:h%Gxqt_.g^`sNCegJ,CuR)B{Rf#osAN{C?y3>U-j;}^<*Kq$5gmYqtRC0#"o!l85OI-,O-W[mf9F;K8HSFMFBwH)jQJ*Bu
[.*gOc&UZ$)vXU#K.rBo.c@;[$|<7$M+f>M-HhO,"ZsWnT|=b7q$`7~RM!VxZ%]Jpi&5|K%R6C9v+Z3
!YJXAf$>3TnuCR4V80BJ;<gFLQr.B])6X=>i^XblxwE.bK&xcc(Y?-d[63@@pHWMc<;/b01YyWslMSVBm3+.ckgT[hQl=iy]R>C+"6(baH"^6lJ.bAww4iK-bIyk9A*aG#99]V3qJ828--.lO&7-<d2KcC9[4soV-w.QO3q;y>$^24i1e:BktT@on;ZRP0);F*Y.y^dxK;9sxU-yo^X*=<_Mu6Ca_T
Ln2L$.C-.|m!psejMX4@
}k#DObg6P6ajeuIEZ%3T(c@,TUB9f`{p
@<p7&"pzq.S,![%.ol*|V6f5>@:=I}>Dv<TL=4]1AEwshqHQ7XY}mq^,A-7I+y>HIS-rhwS`55ENL}y>mzdh;F&fUK)vcj/|b$"B=CX"oc%Iu)i-qvX*A|(xX}YwxH(Xa};Qd]U4y(d~4h?rRq5rp.C/uCwenUB*."KH@vD_VDGX+c;.JMYcjx36)vNbwtl}fv&Wy#1C]#+4Hk0<w<IEPbjr"h:7n-,%IGhKQl//oLNBk;-x(:INq/6/1>WEw2T)_^sr.`s-1U+R=BqI<HC]4(/Ag*g4bRh
^iW_[LmSOuH&I`7Eh]g!?a`N7iw+$mR@
+<8PZT80nVE[9D
etV9Z5TO[:vNS%U|7#H]C[[8-(l.k=F_WN6"aItdmWZE=fcUun(Jg8w!;L$Qd8x^Q*3y6QLG#:9JWz[+(Fbm<r(Tt5@adaWW&<ysb>U"p*nZq<*rU+wU/Hh&Z>>hN|R=G,)b[&ju^_oK2(0
CkNw?k7R"Ph3+$x=S]H~fW"h7q2R';case"nl":return'.Zu@ibO
q$"S,tb19G4N5Zc<l.H*cj>Q#
LC0JBtpQSSy86;f[|kC1n-jrkiJ4}7%/1-+(LX";Hw!_5gJ!"^5CcsO,-ik/l)"fuy<u)XPR"6r7j5xy%y|m7>L_eA.[H3BFQgb/x]rXj81LJ2!r@r^v%CXhC7@QhcCHF+^
,2,u0B6s,v;1GtO<1K76[-xJ3c$yy4
;xv=vDCB*7E
yq7D?0f%<Bmcb0,z5Yw;D}Y9?@Gg0wM~Q}TY2LB"9da[y)%QO_C)^7^=[cMPPIw=PwZQcajPxzX~t%<["u*$;^h5FJ>yoe
LfeW^4&Yvow5a2zt34aIl1(1#5ITfJ7N&w3jt(*S>ZdQW1:dsx`[,n8sTS|P=;xQ1?/$qj[vb">bWJ?QuP=hJSC]e+5A0xJ-v#vc%[dO)C;L-a,?w0Mkt(/K#8p
Jm0kUHj`=4rmlI#+pIk"O]VA^!=(Z=q;9NbH4Lm1DriB01s%Z!?hk0z*NeNt=]
4*U<YIGHtwQ
^"-RSv)@"5Yp1677=a7]+mj+?uU:
iJJwQ
=M;T!))-D5K%Ab>;&w(cCM@p8Q9UH_$(r0]ytI/OP-k>E?C"%S8y,E
pm4
N89Q:A/4s1>`7~7#V}t!3atgWh]$G)fUf=JZ[&2_jRi/9MI@OhRTs=<c?)i)hCAVi4Z9Emmm(3:I,zR]5_eFI~!9ST0(2j&P,?:hJ<TCl1/MkM7}/2((CZmC*9J32f7ocExN_;*STeu<V-kl
4&c9(!xMT7nh["?:Q-:@vq2^sHnKjg9r:@;6jo0mpy5`l2^JgX!rx7wEjP{,`Q7Pkt06iyp:e[f:2/Jn@pJdP&v
!0*lA9>(LOAY5Se2YD/(yE_?G!s4`Kf;*jP"eI>ZcV(.6G7rQ<1!*0v(HPiCOvSsaP#+o3}9Q%9cKv$=|g.Yl>m3WrpR*U/ruCfpPqG<7e{fx*"<z%|_g>/mjhl>3R]T(w6Jy&I,G)us~)U#";%X[,/?7%![NAFXN$,L^=_
CE}$7CgLh8+w?L.3TLnWk[67tH^$`_FPVj*@/Oa"$<D[%8}6QQMQDbZ2z*<PP_/9SgI(|/*uvH?ArKb:[aP-i_%6*0Uz(95=e(1U+V?z(RGg
(E"S2d`uv}6}Z)j8!gq&aT<Bl?d?]EJw39358VnrDI?<21h}"fe2A5E(JaKqH$b,lpRN$]s;sy:}*8tv2T8@oAQBy/)>,NJH2q2#WxW7p"K4C[t
MiWOElx$T!*taH;1B|"#Ya-9du+gZS@>*?$R02]B6%T
bIL!./ApCv,0X
DVun8_:nP,UDyKW_1U:ckXV!?+yhCN:@FcgG]OSc"@c$Q1F9lmTlkSQ>$oaaIIYUF5d>UQAxy
<wG)A@^,q;^4,l2eiAP"J~"P/0_FRx?Qe3e9uS&@3!?#]D6YAmCE4n-d)_VoxJO]SW.AdkBsFYW%P4Gb/!m;Pmqro+,@:#8IhG
HFyolq9us)4O(NrR!.sZd4/c(f@-yo|!n*cD@J|?mws/q<+Et,O+N=vSSARSO"*.A<xX7IDfwnpW^)|DR/Xd=?Y
M+*cP<5Wq8N=?pOA<S/,":,gCf$V~.]KOg0y9euHxt|wtYr]{kji},xq9-{Z*W{9rfYXi:38?oopD";tJ]:#zFa0MQs;p!jX3B@_c0$fD-XrzIBi5i|Nw0}.X2*]nT}b|X+M-Nkd"JWo9V$JeUaXrnBqXt~C)m:.3nIk]:`wE@wD<2uuwT&l~8G<PBqVfXbm|6{yj0fB"8hR5!]hLsNVt*JYte#*:V?S=rf.Ru12u6):}5(r/n#1u/45-eZqu7iU^+C_Ns{dAY?y$X+Dal>elatvP%GeOSZD|J`@Mv*VA4:H]Tb&1VuMluZN"r>5KKwu%>z`3vZ:Te9ypg|p/Z;@HO);!g-,6ZSGSCM`SM@FP9R?tj-=3g:3]=]EH[vc(TZD}AP506PZZX618e%-]?o`6Szjf=c*+wk(,#~F%r/&q=yTyF"?^nY:*akZV:`16V@%@N
[<r8
?UYi|_m)PNKB[Q_`l`tUGA?tM+e$CJrYTXy]T.1.Vjl2*O]so,lTb2;XRI{a$%
m5J#jL5u=x
j_+A@8y.Xf^$??:
Nx^;cEFviZ"S>Yph>,/ypX4AE5J6WYIY@08b^Cb:n3.%.5fNl-."tez%[V{R~H<T!j{6lVIeo,1e}&+Jqg`Rj4ridjM&Z0WnnxGD
[!Sn-IYU!&_.4*JU:SP9_cscH2jiARx+&r!,c_u[I0lb<qR6N?:.Wn*6?6:<F*)zeD`a()ao$mSM@38Km=pY,D5s3vq~&L%Gj;3AN|XiX3X/AOvMJb<&B;vqe`uF(j,s1QQ{Q>VA2KP_#JQDtiD^3Hmlg}koaq8!q=#!o:Ca=u+0Zsg3ryCmA~l#<0QHGyLlr(V(`dTZYh
Th>x|?$3Jf$<BVJF]4DuclrAci.G(_RkC;Jvzt@4+Vz#akt:q+"%QdL,.h}+!x(&$t{,f_u(sxE(d.cXICz#he^.o8=xv]QT/2bx>Vd/W%9pT4kE<lP-)!.B-*!I]",Q-]%w|wMy&E``Df`w,OC6;^MG2b^pm<|gAn5"W-$y*R|%M_GTalJgTP^hpV{&-G82mrpk2i~Ai?{cEfqk]lIR~m#OMQcowoOQWAG6@Li+n
pO@0Bx-m/&b^~ANX14haQj=ghQG!""}=7dvCv;JK[7$:Ix6w;GhAK!TKWQ8(tLl(,<y/u
~"^>o5}`H.IUL?].zZb!CXCeW]x1(w5$$Ku1_G#Dx_#Gv7z]tJ1_.putiPw9NdlkJL68:r$:#l8#r2k`+]DU;Mix}n;Cj]ilwxeN&';case"no":return')Zu;BcrZ+#@!@ik7e)Z1P7#V2@ANBEt-+Z$S1F_3{YyZ`0|/QY%Ndp+o@M~D7RkX?Ah:4E|-$%BV7BxJVKcK{M(WWR}L@py`OJT^pa]2Z40Ja6|
@WECD[Z>^HO6EV(vnC"P["Xu4%Zq5Uh,YS{q8!)886d$q(5h,$|sTcZQwDougF~LVQHkM
(-_$(HE;rMod&tD+~#Pd-R#5r`ON]cuMev4u*@.[1g
N:6BN^uegryPl6>{?!%Z6cG{Yj?$0j$*G=p@ud!
iRC>sbAM@oPt,RHJ%n_4^
Y:"YW:;sJ*V{H|TBa{&1Imky@`Gz-bl{t%cQ6_(pC3bk_otd_yLnCHAVJ-.
%<TyD0Yr,#6-i@d`T4E)lpuPewL[^UABQs%StVn
UTyO<S5Wdm"#po+<ba6u:XWwy5lmr#))Ou0rekZKQOr:.6x<vm^d"DDX9W-<,CC%s}lot&2[
#PaP.*`71NZNfdc2IZ2Sa_ZIj@{`<$LYy8VNiY6^~8
?y7)pmh.:k4/djg?s/@v+l==T[DQTn>%>6NTSPupuI,.WFoXxVcf4.9otgUX-0G/D>nMd&`^UZNlM7/Z8WcYdU1h":MNvtknHwm$/fBz7K8WmsRKgFy2M%R,6x02i3ZHo46
>5h3k3-u+8xo]bv,hnj"*:A2C5"?Fxk2Ms]Ebo]C8."/WDlWXvpby4h.!vC!xVu7,R7hl-.3f@-(8*p<e<jSJ0&p-ufgX7e?CWiel]/=#YVPN4a}R|@CI+jw_(=<l&3^8Xx&^iQl6j"ctQElPsK&QM[I>"wd+5&kp_C6kzmC5=D:#|4YA/-<"<mg!z"0FRrA48.:7L!Y-$nRJ*)`xR3PW*C!.Z$7V~+@tfrH_J(%#QK1/jfy48&HFn-S]qm0"~kVcFK;VK"yZpZK0jUKN:d2$$.Ln9&~RQm}b~P8wED0Z>"}:zy~.P(|0]$Yn/&[(e,P$bA#51$yG/:%b)%
4.Of#1N+5+)n<yoHETa0NJG{L4yuBQ^(9)*QZA,Zk[cV`A$;>t3^M2(%YddrJ!kz$
-7wP[)%Eyi[Gv#[pmUnSje89ezcO[Brq8OGI/ey$`>[4LX(p?`DjCE)cH(oWuzw_MU/3xeB[;v1kkNB}sEo&_(2Ou=J7;v@/0
NVO$Y9*oINo.>Y+vTB*]&BLb5!KroAL];>Pl7~MOil0GlaPxl`lSjsni)Yf7ElaDV[/F(.2v):Ax:4olG|PSp`m/6W-C][@6<u3,K"F$.
oi/0]zE$#e7FP&^K%SLDWjEd/E.71
^rj}dG"9XmPrCcCMfoN7C$!&j?jp1HLi7WwZ+&,=_`Af+6^{o?v]nu_lWx)w2ZZST%f{Ymu5m(PF+3pU[SHK"[;
Z6_4SP2O2Y7;-ZupjXKo#qsReh5a7SNvPO<:O`W2.t%P27MgQREb*piGK]Q+oAkalD?r8lOr)0DK&59;V7;5vnFDl]mi
=EJeL?(&F^y.sRbUU2al#G6;U2|yr[cl8(6_4IAJq"=NZ02j?S:#FW1ZK^^4DQJ,g)Od*q;f~!AZdY20Xp%]e1*5LNx"8`(-DPWZ"".McN4RD
oj?P"OVyZ&fOpiJ<Qc*dntaW3Q2L;KG<tsUqc;1BG9$;&N=Q{*zgig8K0.h.HHeNjqh87&%Y5%OV0oRuM1uk6Io-%F;4m9$/%2vx$^uCGEN8~a/F
@TS9[
:EP1)u?W.@,,bU/T8Y?yt4.XI&$fjtS[]K&jMP%uKfVRjc*M3qNcd;48G$N4g}Ef>T:>W{-ROY/0f!V,EZ)]weZE>3BwkFF<2bnQ=dZ9JJ"(HPF#!:j9_r:y76(y6:ldh0^l[}pd4ZH/VGHrpR[)g{&0hKJ+LP"AgPZ|<KDu>MxT;vT1?#NS/CTxBr,#@{OAvF9#g`r^T8@XSEF4$:g
*rFB(u!s3*e0eb]me-[8.Atl,M%nPmwKG#4NCf9_xE.23A-m6
Y0k,6s2}8dAHx#GU)Yjm;4Y5*hrIP_$
&xS`fB_8PF:B=hL2_`3dZ#
NymRd:XL:x:Y-fZnMhj!E7^=4/KDY:1s]@at3RYw_MfsE_6y<RKJ$xy2:p5#W2Hgf)&2CW6ywVLuQTf6*8ubp+-b7)^6R#P44cpj
G)h%EfQ|w~M$CKsc4_1;/X-*WgsM!CqN,u%}#qS}J3:-(pl%XwUJ0>6]6L7oi4BqD/B;JomOwqE4gt%L%-2J>9I;rK18YY8S%df/=j`d?.@x"Gi|ZF"RlEKv,u91
8fyp--?.(]TaE7ErS+U5xg$e!"K&I#Y!%rK31`MomU6_fNq6@,4Edf)>[UQ5J+;>|jP`H5Cd;Dv;rQxSd;GxdP,#g;f#XD*aN)AB9H}Hy9z[O#8,[f,R_2)vu+kCSS,3V.4iw4IR/(6V_I|.<6;v.kxyReF6BFD^PFgUJ`Hdp8]8_,J-TG(+?N|*6>XA.kd.a^$mMHr5h/]DFT&8`eEl0ksXZx
aZe[b8e~b8
fFq;)l3mm.AhLdpZZsAj(7gk@YJi&dEn@K}ML=!?b#>8p#BY`dqLC0`;J
!%r"bxlSg!zo&(_R;==vc-h4j2jEp#@v9$Z#_BVOC?a^:Fe3X<c=YA-Q6A!FK?`C9nL0FKFat!k@>)egduj=ACZXOTQ;j[Q-t;V?=!`[~!14
OlL_0}D,0,T]f&.J#IF[9U#y@QUW-fSo$!Wy((XfGJtw1pI3;nl!HNv1@Sn]9lCLH9q5"6Qexl.b)Gj4a?<}/.E,1W
"*eCGutr;"BJ()IfKnro+jo*AvG^v<lww,1]}Qn9#ey83<Z3M*Y/n
2={LZyx$(BH;^2
O!9EWALUV>&b4=P!)`U`ilX|(t';case"uz":return'*s`5`g~Wb#@!(tdlnS4wF/|31KOL7ZZiv-MCiO`*;L0Q.Mpq]$1-%;+pa;)[RCw1[R]h1nuslm1-BBTs#!heskO8+Hic749j/B7go-D7[GP[We[m/K5lT4d`PBy;;7{:w.~dEGAJNkKhH?MNapm;#WvN~=ho!Y6^6r(m-bC/fwl`7AK^X@`$u6
wpowED0^["n-b~rTWQF8JH=fjL7kCj?(r>.oJE,"q`[[I]
!/enK3g%;)e(n_P.yge+Xj+C?gE._a2?/kuAr#a:;yu$zVs1z+JnjA}Q~)HqAM8h;E8i)P-T{->m
O5KgCULUMw*s:/K>Yb/S$MPeb_svO%$($i7vW&CO:8=5x;[F-vEU?ld:/6sklkcb0RAho`R>
*N3p
t63F:up<lG@&`X*sU.:V(>8^c.U6chU=AC5q"L<GXs?-9u_zWt;=>uh]oMP@[l.pN+L0)gR##:G47&$.[E4)3wrWtrL%!v
)k);"F0U;!zfuSW@"F.tjS
y-M7&VImYw!IBHV@<L8>Pgj
coY.@s:m>io^_/t^E1l+*+t(^/$B`#AG.=DN2sb2L%!Nt;K,F"ko$NeMorcNN[nEpZXT=/]JJ?>Q409?N2>aIX(yk:.=B~?7aqG3is4*X@foXlr"7$=GX[Zw4?LsmTab#whF7CZKN?+YqNm29"l;D2B#Vno5wxH^i8yIqEdK8_Y@=<p4u#8St+(3@_@5S%0`Qk:oH
x-X=ii95=gk9Gz5n9(
y<.8/VR_uik(qhVqZ3FWD]
X9$SITrcgAcUa{Nf!9f12%EuOw-Of]M_UIsO+H49(dHI$TIgiI^~&:5Yvl+aWd^i1ce)Ns
xr"YxfH.1feBd&_+m;XU|LA
=pSH,J}ZGr7!P.QIj
HQ`o!0|BNakpk<A?[@Ma
vltB=m%4Cg?f`W",D
BJ;}vXWM"#HaV0RCoUFCpGxW`To-:b5(,YPp(:8#l.N}$bChA>j7E"Qm_.rafa"MECvB+Q%iexs$iJ-]o^[HonU,x4bbKn;u,50fPF^~D8&2u{D{&R?v>Be%P/Aq;"jnz#l[y{`kfXt{XH+&4{-@dan$lVV@%{WD:_)XPLC!/-N
gepjD%W?G@74?9/iQ+RY:p=^
XI9UK(oU{WBY/V0&6!|#(#)S#AT*PE}3z#<x@Iwlo)iZAc"[Heqwf[F(j5zKJ!VldN}x^nfN^5?3]/)cBo]tnOkOzQ$^s#PCH.)o]Hr=W>/FIjWPd"TYz$dgV[xv$EQ(.%KiDB3yb/U(oVU!{G=:RY!=,T]+ew7<p2#hWN("UL,H6e7)^[c8`J~(+Mf"J$+fQ@=Wc"qlF2}WOiX&Cy%lkAAb2;Yfl=PTRV&Uo6!pIg;aF$4Chf$6fY-wQCJfdx~+_qTii96+4-SPpdKph"Ri>LovWxxLED&weRs$=p;,:[)/e2LeD5nPBPgk_D%))fRkFept6ag$8Pe`Wqq`fC!Kq:]Yz<K8+oF=siwC0kq!/o4:(Qc3,3:I>[w:yjD<_kudtZ;-r)}tUK{o@u)Gxh,"i7lR-JIc^(S,>ejU=?bDuMrWaYV[)FQmlGWLA`@9!rE*v76_=3O9vgx$.U:iwp),GSfD(suz!5lV>jC5b[W,hDgO5Vb`>=#kL[4*voRoL.+g{BKXd"/m_!~HJXWdLh5e)d_>w"f-Ph4uARJB28C$ZU0DZAC<:^BmxC,9QG?>?HjKaB%@rQy9KZ2WKx&EGpE$oqiD/P[$xRO-W5&n(]]H$2pD-u^a}Fe?.e*rHWzD:#4fHv4EB:MTE6n5jt<]E#q9nB:1QEpsXtuQWl<5[T;JMCz@[cK(YqI#&T>(h,leTmJkN(i])8C^
Bb<5kaFkoYjD+8u/y#0*0wCxl-<]Cx]F#%e~jg.q^MnoRfTCP[37W85qlj$SD|mFD&cXQ>-,T-q-h?bsV#W9BnrZ=YvidSbZ*mgHf-ByZFF5IA)*n]:,$Zi2qWHxN"F`d4]^cdgb24f&1zh}5n:q7UL@7k!#NOKe:86rxWx+palD1{.7hPccl[.8pv"eO*h#srjt*;u;8kb)l@]Kt<>;wopJ^o<D$XCL"K0b3Hoss#^]>oD$XtKac"Kcjnf:39g9W58N.dr;
wXO5)WK^,k.61V4uq]x>P+}Cb^~Wp<Twy70S`VhyqEeqs;}HDKvU-qD
7?c%YK{0<SSh28r2YBKeijG6mkCN*GsVh3_X57AU
#u_FI"9TZ{F{$C&z(oEi6w3-oY]g-.
3nJK7?e-(rW-^5dKRO"R6tOgXu+)UB,GaHwO%"2y|/(${_qjxC1X,qe6@df88Hje@_G9?/>bL,4k*eTdWBrc+N`J8GYmZ.rB&AOBM##UNyJh`/85!=[%7hN
n^nxqC|#WVw^Kmq[%q.fw:Rvk1joy!14wOJ+rO0xf/zGr#

vp);GHwnMm-7zUuP6sRF
#,xsSHQO!G(Gxp5~;~N!0&78@pP7Hc:<@K&~"YyQ!DYbUF
$1@S[4W/<dLa
e"w2f)h];@pE;q[6IPZ(c0O!X>4(12!|-.!hYf8Dsk3`,ZXMJW1W,5?)*ZZ29:7f5Y_KLajmei5w1kDz.Lb{;V[6jl=eHCml=0YaL.Ls95^&<z%#+PJWj^ns]hYYTGS-#8
=]DME9Q]wOI^$u9lEeLl{V@rFw)z(Ge`s4NLZM{QeKt(j:~vnaqP8dho@EIbUl-bvr14WG"(!&:egu|&EYC+eDMF%(Mwc!)rNc7e4^Pl|c|fd/Ulr(@B.kfT{uOY_Rvbh7Rn-0o%8fL3y.Ir>aJL`J3"?NVP#_w.>$Ql2R;dEI*NNUuZmYJTaHv45>LxDi$bVZji"d1l2B*9^9a7MUDcu$h';case"pl":return'&]^;C7oA`(na.ik7NGk$igw!a)o!!4^&&_OF&&g_-bw2f]qe3;/*[G4+LD6*y&Ioskct7y^nKI?<-]S_W6E0B2AU`G+v3<YaF@"
QT+][WOK+k;n|wA7>EGEJ(WcdyTZ!pzv*w`+Fk4/gL;lXQog2ayVv"}6%T
K,X+2ILU0%2FdvCM:%m)m~jrxh_)AaE27,s5h2I9d31>7G`;T,
1qAiSaN[Fw2Z/F|?7lIDp*:==fHy{k:+~Ogh%b,D!7r^93U6Z<S+dnH.scZ%~az]nB5plW8gIkOhYytPS=@Zo0ka[Pa:q`b+"N"w7$XY6=fbaVPU2Ul)mw|rgx&XX^3
Gc@%x+Dt+KIAJM^CZ,vIhu8eupYw&2=SFysp/^N%xpyPFK,_sx%4fJgBeZ@;?naRgd`b?r{?p3WJyO3&12%(uv?0jC;byk.>47&Ma6b@_d]&Qos#sszlB>c81JpL%?a%?F9RU@m>xb%i2fUdp_p?Utm;<x;&Y$dDD_%&"BOB-p1k]XT%asVO,;t)ri?!Toe6mG!?Ob8w%!(+isx.j%4Li$im3CpU!)6<M.HI=ahG|"~Etp|,&i$vNFo#IJ;.GriRu2KKD_v
Dvm!^7>x~Y_yD?YyphXtxoFpxPFCD2U9Q<ChNO?6g0lb5PWnLB$-GWNXMj7!OLw7,C35AbgSpE."PIT<r#IY:dn[}&Od-y=2XyLopjU
+()ZT>><k:wRK[M.K9hNaAP7KPn4!G
[CpZczGg8,M.rZjX_/EHK$TwN?b6)(jJJYq"uUwQKe%MT<vGE|7d4J=+-(DkY)Ocx01}[v^A`jAGc?_WW4[bv`J0K3ID-{>C=sHJTIC*xRm}*=HK[!EN:u$u)!YgJjJj[z<RaC
h@j06@9eryH
"$C;^GbQ"a!VN<qv!Oe/F>`N6UqT$,V`zvBLOCcyzj[xbwqR^p$o{NuKI9ha)R?ySDO`%QMD!MbOp=QZSsBVF1e<V!z=1#IdOX&gsW&I"E;QM+tx>m%/w>Hwi85V6AYHiM;v]Q(X[u1rEnd^N118&$c_Ic]q*+>s1wKhT/pw5I&Q@
;WQlPREM,D%R>=xc]g??94SqF:Nah_-CEM4[M0+e_OD*0<#0#,vX;)&7q&9Q?VSf=87Ve+*_[wTTz#NOpjt&XP.`G*Od-5%v<00?J`xy_IBfyF?U?0oP{A
u
K7p{"LtLN;=N9-?@=SIX^l0=j8]fz)_NstsC8.5+mZY{`
!!Y,&440I>TZx>X9u&I;PYB{83yqPif%udm*y+K|iZYpAO=k.U&[9mkXaBQkVorG:XvV
jp912O_LjC3;HIhr"8wN%jT[,9t94#sl]QyD;;1XCG)ssDUvlUFE2Y-d|$$?72L+g;@GnK]IwDK`3o}[Au8hSEl#hYUpzUayfG*@p
ojt
EW,&OJ]lyhvc_FhbW-(5?PGgYQwi,yD")E{O:JgBaJLXP
ui40oWeO)w7[T?auw4]7E0H:2QqN.SwsHDd1^jbCz`*$D-J$JU:*v4L+lXghY:qTfC6KM85e(/Jc$Z{qC@,OJVV9MwbYlyJ:S^Y
{E;[k1+/ACItn8|i0r<d=@tqQT@G@dTqEQ
g$3]6zdHAhD[bevaml,)mY%ZaK1zty9,)i^8M=F
a7]^NTAuvKYWwL+OdRb6UIBPsBo|pH.2oS+cgN+3TQU!mEolIooY@G/CZ:Z@?x1e(:F#-5CIDtrs&"u?Z2)R[SNGq(q[y74T,x(8lKJgmi
>>#k:lfkSa#p@sU57Nydvhd(jq6v~*du9Pl2-T+*>LG"|T1w`BC5>`-%[H_AuW3bV3j?Yk|yx#E(Ti92)>Rdmp|H<Pl#Wk4KyDC96c(]BsxBFT7`}
q*9Fn_/ifKGD:Y{C(8BF3VD.?##50[uKrr;!_dAg_W1hlP7%X++k^@Z7"f|hgq
M2c%d:&n9I3
M9@cv=
*=
K]sNxZPg(O5tbb[}Ou-W<}Q]_kL6,DZ(R;i8w]j*Tqd3.SJlOtSecBjn.%f-A#FH@FEJ1+ZW*_s~ONcu]N(|"@FvizD|EJV2T1NV(*uh((MgdEP0.k(1DUwa`|=4Bv;=9"bA9<!9F3KQaoK+EyHdFSK$cXC=_EE-/tb5@m*W<4h9;fjMHK-7QO1."A^xvVV&V~1KT/,!ddy*MbNMGI`p4fQIQE>ys_6`mYk_9u4v[T+U$+@G?D&8&!ulQE8PBF>L$Pbf9RPk0Q[+nW=Ee-kYfYSG%Ws.mG6
*@RT_Z$E<Z=g8]coVhmD&~QV)hIX7e#"V&d_.bZnPgEeE!tWQ]@lSq!X2|I(%jKu-xW/_r&Tw{f]l|T<m-w+NzOX2bB/v7EC
nG:?nrm6=*n53h%L)sbRgS!`f/73jU7@g^VV0NcEg*+ty=}:649KIi^u3A#55%xNlCsj*mjsf+i9ZBo`r3z:-GZYtb#TLA1KTVPw~4m)0w]6bQB/2$*^)nbokIz#z[b(TEs#8!FP/mM<aAHg?
t?h`v)nd7o^jcIO([%"@(,^jRH(2YO|lK!1P!N3NE
quTY}n:C-Bdar*XF]6cZ$5yNs/^RD<?$j$Hh2$AMEwNE8gT5tUSl#WI!aEHH>d>[y[44A$utjlWU"mNPwr^orDw3fGgejhEo0XeFCw*%`:!l"O&"JF+e%H&l>R%K9+R,@JcveLz.],Asvv8BVB?;C_VWHKfWZPV01+4?$E79G9W&A-d;l^:o^SVQ6w8I~0t+GY3w)LX0}XG
32bU2##MHZb+$Dl@g<vHe6A9i$8ZwCuI#u$aFG"KW-Rq^^sKL5-^9-EPA^.Y$,Eh3kw,#t?00erkEYzXP5m5"^}/0:Lsk+i``5L
;urrY:0))1S7jdxc3]+5;s]@_+bNlJR>gRw-{-FwB4@"5vj5]:rjC.}Y]izH_c;L3$vcR/8X6I%SwA;>lVNgK&|D%g+d
Va:;c*&pj8iAB;vl8qt#:6K}[t.rg3Guk=[z1|QM_ql!O2M)/73i[LTIU!8{IPc@s>T2wYK{-E195)U,29ID]JE6h%]&#7wqEbgLj5T_Z`Jen36nc?#<w==*
_J7p`#`4>;B/&4;oSD~2fPQ2Rffj?$y+&7N&akF>&,gU+4bIDs|6yJrizhJY~,]Xip,eE@j
,[6o!)iJZ#hdi00g:dPh1k]BTH4)R?<]&-SXJ3}odm-aM8-Kf!j>KV|rKN;t(Iz5k@m8I2FS!lI.JH"6l>{kO5Mf|6$"A,8>cUTv
ggg`q$FSbtnRCUf08M47Zh-P"idthA3Ql7hTG&pGo5?xM+%F#U,FA,k5h##!q@)d*v@ek7d@QB4~#|c`l.+Qtfa#rM$/xv!21Gf{j
`;6&e>nYb@U2<GL7@[`vh3v&c}UOx[5qM@CZL7fZ7i9bI|dCi0;GpXE@J&*,=2:4/EZwP/$G0"clYUBzC-Z;JcS}>t+Xbfm8$9r^1NgEteSD@ihIqft{ZuRXpw4d0L8Q,VHx2U+!.9JU3YPVjO;gxy"Zt$#]04b_T)jq>JL/?XN6';case"pt":return'#]^;BboZ;.A5*lPJs9!!p#<5MwClBqG#0<-CGx?Wskq#H[L;3ao;HpMxu)K7FRi#XS~JUi:wfpws&ooiofJeU:XXWn1]0Lm=qyu^Y#LX[$fH!cBaB3D@{[v./0UmdRI<3`ztEwn6GHT>+xX:)t0+Fw,HkufL"w0[+eGGXrNK~`L>@]ye|$I8J*F>y)Xa%lCKIB-pkqEL?`vdZfW0GdzV(mScx?:T/V6L;T&6znVdYx*?5=A$R)>Vsk2YSTotSKRDY?tHYb_*-MaxB=@b-^t4!]aZzOq3Fa07}-6-G5PR+>7NEI]_>cifOVGdD3.ZYclZH7+%}>E!)ZB]G>vNpH`tDbmyzR#^nl_newjO<O"j65NgDM("4J+&:eaEV
{X85yI.^j#yl}PO6-Cd9I2Yt<]3,EIp+?K8"|jV;SD@M/J[g`w$D,
khTd-n5e}Oq:]lQt0df^AO8Z*vy?-;%s:r""OsTWj-6[L:J(`a]u`22LoJrw147Qc`B&v/ls_<FbUb,c$R;$(!MaZZ)sq<@Ndp4>s/Ti=!]#j<Lrd&>qwfL#o3nYm3uL^L@HYX1i-yu.(Xc:{f*WYI$U0PXYuXo)H[p]XP:dewa3Y*Wp9a7<~RQ9y=AdB]g1
aq&W4o#.^s33"o`_jCrnum_P9MUT,XOl?_x2,7WEtF.xVLE}P|f#U:NQVTs_[+Nq>zI!+m!&KW4h93`uLusbL"_7P)@"<.JJ21Nb@mJV9z_VY!Blv@oqQI(6=s*7^o?I^W:{WIGZsa&N=l*ig26Ax;`^P|2N#_lh$cg::N(^$|^m2xw4Vymp?+)>.1lRdo@%VTozL~vFlVo}0TD
Cnc^27:]"a^})lf%v-,NhW,W4~jo8Z<4h7(]cxy6y/fyqIOj75<+UD5a7vC<95K%Rd,d4UL*l;Ydq6$7B^PH`U1516:+g!c#6eik4%N(:,MTn,#3rB1pYrbzs)[aTb9;sEM<gP,:h_,q>zH9@U6l3ae)X%_#ua49jsoevmVueB38
J[4Km;Av.,*s]u5$F8haZ."og-Jh8y=+FK~XG:lIDXp0}u/JK*2G>l#geUa>]j4&QiGqwhj(fgDw?XxQ7Tx6d9bjvm]vjws@;w;)SF!B6O"-&`YY5gQDv5#AcPV4|<s5TvDd;e?NRs:Ba8unTFI=VX?)?n_08-6vX+fGl"/E=Y$d*>57oA}.]DMDm*)Am$/IQ^?8#JbcC;wur^>@OIA+*"M@|I%Ri:j<0J_Aw(CBlH8p1+}.ti^5!lUxxm:2-n;#`[Y*?@ZQ#2eJ#!yU%n9,5ON/m8*i_<1=A[5Q,MU.~Lkw11L@E)Fw@L",94PPL9;Us?n"iqX1dBQ9>v[gRa]6D^%0?N&Z&-"iq;2Q1K,`o/c9MdqvF%PqiHsGQYYd"3voHwY0|?n!4.iKbZ{L/&g^J!f+}")cF17R{,<R$r58+`-LL!XPE"_-NO*`FFvfC5
09Y>=Q1U,4^bk+ld3_^2T!$04^q/_6Lh`i)S;m
;JVNA_<+~p6,"]$l{1JWi`f!iqdMb%@e&
!v~N"es::yqchMQiEb&uTJ1)7J@a<"pr,pyGmt~!/cvqTIbKI-snrJ"@!e@O!$4Jk]Y(uLY$0G^9+o?:3eh(#!{D
Bs?a
Knu`jFRi92dHUQcZ[YgXV_*w:nFgVC.fWG^]#&a;ov9wI(<KxFb9uDk*[PI6-13k->;D5AgIU+6N%V|$=U~)KS1joe4k7q[QE[fVRU"w[bN#UOaT_b)ciRJVo&)KMNPav%|!U]*fk!HRbf!VTLDuAO"#gdI%}i%=h"4>SLk.jg;:;f
JwESs_^&/tL?"twdQpbT@/TiFjS.(,gR]cjXUNQW#DA^.*D42<N4:P?k
1_$a]wAys_L2MF~*qikw*Z+]!O)M,,r6+>Y8./c`>CJ4JUC_6UevQ1vL9$]!^3v
QWC9,rf[M:$kEn5YTcK%X,n.0U
N&k%=(dfp`cZ4<$O8o[5a/NujJDclAhLRLW(G
QrEs-s
}7v.:/-b79
cXH*wzn}G^4m1Byze(gRD,k/Yt/9%2kp?-P`?k,Y2WnzjNss*j<<<oP`Ze3RW&rIg[p>h$is/<_N=Ek"YkSm_%Ev`IX0%-xZz(1X[dc2=E9A4ZDJ_!%>9R`F?B={*J("`6Z^?27IYw;V`":T/uhEJngcWhZ{1WJ(Dy*lG0i/>$r
b2>SK^YxTH,uqqYDpYI0U^njM0;c`sfbY^9i7.xnI0[).1rH54(cX
bT)s_hG"fzMO=YQT]f.SVO48ex0uCN/slk>ZZ8]$NwILf{1)Kv/[C@M,OgQ>u&J)<<Wx2u]enRCZ4zSsTHujc{ko*;*>PsxPcl=x3kk1uJa!qUK#p5F.:df%osGNU?8h:ZFw/(BDNF(n:Qc}?&D{[!#PhOH.]9S"Js_geuDVM"Kai[3k_mjQkA)ZGHB(hkRmf9g@FY*{ZEii0%^sg*?
8;,PD"hMq,K`s`bO8?99]%X:-Za$LUaK8^Z,UIYy9$rV)"DquI>(mCi2qIFBO"aX73r$s@JkUI2wr4DcT2^@?Wh~sL@ZZmv)eeQ4udgprp
v4-JrD}*4;"0>N:V^4KY;::0d=kEuZQM*:er@RUhdz"te_wm#f.Kw%K(g!*D>?Of5qMMiEvFd6nd1
hG~i#G?X!pORwU#pxhu@g5t.25(g*GOhr+VLF;C2=N6s=M7UQj|hFa"fZ/mmMT{;{@P>9
O0#WxSmM.NJX(gi:,?E>f77G[*$Z6^@/k
m0]*yuPP13Z#1"UfaXXb:@lm,g|y]Ml;<.d5Bk`&0t]di3Sj3SUnt`SNl0plme|:=6B:rgW
jL)s^=}6vIidnaYB[y>i?)X!6/<@3V;4s4gA;4odNO4L+_Nsv64M+tV,F<
.}yE49ozYI^Z)<=LC+C8+M.Z?-T^2O1U/ec%4.OVX+:l;Yy]fzle,[pv]];EW"VB>k3lwj]ep(Rbo0)*K5K&Yqon;!K.lb5qB(*S7dO7JX:h,QaM/vt[+Os]"83xmw7BB@?:#tFa*oJ;?f)@5d%ij|]<i67miB*0JqMTL$uy)Zk#^>Dgis';case"pt-br":return'!]^@ibPDI?TK,o=BShvj;D{%F9A4+-)f[lQB`U.(IB!lzQX_4PKpPMSq10l#0p2i#UbqJXr7?_{UHVI-Y(?0b_
k|_jt?c|lHP*q$dgrFw/nU:}khs7;5g5H4kfZKQ~M:Lg<mK>=Xh*/h^*RkxYVIy8M%>_[kd
wPjrr`nGgs/p)ZM3_x,_Zo8T8/F}NkO+GLx]=}HuvMqFl6Az!ZTR)9W8M8w6n3LMK:BXiBYb";$e5v[}M.5!/VvR3|9}e}XCI&
~`q`rP!^(yn$Y78R`*5#sx}jyJO`JYglhl~GsYxO`dPbr.~Uv3xD6tZ(xpyP%fwLVOqyeo(,8FR`$2{,nDr-(OdWHuM:yCsVIo%kKSv,qV_M>F6M.ne9!=g?oRQrBT(hZGQsPst]SJ`&9QJd/QM39imF/eyJA/Zd;W}jhvx8~c`^G1+":t^)ePlceMeA6OY+ZDg<gc]Lg?wQ(ONq|<)#
v3B0sW?PN4IGW/+Eu66"li$"4B[VC<7us+r2Un!(Ujr7kL-Xg,sLiE3nJ&)NG41(B/cDJO?6=Kjb[sJJA[Hd-eIaNP>1]]<Ec<0OSZ;Y$.2vfnO7J?>p.-%rVk1?"Vcb3X722u;06P#x"qBH)cyb3/L,:10j528AaCX;a9HpW~jq2cPH2nDOmH;mxbmK[
<ipN0a1`9U$.F@S,SaO0gOcwo)a@"qhJrp1U4Qt[5hO@Cw?Iek/k]dVL-G2^#JY&Z%d=UCd1G=Fj`)TD=U/8.u12?lL/1AT*u@)_JTNQ]9ka`xEH`_W5OX;41mA}$8:c%>lFu]r7Ud(eX-K_<e3SG=xXlbeLA6.uG*ifF=$t`XK_0iQ6-(2MkXive?xcvt:3w}7NQu[cVUkMnu<O!sZVd3+Z0ut@%amQX#j=@eLt7Mt)NJ.VSZ(0<+j34R:v/n0C[yQQJ|2k;@!y<88oR>6!F%HST(Fa`69)3KcIp[5QD/-rUQpN#z@0a5h=so"Le03SP3%GY7$Ox|hc*Zjx7P?yK#5[>pJ(Pbx.aKH(QE*]lh"M6Bv:$(3h@x9n!PizE14pRZ1|w?H<i-K;u~^K"KR2*sS<vBqJ?5]w38D;RmTrV[KAQ).L$*W_%_o33+$st>R#"8A_#S-uRV%6c#2%4U*lnB3(Mzp9Xi.,g:
<6AgGjP8r[l,I;|y)?HK10ACx&>+*+qFW?S$H"br6GOHm<?(7Y8R<5f@_9^<mU=@)mYX9mW,BN~>+YS-"P}:LM`^&#IZ0sHYa={rNBW#c2,X)X4yRv..s?()=w9x&*x2m=0j]^Kxh/VQr%/Tq-^
=^zOBqU0rM4]8uj&MlFu+2fQ*!a:uImVeqrFt#W,x<yns8rjh&psDM|<_"Xy#)8OtCL(@Wi"r7c4}FpAY]=6v(ZcP@CeF"P"$D)44%>_"aXSf2#N9f.@_rBd7qo%4HiT*QPJD)Jd*P|4p1MB4Di90>R%@h|fp[@/E;IR]c_A<JSU;Q,HY[_>B:{*1$]xJ]"tF!Y>mF~i1D?#G1M^7>)=4`|rNUz-AGc4=LvI,xd_$*QF9N2^}La5/<PA!=$j&a{gq%Z5plE[&MkP}T"b+Ax?6DxL0KUq?9:3/%)aX#E)ZSW=2u0A[P~94<1;Zshl5nmZ{L-wEJ^2gRD+48#gg-wgO2u362e&[=xi+0qw9vETkQt,M<[b-6lI3q|AqIRZcRu@5L{$x)cl(soS:*+8GFtv^xY>8)Z8<K*=MMq$KW(k+=H?^;%[/dv)[]5J:W~F:`aX63V%Vp_6Ka"S(*7`JSU@Z/~h,[|Nd@WwN8Wn<s}%]WA3ZJZR3JPwITEMn3_-&<
j,s0w+C~EPAHd!G"(_Y+i@Z0]fNWn?sW4Z%:*|(hX%-

%:rp^Y]@7kI47;qA055[8a;jn7v`,;i;|makK0u&n*MSVj;2}N^ovt*:0tlVI+kT7UM#zOvk3ZRW+JxgC&18iEV*:w+7Uum).5SsK[Z^WRiM>(=Xs]HN.D5.]))r2`$lFWCYVYyUk;@G_+,bX>rE{dtK#T;ghRuL|$*M7`]"H35>,ZfuS"4f7V6!D60,>-}[iP>]hJQq*bV>0XpRc_@KWQ0[Gb*7Ao%j#f
*jEmXhrnT:Vef~SWGNh4v!!cxBt<]KI(IS(<UQ)ZZonU6Wm5l40neNX)@8j5X4OOqX)dm7&kqLcVh0Gzj=-:a@!|&|E"EqeX5ak`K^UM?8&Z6qI-T
r?_X8w<Zk)hZ;@*LG#KXZ*:1,Q2^kE/[J:6)dxjO(|1C)I<W@2dMiw#KI$1MaU,=EyJrs[7>XagKde5Hp!cX._ri@Aq%FAk4W9T}O^9@0]Zg9um`.$+G%vban=[!V6hD05q~SN*BD^1O]gi;8qOMdj<l]o/<O~3|/q_I"On|eKWvD<$qgIt8=;v&T"Fhf@DaP^g+Q?8oYOqfFJP
,o6LUe+x=d)Zfe@c1{2QJFC*Y;g,]%T$W3Gs_5(x9LbTyzo8=@;g_a@nFWxR<*`EufKlMcbE!Q
sy{b~a=
Jpg:k3G%Wy:34QsL55-sB?oy#pgM8k4)?FS
8m_ns[%7)s2VOgPgN;.Q&L|Q/2Gmw(&nsEe%M4RxxTk4S+[8!GWFi$:fzuU@dRna8g>n/D1y[>H3Ol|+jqv&gC3uc1Lk)x[
?[TVZTDO-,^n3W^J!@2q(3=F*vg&XA"-ALr8/.~f%V3F?r{("8{Vbv-1rX3"QC$83=d0t;7y$*^s+2=k

5@N=A,2UThM"5fv.[ei%>D,_b0#
acqIA@hc>g*0&>+a+i9_1x([(6Sk_@?_}8fH2mi4R9DeB5J2SU2b$P8W"m<:j.8O3fLTF2E.&93QvpBA6C$,hw}J2V[Noq$?C0rLnOGD(vvo3E+aYb=`2NLtN8yMFDyrHxZ:6qA_<G|&[#(%>7"QZ,(8{y8`KC~t|!/$NJef[F.fWQnZ8$j%@w@>9(p
_5srv6ukifa-svX2+#9U)k86{KZoRhhmqeq-qKLorwji%#iFaUGQd&W-;Ic.kujySXTsv3}F!H"SIC~f@QG2{p&!#&5?E_eFS<T;_<b-PT?omoh_-G:q2><B12BN;*LI.&,$H';case"sk":return'+]^K2h%pM*V2%o8"X>l5c6,mnh)dZUT1X14o6U<NKH
"~U"fa"[swg`d@!U&Q3iR
eKV?tTvWrOO)0#a+uCY1XwjtL.yvbXw>YmG{q#ioMQu2tQ!8yN6Ek`f0
@F/bDlwK@K~d{]yM}j>=D^SmR$(67J^urMtd&f-]s&30Nq1X2-g[?C(3bYOk|.P*yGFmYwD3HWgIl5bd:UrV"7
lJXyUtcahwD?SinH@u^RuvjjCI3(VlivgEvs"Ln+77MDp>Bdsve>j=Z8lf^i`&FV7o(GV03|^Vd4$.wS)TbFN[@ILdcASf]|wC9whRiEWhWq;][>RID)n[k".Ph|Mm8,J*eu`QDqBwP:
^*dnPmNMnK#DG=V02W#&gw+$PCGH,d"@}EJwSD3sQyK`gfV=K0kjcQ#8X?5Rc6{
f,zo1urnYdWHiy1^!^cUx_#K>yNF~?m4{kp]uXJMdoMSolVcoNJaznO)@G%G1]Y1N+;"t=vn/&ut*X]FB*)-Dc0
RC`6#0),bQ,"}9%;E"85+VJ*alIQ?AxnXs`;5/Ukn^CQQ8+^eJxbB)ke7aF./Mh.hWsG
8AShz&^iu}gM>Kk4dxJVXv#LwRUn$<!-,G:&
G@kpm/G>N0ONgidxV*^i]o8"`_l,*(<8)G|m7qA%gUhfzH!OL#?R:rBmK[}v&BhV_AW%;)Y[
?yn"*&tXaT6)eqI$P=S^_s/,TT.-H*Zf,n(D*.By1x1}N+wL.d4Cw/Q
C&
&JEMY`>P:s5e.azdYXv8cF|=lZ3C9+%p$6tZ{@f<j5*QYk)%NHI;LfImDmW$UPFq`KFOQ,4Z#9?L]lnS6"BAIG0Yk?}`6KQn?!.(bU@k};q3}h8;T<D%tXD`P$6-AR@jcTW<%e:twXE1ECR/e<YcyUm/P2!4Rol?^bWq7xZ.C+"=="m[CdgvcJQ/V!fQ~@dGeq{UXI-=ie:I[VaH;TWpvi
RO99&GNothnfHk!eS(4{sB?)g=S`fF[?o&gv3R8cCC3~hZUNqxk8#4%a
kuU1Me5@wU<>;aN;{qF."6Kr:fDKNsDh]H@E@D"s]]e06GbGEtI*Shi&3*PdK+jcZ9>dJJkn^=8Xw)ODWx|EoQQK9UK07^:CqB)n.6aGh2Mew1~D!nNW5WrC4ln;u8dw02/8gy5E+t@:{(_ln/z_4Hc,HLFIk/HA=
?7PZEGKB~xQ"0Q^At]?BK4:3hr"hIgEXv#.bhm+EaXgy%5(DauZ34M#e/6]><!RT3q}(okb/Kfrx
VbdGqL),P;m+bP7sqMP91St8A
2u"S#&omxzo(@{y2r~E%=jtf&zE{j:Dl[7Dwhp>58#SJDeR}LI"V@gURY0?K!2XNa2#39)3*%GP>k+Y:MO<"+B&QayGWw-K/`CM78&aguV6>J:">4/:Wk72ReVu/)bkN
XF3N_<4_d&B:3)K#)_/Vk9GFp"Bp)[0Z)xna`rzfU+O"otGj0Wl(:P-C|=yQ_=HW|a+v!BBGuOvUett-#rBB6]ZP,WkN!ZZ$O+>!PP-DLb-qwvx78NDHKKN8.4
W;9!8HB"LgHdo,nIey.}$0o;Vj"#ao(wgg)jFO]k#k!*FQ%EU]E;Eh+
/omsSEdADT/Y;}<K!L@Q9RR)QnFBWhMd^5qL)JavbS%C<|!2x9!ysOG{e~%HpTtg@h8OL=RR-taCx=Yxe2.xJhuT^59k/")iFyP0&~Cq>~7q=x5c?3"Ko2[LwA"<-$ZCqBYGTEU{pN$0,IoEbb[AT,-*fPi;GI="JG".+uJ+sX/_Rn"3[ahJnduCnEHml_pLg%={a8D8l,u~<n4E+f/t)(5ToVNHIUy]={sVhPLZxtMCXycB82yOx}Wmn"AZ"i=51_EX!~bor6dZ)mhSal7I>[!y4Y(F!x
muGfh"a=SdQmi$p5*D01x[>LQ<PL(4T2}Rdx&9Omh*+O>faD`#$/:W2=08G"6fcC>>!4J#o9wq<qUM
h41R^3v58NH[?@23&nIuL}wCZ(.6I;w7wnqz6H!fbl8mBQkH[>LV*GE[%$If^>=BJ.Vv4s$PjZj:3yWP>jxH+^Xo&a2?h85o,|)=d)2c`DJ^m<k4!!o^[~wR"?teGs:uYHKo;~&W^ax(>f/Hn0ib704<E@$CUmiMFdU+S|S?*
0Hrc-&PEcw/(PK%!fuq5;SLXQ$F_sruRp("m8A*!A>g-"5Gp=NXhCx:2`^&bQ
&%b|;0
Dyk0ybVAtO?4@(I_?kDC3[y[y%-f8;}$|d.
BfrVMk`/]@&pBvHvduX[(R9i*F"&jWD)`+Rgc(.v<)E"g:F-*o_Ww8PA-Bs-|0(,41E0[;vS<2c
IG*+f9Kv5:{BMv5
"Lv/Hk3ldT3lZ)Vd37XaEgq%@k%*haF859}]:+sXTC)V#lY?7<z._f#nfy,tnWvKD],!;q!-0r+HbTa>:-G)SJj6#P$/Snq%stm1BHkip_b0v:zxgq?6Q:]D%wYEV^j2tn@k2!PU5>x<
bTX8Zbiv:d%(_mRo:8&M35foNtP~9*mxM)Y%`]!GAYDF1omUw
Fsy=GWG10U]-s{8(d#GgOV"exU,PD80m"iSm19]D
??@k!I|i{+=]KP*.%:,p]/?+KInq$O0/X``F?GOSif=)#qU:~sWU4NM+Wl<a9iH_qHI^@+;tTtQC1lntVGn=esarl-J,r+Dw6O5h9Y1"iP#%HkbO/S1?K)];Ze-hwL4#|^d%-`mW#TH"YgBrwY)lTyYc5UC<NFFE^V0W<"sKnDv3{1PEG.M=v%&alpO4<Lt%VNwb-
"%+p*Q!OGJ?r<Ms[)^rN/^jj
"bUWZ;=&>Ga^w7b5qG]]E[=A[v6^OIVG6rP.LQ6##k^qtY^r47qL$m.|c34l-~prJKud.+MsQ=
wkdlm7/.%0`a
Z2MHs9QM%tW,Q@<V4;<ZkHPCegaUGyqSXB<YT<Q
W;T;J&6zG"+SD]eHmMG)nX*hY76
aFl_O=JAnSF0K|iO4P1,%b4Pfb0Gk3w)h56>1323a;Z3J8_r)UvI9uF5kDK&3cIL+CZD2]wF8A@4$=gG:n[%b:%E
cA@]LlvVIhv4)S!KaQ
`5U~A,[5"J_p[Lly&Fal>mYUx]Jf;&3gQo]vlhc1WHsYG*Eup?n$]t0[TqcO*ajB$9qUIM5h9(E2c.)}2aqjR-o=,@Ra?#EE%]lZ.aYRHfYmFB:N^<4Y_<G/Y7[zc1)w"gy!GeCECqpr2+Jji2yh_Kr+.qois&EvpZpcy^C3Jo:q-hPOmh,`4qeK>R"#[mqgun.`/&/dPZFq<9&gk?@Qo*$zo|BGM
X>G+B@<3)0EqNg?L[yg044P&m8s:@ES=pwQsnmY)qB[~X)=&/N9/n3@(uYe>o+%z7+0SKo:]mjv(E/E}FSo=SmYMBbGPkewVnU>!:i)&s:x0xNHGL}w*_$0[2O/Q?7Q?o5Hf^/-IvKDOpTXsNj[B.&9tTS>7y!tX';case"sl":return'(]^;:g"p=)Q,S`$nsf5!SBK("=6$zC
gjH^s7BFvDqW9E]r8WPzq/cV%b(SMhkfwQ.7r,R8N<:U]|gpXigzR,D{@RuZ+H>kLsB-X"^C*5qEl{y=]DBw@T
%=JryLWkO8|:8yGLxpqJ4ERJIez]>XD#c?86-cu+BdC7|-}VF<,5v`O]^v:j>(R9lN"uOC*DgH15N-;.ib7H!&oEEtG=(vU&BfNp.R/XDoE4rP[9#KxkX38W,PKlW#6BCe|tQEJne&_Ql@;R|sEoE#~+alGG=1s=jo
R$=F5kHn,$!O
;"VlYM4R[c7OSe%`&N%HT/niJ80c:0CBh/D?E![x{pm*EhDa%Ew-qQF4
1.qvTwAMd+6UW:veA?W:[la4G["<lt1$tAYMi#im
WEuoKf]RXf[5>a9l-lTe`R-XfgKO0"`Lk=kE{WB2,"@R}ris=B*b[dAaHcA>aK2A"*;qn>S6mX44tB@M~JM,G[h>$,x7ZUhj:+Wx1Zr7|N`?4@SacBU`Pq]w{sVlS4GV~Q/z#O;&d%6q4.D2~L|neRWA3PoJB@;5;L[jf:sR[<P%WtU<U]><T3}qh@yao0GSSf"L8hKDP_ka{*rs[(_OK$BP0Uz4^c2:[nj9%3Q@/?S,hi>BA:g%1VIhWT0
Nc<V=%3AZf3;)**&F_0?LBc8~XluGcCIfH%Q]tKN2d:HR1On$9cV-:p4Ed/:Z0}O1nifN#~dxyH[^R#G0u
,Jur`%dNdKsFE?qa5vHa!kfK`Ue^x4A1u&sj(qVQ@XDiALWTi^l$u3];uax;h(m2uAgHG]8B
1M#JaKN]}*}t2CU=Vu^b]$^eZXKHRNb`O6kXZ!Z[Tl)B5BmA5Ul/Co/?
ic^8fD!6)V:t=rbf;ejF=rpA0m-Sbn[*%6>Ql3v)F;V*.By(SeYwmhx
*YU8?8HweNl;$j8!d]./D*8g?nFlo5+)=!4kY61pU~r
B*MW%ng,<[E)=#Od<>AmIm!88gIc+$_It13O**,BD{EF0~V)CDAgHbV-$>""xp#PQ=J}ck"Jxhj7hRA/Kl#iZ2(4?PiZ+5hV=?2=6-A;5&c|aAR%w_RPBF^p-xVS
)m2+r<e$gd))/0BdOu6C^z!^5I0n|b]iN1|&^vVu{1u;DI=$CG"%m#J$+E(m[Q%+4`:@@9#6=RyZRf}(G^Uy@z(XKUW3|KNKP,M(hn@%<cl
IHS:Fbn`_DkYta@UDO%=YTwd5>}07?:5,Nv-T1A?D;erJ3)JexKx1]sAbULk}-l%>C_"(-.m+HBb&pp5vHYPcw*<J@v<?@yy2s-3U2R"&$UFqvZ30+NT<6Fes[rPpCuOF:5Fq(vdx<AdP!5#
k4mQB=&NP#Z8h(t^jzHX?a)VoA"V=0795O96v;Z0:r.mMNI$8Ej:w(tq]idH?jit*<wS$
3`7Kj"s}(VXT<v"jr9V$:nt_Z%Cz><"2`0gNp*ke6=,V3RZ~pS^
H-(qL=p2VQtd,;fav!:U:+AEe9nue"
be+J~P)iYD>eq?s@OB;_=o=?)w*aZexh|:rM#4knQs0`qZB7)v
Oq6)16/^*ff(E#SL#}<4>Db[##,W;yq}.S&99"=+q.0xRFY_4LfV@<
1T>s,e9G|W7%+*d;iJcdKR%)/j:[a)X0-QcBuo+-F.-L/f5eIkPl^85*95GDIKN;}7mOvS|O_e%Pfn!E6IV3]T5U=ryx1N^14N12[2t.0/K*_PHSyYwc7EMYK..B_m%e:U}:aDxds!uFO*J2}=PV{*3y-":"2[}B-4A6YQL2,(OGO.4B:>LnF:c$.fHD#h/Sc$0Es37"Tp$_
TD)~t#NQ#|NQ=B1m2j?%"*5ih{w8YgW{,e%q59Adj8g:P`iJj|BXl1o>;E!|#IBcu]M1Fh;nVKZKS@c:?97`_b5F+o@sg?^K3DwTp%*Hd2GFXJ.M`P:xxO6Cqq$z!mhUP]lf4lH@X*;@Rz6$B:@NtXEOrHc`c%&b.RG?lA4!8<XR4}q@=g^!=rX3h_h_x3iy,x.PNS"%VJ*NM+1r![%Hi(XkxjV2.de8BKk{vtk~"Ao,IVL/`ue]hACD%5!n/[L*((gSEX^F7oJ0A}YW<sTvG#Jq%<@#m~0ocT*bAFsL0_p~@5u@K(sV_$eH`%Ks8X6(whxb_;h5BtLfl@sRVR;Vp2Inp4j}x0(ohUy~67]|Hy:nE3?cStSUfke&!>PHTRY:<Cgul(FTDVK??u
24|b%dR53QbT;Q)3}phkHAnTc%O6hCM1dUgAEHwTL<s-To.L
,nDpGj?<Y]@(1hOf]sI:RLD@?!a9gi%2yj+?H!BCKB!>4p"?alM6kJT_d*"u6@Yp>?chOdL0dI^/X,@}<Vuh2yz!"2mMY]g/D`hSrV=}d4wh,ehHUt=ugjn
XpsDVfSjtLs:E`lHehD:XwHpyn^Xu_L<-|C`y>yIf8xbpN9ixcqO9BxD*hu~dsYS%]3rxyq0LR4*n
p5_SJI:u-kT,SgN
;:H`v0@v=!/KxoGLB/Ep+.X&<a,O:/67fl8*c
$a@uoU5V,w<.4o2XI!e6VU0Df6*[QOp8u%-H/u/%PQ,kyo1HZu0e-CTJx~_]_]BsHcqQYLQkZ,-{=&8Q9<5Povw_8+g1ZeiNl_x/r&QA!Kx1PmLkewDco;"~,ewp2C_WQ>tTfgE8WsT?j@m/=;T-TKi9NXV_j/N1#3q!EP#*Is3o(rt47r$ErBKRp_#eOdnaJh/nFCLc2E__F4"rcCDdsL-@Mb=OSlbeQ;2N_#BAe"k00bk`S2@C;kK1HQ0;+q+zn;AxZ+41G$p+^$$AA65>9r>2W[m<7%5>8u@h]>LWAcEcI#7GM~mch=<<$Zk@=u2!AQSUR.#dg
_XbI(s1C22LKj"!lv}fv@;v"5pT]ZcLgw=SUcD]tH$6{[r*`ApO89?mVEWMjepmlAkZfHyrg8LVt_7n8vs$`dGEK<"<,(8*Q!u/adhZ(2pQd-OeeEkJYnzicMJi[LFq{6PXm7?e<:n[aY;
0L:Xw-i37QFoXpS;)k.J%8T5Pcno`$=h<iUhB4;;1L::BIHaQYP"ngQbnb;4pIOWS"oTqEFF>Qhx.O[iouy1
J"@uU=Q8[x8{nEu+yi[jT,
:ALDC_nU$fAa48s,j@[ur(<Udjc`L.&;[(h0`&I-RNVR,X"V~)@5H7&)_<)Y8W8OoOnh7EHAfw|1[%qXmLI#@N_!Rf/T
i4Vm8"""';case"fi":return'(]^;;5HWR$#Jmv&nu:1=SnFNWOYKUne#^hGl@d0<jkyNo"QZGIwh1"-q&`F;KhL=3#U1d/OueJKcZkJv%.n^t,@?!``Vr%^a{n[f)O:bEnEB=CEauf;6]L-aA*mhS$6Gc^uo
x*Y/0YFy^BEYUH3ee;3D
!m??*MHtjVwaw]?i-K|]WUdx{<-b}5hW6X#o&m130kXK3ZrIp6F-ywQ77*I(tHSy+/uTC$A]KD>PJp/&,63V^<l&]@S+W,`*G"bE1_uI9&-
9pEdw+TPJ*L+D5Hn9wB)`d?WS:2__@Ca3BFlcHEiGb)Ff0)t{7h(zP[HbhwCB>4mTtVP~(-0M*Zdg=)c8o_%-T^jUr{I-2V+X+i
Dvx7(:JJ(%(U!#Lph$7#%$N"*[aLvHd&*@Nv7W,$<Ws,4>kK=+^!>qyKOLR$:`[Q+j3FI
!Ah,9HF.YEeW2n`4bwZ)FSG-.
+qXcP)fn]Gk(;@f>7k&?|w"R(7Q?g4-qK^Q)O966M%K[DK$[38V$whtG*m-T|-?NIC+F*y8H|u"mK2eNYLUqNtZ4mY]+/
<,A3~dEbF/lRPP;9G[.?!n?4U/)NZ-+BKR6%E^6E240$&D56f"nPz0tWP0FG<J^*E)%sFsk;.L27sm91ze#H.#=VvrjE=fJJ>@j"$-@<CPSE52E/8b~t,Z877-T_o$#+z)%!WUcE]v;T*y:5$w
=$ScwT[}3Xtj?{"A>4Rey+oEQI/MqvV&-sN/,_(
1.;jyMmcTSNhdZX-m$*0YZS/Ps.2;^[oKSK~&5-8qzoRf$.-U$5QH$Nl,of<m[
"^2.>VB]YNf9U3Ud6k]Bd%XK5Gx8G8aMKFxN>C:5UCcaN.$1
,FRa#CMBAhddIEY8Z@0ZSIXS_)37C8R!1GAE>"doc7265orktluuxub|!ZYU-]6$xgwhr~2e!A0@;FVfF>^|XFIc!sJ9hyy7VAW]S:r,4o<Bb`_B7F)mP3-}bDaRTY0H9GyH[.KQ!bW%Q9!#Q@lpMWRf13-6K98f4y;";MP[sb320Z!L-ykLF
ZY8>bmd@n1!if-M7+
@QPZ=WjX_9^XOn+D4hH=9tR=^(v+,%@E7)k`43x*5<#bo_#$R3":pS"TU,#c5`"@I|0h1>X<(r/cH/@LPAW2.V?;%:4^P816]CebO;q>sZ[_*B0=gTWn[el:bC$lRxVR!XUF"4,Xa7E+v=3xUV%@N7C!h[x
Kzb{?oA#o`[s-B!*ht)u^ZMdg+MP>#y$M{>uksxdmUX(
*,He)^B-RY@_<J`&;?4-m?}9h8uo;yt]?TeCW_j^<isb?WT%"-fA%w>=$T")%oI%yUVH2?9N5CLv~x:kv1jIIo39}!^/xJ~V)tgn(M"of+^vw^dtzJ+hHD]ALj}D@7ErW0]91`%f=Ti*`AuW%D2x`9*r5Z@b$[q&V=
t`r#*):(&?h`Lty!]t@TVK_7S&E*$NR#/iX65f["
7N;>N6iyi@J;^SM9]caMIni[IRasGat%!CT5^)YY6Y~84;P=j/.lk7|E~8?UX?PoC.$Cd^n5R?CH2T+C4i"Sv&kE3([c-%6_UJ[P8",8gBysmB3rjL72L>YFb-1M;h4P@grQ?E>15i=%-a?4Cdt@HsmC#vw39J{2?_cCT([r:GcC~P(:(H7ome+(Hi&HX</>-Enb[4UVH4^%2aW#s:,Kc-q_w$l*ZVlJZ!*NDCpP}tlN!l@9S=LYI3RZ~#_a`cE1++1YC+;`_h&WWy]miR:"3
;UXN}t15|K1@`M`,d0O+jP1NOwzK$dw
$y,lF>QyeVPC}5U[M(pr]ymVLNBl&cP6%flIZCavdC#RQ-5s9G)OQqRj,j.G$`s>OLX[Ch]/0)i6b$=[YY2u+RNDGnP^"r-xx/i>&=10j.(0!y]#f/$)#o%!#qw6!eg!$#[OyptB5HVy*9kZN:Z#eyy/tDiaRY"d8(^a{ih9SLs:Vfj4"^T"JM|F{OX^1d99GWJyOmyBr(_J"*BkfGC$]OVnqv4z$Wm(8#F)/J@T7
v>CnF5s9W(33;DLjnJWd<;"$q_k*>;Z"=ehAfIhrS+QXH.Mo{il`D1DqeyW14^>v"j(ymGR;/Tb-!=3p*pxugW}b9-UU8
CjOb-^nl&K{`W;%
++}FOh}7ZF/&ROdT-_(kft}?:_QrIOS^THMkI@
>1lx5/PH@xL71>l5VRCZ<VVPX6Lt%![jZq4(6wH}OYZG,Qw&?[JhqaJ75PA);h]KN=Td
X>
rCOyR?.`v-7RvGnxft
tDCE4VZ6bf@Ih[uv(>ikkhPfN"n8@pX;qAQG4YJv//c$
qPd~2
IqQMI[jwUEa<Ah]Dq-A1&C)s+oIl;{2XfSB~pwjv"hA27I$t52d^J83a2)^>e3?g0t+.x<<5#SF8g$
qhDU#S314uoDi0FU!:m
[apjz&=*Oba2d3S)hs<[9ul:04/H|LMlpnncz:1Hl[<_a7g0V:gN*Vp17z#;fmx=]<P"z105u_$k9qTaU0%Cvs=4@[E#;bpb5/Rh3XobwUTOX;850lk_Q"uA~1^4Ts0M^f4wrEr%|DHyjGJnUFJ_h<TI5x|fZ=NL8IFq9R/aNg";fRU&-e2%P
b)-gMdTXq/bUDNTD]fsRT)*E92!?[UT*eb)d[U{Q:rP^}Ph;ZU1*,L_/_y8S-BDH-3R@Uw]m6?U,kKYm<E{.JwwZ;F]a|i^?~c:&1+Dv;S?k`1>qKalcWB.<Uo:qbdecG,%Qo.@*$T[Hnir]oLM`G#zGhsqZi5^;|rdUf9"KW^<*-Gi`!
_CNRUL69:#y(hP.TTv[:G

*pN4<PUCMQrtlEVWeo:fdXvQLeBSfoUF5p@9?ojm>U)sME20BE@;lsb&:,i8JHi0(ZYVlX:$Tx;aA&r/@Ne$o-ip1f-/buU+!W,DyY-)<+UXaAgDiP[R);h=fNZl()H-FQg|:+BB&@bq,dS1YI_ZSdG`
02pt%),A4Yz5=bV
@i#0?%]hzIK)I58*nsY%f)xX_-[Cw)K0anHA`dE&*vx%9piO7]PwxDHb{jLxK2Xb/DS/[UDOofnNx%~E!8s"-7/!mZWMQ2,&3*?a^h+eZg1sm^dhW,5;aEaFnJI^gI&oJly<].RYr^CA#;D&/fnoc/+ET:7g&pE"7"E>i+_f9,iR0""';case"sv":return'%Zu;:h%WB$",Stf0pEb.oD{;H/@eQE$tNDeXHKD)0Sa%u>:jOm!rq-!v`cu#t)]w,$0(6;"hM
*:jPmv+y^X>X+ogb7R"bU<m[@/n1rh0rDwO=3*W&grFxB*Z6@W6Dns&MIqat51],k!IM,Q<t!qq>#$:v!TC$y*~k
$*"^]k*_SeHo?|3=T5kQ_S)Hh}y*ZxUL1H*9%HJ`$):z:&@.CZywkaw3Ji4uV71RM}e^0UWC@ql{H(3{@;[ZPNxB);vEklj3Zv
@mckspM9=aW_~qK^!t(n*:!,k$=X|q|=!>{KU?Igj5*61jL-~*N&!0?m{WGfI1mld+<>]cU!=_%eLw~ool3E.b>=T0Gg,cHI2Bmm%+TEBpj:#NS
fnS!iBOT94S`{$r%9FzM6r|
emM)ILp=>rvw{/>Y-1@pNPNQG"Wpxpmy{jjsYNIA5-u-ubTxYkG-*qppMLivK^_QWSq$mF$6.8D"e-JZMP@+qF}57lzJwoSFs1u,i2
N(:rfQyX9u:W9}eK[qe6S-pj+GAvHTjB_bW*3ND$RZj#Vg#;(]OABNWCMeZIanGnVZsZf+!.>+$UYBwN^NR@DY&h!]ps&2!PA[4Vfj5`1m::,D(SaC&]omJp6RP:qQYw3ik:mfLq$rgeCi!S3jZIA9t-A;-i^^#}d21#%jv5MH19Vic;"VUy^rk!7ZE-9sNmdQZJ;%9jC!E%FS_MKj2V@%"?OllAt~r([JSnk]3BAbE~7M24kixeCuR{n&)LwbQMCwE/sK0yFj]<;2L1QlsT$WHLc)Qfd.0u^$5)%*U|JHZC#2vaJ75.XG_8_.)4$JAf+*?rTZ*Fnv%QN|U}<}MGkpdx?b_I1CBR&)l0fu_">]=86p8Vv"8{A|di;asg&IQMpsW&d19o<<Qy!WKC`:gz)iS~bA;P;b"QZMJXVJ)y._;KmWqXVYl&_eEsK~T$3;"^nw*!T}.V>XCbk_mKCr]<FS>E"?#>$4A+-%N>J,ciRBNaHCO|+E4n1]r;xA>cs0_w@k3x9R[4-nWNYL?~o04[D(*+x_uk"p^y
TS`?Xbclu)kIUQKyfa>6@;3UuJ.niM$Av*xW87)<u*b#R`fQ~Cc_y7;QR($Y9H)gL_5grKB;,`(k2n1OIu9@ebMw:m2uPm+C./L!16}]XLjKPC#v^yzmiw[J!c.`_YE?Q/f"#c)OQ"!Y)sR&JL_!Sl1+pYFO)t"j;9)0hPccHvle6L)-6VHq62(u"NTB7X^9h;MMtg{B{?kIknEv&";1_GQA5g~QY.V^TEl`1393q$0[wl}9bC5lP!1/1uBM|72SfSYx$ULyB$VZt#MuP13vbKK:6XZd;.`4.9arg;S#BN7%mL[lJUG)FN&^Qqi_<!G&*&.VG6e9R#IswbV114j.juUg
(?OiL2X>p]RU]LNO+%^2Dj!y8d5f">><6z#^"f4`U`S^Dy$~@l8vP]PIwM7~?Dv
YkQJ?O2fpXg"F3tZlk]K&jBhd9*okaZ>?x<:&/G5e-*5D`"ESw"y[Qp1u:fUgU2q.PkR2;e)SZ)[T],#(/Ak${?Z*-C@I[NPNzZS84va9I;4k2Oq3W.2q03yg7B^od<Y)Q&UPs/hcK:Yh{xwL?OP7frQL5*vXotVv|XSV1Qhiw11^+yKOQ^W]#0ZQs7lI_g8?s98KO?U[t`InTXd0P+Bl`,tQ#Hv2F`=kP-xwD#Yrx(u^0$Du7
8;H)906@/$=2]rU8NIlTzyTgcyoc5?<I<1zMY6}Dp
=iJgq
CV"3=9w"fAv$!R"uJ;S6T#l$]EZ],P0X{LNX/]S/P9t^2rqPQ31>k6x5oNgWB,5fkS];(sSMF<-PVu1h14.AyPV/K7VU5m9w-_kJtiF:$Pct_x`fn$4lx*r8<>mo>n=lvoddP!^VVq1H-PmZ<w#5]W+`6/8-L3tEWY[GXk_rTbu6hJY-BJi!h,Pq@Fo#,gR5n3GdN
,4N:@9aE1
YCRp543%Ra$-yBtd0QXdLP]3ko4s/Eo`Ck<itIJ4a41GSU:-=)IP)cJVJ-;=2SAhR"uTgrO7cTjE1=Sn2=w:[Q4/8MQg(s23^AHp
fG(rh:;i-/H$Jy)*0gz%t<y>SRZX/C$D:9@H-?E<GiC*0XFQM;Lz<#w
M="10X1CrDk-`}rx1b2?MAhL8@uDj($j!&uS*1C)xB2?XRxNiy97WVA[H`WfvZlJPtjvg$Nf[FW<1
3.hF`j2Z2._EU:;>xyh/e+m[Ho+U%rIjvdyhoM6m*AL
Lg("ko:2"pU?BR^W/jV^xleO]vb?p^#I$:1EN0y9yS>j@c;RyJ9,@2R;$kp-=1@yiF&k/+tH_5:H<p3Hn=Dnq]I.<0L-az2P23dY"<
Ba7+=ub,;G;jy3&s>-qJ4(Y/Hn.jK[1rhw=Y45
Yo!B%n]EA3D3RG_F`bPE6itO[&Lo"Z!uOL*|`clYKm0#O0Ai^ZL7quM#&;=:;%9Z:H7nx-UrEw5rSSs#`zJ6TNp?TIZ?-rhuuyKh68P]MxS>1?^]t&BD9)E3w-XiytlDH9w$]@h%^AVSu@(lso^6ru)Xi*K:n4
gX)SekaOyw_Y8C~c?q,0HlLCR=j)icNFI0aZ1;c
2Uf6)L=6";sG^2)J@:T3Q0h8>Ore|J()o90MCo
.vgOxM`osny!k<AkjDkF:P_D"PF*L_U"l08/=MsjD6Ir_:Lz`i/mN8=h[YsuZ$7mgG^
>.IGlbo9`(=gp6)^d.+/R6cT<pU2YOagm<_$a?Dq,ELiCbfP&%drQ~x^v36nW#8!,h2"^le%j>M=0C1-M9`m[$Nd3i+b>R>aL8@PsIXr[%,|9TxjFe&)HC^Y^nM_-0VuwL9/53QX_8?&^|?h:97BC6NBl$0q#iKXuY>LOwN}dU4J!q[zK96180ZW-KO%x7;)c(:h_Z2hQP6GI@?L9rBC9!yS/&G44C9u/#]2"z';case"vi":return'&]^;R]@s&/fc
s@r:.R!$Mz_/:;N@A$P$Z[yQ
D&%TBb@vXeZ.gxx:o+?@D-5
p9Z6/1tiEQ1/LThp#o8y9Mpv7av<LU7[r%:]aazE$uggDut[IhhjGP3vbIl`4Bcpa5h[Bq/k
ou[O`KIgnw]r00kafatSM)(cf[D&f`2;p#<hcx(i%{=2ViV:<LBi[:Wd"2!KM,QSGA)dp}L@g}*%qg]+_8
]KlL@=?ZdZGfy(_+gr6Wn+|PJ2Mk*46`8rx<1i>-
owHM(rkKiMPg:39`qHH:7oV%EGGgSylJ.,e;!,q.VEcCX<nob~Bxu{qM/rvG]wBkUY<$4{>)3>UabM8|>"To?OZv%<@rP=,Fk-+DwuFw&sMpd3;/H1,w)s7<HkqWE{(Vkl1J*`b2IN45=QR)qY2>Qld<S:1aoNsT2ix{.>c8-"MGPg#PI6N`=Jp+g`xlQc?7(I)NusW=,9XIpyQ+egN2KPt%j%OA7V5V<dR@tn4%tFhtiANr#Mr4XJMUt$B^j3apkJf_0L_hAPQ.>ivw@sSLO`:vkjJ~t?[Y;{/U[1uTDcm_CDLb]eF$3YvgrjcVulJ|pbKfA*5^_2].esT8Af^zi%7zimx9F)`<nhyKLBiL,=[K+>Z>e.tp;]M][tq&4B[2Deh?9z!M9V^W//!LJ.lZF]&wuy;1fl#mi3BS_&6,I-%f&[f`-^Sn@(y*9QM0nh=wd^UzUe&)R//}DbX|iIxoinBCZ;%BliX+,hWRrae32nhn>[mPm>lFQ>uOX`@Yo+("P|S83qVK[=cc@?iz94V+lGz%:Z!X9=qWCzE5MZ4Da."PWgL1k}eqU
78&p9EoRv.$mtzH&vy?/tCFR!32#&tt6C$vV
`#HO6>`Y>_#4=F#^EH/P{>Jq#gDy+u&f>L<d$b=P"P06X!lNFpdBh`1<0m]09#&EsEw$ar`4:QGw4ItjTy(R<0[^t"#
E]wwz
@SKqSo,1q-rM*$!ZS%nA^18Hxrq&jG>dY+i/TP6^qE/-&]<%(kGlJmJ@kN6SO"J&$Eb(T=L-8UVCCZ4&0m)J8)*$I1nR(a!7xYsK^3M2XV2;$iU=5qLn`w.8!?m_}*?PXrA+4]6P
hKK{MN`j4j7c8cBf`Gsn7.*5FiLxDOZ|];+yKPT[G.gR>X+T5#jK5;]Y(g@j7t!
V61lXF-mA!M-r0#ecnw
dW`HiyM>2">7`@"r19(`j7cvw;n/97a4__m~]QFD#nMLUO>&N>b}^g)k=5g$n9nrjS:cJ]M@nJH)4j0f?{%C[*21)
I!1M#>1
_J0tZZY]2mMw/e-
0@YJk{Jr)wFun,mrHGxic!4A>ndQw-QY#XF5]4wV")y2xzt*,-rbG<.-Q0mr(R
n0]f6F".TC5K/0/mVo*Y>$sV]n}8CsQg6_#Va:y=J%2yJo4vr%Rnr%}4<.KdknL$`Lx4>N:RQ::=XRCVFItA}wu"^pV:__>yTJ,d#<I%a([/X@6q|k]K*5S#a.tt:5drJEhJefd&$UTSl$0?g<UN8ns;%w%_mZ<!N%dW*q[=@Rj/KOV>HCT(Sy@I{l0W4njamY_OhAaOd?:[WdIBW8#ullzw0TO+2Z,51QZ_k_%R{5C3}#|.O#>-hU/e"<oBXcR!b?R&n^)PpRO@oDryC1#(U;1(Y*?%2QQtp=P+nj9sZJ
L`rS$j<Bj>bAM9FCc}ot=d=*/RB
sj&>R"Bd<ZduHco9w~ExFj12RV*B1c3N2l>%_>vu_=MD>;vm8NoqHw)NyK_%NAbGFH[Bj7F3dKj5-)5fmGx#u=A+"+mgddv^k]_abQL3IC?z]wdr.MU*(Nv{uEX$rMI+N)sNdF3E@expk8[=QoA!W7Fs({[Uv?mGRWQN/=#UuGETg:ik2`;L8:-vCK^vTTp5unTr8(fqU#@Px_:-BJF1-4C`pYd:${9_8jCQqLR8bN+QQGsZfpx)H(8,@#w5NO?uX"ierVK9N~<`=bK5T/3_QT3jv
S
Q|x9F@R}YbxFy^?a.,2|viHrNcNp5sei"`v0%-2T2yre#eiYvq+NlgZ[@d,*5E,8CYTJ:?"MW|Tb5bgn"Q]@Fh(>,`u)ZdO,d+I3.yFgI94=$pa2[2Z"apM[KkITty^_:Gi41BDHd:;7D|19kzJ4gjx(>em$q1%Lhp(aS"RrClq3oFe)-&AZsWQ|lfSIQ0Ld>-/}$WoKq3kP3^m}b"awYmX7k};aJphPgY!`Y$*12ba=imjVH*sGi6>(HWcVIOOg*`UxQM[@*>27Z-v2N#eVMGSixB+<.QjRil,7jDQ]fsP7B]/Y+7cGKkxb&44<f*asF9Dtd(oXeNq2O{@J
-xWXT?qmir2FB<p$"XhGgCMQ7I(E6&G:8NIEj_,N3(TesE/o*@i7s*Q(ornyPWeWe[1$*KEn/9|xe%!1~R[:9wH*ehh>vNYt<[^;nSOAO9DWp]LApR*O?Y!Cqwg(=*2&qM!-Ci69z+c4L57[A.4ac(w@m&WD)^=GE`2oG;/x0k]^d!o&=,futQMB511&6Sbv>K8JLm$)[R%q#P}u{R8K:<)&$wdnWvutiw$t{N2*irzG_Zy(#aL_h27w
lP.cn3m1B?+zSf%fb;HsDrFUv}(j-
;c;[Ns6gpyN1j]I`:@(oZtmQif%Zd^$M+a6L*dd20/%+=D]NDoA3b,=c-CSDt#7)1^0]@(H%C]U|#RUK`qUJ6),g:Le4ui#]h6=L/EuzM-n:U)G!^dPWU+OUmu6PB?7v?N8$*
C8<h=io#MQOva?LdVh]DGBlKRY:dS^?}I|OjPmhc&`N.I9n0qh=%wcLk1?0-oj8^yYl]U7
2D:mGZ5Lx4;YLVkOftGtuLu=kI&BeDmuE*3Mp=f!*Dg#qTx2vCP[8A20vi<<nGJoiN*Z?Ks"0h
08tNu!(FJwci1,ZLjG%lSN#BGCRmI[Faekj$PxfY%|Js/E22Ocn`F7MN;asj1?[/yFl)<Co3
o+~5}3EY}s~Oz0ew]QkL+0v0c#>%UP]NggZ[q0GGBGrj*UoLW4A:RJFru0*`kazeotOJ|b^=inu>W/b+I<U2AZ#.P88s>IOm^l!>LVsG
NIR}EW=rID<f)z.}u);V-~W2g{qLs5Ks?A.%DIrZ826;E$[8*@O^;&WC/#&]2tk>`MxI(q1a593J&)cmj[f,;m8/B2Nw*rhb>o;P^WkF+Wxr8[5FV3i`Z_^)NGMUpe%X0<`ZXq#BT#V{EFTUi^JYPuc/4y7M4j]K9cZ#M8?P[:4E?h49JP2@Es"R=!?X=}kJrB^bB1KHF(Q?f"9tAU^UNV';case"tr":return'.]^@aaMDY(nXc={uao,2RNVMAEq!+]TC&`0"!!Ui]y:?"/3VY=tM9[|qtxU,nYwc#KRLyz&n^QhFdpL5+CMh)EGYsBge
;-L?6/*
lITxTqU24qJO$kLrgvAGKLkY*AE^ye(7*}eB7hY&
]uq165>[t76L,^|X^PRxB%KKLKh,/MNFd`6W`dlMgt+7@D
H=oYUd5)QEvJGr=j&dR@crj<q,lEO<Vgw}RKK<P(Fx+BS9-ux;uDDY1(VtBASiZgHN4;WfDNBk3Rs%JVMNd4r-B?fxY52~=z#^ETXk>S&nV/7znf_N=HhhdlSFUV&t/1!,0c.,56yM,Uc8`9i!cpCoP_qvx*&sC@Koj6aNIeEMyA]CV0e7ENa|ygR-)2Ar!oa{%8evYU1Q]roV6/$FE3akYaXZ
++Wq.@t`.,5>`uws5#ua)"5/Ttj+]y`lxIq]CSr9D(<(NUnJ]%G_s3QM9&(iH+s^XD{)Fr?eh:%Y0.c-rGs*63w5j!0Lg5;$b@xj#KP>05e5y0|ar_X1AY:0S;{cHg/+]5XrpWZ#$IERoeBlF%{I]7I8]L`/>v~#6NW1_v!@XwXT4lG>j.mo,u[eEJhvf"aGF(UuWUmi{_^!WdqBc3l!uv"/Fa|eCW#PF%RgX#sqKRzsv*k6Rk1CF@rMO]y.ztip1L~G;?.OZh883!,U(29c2>xb34k.:65^d^!URPTvV$/lywox.[k@tERrekdvDM"w7``T44~i!)I_?-dZHvU`-_]1qm9m$Yolia?tzs(e9v4f!8NyC<)-a*JX!>Mwb!I"3-fZYozputf[Rncd*%(kE(./,qeUU3eu8PwyF/z*HO|Hhnv9BX6!f2WX"w,"UObP?l$mA$ZCJw"F4:"h`A2(5a%oUaz&=uiIvx)-=>rC>=`P
mUr<RSI^Q1d.ik.?Q
w
7Bu"Q*LA2YeIwYJefa#!2hnIeIIrquEcZ!f8JvGO+N-9,;+|=wfDfn2"%X=rK{J^c`_xc)a^1)EjKY"KtKjenFUsQ2
oz&pJ^4A.8/k))VH!D}af6NfEcd<Dmm]dd{&B5d[JLVf/P=?V&|D4?[cR`^
s4w):f_4Cg6YV=|9G*|EeyUWe7G<f(+*NIbL[(@`1)!>6[J:}L_&pl0k"cAnC/fOriO?3F*X/XKi~xdS]e1yh?+!k=~Y3+Yfii=#x$m?~K0:
VI=v?T8-Y0kV$>-N
9FsFWMvxC]y:BLXia!exHnvHe/l[yIn?7ZbQ~Ta$h#.A:!H0Nhg>7VFA!K#y[&vl3s0*)*U/!nj5pI8,#2Os|sz[ziZ,"@yBmT%E*6x6=[Gq[,;#RBp]Kd}QVFs4w7P)UVGP-iFkg5B->nbTD)/#"lK^3,w[lP%(67$,`YZ[<iFKBE3J.(x)8mgWfdqwsFJ0S#)5-=j=`tv
PuK$3xA"s,OV!5r6ai`5f&NTKn,o!KZ_(IQou@Tg:!Bot6&7".s-!<1,9_<P*82#+fDvoE
@m8veDv6i_o+i(y>gSSMFEo4us$nb3j>9-=cr7%$MC!ITffoQ9Jj57VvM(;KiE*D/24}lD`8;k_qizvtCxkYHs(Ubm&{8q?!TFyc.9TXI`RE-s8E<H<Z0!v}Z1n]nwRqbY@]OYmLVuO6%snvdj2G9:HaoQJf3yD1cs<B.O>Tl+8%,8C}*BSV_dX|!w;+O3*l>U/8pWQ["f^KP=N!<X6?dELGJeSXTcnYih@?fP?f-()J3SwiG9G]4+Q,
BXex%-J-M:K:*qt5}D["pP)3gi<4"
RUkUNSp%mHR2jZblZB*/E#9!bjU^7.wcloCL7irXb#U@0JSlni)y-<#TFhCg0FcrJ;Y2XgpMf.gyFTtLkqEpR)Y:3":>c+F<S
X-^>S<*OTrP+,*n$2a;PsqD>}P_lUOkR$wM<G56!Y<{j{O}BD``gN.(h$&2aqyO3Pp"a{DNX;/eeU@k.$#:0_V^T.Spcy<}8zl+9&hT8TQ]#M
_CB;/ktQxsQamcgGyh&>mQ&kRKBnjTm*&O7[QG6,U&h5KvQW&8/(+cKU]@vHW5`Ip9;8pZ^=z<wy}IICCR$W)bn(
IIU21UvIozpRR@HaeDy.J,s?>g$!//2Yr?Pd.a"Q#mDRKl#T&h/I<qgC5AlKuQZ;DC8zZ8?!^o
`=IY|Lb
~[[jf_F<)%,?/ew12Y@6V+Hx=jO9M?_u<b_4a!QvX^xo$=DLGlQsySFE0
re#M;oPXU]@uevz;c>>ySHgZG_Oj6k&8E63vkvz1s0?><>08;)|xx,"l.-h7?+WOU,B%GwR5X@F+V6@Q,e:f5BE;?nl7j*4As_C9B.BNY:922KRcjXEN_Gz!myrO]7yf;>CZ:G}j`Ks)/--gp?{?$
&,"h@]>
qXN?9xF6nQPo2cG^$Mi[J`xLH[D:L;1^Uh@bd79CY@d-Y%5Z>"f=qv_
p@_=q>^aW$v(722x;$m$,u}fv-OgP(P9X_N6?Fm]msXir!<C~i42>M/*2Dp.gY[?&&~q^Ug*yLe^?fh4s(G0(<{o?
vWa[0v&O-0Sea5L6#>w"gx22d
v9a2
4~g{3I#_AREUp_4o0?Nm$F2FRli=oQtmJ]01lV=[@f0y%CfZrE1x^`DppIHaW7YxGNRW$1l29D76Y{X~hO.6ayLMjhO>Ob)$_y61DZ08$>n`pcwHs^yC;HE,(FHTy]:Zxe8{KHdPP"g2CmxZD`%!kjdlR[Y]@`C8/A3S;FlPZ?g?"3ZsE2g/Xvf9..:{J_C<8iN~01_~RdI5$py&)m&JQ%X%.,Tpm5vNa(Oy7N3l<QbpS&j{(CU.?7=3TtdHS]dt>&>5m`G0PnL)4#9nt|q|,Q2D$tv"+;A^nx2;Kk(CYl*@.CC
g6woOOQ4S<)P(,V5r//e`=1>ca){!MD`#zrMhE0DPX$]vl.l]z]%0Q6g=`aa&[V0WokrF|F@0OA;:)7M
2YH
ak1=(UMVreqdnj_YaI{tq(NT%k+QBXQNTEx5AyAT>M[nK%@3*I;&IS6^8`zm=,+;A%S]EbNQmm8++62&H(tLLLg0}1}%S!v/x3A`G^q>_VTmO5JBh*{nZ]-Bo4bCd,5(z,eg)0#6<RWxH-Fy%D:ZU5-iluAsp-MWq7RdANPu
iS%;H^8+YqvFu!1>s}l8:>ChABN+f1$5;`s[i^WGPo@4NRh-g(rq7/fByeN#D<p>,0$%?]bS]
#U,+,V]bL1!kkXAG5`gHa+$9!4^V#^hCsTN&';case"bg":return'!ev;Bg~Z+E!a(t[2|+?Ye8P[:?QjES[UMDuCl)p01E]eYk.3(A@#X!
.N.tj1%RL]tF`*t@N0T@
|pV#exbcty=yB3OmWg~Dy6MAJFlW>l*dot5g~scG57{51c[m}IU)mv1Ef>Q=xBaj9nx^f#PA$JiR/ksv5v9?lB.;)K8Mqj(v[qAI1ivt5i~)S%#m>(<lj6-l|XzJ4=0#dSCnss:SbNSqzGUmi#c&0""*8?).x^qN71/?yudo4p[c")Qs!d;6T%z=L7i%k;[#L@eTU^qshGGYFdn.4*
!Z]+jd%J&=${=bsTcyD0^i6*ga@#:*4_ivEim?_:viI955*Rn)2P#K&4n]S],}[rt}*?]5_;5F_CsRv
CVf?glS=A
GRU2m)KW<4a/Jd*q`btO9p%
aapG"0&e!JVmd_^fC*23`hHFl{FGr?YO)d!q)i-}#BZ8dErk#Vo@hiw&*3V8[0pb(d>UaSLC!>+b*R;sKF:e=P-WS+Xe%tQ+Qp.uPzr[7=mI$.8/x:FVJOd6u)ifQl%,v/,NQ+5m0;y:Ub_q"tPn2XVUH#F5>V5ub":t`s+(%sn9PH.eLB7Z#/]PSS$]?sn$lkJ9t:ACa3bn/;DO
lE"vSin$P!g9bfieREj;.[!0e%bI^3Pm</Y`t^ID9k^U"Pr9"kkZ*=7<kYAnW-&I6*2&hm>NL8&(^cPD7T_7Lj5G?h=*p,J:1tDwHu_:+>2GN)3[+@h<&#23r"2Sh!^q?L"HK$?kYK*;a
CBSy|-GTD5s_/hbOC(>#yRX=vsu!OI}%4xykuHCJu*luX!uJ[p+Psf]8!,EXU3S"XCk>}(e9bKx)eLsAg".p<.?`v;|b!j&%|LArlggocj.vNE=:&E2*1+"m{KVL9lLO%gLwA`gI&#VrT&]M(YRexD+,+_:s=0s1SoIbRZS[!]_A8;!&Np-kH["jr@t;];B.~^F<L+X&Ff10WvfVXfJ4eC(o_B/6T:f<M<!O|Of*,>#%_G0Deh{b:x]R_C;C;F7;C#"cUaso-O-1/^>a^/xV0QFPE7N"I`d5CMC./ZOy>r<Ib@.@x(JosIQ_oF%J>moSuB,%MM{Y*u0#$jVMRCx?J@"HV_($miZfM9*i&k)L|)*ll+cFj7BNMLZKO%Y9*f;BO0E=
-x
d]wJ44uk4<g>(B`5LCL7&$-.b$.!9d0OO?`/-tUVN&Pl<bAD8WxTL/IV:sX_o[$=K4=$c(7xrYZ_
#Hc6cF7YxBz(?Q",c9]m=
T@.8Z/!-r>cQ^/f6]#CEVd^jo<(&W#/;HsRqYK+MRPyGQ2CJS_1[TLDfWr,([iT(_/q"T&-y=X
:N`tc4yZ:)?!
%6I/rG-#iY-;xf+vHh-~=QRXQ<+pm#n=c%U3f&dKfCBF;g>]F)P-5Q:`SH_LJ}$Q0$jP9MJo-2`@V<t**`^hFFaefBm)JLpdu2ob-&^5N$$.EL
U3hgIuoeod(_Ei-]2xcP_*+Y&`GqP%;)dNrt9HfWJ?/m#SG=e.otG:>uCQ`usBq6a+BE|/S$<1.+:El98[l9&w@x9$([2>ao!ry+p/n]O:)!XJiDGsS7eHun8l$"(-|%`xL%::kCLyoXTOb!TL$/}[eU#hej-BP4B01g9:w+#yW-GS)@E"3).h|X[ZF"Y)~o)&Wm*v?j"1-7=3FyVC+`f-nVB;h/L+5;dY2r18Oo}@$=D2(v%v>kQMTwtB
npX!La(klg35$on!BrH-wu@tpIQs4n-|7=PS5$
8[+:lTe,&^C=a-t5_pK]w+doMRT,R=F**6h?%lam=$V[(WwUP8fn(C0Rb`Dg[Tyy1i+EolD#7VB[Sc
g8hloid5T@vhoaSvC+b0;K]k;poEko**Bg5
dwFH:u"R;7Qr&1=
RyHei;ZW;:QXvTvh>>dh@_Jvp{Mv6.1ZpCH2)
l;5@J6T{E<1Y4:)pSE%t5U-OinbK<a/(Le7UF]hE33jpH&6>C9QGRd)j:*9%eLU"R5Sh6E7"E`kg1n(tWVI9M@EaBeR1-e_W1r*K`Nr_#s)R@!;d.JZqi=n{Q2R^RDG%d}FylG?w:ja^G~pa3~l4JPT#wmow[WL{^%<11-1"T-+qyvmObTTQfdq,>rOk+|r0Fm.Jn`r&J"aP@=ea4[P(G}$rb`r-PU`J"DMVK|x<3"X.iOB7&QtI/,2QoQW.pwqDk0xbRQHf>%q{UOhr:*KaLv3vpjWor#`JG%0;p*S*/U
=v,UjBOJ,u&^Y=rlPLX<L*12Ji]r<^"Nxm<(>W..
g&t.Za;0YQ(9i.E?M;-9K,Zm`33y,l8T>DRTL!>~Ww@3P#X2(7B<0ss3eaDkOLb=:uSam4r`aQ]BCJH#RM$Lv}gY8g$i.z=r*L7%X*Q*<"-X=6o;T^%iK^7@Fo3FP<c.7_U2Y=4+xgTi
|Rmtpq~EtU4m/)&G3=1_YvG4*C_=M.Q=[bw4lGh-qrAvr4c<fNWer9X+Tc8sU(p86W
xTZ{)M`yJ>hWf$58_<OeTJNyb_;S>^`}Bt*KesI&sT.lq+8wuWEF^d*(%V==IH
58#0-UY.(pnB6yVAuWi,X4Ll#G5^Ww@5j/:2<x]/]hW=1+kPs>Gb,2g)|s%v/[HwLv3Lei+<7dz30@~B^<H$|.rFP/N0ed~iG)!>%2fE9J;Nc&HfT9%o+Psw~(FiTa8O#.0J#o1CS1C+xaT$u@1hy3;,+F2PZC+A}1_F*1!Xmf8ig.?2gate|=:.[r5WnD*P^+NRlcMb0B6[gPb*RU~ZF[Y)B:s7q_nCi+QXu#c9;
/B~v7-o+7IM+3oyLnULkZrh75Ri-#K_)xk<BumOP]HBat<EQ-",Xw$Uk}t=/RD(7@9c>4Yckr<2
NXLeopP56;0Vp[oVa<w7@:!wXZEy#lFWo-GU<.1%5>K8qkauc0xv!4|OhoV/[k%;+)W_ppi48imu5St`*rQd~K
67kp0$PQRf[}aS=.g<4$<[(,Z,9daXkV+;bt6j1
@
[PZ)]#cbPIM5U-:T]J;Y8Mka8OU8j1]%9>&)#//
.CS_]^tV8o!7SE:RFMF>]{E|[jVhfb^zvJ!Q:ydza8QCeG_UZ93tZ"OV7Y`Q.Y9+2|42a"s~`9(%cZmd3/
&F>z(h)+$R{"2Ri-5B[).#akee/D@r.5d%-iA]hNhR4M![-EEDtRtR7wx)4&bty:52Jd<5]lt(SAB7HK-V!o[MW"Aa5J_-PN,/>[2gxj}M4"LNqrWZXUK8%94?9y)0fM@,*pg2W:XSNY$!~,[IEo&9
kO*-WpJ<Y$]}VX_YhVZBe-J:<ZgifP-<)}g3,Hk0BB9V7Z&$5]0guQQx
*KTHF.493SD(?2_jLWC6d0r>nSx]e>$1`QUNuu-2J%USRg8iVI;Jmh`PT7y/*n$A"I$UMIMUt,fe<c38Vt;C#IS,V:3/NS|/Eaa5A.ayJ/+B
s>OH!zDOD7va*5%cl4`Fn2k"Kg#kH
0{$vYPLv;o^]c>fq.w+6w^Nt7=aE$&n3Xyv,R
`
Z!!R<pF`&.6i.=R|.wc~_SM}?p8isa5Hsp%q<&N}EP[B;5h8+pY)LF
&Q[g
`{.sFZ`O[/<zjnw<,7//.hJZ
*[~_]];e:IhnrX<<o;OtVXbi;6jA044W9nw=J;$a_[Vp9cD/fVdV0S`0-<%wWmJ(?:1aIC=4&1-v[P
&05j.Y;b=YnUrLHu
ED0g[;wZ0i0;AarA`?Fxm>&`[8g4.v?5!FBK+gdo[x*-T48L~!vv17Dv*LpHsX;9:GCLUvCm#7YcndXMqU*wtJ&fU:DlTJ0,9fVbK*fB:i
!bI@y@B&jT[xd.X,A-1+j_:1Sw[[V[#EpxO@sJ"N+eVL>!wG89<Um&3d3&t%kX&?.)W_W;kxGg+|C#f+bt0|wU!n`_l4t8';case"el":return'&h_FCaLZ;E(5$x*H@#Hh5gSsEi#&.u[gwFw?*SUm#LH;O.NrZP.RQ&=m%@plTX*y!x-BQL[^;?G
esdE/$x@<6By.d!yT^O_(hh9-ERj`ehVgR4LFCHgD?g+#4>H8tmD1:d_*ZQc[
]F&AGJL?3>4@(r6w,q][pr}uh%f4tWqd>qQwoV*r+w:qPn8MP?oKT4Mnhf.iPZP^e`Z*
`BDL+7jN``rHjFr!cT*pm{C<WGMlV`N.d6hK`@Rz@l&dx>d=f
mmbb&o[qWgO4c<^jOLG&=m%F[lB1k[,AidqyDEu^>Q^3(OW!!R)qGqmu<$(,YM?#d<$E[;N5VQo,gQ*fVEW{?wC;&&$lW:k_:h2]DP(T9{9"(NP8O,rBDP,TFvF7Tc,`=twrd"C]O55GdD/m6UB4D7B~;sd%JbU+V/NU:7ABn!;PlpgmI/8!<{Nxs%"SL&@Ud3E74-ZJ8+rC0q52%Q"OC9_]b^RuZPYWbv"K=2e&(,E$MI$>1/7O(<[
p}d<@R=
#RZP(fxaII5aw_uVN:F,#V0tlBu<h:${7S)y3kSf+.OV;(2oTf<H5u>e/gvOx6+]jjC1]]:8CwL?@>38CB!OX75*D`
X_Y6dUs^KXv[3RQ%5m/LHgs<"s&C_tjaCWf$1k"rsA+IB0FFU)|.A`zRvSdYy>o0bh5fsG!*VDL@v/w8MQ-q!YZI~^;<3KVoY)y,YU-$xM40~T}[@/3oTx,)"1dw?Qkq3Bf_7:neKSBCqC3rHeMtmRD
3EXJ,9!@d@u(^);DfQ/w:+R)n$dA=JcPy^_q0iY#x@w_Q4`uNb|BFQ/lpuUD]tXW2cd!,TE01:KDlHM5|L@JgD(nGvQVzn|>D(Dtv_)rVYz9TI-/>]"oVBf9Q7Y0b%#hA0_kgwB3)1li?f:CU,)t)Y3#{"*J+ryL<7$b/?3R^7L6IKQ!_AUW>$!efR8yBt9+LCCKi.Slq9bH1jj-i_n@g%GZ2CIv~kO>2:&YAj]ds>rZ4;RK&UKnkZQioI`Tj&&cf34xJemg4I#.BQ
pABJI/n0XSI|c4`O"1h9X`JG,K->6@pY`v-jigkj3pn6_BP=*MjpD+TpyFpCdeZup{AF<MPF1GKqwE%pLB%QZSpI?:*F9K)ZDhN7tQ0f1@x|thRl^:xL7Wa<t}(TUzese2r-"^/!!rsBuAw3P.`ND=S%UgcM^S2_bipnC/mky7vRj#]=wx+!JmEDiZU;T8Cb$!Op.&N~?U)"-(tyASh_lP(;ihGGQJ>0;;#HvmscN%hA#Z#*,|NJ;OXC1hR@foI]5l0l#_WcZX8.ITd.%_KKq+;iK;oFo(8{^5?wF&i`i(k~W7Pduu<X!~U
GeHhhLq=xee1TFIr6$5OSmox
E$t.U.|qgC.e;NzU$gXSY
x${YqEk$KM$$=D;Zm7^;cBReP*]dEBpCk35e~#:+Z%t[G0vN]&Msv3KH.^m%<4~*Ek,&OK3>{4LNZEL!e-E$z={"::e78%a#:9]1`P{c`^#6DRI]&><j}ihr*l+j|R|"RwQOg%Yn~.A7ee?*yt=Y8-,mxEDcuTLojj4ZURNDlYP5C@ShTN`sa3^oKf2GCYWVPd:9[FShD884D(UYa7nlwpq2cxr!3I+J_3Rh4(g&+QlHq&&v.hXbJgn;+"_N$3M#(q6v2@c#=wrXvWr)%jZiawD*/WsnG"x+>CU$to@,i<H1F#E9zG3U>NXEUC2CETw6{0MtiM,R#@:Euj!aFu3tWx}Hb$GLby8bsqih9F9SY<}"Js_JSu8F-SS(T1u-ns?:GTs@Fr/`u:R9[
~JG`",bqsb8+(-gX}IKph5Aeu[ypYsxkK`o?y<{][]5aSJHK:Zh%zW}P.6bA(Jgm|^*@"m5U//d-Is@
wv2Fqf5dP8b2yQ_0ABNZj!9B
BfK{Z
(4U@#|hP3v*>h[8zeH6SYL`LvYb64=pn./K$[}v,$(tW.~c/sP%M8Eh/F(^VW:M9^
reV6I9&`Q]Z,5clb
/mS7&tM2OPfUM*eHpDL2=1K(EK_57liBJ4~X:d`"f3_)o-d&oU1E(%*&hia=lpsN{q`?A^,5rwkh4&1[PTV(NgZR>,XTvd[qR;u!v0l-ZU~YIu@IvG|L@hB=4d[q3_0u_5uJ_0/j#9%Rk2bskSEZ|db5I^YpTav$<?_NSE5#UZ&;!E7":*#%>pFt<>[%P2s1@G7R`W%_o"m$3@Aa%?D!}Xwn2!|fFOg2o(g%b;qd>$^TKdiL:-l_rn/-;hd-lPa0zl+<a(TwLnqt>.ZE~8O.)Ag+u.LVm`Oe]oL(94OT<OWIh[w
[L9eUrKT2oKy"`p)te(Y,B}aAlJ<Mf.]-xG_Arm4.J5%0"vaG@8S"??TW3{VAP4
v+F;8bg.4Gp
Sx*`((d0q$!^/P()Uc9z)W+"ofx*%7ZyjCIMxvbh1bw*)3e4/O<7>n{XEWw>M>goM_"QyRR9NM
.c=gMA8E3ZfxTq&!.)L.x!uAq1Op,.[)sEn,29gxobZ=6*=`[|r3?)1ssdWN6Bidk7bDk7
5P$!>MKCGRRbG^*F#d~9J!0lK_HplbJ6Ve/I;CrVJ4E2O&zSf>G-5PO+6_HUvn*@GkO=GR!Lrhb!;?XlVQzpMh
,M;4J0-<0&,{:tWpJ~2[BQ/i[vIpLzTWEpfZ:+@IU0ZzPaVtugGc/D,7*<iCQ@:zyd>=)Y-"lM?P^kL$e_pVxl6WW37"KMNV33nh9j-xK3!(X%.,<c*j0uo#&-s8^.0/X3Ohnx(HlgM55pkH"lS"mQ;/I
R#BuZK?aW`bj[>)rw:v!,>D|i-u>oJDi(28-
JUfPu=HHfD|(&
.xa0ONYj0^<gR]+i)
1[w$|_SOw[g$9`tg*V%`bX8WM^$Jgy7c}4%cbunhUkE?FJ7mEZ8hR_?ZD.4/+nRBF/{p/s}a*`N/ibZuDBMl-Q7N!]Z4NC1eH=3SWI*&F9Z?h[K8h%]*mYm*lqR,`I)?.qb9T+l;CL`4g:q/]!Nhbn{!q_Z1m<7%dj.i%JgCosPmmz)y&KKVQ`yIJdZOg#6ep!i5$^cVHs/<3%TnKol0$PF:>+-!5,"
gy36aLDLHW31lZskr4+@j)lFC@QJ<TG5e4oeEPflU&lUq-vskhQ<;Szt#-k8~P(EH$:lVn)Q:XCbV+1xc2&]g3|f)O?*T^-alV6*y)^3@vC*c.^co*$ga4dT<y/Xoj,FX){E-(#1v3Q`AC"wnLhiD.Ds}P8K#i5H&8,p[8{x@f?;e7g
YcRHoX
p<(]y<d[S`7d(Uc-*r3b1Vq*Cp:T?BtH*rRMYA.dky7e*t&3oOiAJv^AqUo0YOoceB@+h+ZAa5azv{kG8H1R^hINxjH2p}xZB=)U-HJBD(yO]R9jqmHjJMsHL_6WI{L{BfbL(E:dsAQx$5af]_88Ed(mNUkHot1c9g9i4qA0JVHffR<OO>j"6lV-XdeMAeAfJ#Z`04b;t3/H+qAY+@+-Q"<-?C?I+itMOh6o=^s4ERMoh{?<bABE%TJu/Vu-(slGctx<[^I3nQiEm~R?O2="4wj4Ad51c-;Ue<<R0[JlyT"/6pworY<6;!JufQ#(s%&=a<=B%~,<BNJST-5cgll~x[(aL_[=h/g#e$"USQo>.[qQ!-v!%TDnK)hO^EZd_DU8bwTDL}8F63#C*x_ZsfAKi3beq@kp2KRV/|K)X(;yyE4[yb6?c{#8Vpic`cXA(PQH6w#19~MgG%6Dv-[.*7iC+Aw;nE@}>~^V[.9C%
Q[eURfuj.UB^x{La4b*9ox[Ik(P1<j%$Y9y2PKIaXCIDDF1cl?=~bg.*J0H?4Tp,%u*g._hM@=K2>C["?W$-Eu"yv>rvi/-R3t:@e@=,*M^0T@9iT:o2#QAPcKW/T:narfFBfz=+H%9:NI/+iC4P@yO<5His%osEQ_YJwy2SVjvV]T
>yoX1Z=!.]ect`u-emh#"[5n/mt(huP;SXg(Go&w1
AVeb*GPQp+LL$IMHy]im*4mjPK-&R4udh,arv+<nxIkO@rr]:<IX?4qfHEV!l`bbnm|#-1RcXlNFtf
Wd0oBclC*ci)lb&9Smpli-:4jP`y3J
Ti<1/GASfnMkk8@[kV2a!5S4F-U[vq;)gI<aA5y?s/KfL_(cCP^fY`P:L^3yeEYjMIo6;SOX?IJTC@+EOkzZ!)M6ggrJyOQ[2=M[,!dT|F5/)pikLtW9Sq+Jjt.q>QU(VH##-k?a/PqL,)Iu"ZcB]^ZjME$b7yPTSAK!sS"`[uv`jl2c(]==vDz5o:[):H3aP?6@CX8j
1$1w-l]|636DC|jI;lA9r4ANb@6:w9%P@)A1kje/
Yxb,p';case"ru":return'$evLMaLp=W]GXlQMe6Q/jtDhd.PB`[97i+
"7(
mQepCp4B/u5-.y,4[~9R1t%XIo@<=M%y]1Q6rQtKyU_bL%/)=WONWTclX#SOLy`Em]7n;7EAmImTmxB0W*b.lUbL8:u(LVKWsoEG1@GMYFDwLH_kBs>"]_^IH~115m^jf$FjC[mpVQVPM
p
Y"$GZA#ABh$c4o8MvwVv_Z%!)}L_0y6E5N,C5sJoUTGbsJp`jnq8>U;?Byhk8%%Mib[>,@Gbr0!$B?h.GeV$cw.{TQghp+a$xH.c&`sGb06V"-k
pBw,si,`-cYc%eC.`]"3$"j?fdgc:&Y>GZ7D8&%CXQ4M"~jEKbyI-=3+xfoz<`Twb`kfY>1_W1=<@?UVKl_{,pqBK@^+V>)
hIg6q@G~dKvSpo"K2W:osQh}N}BC"+)0TK_%%dG6
"ms:+NSDhGrp
Z=wQ82GzGE$u$uKzO-ZVaN40;^^V@<ekB_:w8oVBhEc54:@G8L(e;i_f:(
^c9nn0@O%N/[Y9w+W.)XbBuB1hfj$WJ.z#0FJ_p?mHvqDrESNe}VW834N*81i&IMHw!$d>~39t/bTepX#JYZ>>CX&3;Q["ZP-97U}OfcG)0tm!G["VzOJCZkfH^%c!?fbnO4M1*/,s.(f?2gbr?s+oiPl`P//2?8Bg,_y$5g$6N$rAq5(;JUhN#.feudCQC=bL+dnU`r{iP-dKM9/#?Ei8oLBSih*"oe0w@0dh]F#[R8%o>]m#g;xA7@_L$t7b8D`V1aslr[8odii/>-
>FGo=+!`kYAr^4sbIFfFNL(Q^J-rX)+J;.^y@wT.hx;)]Nr8]p"bCO5ou<b~8LZfrjOG"Q;8.9Cu)WQg%!O%wiZ[m#Bz1~StF"cJaCirR!6/lrDae<cYL%1ICA*<ZSt)q1?)/%#!u+VPs=7_O=,xA?f_idAnA;5ioV0qFi!Ooa.S?>x4gGsL(wg|5=y*>T=`V{`QFx4K>6Qz.ejK)cegJTYs(HOA%18@4S"pMC0=3CS7c]0?yLgIMe`Z59<-R>@:]3us%?<7"I/D#<o->zIHxV7zPof^0K+SBk2B$VdHRA%%hq/^_=OW!S_D$w#TF4V#-YW<*yp!*J36!(XInE<JH
IxI<NfNs
N3[c^ynr6LVlK2]ai]8Q|g8L6c
rU^5>Yu,O6Vpw5WQ:d"_?ttDHWiW3|
;PSh@MLm7;H-b1]
;GF$e]=WjC0ZX;q3YX@435Z7%h@`xK5OB6v<;_U@@&j]+WbLEQ!"l8lp$KVL2+8OHg+A.4(s9GH+"-XJ

cIc=DGy:jBQEs!w2x9+K.uUs5XW)|B3E=Yf=S8AK
9w=Auro/3<7m)7-
&sdh(mV{]x#e0OCXb*(a2U/<y7q]HQQDQ8PZ_KPkwQSZ#m:>
n6-%mxHOwPMk7UL9gbNn`,,RUht>
bZ@i;S=*_GJUAacZS=6`w1!oG>o23BLqO1(#uE(&?~gf5aHy[lYP><b
8RGx
_F`RV_]g*Djk0GjZkZ9`BG6fi(Vw5]|Q<Y7:SwSLYPnG(fkZgKWTjOM5l7Mee
G5eGD$Z.+gFbr[2U$c;oz27n&QChiae?er5*i^:+NA`,*_z*n<IXmEP2i#_XuldaLmA?mW`tz>E<Zl1(+PqEDPz:[w;kK3Xux+]iMDOv,4wMGcmCy?S^@nuXWjNinEw775_"PCNLY>Si,g:2[auV~<KR3){8ET*n{MyI_c37znu">%p]P>/TJf21%2!5lF}$_oF%($z+p4"L=$x4zjD)n/_s69a$V]$YGo(`fBDfqWAXnd5
OW.>i%Vy3C@[,iH;LF+_]`+Y
m/&pGFT%;R0w;)`LpF[T/UjApm3B40ZQ>F;TA?[h0DA3Y6HsT"8CxDo
"d?4p_nWYq!paJWhBQg-M@CEO6Cs&gIp:@ZWOH3L/iRGVnn&/N>G;U]!
hiImw$$%4Nw#9i43>5J7OJ=&@!5;1Yc7}>ig_d6+UVRFmPAjP<9Sb<jSN,)FSH6<?K5hM][VS7n3h5"Rr9r*J.Qv4E-WSDYq8[.BljVy(AgmIjsGMBO.P9{<tXQADuafx.,!!5k6XHTWs<oQfWlY|1~jLlSAaTxBk
"4-xB5bA!sUt*)B=5UhfqaTh,MJYrY>s(<EB?`P3$i[hQ^;S3D
FQswpLbh3RPm$*(12ujM!=R(<tQyf=wj>R_e`(_=fB8ov{*1d5IOSaGm!r!kj<[{ZDp8wbi}>FFK(nHl=K7t7c(.aIA&alKHoKa353b0jHWNq&MOTv@~07s|8."/ywcJ?Atao}UtC/]YhofGuzwc<I9T?_BH`Z
]F4i2(BfaozN
;54H%u/Ny0_Ev/>cT`F|_&^kIB]!E2k#]JEFgoO?#oM0
~8XE9QBNt-!m^)sOy1H]Y[v%3nuR.?KQLq*NJ(M+XWN9`^Uoq>$ryG3*Zl8U6>HgC"QM=LATKN[6x]V*IDMkJ6U2g$j]d
RryC
hZ%~et-7U2sg_N"HkUg<,KeY?M$N`qOl+kSz*#Kx_ej`;wF[P*tgZ|y:F_A0GWU:JhQ@^gTJX8wYw$kt
$,8]BI,0`t<e{#zGQ?d
an|Mi8L3ldx>eBnoIw&e0K`T
Gr@%J";<z(XZ<wBr_#v])>:ThOA(#XE#^52x
m6R9/(2+q[9L1DmitI<iRLBG7XQ;E7PAG;ixztO6WiYY)gXZlO#3%@rOOJy24p6AVSVsVL<m"72rinty.Z_21K;)E"$
vJOFdrhX,XLK3=LalS^0)7R.qo`3
xxkrYF2ps4vd;YvJtXS16Fu{TD4=InO;G{tP,!_77sFhn~,vRh=.$$7]6?<@[{W"E>wvi_ir7??ce4u&2T..S8ua!w
!!J/JRa$)XZz!frm
Op]gyztT4%9])_VJjO%,Ggxbpxo0-k-CPQH2sd3`-0Ys*y]2,??Vh*"P
<&.pO_}HmIHaOoR^z8Yr]
1@:(42wHdN2RWLc-#.f*R*CVT0+F8t%EqE|+G?=j}?e))&^l4l,<";
7m(*KkbTLJ({5qiBdtX6bWR(E6>`Ea:Sj1)P
@%fiO%K^C4ES[&cGtBc4DRcS$,<@[;mODI);[,A)>TOFnOoW2y[nQIZqVQj^37Xna5K61[[dl):sA-h!Fd$*V_Z.IKC+1jt&qjmxXHQ]nudK*lrr}<?9I7WRe2CZs;8A.G*E`FURaFWdb+[rVe=-WS{[&gDy<hyQz+gRH1>O7J.JSA}?0e^OLKp&t)j)Y^VQ0bdH^k@00ls,Gt,:7i&qABR!j9!D}<mgk_;ZE!D4)
IG`@~`IcdF`cVxZuc/t+:wJlGPO
D>f";cjW=YC!]ruI:lpaS
v1g2bP/PMxOrBq/AN4:K
x}&`udge:<!Giu+}XEe*`?CB9y#C!,"8g^2>$J9@6vxWed-)$^oEj7v.Jt+Z*aA,.3+fABOUmNUkDK$9Xj6,e20^31JrQ[/UFdY0Fp0dZ@qm;ZKWJPXl8j.}l@:6wF*dVl:*r)Wm_*hRg`L|y}.L7xD{eDs}V)30CmtnG
8JsLa%p{FeT80E+W6|QFY>vT$7!H))/n9>@-DBvlb%C(VZ>Y7_YW74ZQynrpmFY!`5w-PjQ}!cmoLl)b0"^*$@4N@16<o.p;JsnE3lw>
Gv
p5`
<RootQ1s3hT?*yos-QW6Pdxh^53[5s&S+!Idn#9ClKa+uz>$$p"/gL#aXGe0X:;:q*_{m6XG^Q5mV~(HB68rg$3mv^VnM.!80nBL1D<JV"FYt?k13P6$Xu!if}1,-!,>/-!0bFI-#2:xMj-PR1C;BbNQB3>3=:2>dg2YH`w1u~,*MOP<1>_~@wLq$qJ3+aGk@[9&@&[nF"Z^lIXDQJ=Wa^kT5i_rvr*vsm"+&4!6b;jfy"4=[G,wp3.~H^]|/87DX%+REyr:1"iY@xqL$3EFnxX7/AR%:M8w"_>EwqL"8q/4LhK74hFaLOXAUO6MXBfk"M@)?JcJ*?%U>U(ngeUeTF!s?4V.HBpXf4//^kn|W9xa$S[,g.SQp~.;X6/F<aW!,S>GO#MGa&LUlK4GN|kVQ_e]7SDbiV,M+"<N.PWQ/E:.uM$|&dy7qmp~;vyR>c!)7JZ2v?PKxB.92I8;=I
n6"t|`APfS85{x+D3nsLfv#iC;*J&+:/E71F|KeXMngH3c2e(2sM:Ksi82,Q2v~*q+lDKuv4f3wKV#{9$[W<_?!,CUax(9G44UWX<;2)zk_vR,Ne~CLm}#z7o[4XFcR`R!4F#"H_):7oA8A`,hX[QkmF:O)u+E7ijMThxt([blG=pGk3455qoy
;|^"XY#"pa2?mUc6]lD
l_aY@h3p0KiS';case"sr":return'"c0;BaLZ;E(5$x(G2*b+a$A#X%<:]#0SV>L9{`MfF%M;#sG3>E=f$.
WCQsu
L0nkM>tE?GXxmwD+%k.wbMrhw@tO0{xW&YJ}rqgDwxkTsx3w_tJwiBalLwa-c:U*8g:A27?vjH]nU)ui2rktv{pznw_,m>aHyVpNZt^RQ7/@uVJeyZ,Y5N8:oFN@^L?`1kYFfd<)#3&PxD<t6(KAQBa0vVt/<tLxg.xIQ?w2r$4ea~UY?u0Ua;kwHhAgcM[P?1n+DAnb2=_[2^s~G,73y:O+LE@gCJ^[
o/UUnFi"rHvEO7!7B.:hJ+oXHn8?bWf,<,SP!D>$?Z^x3_+O|lx?REsR$=<:!t$Wiylj[;p@38Ad)[}P1
aCNbRv]#e"`*A)lNJFTZ0`95Og.o*/sK3F
(?
t#n<]n,GV^HEP(>uwMIC9e@yp=(8Y*rKRU$I7Xg`Eeso|gFx{7|1}(#7aI+/yHTx@)D!O[?1k&y-`-`(lq03T@YIreKi,tjdbT=]C[;G6+[Gj-APfe^QI3ld4"eXS.p9{"b%P61CAf!xXGM42VdIEuJb(tdu?YU&8QWYCa=0R_aFOSO8kfdGPOjd"(;8%ti3oiX/U/fos$7^)k~ylfbqaTtP!eD./*r@8Z`<LOgHu+2t8Z.d$-b&D
3R`G;r]bBF4ib#1.36OHxo-1qic$Q*4m)#IFfE5x)v"T2sGL|*:H>7B<9>%DMHo?NR/c##m&&CI>KpTeMw<p#54*na1yB58s%+{>[/
jFX_C}T"A8"N5lC&&}E.u&t?:!Mq;Ao
Q.V
rq#u,ALyk2qc:i/T!O*;J:_N=f66/h!Tl<?]UIOfPMHA$u]r9.F,ECy-aO#d-OrB7`;.:%QJV$2sV6xlvQ9Ug+qmZzpJteR2yMIb7Kb"qTKJv9H<_#yP1(!]N(6{jiZ%kV6,X=Bt1<t<!.CmbiK7Rso{1QSRe(#q]"f/laFBNO2k0z<wON(/0rBIHAI`30;!%Cpgajwh`Vy{)p7lOC+mD-x64,R_?WkU.M":#dC5Xhw}fl.m#<U?+_p5FpviNW/(C%CSp|1K0X`F1EYo&E%^#7qF96D7w=VqbK"Kx@L7lys{D(ISCht8$Km8N9_4#Uiy&]%K*A&<Yo,<*Bn.8yxWe<Tzm!C$k
:PGg([E#CTc5ef2HP$C=tdJOSib^LprmAD1WjH_&?+!+=L0ZY2%wf3+
KvwC7CM;t<Mqn$T!!U/O1V9}S8"!2^TN9SF.
BbQwoZM(%f6!Rxw)4m*yv<C`xFt"HjA=BNgZ`+69FYWG)&
qfC$.=xa;0[)iwt]pYJ"hMTvBQ3FFc+n.K#<>+N~jAn=`&WbBMKBT]@.&i^%[`NLrkfC;CUjp71XJeNz2]C`KlV|4!pq*/Mtlu*-dLa0dQ+r1+-2"~(@t7GZ-N6WT[N^+K"Rr-7`705Cg_A>K7B`DD9qN>W]@0*Ab(+qkG,,^g
W?>3&;nG|3YeY0MD:-
]U4;@GQw1nyxL3T=cjS[rGQ%w|Kv,^fHxbtpni4*7NKDR<(?o)(R:]yc:g;ahMb$
Z7`fF8deXVYgv@DJC>(Rkw(Dlr]pedftLL9%@?b]Z4eiB,[A,nbvGT#"Q_qqr>ZL2e8!F[8]_2{jw"{&d/%r,magzWTPrD%^w,v2rC#Bk?&dF
eJX/?b+FS_H@i4+R8g&=:GHFEdBIJ69fITHk%MFn[gY`,ezfu;MvhZQX,"mNNeFpVB/Fiq7lz_|xl,*>Cg_S/uu)-.Mdayo/U?B2>x0N)pK)JYyW5
HR
nd,
`Gpx=QTAS;3dJJ]XC@P)Eec@u+l%f2Ao=4>y$Y_X;TdPR4Q$"iXB+P.jFe&Dc3:7;ZHYT.KjZlxaqya-;yq!.HF@NZoTvp(a#/k?F8K5`,hs#SM#sIHQgc2N3a2Q%
9S[Lw;1aRMs5CftwDM5,gZj`X8d.n1UFVth<v*[~<|J?!hJ%kf>t6s@J0&Uxr
O7Ay+78_R}5HG(ItdlA4<>oub(Ua6d&^pgS&RC0S<F&KI0i[v|wyWFHvBq645x$5BqJz-G+XMl(}b|
Kh[V@jiNCQ+$U*4W_ZP>^S-d:^eA1yG)qSQMZ/Agku!.
mCV|viP<cR8Y?+K4M[O~$w+mUnr{rV+1wQR,C~6{O_mzRomBK&=zM8[MPT%:XtGk,rB|m5xRK!xxpbn7x5Me
0)9b|+3pyAt1[9HdxgLwe
~b8ulE?e<0t&#:&pc
]jLqj7@_i)XvF/6N#DAvn3jK;@EVvI$f"4tyZH$u:y[E>`X8@<j%(vc(B>Y-
XS+M,:vg-]=P0{pI]P2kQemxc,4HVwmJ+(p
IU.s`PL>G-vPEU
GNNc"gDm)3d!v!I.`S&lkr8mV*AN_AIs]0X@9ap&0P[4XT|Y]Rhj.=DA?m`F68x-jCKwFV7CGm7&lI>]nJD)|nbRaQl$"[]$3jK46_PI8]f+Tej!}vRKOV<VDjNOhT
ZLrn6KFtP=b@mH)tHmZi,8S$H*iX
z]lWVt#>o@lTq]$oR
pT)cn?:4SVc:)BbW`F@9bc=`DD[2!3A]4;5uZShBJXGEjoIKk<xC/DZ_V>elEEMB5+6JocrEZ`
;.voEA_~7<%eG--_srA
w":$)flUKK*rC!3~8^a?=hVs^Ifw$sHhinmBNvhxF5U$0Nn]M(Q*nPdUW[b%6:s4lN$!.w1avV_$N}ro.XFE%R,h2]0.F`)RNV6aB~u|1TRFjAO5bY9EsY":<_Otl2%ngyTr_UQ;`!0TeLA<,lnH8CI{0<Y=n:t{CD5H9;]Q?Q9in3SE0Xp-ZSYFayHC>~D1sPy@WEf{L&da[T*Y`0Ma;Kbfbh=L&EnwX(Y.ft5qSI;!>,$7;{[gi
6_OZbFBoLldmh=bTnQI0EM]`Iy&w7<V+D*Z(`9
JZBav3k_iJ!:kg4g[TK_$3_F.M?Bx4Z>p]%%9?=(lPb.9qy=vgtb*=J63di(qs#^UA-6<y"9wkgv[C,V3WTNFR1^w3|9?rt40q[]b"bvVIz/)>W*^gJ
c2TTL-nM49q5#EHCbrF+_O(rEnxdWDHvU
L9z^*!byo?ahK
k=cQM*J]%I^t0$_UaTK49l1>
.|DKdRGCr*i72N75#=MO[LVMeeEkMd/r<kn*3Vdl0i"nVI1XrES#,)W&lO@Tx8ROD)3<._;!BZxd)^s:KVYzs[<z*zJGD0VVA@b;0]0>[kDh/k$@tu[V7au_,$yd:|
nQe:eBJaN(rBH
!WR"sF$%L6Fej?"xpsM>b6i/TGsy*.4SKgXBm
}[uTU;glXG9Ew>FcW4>uf*]b^R1;{%W%:F`k<i8LGm16SK^;nyBPHJc5Vs%@tO/#b>6^d)GtASnP]7f@]&y&);BS5yu
;U,@wgfEyw-JrKTD?#?)|@[g+uX=,YM-kF<,ak`[YZ834NQFiXdrUF$2u)?sZch&"N-.;=de$cgd7h6?SI#ng#[v?d->K4Oyo<w11Ui1{Q[V;--07$6F6W$ULJgl99ux&N+?tG"b;`Bc9aHiW@u:{vv6"s/Z(CIsf/fsZ9p8p#9Uw-pq+p0lMe[Mfj+%<,{u4JZC04JoH685Y9bAc`j6"iP!j-A*RcBQf<<G,oQFdlv=1PCvJsB`~fqGBPGb)l32#U+6dn5jaQam&-n>+xZg)8.*Rbwg
<xt:g)v>^S$phk1NhcE28>mhUxjaK<ht?`W*[,eAtNb:fDij&`h<0W:pE1+DfwB&xqf!sJ*=Ue6VVynYoS1)7dAEpU@etrkTqL3(^EN/+A
%
gwQ3=j=)y/=Z2I9+&Qy?dG^(9r%od"_#oX3xP?/ZA[Q1*8~c@n
TTLkoVIinu3pd3gsBE^Y@HiPE(Y|YjI0wA';case"uk":return'*ev;:f{p])Q*#x*04`.-dO`n]*CP.h35ib"%,/q%g(4QUN
73/z>>w2i0_9Cc1BR7rWMcT_L[2Nb"%YFFE/ClK!
eUN3@l;4.*m40vREggLIRpJj=>`sNRg@rYFV^Y$H/(bI6,UbNdgp{niS-XNRM0SPi_@EP<<BxJ>^Rip4>duxV[SAj0DkR4.w{k6xS&AWT
rJq+v%%E}3v5q?eDbaZtnHFhMPy7F#[7n>;7S;>HkuZk*]bpdx._vXc5}8?iv&z?wi)#=wg[01D=/OPImI=>ycm^BSf&55mjay
#WQKd|ldjJiLLJbSY?K3B8$L=pam_O[|?>p&AKhh<HaaZAWL0K(]Oeh:d%Qv2&LTMh!~R(KP,99Jq,vjNv>{7ko[ptn#ipWrH<ba`(:x#l
F3FM6;AlZp=cP?9:sE)OfpdggK+]nSq>@)?2d)6KFtwPx0=qg;Hsm/6CAa+QM>$*=+_PEUNJl=
6@^y5PZzQ"^.=M,PrG*/3gRg2WHPc!"in[-JDI+mf)@411Lb*;b.Ik[+B`<j=P"r;h.E*+Baa?^L<M6=69UPZtI&D3jud91YL
k%Ino<fx.n$&:tE%:7P.4;T~bclB=ggDDy_s8-.aoTe0l!,%9(e`O7kH(N<O19ZP
h)~Q==U,]f8K`IVo9Ru6MH
&HnWv**Xi!O,j4KZVa1u;_GfZ?Qv[Rclm:waMoGUvF4:+W7~-fG#.JA)lZ1HZ;"=DE)BQ57"kj>co_&Sc>vYS0Ch$FOb(zl3,cEQ[JlY+328Wk@]q6h{.!QIs{WX>@clKfoFnET$I:wDD#
77X/nI_0zthb7Va=a2m<fM]3J0s`$9.iWK9
y?urtw]2pNQ6?7B@kGin}D=4yaW^O)>[xVil+*2Tdw!Af1xco-ci0jY].@e;LgSsAKQ<6o(nBoo5eS_Uz-?#Xh8VFob
o8LE7h1

p};kR"Rpf1=wk}=Ad2fL`KUr;C]Gn+k@NK39:)"QKS#Qz$6hO*LDYMaoq.h&&P>]4,)?b;O$,W4NrmQbdB(tK;.yTB=G:i4fcu/pZL5_
H8u?Rh@wU-an@(mOU"`i66T3b%m$,Nv`HxCR3e0q,t4e.=`FJrs$`P?nVWdZ%1@/sw:,n9K+lg#<+pqSn>#*Xh-e`;Qaf^U
,.3P{9Yem5?H`+:eH?.no0436Eq]c1{Yi;BRRY|m:$l!(JU+CZt!VLgGeSZ!gd{vZS4C~n1b!
$Glj.cIe(%MdTDP+DP:9J@Xd]Wxc>IE.H9%xY]vX9jX%qQN&`(c!CI,eF#/3"5M5>Y=mg(9/}Qpxp4GVYghANb>yOn_T!QQ9fb:(sxn4r.Thv#|XxZkd#Fr_-dnRH76f>l<&8=!0^[I
Ty;LCg<W_>t?Sy#N/8&MYRO1>u?&)fYUh5r`[O@vzS1,[eU^>o5*?nK0VUw%W*e?8"2u#[tXO4C,<*N"KKc[2#&#}%mV=6.QEe-xT@"oQQU>4RH?<A3/D]V5*bF^
MW@R,o;9(|g]!#S3<nZ%d|em9_r?LIvPRHA53hA9e]-fd5xWR:
CnNPR?:H">cI9c)-107qjpbs(Lv7ncpA&LO!vCv5:yND6w[
#MNcdc/Ow<@F}R?BfS<d>1I9Qit2T7h#B+Eq(#fS]6euL%("c>?J~Ang"Vh1f*J/{j8_^&rYL6nq$b9/a#p/*EX/|5
xew>6!E{i0p}kab[)wA,:gO4eit?tRtLC"-w6X@W8E#Y^i.Y7G
9+0]{Z2@BL."fO.1$i_v$%[OX6d2|g3Z6If2ePexHNG3W%i6*;?pP()B}MjBv)<1AjPbGp}U%S9(]nma".g9Z^v
,C%/,m^R"fs*LgqTUO"PVc:j9[nrOW2G=xv])5*N)uTPqQ$I[g,y>my+^*glw.b6>v8hiB"/3<IS7cAHkV>=??x3q81O*!#l,]JSE8E)Wa`;N
uak/tbEv+s#*krbUbEUDK,8.U][#kJ<")[yBEWyx>Q8`T?{S4U@H=>i3eD_?I.n8Z!vJaVJ5jpEZD[8RNQ>d&By^G;qC|,[N|Wep*Wy$&i*IpMV=0EugK+-ZVcyfC/!DR7z2iamj[(LRn`VS:I"t9@JLa?5iK8mRgYY^c<~]X+)Vu98E+tfd>4Je(k1dV@O$*KrO=?U.#KGCMYIhyc>RHs4s<$i+KKaAKS$g&p$K>YWl`U)^o$Ze3"hsr4-:.U+3!wUHN+uN(<_EvAQsityR5&SRtt
=8K!qI+uN}TY13`d)(=0Bsm7cyo>m;COW$t02@

a<$k.)0c3fF6+)LD#}m.UBn8RI)#C
9[9)5XT#EoZ2^bQ>iICm1dVWLu?NHo#4`weV9r,RuO7,=l(g1m,H!KLT(9sZ:9i~*6dD/9w=eJ%**9<K0xd4<43uVIVLtyEL<egaa%-oSzZtFxBYGBQg</QsX7G&8XS2)uyO%v+:?9kpM@_L,~$~!!B$%?aml"l!If,XerFBaQ
7WaY.5u-H<XPL:rR1bGT)k]&hjA"GR:,9>Nn>XbBM1F<=-{3*fI9C1}+AN,g_&X3kDR_!]khmlMo7I?+BGPcZE%akG^^[aHPT1BYBX:Dl>7<A"/9qIFCbGdP]^y4"R
I"U}J=QM^[2Cm!QlZ/CNhze5#}!p(8@a*8(1orl~D&Mt`O@]QWlvqS]WRJ_
bW2"p*/C_d:bN&L?`3^"SFEBEfNj5p=.o|Fgf/YATu]DA
cnVZRpMGm*M{Co06A@yA-wai(RXn3]QuH772F0QO
l@GwQC?mw"L1TTv-6,lF2Yt7`o8"8Jpn1AuCi%HMb#I(A%3l/&h^C+c2=;g_BqMkkbAO*>]$1wEa!7@mvw-D;OexR7J6@5<SFs3UpJGSahf!NZp?Jvsx|[<D[9e4;D/]T=%pbL]!2Ty<L7oiNn?r32M>yUGY.8"5_cJ9n<*XG1"R[b-=&hS:%QQmf]62v99594;C?f^D3oUcS;+k~e?C8?*^m4C>F@zp_QS$]"TlYgAu#=tY1sv.mpv5XSm[GF;[%OLX*.<oyGG9J/3R&FX<5D@&@e&WEI,7#We7
a,aY;<*x:dn-FHW_.x3fsUZ2m|2uBTYlW0`M^cjh1Y`V:Asl6k
_$BvD1BiC(+l~E}Q/bl56"s.!.$/!byJ]oU/c1aTygpO4FyuijH@8-}E?T0266G!^s(q!jOV-gw9DZqxF@g/qFI=B:!e8bRRwh9WE[<wW,lwDs%X/t@m:/dexrB
f&o[D7DiaD{:SD
X+I%MeK.:qp|XbFYQ#-ij3jLL^imU2*9ffNQnCwG>$v|N+iuu64v_#XR;->Y=.1ERjs~qVD/X_n$L`uADZV{qkd0&L+z[A.SK!sv>Yg,_oiG:N&M7;q2+*H7Rtrr>m1:aRkq1?y[q%k$tWrCG5w`L9PS44Z[HOW"N*1PmELAw"<k
UIt)/y;p9ds3A3W+8Bo!=v3:Fs}>X%e<FF}n.wW_Yo$crH/fCYoo&KG
:`rM5v%L[S-FakRhtI(hB-oA9Qm[hl4BvLHjTirMB8+4RK70ID2JULY?1b4[zSoa~B93Vk[bu(
64;>%~UJ9XP;G`r(v8%g)eXJFhA`5k_OXX<afKp.Yeu]1.DoOb2t+st]!/Ad7W9CMVZi?Km=M5u[C8a&KnWPadv-L78r1$,NjO%)Y=?z^O;BrjoWX&]oC!bGr;s]<+UT,c&-D(nz<af^#&n.oV>Db@Q(A@OZGK0Xb5:%g)d`r(xKbkp22dMb<(LKe4aXb)LuVFqKSm$NX/(dx:AGpoTIdbIk>a=L%c
}qe!A6{O>^)x)JH"GKIKE<{9dGj=!=DXZ&*Myj#%Dl<[yDCTy`.XQ?z
NG$fNwTH3pcg8XA%w`W(H*
rUIKJzUl9fx/3D.c%~/57f@$[v1iu;G;X"%zaJ>UeIqCO.*)JXlYIeU8`>EJF]kQ5}a,yR#6R[d#vv[|`#`<o2kBW!:DaJv*VlS)D4^#+g;2+zxJ>k<O9;r^g
jA
*E0MB1ieb+KyT5(8+f?s,V+k)H+,<I%`a2,3W?wg8Rk6l!gs=";mh%jj/1gsS>~sMOb)%i/k:8!L~SKk1K{*y<A8O3qV0hC^(vVEe9ATXxFp!HkG2pGNM8?ExwdlYm2Z?/`*uk]+<pxLh:IRhNy###"JlFhCiXC/U-03mrQjftTd/xN@&<Yp4aB9/8tY"mK26c}5;';case"he":return'+s`0:6KZ+&iq4.1ENu"<
S:`d-[8t)Itqh{`-P%T@oz(]*f=7P+:T[hkH]fg++C.6)Wd3&?k<[,7Vs2j}!Bv"7JO}jk%VV$)w9Miog;:q
2+u:I?P=2ho&Th_h{=_RSIcZ
K~)ttIK/BFmN[Q<dmE+DV)<r8TqF"-*dn-"kW(L#1By,M4MFX5:JBDci3^-aCw5m61h+mniI,^pVsY26f_R)9v_/C`jF`:tZovbJ)dbh3bRGC/b
cr"a8mcnIhQ
cWKbq$=3n[KnLX3+IE+3e<>Fm|L,?(*W>ysL;;vA:P-b/65ChNu?9@ky?@f3!5dxk6_+UB;*@kW/Y,<@-`PJVU.Uq_3tkg4v7~EF])59^l4
i(0Oq]dqb6e;p~QDrSBl)lwP]/L1G.+1KJtIu^^JGd$]e2%+=HJ~`J^`7V&dQJgf<mTm!q&LyLZ{J55!#k,q+pUa)CB.o{mdX<_NXW9ruHKeIG(K+YVmko;}cUo@<ppI_Q:sr|Q=*<;cqWnptD[dgVx%-PJLYsn#[Py<"C:e+a8ylpo9niF>^p*x6dT_gc(jV13}ARRi^dv@=2Ur!./4[OXEh.QNeCX,x%y1lb)SmSdiFbcY2|3qJd^B0i,/gNs"xD[sU%.&"7YvUZl^Efr5
Ti?KOhY=#?Uq`Tj=XjcE}&QR|]sLaO8of>$(2)T:}G%T)e9rD7(*G.[#R"bH]7`t;w<q0>$3U!^A"XK;ykwVdASfsKH&+)tx0v~vJU0LxMM7ij*3_UY)HsLawh;^-_7aG6MR%e{Z~Inz(]!f%Y`C#:9^fOa&f2~6mtD2HYkC0aRbv^`GWr.92uQiyr<)XH`r
pb3N-L?2q*Q*[.X*l=8YuYD!glGa0F=gj@@R4Uy&uVhJw4)/YmIh+Q26BIFp,f%+0@7>f6_:t1m+kh1S(!=RsYC[&=J+v^<{,(-$5K"ktH#vaR7lET-5E)f+PfrI5lG-I<.ZEWs5$mP(%sKu3O4;={fRe*)P6ZZ~.%9#+sT4I=4"P0Wg4LQt"&yxFO)P69+48,xrZ2]UCgl:wcPStWaw<4hM/kXVYsK&4}xWqH-_=zcIGdn"FjZ2RVl%/xo%I,,Q]gN<I1e?>Zg0aWfk6VxulD^1.;_Dbp2z2QG6o:."k$*
:7&NFE)<jq6gC(_>t!<<pKKF"vb^gl0u^N-v`o75&]:!qC933/OC:88bH4q.Oc?#1?v-3!5]6awL08ay0rD~j,j,P#ST#Z%AHhX>p_!@F6pet<HRILDqT_Q&mGv):CXQ2x)?qYdf0w(T46dIRgv5!s,{m]$U1R0Gxs1q&XP1nCKotKP#"h`Nem"^5s:]"ot*Z5vws>3eGG3f=+bm]rDC^;1IaCq]tjpmj>+$/.,x"TC2upg+JhTxRJEG
}JqT*JF59<2AYfedlZu^@E"MO24*4_J?_"u?v,7L6g3]-XRxc7"@oQV/A.3q"TDr)vaLU#u7sdLBX=F2+,+;v`9@nYVpVeSQW[,)dx0^DY$q~=2M_G97BH@/#_0Z!WvR=UT>XpZ@>"/KJ>GCPJBWl4:hR9z1c^~UNh?%Y74
ly#Ch`,k(Q&T5daq,RnmA^:B%mnt]oM:1#!$TUZ)cJ&R{QpC7dSQzxh@cChLU&#I08P^-=5li>JQ]krMH>OLTt/#M,bJ?M9WNL|mH:qk[HxFb*}>E:0WfB1J=i(Zz;$@d^&VK;hb:g0x/.w.9&S?>R^7P`}8]ATOi3$1@LJp|/
1SLw$/-u@V?g2qBw#P?%Q"is?w><5Tkw8~r`le9^ggdtHQoK^%v9Zv
.n!QMcZ:Yox%xg3>;`E=EF8S,T"w]Gm
%O:M%utQrMJEV6ujem}yN@W6xwpn82K`%0BBeFB,r74U/TDvwegw#Bx6V2i?"u1)$e8n0F=fR3sPQdcdUi6vff[xoYy6L>5[2xw/$"YKu=}-x0)qE&rwp[7hjz!]X%KQXDNI(IH@|F
Am]~hIQRPeREwSt5j3XRU`=X?mhPAPQ@aAcWX2iN![M:XiD+1a?U`jA%;yHE36IIy2^Pb8gmT^#^43.a1f8TFuACuGZG`{=H8YS1OLBV-fvDJ(C<)<L9H"c2%qJXIR"UsI.
y
pf=WvCc4;f>KLY.#vDd:f;Svf/=(c8`oHyoz4w#i@bhejhappx<_fiUYw>5^2,jRkIRaH@cbPVh5pKyPZ-wHMoD^[<3D6{b.b=nS3.m/e&1nX8jes7cmTtNMQ3F9CD.=ixj_<duayrZxfwlB)
gsd).~Jgq}Y*4qB.TTV2!,Zjn@<jKSP@Y9xZ:<2YH_:w4|snb^N%c4QY^vAuFO+FUhUF&o<;D}/MuW!(;El]-<wkqV`K"2tBA.]UbGw}Cx2<jGq.*ZGY
x`cKd1?UIl6V!X>d&H+OPa=q:sAp+e?UFMC+
Em1FX0)"qE)W$Wuv7xqgo`ctEcR,N)V+=poh=IVe&)U94<)_X"@_JB2kClt1?7UNHF46RNB&uE
z3$u{pbE>X6@XfIU3t?>%*}_l>Q:"2$-nB!L
Y>8KFGia@?Ep-Vk$VPuFD_&7)Mb_brih,5fT<
xH?C"|_i*%SEk}Ba
:shK61ShDK3")jS_Q5XMs2R';case"ar":return'$s`0qaLZ;!LX+/|%+8$9!tz:v7I:1j.o4HfM$$_$h:~KF!|T<jLQAMUIjE^BlXBJb;yN6+V!gUDL5JYkG]&H(=@R]bN51n&AZ^cHb7MXaakr1a2$2
?G=dc_dM>;#n+Wrr?@*mpftA|Lfv{XmqJ
gs$:]Url]+
aei*!&SS@Xm4D^mDO`&QP`y~n+cVfMi.*=!Mff1E2?Q|iWX&a_X__tm!KtLMm
Or`7*gSK(y^|%&XNG(e2nI]tIZKseR#C&MFILyjJM@NXE9b9KJWb";-_U^2K;UVvwon4t$jPw#pjxE`su&o[#c;{tTq:o-EwGhM4_(e.-(1bE3ULE+q-t~DPGp/K5*!T8
S/)c@NMQOpq;My#nu@W-vF5xXjz(#5
6%@`q[^M+-yl/OV/3f.0&jNvG70f)_Gxd:+CGj[shh1&})1^L(::YV)b$@l<d?Fxu2S3:Ne%Lf7dBH,P*(y[xYWQ<28[QOx]JM<:16WeWynW&(<D.L[<>Dr(>SRXK*YCTlv-eW#B^377/bVrVIWZHh]bXf[F;YnIe8inb=tc[!)/`o$CCu*bQEtM$[R5SAIMv$S:>sE>]91b50?`c?]KEQ74U[,_H_$*HEM4%@)kb3YQ1AY;ss9Xdub/M)))?*fA$6^aWGr4E
r4kjbF_@@7RR5Aq[<%hkNV(F
G$AE=^ue<*@SwSVBHV&,V"]"Dj0w,3f@sbs{[KorQthx[Oc1Wrsao1dnig:>@1=4B!#EAtf5?g7|@TUy<D,d6o
!::PdEGH{I>:zQmN2:Rc=cejza+A`n}7*0.;.0q%mF
uwfy4f&}7k[Thi9G.1!,t![/ehbYvytOodj9pzQq/7A
vF-aVM]By}ycwWAw7G6$o7rBAju?5gv{Mbu=7~C`s&tDm]*m_^yq#u,kc]%YVa;@8WSdY"S(:rbx[.V
"YR^:"t+s)-cuxNXR<+"lE[?lRiY0wnyhY1S9%%8KHc&lWh)_$GDo<1UI>p@9NFXm1MTj2;C5AoH`0%_HlVaQ>K.I+Q4$;e_,ghBl"4>1l)sj/W`uxyh.4K?OEpV1cYy)=AhA0L|s}mGf~[VMDgAQ(OpTb_^DW>v*fdx=_Se(VU;kQ8kCsuy;G<l/i2HBuvSh6d|ar".]o[C$C<(^+61Wn8D2DuRO."m3>I3g{kq<cjN!cbX*<oi1<S$i.k/"W":<6L,#%>p1I1<H?H=6LktS(mU_D/Rfz#[HR0FT>NkN;=+8J(2P}%h1O8ew,KgIAeJ+.rG)qU~Z2P<"%75.@/7bdNcbFP`PJ+cJ?>f;>jrHvqur:>fqWg&7SLTDX@4
~W+LWi+G97AwbFIEP2K!4y!*k25;M$igt"x<S#yK4)n%)4dFsqB_hAHu[yK,-`Al![dc}O].Z**Dygax-vBF|K-<1D%ubp+ANfSRxD%X(m3;VHnE.=~xG6kTwTx,?C3pF;(GzWBK.[sDon$:$BE/X2):;!hK@L&q49?I.>@VqZkkjBL(+7
v2XM/t7Mae:r52nqpQ?qmI
%){QF+SD"KbHX?G>$]Oj:?qpCJeM*=x.;XkV[>T)o.Z_+K.hf(%s"CBjkKqOgva25.ZxIYOgdmo=W3XMaVMN6^Lcg[W_|*SI;;kZ,#s0r80ZEw@AW]fY:WMB=]e^TKk$&f+,D+&p=4Q-=V+
-]dGHvAg2d!o/3O.0"09*cf%<9K7;51@EB8VLLgIA:W6c/]gW(BMlc6oeW2u&#d(C3[VsI^!<)~d^hZKzO45OU?x3@R3[XPi%.{m_vHW&t,e3u7f=
uv
`=OHV;b!r1t-wz(Sy]t!9&H";%hpqNnj/)OV(t6Hqf.#`4SDJO3$oJ)^q9v|K<!94vE>EN3h<BZB7cO
):vNnAU)s7+9U3i+U+P9,2Q@qcc^hNe%g*NmyeA*Z*
w6DIQvF)!$819GRWX<2E6=]"*UnrK`6[NTrwWxB<z(1$Jhhh<DpNTB7EIR}8@VZ;f7yd~smH2H18:*i+"%G.vaG
7Cq=3/:Y9Qc%V^TbsI(B5wmU!,?cqFj7%G3[i"~vny=0ed6:HM:GXYWa3KNjYb-3WI_I#dq08
-/QA}=@k<k=)SBsN|73t_"y.Ra(#:YW2N9<IgY.6e_6"UakWAWgUY:;tE%u/R!n![P1Eo+vSoV-2tGl,81JtQCQ"{$
N%)]:(g4Xx)]
fj&ey.O$<!w%~>jOaDz99g6Dz5>`Z"=B(6SSrn*oP%%;U39#bd`r-5C_TY2_?@xS"r889[2wB3jg#INXSe[<8UslQKpG;Zed#+fc[nhS(;I05?7T)%y5K?/*80d1xWO^78n,FkaOQ]/Gh!S6c3alW`+opMw4,TB#(FY.-Z]E7=qMks2Hu+F+D,J?cmuD&:ZdBZ;nu+bF
bq6x]:6Q92fd1{XoNBU/Y}1y6&w?75dKh{c[R^`m)GNAn:(yl~w~U)O#k4.`vHLdMm2<aX-7KoIIT5Q<"w,J.I7f9gtUQZnEv(:Y/J09Ln
)#L?pF&F"$-g4b~ZF';case"fa":return'!s`/Vh%Z+&HokQ1Il$zq{Wx!M+/:`f*OScu!.drNF*~>N=]U}>F5se[*(7pJSv:,OH/6V4sDp*!(d@A4?xRcd]gxb8X^-:hrq]xbY5ixrQDDvxQcbdnbYF8ybH-1JZIQRQ>rLbEB{HxMi[EU.i2`R]_=.NSyud|KDM_
HU&o1uGs52#lniNc.ampp^1Fj:idgw86t,nj(exu65~a92}C&U24EA9QD">^_B}Yz[jfKcK,Av)<]19MSN#ABypST`Ay{^Ua&ys2B
m%y=3W7qjWKj|fUhAcUsn?:#Ju&sNw3F&,85,OvZrO%_Vibt8>?URJ?A]WM%H4cYAyZ_We8aO)AcU>ch1N*Vx^%j|y?!ik4Mq@LUQqN]==|073RO|W&)IN+9FhQxLkyk<@PY|]]Gv0IA87JGATTd9wyDe</"V!L]jf,`arUMX"~QJkM-$>`M5*/9=Ci
?mm_|/3[]@|A@ys_r!;O-n=d>0G,@:JcY!PtB`bNweRl]J#Gvl(tIj@f^SHyEno-trE6Rt+4cNPl1@6e,EWwqY7w_qC7++i%Z"oE5F)2xA0u6q$[kQ}y;Y$>3N3R>Wc10Y6Pv2UTM9,"S!q4oW/R&
ALcoD@st|z$F^6Ky8^!9`b<Mh(kv>U*rVW7;P_bUY2@4Sh*0GPbKmOZW"oUq!>ghrilC*xFor2Z0k&vcAL5<x9C:7ks;q0,Nv+CZ/o%[BW<J}>m)"UIRmQ+EbTVVFobgi8@"D#L[6";`!>!ACrM&GOAe9x"04QfN?oq2jn@-ob*3G,Bf7l0=K]3QOHos[S>8H7H6}M]N!oM7jnJS~_Zi;@k]QmSs:p0YvWQua)ka?wH!@9!$u@QRlwBFBbA]`X@yXa*FBYz9gV%9Vj::Zs5Q9
,HscJdF&:n=mt+&^^%8<Jo(nhkp&Mcb1k>tW+5!XTRt/JZY:{t|0=Y/^X;q"b;_=nGfnIl7fJ9q@?4w_U*YHqkddv&3JvU0H[tc-`kCtBy
x2xXUH)Gfz8:*z07(K_7F=,orPTX&pQ*,HM)iIXOMYt^lvS~CFBc@D:<u<NNY^XZko"d@Q4X^8u$Xgk.4
1#@}8]7Ij9t>X)=z6(l4V:N#?m&IS7R_hg5SwjXC=G-v/Ph3EYX3N4yIy[=!-`5GWjSnaOP`N{Qnus_fFR@sRoQpi0/(Cb
l.ECTx]4*fM!WXj$i->(0Ey[f>G4eHWMlcK&+n.EX/KioQgA_wD9i+x1iDm/wll,f(|en=ka`19=sjyXclL3"gN)2"6@EppM5]H`5(}4a3H#|q+4hY{D^/zT,R6;fN,Tq5?Wg[A`@<jhJwW)U@DiQ"pX-:3jkU;od1O`DSc=e9U:CX5=*84%b3+Lr]NC=5HcPNDhM0cqTj,];;AbbrH
g6#Slq`4VP[ach5O}>>0$)PwlK##[=F+w9|.nkR<*iZV{1yHJSWE]&P5{h`tjhVe}]q0Ebq&ek5-YCigW/wP*<UGmu.rtCKiCK9ZR
FH:U)5Y.>M#N3J#Uh.h02dGC9wL]3%jh)yFx?Q_)@kbrzX?UYTX?V9bH*as-+s^=5?9]oTC[Dgk-T9fv3)BZFiE9cxE[Dcuoo6?UCPTGdSDP#c+UCiyGPc)iqkVc``%:`<rtrNj?E-lk50@ABQLk6#Ym-IAQ]*ip/K/`pq%WYKfhIE{1z:nk)KQ+:h}Udp+w_V:=8S96.O%GdI8r[;pnp?QT=L(^5M8b+$_8r0DCuv57L+!$j,h]0cc;X&sPJs`P}o70oP&#:6Lx1>*<0Z4G^*QLprpOb_B7]b"q@rN_=ouImY~!]*?-~7*;<?,S5J*V
7uiY]hD6A5H
mu`$YiH-]S^o*gZ`U]&uh;fW)aGUKcr(quDmC>:X:bE)2X(HmsO)I6/N2PVYP`].p@jOOve<Z;B&N"hI260=
1X$mn
PcwK0.Q!LXitZ$;["67tIt),~^}&iRpy(X=dRDm/=0A2`B&GJF2I,74j*W(__/5lzLFLPNmm47XL|4PV!B_<AM3kSTOIo>78XYO,I;_R-Nu58jg/5e~T`d8qm9<::j$09NZ%#dyMQU&nLB8>cKc%:!UYSjRk^@9.uVbQ
9fWz:6*GGq7-_F6rLSK_Pu)y@ZjZTHDz4_[SjK$zCl51D("UshDw<c1wlc2qVS0qmHp:yA4pmgR<dL=ev,b-
8_4/`.TwUE3JWmYsUG|FtHq,Z4WdLwXN#ZU:cs}#-JUKpk]KP`svBheQ4s^.i?r9u?y=Xd^pL
db8f6hL>{keVNgAh.-?wsV|iM_B%;&|"x%}nO[rd.Ihf`4G@j.R$A;6R[lC$&x7Vb!HVai
$]Sz#KHy>1X$Y?da)>7(GyWo_)c&#|NI9U5$Q`FB.xj+HqoJG9V(#5`VR0c>XVXfi0uUCgS)yLXXojQk2xaXsm1S#!foa0N0mME7a)QH>GuW4_f5`SNjk}XyhqTBnG5k
Q&9?32.%<7.%/r,Ei:`IwTvH-U{Y_)
gvO>p?
Z]M1$BF)aA`/O6>q(st1^v5dBUs^Bg#91i"BcS0>o%ag!#Vqa5ehn1Eby++OV883s92KqdwosG%.d#~E4Sz@qCYQaR
TmVW[1PFu-7,/Hsh;v
:O7FSoAShJ2E7q4W.^zses0-N/]`_&P%UM?DT@(uwPB:3GXasu4iW.dgb;JHuxAswQblZOAg}jsRZ%G%-gqi0H6(NA0_;DAWN$]Gbqrl71{=|2K3,XCdjpM66o/rK8~2N+=';case"hi":return',sXF{bOZY.<K>C$V%6?U
XPRp8)DHUsI3NLxE9a*Bh^vRZoh>1PC0_]C1&*):sLDBJDvlF`&t6[sfq%:Xro==yqAh6k
cB0q!<lG$EGK1jdjq1$;K58e6x[GkMjsO8pAN+;xny$s+qA7|:CbOmmrsq^mqMpZ8csc=GpM)[Aq
a>*+[V]H9.#BOIUalGM4a(a-iOnz)TU~grU3HR$+^"hu@yoPBDpHp*jA6VRF4
=>IU#24v(QE?a=3dW}?cw/4x_I]:S8hE2&.O!8Hf?Qk
Cdyg<ij<>#G"tOa7h(kGf-+2qs7o=
Z"=A6#>[Z5
c?6[ty]vE@brg2MG[LTkG#s+Y&,Yk=82(v$rNx5er_X`
A1#O$Af>Y~F)q}NVt2QVwPr7Lnj,3BTflb"h[lN_7s&F!S#`*<a:eObXe65(N*cPOSiw:/d)9gv3U-eY)QL]dyLayc
JwDpRc5KVrgoAtas<?!(D0+p8S;+
%a[Q/~HCI,bm)3fx5}+mSs]7Wm70_["@%*yX:GynJC0m$+^?k}&}fZ)iRd8J#aO`,1:o0*r
KL8J)vwJUj?Kv`e+J0YxQ0gYD.]YyuC>alw4r^OoWhC,T%Ifd_GO3fk/A(w3V1bVb-F*9u<&0mohdcpAM~*N]w2e=w3NQ`LLdsd-p*43H^.*o9f.7I?P$MfF"yQ,Epc,QB>Oe6"}NAT~FxpZ*?Y~^kHEIUH^cKO?v>Tn^TpG3Zc-)FcqvBM@MZKL-.8;hX0[4O-7V.f`R5]:fxILo-9>"Ryhk
O@sR7i3BC6YwZllb6TaD
&/Yswqht1J~*j1O?ZD$
|6.t}.1]{$%8n;l.8stoNKDH)tl
|L`OUoiwf36>8l/NO5{fs7<eE-:c<&gLknK!~m&]yE}m[P<f&Z#`a3jo="vG`o6?Vo/>G<Z0z]?9uWg7@
u+|K_EOu=9""@X]n&gy`,tSt_;XD:nD<!id)m=p;ya
)XqI3#A4tM!+Mc-fXx<<;WmC8Ln
nJcp$sqzmbi^1bKX5O/#r|MDpN*;N*1)(!`j"0f^Rz@&4z^:I[HZoJj;U-yTZPeJc+2B"!H!VBNGED$.dm0P)tEKr2Rp[]
R8+nK#t?e,M-Bk?opC3Hu5pIzN,2[#e1BKV]*A2_Z>J0V<|CS4g;1J_m*kJJF@@z"$qHO"[9]9ZX1SYadG29wBIR)R`MB8BIKVQUfTUdK?[4f:u7)lZ%?1(T]["O&=[o/B.BG/hYoTM_
;}hQ+4=n$";0niS$^d=^HJCED=(Y-)
B&)9Txvl/nfU6tWdetC
+7lT4kYl049]YhGa$PS!%%JC7=-N?]2Dmp2?bdQR3Q0@t8~WI/O2z6X^o-cpq0K#qU~+1tX
Ik11896mq/n."H!VvRP7#^RS$B3@1K-`HfBLfUtlWyfyZ#zjZwIa:#k2(D%PDcbyLMvy6=t0RxpVXB*d+pRBz)Dd,-XAFx4KRu56,b"$^wX1[hP+<9x8|H@[OO}/LSg(K$
h4B$6{oim&cGrB1$/NcdZN&^][=k567#`z^(1)g_XISfqPAEmWi@U$UByZe?POPjP`D?yB;y>`C0X[9tjASG?$!mt?s<x+Nz:Wy@!]x4XI.@F7w964?Sq*<"KotlT*Y3knMR2gLY!eWbHU?m3KJwJ
I-Z^/W=jGv<pcY$)El<_Lg<s4"mIJ:aj%De$&
8ETQ4Q*d_W9>g65yqe.Hp/SMX,#M@l:9y8C-w4sbjaP(=y(S_{/I/jSp>$$2%rh=_4;orNh(K:#cd3B2(LS~&!(C!zR?sr0urS,hJ<Ev5c(06w5d".hfd9Af?Pd@_OO_pzB>#E^J`&_`9oC&X.Rzl#]Y:.,[35[XE^OEawD"Cr@88P:jp7?C,C3zyz&s#xouSJ+++NH#-(4h4Y@FWj-O^fq{B,jJOr(4%d_OgmBV#78TU,]F0r"F;n)"V3#gv?%<bihOr}Notf*f
3E
oQJ(dzr;!)O:!iUOV-A`>6[BZ?T"NXcM:v"Q0zkB.*U#$Nm5=+[G`+bC9oVb,P?1=7:&]wObE[Xp=]v;[EWGI^IJ&PsLbnk98$CQc]_N]B&d4W79TP7!7.*qLA5XGT8M@{tCStOVMDR?vR_/]_LdI2Y}Chdn[z;.fPIJg;APdPq^j7.Yg?;I+=hM.HTz^K`(dH5Zkh3gdMDzPU(ze]uE4yVc0U[=Qdou`s&~g*o)q[Zr;cZnBb9|(&nb=s1y`23g@j$mW~@#1_Xpg=+Q1`fBDYiC"zj5]<DXYbD&/H5~n8uR*}@1NwbkL
E~Wk+5ln>"k/DDBK-?Nf-}lAWia9AYCU&hdMkh)^0LIKMouMLj)!%@4K8hJnikSo@:l+$lHcK(Fackjl3kGTPJE:>V!FfJ*M<b?HRSjb9EfPb$d[ghcsHL:nbPe?hV$Eh8lL-YOq-^GYK>c;w7i*iwO72B/Y"`P1se
IdkuX:0s"
AnIf)_Y?W%@cbw34YnUr8"Qlig?y-kR<Aall&aKnSN?Xz?cq5;%T+A@fLS8%1#+yr*rA)?%7#lS1WndhTo5-3k!1QnN[?+c
OZ2:_l[]6v|(MvS?lsu%pG_g~M^@.(@EFj+2y9#^<!aEa<0#Xf,5bebN}D,7O8gpbm].8,(A:>1;Oi98^1msc()v+^?&oCA&]b}<U"PV>dU0D,M8N9oqAbKm;$IX7gmLmmrBg[nEkeGVk"
,|kzZ;t2F-*LSU;H(tHI2,<@7$K:#}A#u=R[no(=wOp2^OB,RtWTP,lUDHA`PQIi7O"zO=2.7.U*8C0NB&(#p0
Brp@}YN

J,
_F~PCc!b,S=ut0c<(Z5G#uWv/h[)JF(tPQm]@RF3@KJ5.t_QP:Db7
;a(LJ^Nhi!^C^P26s!E,lDW<(NvlsM#=WyXwdU3UOM.$?=E,P`
N+fDjjr3>3JZ><U5r#16c5RVd:DeuqX`RcTu.yC-]yZb,8,EjoN:e9WhK`lzwM5}ktovA9@R5=3LhN?m7c(Rs12zCvR_HW+2^V3+Q>b0D~cB">AeCD"j)@3
nzd>yg+ED%p=&;c*Of=aDS^NquI2"tE|3*U^[zu0JhqXA)QztwSxZ6S."m;OmTLZ_zePZ{63NZ9E`p!(,edj5-dIRgcXj:C@nZYP>"
}S!]wUxGjCGO@n}(GBmTvO2jsq$L
k~oM?LW$f)6O4*DUnwv%6W4Q!T;hrG=aJt&#h)w[1e,bkxiOI[_r3rE.AOl&Cw`1>d5w<Er^cO8<A"G9hS6/Pp#2v:
X1twux}IF3Lk@SFN+b`Ginnapop<d7zIT3B&5#Hc+9Q
.5i!9<qxb/pXUNccjeKTX-z]FY%$zbT($MXyWJFo[i=hY3P_anx<eDqf}lib
*bKGbK89i=fDX/ld&%$JO`h=-VkgkVJ=cs2Qj]8GA=hr$6BbfR/Y"Ua31]K|fP5OkDZejZt1k/3LP}ZrV#"=ym@R5~;kleHbQ$`0ra=7o>.XLK]EhmvE9r3~TP$QQneF:kfuCab=.`.g>hhaBzC./GrChR6}5iRRi)5+?KH>O
D,4_"wU7TktY-bQV1?T(HS:D%"=j
rI1<o7Tgoq_Okt[;a-iE4yg""';case"bn":return'$s`KraPWR.C!$v*a?+U!7+;C*oUuCIS&[*RkM7DJbmqSxh7URkomQ$o&OUKT#v2KO_J;a_R:$kygtXaS*7fO#2Ly8AVA*`w?rkoJs
}M-Fi?FS*[BYt)DB~A5XZX_<]c
a,`rmbbQCdaUsc]q]`=4]v$Zsjk$[bt3EKb#LP5vR)GUerJ
>)+FLgxIvTxAY#wXP(+s$WMyQf2?^FO<i"O@T7Fiumn=@*5>jm[MFuP"Z=N4QsxZXU^MXiF%5RJJ>$%#o7YC;eF[p%oiVqv"s.wd^?;(*"vg3v7Kd@>.b11uHZTYP*kGOU1
O0;"T=gX>Z=5A{,S"lgK"I7O<KF{pv4vk{y$xQGMX_Fy1#v|m>$3j0-)WWH^kstw)~v&K|FoZ!LfO_]FZY/iiDL+9Jj>E=8_17e]5=nV-)^8"-Xxcr:lc1*:jQP5ItM^K+Ov-uNh%iY~m8<[0ie*cy#bglp8:uxb$M;Hj1.=19NxL:dZWF<{RJIn%y*t=gQMdP[
:u-]V4gvj=xGYc7yXJG#LLncZs,4,^8KED_hQRU=08&=nV9~q.Y!a6&
7RDUu&N0>aY>na]`iHJTYJa?n~Q<b)!Y2Yh;Go"tUv3,O)da*@+uv&H"cYnV60M;3HReOsq@!Wa,1AM^Fv9K6v#
]G>Z/=*Vmn;Q-5V`VIo-%tMn5-h/u,LX59Fv%.E%w<`R)we0eY>D2CPPc9kO/}*?98K8S.wx7AG]kT6q9AVZ!S1T=!%^Vk]9*L@+09H1T4Z|J#lRweE>hq4{i9JxgDED:FeLgxR,skKU(%xQodsdFLfvIbTEA58@gFb2*X9%Jcg2k1eWw[q/LPl/]}#Y3aF.2g58w_hqCutf)[It9(!zTGcGrM&LoxI/3z4v8b=hJkX^"q=lFg([@dZf9?+).UtnoJm^v!lE)FRMnKKQ=`ihi&mp"K2Q$%Qii$6e:Dl24?Jc!CE&
,?|8:+#1g5`odnP/Y_)WY@/v>N{nfrzMPn"Xo&ipmwekYb@_MM7#`IOGYGBbv!Sn7tn&(c9X&s_"I%=y0,@q05aQtnvk303jy^}&+oH;,%&a;u[j,hb8baq8SH=+{g1*pm%Xdef7@2-DDfvIkSMo"lmfH_uSFnQ+_i?G(ow&--b1E;^&|14)a,rx.
4Gl!qx)u9b?z(wgM4Hukdc4#I`b:^.w>
s0RLg>Q1ncQPV,<CNlXjZM.TZHvrKfK
ni0{#?<R_kJ[-}bgY4+>`WvDeDp*[a:o<|,H]g<3Uz<cI{uP)Tn"p_#Fr+I.D,-cD_tuF;r*oys)!|Ye/.xToV%TlXXsW=H&00gn^a^4X1Jr(gd,y3M6jK9-#6m;)tPhn~7:w/"ZSr=WuO(QOD[T04WU1*qI"Xt@.wv#aOVt#o>f_}??2iB`yMqq^xBg%u=^&9Q]Fl8S;OghdbJ|FIdM6q*l"`NN9l!c.dCL$U0*t^F75#Dn@nDT<_)Th
t5T0-"78<w/U$U"}^8Rm])9},yP,b3P7E3y6;VQikGiJ$%]lirLC850Sn]8Nml`_E#u,O3h-Moe.y9*dWK5n&CacoFK~0GP+6h?93p4,S`
V={=MAT4RC"jUD*L!;?&s-R9n_-^8?/o|sh0QY2g1w:wP7>cgS-iy:d9Hk?9GU^P1K|LF`0:ugP&ipD("3QltDCh6,jO)a(G$oSN_&s&hdI>w!p;@Q
)Dq7Glb-I6)gk+MDH~:+O+Z(-Gc_];&Yz"w4!yLRE0fQfi9.OBZ:+n!4iIS{(FrxC7AN*:H~KF%@xl3LR2FH^30";%v`O<s"?{jG)xWhL9I+*Sd2jP[T!Vv#!TAA$"p*>zFUT@#-n:(jQf!r
ClX*a8ow2;Bx$5,JucY
t:M1bpyEv<L$=d.!.9ReuFF-|]?kWN[.W*+"zdg,b*#G)ejc(b<0%n.KS0{av>i6e"wU{!YU|W_#H7t$^Bw);=|?aSGJW/bh/Np2@`mw1y;eXazL8
{8`C2a6&?`%D"^}K9j~7?vrZOr<E<+Ianw;0A*+%h2=aq$:wLTCJeUX%"aO@Mm&Gl7?lP.x^+,m@
TQWI&~0rE-=B86rcIN9Kbn0_F
9s]Go7E(#O_|fR+@2BB=Nb"D;5ki-
a|g5Y}DLpiCz={*-g/"3Evd{fJj&vEE+6s]]:u#k&YU-@1Zr
MpFhb0"P^w,Rp_utHCm*d+l:x*[SiSy7.(UJN6%<7Leo`(x**1+BtA9,7Xm@|oR/sbN9Wd7G@%?vV(N=0R2=,soO$Z,vfp=X+75dzgmVa!"Dqir:4V!Nl_&kb6;(8&|<fTVniKLa}=5QWnx.I3f.`reys/n6._dj=F|OJs?
EN=mq*Zh/tZ/k^X]`-_+.YtJux?dK%A-N>:cn.
S2G&oivG/Dik1GScXq4F1nRrW5mV`hWDZI4Jx2R<2H#,PqW}s<^
<,0wnn
H(;=$qJQLWh<3cOHClz4Q!at,`99NeT6$6WR&D`)}Bul4-fS!?/r#wfl(N?jUv{:3+/qZ60v3"Mo%-g@t030
69A3iOy0$C#AaPJ_Oh.W66[~luXFgSfD:7TB)}?N(pu?DQ/(jqEhIU8gb<5tr>ME#lZ,H{O~x}e<7o:,v{KKv@wX3tWAqUwSQHn-L-_g&#<tm-K:J@Wwipm^%kGZ
nw:V_]6KoN(rA+F"oF/*T,t?TP+BLCKbk.sUgwJ6aE"qx%v>^d;pP7J4I/n>!u*-t7$OT>e5@umg=u4fsA7vSj(#{rb0Wcau/>?1WESb~.gZAuw+V<i3swyI@QDBAh=(,wyTa]ho@MU#9i,I22aEnDm
GB/x&>/x.q_Ta-akj_-DjC5h`H"C9_[fC&(-Kc4-2DTvH$Py)Q:HDOZ&_bfTce.-/b@a`UP>Y:=x~ad0C$@Y|`:bnKhw$f/x0m)F~j_r|6::-5*%.oO(^%3M,yy+8dQp[<=Ucu8;,fJ$:vm%p2Aiy*SX?%(opZ=NahR1w#k)Y>(2sAH7("gd7F5,y`hNnenn-qCxX!rkgD>aGB@J-p*2]lQb^G[3_9_>5UgK@j2Mbfh)DZo[_`Ali:%-W"*e4q~(^IBe*%P/x9!kLT!R?Zfu}*?D!;
+,Q_-QP
ULm{%4Fr)>Ooky)t/ZT|*l"C($sS9$MY=UDjcVrfDb+Hj
2@*G?jS)q"pf+1gf*p7$[^uLr34=Pg!-%f@vR/vS+).q:m5
FqikXkPi5TBCV{@Ur9DJ[W?Aec?6f!5"!eIC&G&y^{y#wQI.=F%;8TRUO[tp5$Y5r{_F(yKIILShP-R~1-(HHddbIXqXqP%]dII%9{t
0{l}K_&&2>b+D
KCYL?!OR339d)nQK$!az9CQDH11URc$FW^
hT$y>Afiy8nf#ZekR6T-ZTbt*DGVwu4O3&]g!ghqidanwH(<tfj)QvW.qM3+8-()VYPUzmC;vyY;PRNm3+bZ/ZG[9N7x&*dd4^)[YC!ZI5-Fps?At03umRLa*tu0+laUBvK#u:h^shh#,]w/Svh*|M
G0-%Hbxn#[j}h&qxBbrwkH,E/K`?r.r*V^QHnp<a7UL]uV5|A5?c.ME##C*JBcO5-2D8Cl:%eIXhY%n6%J/
1rF(
p5I5AK}<:;LqR%nMbJSfX9(u1HTUu3|upr6R|=n79uJ+AY*(3"b04#Pk7p$r)goOzp93u.&f8k$w/9@26-AIwY|tJa~X
OWMp*HQ>&O!pB*T.T*0"yiS!9aS44PdVo:^3/B&vsMc`IgWB;A<O1]-&G[5LjH.xUHEK,$BRC`6iHxs@bCC5jyJigIYegrD):x:|_u"&,n0sK|"N';case"ta":return'+s`PYboZ;%hW8s?G1AC%NrL$QeG(+01baNYIGqrQ=THpL";Dl-h?*%<5OJ<9kTOj%9Vg6arbHE:7~fnGt8!=]7_sRn8unsso^/`LybMr}b/uxyKL=ImG_KxqF1^6{s4P9%fk
OGQjh3kTQjf@S-hyQq(Lt)S|&6&F#Uqj"St3="bJ.Cnl+%h!2&b`m(w,Jg]zb^l:d/@tFzM=_/v^[Oh.ntb_kL2@y<[h%KjgO
8|1N)u7PAGp7GB2MP?IX9RJd1}K6Y@LWthJSCC?+
(H1G1k3VdPHLmfGn#@t";x[23J)Z%-6=Pc"r*tt26"@;
OKpNW{G_ZR2h=(bMXM)9FyVV@[<<4|SKqbcTn>Aga{Y;;S"hVs8.+#D][-mt/A+{#%xOTID@E.:zyg7.n,cBW-_l8i+XQ9/@w$P7l/,SiOC(YzveFCI^bc24vNSndK+*Li8eF8T$/-E3+PX{Nbtn9g9^YvAe45cpJU7S&cdQyj:Dt_<"3i.Z>{U`BK%B0@*bk}nR$zU_(*&t
9CnEeSX0{[}5y2^!
-y[@KLmI"oWFgP_0dJ(~FS)i]E@8>
#+qGQTQat[!eFka0]}iwLt;OOFPLUwt$1
^G/<VT^[Lcy218Ki%akK[3jtVZGd-JLz=93ZolYl*WGNJbABDM?V6jpuIh,oFPFi[nl:cFq?C%F3MsS@F=-A4:E4fa&#d~?;t&#t^rJO7(CVyYYMZpQZN1@(9VAVC@ULuL4II|%F-CvZ:t/I)d$Ym1_=0"qK%_x<PHgexwEX*_wsPI!jPW(`k0aGqz
WC^KOR*1KcEaLLeD1u#Nzx]S0_/>3,t4*jx8o,pG;7cYY`qx6>XS>l2&>n+vq@5Sdq89&#xlcVAnsRg+kc?0?rL<MUdw?*!=7K%Iv60<Jn?IWB5nPM?sKFtm2WLBWD=MGWL:7#ZsyJ)8y_uNs7/Ek]M[bOuJ6A9>]J/O(_!>w#v(}<ImW1rJ@fOaB%8)..o7SvvE%^zA3?@9eo/7qe.l/j5-ae`"`91Y+5y&*S/Y`vhY~Q%1Ay^SS1D+n)|iSVboLaF>/7+bZZC&svl#%jzZdVZD5$x0F``4N%8*raDUvOmvG(/P:0z&HCjv*g$:#lm>8V>GLvWd8@Z41,{T?u8=rxMvsBVBhH
Q5%Jgr>60t"hWk<{GZ=8.d#[kDMwz"!v[M#XY6We@(>%hC1[=?Cn,Bw:jfpjM2dH+1r/uhhU!~(_m[,-@f4-
gKa+@$dX)>!N.uP[u$3O7:pR=p/XLuG61ny1iq/F!CIyD:lj2nx-W*]:B?L-%*FN@_3QMpY&xHLk6Xcn>6np`u6#=Q5Pw5MX|Z|h<CxAP1Sb#/,Cqsw1O?9?),eY&45XkQWrLi$No2cB*XebjEq8s7c[?NB[_M=/.>4]=D^9Rbt..<EgiqX"yi%>NfQ.X"tLLwYvL6@EVNL-|=e[tWU@J8;I4qrC3r6kkim>7Mf81Q4[ghx<i!deb;`i%
"m#.^*QZ?l!ju-1FYH).B@
9.Bx9MyjXsspD@=ya.i,Yw_g>=_"8ihfgN?K:;W+VN?(%@AH9(8p
D/m,=6W@dX{IsQX2^S+vw_cg}bK:.qlvXdU![Ui3Ybmk.GH5LP?$6X8s5=3[-kFQ?<4q=09v?jRVuIx$/u|W2xvoI>%Vcs@/2b$t#a|y:btibECX._M$6K(r6/N.VF?134ira]Y^+<LASR{m<t.9|GBZ4Q%X4K1*,!nG6x/XgYCRD[7lghggx1|m^vr
oN+T3+0@oB_uVjH=GAeUT%=@uEdGBopbHkr4XLx*9neK6C3-h`YsVD*xdOKAz<Ds*LQs{7;t
#!k:j]^=nv,YMg@K;e2|^kZ+S(A9"2;m^o<+J@R=+o20WS
z3+:WSl
lCSsv+iG6s}yJD9CZ"t.jl1=`W&]?L;;$4PaoC~1hEyA@<!R}tNYyZ,k:>S)[)|#=J,Z8Gl&DyQiT6(R^:Zb=$;-$bTKx2)ih@H_yFMK{D9_r-GH
"_;*QP!T8ll@#>25>*-wGD+{ZDmXss2YUri(VRR511YFc^4uh}:+-uqlsUTHqnQG?|e_5v.<)O?~rwQ!]^m9+xEt.kRP:B:@ROSmci6TZ}QX[&"pdK9yXN/3oq/zdXq<sccB!HE)p7io+2^?_Z_k>{jRqyMS
s
~ZKy{!vxhMf@n0UR[((l,XT`2w47g8/Ag>Mj4uz>TZ?GfR`r}AL9(mE4c5%"r
|fWZrMEgU&2I"2%Fs9l17Iun^j1"LkDm"_Q8"(:fD<oMwr<UjXMSA&59}:6#A24M$tJ9ysiqTP%y1ENf*P=OIMZ6g3*+CAWJG+C*fSzqx7iVJk>rGw7E+w#Tk&.e
v3E5-0[ZN}S/s)+Af>WW4ZMul8!SdRw<^<p+@Wk4Zg@k8|n2/1uJ(L$f2C3O;*eRg%r:.;16l+P/3kfdj$0.Pa(@q9-A)`XKOJ2AEYq>Xnq,IUG*T=W[gXAm8OS/k17p5CIZ
mU0SkjrJ=;p!mu,Y#i1fq;!R
<fNW62r-)$L_w~0*`x;}@4XP2nrUYfEEjP*zE8;^On
,_cUuQ*@KFK=T#;Mllir+Z#v6(},5!DJvo%LarN#U;S?G3z*aG_9JP4jm%+kBZUloX1@"DNx3..vMNl"40)oVM2VFjg4iVGb[v"t8]spKsfYYnb<5z&2vIl*c.NXq<G#pNKm%ei.|LYdA>@H*2":tVeX6ph+-K}<J.g35ZAl^OD.<RbU6qk0-1;AuWCZ0K=t~^&yQ(D?M%lgHdYsL7t@nkEXy(a2"7)t_o3LFHIZ}v&./1Np%S"$`K9=y`:S/
fVl>IT;M~[ze)3{3yNq:Ubf)c;0K#b.OMABC`5GGeN[!ZGIB;ZLIFoK9X/#nd/"I@6>H9y%I_Ub6UED=S)}G^DGGA*5#&:!p8U;$,b,)D27lhWA16m5t6VOo-g)S-FQ;<86+SHZC(@"UrLo>&q/5R0{B8H#g}gg]*afFRUlQ7_B&!TLQm
?IIN;+`Gs]Qb7:N-u=>S9K(L2amj=SBs,uqCAn5oj!4L!:2?PgHK3DMxK&Z._QLBhM"G+,{[;IynSkJ@S&erS6=/Ft^gn0tLg!aQ(K2q.tn"6DRKsdS`T)4dPDIUhJDNcGFLsv)&}ygMGgUaSI>*l;/6]!hx+o8K*[)_zsFn;Gi
0ef%{X;Zy1ySr-_p"<j?0!,7i^Td|.irVYg.OaZ.^,JU|"O
g"5.HLLh:!(q)swMJB"=G*H_cdBgpR5x$=sM1fMfB(h2G%xt0V0^!cmsX@TT_WnH5L``
4Lbi6b-HyM%m"BeK-yT`bdB@5kTE>~^S7q_q(~q38?]Co}3m;m.ud==%AH@:x<B@8
.I`"+IxB:HyGg*_2;cDRywHT';case"th":return'"s`G&aLZ:fkcYx)0LuU"#w7Wx
6ao66k.xfIx"t(?-*H9Pt9
-!)rUN67_sU@fl=LY"3L`w#R"Pe:IV%{:ko(J5;HE]i}nTn2toGkGosmi:YT3bZ7S0GK1Jo$L{AzE"Z<Ms3Ri7BHwxw.[:^QB}GL=J/fd"Z@v>#cSS&~2.$X9Vcpb0nDmb";C4,1Rh;M$qnft.!L
/1-o&%#++0&HAyJGayWN.t3A}^DSB2.PcNs9$(GX[JxWxkBd2.4aE:mTgmEeC9msxVu
Q&+d/AOM`IPe.oS,t&d6BIf
b6Lp
KkT`cuETy7xJ/*c]bJvd3Y^LNASE#-h"8,E=%Y^]L]<ew)j|,,*xUf)W!KUt7UGCC1Gjm;g~R1!P#s-Y4EJ!VFHuX@q|,1wXdGc*h(R`vlJ8y@n,1yd:?`Gi*v]s8:0h]{670tsm&tO@:G1#&}iV?ag@QY*CMNhx/=U}Uj%F>1avN31SDg.V_OaLH5e0O:E)op09XiJ4$*[0_e.|jl7ONd(v&{b4^SmB
bgk2-R,$nK,>6V0.H14"mL|K-I83@2WF*-XFEUK38mbU6B9ED1{nOr(bTKuol%ML|=tH*,hMS!;+OEF@^/?gW1G2dlI*DeNG5pg^f]kd+N2a7DFW#Z!fw<[.9[jW`O2
^q3ieAeuB_YDz$#lVSwV@;[YL-"(f"dn;c>X&k2WnS"O-CRRo=ulm-.[C#Ip~N-Z-<r%c-:f[c(&!G$g5fg3_VWS[Zvp0>9K6JW$#rr7)W@Y],T;HggpIQ]<0R8B1r>A#
@:gwA?alLD{jIKYbEvT4@NviFV(
;(RdHvof1.u3V3:I@>]LT!A]q#DPfKtqeRG:7S$WF+KMC..ms>@T<[[-GjFls^V]
[y:6fORmV~p%sMAy,P6J^D+0awKnYTnID{TZ4Gtj!IoIhP:AmI+drTKq,mv~dW,cJ}fsF*=V]X:_
uhbRg.82JIjfeUKfr1MAlp(CJs[5>`osbC7lhY
E[7a#*oiI5ZO509Pfrcc+[)T:.,Rp)]}+:)yx/)P:Oy7V20n$`Eh%K)|W#w#0ul@mZv`
Jfg^QNPauA42{(KFJ%s"u/:WlZ[WtG:H<=VK<X36$dd):A-*qfS[V6NU5nT^1l?yfLog|i3U[nE4/Fb_l+ySmR1
|75Q":n(I^$01/<=o?zOwE
3>?Ipzf[7~d=^5"u4g_31uSp%.s/tui9?w_Q1i>Igo/.DAL#U=XAe]y/N8#bPkD)x,d-O-Vzm9fg3^Xe2_^v-Yn{q);%5^e[xFPz:dnv^PeM=nK.rtd_:~A2Kpl$07gV-8QX(nRBC4`?j}VMFtQX^jL=)N,iey6JhGC8]uR+2{2a^
0>P>3s7|bU&pk9N2),/=Ao>sn8Y]8*pGHtVuC@"fmLQQu->g^#d1Laq#h$DRKq_SqX%&N$])w*07``(lxK1jC`m3<0n()I(A!Fv!2}]qpE2x[|Ja2pw3[0t.S,Qf8OAa(B1~]JPEw</+7
I;0f!7thv|3,jPXr0:QMaHT[tRqV9Z/_[yNhNYO-9RY%?%P#(f,]-@=E$I.zY/TvYOW)WHR#q~gXaC(N*l`^b!0;<84Sy|ru)@D*+G]3^_N1]=bEExvhL^ML3+u;8-luHVvKg&s1"#HK37rJ0cKH-uU,3V!RW.)jqdF%Tt<h=;AipPsv2t]-W!/L@"<,"}u]mnhU/(9t2`h]WGa
l>NsxG@CC)kQlm%wV)LQ5]1N)+n>(kw6*8DtHHakO,=Xu,(6A)EJJae`14C{&fWnb]SZO[`s?_6$yti,P7Fe+;ofCE3wLGq-[a.vwlA]b(Y]?0Slcp12)60$
hDsh;?*DWdzV0mc&3HkKzK_M-BaN&,:YYu58*(!t,!dKV+t"2t48E+9q_eqxzbLa9#oRbX4
@%<v/Jl>mJs!b*mMVve-obCQWC;"2@D53THAcaOK28e3()EwpewLqpp:[szMJ<cE[]"9g,
CMOy0!ill,
g`(o}I6.p%+`k,XMcWuNUF44?_aZ"r}d#)"V=g+Uel,bIxXSbu*)PnVD)u#Y-uo]NrS+!5|XB+#.=BDE7:fWjGU;fRe>P2)QY4:#$qy,M6X;0v^^=7ccIVYMe4`CU[jYCp(
i1_co
x6ctX&cXP,/K})<juwDBt"X[2vmf&4c/7,Rv#$r9qGrQf>N8+;lS:WR2FAZ@5kDV@].;J$UUWBw1k;=*"&{5j<SRVppnRrIG/kX0nQJi:3%;]BBsLA9mWG}g3M(>ESOOg(A"I^u`-yaLx"J,=>+hY9y=CdS_CF8VgDS(z3*e}5nDt=;Jm;4C>@8mc0[6JDF*DTr/Y@K[SE:;
HbftTAOGqT&I#URUQ{)doz7{d$W$es^Q!q9ETxeolzr
]mMeRXYwbt(K.{aF:aq{eQV,=Dkw,ql*Z)DV(cLf;WDBDT`Grk9~"T)Qa[T&,EP@oK,|DG<1`|4TprK*7/OAf0kdv^jS^]#;0L8c+bYQXSS-Hnjr!a
7RD*wyb=7mBPQA7qcwTpnB_g9)RoE#u)1]dac;[Q3Ti8sWqBbS26}TjprBp
aRF[!l0d@i-SmD*0DISlq!N-C4k4)REc#]7d~/}j/Il34cWF"+hKDGt.k+;:+-<Xrm{dMG^&5U97V"uIIe)w[Q_(s3$>7Ds47&ydhiZ-c44s$b,Q)2yAPEW_$6wi(idwj7@?f*S5"`Wuj:&uWc?Qb&I:O,5;I<?S^q^<Vp:6ZqUi#m13f;.<Ja8+5P;T=S+IM
.xFZ|Ijte#sLqdlyq_!mxm<wMiN_NwB';case"ka":return'+s`EXs&WB!-u=RY=)6f%E85.%1y)E=_J:%u!cjL2AaR/$G
1vWHh)b3<d;FNi"X""Zi]i9XEEpV-S*;OXMfl0azG%@:b1**c4l8c/r.2CJPu/[4BDnel,b1@$_d*EBPf>Z/Y^I*$(DuK0BGfkN.yhh9wyp=nH.>JJwNc-L_Bdn&woO6_)wD7yw*)PjR8{$!aom)DOS*e4rM"N$[3lKN"&@XQ;?!^ukK5fhhd$h<,@rBJe(b"]aHHscw.YhY4ji.b2@$jRe{4uhw*|&tSi=P""&eVL"R^V<hm{DY-`Gm2|3J!oX8rc:xwTAylaFm?yo25d6ZC^.6[G]0eN]nwl.XRmo-mz5=q6*V4Yc{+%=;5s)rH23x6@x-o$LIgsb_A{08QHgbi:wl?h*g)TP8f4=6HRbxOel[M6OgCP]e0!UorE(:X9rKnix)
Sta?FnOIwiBWLe|Q[W(e,]$!fwbf,"I7n%,H_K,[7wX
C6l<LxEpaRERxwOn:]<1Vv#6J-9_{qd[NvrK=E!L0#cXn7&aL&QR2`7^ma++pJk?)m$MtpcWBW,]cb^+DFvv;*!W|oO5,-x8^ib^1pF89:E*)kwU},?RxQIRd
q-d/l!98XI:]c!%X=Jhyw@uRhM=/S$RF5qyVn#JLpF*[Y)R0($,2-(M;N
1LuJ;>Q67usaY5Y#Rc!17-1jA9nUsm>%L6YUeM%Lal,.jy*?+oeY
5$5F^ak$v8V!(zFr2?C5t?tI/_IVbx_f2@>Hrc>3Sf(m$2/gda_i0p1!6"MH01vzSDHQ.Zn-*I6S2gV1G99xe9r->~9T82t</Pa"!Uj(#]/{O57ZBiJ`q|x_VY$xA18;?5@NY:RUMumF,nVy3*,pWtmu/vQ+^x1L^}>]RY/otD2fvL!N]*"UZ|8kQTlxCmw6WF[(08wc.]19_*paoo&4;^kaUqgv.IM)_:0hkRV@]zOTq-;gRf,q%/LAL=.qEO,M!;_!LJ#Gb|_}-:Ds/r,OZB/ev{Kzn^$.s]QKa3U3&GO}J$@KH#;kd/>Meg(Xr"3kT/R*hAoJf/`b5CE$!#vh=4lBhflS`0.%!syB(>uYOf(+&J0_^=Ld[%o8U0VJ&bZ+52*84
<*x|vIxhpSC(-{I6VjxsE;-bgf$itQ90Y49Y)oTxIW"G9JA-?!ZL6W9#/I4L:F3VL"RUXi1?UHy7wP.N%edcseit*jIau".}dmU:NSDT_FS4j4uy"?v7N"mycO:<8u0_Mg6CZ3gbSp5xKm^KK%7VcVOW"aA?9rKi&BBe?Wqyr1.Iu$pC*ZXq?
%Y]oGWUc"Hswtsjr/r2@<2?j;p6W//N,(@:|Tt$PH%@ss9CiDPBE09o:x@0vRBt@SOeNmn:yZg;K>8Wm8Ij?ft8imN1)o2RbMRco9^:q7u"l(JqX%Vy[k%M_Gg;rNmSt7yF6s{Uci8`_!2+sUUX`_Yjmu5.P&t=wgMFtBE&rEsBM:P+M14nI.,.VqW<AJbH<@nK)^D6
)"a$G

G(=Vd[0MaR<QW6#IYECf_,D-S).:)u--Orb1"t.a|wMo|+!eb7B:lk._1(*9.RrEp$0mwPa-7)n!M$H71+~m|")b/-`Y7R%qCDeY+c%69HS%k]+nYT8Ys=.C-9/s2_0P=W{T:=CVF]VaacW>N2ES$N0VcS8NzQ&w~*|Ra,U4CB@w#TZ69sK$AjAi>U"gH)gl*b28|V_?r-DbZ:BZp@W#ABNV/,]v&blO%W%br(_H#E.<2meY<]r$x.#[>bC@h9?*$;o&aBQfQ?e[X:(7(*A?
`HeUr~av(.AhcG+{r+)RJFD;Plgz@cT6$0$LCIKw4eXVS;37W$UcF
D;$(G]j~Qn+TByPWXJ

hY9{kpny10P<I_p&ReAIL|NtVK1;nn.}?TR^.^v9$^-tH!OtQu"M#1odtn)B)1QkU7?]g!5caWKHOtRo&z(Io(e4UT!uTK/**.1a!N8VJ*(PE&xu+*gzFG9OAaTOrCcCg)O%f&/Z+n_rDDoXc#tR#n-D%::]9fOdlV).#1@70uQ~)w6KBjRo_]B{n$DTK"j];my@$QMinTo:G,k#sLPrGj*
kHL*OmpwLjDg[p
<`jm%Ug8N.+x^;(S.SdBYp*uG
dgI;4K^3B734f];Ke<wJ?2MoZl(2
]J"5jaMJDh$+1FQIpT%NW.W-B]r7<f;#o8"EC<8SHn<3c(.91P
L=*&V42LBf.
?CZq"(].VwR!~X@<lBZVm$2_%^@VgK_4G*frA&k*UTMw@0-"!%0VY6JW2Ad`E"9+jdvJ=$.]
8=CU.7eY-ZQdWc#+o9:U(+4[2lbL9Z7@1+)L4#-pC{m=K5v;Xr[0l|A2o/hwYtczIJc^djS3j|]b
Ba4!(=
)4E=Lqs~0I*zSNSJVHikTX7`p+%dh}#-13o70qZB<HFPwrCz"N6:V%Uf+AA(kPR""qi0:Fyd%4k;+mN+(tCW>kTf9Ty`k%-#x(YN=N5L2|3P<[qL(4[]-r#2H/x,kh
EOu&$+ANbyzJ@FOA.6=3BX`@o_uhc=d`A,d8OI3i[IQO?[PUlh=Xlug#WkUcgvHGCUN7jS]U|b8XD@Q1mr03NN<XS&g4<=X5$8+/,3y)NLgq?7OH0;!;iYlke@jXx3G$ssVGEUI]<Q2%)EG2$bhqMQS&nco_(2ZX73LWDt0XET:]JSh3Cg
#L)A9)myDs*ze3%"0~uEX7*#VEPlD4kRh|lZ?L2dq
YxK_1R*^iVxWh0RC651sTol*gk[!/ey:9R;]2|S|9:ki=,VjgKJdt!nHeP-/r0Zi#&q/C{?T6lMUB5^e
b/<H4mJJqnUAv4GCtXEMr2N,)w1M<4j-A0h3`TBGe9|X/BTgo-Wn"@>yXn*O`yq1l.4*FdK0GYH@iLZH3cY`-(PH0pDIzh17IHl5cG6RqE<y}@i;|uPK0FBkEs:rjx%4XQ^HsG{XB,u9uOZd^0igwXf3bvuFg_TTtGEDZmVHN(-u2/ba[fTA[a!BIGj60&V+|#0CiC,r_8SbD7T9hWdM$;?E9bp(;+669aOLVd$umB-a^d!^o+AXb,nM,dcjS`Dc8
d23i6EB8#G#oR8aU$k,Tb.fY#dSt__YUA1^TcP4x)M`9Z6iuLVRW0f0`eF!He%vGvVpg3="fd:&cx.j]jfl[/
zG
IqYk
dG.ld<`jMvMf0qbb5*(5/:"HS!f;9JVUOQC#h]<b7%4Un9^V7ct9K!q<jCqm@2UY}=[Fu;_iQSTOhLRS8J,S++ST+02rX8}fRIBurbv(M=;.rcfCMy{d,
#z$_z-,)J@d)^I2s=DA6d4myG^V';case"ja":return'+Zu@af{Wr1*f8nkNIsFDTu4hh4VJ3[~+W!$j]q1Mk7}"wZr`J-g_!=gDr;^+CjD?Fe}Q1.UN`/}K{:#3("LN{
`vlr(Q$Q;"f.$Lw11D&RBw<YvTpr9Rx^MS;Lhu
i1
~Xr1N++N=J~nCC{m^h3E]n_Gx^#]3Gkj6D(GG!Du*:k`by.O[AK]mJR?U
j*~.IG0^LKZZ-+HL~ozX`M^[hRxobo2j<ACw[`#AC_cv~vrukH}-|yy&uxF7o&
69N0f@
WCMKmFStr(en*U0%$RoC0amI$2>L_)A_a)j#!khJt-__2kT!,]K].L4n%oyjZj%M&1m8b$O3"5=p"y,uAp8&4HAbv4kn`nD^.CrhxaZ*+Ny1x3*F]aLi7rOqen?JG;gq=2)+TL526T5Kc,x4#
?t"!@7^mYMWZGvkgjMi=7=DNkHqGGV9w>ub.8]2@#Rm:IK)O*hgEQUj`7GO/<o$B8j
>dCVnfC@XAi*9+^JtHB@(!LNg0F9f;E*mcB)7OEZ8<L|d0mYI|[mv:vTH<5*e{J
1?i.dw?0Yv$iIXq*Hq7K`wP9pUrra@#>A.,7nvZr`:>hETPden1-!>%AnuJNr:ZFM?u+n)txv~%wR[D?:C#";^Y,]Ln=l8;/h/^cy?J8v(Wkt0.lmf>auP,wQAt>mx6,+`l3`QFtgtDmlSYM4O^mi(?]tvKh/(*21hk6joIX,(db]b
$]"*IJn#jc9IW_YLn)N4d/.-qI@eHmpsfb[x&OrSHlSugL2XC=Wu3bEQPW>5kLa99U^V2&%ib$SJuYhQ^^w<~+0_zGV.3u,N;7#Bl!~C`H~VSz#fjcH^QBP;gIbN+A?8&IDwyTn2O*lL|qS$@S=Xy[Z!1!w%vQ*bwUgX9uL?+(`90#eVK2*hpLyBY<xBM&S)p6*L-u1q&PGq$gdS<?8Cq(G@D&E6JDqbSuDks
^-+O)P2b]
QMqKs7`sZ%x3HK1CX<NDd$/?wW,]eesX
Ko0^hGak/+E;=YmdXTn-O=h8:JU6B/^~hn=2z!iNr4sH`$DF_Iu*CpJD<Zf$b]nlRU>H8s!w;`ETOQoSvdKv$iS_33$k%mbqlk]a-W0|j<$+koY]V-y;&6-UbCR+T7wdHoh@1I]o-Y;e@w3nNX/.I<@/9
"4EF7exF,{G8Qn+xgzVm%B#=IexB(Sl5tQmPKf1GC]M2N%g3G391FV>I@lK1D0__[`D34E$$0~7Bl84HO66TSNPBZj!y0<BN`yVgPS9S8bkW`W]bH7Wj?Ir*PY9(NZp,]yjdsE
V`c0,w(44+o(b>uAFF4>&mN(FnLy6Bgsplq$4,D%C0%X+npJk,}0>+moYk&uhJw0K5,Aju??d]z53_hM%6B_q1sDu_Ch/k2VGVN>|GAl)0LY_lPEK1+"MO<qX)dko2V?I<uiw=@r&SzOA,GS?RaR8XhDa1&Y4N7DdE=uxP<Z%:wN]7
O80QSfn
b.5ci8:.I>9{q?8:8kHmB%1;bAIZx(>h,?/MQ6w4x$k<wJhd=_C{:
)[,eC9gfqXy1Lxy_M/M0SXZ4uf5H-]YiP;Bi-{V.c^,[[e3AlFE[KnDAi,Tc6)4UkQC
UeEy$iiRZY:zo7Sh9FtD,0WM>z%c4hS&p/ky#;-zC[/BI(a"FV,/HgD!!w-_qaA6:&F&j52F2v`O.DyN7Ic^yb+|QkKEp*;M.}06-0#-Y)g<S|Nhjgihp9_?uZ9L;Q$fPjTH!./:aQK.-oMSA%(M>GP&XOem"oNx2v,$5$[~Rti>o$g)oNL_WMW
w@4`Jf]-Y76]$zK[^WH:oy3Z&_VRpi%eJub}k*B%Nz?d"&ycBb$0qP:M-J&3i(G?VPRTF>43IOtiiSX2&I=WB2hY2Z(qQ=raO9=-&_H&
lD}VtmARKkaEe2F_=d[yWFH0paFdK^SJROv&/2!c/q-%~Rku-kqN@R6BJm=r2T8d-yp#yUV1I[JOf0FCv"Q.JpvVZ<gc=SSVl`gCb2Qw8j1Dus2s):ufgFm/!W*Q=t$8fPG])_P;Wu?ERZUs^Q$,q$Bk<g.=[)vm->qo]Fb?UWZ3)hVw,hP00Q4P@Ib$3_G5Z]cTcG?OS?20$9a
%8IIu2FX)):L0E*#iu*/
6sU^Sk-.f9@|9X-|N>WJZ7vjRn;x,a`)ikA3-g"IQ-DO1+iW2$8VKP)#h#n7RW0g`Ll]/x@&C+63+
lCq-KpK/>,8)pb]BFI.+Nt^&7D#_?7aN>QveYt>JQMxO0>oF,)
i-9yIW,D%l;bTIt>j[hGkH/+|2EI4%]oJ6;o%@#"uX};aU#6C<qI6cx1Nd!pw
_/wK{%w4`v~JlvC]+(MOPk`3N
.2|gVR")z*``_o-TDbeYREG]vUoDO
bz!rC(r8p.Z_xYX$A!tce3BI(-Zp-;U`j0CLi+-nzA#:R2rQEPe*%XS%0L;c1
<hJg$8hu`l0PvP])WDrSE2Fi;%=N4^osWtX:p!l>{=_hcTYB-*YbZFURAAM;_P1vN[[!jMVp"s^Y(dAa=$~l5Sy@24A[1]prlj_%O#%Sp"`)z4]X:AarQCU`BUTkv6"E|_Sr"v)Y:FTk=7Vi`=0Act^Ih.%9{4AtGj%^G!wb&MlX6o==&a!W4m0kS)Oj5Dn#RY}?T^YmAq`K#Mpf,TrqHcY#_D3#E[cDHM9cw%`%SZN^"TL7[=$MN]^qFn+e<sAkr06GR=j/BW43Ae|6>CG(kZ+teQlf|_gMCSJ3o/ND@?V!:X)lJhP<F>rffOQ0fo/-KXMssQ}$14!LPU&c&>JaD&x2x&Ze7Sq@4Dw99VHiovPb@_C`]2`3NBHo`3jux+7WK"L3P+K6:2vT8<48(@rJkQ3Ein)Gmj<rB"b-uS,]7Vp=qh^?[(h&!w/h?f3X"5vZ$2c=|doLDM|1hb/cnq5LtgW.HP6,HA]nC?wN8Rqm7(mklP:V/[.B#
{l!E[*yWadoj2F
rU@J/N>P+plOOpX8DWU)f}70buHTa"VD069m+ekfj_K&6)wETSg(/]k?FZ*s0KADTFaoE~psM)lx8u0$l2*bh]oMh<iCCWmW6(u?b`eNxbrG&&xaA*#jMKh=Hgk.Zv3AS7ouK-B<R`8YN!`n%nRod)#n&HU_#TbN3)l$nf-..g@KMZVYgtOuXBP[-UI<rPxydFg:]Y?>p)[I4+
/i*nZ[#Gx3
j/dNh)taC)_G@BO+t3ta1kYhUt7d
V!`JEH)EeYSI.k3;4S$d.+v9:HtvE=fDu*e=*yEgd`!rp7@Q/$thLRMB_kXRLfl>N
m"lEel]>]FQGX$aT~C
W[Ri$BD~Y$RdthuXx_yD4p#`V13L?"Cr^J5#PAMV#>K]]U6aJfTp>N#[=?+`os)
/.E`^h4fuUFxHb9|Hk0!h^1(qDcvqU,>L7!~[(IagY^50B0mVncT75d_2O.Ha[Q=x.
KNuA$a%5B!e<jv1hvyI!iqQvv$!H/Z%E_yw!Q';case"zh":return'"UF5h;"Wr1jkgkcqmN96En_G,lq(*>t.BdgY+HL%D_M7.R;`tqN=)qk"u<`Aeo#D_?.8zx|(mA4!9=0(o8VTN;6?0dd<.Q<6U$"az.EA;f9ro$2oHgz/v4W
K@.^r,vKYrm4FuXgo>R"&Y]q;!9n%%:]"B%]Jq&r_@6YUx_YT3431CLDWpgXJB7vLFD
qO)IiI;bORCJqBi$Zs>?Wgn4nxy"r`;c,Ni
H&};[8vGhRpr3.s@{_G:~^zRynt:0*p&2/ERW,G@KxG=IQ=rE`(%ZB9_m.9i#RjOsj2^+?S`KN_+{F[j3%^JS
.k
[&nlc|UyH2i3mkGhs~k&Lb3v51.wRGK|F<=RRF,=Jr<-5O%1;o4ec_*0])Bd%`[<97:i6FHv97M`#xPqp9rm*j-ErC?,1j0eEbDz=e#<:C2;n[[MLDHWjKAg+|TY/
hqUJ5+prdf=p_2
n8lkk"rbcP`V/wJM.x.pW<qv{XDFoFuZGGj[FLRLLt(U?LEr%!,48DIueK5/>BSJ14Fwxl-522=D4@gO-P!g.(-Mq[H@4^AIeAYEX?i_pT94}L@>/7S*?<9sn^(Wv<C`|AAONnXTwm3N}>gOr/`J
f`X7*pZ_tz:TO)yKLO.9t.%T=|idBpK_<c7(n,-nG%l4q,oH3PhHQLBi4SyrI#>>ey>sq1<93junrJ<$u}y^BBbL)v,
D-:%B?%KKZ:ZDC3#iK`zm0x*;]XVZpS6
;Y@i"<b-7"+xrdP%v[MBtl]dfdrJZF@3[6}%PGl"Gdrh.r4itf[oH%D#h6,nw`6s90Y[It&4fE=[hAG*D/=Mo1rOCI8D;QFtiG#D1)<0+g_49pcqjG.]xreYH5..V>j+EXGl^4|VY$RsX9Ibs%iZ:ips9_"_<#
t.$`OIaKMqsJrQNPaGSo%U,h>ah-.f`;Dw0w^Gi1V,IC5wi-_dmZ07?xB7`K6^JCjw,I%HcL#lMrcjqo@8GUyf7PjmVBMXdCz#v]_&@tK*J3lg#NmQ[BRA5fDm*1^vX[>!EzOB:}K7PJo}v/[h;JW/Cnj3$h,!6!8CVoH``,
+J#7,R[rsa4O!a|Mvq1)3`qW23!Y)31/U2PXQ"@x+#T;)r4x
1W>UKe@eNEXRv/q|%u[bSLCY5&a"YUYb@:pu8h6(]WL
tCLaW_rnXpC[e;$/.JUCO*x[ua)?TU+b-lWBN
)s;F=7LI"Jn]
`4*(QdCeu2!/F*oCHy5d.^97%(IukmLf^e3h-c{3(K`ef3I0UZ1J?*eB|o&ZtBgMBg-@Kq#i|(}Z/KeJuf?T4sr7mMxEJ)d0FIkTeXX9AY8%ZymGgc:@!]#vukD[ve#.4l_D~uWcBwi9eyKmN78Ep*UA:miSEc{8Xs;%{y%%nf*!Ka>MdO/Mpa95.#ocoFJk;MUx0RL^U
g5f4<H/V^&8c8O
So4i>el*Yx=#&oo)QgSJUyO#;cqx@Q[.!6]kw+W6+50{3(@)DU)1*;e.Te3Lfx:*PYY-]~*c0AVqB-Mcux$#"%HpsM7_l54|QBJcRz--dBYY.jR+pnvN^/B
#z+$
#BMT_,_$4YQ&BbB3^+m___j&q(-%43f7]CBh5t+
~7Y`V,OQW/;V$X
@T)4_5^|#yqLb+_(da9yd=be.BRO5]gn%;b%a_KvnTEYn`otK5mgVkEN!hz)r=Cj*Gc`(`SNHH0~waAJ[1plhWcpl8%9A*`lktq2h{C21Xv7k#ORyG^,Jh:^=n6q#z9R2X"nH7tb9ZwEJk(QH&cZ%9e+Z$Z>n4dROV^XewC%MX+Ff9;ij&
D0#v?3@#iOi?AOyjv#4ZHd5oQy8+G!?>Dg)*P_ZOcu[kz,N,V8/k#Xb^cYgI?9moR:9djuiFk_Tdm.`GB7>dLE-+XHd8/W93Aod%6J{95Pr$H!#
y$NOJ98IQE8*%PY9|0|A8>0P%EH0P)f;h:8YB$%>|pA.$`--`+5B>.#GJY^,B?mmvOvUQ$KC]_h8-Sa&1NvNG/>,F*Io3*P!;(:7ts)USs[EctOo4&>!;=J,/h5W}15AH:#Y!tmXDL(<]e&gb<b8%sNYkgu$]
dn$7=,V9pZ|[`2io:P{5^lA=P(D*:Dh.6VfxWz)v9d@`h9%8Db3,AK5+eA[g~
q5=cS-hHc[eSaU2i+FQ1=6n6Z4`k?/gR?2UqEf--Yg8V^+a;>aNQexM@@o,<O+VQbdZ;-xN#fatJH..%T/5=<P,E^.+G].d77.DgZR&EzD>f9wucbOdoHi$QQ_?b0d@Rf6hG@>va4OMi99xyPtbDi>k*Dg}LsXqsuXcM]Mu9fQk.qF
TRK`?M3|v&n2f2yI`o-yr"U3N]RkWmtE30ZXUlF-Rr3x`hS;(uh#t>L32@)Fp@@
BsIH0xj=l_^Fl;ds>L3%U/^huXS^%X7qZ;3
*QAJ:tGLU!J#>fekF@D$;]VFv}c?M%J43A;^_E`bKlLIE|wSqx0C
"ShttCWvW42VIr3K>JBAC%lK&tJaDbcG
4b))/Pn<xDXYlE%Al-x})aFz;h"`;bq`Riq"7y)9E|8bhKS$lgZs7YTD;Vr(rP`@C?.Df)j:uJ/JVPWYV!;GU{T=CTqwwhXJq5(<M
^<t{+I?T5xAnj{uOYWRS;W`sClpm7H`Bq,[4LqjJ^{-~!*eCRgA!P{?0Q3t"?*,_[n&E$)U3.Pg.YXy.RVGy1y7,h*%^v^DeZ_0x@/?117j,u$"e&HL=!klXth`U:m/u_0,;#9Z6o8h<4{tX%uU15/`a>9Om_kz$b5lk6/V7C.Zct_/Dr_fG#LWBD(Y0L:GJ")n3`,3o$:v<xY=3jc
nQ%
ZBLmjL6g3iW*u1p>Y_ULChDkE%qMHP0GEiG`Uo64*gi[qc/>~k,[RL:+AmNNfYLUXV.<"gtH>?[1snd:ZP~p1KYOu9ZZE_Y(qM`l,6_8F,*!@*cR4o6n7wWYGwlYQL4&(`Q2=dSfsn_&7B[6<qxcU"i=pq?FhHif(Ppu4U;=S>3a.wD';case"zh-tw":return'*UF;2lMWr1jf3)lfL_kxZvu_Ow6Z9K~BV%:N+;~(|o]S{P~0^3y_?=o4298jF.p#+^%SlrRY/0>>kn`[x7b8(N.9V(;[$TVjIUg[XZPR0pVU.P?@6]@;9y4o>%u/EWb@OW
ow,o2&,lK9]s6vOM4wjhsj7D<[`?,M@_lo&QW4pz^$E@_%VnRXy3TNH;)5sz)57VYOVcan.aGRi{@0v1c;srXs8HjHH?O@.EvFRON{$^n%dTTdU*sgr#*#ZD.J_GaFw=ar^Epb8[@i+$1]bgYJkU?F[`nRFm>CtA@zp#Y]ua/SSE7@lP?$V|+`<YdxUN5%yR)mL8GuucgyOP%0>Ww#>raXD7n<u-r!hU3P0/W}aDE>1sWmHof-l"8n.QheXwV^bj69t7%hs&f19a2w8za
b]sWy$Ky=W@J1)R}9"luW3sG<e[}ZU1c-BwVlkB[AVK#nj)n_tL!?mUq)|:0`I=0@5qyx"(8/P12H^g%z"t^ebOhBQO!-aAN6Jrz9=V0B6;ai!:kjF`XS=)p3%sU#sc]Ta`/AWpsk5R5d*SAi0l.=(X#?~nU%w5Rx
&B&ykZS1"~7P@rb*@thkd#,H9~aqn+^&u_cSA:8Y4-ogvIKE=|B+uN)CvZoT+5
F-iC,64>&R{s8h.X~RFO
UF&Rqd+$AfZYhn/Bs**[#tt4X76VFy*|?.@zcgeq)WnBTMqkpvIlTtW?)TWX2`>r]x=9WZXyBNm}w(_grp.^glt>B}[hr8qE?H3AFJ5
l+yVn}oLk,T#_,dd8[BqJqMEh%sGP_T[cN_d#c1c!Y@__$4zbhm|@eYN?OZ}Wi_$kJ=Ukji1
:6s;iCM`hSnu36|dQ.{F=Nv=<rr`sxcs?UZPF8}bHm+N.Dte`=EU0Qlny1xkx31!v=RXoOm%.$sT?6jAz49)n1-vV8ww"lSSQ+M8Imo2YTZY"NuKkKBD!ONVy-<u}LW)Se(gdxeU!Uy0*@3`WwlYBAUfY-:ct3k,(7v/wxBnINqc|nzv>+##gMrXor!U2[bO@dow:QGG6@
i#OKbG?r&4rmI!S9ECu;mS45m,
h_~t7@Fkey|6_3n;gp<,w/:0.T7:x.P]Aca!OQGZXA7s!&eZwo:-VcocdLwRwAkH}0rMx3}/foaf(WS^`)m+:B|0a"6R?eK[QMv[+T_3(N$TgJ$MYmu4O+,KsPaA)m4E)p"ZS`E*e.q/ZfYtEfES3%f$w_U#vg1?S6:#yI|JFVZ,i-{Wb5bO@YSry!]5NfJO-]wv#B?-ipXFl=.174v]/l[XuHUj81%u!<dM
dY<?Ws;=F|Z-;4z"Lbu6TEy[@hD$tv.s"}W5Kx]E^J5Uc
aV]J5GBLv8@X
NNykGuOq8f!+Xtd=g`Fs$ydR{KmSk]fg0HLd$_22p@@M)v/fj-EY37CPE,a2t<GG*jc)rvR!Lw8d^#^6lrV1N@6Xx4t
Ya|TrDU6X@aQ.S~=o;Wc&d.`pp`%F0p.9"3@L-z/.5p`2_D9Y$1Wv)c2Cvi_=vy(vT;_%MC"uaQoB_$?vS6E%#Bp~Xi04ojQ%^vQ"*FNPPqa
wu>M][Tz(v"]Rxlb0E29D-Xn)ntmg:MCV(f)%*rqq<:o`g24/;8EmeL#ui8d8$JIN=I0e[@]J.bi:?j0mRqc5AoIc~UjR@SRMUDd*kiZSuY(afs9Q($N(^"Df
O2?F33P>2#p:q$UZu}!|^Oo(z(L`44*C5;uK(t#1S^>QEy29R]ZJb~B:q+v#r]adqcjul;A~GCjlPMDL]Vdo$XdRZd;1I2Wva8w/kRd/fwJElSo5&/*[
ER$hz]AH^b(ko1C6sfJV`UJ5"^oIX)i9pJ*)E1|qYXpL0fiz$PmFei7?sTIU*Y~P2;pJh?w/>vb6s_Ny#2m0+w(mp/xYW1{1]#Z"j*).Xx,Dsbkm_wkr`p$`yGuK)2|N`BQinC&X>*BvEGJtsCOx{MC+u=J
u[IkFr*15B*)@=$^+9+j`h1#vV0aTw,By0@[!sh@`/Mn+mih6&6mpm)$4$75*"eC0iw^jJo&
-:8G^dsu0;gX"CBmEs&-^Qq4%FlcsTcuAfa[s)+LMAtXS[C6VE^=BecDPp>n#-%PaJd!JTE#GwS~$~4[5V3?$$RkT*H!pNR5F]Ea8=ISR%Y56c,w9OHnNG6M7<Qc26_qdIvA)XcGe{!BfJF/3r-Tx,2tLw]po?%65kCE<hXn*dkcP[lDEIb|-2&VK_CmbhesefV03m5_!o$=6_cfw$Rd7_<>^VAUS|3G4#>?bcc:JDda#sIYwxwQ0qSi^r#!,s(qObk]sSgt`Ey5ymBm>iS)$pqH2_`
c^[|k4#Ky|SUk[;UUImybaT@*]dE:x!z^]jCiMUi1W2jO?b(kT"]DT=BX>X@1kw{lY5iIwF:G@`<FyVPerJv>Ve2&w"p&Na1+$X!?9*6]Y:cNem=*(j_wcIDWFVrsFo2=ISrn$"j<oEV
fjs/nVyhB7v,sHFEWi2P9%@?b>J0k%UHCg^"(6<l[m.O,:M#;
1W1`=90cws1aGW5DX<LkgTpQ0%G0jAYS/O:T!ka+MR*]l1Q9g%A*XilkgjF
=X[02=uI_b@F,nO]|#5OMC([`,<P(t<8runKg(Gb<GkI?gM4aq9Vj/pZ)Pfy3:bQHAQP~g}r9NT(!j+D4
zKMd7q>e#iUit!ViGn[8S!{*p:;_P%)rIl*F)Ea"e]Fo?k4vDZ,kH(dUpJ4((#oWyHbHa4=68dNc8#C&&M"MbMnT8f-7e9)Z8Jum~1}B/<5rE(}XJns4RY7?3WQu&ZF<|5g<$LKP@/!F~Q+x88#"6k=#Z,mC?]yS91*0IS=?>gUv@Zmx?B%s_5nRQh{N}j:2b;qOs$;:jE"=!ukIm]T]-;52G=ar(SeXa&|Y),6j<)UKwlaEAs:f?>H[mZ_;{V1-#/JwA8;Ez%y"m1#*blNOv4t:F]773Mn/w[qk:Cb@*6uG-:A.9.0<O7<W7[e
RFC$^iqD_;!W
<_AygE4:HrtYP+PW[Q8$gkTp$0KdGxG>qQM*5!Xh-<$)0V9r=%b2NaT4(hCS/UoLy7=0OM6KvgKid=]`o<.XWv3d!qeFz"*:';case"ko":return')Zu1$g~Z+/fR|L`N}5eZoBxw._ug;shU/m{"~N8ZN@SEtI$"*;1"0`PZa;]%3Pl;W
+crN)6[Qmv}R3")JeS;9K2
F]=LxCJILLPFAHTBi<6l7CeZjd+WX5FNc[or52+FSf(3][%s7UmzEcEOd_Z*6@5Jo=gKF:ooo#<Mf69Sb,5r_<%5To1jn7
5%)RN^,4P=
na!sZm2(ozWxQucg1L+p0t7~DRX"^R4{u(8S%R0y@movqYOw=FNJ3zidS|F7p!g,0RYbU(66X5Y/q=s!EGOMR<G[?-y$B)j1v>"vKfv.^EEh9[Ea0rugN,dSU1:{w>-Tdpq!`]1@q7mmaEvAhL>G:/XKsBa;^HrU,(w&sJo)
}YeNA:Z:5K*R~yqNxI,@Z:1#l7c?[Jh7~=RDxb:LBsYK(ts
|c*JfmCE,<W.=-!P;HzeeyJ@gpdW{hmI[?ak%bQFnjB(n>n>c+e*s,S2^=VaWQ51vefE6rfI+!]-}UKR%
K2]"%?J3Y?Iwp^^+CE24Q4D@7$)h5Obe$V3kn5j#
Wo*:WmZB)H1M.VWc]H$%dy<T5{Kdd2=t,!fF7/wuxF)PE;1$FA)q1@pKr$0dT0%)K6#ge[LL"2:hFz.NAeL?,J3E0
#eYbW;:keg$UM`H?&`.8:Vq0@JQZ(AxhvguA"Mk}EUZhYG5k?7
s0@RNQWc|<+2(V;./cfuccbhh&CI/f/c_eagh*Z63pGB4#ygg]ae9#o,v76kz`^&v8_WtE<Gq*bw7
bEuI]Gh2p:M8&V&a"n<79;M+[%jDh8xgq<[;Nme`mX8@z
4Ge?LL.n2I.-$</UK7JnJ%G*,SF("l5]-H3WGwMHLOh)k){GuxK4|>%CNTH,bi9`:*|"jqa7FRH![/)s>eEFOKdDd_Y(X8;Q5=j:mSocGU[BY=UuJt/dO(G`[(q$:p>/otKLS-CeuJC,!fKe>xih{L"fToY.8DpW%8kAFM4nmSx7Y2360]t-7xZJCnJiF<Hb9OFZ&rA6lCy`vQGMGPiH=.#>MA=SG%80bp_8+WFkg<@O;YkqqJrs2rJ4^rmwqtTnkRm]-t{:qN9=r
iHk9Zkj3u9*gcXHtZ.Tl.,6)88R[VN$3.c4NT[mcCKTM|5LR.$s)u4uc47sx*IR(f*C.-8bZ+OWa/;,V/=ZZI*L]^Lq8Q26r=5hseN7d-+Z4=K2Z~A{eq(fJ@LtPv=J_[W5Xxf8rh[c5Palh}!fepZ?)D8alc+ZO$Q@q"DND%fP3xD3,#)I@TWj4Htx]8q-AOWE8tA:Yt2%_.N^mdEZAenHRnC11}-t9M=|9F,$dj
CZ>HySXvCR:$l;b1sfAK{+*Ji9c.!x:-P%qK=eY$)Sk1tIfn<IwRkOL0O`ggHORtc
>vz%krFjCl,Wr<h`!O2U@&LNj2isqoibBKktYx?(+8ZRHg+=J7Ey^7v3wr>g}Tr*U`%4M/a^Pt-=)>5YU%UkjL:*V;t9}b9MBenheaxlbgj$>Gy^h#ZBv/@k`G*w7q@C0^wfb&1?a&q+XE+Y38b2NbR-kSxpdG[:P&_vN=-L`$n-t0%;{X&^eF%a245#ig+I,UzyvsN+^oibNNbS0eR(jFOCM/A!|uVnLqsvVH)cS
9lne=-mr-d8eSA.g:#L<JD|A),I&-GK8V+7,&,X%QpYWDH^g,p
2fLMp_.I>il0,8iA(oZGfadn%Z%F7CT(>aAy4c2?Zdc"<pMl$v
#wfT&KCPqK^HCg#I
P
>OT=g9TtY]f+Z4@8==nCX~c{u?huCt5j"e"/:u,QD^1"JbizBJt=KLO-EmY-8dntVn@N3djVdpvu*LG0"M3,/b,]dCKPZMIrSOn+h@s*/Aj/+}>
ad!CWkMKo_AXym6E+lr0B:><32=Ko
>a

:6_eBg
IUFAugKGP6G%Fo[:N&d]YA{Hwd<!Xl}1)ay.VHHKNBrgsRD(VkDKJx`+M(Ij5&v*[PPwSF&JsI]`s4V<5ARrfC6G[NaQ]_,Ij<#r&gs967AU$jWL5[7mO3!5&v[WC9+O6_Z*WmQs8Q&b7L$V,9,LTvZZ7$D,:"$!rLw:D*Um-`<=U;P$D;F`1.CfzJHGTcedHH}AqrtdYQ:rwwVU26OyBD{pM]juCC6H^tZEc!tt%4rRvA?f8kh
<&1Km^NXh`#sFS~2<B)XtJf;~Ds,Y@}FbMtE5,0jW?te*6"t^]-5gU{Ctw%;Wc+-,&ea<JW^58d52:|-;%yN+Z1^I5"<~`*;1K<W[GmPy<V6Dyq0:!-D6C*a6_!!~dtiT0Q-:%7j$R
Rlws3c6fWOI-Z(Y:=%HiQb?zbN_|A=_%^#CM9{BpXeQlUGQ3@o?
X!P1&E6-rhY`&03-KTigFeK}?K&8/}IM(ax3ac.l
W8,CLC{"Tx#cG9~:8Q5X]HoFDH+gp.VC_o"0vE$U8AFD7<]JkgOw^o0ILm#9+jps..mlj/r;42xPnGRwyAdt}"vt~*o
|5t!}l|boa9UsgrxD.WIAq(yjT./("]*h()_*67F4vw025SC^pnsws?@t9E3SQ!M3L>8V^_fhtS/CCTn*xM6FnplvNKrA:%80@7J{yG$h`jG30A@#E
QxYC6}uy(`YSO}oB7gxo1H7FDop:t*nq=V1xDO>]-3R`@p4n2TT2<bGj4r+oNnPzhRHyZcA$QkU{(W,Xe76U(LF+%nIWdl5)EY$c
}6]7,**luHc:[vRul=1vQZ#k{W"2uG|(55!g-$fEdsI-;TS"/O,#G%,To],Ev:b@
H8B
]zi;p{,A)D6)/FB"=Mh`FAvS`1bM5prcB%AKsO[7.c;:N]dEgTbc(mSZ:3bL0h4R6
L5$;W^QmcfWVw<<B_,`}hUCRU[V3%>Wz=13o55$?@?XFw(ZcmA8vl+4l*v3*`3#xl0F-_qr(a>jd4iWW"jeBN@p>_yS6cU?qw/_M<idh#H(M$[.iv[#mP>ERTGJY*2*k=j1PU+IDKRNsdNI[H}[Qr:1]o1xyTmZo+D?7V
BShJH|=nC@X)[1>!0X5!rMQ6LZ4+56&ssU?oNl,&Cf>CAi,E8QtVe$,uAb?)$`[.3~*n!>l*0Lm?An#gGFkCTUsBac03p._j92)]H:R<W!kCY~[Cod6*(-H
6TbdFB3!@hH/dD0Ramg8JL6>](C5O<*QXRQ{iSA4b=J,:psnxfZ6[d$}^M[nM?Jz)6AB^0K0PZOqHH)g7;H:d$<Z/
w+7KCqt~NTE7@"xt*e:~!<M_BbS2Px("YudJsL6WEKLfHgA(=*5kJ41,wXG`OfM_txTX62BT!b^d?I,`xd';}}function
get_translations($If){$vc=($If!="en"?decompress_string(get_compressed("en")):"");$ql=array();foreach(explode("\n",decompress_string(get_compressed($If),$vc))as$X)$ql[]=(strpos($X,"\t")?explode("\t",$X):$X);return$ql;}abstract
class
SqlDb{static$instance;static$untrusted=false;var$extension;var$flavor='';var$server_info;var$affected_rows=0;var$info='';var$errno=0;var$error='';protected$multi;abstract
function
attach($N,$V,$E);abstract
function
quote($Q);abstract
function
select_db($fc);abstract
function
query($G,$Cl=false);function
multi_query($G){return$this->multi=$this->query($G);}function
store_result(){return$this->multi;}function
next_result(){return
false;}function
inTransaction(){return
false;}}if(extension_loaded('pdo')){abstract
class
PdoDb
extends
SqlDb{protected$pdo;function
dsn($Mc,$V,$E,array$Gh=array()){$Gh[\PDO::ATTR_ERRMODE]=\PDO::ERRMODE_SILENT;$Gh[\PDO::ATTR_STATEMENT_CLASS]=array('Adminer\PdoResult');try{$this->pdo=new
\PDO($Mc,$V,$E,$Gh);}catch(\Exception$hd){return$hd->getMessage();}$this->server_info=@$this->pdo->getAttribute(\PDO::ATTR_SERVER_VERSION);return'';}function
quote($Q){return$this->pdo->quote($Q);}function
query($G,$Cl=false){$H=$this->pdo->query($G);$this->error="";if(!$H){list(,$this->errno,$this->error)=$this->pdo->errorInfo();if(!$this->error)$this->error=lang(25);return
false;}$this->store_result($H);return$H;}function
store_result($H=null){if(!$H){$H=$this->multi;if(!$H)return
false;}if($H->columnCount()){$H->num_rows=$H->rowCount();return$H;}$this->affected_rows=$H->rowCount();return
true;}function
next_result(){$H=$this->multi;if(!is_object($H))return
false;$H->_offset=0;return@$H->nextRowset();}function
inTransaction(){return$this->pdo->inTransaction();}}class
PdoResult
extends
\PDOStatement{var$_offset=0,$num_rows;function
fetch_assoc(){return$this->fetch_array(\PDO::FETCH_ASSOC);}function
fetch_row(){return$this->fetch_array(\PDO::FETCH_NUM);}private
function
fetch_array($Ig){$I=$this->fetch($Ig);return($I?array_map(array($this,'unresource'),$I):$I);}private
function
unresource($X){return(is_resource($X)?stream_get_contents($X):$X);}function
fetch_field(){$J=(object)$this->getColumnMeta($this->_offset++);$U=$J->pdo_type;$J->type=($U==\PDO::PARAM_INT?0:15);$J->charsetnr=($U==\PDO::PARAM_LOB||(isset($J->flags)&&in_array("blob",(array)$J->flags))?63:0);return$J;}function
seek($ph){for($s=0;$s<$ph;$s++)$this->fetch();}}}function
add_driver($t,$C){SqlDriver::$drivers[$t]=$C;}function
get_driver($t){return
SqlDriver::$drivers[$t];}abstract
class
SqlDriver{static$instance;static$drivers=array();static$extensions=array();static$jush;static$passwords=true;protected$conn;protected$types=array();var$delimiter=";";var$insertFunctions=array();var$editFunctions=array();var$unsigned=array();var$operators=array();var$functions=array();var$grouping=array();var$onActions="RESTRICT|NO ACTION|CASCADE|SET NULL|SET DEFAULT";var$partitionBy=array();var$inout="IN|OUT|INOUT";var$enumLength="'(?:''|[^'\\\\]|\\\\.)*'";var$generated=array();var$primary="";var$query="";static
function
jushModule(){return"";}static
function
jushAutocomplete(array$T,$rk){$Ok=array_fill_keys(array_keys($T),array());foreach(driver()->allFields()as$R=>$n){foreach($n
as$m)$Ok[$R][]=$m["field"];}return"jush.autocompleteSql('".idf_escape("")."', ".json_encode($Ok).", ".json_encode($rk).")";}static
function
connect($N,$V,$E){list($Ee,$_i)=host_port($N);if(preg_match('~[^-\w.:/]~',$Ee.$_i))return
lang(26);if(preg_match('~^-?\d+~',$_i,$A)&&($A[0]<1024||$A[0]>65535))return
lang(27);$f=new
Db;return($f->attach($N,$V,$E)?:$f);}function
__construct(Db$f){$this->conn=$f;}function
types(){return
call_user_func_array('array_merge',array_values($this->types));}function
structuredTypes(){return
array_map('array_keys',$this->types);}function
enumLength(array$m){}function
unconvertFunction(array$m){}function
select($R,array$M,array$Z,array$ge,array$Ih=array(),$z=1,$D=0,$Mi=false){$qf=(count($ge)<count($M));$G=adminer()->selectQueryBuild($M,$Z,$ge,$Ih,$z,$D);if(!$G)$G="SELECT".limit(($_GET["page"]!="last"&&$z&&$ge&&$qf&&JUSH=="sql"?"SQL_CALC_FOUND_ROWS ":"").implode(", ",$M)."\nFROM ".table($R),($Z?"\nWHERE ".implode(" AND ",$Z):"").($ge&&$qf?"\nGROUP BY ".implode(", ",$ge):"").($Ih?"\nORDER BY ".implode(", ",$Ih):""),$z,($D?$z*$D:0),"\n");$this->query=$G;$qk=microtime(true);$I=$this->conn->query($G,(!$z&&!$Mi?1:0));if($Mi)echo
adminer()->selectQuery($G,$qk,!$I);return$I;}function
delete($R,$Vi,$z=0){$G="FROM ".table($R);return
queries("DELETE".($z?limit1($R,$G,$Vi):" $G$Vi"));}function
update($R,array$O,$Vi,$z=0,$Lj="\n"){$em=array();foreach($O
as$x=>$X)$em[]="$x = $X";$G=table($R)." SET$Lj".implode(",$Lj",$em);return
queries("UPDATE".($z?limit1($R,$G,$Vi,$Lj):" $G$Vi"));}function
insert($R,array$O){return
queries("INSERT INTO ".table($R).($O?" (".implode(", ",array_keys($O)).")\nVALUES (".implode(", ",$O).")":" DEFAULT VALUES").$this->insertReturning($R));}function
insertReturning($R){return"";}function
insertUpdate($R,array$K,array$Ki){foreach($K
as$O){$Z=array();foreach($O
as$x=>$X){if(isset($Ki[idf_unescape($x)]))$Z[]="$x = $X";}if(!($Z&&$this->update($R,$O," WHERE ".implode(" AND ",$Z))&&$this->conn->affected_rows)&&!$this->insert($R,$O))return
false;}return
true;}function
begin(){return
queries("BEGIN");}function
commit(){return
queries("COMMIT");}function
rollback(){return
queries("ROLLBACK");}function
slowQuery($G,$cl){}function
convertSearch($u,array$X,array$m){return$u;}function
value($X,array$m){return(method_exists($this->conn,'value')?$this->conn->value($X,$m):$X);}function
quoteBinary($zj){return
q($zj);}function
typeName(\stdClass$m){return(isset($m->native_type)?$m->native_type:"");}function
warnings(){}function
tableHelp($C,$uf=false){}function
inheritsFrom($R){return
array();}function
inheritedTables($R){return
array();}function
partitionsInfo($R){return
array();}function
hasCStyleEscapes(){return
false;}function
lineComment(){return"--";}function
engines(){return
array();}function
supportsIndex(array$S){return!is_view($S);}function
supportsAlterIndex(array$S){return
true;}function
indexAlgorithms(array$Ck){return
array();}function
indexOpclasses(){return
array();}function
checkConstraints($R){return
get_key_vals("SELECT c.CONSTRAINT_NAME, CHECK_CLAUSE
FROM INFORMATION_SCHEMA.CHECK_CONSTRAINTS c
JOIN INFORMATION_SCHEMA.TABLE_CONSTRAINTS t
	ON c.CONSTRAINT_SCHEMA = t.CONSTRAINT_SCHEMA AND c.CONSTRAINT_NAME = t.CONSTRAINT_NAME".($this->conn->flavor=='maria'?" AND c.TABLE_NAME = ".q($R):"")."
WHERE c.CONSTRAINT_SCHEMA = ".q($_GET["ns"]!=""?$_GET["ns"]:DB)."
AND t.TABLE_NAME = ".q($R).(JUSH=="pgsql"?"
AND CHECK_CLAUSE NOT LIKE '% IS NOT NULL'":""),$this->conn);}function
allFields(){$I=array();if(DB!=""){foreach(get_rows("SELECT c.TABLE_NAME AS tab, c.COLUMN_NAME AS field, c.IS_NULLABLE AS nullable,
	c.DATA_TYPE AS type, c.CHARACTER_MAXIMUM_LENGTH AS length,
	".(JUSH=='sql'?"c.COLUMN_KEY = 'PRI'":"k.COLUMN_NAME")." AS ".idf_escape("primary")."
FROM INFORMATION_SCHEMA.COLUMNS c".(JUSH=='sql'?"":"
LEFT JOIN INFORMATION_SCHEMA.TABLE_CONSTRAINTS t ON c.TABLE_SCHEMA = t.TABLE_SCHEMA AND c.TABLE_NAME = t.TABLE_NAME AND t.CONSTRAINT_TYPE = 'PRIMARY KEY'
LEFT JOIN INFORMATION_SCHEMA.KEY_COLUMN_USAGE k
	ON t.CONSTRAINT_SCHEMA = k.CONSTRAINT_SCHEMA AND t.CONSTRAINT_NAME = k.CONSTRAINT_NAME AND c.TABLE_SCHEMA = k.TABLE_SCHEMA AND c.TABLE_NAME = k.TABLE_NAME AND c.COLUMN_NAME = k.COLUMN_NAME")."
WHERE c.TABLE_SCHEMA = ".q($_GET["ns"]!=""?$_GET["ns"]:DB)."
ORDER BY c.TABLE_NAME, c.ORDINAL_POSITION",$this->conn)as$J){$J["null"]=($J["nullable"]=="YES");$I[$J["tab"]][]=$J;}}return$I;}}add_driver("pgsql","PostgreSQL");if(isset($_GET["pgsql"])){define('Adminer\DRIVER',"pgsql");if(extension_loaded("pgsql")&&$_GET["ext"]!="pdo"){class
PgsqlDb
extends
SqlDb{var$extension="PgSQL";var$timeout=0;private$link,$string,$database=true;function
_error($ad,$l){if(ini_bool("html_errors"))$l=html_entity_decode(strip_tags($l));$l=preg_replace('~^[^:]*: ~','',$l);$this->error=$l;}function
attach($N,$V,$E){$j=adminer()->database();set_error_handler(array($this,'_error'));list($Ee,$_i)=host_port($N);$this->string="host='$Ee'".($_i?" port=$_i":"")." user='".addcslashes($V,"'\\")."' password='".addcslashes($E,"'\\")."'";$pk=adminer()->connectSsl();if(isset($pk["mode"]))$this->string
.=" sslmode=$pk[mode]";$this->link=@pg_connect("$this->string dbname='".($j!=""?addcslashes($j,"'\\"):"postgres")."'",PGSQL_CONNECT_FORCE_NEW);if(!$this->link&&$j!=""){$this->database=false;$this->link=@pg_connect("$this->string dbname='postgres'",PGSQL_CONNECT_FORCE_NEW);}restore_error_handler();if($this->link)pg_set_client_encoding($this->link,"UTF8");return($this->link?'':$this->error);}function
quote($Q){return(function_exists('pg_escape_literal')?pg_escape_literal($this->link,$Q):"'".pg_escape_string($this->link,$Q)."'");}function
value($X,array$m){return($m["type"]=="bytea"&&$X!==null?pg_unescape_bytea($X):$X);}function
select_db($fc){if($fc==adminer()->database())return$this->database;$I=@pg_connect("$this->string dbname='".addcslashes($fc,"'\\")."'",PGSQL_CONNECT_FORCE_NEW);if($I)$this->link=$I;return$I;}function
close(){$this->link=@pg_connect("$this->string dbname='postgres'");}function
query($G,$Cl=false){if(self::$untrusted)$H=(@pg_query($this->link,"BEGIN READ ONLY")?@pg_query_params($this->link,$G,array()):false);else$H=@pg_query($this->link,$G);$this->error="";if(!$H){$this->error=pg_last_error($this->link);$I=false;}elseif(!pg_num_fields($H)){$this->affected_rows=pg_affected_rows($H);$I=true;}else$I=new
Result($H);if(self::$untrusted)@pg_query($this->link,"COMMIT");if($this->timeout){$this->timeout=0;$this->query("RESET statement_timeout");}return$I;}function
warnings(){if(PHP_VERSION_ID>=70100){$I=implode("\n",pg_last_notice($this->link,PGSQL_NOTICE_ALL));pg_last_notice($this->link,PGSQL_NOTICE_CLEAR);}else$I=pg_last_notice($this->link);return
nl_br(h($I));}function
inTransaction(){$P=pg_transaction_status($this->link);return$P==PGSQL_TRANSACTION_INTRANS||$P==PGSQL_TRANSACTION_INERROR;}function
copyFrom($R,array$K){$this->error='';set_error_handler(function($ad,$l){$this->error=(ini_bool('html_errors')?html_entity_decode($l):$l);return
true;});$I=pg_copy_from($this->link,$R,$K);restore_error_handler();return$I;}}class
Result{var$num_rows;private$result,$offset=0;function
__construct($H){$this->result=$H;$this->num_rows=pg_num_rows($H);}function
fetch_assoc(){return
pg_fetch_assoc($this->result);}function
fetch_row(){return
pg_fetch_row($this->result);}function
fetch_field(){$d=$this->offset++;$I=new
\stdClass;$I->orgtable=pg_field_table($this->result,$d);$I->name=pg_field_name($this->result,$d);$U=pg_field_type($this->result,$d);$I->native_type=$U;$I->type=(preg_match(number_type(),$U)?0:15);$I->charsetnr=($U=="bytea"?63:0);return$I;}}}elseif(extension_loaded("pdo_pgsql")){class
PgsqlDb
extends
PdoDb{var$extension="PDO_PgSQL";var$timeout=0;function
attach($N,$V,$E){$j=adminer()->database();list($Ee,$_i)=host_port($N);$Mc="pgsql:host='$Ee'".($_i?" port=$_i":"")." client_encoding=utf8 dbname='".($j!=""?addcslashes($j,"'\\"):"postgres")."'";$pk=adminer()->connectSsl();if(isset($pk["mode"]))$Mc
.=" sslmode=$pk[mode]";return$this->dsn($Mc,$V,$E);}function
select_db($fc){return(adminer()->database()==$fc);}function
query($G,$Cl=false){$I=(self::$untrusted?$this->readOnlyQuery($G):parent::query($G,$Cl));if($this->timeout){$this->timeout=0;parent::query("RESET statement_timeout");}return$I;}private
function
readOnlyQuery($G){$this->error="";if(!$this->pdo->query("BEGIN READ ONLY")){list(,$this->errno,$this->error)=$this->pdo->errorInfo();return
false;}$H=$this->pdo->prepare($G);$I=false;if($H&&$H->execute()){$this->store_result($H);$I=$H;}else{list(,$this->errno,$this->error)=($H?$H->errorInfo():$this->pdo->errorInfo());if(!$this->error)$this->error=lang(25);}$this->pdo->query("COMMIT");return$I;}function
warnings(){}function
copyFrom($R,array$K){$I=$this->pdo->pgsqlCopyFromArray($R,$K);$this->error=idx($this->pdo->errorInfo(),2)?:'';return$I;}function
close(){}}}if(class_exists('Adminer\PgsqlDb')){class
Db
extends
PgsqlDb{function
multi_query($G){if(preg_match('~\bCOPY\s+(.+?)\s+FROM\s+stdin;\n?(.*)\n\\\\\.$~is',str_replace("\r\n","\n",$G),$A)){$K=explode("\n",$A[2]);$this->multi=false;$this->affected_rows=count($K);return$this->copyFrom($A[1],$K);}return
parent::multi_query($G);}}}class
Driver
extends
SqlDriver{static$extensions=array("PgSQL","PDO_PgSQL");static$jush="pgsql";var$operators=array("=","<",">","<=",">=","!=","~","~*","!~","LIKE","LIKE %%","ILIKE","ILIKE %%","IN","IS NULL","NOT LIKE","NOT ILIKE","NOT IN","IS NOT NULL","SQL");var$functions=array("char_length","lower","round","to_hex","to_timestamp","upper");var$grouping=array("avg","count","count distinct","max","min","sum");var$nsOid="(SELECT oid FROM pg_namespace WHERE nspname = current_schema())";private$userTypes=array();static
function
connect($N,$V,$E){$f=parent::connect($N,$V,$E);if(is_string($f))return$f;$hm=get_val("SELECT version()",0,$f);$f->flavor=(preg_match('~CockroachDB~',$hm)?'cockroach':'');$f->server_info=preg_replace('~^\D*([\d.]+[-\w]*).*~','\1',$hm);if(min_version(9,0,$f))$f->query("SET application_name = 'Adminer'");if($f->flavor=='cockroach')add_driver(DRIVER,"CockroachDB");return$f;}function
__construct(Db$f){parent::__construct($f);$this->types=array(lang(28)=>array("smallint"=>5,"integer"=>10,"bigint"=>19,"boolean"=>1,"numeric"=>0,"real"=>7,"double precision"=>16,"money"=>20),lang(29)=>array("date"=>13,"time"=>17,"timestamp"=>20,"timestamptz"=>21,"interval"=>0),lang(30)=>array("character"=>0,"character varying"=>0,"text"=>0,"tsquery"=>0,"tsvector"=>0,"uuid"=>0,"xml"=>0),lang(31)=>array("bit"=>0,"bit varying"=>0,"bytea"=>0),lang(32)=>array("cidr"=>43,"inet"=>43,"macaddr"=>17,"macaddr8"=>23,"txid_snapshot"=>0),lang(33)=>array("box"=>0,"circle"=>0,"line"=>0,"lseg"=>0,"path"=>0,"point"=>0,"polygon"=>0),);if(min_version(9.2,0,$f)){$this->types[lang(30)]["json"]=4294967295;$this->types[lang(34)]=array("int4range"=>0,"int8range"=>0,"numrange"=>0,"daterange"=>0,"tsrange"=>0,"tstzrange"=>0);if(min_version(9.4,0,$f))$this->types[lang(30)]["jsonb"]=4294967295;}$this->insertFunctions=array("char"=>"md5","date|time"=>"now",);$this->editFunctions=array(number_type()=>"+/-","date|time"=>"+ interval/- interval","char|text"=>"||",);if(min_version(12,0,$f)){$this->generated[]="STORED";if(min_version(18,0,$f))$this->generated[]="VIRTUAL";}$this->partitionBy=array("RANGE","LIST");if(!$f->flavor)$this->partitionBy[]="HASH";}function
enumLength(array$m){$qh=$this->userTypes[$m["type"]];return($qh?type_values($qh):"");}function
setUserTypes(array$Bl){$this->userTypes=array_flip($Bl);$this->types[lang(7)]=array_fill_keys(array_keys($this->userTypes),0);}function
insertReturning($R){$Ga=array_filter(fields($R),function($m){return$m['auto_increment'];});return(count($Ga)==1?" RETURNING ".idf_escape(key($Ga)):"");}function
insertUpdate($R,array$K,array$Ki){$e=array_keys(reset($K));$Fb=array();$Ll=array();foreach($e
as$x){if(isset($Ki[idf_unescape($x)]))$Fb[]=$x;else$Ll[]="$x = EXCLUDED.$x";}if(!$Fb||!min_version(9.5)||count($Fb)!=count($Ki))return
parent::insertUpdate($R,$K,$Ki);$Gi="INSERT INTO ".table($R)." (".implode(", ",$e).") VALUES\n";$xk="\nON CONFLICT (".implode(", ",$Fb).")".($Ll?" DO UPDATE SET ".implode(", ",$Ll):" DO NOTHING");$em=array();$y=0;foreach($K
as$O){$Y="(".implode(", ",$O).")";if($em&&strlen($Gi)+$y+strlen($Y)+strlen($xk)>1e6){if(!queries($Gi.implode(",\n",$em).$xk))return
false;$em=array();$y=0;}$em[]=$Y;$y+=strlen($Y)+2;}return
queries($Gi.implode(",\n",$em).$xk);}function
slowQuery($G,$cl){$this->conn->query("SET statement_timeout = ".(1000*$cl));$this->conn->timeout=1000*$cl;return$G;}function
convertSearch($u,array$X,array$m){$si=preg_match('(LIKE|^!?~)',$X["op"]);$Tg=preg_match('~^(character( varying)?|text|citext|bpchar|name)$~',$m["type"])||(!$si&&preg_match('~'.number_type().'|^(date|time|timetz|timestamp|timestamptz|boolean)$~',$m["type"]));return($Tg&&!preg_match('~\[]$~',$m["full_type"])?$u:"CAST($u AS text)");}function
quoteBinary($zj){return"'\\x".bin2hex($zj)."'";}function
warnings(){return$this->conn->warnings();}function
tableHelp($C,$uf=false){$Wf=array("information_schema"=>"infoschema","pg_catalog"=>($uf?"view":"catalog"),);$_=$Wf[$_GET["ns"]];if($_)return"$_-".str_replace("_","-",$C).".html";}function
inheritsFrom($R){return
get_rows("SELECT relname AS table, nspname AS ns FROM pg_class JOIN pg_inherits ON inhparent = oid JOIN pg_namespace ON relnamespace = pg_namespace.oid WHERE inhrelid = ".$this->tableOid($R)." ORDER BY 2, 1");}function
inheritedTables($R){return
get_rows("SELECT relname AS table, nspname AS ns FROM pg_inherits JOIN pg_class ON inhrelid = oid JOIN pg_namespace ON relnamespace = pg_namespace.oid WHERE inhparent = ".$this->tableOid($R)." ORDER BY 2, 1");}function
partitionsInfo($R){$J=(min_version(10)?$this->conn->query("SELECT * FROM pg_partitioned_table WHERE partrelid = ".$this->tableOid($R))->fetch_assoc():null);if($J){$c=get_vals("SELECT attname FROM pg_attribute WHERE attrelid = $J[partrelid] AND attnum IN (".str_replace(" ",", ",$J["partattrs"]).")");$Ya=array('h'=>'HASH','l'=>'LIST','r'=>'RANGE');return
array("partition_by"=>$Ya[$J["partstrat"]],"partition"=>implode(", ",array_map('Adminer\idf_escape',$c)),);}return
array();}function
tableOid($R){return"(SELECT oid FROM pg_class WHERE relnamespace = $this->nsOid AND relname = ".q($R)." AND relkind IN ('r', 'm', 'v', 'f', 'p'))";}function
indexAlgorithms(array$Ck){static$I=array();if(!$I)$I=get_vals("SELECT amname FROM pg_am".(min_version(9.6)?" WHERE amtype = 'i'":"")." ORDER BY amname = '".($this->conn->flavor=='cockroach'?"prefix":"btree")."' DESC, amname");return$I;}function
indexOpclasses(){static$I=array();if(!$I&&$this->conn->flavor!='cockroach')$I=get_vals("SELECT DISTINCT opcname FROM pg_catalog.pg_opclass WHERE NOT opcdefault ORDER BY opcname");return$I;}function
supportsIndex(array$S){return$S["Engine"]!="view";}function
hasCStyleEscapes(){static$ab;if($ab===null)$ab=(get_val("SHOW standard_conforming_strings",0,$this->conn)=="off");return$ab;}}function
idf_escape($u){return'"'.str_replace('"','""',$u).'"';}function
table($u){return
idf_escape($u);}function
get_databases($Nd){return
get_vals("SELECT datname FROM pg_database
WHERE datallowconn = TRUE AND has_database_privilege(datname, 'CONNECT')
ORDER BY datname");}function
limit($G,$Z,$z,$ph=0,$Lj=" "){return" $G$Z".($z?$Lj."LIMIT $z".($ph?" OFFSET $ph":""):"");}function
limit1($R,$G,$Z,$Lj="\n"){return(preg_match('~^INTO~',$G)?limit($G,$Z,1,0,$Lj):" $G".(is_view(table_status1($R))?$Z:$Lj."WHERE ctid = (SELECT ctid FROM ".table($R).$Z.$Lj."LIMIT 1)"));}function
db_collation($j,array$vb){return
get_val("SELECT datcollate FROM pg_database WHERE datname = ".q($j));}function
logged_user(){return
get_val("SELECT user");}function
tables_list(){$G="SELECT table_name, table_type FROM information_schema.tables WHERE table_schema = current_schema()";if(support("materializedview"))$G
.="
UNION ALL
SELECT matviewname, 'MATERIALIZED VIEW'
FROM pg_matviews
WHERE schemaname = current_schema()";$G
.="
ORDER BY 1";return
get_key_vals($G);}function
count_tables(array$i){$I=array();foreach($i
as$j){if(connection()->select_db($j))$I[$j]=count(tables_list());}return$I;}function
table_status($C="",$vd=false){static$te;if($te===null)$te=get_val("SELECT 'pg_table_size'::regproc");$Pj=(!$vd&&min_version(10));$I=array();foreach(get_rows("SELECT
	relname AS \"Name\",
	CASE relkind WHEN 'v' THEN 'view' WHEN 'm' THEN 'materialized view' ELSE 'table' END AS \"Engine\"".($te?",
	pg_table_size(c.oid) AS \"Data_length\",
	pg_indexes_size(c.oid) AS \"Index_length\"":"").",
	obj_description(c.oid, 'pg_class') AS \"Comment\",
	".(min_version(12)?"''":"CASE WHEN relhasoids THEN 'oid' ELSE '' END")." AS \"Oid\",
	reltuples AS \"Rows\",
	".($Pj?"seq.last_value":"NULL")." AS \"Auto_increment\",
	".(min_version(10)?"relispartition::int AS partition,":"")."
	current_schema() AS nspname
FROM pg_class c
".($Pj?"LEFT JOIN (
	SELECT d.refobjid, max(s.last_value) AS last_value
	FROM pg_depend d
	JOIN pg_class sc ON sc.oid = d.objid AND sc.relkind = 'S' AND sc.relnamespace = ".driver()->nsOid."
	JOIN pg_sequences s ON s.schemaname = current_schema() AND s.sequencename = sc.relname
	WHERE d.classid = 'pg_class'::regclass AND d.refclassid = 'pg_class'::regclass AND d.deptype IN ('a', 'i')
	".($C!=""?"AND d.refobjid = ".driver()->tableOid($C):"")."
	GROUP BY d.refobjid
) seq ON seq.refobjid = c.oid
":"")."WHERE relkind IN ('r', 'm', 'v', 'f', 'p')
AND relnamespace = ".driver()->nsOid."
".($C!=""?"AND relname = ".q($C):"ORDER BY relname"))as$J)$I[$J["Name"]]=$J;return$I;}function
is_view(array$S){return
in_array($S["Engine"],array("view","materialized view"));}function
fk_support(array$S){return
true;}function
fields($R){$I=array();$va=array('timestamp without time zone'=>'timestamp','timestamp with time zone'=>'timestamptz','time without time zone'=>'time','time with time zone'=>'timetz',);foreach(get_rows("SELECT
	a.attname AS field,
	format_type(a.atttypid, a.atttypmod) AS full_type,
	pg_get_expr(d.adbin, d.adrelid) AS default,
	a.attnotnull::int,
	i.indrelid AS primary,
	t.typcategory,
	col_description(a.attrelid, a.attnum) AS comment".(min_version(10)?",
	a.attidentity".(min_version(12)?",
	a.attgenerated":""):"")."
FROM pg_attribute a
JOIN pg_type t ON t.oid = a.atttypid
LEFT JOIN pg_attrdef d ON a.attrelid = d.adrelid AND a.attnum = d.adnum
LEFT JOIN pg_index i ON a.attrelid = i.indrelid AND a.attnum = ANY(i.indkey) AND i.indisprimary
WHERE a.attrelid = ".driver()->tableOid($R)."
AND NOT a.attisdropped
AND a.attnum > 0
ORDER BY a.attnum")as$J){preg_match('~([^([]+)(\((.*)\))?([a-z ]+)?((\[[0-9]*])*)$~',$J["full_type"],$A);list(,$U,$y,$J["length"],$oa,$Ba)=$A;$J["length"].=$Ba;$jb=$U.$oa;if(isset($va[$jb])){$J["type"]=$va[$jb];$J["full_type"]=$J["type"].$y.$Ba;}else{$J["type"]=$U;$J["full_type"]=$J["type"].$y.$oa.$Ba;}if(in_array($J['attidentity'],array('a','d')))$J['default']='GENERATED '.($J['attidentity']=='d'?'BY DEFAULT':'ALWAYS').' AS IDENTITY';$J["generated"]=idx(array("s"=>"STORED","v"=>"VIRTUAL"),$J["attgenerated"],"");$J["composite"]=($J["typcategory"]=="C");$J["null"]=!$J["attnotnull"];$J["auto_increment"]=$J['attidentity']||preg_match('~^nextval\(~i',$J["default"])||preg_match('~^unique_rowid\(~',$J["default"]);$J["privileges"]=array("insert"=>1,"select"=>1,"update"=>1,"where"=>1,"order"=>1);if(!$J['generated']&&preg_match('~(.+)::[^,)]+(.*)~',$J["default"],$A))$J["default"]=($A[1]=="NULL"?null:idf_unescape($A[1]).$A[2]);$I[$J["field"]]=$J;}return$I;}function
indexes($R,$g=null){$g=connection($g);$I=array();$Hk=driver()->tableOid($R);$e=get_key_vals("SELECT attnum, attname FROM pg_attribute WHERE attrelid = $Hk AND attnum > 0",$g);foreach(get_rows("SELECT relname, indisunique::int, indisprimary::int, indkey, indoption, amname,
	pg_get_expr(indpred, indrelid, true) AS partial, pg_get_expr(indexprs, indrelid) AS indexpr".($g->flavor=='cockroach'?"":",
	(SELECT string_agg(CASE WHEN opcdefault THEN '' ELSE opcname END, ' ' ORDER BY s)
		FROM generate_subscripts(indclass, 1) AS s JOIN pg_catalog.pg_opclass ON pg_opclass.oid = indclass[s]) AS opclasses")."
FROM pg_index
JOIN pg_class ON indexrelid = oid
JOIN pg_am ON pg_am.oid = pg_class.relam
WHERE indrelid = $Hk
ORDER BY indisprimary DESC, indisunique DESC",$g)as$J){$jj=$J["relname"];$I[$jj]["type"]=($J["indisprimary"]?"PRIMARY":($J["indisunique"]?"UNIQUE":"INDEX"));$I[$jj]["columns"]=array();$I[$jj]["descs"]=array();$I[$jj]["algorithm"]=$J["amname"];$I[$jj]["partial"]=$J["partial"];$Xe=preg_split('~(?<=\)), (?=\()~',$J["indexpr"]);foreach(explode(" ",$J["indkey"])as$Ye)$I[$jj]["columns"][]=($Ye?$e[$Ye]:array_shift($Xe));foreach(explode(" ",$J["indoption"])as$Ze)$I[$jj]["descs"][]=(intval($Ze)&1?'1':null);$I[$jj]["opclasses"]=($J["opclasses"]!=""?explode(" ",$J["opclasses"]):array());$I[$jj]["lengths"]=array();}return$I;}function
foreign_keys($R){$I=array();foreach(get_rows("SELECT conname, condeferrable::int AS deferrable, condeferred::int AS deferred, pg_get_constraintdef(oid) AS definition
FROM pg_constraint
WHERE conrelid = ".driver()->tableOid($R)."
AND contype = 'f'::char
ORDER BY conkey, conname")as$J){$J['deferrable']=($J['deferrable']?'':'NOT ').'DEFERRABLE'.($J['deferred']?' INITIALLY DEFERRED':'');if(preg_match('~FOREIGN KEY\s*\((.+)\)\s*REFERENCES (.+)\((.+)\)(.*)$~iA',$J['definition'],$A)){$J['source']=array_map('Adminer\idf_unescape',array_map('trim',explode(',',$A[1])));if(preg_match('~^(("([^"]|"")+"|[^"]+)\.)?"?("([^"]|"")+"|[^"]+)$~',$A[2],$fg)){$J['ns']=idf_unescape($fg[2]);$J['table']=idf_unescape($fg[4]);}$J['target']=array_map('Adminer\idf_unescape',array_map('trim',explode(',',$A[3])));$J['on_delete']=(preg_match("~ON DELETE (".driver()->onActions.")~",$A[4],$fg)?$fg[1]:'NO ACTION');$J['on_update']=(preg_match("~ON UPDATE (".driver()->onActions.")~",$A[4],$fg)?$fg[1]:'NO ACTION');$I[$J['conname']]=$J;}}return$I;}function
view($C){return
array("select"=>trim(get_val("SELECT pg_get_viewdef(".driver()->tableOid($C).")")));}function
collations(){return
array();}function
information_schema($j,$L=""){return
in_array($L!=""?$L:get_schema(),array("information_schema","pg_catalog","pg_toast"));}function
error(){$I=h(connection()->error);if(preg_match('~^(.*\n)?([^\n]*)\n( *)\^(\n.*)?$~s',$I,$A))$I=$A[1].preg_replace('~((?:[^&]|&[^;]*;){'.strlen($A[3]).'})(.*)~','\1<b>\2</b>',$A[2]).$A[4];return
nl_br($I);}function
create_database($j,$ub){return
queries("CREATE DATABASE ".idf_escape($j).($ub?" ENCODING ".idf_escape($ub):""));}function
drop_databases(array$i){connection()->close();return
apply_queries("DROP DATABASE",$i,'Adminer\idf_escape');}function
rename_database($C,$ub){connection()->close();return!!queries("ALTER DATABASE ".idf_escape(DB)." RENAME TO ".idf_escape($C));}function
auto_increment(){return"";}function
alter_table($R,$C,array$n,array$Pd,$_b,$Vc,$ub,$Ga,$ki){$b=array();$Ui=array();if($R!=""&&$R!=$C)$Ui[]="ALTER TABLE ".table($R)." RENAME TO ".table($C);$Mj="";foreach($n
as$m){$d=idf_escape($m[0]);$X=$m[1];if(!$X)$b[]="DROP $d";else{$bm=$X[5];unset($X[5]);if($m[0]==""){if(isset($X[6]))$X[1]=($X[1]==" bigint"?" big":($X[1]==" smallint"?" small":" "))."serial";$b[]=($R!=""?"ADD ":"  ").implode($X);if(isset($X[6]))$b[]=($R!=""?"ADD":" ")." PRIMARY KEY ($X[0])";}else{if($d!=$X[0])$Ui[]="ALTER TABLE ".table($C)." RENAME $d TO $X[0]";$b[]="ALTER $d TYPE$X[1]";$Nj=$R."_".idf_unescape($X[0])."_seq";$b[]="ALTER $d ".($X[3]?"SET".preg_replace('~GENERATED ALWAYS(.*) (STORED|VIRTUAL)~','EXPRESSION\1',$X[3]):(isset($X[6])?"SET DEFAULT nextval(".q($Nj).")":"DROP DEFAULT"));if(isset($X[6]))$Mj="CREATE SEQUENCE IF NOT EXISTS ".idf_escape($Nj)." OWNED BY ".idf_escape($R).".$X[0]";$b[]="ALTER $d ".($X[2]==" NULL"?"DROP NOT":"SET").$X[2];}if($m[0]!=""||$bm!="")$Ui[]="COMMENT ON COLUMN ".table($C).".$X[0] IS ".($bm!=""?substr($bm,9):"''");}}$b=array_merge($b,$Pd);if($R==""){$P="";if($ki){$qb=(connection()->flavor=='cockroach');$P=" PARTITION BY $ki[partition_by]($ki[partition])";if($ki["partition_by"]=='HASH'){$li=+$ki["partitions"];for($s=0;$s<$li;$s++)$Ui[]="CREATE TABLE ".idf_escape($C."_$s")." PARTITION OF ".idf_escape($C)." FOR VALUES WITH (MODULUS $li, REMAINDER $s)";}else{$Ii="MINVALUE";foreach($ki["partition_names"]as$s=>$X){$Y=$ki["partition_values"][$s];$gi=" VALUES ".($ki["partition_by"]=='LIST'?"IN ($Y)":"FROM ($Ii) TO ($Y)");if($qb)$P
.=($s?",":" (")."\n  PARTITION ".(preg_match('~^DEFAULT$~i',$X)?$X:idf_escape($X))."$gi";else$Ui[]="CREATE TABLE ".idf_escape($C."_$X")." PARTITION OF ".idf_escape($C)." FOR$gi";$Ii=$Y;}$P
.=($qb?"\n)":"");}}array_unshift($Ui,"CREATE TABLE ".table($C)." (\n".implode(",\n",$b)."\n)$P");}elseif($b)array_unshift($Ui,"ALTER TABLE ".table($R)."\n".implode(",\n",$b));if($Mj)array_unshift($Ui,$Mj);if($_b!==null)$Ui[]="COMMENT ON TABLE ".table($C)." IS ".q($_b);foreach($Ui
as$G){if(!queries($G))return
false;}if($Ga!=""){foreach(fields($C)as$yd=>$m){if($m["auto_increment"])return!!queries("SELECT setval(pg_get_serial_sequence(".q(table($C)).", ".q($yd)."), $Ga)");}}return
true;}function
alter_indexes($R,$b){$h=array();$Hc=array();$Ui=array();foreach($b
as$X){if($X[0]!="INDEX")$h[]=($X[2]=="DROP"?"\nDROP CONSTRAINT ".idf_escape($X[1]):"\nADD".($X[1]!=""?" CONSTRAINT ".idf_escape($X[1]):"")." $X[0] ".($X[0]=="PRIMARY"?"KEY ":"")."(".implode(", ",$X[2]).")");elseif($X[2]=="DROP")$Hc[]=idf_escape($X[1]);else$Ui[]="CREATE INDEX ".idf_escape($X[1]!=""?$X[1]:uniqid($R."_"))." ON ".table($R).($X[3]?" USING $X[3]":"")." (".implode(", ",$X[2]).")".($X[4]?" WHERE $X[4]":"");}if($h)array_unshift($Ui,"ALTER TABLE ".table($R).implode(",",$h));if($Hc)array_unshift($Ui,"DROP INDEX ".implode(", ",$Hc));foreach($Ui
as$G){if(!queries($G))return
false;}return
true;}function
truncate_tables(array$T){return!!queries("TRUNCATE ".implode(", ",array_map('Adminer\table',$T)));}function
drop_kinds(array$T){$I=array("MATERIALIZED VIEW"=>array(),"VIEW"=>array(),"TABLE"=>array());foreach($T
as$C=>$S)$I[strtoupper($S["Engine"])][]=idf_escape($S["nspname"]).".".table($C);return
array_filter($I);}function
drop_views(array$jm){return
drop_tables($jm);}function
drop_tables(array$T){$sk=array();foreach($T
as$R)$sk[$R]=table_status1($R);foreach(drop_kinds($sk)as$Df=>$Sg){if(!queries("DROP $Df ".implode(", ",$Sg)))return
false;}return
true;}function
move_tables(array$T,array$jm,$Sk){foreach(array_merge($T,$jm)as$R){$P=table_status1($R);if(!queries("ALTER ".strtoupper($P["Engine"])." ".table($R)." SET SCHEMA ".idf_escape($Sk)))return
false;}return
true;}function
trigger($C,$R){if($C=="")return
array("Statement"=>"EXECUTE PROCEDURE ()");$e=array();$Z="WHERE trigger_schema = current_schema() AND event_object_table = ".q($R)." AND trigger_name = ".q($C);foreach(get_rows("SELECT * FROM information_schema.triggered_update_columns $Z")as$J)$e[]=$J["event_object_column"];$I=array();foreach(get_rows('SELECT trigger_name AS "Trigger", action_timing AS "Timing", event_manipulation AS "Event", \'FOR EACH \' || action_orientation AS "Type", action_statement AS "Statement"
FROM information_schema.triggers'."
$Z
ORDER BY event_manipulation DESC")as$J){if($e&&$J["Event"]=="UPDATE")$J["Event"].=" OF";$J["Of"]=implode(", ",$e);if($I)$J["Event"].=" OR $I[Event]";$I=$J;}return$I;}function
triggers($R){$I=array();foreach(get_rows("SELECT * FROM information_schema.triggers WHERE trigger_schema = current_schema() AND event_object_table = ".q($R))as$J){$tl=trigger($J["trigger_name"],$R);$I[$tl["Trigger"]]=array($tl["Timing"],$tl["Event"]);}return$I;}function
trigger_options(){return
array("Timing"=>array("BEFORE","AFTER"),"Event"=>array("INSERT","UPDATE","UPDATE OF","DELETE","INSERT OR UPDATE","INSERT OR UPDATE OF","DELETE OR INSERT","DELETE OR UPDATE","DELETE OR UPDATE OF","DELETE OR INSERT OR UPDATE","DELETE OR INSERT OR UPDATE OF",),"Type"=>array("FOR EACH ROW","FOR EACH STATEMENT"),);}function
routine($C,$U){$K=get_rows('SELECT routine_definition AS definition, LOWER(external_language) AS language, *
FROM information_schema.routines
WHERE routine_schema = current_schema() AND specific_name = '.q($C));$I=idx($K,0,array());$I["returns"]=array("type"=>preg_replace('~^_(.*)~','\1[]',"$I[type_udt_name]"));$I["fields"]=get_rows("SELECT COALESCE(parameter_name, ordinal_position::text) AS field,
	CASE data_type WHEN 'USER-DEFINED' THEN udt_name WHEN 'ARRAY' THEN substr(udt_name, 2) || '[]' ELSE data_type END AS type,
	character_maximum_length AS length, parameter_mode AS inout
FROM information_schema.parameters
WHERE specific_schema = current_schema() AND specific_name = ".q($C)."
ORDER BY ordinal_position");return$I;}function
routines(){return
get_rows('SELECT specific_name AS "SPECIFIC_NAME", routine_type AS "ROUTINE_TYPE", routine_name AS "ROUTINE_NAME", type_udt_name AS "DTD_IDENTIFIER"
FROM information_schema.routines
WHERE routine_schema = current_schema()'.(connection()->flavor=='cockroach'?'':"
AND substring(specific_name, '[0-9]+\$')::oid NOT IN (SELECT objid FROM pg_catalog.pg_depend WHERE classid = 'pg_proc'::regclass AND deptype = 'e')").'
ORDER BY SPECIFIC_NAME');}function
routine_languages(){return
get_vals("SELECT LOWER(lanname) FROM pg_catalog.pg_language");}function
routine_id($C,array$J){$I=array();foreach($J["fields"]as$m){$y=$m["length"];$I[]=$m["type"].($y?"($y)":"");}return
idf_escape($C)."(".implode(", ",$I).")";}function
last_id($H){$J=(is_object($H)?$H->fetch_row():array());return($J?$J[0]:0);}function
explain(Db$f,$G){return$f->query("EXPLAIN $G");}function
found_rows(array$S,array$Z){if(preg_match("~ rows=([0-9]+)~",get_val("EXPLAIN SELECT * FROM ".idf_escape($S["Name"]).($Z?" WHERE ".implode(" AND ",$Z):"")),$ij))return$ij[1];}function
types($rd=false){$qb=connection()->flavor=='cockroach';$Ef=($qb?"'e'":"'b','c','d','e'".(min_version(9.2)?",'r'":""));return
get_key_vals("SELECT t.oid, t.typname
FROM pg_type t
WHERE t.typnamespace = ".driver()->nsOid."
AND t.typtype IN ($Ef)".($qb?"
AND t.typelem = 0":"
AND (t.typrelid = 0 OR (SELECT c.relkind FROM pg_class c WHERE c.oid = t.typrelid) = 'c')"."
AND NOT EXISTS (SELECT 1 FROM pg_type e WHERE e.typarray = t.oid)".($rd?'':"
AND t.oid NOT IN (SELECT objid FROM pg_catalog.pg_depend WHERE classid = 'pg_type'::regclass AND deptype = 'e')"))."
ORDER BY t.typname");}function
type_values($t){$Zc=get_vals("SELECT enumlabel FROM pg_enum WHERE enumtypid = $t ORDER BY enumsortorder");return($Zc?"'".implode("', '",array_map('addslashes',$Zc))."'":"");}function
collation_name($qh){return(min_version(9.1)?"(SELECT collname FROM pg_collation WHERE oid = $qh AND collname != 'default')":"NULL");}function
type_definition($t){$U=first(get_rows("SELECT typtype, typisdefined::int AS defined, typrelid FROM pg_type WHERE oid = $t"));$I=array("kind"=>($U?$U["typtype"]:""),"definition"=>"");if(!$U||!$U["defined"])return$I;switch($I["kind"]){case'e':$em=get_vals("SELECT enumlabel FROM pg_enum WHERE enumtypid = $t ORDER BY enumsortorder");$I["definition"]="AS ENUM (".implode(", ",array_map('Adminer\q',$em)).")";break;case'c':$e=array();foreach(get_rows("SELECT attname, format_type(atttypid, atttypmod) AS full_type, ".collation_name("attcollation")." AS collation
FROM pg_attribute
WHERE attrelid = $U[typrelid] AND attnum > 0 AND NOT attisdropped
ORDER BY attnum")as$J)$e[]=idf_escape($J["attname"])." $J[full_type]".($J["collation"]?" COLLATE ".idf_escape($J["collation"]):"");$I["definition"]="AS (\n\t".implode(",\n\t",$e)."\n)";break;case'd':$Ec=first(get_rows("SELECT format_type(typbasetype, typtypmod) AS base, typnotnull::int AS notnull, typdefault, ".collation_name("typcollation")." AS collation
FROM pg_type WHERE oid = $t"));$I["definition"]="AS $Ec[base]".($Ec["collation"]?" COLLATE ".idf_escape($Ec["collation"]):"").($Ec["typdefault"]!=""?" DEFAULT $Ec[typdefault]":"").($Ec["notnull"]?" NOT NULL":"");foreach(get_rows("SELECT conname, pg_get_constraintdef(oid) AS definition FROM pg_constraint WHERE contypid = $t AND contype != 'n' ORDER BY conname")as$J)$I["definition"].=" CONSTRAINT ".idf_escape($J["conname"])." $J[definition]";break;case'r':$Yi=first(get_rows("SELECT format_type(rngsubtype, NULL) AS subtype,
(SELECT opcname FROM pg_opclass WHERE oid = rngsubopc) AS subtype_opclass,
".collation_name("rngcollation")." AS collation,
NULLIF(rngcanonical, 0)::regproc::text AS canonical,
NULLIF(rngsubdiff, 0)::regproc::text AS subtype_diff".(min_version(14)?",
(SELECT typname FROM pg_type WHERE oid = rngmultitypid) AS multirange_type_name":"")."
FROM pg_range WHERE rngtypid = $t"));$Gh=array();foreach(array("subtype"=>0,"subtype_opclass"=>1,"collation"=>1,"canonical"=>0,"subtype_diff"=>0,"multirange_type_name"=>1)as$x=>$dd){if($Yi[$x]!="")$Gh[]=strtoupper($x)." = ".($dd?idf_escape($Yi[$x]):$Yi[$x]);}$I["definition"]="AS RANGE (".implode(", ",$Gh).")";}return$I;}function
schemas(){return
get_vals("SELECT nspname FROM pg_namespace ORDER BY nspname");}function
get_schema(){return(string)get_val("SELECT current_schema()");}function
set_schema($L,$g=null){$I=connection($g)->query("SET search_path TO ".idf_escape($L));driver()->setUserTypes(types(true));return!!$I;}function
drop_sql(array$T){$I="";foreach(drop_kinds($T)as$Df=>$Sg)$I
.="DROP $Df IF EXISTS ".implode(", ",$Sg).";\n";return($I?"$I\n":"");}function
foreign_keys_sql($R){$I="";$P=table_status1($R);$fh=idf_escape($P['nspname']);$Ld=foreign_keys($R);ksort($Ld);foreach($Ld
as$Kd=>$Jd)$I
.="ALTER TABLE ONLY $fh.".idf_escape($P['Name'])." ADD CONSTRAINT ".idf_escape($Kd)." ".preg_replace('~( REFERENCES )([^(.]+\()~',"\\1$fh.\\2",$Jd["definition"]).";\n";return($I?"$I\n":$I);}function
indexes_sql($R,$Ki=""){$I="";$G="SELECT indexdef FROM pg_catalog.pg_indexes WHERE schemaname = current_schema() AND tablename = ".q($R).($Ki!=""?" AND indexname != ".q($Ki):"");foreach(get_rows($G,null,"-- ")as$J)$I
.="\n\n$J[indexdef];";return$I;}function
create_sql($R,$Ga,$vk){$pj=array();$Pj=array();$Qj=array();$Oj=array();$P=table_status1($R);$fh=idf_escape($P['nspname']);if(is_view($P)){$im=view($R);$h="CREATE ".strtoupper($P["Engine"])." $fh.".idf_escape($R)." AS ".rtrim($im["select"],";").";";return
rtrim($h.indexes_sql($R),';');}$n=fields($R);if(count($P)<2||empty($n))return"";$I="CREATE TABLE $fh.".idf_escape($P['Name'])." (\n    ";$Fk=q("$fh.".idf_escape($P['Name']));foreach($n
as$m){$Rj="";if($m['default']=="nextval('$P[Name]_$m[field]_seq')"){$Rj="$fh.".idf_escape("$P[Name]_$m[field]_seq");$m['default']=null;$m['full_type']=preg_replace('~int(eger)?~','serial',$m['full_type']);}$ei=idf_escape($m['field']).' '.$m['full_type'].preg_replace('~(nextval\(\')([^.\']+\')~','\1'.str_replace("'","''",$P['nspname']).'.\2',default_value($m)).($m['null']?"":" NOT NULL");$pj[]=$ei;if(preg_match('~nextval\(\'([^\']+)\'\)~',$m['default'],$gg)){$Nj=$gg[1];$ik=first(get_rows((min_version(10)?"SELECT *, cache_size AS cache_value FROM pg_sequences WHERE schemaname = current_schema() AND sequencename = ".q(idf_unescape($Nj)):"SELECT * FROM $Nj"),null,"-- "));$Pj[]=($vk=="DROP+CREATE"?"DROP SEQUENCE IF EXISTS $fh.$Nj;\n":"")."CREATE SEQUENCE $fh.$Nj INCREMENT $ik[increment_by] MINVALUE $ik[min_value] MAXVALUE $ik[max_value]"." CACHE $ik[cache_value];";if(get_val("SELECT pg_get_serial_sequence($Fk, ".q($m['field']).")"))$Qj[]="\n\nALTER SEQUENCE $fh.$Nj OWNED BY $fh.".idf_escape($P['Name']).".".idf_escape($m['field']).";";if($Ga)$Oj[]="$fh.$Nj";}elseif($Ga&&$m['auto_increment'])$Oj[]=($Rj?:get_val("SELECT pg_get_serial_sequence($Fk, ".q($m['field']).")"));}if(!empty($Pj))$I=implode("\n\n",$Pj)."\n\n$I";$Ki="";foreach(indexes($R)as$Ve=>$v){if($v['type']=='PRIMARY'){$Ki=$Ve;$pj[]="CONSTRAINT ".idf_escape($Ve)." PRIMARY KEY (".implode(', ',array_map('Adminer\idf_escape',$v['columns'])).")";}}foreach(driver()->checkConstraints($R)as$Hb=>$Jb)$pj[]="CONSTRAINT ".idf_escape($Hb)." CHECK ($Jb)";$I
.=implode(",\n    ",$pj)."\n)";$gi=driver()->partitionsInfo($P['Name']);if($gi)$I
.="\nPARTITION BY $gi[partition_by]($gi[partition])";$I
.="\nWITH (oids = ".($P['Oid']?'true':'false').");";$I
.=implode($Qj);if($P['Comment'])$I
.="\n\nCOMMENT ON TABLE $fh.".idf_escape($P['Name'])." IS ".q($P['Comment']).";";foreach($n
as$yd=>$m){if($m['comment'])$I
.="\n\nCOMMENT ON COLUMN $fh.".idf_escape($P['Name']).".".idf_escape($yd)." IS ".q($m['comment']).";";}$I
.=indexes_sql($R,$Ki);foreach(array_filter($Oj)as$Mj){$ik=first(get_rows("SELECT last_value, is_called::int FROM $Mj",null,"-- "));if($ik['is_called'])$I
.="\n\nDO \$\$ BEGIN PERFORM setval(".q($Mj).", $ik[last_value]); END \$\$;";}return
rtrim($I,';');}function
truncate_sql($R){return"TRUNCATE ".table($R);}function
truncate_all_sql(array$T){return($T?"TRUNCATE ".implode(", ",array_map('Adminer\table',$T)).";\n\n":"");}function
trigger_sql($R){$P=table_status1($R);$I="";foreach(triggers($R)as$sl=>$rl){$tl=trigger($sl,$P['Name']);$I
.="\nCREATE TRIGGER ".idf_escape($tl['Trigger'])." $tl[Timing] $tl[Event] ON ".idf_escape($P["nspname"]).".".idf_escape($P['Name'])." $tl[Type] $tl[Statement];;\n";}return$I;}function
use_sql($fc,$vk=""){$C=idf_escape($fc);$I="";if(preg_match('~CREATE~',$vk)){if($vk=="DROP+CREATE")$I="DROP DATABASE IF EXISTS $C;\n";$I
.="CREATE DATABASE $C;\n";}return"$I\\connect $C";}function
show_variables(){return
get_rows("SHOW ALL");}function
process_list(){return
get_rows("SELECT * FROM pg_stat_activity ORDER BY ".(min_version(9.2)?"pid":"procpid"));}function
convert_field(array$m){}function
unconvert_field(array$m,$I){return($m["composite"]?"$I::$m[type]":$I);}function
support($wd){return
preg_match('~^(check|columns|comment|database|drop_col|dump|descidx|fast_status|indexes|kill|partial_indexes|routine|scheme|sequence|sql|table'.'|transaction_ddl|trigger|type|variables|view'.(min_version(9.3)?'|materializedview':'').(min_version(11)?'|procedure':'').(connection()->flavor=='cockroach'?'':'|deferrable').(connection()->flavor=='cockroach'?'':'|processlist').')$~',$wd);}function
kill_process($t){return
queries("SELECT pg_terminate_backend(".number($t).")");}function
connection_id(){return"SELECT pg_backend_pid()";}function
max_connections(){return
get_val("SHOW max_connections");}}add_driver("sqlite","SQLite");if(isset($_GET["sqlite"])){define('Adminer\DRIVER',"sqlite");if(class_exists("SQLite3")&&$_GET["ext"]!="pdo"){abstract
class
SqliteDb
extends
SqlDb{var$extension="SQLite3";private$link;function
attach($o,$V,$E){$this->link=new
\SQLite3($o);$hm=\SQLite3::version();$this->server_info=$hm["versionString"];return'';}function
query($G,$Cl=false){$H=@$this->link->query($G);$this->error="";if(!$H){$this->errno=$this->link->lastErrorCode();$this->error=$this->link->lastErrorMsg();return
false;}elseif($H->numColumns())return
new
Result($H);$this->affected_rows=$this->link->changes();return
true;}function
quote($Q){return(is_utf8($Q)?"'".$this->link->escapeString($Q)."'":"x'".bin2hex($Q)."'");}}class
Result{var$num_rows;private$result,$offset=0;function
__construct($H){$this->result=$H;}function
fetch_assoc(){return$this->result->fetchArray(SQLITE3_ASSOC);}function
fetch_row(){return$this->result->fetchArray(SQLITE3_NUM);}function
fetch_field(){$Bl=array(1=>"integer","real","text","blob","null");$d=$this->offset++;$U=$this->result->columnType($d);return(object)array("name"=>$this->result->columnName($d),"type"=>($U==SQLITE3_TEXT?15:0),"native_type"=>$Bl[$U],"charsetnr"=>($U==SQLITE3_BLOB?63:0),);}}}elseif(extension_loaded("pdo_sqlite")){abstract
class
SqliteDb
extends
PdoDb{var$extension="PDO_SQLite";function
attach($o,$V,$E){return$this->dsn(DRIVER.":$o","","");}function
quote($Q){return(is_utf8($Q)?parent::quote($Q):"x'".bin2hex($Q)."'");}}}if(class_exists('Adminer\SqliteDb')){class
Db
extends
SqliteDb{function
attach($o,$V,$E){parent::attach($o,$V,$E);$this->query("PRAGMA foreign_keys = 1");$this->query("PRAGMA busy_timeout = 500");return'';}function
select_db($o){$G="ATTACH ".$this->quote(preg_match("~(^[/\\\\]|:)~",$o)?$o:dirname($_SERVER["SCRIPT_FILENAME"])."/$o")." AS a";if(is_readable($o)&&$this->query($G))return!self::attach($o,'','');return
false;}}}class
Driver
extends
SqlDriver{static$extensions=array("SQLite3","PDO_SQLite");static$jush="sqlite";static$passwords=false;protected$types=array(array("integer"=>0,"real"=>0,"numeric"=>0,"text"=>0,"blob"=>0));var$insertFunctions=array();var$editFunctions=array("integer|real|numeric"=>"+/-","text"=>"||",);var$operators=array("=","<",">","<=",">=","!=","LIKE","LIKE %%","IN","IS NULL","NOT LIKE","NOT IN","IS NOT NULL","SQL");var$functions=array("hex","length","lower","round","unixepoch","upper");var$grouping=array("avg","count","count distinct","group_concat","max","min","sum");static
function
connect($N,$V,$E){return
parent::connect(":memory:","","");}function
__construct(Db$f){parent::__construct($f);if(min_version(3.31,0,$f))$this->generated=array("STORED","VIRTUAL");if(min_version(3.37,0,$f))$this->types[0]["any"]=0;}function
structuredTypes(){return
array_keys($this->types[0]);}function
quoteBinary($zj){return"x".q(bin2hex($zj));}function
engines(){$I=array("table");if(min_version("3.8.2")){if(min_version(3.37)){$I[]="STRICT";$I[]="STRICT, WITHOUT ROWID";}$I[]="WITHOUT ROWID";}return$I;}function
insertUpdate($R,array$K,array$Ki){$em=array();foreach($K
as$O)$em[]="(".implode(", ",$O).")";return
queries("REPLACE INTO ".table($R)." (".implode(", ",array_keys(reset($K))).") VALUES\n".implode(",\n",$em));}function
tableHelp($C,$uf=false){if(preg_match('~^sqlite_(seq|stat.)~',$C,$A))return"fileformat2.html#$A[1]tab";if(preg_match('~^sqlite(_temp)?_(master|schema)$~',$C))return"schematab.html";}function
checkConstraints($R){preg_match_all('~ CHECK *(\( *(((?>[^()]*[^() ])|(?1))*) *\))~',get_val("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = ".q($R),0,$this->conn),$gg);return
array_combine($gg[2],$gg[2]);}function
allFields(){$I=array();foreach(tables_list()as$R=>$U){foreach(fields($R)as$m)$I[$R][]=$m;}return$I;}}function
idf_escape($u){return'"'.str_replace('"','""',$u).'"';}function
table($u){return
idf_escape($u);}function
get_databases($Nd){return
array();}function
limit($G,$Z,$z,$ph=0,$Lj=" "){return" $G$Z".($z?$Lj."LIMIT $z".($ph?" OFFSET $ph":""):"");}function
limit1($R,$G,$Z,$Lj="\n"){return(preg_match('~^INTO~',$G)||get_val("SELECT sqlite_compileoption_used('ENABLE_UPDATE_DELETE_LIMIT')")?limit($G,$Z,1,0,$Lj):" $G WHERE rowid = (SELECT rowid FROM ".table($R).$Z.$Lj."LIMIT 1)");}function
db_collation($j,array$vb){return
get_val("PRAGMA encoding");}function
logged_user(){return
get_current_user();}function
tables_list(){return
get_key_vals("SELECT name, type FROM sqlite_master WHERE type IN ('table', 'view') ORDER BY (name LIKE 'sqlite_%'), name");}function
count_tables(array$i){return
array();}function
db_status(){$Zh=get_val("PRAGMA page_size");$Vd=get_val("PRAGMA freelist_count")*$Zh;return
array("Data_length"=>get_val("PRAGMA page_count")*$Zh-$Vd,"Index_length"=>0,"Data_free"=>$Vd,);}function
table_status($C="",$vd=false){$I=array();$K=array();if(!$vd&&$C==""){connection()->query("PRAGMA optimize = 0x10002");$K=get_key_vals("SELECT tbl, MAX(CAST(stat AS integer)) FROM sqlite_stat1 GROUP BY tbl");}foreach(get_rows("SELECT name AS Name, type AS Engine, sql, 'rowid' AS Oid, '' AS Auto_increment FROM sqlite_master WHERE type IN ('table', 'view') ".($C!=""?"AND name = ".q($C):"ORDER BY (name LIKE 'sqlite_%'), name"))as$J){if($J["Engine"]=="table"){$xk=preg_replace('~.*\)~s','',$J["sql"]);$J["Engine"]=implode(", ",array_filter(array((preg_match('~\bSTRICT\b~i',$xk)?"STRICT":0),(preg_match('~\bWITHOUT\s+ROWID\b~i',$xk)?"WITHOUT ROWID":0),)))?:"table";}unset($J["sql"]);$J["Rows"]=idx($K,$J["Name"],0);$I[$J["Name"]]=$J;}if(!$vd){foreach(get_rows("SELECT * FROM sqlite_sequence".($C!=""?" WHERE name = ".q($C):""),null,"")as$J)$I[$J["name"]]["Auto_increment"]=$J["seq"];}return$I;}function
is_view(array$S){return$S["Engine"]=="view";}function
fk_support(array$S){return!get_val("SELECT sqlite_compileoption_used('OMIT_FOREIGN_KEY')");}function
fields($R){$I=array();$jk=get_val("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = ".q($R));$Pi=array("select"=>1,"where"=>1,"order"=>1);if(!preg_match('~^sqlite(_temp)?_(master|schema)$~',$R))$Pi+=array("insert"=>1,"update"=>1);foreach(get_rows("PRAGMA table_".(min_version(3.31)?"x":"")."info(".table($R).")")as$J){$C=$J["name"];$U=strtolower($J["type"]);$k=$J["dflt_value"];$I[$C]=array("field"=>$C,"type"=>(preg_match('~int~i',$U)?"integer":(preg_match('~char|clob|text~i',$U)?"text":(preg_match('~blob~i',$U)?"blob":(preg_match('~real|floa|doub~i',$U)?"real":(preg_match('~any~i',$U)?"any":"numeric"))))),"full_type"=>$U,"default"=>(preg_match("~^'(.*)'$~",$k,$A)?str_replace("''","'",$A[1]):($k=="NULL"?null:$k)),"null"=>!$J["notnull"],"privileges"=>$Pi,"primary"=>$J["pk"],);if($J["pk"]&&preg_match('~\bAUTOINCREMENT\b~i',$jk))$I[$C]["auto_increment"]=true;}$u='(("[^"]*+")+|[a-z0-9_]+)';preg_match_all('~'.$u.'\s+text\s+COLLATE\s+(\'[^\']+\'|\S+)~i',$jk,$gg,PREG_SET_ORDER);foreach($gg
as$A){$C=str_replace('""','"',preg_replace('~^"|"$~','',$A[1]));if($I[$C])$I[$C]["collation"]=trim($A[3],"'");}preg_match_all('~'.$u.'\s.*GENERATED ALWAYS AS \((.+)\) (STORED|VIRTUAL)~i',$jk,$gg,PREG_SET_ORDER);foreach($gg
as$A){$C=str_replace('""','"',preg_replace('~^"|"$~','',$A[1]));$I[$C]["default"]=$A[3];$I[$C]["generated"]=strtoupper($A[4]);}return$I;}function
indexes($R,$g=null){$g=connection($g);$I=array();$jk=get_val("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = ".q($R),0,$g);if(preg_match('~\bPRIMARY\s+KEY\s*\((([^)"]+|"[^"]*"|`[^`]*`)++)~i',$jk,$A)){$I[""]=array("type"=>"PRIMARY","columns"=>array(),"lengths"=>array(),"descs"=>array());preg_match_all('~((("[^"]*+")+|(?:`[^`]*+`)+)|(\S+))(\s+(ASC|DESC))?(,\s*|$)~i',$A[1],$gg,PREG_SET_ORDER);foreach($gg
as$A){$I[""]["columns"][]=idf_unescape($A[2]).$A[4];$I[""]["descs"][]=(preg_match('~DESC~i',$A[5])?'1':null);}}if(!$I){foreach(fields($R)as$C=>$m){if($m["primary"])$I[""]=array("type"=>"PRIMARY","columns"=>array($C),"lengths"=>array(),"descs"=>array(null));}}$ok=get_key_vals("SELECT name, sql FROM sqlite_master WHERE type = 'index' AND tbl_name = ".q($R),$g);foreach(get_rows("PRAGMA index_list(".table($R).")",$g)as$J){$C=$J["name"];$v=array("type"=>($J["unique"]?"UNIQUE":"INDEX"));$v["lengths"]=array();$v["descs"]=array();foreach(get_rows("PRAGMA index_info(".idf_escape($C).")",$g)as$yj){$v["columns"][]=$yj["name"];$v["descs"][]=null;}if(preg_match('~^CREATE( UNIQUE)? INDEX '.preg_quote(idf_escape($C).' ON '.idf_escape($R),'~').' \((.*)\)$~i',$ok[$C],$ij)){preg_match_all('/("[^"]*+")+( DESC)?/',$ij[2],$gg);foreach($gg[2]as$x=>$X){if($X)$v["descs"][$x]='1';}}if(!$I[""]||$v["type"]!="UNIQUE"||$v["columns"]!=$I[""]["columns"]||$v["descs"]!=$I[""]["descs"]||!preg_match("~^sqlite_~",$C))$I[$C]=$v;}return$I;}function
foreign_keys($R){$I=array();foreach(get_rows("PRAGMA foreign_key_list(".table($R).")")as$J){$p=&$I[$J["id"]];if(!$p)$p=$J;$p["source"][]=$J["from"];$p["target"][]=$J["to"];}return$I;}function
view($C){return
array("select"=>preg_replace('~^(?:[^`"[]+|`[^`]*`|"[^"]*")* AS\s+~iU','',get_val("SELECT sql FROM sqlite_master WHERE type = 'view' AND name = ".q($C))));}function
collations(){return(isset($_GET["create"])?get_vals("PRAGMA collation_list",1):array());}function
information_schema($j,$L=""){return
false;}function
error(){return
h(connection()->error);}function
check_sqlite_name($C){$rd="db|sdb|sqlite";if(!preg_match("~^[^\\0]*\\.($rd)\$~",$C)){connection()->error=lang(35,str_replace("|",", ",$rd));return
false;}return
true;}function
create_database($j,$ub){if(file_exists($j)){connection()->error=lang(36);return
false;}if(!check_sqlite_name($j))return
false;try{$_=new
Db();$_->attach($j,'','');}catch(\Exception$hd){connection()->error=$hd->getMessage();return
false;}$_->query('PRAGMA encoding = "UTF-8"');$_->query('CREATE TABLE adminer (i)');$_->query('DROP TABLE adminer');return
true;}function
drop_databases(array$i){connection()->attach(":memory:",'','');foreach($i
as$j){if(!check_sqlite_name($j))return
false;if(!@unlink($j)){connection()->error=lang(36);return
false;}}return
true;}function
rename_database($C,$ub){if(!check_sqlite_name($C))return
false;connection()->attach(":memory:",'','');connection()->error=lang(36);return@rename(DB,$C);}function
auto_increment(){return" PRIMARY KEY AUTOINCREMENT";}function
alter_table($R,$C,array$n,array$Pd,$_b,$Vc,$ub,$Ga,$ki){$Rl=($R==""||$Pd||$Vc);foreach($n
as$m){if($m[0]!=""||!$m[1]||$m[2]){$Rl=true;break;}}$b=array();$Th=array();foreach($n
as$m){if($m[1]){$b[]=($Rl?$m[1]:"ADD ".implode($m[1]));if($m[0]!="")$Th[$m[0]]=$m[1][0];}}if(!$Rl){foreach($b
as$X){if(!queries("ALTER TABLE ".table($R)." $X"))return
false;}if($R!=$C&&!queries("ALTER TABLE ".table($R)." RENAME TO ".table($C)))return
false;}elseif(!recreate_table($R,$C,$b,$Th,$Pd,$Ga,array(),"","",$Vc))return
false;if($Ga){queries("BEGIN");queries("UPDATE sqlite_sequence SET seq = $Ga WHERE name = ".q($C));if(!connection()->affected_rows)queries("INSERT INTO sqlite_sequence (name, seq) VALUES (".q($C).", $Ga)");queries("COMMIT");}return
true;}function
recreate_table($R,$C,array$n,array$Th,array$Pd,$Ga="",$w=array(),$Ic="",$na="",$Vc=""){if($R!=""){if(!$n){foreach(fields($R)as$x=>$m){if($w)$m["auto_increment"]=0;$n[]=process_field($m,$m);$Th[$x]=idf_escape($x);}}$Li=false;foreach($n
as$m){if($m[6])$Li=true;}$Kc=array();foreach($w
as$x=>$X){if($X[2]=="DROP"){$Kc[$X[1]]=true;unset($w[$x]);}}foreach(indexes($R)as$_f=>$v){$e=array();foreach($v["columns"]as$x=>$d){if(!$Th[$d])continue
2;$e[]=$Th[$d].($v["descs"][$x]?" DESC":"");}if(!$Kc[$_f]){if($v["type"]!="PRIMARY"||!$Li)$w[]=array($v["type"],$_f,$e);}}foreach($w
as$x=>$X){if($X[0]=="PRIMARY"){unset($w[$x]);$Pd[]="  PRIMARY KEY (".implode(", ",$X[2]).")";}}foreach(foreign_keys($R)as$_f=>$p){foreach($p["source"]as$x=>$d){if(!$Th[$d])continue
2;$p["source"][$x]=idf_unescape($Th[$d]);}if(!isset($Pd[" $_f"]))$Pd[]=" ".format_foreign_key($p);}queries("BEGIN");}$db=array();foreach($n
as$m){if(preg_match('~GENERATED~',$m[3]))unset($Th[array_search($m[0],$Th)]);$db[]="  ".implode($m);}$db=array_merge($db,array_filter($Pd));foreach(driver()->checkConstraints($R)as$hb){if($hb!=$Ic)$db[]="  CHECK ($hb)";}if($na)$db[]="  CHECK ($na)";$Wk=($R!=""&&$R==$C?"adminer_$C":$C);if(!$Vc&&$R!="")$Vc=idx(table_status1($R),"Engine");if(!queries("CREATE TABLE ".table($Wk)." (\n".implode(",\n",$db)."\n)".($Vc!="table"&&in_array($Vc,driver()->engines())?" $Vc":"")))return
false;if($R!=""){if($Th&&!queries("INSERT INTO ".table($Wk)." (".implode(", ",$Th).") SELECT ".implode(", ",array_map('Adminer\idf_escape',array_keys($Th)))." FROM ".table($R)))return
false;$xl=array();foreach(triggers($R)as$vl=>$dl){$tl=trigger($vl,$R);$xl[]="CREATE TRIGGER ".idf_escape($vl)." ".implode(" ",$dl)." ON ".table($C)."\n$tl[Statement]";}$Ga=$Ga?"":get_val("SELECT seq FROM sqlite_sequence WHERE name = ".q($R));if(!queries("DROP TABLE ".table($R))||($R==$C&&!queries("ALTER TABLE ".table($Wk)." RENAME TO ".table($C)))||!alter_indexes($C,$w))return
false;if($Ga)queries("UPDATE sqlite_sequence SET seq = $Ga WHERE name = ".q($C));foreach($xl
as$tl){if(!queries($tl))return
false;}queries("COMMIT");}return
true;}function
index_sql($R,$U,$C,$e){return"CREATE $U ".($U!="INDEX"?"INDEX ":"").idf_escape($C!=""?$C:uniqid($R."_"))." ON ".table($R)." $e";}function
alter_indexes($R,$b){foreach($b
as$Ki){if($Ki[0]=="PRIMARY")return
recreate_table($R,$R,array(),array(),array(),"",$b);}foreach(array_reverse($b)as$X){if(!queries($X[2]=="DROP"?"DROP INDEX ".idf_escape($X[1]):index_sql($R,$X[0],$X[1],"(".implode(", ",$X[2]).")")))return
false;}return
true;}function
truncate_tables(array$T){return
apply_queries("DELETE FROM",$T);}function
drop_views(array$jm){return
apply_queries("DROP VIEW",$jm);}function
drop_tables(array$T){return
apply_queries("DROP TABLE",$T);}function
move_tables(array$T,array$jm,$Sk){return
false;}function
trigger($C,$R){if($C=="")return
array("Statement"=>"BEGIN\n\t;\nEND");$u='(?:[^`"\s]+|`[^`]*`|"[^"]*")+';$wl=trigger_options();preg_match("~^CREATE\\s+TRIGGER\\s*$u\\s*(".implode("|",$wl["Timing"]).")\\s+([a-z]+)(?:\\s+OF\\s+($u))?\\s+ON\\s*$u\\s*(?:FOR\\s+EACH\\s+ROW\\s)?(.*)~is",get_val("SELECT sql FROM sqlite_master WHERE type = 'trigger' AND name = ".q($C)),$A);$lh=$A[3];return
array("Timing"=>strtoupper($A[1]),"Event"=>strtoupper($A[2]).($lh?" OF":""),"Of"=>idf_unescape($lh),"Trigger"=>$C,"Statement"=>$A[4],);}function
triggers($R){$I=array();$wl=trigger_options();foreach(get_rows("SELECT * FROM sqlite_master WHERE type = 'trigger' AND tbl_name = ".q($R))as$J){preg_match('~^CREATE\s+TRIGGER\s*(?:[^`"\s]+|`[^`]*`|"[^"]*")+\s*('.implode("|",$wl["Timing"]).')\s*(.*?)\s+ON\b~i',$J["sql"],$A);$I[$J["name"]]=array($A[1],$A[2]);}return$I;}function
trigger_options(){return
array("Timing"=>array("BEFORE","AFTER","INSTEAD OF"),"Event"=>array("INSERT","UPDATE","UPDATE OF","DELETE"),"Type"=>array("FOR EACH ROW"),);}function
last_id($H){return
get_val("SELECT LAST_INSERT_ROWID()");}function
explain(Db$f,$G){return$f->query("EXPLAIN QUERY PLAN $G");}function
found_rows(array$S,array$Z){}function
types($rd=false){return
array();}function
create_sql($R,$Ga,$vk){$I=get_val("SELECT sql FROM sqlite_master WHERE type IN ('table', 'view') AND name = ".q($R));foreach(indexes($R)as$C=>$v){if($C=='')continue;$I
.=";\n\n".index_sql($R,$v['type'],$C,"(".implode(", ",array_map('Adminer\idf_escape',$v['columns'])).")");}return$I;}function
truncate_sql($R){return"DELETE FROM ".table($R);}function
use_sql($fc,$vk=""){return"";}function
trigger_sql($R){return
implode(get_vals("SELECT sql || ';;\n' FROM sqlite_master WHERE type = 'trigger' AND tbl_name = ".q($R)));}function
show_variables(){$I=array();foreach(get_rows("PRAGMA pragma_list")as$J){$C=$J["name"];if($C!="pragma_list"&&$C!="compile_options"){$I[$C]=array($C,'');foreach(get_rows("PRAGMA $C")as$J)$I[$C][1].=implode(", ",$J)."\n";}}return$I;}function
show_status(){$I=array();foreach(get_vals("PRAGMA compile_options")as$Fh)$I[]=explode("=",$Fh,2)+array('','');return$I;}function
convert_field(array$m){}function
unconvert_field(array$m,$I){return$I;}function
support($wd){return
preg_match('~^(check|columns|database|drop_col|dump|indexes|descidx|move_col|sql|status|table|transaction_ddl|trigger|variables|view|view_trigger)$~',$wd);}}add_driver("mssql","MS SQL");if(isset($_GET["mssql"])){define('Adminer\DRIVER',"mssql");if(extension_loaded("sqlsrv")&&$_GET["ext"]!="pdo"){class
Db
extends
SqlDb{var$extension="sqlsrv";private$link,$result,$warnings;private
function
get_error(){$this->error="";foreach(sqlsrv_errors()as$l){$this->errno=$l["code"];$this->error
.="$l[message]\n";}$this->error=rtrim($this->error);}function
attach($N,$V,$E){sqlsrv_configure("WarningsReturnAsErrors",0);$Ib=array("UID"=>$V,"PWD"=>$E,"CharacterSet"=>"UTF-8");$pk=adminer()->connectSsl();if(isset($pk["Encrypt"]))$Ib["Encrypt"]=$pk["Encrypt"];if(isset($pk["TrustServerCertificate"]))$Ib["TrustServerCertificate"]=$pk["TrustServerCertificate"];$j=adminer()->database();if($j!="")$Ib["Database"]=$j;list($Ee,$_i)=host_port($N);$this->link=@sqlsrv_connect($Ee.($_i?",$_i":""),$Ib);if($this->link){$af=sqlsrv_server_info($this->link);$this->server_info=$af['SQLServerVersion'];}else$this->get_error();return($this->link?'':$this->error);}function
quote($Q){$Dl=strlen($Q)!=strlen(utf8_decode($Q));return($Dl?"N":"")."'".str_replace("'","''",$Q)."'";}function
select_db($fc){return$this->query(use_sql($fc));}function
query($G,$Cl=false){$H=sqlsrv_query($this->link,$G);$this->error="";if(!$H){$this->get_error();return
false;}return$this->store_result($H);}function
multi_query($G){$this->result=sqlsrv_query($this->link,$G);$this->error="";if(!$this->result){$this->get_error();return
false;}return
true;}function
store_result($H=null){if(!$H)$H=$this->result;if(!$H)return
false;$this->warnings=sqlsrv_errors(SQLSRV_ERR_WARNINGS);if(sqlsrv_field_metadata($H))return
new
Result($H);$this->affected_rows=sqlsrv_rows_affected($H);return
true;}function
next_result(){if(!$this->result)return
false;$I=sqlsrv_next_result($this->result);if($I===false){$this->get_error();$this->result=null;return
true;}return!!$I;}function
warnings(){$I=array();foreach((array)$this->warnings
as$mm)$I[]=$mm["message"];return$I;}}class
Result{var$num_rows;private$result,$offset=0,$fields;function
__construct($H){$this->result=$H;}private
function
convert($J){foreach((array)$J
as$x=>$X){if(is_a($X,'DateTime'))$J[$x]=$X->format("Y-m-d H:i:s");}return$J;}function
fetch_assoc(){return$this->convert(sqlsrv_fetch_array($this->result,SQLSRV_FETCH_ASSOC));}function
fetch_row(){return$this->convert(sqlsrv_fetch_array($this->result,SQLSRV_FETCH_NUMERIC));}function
fetch_field(){if(!$this->fields)$this->fields=sqlsrv_field_metadata($this->result);$m=$this->fields[$this->offset++];$I=new
\stdClass;$I->name=$m["Name"];$I->type=($m["Type"]==1?254:15);$I->charsetnr=(in_array($m["Type"],array(-2,-3,-4))?63:0);return$I;}function
seek($ph){for($s=0;$s<$ph;$s++)sqlsrv_fetch($this->result);}}function
last_id($H){return(string)get_val("SELECT SCOPE_IDENTITY()");}function
explain(Db$f,$G){$f->query("SET SHOWPLAN_ALL ON");$I=$f->query($G);$f->query("SET SHOWPLAN_ALL OFF");return$I;}}else{abstract
class
MssqlDb
extends
PdoDb{function
select_db($fc){return$this->query(use_sql($fc));}function
lastInsertId(){return$this->pdo->lastInsertId();}function
warnings(){$H=$this->multi;if(!is_object($H))return
array();$l=$H->errorInfo();return
array((string)$l[2]);}}function
last_id($H){return
connection()->lastInsertId();}function
explain(Db$f,$G){}if(extension_loaded("pdo_sqlsrv")){class
Db
extends
MssqlDb{var$extension="PDO_SQLSRV";function
attach($N,$V,$E){list($Ee,$_i)=host_port($N);$Mc="sqlsrv:Server=$Ee".($_i?",$_i":"");$pk=adminer()->connectSsl();foreach(array("Encrypt","TrustServerCertificate")as$x){if(isset($pk[$x]))$Mc
.=";$x=".($pk[$x]?1:0);}return$this->dsn($Mc,$V,$E,array(\PDO::SQLSRV_ATTR_DIRECT_QUERY=>true));}}}elseif(extension_loaded("pdo_dblib")){class
Db
extends
MssqlDb{var$extension="PDO_DBLIB";function
attach($N,$V,$E){list($Ee,$_i)=host_port($N);return$this->dsn("dblib:charset=utf8;host=$Ee".($_i?(is_numeric($_i)?";port=":";unix_socket=").$_i:""),$V,$E);}}}}class
Driver
extends
SqlDriver{static$extensions=array("SQLSRV","PDO_SQLSRV","PDO_DBLIB");static$jush="mssql";var$insertFunctions=array("date|time"=>"getdate");var$editFunctions=array("int|decimal|real|float|money|datetime"=>"+/-","char|text"=>"+",);var$operators=array("=","<",">","<=",">=","!=","LIKE","LIKE %%","IN","IS NULL","NOT LIKE","NOT IN","IS NOT NULL");var$functions=array("len","lower","round","upper");var$grouping=array("avg","count","count distinct","max","min","sum");var$generated=array("PERSISTED","VIRTUAL");var$onActions="NO ACTION|CASCADE|SET NULL|SET DEFAULT";static
function
connect($N,$V,$E){if($N=="")$N="localhost:1433";return
parent::connect($N,$V,$E);}function
__construct(Db$f){parent::__construct($f);$this->types=array(lang(28)=>array("tinyint"=>3,"smallint"=>5,"int"=>10,"bigint"=>20,"bit"=>1,"decimal"=>0,"real"=>12,"float"=>53,"smallmoney"=>10,"money"=>20),lang(29)=>array("date"=>10,"smalldatetime"=>19,"datetime"=>19,"datetime2"=>19,"time"=>8,"datetimeoffset"=>10),lang(30)=>array("char"=>8000,"varchar"=>8000,"text"=>2147483647,"nchar"=>4000,"nvarchar"=>4000,"ntext"=>1073741823),lang(31)=>array("binary"=>8000,"varbinary"=>8000,"image"=>2147483647),);}function
insertUpdate($R,array$K,array$Ki){$n=fields($R);$Ll=array();$Z=array();$O=reset($K);$e="c".implode(", c",range(1,count($O)));$Za=0;$gf=array();foreach($O
as$x=>$X){$Za++;$C=idf_unescape($x);if(!$n[$C]["auto_increment"])$gf[$x]="c$Za";if(isset($Ki[$C]))$Z[]="$x = c$Za";else$Ll[]="$x = c$Za";}$em=array();foreach($K
as$O)$em[]="(".implode(", ",$O).")";if($Z){$Je=queries("SET IDENTITY_INSERT ".table($R)." ON");$I=queries("MERGE ".table($R)." USING (VALUES\n\t".implode(",\n\t",$em)."\n) AS source ($e) ON ".implode(" AND ",$Z).($Ll?"\nWHEN MATCHED THEN UPDATE SET ".implode(", ",$Ll):"")."\nWHEN NOT MATCHED THEN INSERT (".implode(", ",array_keys($Je?$O:$gf)).") VALUES (".($Je?$e:implode(", ",$gf)).");");if($Je)queries("SET IDENTITY_INSERT ".table($R)." OFF");}else$I=queries("INSERT INTO ".table($R)." (".implode(", ",array_keys($O)).") VALUES\n".implode(",\n",$em));return$I;}function
begin(){return
queries("BEGIN TRANSACTION");}function
convertSearch($u,array$X,array$m){return(preg_match('~^(bit|n?text|xml|uniqueidentifier|sql_variant|hierarchyid|geography|geometry)$~',$m["type"])?"CAST($u AS nvarchar(max))":$u);}function
quoteBinary($zj){return"0x".bin2hex($zj);}function
warnings(){$I=array();foreach($this->conn->warnings()as$B){$B=trim(preg_replace('~^(\[[^]]+])+~','',$B));if($B!="")$I[]=$B;}return
nl_br(h(implode("\n",$I)));}function
tableHelp($C,$uf=false){$Wf=array("sys"=>"catalog-views/sys-","INFORMATION_SCHEMA"=>"information-schema-views/",);$_=$Wf[get_schema()];if($_)return"relational-databases/system-$_".preg_replace('~_~','-',strtolower($C))."-transact-sql";}}function
idf_escape($u){return"[".str_replace("]","]]",$u)."]";}function
table($u){return($_GET["ns"]!=""?idf_escape($_GET["ns"]).".":"").idf_escape($u);}function
get_databases($Nd){return
get_vals("SELECT name FROM sys.databases WHERE name NOT IN ('master', 'tempdb', 'model', 'msdb')");}function
limit($G,$Z,$z,$ph=0,$Lj=" "){return($z?" TOP (".($z+$ph).")":"")." $G$Z";}function
limit1($R,$G,$Z,$Lj="\n"){return
limit($G,$Z,1,0,$Lj);}function
db_collation($j,array$vb){return
get_val("SELECT collation_name FROM sys.databases WHERE name = ".q($j));}function
logged_user(){return
get_val("SELECT SUSER_NAME()");}function
tables_list(){return
get_key_vals("SELECT name, type_desc FROM sys.all_objects WHERE schema_id = SCHEMA_ID(".q(get_schema()).") AND type IN ('S', 'U', 'V') ORDER BY name");}function
count_tables(array$i){$I=array();foreach($i
as$j){connection()->select_db($j);$I[$j]=get_val("SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES");}return$I;}function
table_status($C="",$vd=false){$I=array();$ak=array();foreach(get_rows("SELECT object_id, SUM(CASE WHEN index_id < 2 THEN row_count ELSE 0 END) AS [Rows],
SUM(CASE WHEN index_id < 2 THEN used_page_count ELSE 0 END) * 8192 AS Data_length,
SUM(CASE WHEN index_id > 1 THEN used_page_count ELSE 0 END) * 8192 AS Index_length,
SUM(reserved_page_count - used_page_count) * 8192 AS Data_free
FROM sys.dm_db_partition_stats
GROUP BY object_id",null,"")as$J){$kh=$J["object_id"];unset($J["object_id"]);$ak[$kh]=$J;}foreach(get_rows("SELECT ao.object_id, ao.name AS Name, ao.type_desc AS Engine,
	(SELECT cast(value as varchar(max)) FROM fn_listextendedproperty(default, 'SCHEMA', schema_name(schema_id), 'TABLE', ao.name, null, null)) AS Comment
FROM sys.all_objects AS ao
WHERE schema_id = SCHEMA_ID(".q(get_schema()).") AND type IN ('S', 'U', 'V') ".($C!=""?"AND name = ".q($C):"ORDER BY name"))as$J){$kh=$J["object_id"];unset($J["object_id"]);$I[$J["Name"]]=$J+idx($ak,$kh,array());}return$I;}function
is_view(array$S){return$S["Engine"]=="VIEW";}function
fk_support(array$S){return
true;}function
fields($R){$Bb=get_key_vals("SELECT objname, cast(value as varchar(max)) FROM fn_listextendedproperty('MS_DESCRIPTION', 'schema', ".q(get_schema()).", 'table', ".q($R).", 'column', NULL)");$I=array();$Dk=get_val("SELECT object_id FROM sys.all_objects WHERE schema_id = SCHEMA_ID(".q(get_schema()).") AND type IN ('S', 'U', 'V') AND name = ".q($R));foreach(get_rows("SELECT c.max_length, c.precision, c.scale, c.name, c.is_nullable, c.is_identity, c.collation_name,
	t.name type, d.definition [default], d.name default_constraint, i.is_primary_key
FROM sys.all_columns c
JOIN sys.types t ON c.user_type_id = t.user_type_id
LEFT JOIN sys.default_constraints d ON c.default_object_id = d.object_id
LEFT JOIN sys.index_columns ic ON c.object_id = ic.object_id AND c.column_id = ic.column_id
LEFT JOIN sys.indexes i ON ic.object_id = i.object_id AND ic.index_id = i.index_id
WHERE c.object_id = ".q($Dk))as$J){$U=$J["type"];$y=(preg_match("~char|binary~",$U)?intval($J["max_length"])/($U[0]=='n'?2:1):($U=="decimal"?"$J[precision],$J[scale]":""));$I[$J["name"]]=array("field"=>$J["name"],"full_type"=>$U.($y?"($y)":""),"type"=>$U,"length"=>$y,"default"=>(preg_match("~^\('(.*)'\)$~",$J["default"],$A)?str_replace("''","'",$A[1]):$J["default"]),"default_constraint"=>$J["default_constraint"],"null"=>$J["is_nullable"],"auto_increment"=>$J["is_identity"],"collation"=>$J["collation_name"],"privileges"=>array("insert"=>1,"select"=>1,"update"=>1,"where"=>1,"order"=>1),"primary"=>$J["is_primary_key"],"comment"=>$Bb[$J["name"]],);}foreach(get_rows("SELECT * FROM sys.computed_columns WHERE object_id = ".q($Dk))as$J){$I[$J["name"]]["generated"]=($J["is_persisted"]?"PERSISTED":"VIRTUAL");$I[$J["name"]]["default"]=$J["definition"];}return$I;}function
indexes($R,$g=null){$I=array();foreach(get_rows("SELECT i.name, key_ordinal, is_unique, is_primary_key, c.name AS column_name, is_descending_key
FROM sys.indexes i
INNER JOIN sys.index_columns ic ON i.object_id = ic.object_id AND i.index_id = ic.index_id
INNER JOIN sys.columns c ON ic.object_id = c.object_id AND ic.column_id = c.column_id
WHERE OBJECT_NAME(i.object_id) = ".q($R),$g)as$J){$C=$J["name"];$I[$C]["type"]=($J["is_primary_key"]?"PRIMARY":($J["is_unique"]?"UNIQUE":"INDEX"));$I[$C]["lengths"]=array();$I[$C]["columns"][$J["key_ordinal"]]=$J["column_name"];$I[$C]["descs"][$J["key_ordinal"]]=($J["is_descending_key"]?'1':null);}return$I;}function
view($C){return
array("select"=>preg_replace('~^(?:[^[]|\[[^]]*])*\s+AS\s+~isU','',get_val("SELECT VIEW_DEFINITION FROM INFORMATION_SCHEMA.VIEWS WHERE TABLE_SCHEMA = SCHEMA_NAME() AND TABLE_NAME = ".q($C))));}function
collations(){$I=array();foreach(get_vals("SELECT name FROM fn_helpcollations()")as$ub)$I[preg_replace('~_.*~','',$ub)][]=$ub;return$I;}function
information_schema($j,$L=""){return
in_array($L!=""?$L:get_schema(),array("INFORMATION_SCHEMA","sys"));}function
error(){return
nl_br(h(preg_replace('~^(\[[^]]*])+~m','',connection()->error)));}function
create_database($j,$ub){return
queries("CREATE DATABASE ".idf_escape($j).(preg_match('~^[a-z0-9_]+$~i',$ub)?" COLLATE $ub":""));}function
drop_databases(array$i){return!!queries("DROP DATABASE ".implode(", ",array_map('Adminer\idf_escape',$i)));}function
rename_database($C,$ub){if(preg_match('~^[a-z0-9_]+$~i',$ub))queries("ALTER DATABASE ".idf_escape(DB)." COLLATE $ub");queries("ALTER DATABASE ".idf_escape(DB)." MODIFY NAME = ".idf_escape($C));return
true;}function
auto_increment(){return" IDENTITY".($_POST["Auto_increment"]!=""?"(".number($_POST["Auto_increment"]).",1)":"")." PRIMARY KEY";}function
alter_table($R,$C,array$n,array$Pd,$_b,$Vc,$ub,$Ga,$ki){$b=array();$Bb=array();$Ph=fields($R);foreach($n
as$m){$d=idf_escape($m[0]);$X=$m[1];if(!$X)$b["DROP"][]=" COLUMN $d";else{$X[1]=preg_replace("~( COLLATE )'(\\w+)'~",'\1\2',$X[1]);$Bb[$m[0]]=$X[5];unset($X[5]);if(preg_match('~ AS ~',$X[3]))unset($X[1],$X[2]);if($m[0]=="")$b["ADD"][]="\n  ".implode("",$X).($R==""?substr($Pd[$X[0]],16+strlen($X[0])):"");else{$k=$X[3];unset($X[3]);unset($X[6]);if($d!=$X[0])queries("EXEC sp_rename ".q(table($R).".$d").", ".q(idf_unescape($X[0])).", 'COLUMN'");$b["ALTER COLUMN ".implode("",$X)][]="";$Oh=$Ph[$m[0]];if(default_value($Oh)!=$k){if($Oh["default"]!==null)$b["DROP"][]=" ".idf_escape($Oh["default_constraint"]);if($k)$b["ADD"][]="\n $k FOR $d";}}}}if($R==""){$ma=(array)$b["ADD"];foreach($Pd
as$x=>$X){if(!is_string($x))$ma[]="\n$X";}return
queries("CREATE TABLE ".table($C)." (".implode(",",$ma)."\n)");}if($R!=$C)queries("EXEC sp_rename ".q(table($R)).", ".q($C));if($Pd)$b[""]=$Pd;foreach($b
as$x=>$X){if(!queries("ALTER TABLE ".table($C)." $x".implode(",",$X)))return
false;}foreach($Bb
as$x=>$X){$_b=substr($X,9);queries("EXEC sp_dropextendedproperty @name = N'MS_Description', @level0type = N'Schema', @level0name = ".q(get_schema()).", @level1type = N'Table', @level1name = ".q($C).", @level2type = N'Column', @level2name = ".q($x));queries("EXEC sp_addextendedproperty
@name = N'MS_Description',
@value = $_b,
@level0type = N'Schema',
@level0name = ".q(get_schema()).",
@level1type = N'Table',
@level1name = ".q($C).",
@level2type = N'Column',
@level2name = ".q($x));}return
true;}function
alter_indexes($R,$b){$v=array();$Hc=array();foreach($b
as$X){if($X[2]=="DROP"){if($X[0]=="PRIMARY")$Hc[]=idf_escape($X[1]);else$v[]=idf_escape($X[1])." ON ".table($R);}elseif(!queries(($X[0]!="PRIMARY"?"CREATE $X[0] ".($X[0]!="INDEX"?"INDEX ":"").idf_escape($X[1]!=""?$X[1]:uniqid($R."_"))." ON ".table($R):"ALTER TABLE ".table($R)." ADD PRIMARY KEY")." (".implode(", ",$X[2]).")"))return
false;}return(!$v||queries("DROP INDEX ".implode(", ",$v)))&&(!$Hc||queries("ALTER TABLE ".table($R)." DROP ".implode(", ",$Hc)));}function
found_rows(array$S,array$Z){}function
foreign_keys($R){$I=array();$zh=array("CASCADE","NO ACTION","SET NULL","SET DEFAULT");$L=get_schema();foreach(get_rows("EXEC sp_fkeys @fktable_name = ".q($R).", @fktable_owner = ".q($L))as$J){$p=&$I[$J["FK_NAME"]];$p["db"]=($J["PKTABLE_QUALIFIER"]==DB?"":$J["PKTABLE_QUALIFIER"]);$p["ns"]=($J["PKTABLE_OWNER"]==$L?"":$J["PKTABLE_OWNER"]);$p["table"]=$J["PKTABLE_NAME"];$p["on_update"]=$zh[$J["UPDATE_RULE"]];$p["on_delete"]=$zh[$J["DELETE_RULE"]];$p["source"][]=$J["FKCOLUMN_NAME"];$p["target"][]=$J["PKCOLUMN_NAME"];}return$I;}function
truncate_tables(array$T){return
apply_queries("TRUNCATE TABLE",$T);}function
drop_views(array$jm){return
queries("DROP VIEW ".implode(", ",array_map('Adminer\table',$jm)));}function
drop_tables(array$T){return
queries("DROP TABLE ".implode(", ",array_map('Adminer\table',$T)));}function
move_tables(array$T,array$jm,$Sk){return
apply_queries("ALTER SCHEMA ".idf_escape($Sk)." TRANSFER",array_merge($T,$jm));}function
trigger($C,$R){if($C=="")return
array();$K=get_rows("SELECT s.name [Trigger],
CASE WHEN OBJECTPROPERTY(s.id, 'ExecIsInsertTrigger') = 1 THEN 'INSERT'
	WHEN OBJECTPROPERTY(s.id, 'ExecIsUpdateTrigger') = 1 THEN 'UPDATE'
	WHEN OBJECTPROPERTY(s.id, 'ExecIsDeleteTrigger') = 1 THEN 'DELETE' END [Event],
CASE WHEN OBJECTPROPERTY(s.id, 'ExecIsInsteadOfTrigger') = 1 THEN 'INSTEAD OF' ELSE 'AFTER' END [Timing],
c.text
FROM sysobjects s
JOIN syscomments c ON s.id = c.id
WHERE s.xtype = 'TR' AND s.name = ".q($C));$I=reset($K);if($I)$I["Statement"]=preg_replace('~^.+\s+AS\s+~isU','',$I["text"]);return$I;}function
triggers($R){$I=array();foreach(get_rows("SELECT sys1.name,
CASE WHEN OBJECTPROPERTY(sys1.id, 'ExecIsInsertTrigger') = 1 THEN 'INSERT'
	WHEN OBJECTPROPERTY(sys1.id, 'ExecIsUpdateTrigger') = 1 THEN 'UPDATE'
	WHEN OBJECTPROPERTY(sys1.id, 'ExecIsDeleteTrigger') = 1 THEN 'DELETE' END [Event],
CASE WHEN OBJECTPROPERTY(sys1.id, 'ExecIsInsteadOfTrigger') = 1 THEN 'INSTEAD OF' ELSE 'AFTER' END [Timing]
FROM sysobjects sys1
JOIN sysobjects sys2 ON sys1.parent_obj = sys2.id
WHERE sys1.xtype = 'TR' AND sys2.name = ".q($R))as$J)$I[$J["name"]]=array($J["Timing"],$J["Event"]);return$I;}function
trigger_options(){return
array("Timing"=>array("AFTER","INSTEAD OF"),"Event"=>array("INSERT","UPDATE","DELETE"),"Type"=>array("AS"),);}function
schemas(){return
get_vals("SELECT name FROM sys.schemas");}function
get_schema(){if($_GET["ns"]!="")return$_GET["ns"];return
get_val("SELECT SCHEMA_NAME()");}function
set_schema($L,$g=null){$_GET["ns"]=$L;return
true;}function
create_sql($R,$Ga,$vk){if(is_view(table_status1($R))){$im=view($R);return"CREATE VIEW ".table($R)." AS $im[select]";}$n=array();$Ki=false;foreach(fields($R)as$C=>$m){$X=process_field($m,$m);if($X[6])$Ki=true;$n[]=implode("",$X);}foreach(indexes($R)as$C=>$v){if(!$Ki||$v["type"]!="PRIMARY"){$e=array();foreach($v["columns"]as$x=>$X)$e[]=idf_escape($X).($v["descs"][$x]?" DESC":"");$C=idf_escape($C);$n[]=($v["type"]=="INDEX"?"INDEX $C":"CONSTRAINT $C ".($v["type"]=="UNIQUE"?"UNIQUE":"PRIMARY KEY"))." (".implode(", ",$e).")";}}foreach(driver()->checkConstraints($R)as$C=>$hb)$n[]="CONSTRAINT ".idf_escape($C)." CHECK ($hb)";return"CREATE TABLE ".table($R)." (\n\t".implode(",\n\t",$n)."\n)";}function
foreign_keys_sql($R){$n=array();foreach(foreign_keys($R)as$Pd)$n[]=ltrim(format_foreign_key($Pd));return($n?"ALTER TABLE ".table($R)." ADD\n\t".implode(",\n\t",$n).";\n\n":"");}function
truncate_sql($R){return"TRUNCATE TABLE ".table($R);}function
use_sql($fc,$vk=""){return"USE ".idf_escape($fc);}function
trigger_sql($R){$I="";foreach(triggers($R)as$C=>$tl)$I
.=create_trigger(" ON ".table($R),trigger($C,$R)).";";return$I;}function
convert_field(array$m){}function
unconvert_field(array$m,$I){return$I;}function
support($wd){return
preg_match('~^(check|comment|columns|database|drop_col|dump|fast_status|indexes|descidx|scheme|sql|table|transaction_ddl|trigger|view|view_trigger)$~',$wd);}}add_driver("oracle","Oracle beta");if(isset($_GET["oracle"])){define('Adminer\DRIVER',"oracle");if(extension_loaded("oci8")&&$_GET["ext"]!="pdo"){class
Db
extends
SqlDb{var$extension="oci8";var$_current_db;private$link;function
_error($ad,$l){if(ini_bool("html_errors"))$l=html_entity_decode(strip_tags($l));$l=preg_replace('~^[^:]*: ~','',$l);$this->error=$l;}function
attach($N,$V,$E){$this->link=@oci_new_connect($V,$E,$N,"AL32UTF8");if($this->link){$this->server_info=oci_server_version($this->link);return'';}$l=oci_error();return($l?$l["message"]:lang(25));}function
quote($Q){return"'".str_replace("'","''",$Q)."'";}function
select_db($fc){$this->_current_db=$fc;return
true;}function
query($G,$Cl=false){$H=oci_parse($this->link,$G);$this->error="";if(!$H){$l=oci_error($this->link);$this->errno=$l["code"];$this->error=$l["message"];return
false;}set_error_handler(array($this,'_error'));$I=@oci_execute($H);restore_error_handler();if($I){if(oci_num_fields($H))return
new
Result($H);$this->affected_rows=oci_num_rows($H);oci_free_statement($H);}return$I;}function
timeout($Lg){return(function_exists('oci_set_call_timeout')?oci_set_call_timeout($this->link,$Lg):false);}}class
Result{var$num_rows;private$result,$offset=1;function
__construct($H){$this->result=$H;}private
function
convert($J){foreach((array)$J
as$x=>$X){if(is_a($X,'OCILob')||is_a($X,'OCI-Lob'))$J[$x]=$X->load();}return$J;}function
fetch_assoc(){return$this->convert(oci_fetch_assoc($this->result));}function
fetch_row(){return$this->convert(oci_fetch_row($this->result));}function
fetch_field(){$d=$this->offset++;$I=new
\stdClass;$I->name=oci_field_name($this->result,$d);$U=oci_field_type($this->result,$d);$I->native_type=$U;$I->type=$U;$I->charsetnr=(preg_match("~raw|blob|bfile~",$U)?63:0);return$I;}}}elseif(extension_loaded("pdo_oci")){class
Db
extends
PdoDb{var$extension="PDO_OCI";var$_current_db;function
attach($N,$V,$E){return$this->dsn("oci:dbname=//$N;charset=AL32UTF8",$V,$E);}function
select_db($fc){$this->_current_db=$fc;return
true;}}}class
Driver
extends
SqlDriver{static$extensions=array("OCI8","PDO_OCI");static$jush="oracle";var$insertFunctions=array("date"=>"current_date","timestamp"=>"current_timestamp",);var$editFunctions=array("number|float|double"=>"+/-","date|timestamp"=>"+ interval/- interval","char|clob"=>"||",);var$operators=array("=","<",">","<=",">=","!=","LIKE","LIKE %%","IN","IS NULL","NOT LIKE","NOT IN","IS NOT NULL","SQL");var$functions=array("length","lower","round","upper");var$grouping=array("avg","count","count distinct","max","min","sum");function
__construct(Db$f){parent::__construct($f);$this->types=array(lang(28)=>array("number"=>38,"binary_float"=>12,"binary_double"=>21),lang(29)=>array("date"=>10,"timestamp"=>29,"interval year"=>12,"interval day"=>28),lang(30)=>array("char"=>2000,"varchar2"=>4000,"nchar"=>2000,"nvarchar2"=>4000,"clob"=>4294967295,"nclob"=>4294967295),lang(31)=>array("raw"=>2000,"long raw"=>2147483648,"blob"=>4294967295,"bfile"=>4294967296),);}function
begin(){return
true;}function
convertSearch($u,array$X,array$m){$U=$m["type"];$si=strpos($X["op"],"LIKE")!==false;if($U=="xmltype")return"XMLSERIALIZE(CONTENT $u AS VARCHAR2(4000))";if($U=="json")return"JSON_SERIALIZE($u)";if(preg_match('~^(date|timestamp)~',$U))return"TO_CHAR($u, 'YYYY-MM-DD HH24:MI:SS')";if(preg_match('~char~',$U)||(preg_match('~clob~',$U)&&$si))return$u;return(!$si&&preg_match(number_type(),$U)?$u:"TO_CHAR($u)");}function
quoteBinary($zj){return"HEXTORAW(".q(bin2hex($zj)).")";}function
hasCStyleEscapes(){return
true;}}function
idf_escape($u){return'"'.str_replace('"','""',$u).'"';}function
table($u){return
idf_escape($u);}function
get_databases($Nd){return
get_vals("SELECT DISTINCT tablespace_name FROM (
SELECT tablespace_name FROM user_tablespaces
UNION SELECT tablespace_name FROM all_tables WHERE tablespace_name IS NOT NULL
)
ORDER BY 1");}function
limit($G,$Z,$z,$ph=0,$Lj=" "){return($ph?" * FROM (SELECT t.*, rownum AS rnum FROM (SELECT $G$Z) t WHERE rownum <= ".($z+$ph).") WHERE rnum > $ph":($z?" * FROM (SELECT $G$Z) WHERE rownum <= ".($z+$ph):" $G$Z"));}function
limit1($R,$G,$Z,$Lj="\n"){return" $G$Z";}function
db_collation($j,array$vb){return
get_val("SELECT value FROM nls_database_parameters WHERE parameter = 'NLS_CHARACTERSET'");}function
logged_user(){return
get_val("SELECT USER FROM DUAL");}function
get_current_db(){$j=connection()->_current_db?:DB;connection()->_current_db=null;return$j;}function
where_owner($Gi,$Xh="owner"){if(!$_GET["ns"])return'';return"$Gi$Xh = sys_context('USERENV', 'CURRENT_SCHEMA')";}function
views_table($e){$Xh=where_owner('');return"(SELECT $e FROM all_views WHERE ".($Xh?:"rownum < 0").")";}function
tables_list(){$im=views_table("view_name");$Xh=where_owner(" AND ");return
get_key_vals("SELECT table_name, 'table' FROM all_tables WHERE tablespace_name = ".q(DB)."$Xh
UNION SELECT view_name, 'view' FROM $im
ORDER BY 1");}function
count_tables(array$i){$I=array();foreach($i
as$j)$I[$j]=get_val("SELECT COUNT(*) FROM all_tables WHERE tablespace_name = ".q($j));return$I;}function
table_status($C="",$vd=false){$I=array();$Dj=q($C);$j=get_current_db();$im=views_table("view_name");$Xh=where_owner(" AND ","t.owner");foreach(get_rows('SELECT t.table_name "Name", \'table\' "Engine", s.bytes "Data_length", i.bytes "Index_length", t.num_rows "Rows"
FROM all_tables t
LEFT JOIN (SELECT segment_name, SUM(bytes) bytes FROM user_segments WHERE segment_type LIKE \'TABLE%\' GROUP BY segment_name) s ON s.segment_name = t.table_name
LEFT JOIN (SELECT i.table_name, SUM(s.bytes) bytes FROM user_indexes i
	JOIN user_segments s ON s.segment_name = i.index_name AND s.segment_type LIKE \'INDEX%\' GROUP BY i.table_name) i ON i.table_name = t.table_name
WHERE t.tablespace_name = '.q($j).$Xh.($C!=""?" AND t.table_name = $Dj":"")."
UNION SELECT view_name, 'view', 0, 0, 0 FROM $im".($C!=""?" WHERE view_name = $Dj":"")."
ORDER BY 1")as$J)$I[$J["Name"]]=$J;return$I;}function
is_view(array$S){return$S["Engine"]=="view";}function
fk_support(array$S){return
true;}function
fields($R){$I=array();$Xh=where_owner(" AND ");foreach(get_rows("SELECT * FROM all_tab_columns WHERE table_name = ".q($R)."$Xh ORDER BY column_id")as$J){$U=$J["DATA_TYPE"];$y="$J[DATA_PRECISION],$J[DATA_SCALE]";if($y==",")$y=$J["CHAR_COL_DECL_LENGTH"];$Pi=array("insert"=>1,"select"=>1,"update"=>1,"order"=>1);if($J["DATA_TYPE_OWNER"]==""||$U=="XMLTYPE")$Pi["where"]=1;$I[$J["COLUMN_NAME"]]=array("field"=>$J["COLUMN_NAME"],"full_type"=>$U.($y?"($y)":""),"type"=>strtolower($U),"length"=>$y,"default"=>$J["DATA_DEFAULT"],"null"=>($J["NULLABLE"]=="Y"),"privileges"=>$Pi,);}return$I;}function
indexes($R,$g=null){$I=array();$Xh=where_owner(" AND ","aic.table_owner");foreach(get_rows("SELECT aic.*, ac.constraint_type, atc.data_default
FROM all_ind_columns aic
LEFT JOIN all_constraints ac ON aic.index_name = ac.constraint_name AND aic.table_name = ac.table_name AND aic.index_owner = ac.owner
LEFT JOIN all_tab_cols atc ON aic.column_name = atc.column_name AND aic.table_name = atc.table_name AND aic.index_owner = atc.owner
WHERE aic.table_name = ".q($R)."$Xh
ORDER BY ac.constraint_type, aic.column_position",$g)as$J){$Ve=$J["INDEX_NAME"];$yb=$J["DATA_DEFAULT"];$yb=($yb?trim($yb,'"'):$J["COLUMN_NAME"]);$I[$Ve]["type"]=($J["CONSTRAINT_TYPE"]=="P"?"PRIMARY":($J["CONSTRAINT_TYPE"]=="U"?"UNIQUE":"INDEX"));$I[$Ve]["columns"][]=$yb;$I[$Ve]["lengths"][]=($J["CHAR_LENGTH"]&&$J["CHAR_LENGTH"]!=$J["COLUMN_LENGTH"]?$J["CHAR_LENGTH"]:null);$I[$Ve]["descs"][]=($J["DESCEND"]&&$J["DESCEND"]=="DESC"?'1':null);}return$I;}function
view($C){$im=views_table("view_name, text");$K=get_rows('SELECT text "select" FROM '.$im.' WHERE view_name = '.q($C));return
reset($K);}function
collations(){return
array();}function
information_schema($j,$L=""){return($L!=""?$L:get_schema())=="INFORMATION_SCHEMA";}function
error(){return
h(connection()->error);}function
explain(Db$f,$G){$f->query("EXPLAIN PLAN FOR $G");return$f->query("SELECT * FROM plan_table");}function
found_rows(array$S,array$Z){}function
auto_increment(){return"";}function
alter_table($R,$C,array$n,array$Pd,$_b,$Vc,$ub,$Ga,$ki){$b=$Hc=array();$Ph=($R?fields($R):array());foreach($n
as$m){$X=$m[1];if($X&&$m[0]!=""&&idf_escape($m[0])!=$X[0])queries("ALTER TABLE ".table($R)." RENAME COLUMN ".idf_escape($m[0])." TO $X[0]");$Oh=$Ph[$m[0]];if($X&&$Oh){$rh=process_field($Oh,$Oh);if($X[2]==$rh[2])$X[2]="";}if($X)$b[]=($R!=""?($m[0]!=""?"MODIFY (":"ADD ("):"  ").implode($X).($R!=""?")":"");else$Hc[]=idf_escape($m[0]);}if($R=="")return
queries("CREATE TABLE ".table($C)." (\n".implode(",\n",array_merge($b,$Pd))."\n)");return(!$b||queries("ALTER TABLE ".table($R)."\n".implode("\n",$b)))&&(!$Hc||queries("ALTER TABLE ".table($R)." DROP (".implode(", ",$Hc).")"))&&($R==$C||queries("ALTER TABLE ".table($R)." RENAME TO ".table($C)));}function
alter_indexes($R,$b){$Hc=array();$Ui=array();foreach($b
as$X){if($X[0]!="INDEX"){$X[2]=preg_replace('~ DESC$~','',$X[2]);$h=($X[2]=="DROP"?"\nDROP CONSTRAINT ".idf_escape($X[1]):"\nADD".($X[1]!=""?" CONSTRAINT ".idf_escape($X[1]):"")." $X[0] ".($X[0]=="PRIMARY"?"KEY ":"")."(".implode(", ",$X[2]).")");array_unshift($Ui,"ALTER TABLE ".table($R).$h);}elseif($X[2]=="DROP")$Hc[]=idf_escape($X[1]);else$Ui[]="CREATE INDEX ".idf_escape($X[1]!=""?$X[1]:uniqid($R."_"))." ON ".table($R)." (".implode(", ",$X[2]).")";}if($Hc)array_unshift($Ui,"DROP INDEX ".implode(", ",$Hc));foreach($Ui
as$G){if(!queries($G))return
false;}return
true;}function
foreign_keys($R){$I=array();$G="SELECT c_list.CONSTRAINT_NAME as NAME,
c_src.COLUMN_NAME as SRC_COLUMN,
c_dest.OWNER as DEST_DB,
c_dest.TABLE_NAME as DEST_TABLE,
c_dest.COLUMN_NAME as DEST_COLUMN,
c_list.DELETE_RULE as ON_DELETE
FROM ALL_CONSTRAINTS c_list, ALL_CONS_COLUMNS c_src, ALL_CONS_COLUMNS c_dest
WHERE c_list.CONSTRAINT_NAME = c_src.CONSTRAINT_NAME
AND c_list.R_CONSTRAINT_NAME = c_dest.CONSTRAINT_NAME
AND c_list.CONSTRAINT_TYPE = 'R'
AND c_src.TABLE_NAME = ".q($R);foreach(get_rows($G)as$J)$I[$J['NAME']]=array("db"=>$J['DEST_DB'],"table"=>$J['DEST_TABLE'],"source"=>array($J['SRC_COLUMN']),"target"=>array($J['DEST_COLUMN']),"on_delete"=>$J['ON_DELETE'],"on_update"=>null,);return$I;}function
truncate_tables(array$T){return
apply_queries("TRUNCATE TABLE",$T);}function
drop_views(array$jm){return
apply_queries("DROP VIEW",$jm);}function
drop_tables(array$T){return
apply_queries("DROP TABLE",$T);}function
last_id($H){return"0";}function
schemas(){$I=get_vals("SELECT DISTINCT owner FROM dba_segments WHERE owner IN (SELECT username FROM dba_users WHERE default_tablespace NOT IN ('SYSTEM','SYSAUX')) ORDER BY 1");return($I?:get_vals("SELECT DISTINCT owner FROM all_tables WHERE tablespace_name = ".q(DB)." ORDER BY 1"));}function
get_schema(){return
get_val("SELECT sys_context('USERENV', 'SESSION_USER') FROM dual");}function
set_schema($L,$g=null){return!!connection($g)->query("ALTER SESSION SET CURRENT_SCHEMA = ".idf_escape($L));}function
show_variables(){return
get_rows('SELECT name, display_value FROM v$parameter');}function
show_status(){$I=array();$K=get_rows('SELECT * FROM v$instance');foreach(reset($K)as$x=>$X)$I[]=array($x,$X);return$I;}function
process_list(){return
get_rows('SELECT
	sess.process AS "process",
	sess.username AS "user",
	sess.schemaname AS "schema",
	sess.status AS "status",
	sess.wait_class AS "wait_class",
	sess.seconds_in_wait AS "seconds_in_wait",
	sql.sql_text AS "sql_text",
	sess.machine AS "machine",
	sess.port AS "port"
FROM v$session sess LEFT OUTER JOIN v$sql sql
ON sql.sql_id = sess.sql_id
WHERE sess.type = \'USER\'
ORDER BY PROCESS
');}function
convert_field(array$m){}function
unconvert_field(array$m,$I){return$I;}function
support($wd){return
preg_match('~^(columns|database|drop_col|fast_status|indexes|descidx|processlist|scheme|sql|status|table|variables|view)$~',$wd);}}class
Adminer{static$instance;var$error='';function
name(){return"<a href='https://www.adminer.org/'".target_blank()." id='h1'><img src='".h(preg_replace("~\\?.*~","",ME)."?file=logo.png&version=6.0.1")."' width='24' height='24' alt='' id='logo'>Adminer</a>";}function
credentials(){return
array(SERVER,$_GET["username"],get_password());}function
connectSsl(){}function
permanentLogin($h=false){return
password_file($h);}function
bruteForceKey(){return$_SERVER["REMOTE_ADDR"];}function
serverName($N){return
h($N);}function
database(){return
DB;}function
databases($Nd=true){return
get_databases($Nd);}function
pluginsLinks(){}function
operators(){return
driver()->operators;}function
schemas(){$I=schemas();if($_GET["ns"]!=""&&!in_array($_GET["ns"],$I))array_unshift($I,$_GET["ns"]);return$I;}function
queryTimeout(){return
2;}function
afterConnect(){}function
headers(){}function
csp(array$Wb){return$Wb;}function
verifyVersion(){return
true;}function
head($bc=null){return
true;}function
bodyClass(){echo" adminer";}function
css(){$I=array();foreach(array("","-dark")as$Ig){$o="adminer$Ig.css";if(file_exists($o)){$Bd=file_get_contents($o);$I["$o?v=".crc32($Bd)]=($Ig?"dark":(preg_match('~prefers-color-scheme:\s*dark~',$Bd)?'':'light'));}}return$I;}function
loginForm(){echo"<table class='layout'>\n",adminer()->loginFormField('driver','<tr><th>'.lang(37).'<td>',html_select("auth[driver]",SqlDriver::$drivers,DRIVER,on('change','loginDriver'))),adminer()->loginFormField('server','<tr><th>'.lang(38).'<td>',"<input name='auth[server]' value='".h(SERVER)."' title='".lang(39)."' placeholder='localhost' autocapitalize='off'>"),adminer()->loginFormField('username','<tr><th>'.lang(40).'<td>','<input name="auth[username]" id="username" autofocus value="'.h($_GET["username"]).'" autocomplete="username" autocapitalize="off">'.script("fire(qs('#username').form['auth[driver]'], 'change');")),adminer()->loginFormField('password','<tr><th>'.lang(41).'<td>','<input type="password" name="auth[password]" autocomplete="current-password">'),adminer()->loginFormField('db','<tr><th>'.lang(42).'<td>','<input name="auth[db]" value="'.h($_GET["db"]).'" autocapitalize="off">'),"</table>\n","<p><input type='submit' value='".lang(43)."'>\n",checkbox("auth[permanent]",1,$_COOKIE["adminer_permanent"],lang(44))."\n";}function
loginFormField($C,$ye,$Y){return$ye.$Y."\n";}function
login($bg,$E){if($E=="")return
lang(45).require_password_link(null);if(!Driver::$passwords)return
lang(46).require_password_link($E);if(!password_required())return
lang(47).require_password_link($E);return
true;}function
tableName(array$Ck){return
h($Ck["Name"]);}function
fieldName(array$m,$Ih=0){$U=$m["full_type"].($m["null"]?" NULL":"");$_b=$m["comment"];return'<span title="'.h($U.($_b!=""?($U?": ":"").$_b:'')).'">'.h($m["field"]).'</span>';}function
commentValue($U,$_b){if($_b==""||$U=='TABLE'||$U=='COLUMN')return
h($_b);$Fi=function($zj){return
preg_replace('~^~m','<tr>',preg_replace('~\|~','<td>',preg_replace('~\|$~m',"",rtrim($zj))));};$R='(\+--[-+]+\+\n)';$J='(\| .* \|\n)';return"<pre>\n".preg_replace_callback("~^$R?$J$R?($J*)$R?~m",function($A)use($Fi){$Id=$Fi($A[2]);return"<table>\n".($A[1]?"<thead>$Id<tbody>\n":$Id).$Fi($A[4])."\n</table>";},preg_replace('~(\n(    -|mysql)&gt; )(.+)~',"\\1<code class='jush-sql'>\\3</code>",preg_replace('~(.+)\n---+\n~',"<b>\\1</b>\n",h($_b))))."</pre>\n";}function
commentInput($U,$c,$_b){$Y=h($_b);return(preg_match('~\n~',$Y)?"<textarea$c rows='2' cols='".($U=='TABLE'?20:30)."' style='vertical-align: bottom;'>\n$Y</textarea>":"<input$c value='$Y'>");}function
selectLinks(array$Ck,$O=""){$C=$Ck["Name"];echo'<p class="links">';$Wf=array();if($C!="")$Wf["select"]=lang(48);if(support("table")||support("indexes"))$Wf["table"]=lang(49);$uf=false;if(support("table")){$uf=is_view($Ck);if($uf){if(support("view"))$Wf["view"]=lang(50);}elseif(function_exists('Adminer\alter_table')&&$C!="")$Wf["create"]=lang(51);}if($O!==null)$Wf["edit"]=lang(52);foreach($Wf
as$x=>$X)echo" <a href='".h(ME)."$x=".url_escape($C).($x=="edit"?$O:"")."'".bold(isset($_GET[$x])).">$X</a>";echo
doc_link(array(JUSH=>driver()->tableHelp($C,$uf)),"?"),"\n";}function
foreignKeys($R){return
foreign_keys($R);}function
backwardKeys($R,$Bk){return
array();}function
backwardKeysPrint(array$Ma,array$J){}function
selectQuery($G,$qk,$ud=false){$I="\n";if(!$ud&&($nm=driver()->warnings())){$t="warnings";$I=", <a href='#$t' class='toggle'>".lang(53)."</a>"."$I<div id='$t' class='hidden'>\n$nm</div>\n";}return"<p><code class='jush-".JUSH."'>".h(str_replace("\n"," ",$G))."</code> <span class='time'>(".format_time($qk).")</span>".(support("sql")?" <a href='".h(ME)."sql=".url_escape($G)."' class='hover'>".lang(13)."</a>":"").$I;}function
sqlCommandQuery($G){return
shorten_utf8(trim($G),1000);}function
sqlPrintAfter(){}function
rowDescription($R){return"";}function
rowDescriptions(array$K,array$Qd){return$K;}function
selectLink($X,array$m){}function
selectVal($X,$_,array$m,$Sh){$I=($X===null?"<i>NULL</i>":(preg_match("~char|binary|boolean~",$m["type"])&&!preg_match("~var~",$m["type"])?"<code>$X</code>":(preg_match('~^jsonb?$~',$m["full_type"])?"<code class='jush-json'>$X</code>":$X)));if(is_blob($m)&&!is_utf8($X))$I="<i>".lang(54,strlen($Sh))."</i>";return($_?"<a href='".h($_)."'".(is_url($_)?target_blank():"").">$I</a>":$I);}function
editVal($X,array$m){return$X;}function
config(){return
array();}function
tableStructurePrint(array$n,$Ck=null){echo"<div class='scrollable'>\n","<table class='nowrap odds'>\n","<thead><tr><th>".lang(55)."<td>".lang(56).(support("comment")?"<td>".lang(57):"")."<tbody>\n";$uk=driver()->structuredTypes();foreach($n
as$m){echo"<tr><th>".h($m["field"]);$U=h($m["full_type"]);$ub=h($m["collation"]);echo"<td><span title='$ub'>".(in_array($U,(array)$uk[lang(7)])?"<a href='".h(ME.'type='.url_escape($U))."'>$U</a>":$U.($ub&&isset($Ck["Collation"])&&$ub!=$Ck["Collation"]?" $ub":""))."</span>",($m["null"]?" <i>NULL</i>":""),($m["auto_increment"]?" <i>".lang(58)."</i>":""),(isset($m["default"])?" <span title='".lang(59)."'>[<b>".($m["generated"]?"<code class='jush-".JUSH."'>".shorten_utf8(preg_replace('~\s+~',' ',ltrim($m["default"])),80,"</code>"):h($m["default"]))."</b>]</span>":""),(support("comment")?"<td>".adminer()->commentValue('COLUMN',$m["comment"]):""),"\n";}echo"</table>\n","</div>\n";}function
tableIndexesPrint(array$w,array$Ck){$fi=false;foreach($w
as$C=>$v)$fi|=!!$v["partial"];echo"<table>\n";$kc=first(driver()->indexAlgorithms($Ck));foreach($w
as$C=>$v){ksort($v["columns"]);$Mi=array();foreach($v["columns"]as$x=>$X)$Mi[]="<i>".h($X)."</i>".($v["lengths"][$x]?"(".h($v["lengths"][$x]).")":"").($v["descs"][$x]?" DESC":"");echo"<tr title='".h($C)."'>","<th>".h($v["type"]).($kc&&$v['algorithm']!=$kc?" (".h($v['algorithm']).")":""),"<td>".implode(", ",$Mi);if($fi)echo"<td>".($v['partial']?"<code class='jush-".JUSH."'>WHERE ".h($v['partial']):"");echo"\n";}echo"</table>\n";}function
selectColumnsPrint(array$M,array$e){print_fieldset("select",lang(60),$M);$s=0;$M[""]=array();foreach($M
as$x=>$X){$X=idx($_GET["columns"],$x,array());$d=select_input(" name='columns[$s][col]' data-default=''".on('change',($x!==""?'selectFieldChange':'selectAddRow')),$e,$X["col"]);echo"<div>".(driver()->functions||driver()->grouping?html_select("columns[$s][fun]",array(-1=>"")+array_filter(array(lang(61)=>driver()->functions,lang(62)=>driver()->grouping)),$X["fun"]," data-default=''".on('change',($x!==""?'helpClose':'selectFunAddRow')).on_help_value(' (.*)|$','($1)'))."($d)":$d)."</div>\n";$s++;}echo"</div></fieldset>\n";}function
selectSearchPrint(array$Z,array$e,array$w){print_fieldset("search",lang(63),$Z);foreach($w
as$s=>$v){if($v["type"]=="FULLTEXT")echo"<div>(<i>".implode("</i>, <i>",array_map('Adminer\h',$v["columns"]))."</i>) AGAINST"," <input type='search' name='fulltext[$s]' value='".h(idx($_GET["fulltext"],$s))."' data-default=''".on('input','selectFieldChange').">",(JUSH=='sql'?checkbox("boolean[$s]",1,isset($_GET["boolean"][$s]),"BOOL"):''),"</div>\n";}$Dh=adminer()->operators();foreach(array_merge((array)$_GET["where"],array(array()))as$s=>$X){if(!$X||("$X[col]$X[val]"!=""&&in_array($X["op"],$Dh)))echo"<div>".select_input(" name='where[$s][col]' data-default=''".on('change',($X?'selectFieldChange':'selectAddRow')),$e,$X["col"],"(".lang(64).")"),html_select("where[$s][op]",$Dh,$X["op"]," data-default='".h(first($Dh))."'".on('change','selectFirstChange')),"<input type='search' name='where[$s][val]' value='".h($X["val"])."' data-default=''".on('input','selectFirstChange').on('keydown','selectSearchKeydown').on('search','selectSearchSearch').">","</div>\n";}echo"</div></fieldset>\n";}function
selectOrderPrint(array$Ih,array$e,array$w){print_fieldset("sort",lang(65),$Ih);$s=0;foreach((array)$_GET["order"]as$x=>$X){if($X!=""){echo"<div>".select_input(" name='order[$s]' data-default=''".on('change','selectFieldChange'),$e,$X),checkbox("desc[$s]",1,isset($_GET["desc"][$x]),lang(66))."</div>\n";$s++;}}echo"<div>".select_input(" name='order[$s]' data-default=''".on('change','selectAddRow'),$e),checkbox("desc[$s]",1,false,lang(66))."</div>\n","</div></fieldset>\n";}function
selectLimitPrint($z){echo"<fieldset><legend>".lang(67)."</legend><div>","<input type='number' name='limit' class='size' value='".h($z?:"")."' data-default='50'".on('input','selectFieldChange').">","</div></fieldset>\n";}function
selectLengthPrint($Zk){echo"<fieldset><legend>".lang(68)."</legend><div>","<input type='number' name='text_length' class='size' value='".h($Zk)."' data-default='100'>","</div></fieldset>\n";}function
selectActionPrint(array$w){echo"<fieldset><legend>".lang(69)."</legend><div>","<input type='submit' value='".lang(60)."'>"," <span id='noindex' title='".lang(70)."'></span>","<script".nonce().">\n","const indexColumns = ";$e=array();foreach($w
as$v){$ac=reset($v["columns"]);if($v["type"]!="FULLTEXT"&&$ac)$e[$ac]=1;}$e[""]=1;foreach($e
as$x=>$X)json_row($x);echo";\n","selectFieldChange.call(qs('#form')['select']);\n","</script>\n","</div></fieldset>\n";}function
selectCommandPrint(){return!information_schema(DB);}function
selectImportPrint(){return!information_schema(DB);}function
selectEmailPrint(array$Sc,array$e){}function
selectColumnsProcess(array$e,array$w){$M=array();$ge=array();foreach((array)$_GET["columns"]as$x=>$X){if($X["fun"]=="count"||($X["col"]!=""&&(!$X["fun"]||in_array($X["fun"],driver()->functions)||in_array($X["fun"],driver()->grouping)))){$M[$x]=apply_sql_function($X["fun"],($X["col"]!=""?idf_escape($X["col"]):"*"));if(!in_array($X["fun"],driver()->grouping))$ge[]=$M[$x];}}return
array($M,$ge);}function
selectSearchProcess(array$n,array$w){$I=array();foreach($w
as$s=>$v){if($v["type"]=="FULLTEXT"&&idx($_GET["fulltext"],$s)!="")$I[]="MATCH (".implode(", ",array_map('Adminer\idf_escape',$v["columns"])).") AGAINST (".q($_GET["fulltext"][$s]).(isset($_GET["boolean"][$s])?" IN BOOLEAN MODE":"").")";}$Dh=adminer()->operators();foreach((array)$_GET["where"]as$x=>$X){$X+=array("col"=>"","op"=>first($Dh),"val"=>"");$_GET["where"][$x]=$X;$sb=$X["col"];if("$sb$X[val]"!=""&&in_array($X["op"],$Dh)){if($X["op"]=="SQL"&&(!$_POST||!verify_token()))SqlDb::$untrusted=true;$Eb=array();foreach(($sb!=""?array($sb=>$n[$sb]):$n)as$C=>$m){$Gi="";$Db=" $X[op]";if(preg_match('~IN$~',$X["op"]))$Db
.=" ".($X["val"]!=""?process_in($X["val"]):"(NULL)");elseif($X["op"]=="SQL")$Db=" $X[val]";elseif(preg_match('~^(I?LIKE) %%$~',$X["op"],$A))$Db=" $A[1] ".q("%$X[val]%");elseif($X["op"]=="FIND_IN_SET"){$Gi="$X[op](".q($X["val"]).", ";$Db=")";}elseif(!preg_match('~NULL$~',$X["op"]))$Db
.=" ".q($X["val"]);if($sb!=""||is_searchable($m,$X))$Eb[]=$Gi.driver()->convertSearch(idf_escape($C),$X,$m).$Db;}$I[]=(count($Eb)==1?$Eb[0]:($Eb?"(".implode(" OR ",$Eb).")":"1 = 0"));}}return$I;}function
selectOrderProcess(array$n,array$w){$I=array();foreach((array)$_GET["order"]as$x=>$X){if($X!="")$I[]=(preg_match('~^((COUNT\(DISTINCT |[A-Z0-9_]+\()(`(?:[^`]|``)+`|"(?:[^"]|"")+")\)|COUNT\(\*\))$~',$X)?$X:idf_escape($X)).(isset($_GET["desc"][$x])?" DESC".(JUSH=='pgsql'&&idx($n[$X],"null")?" NULLS LAST":""):"");}return$I;}function
selectLimitProcess(){return(isset($_GET["limit"])?intval($_GET["limit"]):50);}function
selectLengthProcess(){return(isset($_GET["text_length"])?"$_GET[text_length]":"100");}function
selectEmailProcess(array$Z,array$Qd){return
false;}function
selectQueryBuild(array$M,array$Z,array$ge,array$Ih,$z,$D){return"";}function
messageQuery($G,$bl,$ud=false){restart_session();$Be=&get_session("queries");if(!idx($Be,$_GET["db"]))$Be[$_GET["db"]]=array();if(strlen($G)>1e6)$G=preg_replace('~[\x80-\xFF]+$~','',substr($G,0,1e6))."\n…";$Be[$_GET["db"]][]=array($G,time(),$bl);$lk="sql-".count($Be[$_GET["db"]]);$I="<a href='#$lk' class='toggle'>".lang(71)."</a> ".copy_icon()."\n";if(!$ud&&($nm=driver()->warnings())){$t="warnings-".count($Be[$_GET["db"]]);$I="<a href='#$t' class='toggle'>".lang(53)."</a>, $I<div id='$t' class='hidden'>\n$nm</div>\n";}return" <span class='time'>".@date("H:i:s")."</span>"." $I<div id='$lk' class='hidden'><pre><code class='jush-".JUSH."'>".shorten_utf8($G,1e4)."</code></pre>".($bl?" <span class='time'>($bl)</span>":'').(support("sql")?'<p><a href="'.h(str_replace("db=".url_escape(DB),"db=".url_escape($_GET["db"]),ME).'sql=&history='.(count($Be[$_GET["db"]])-1)).'">'.lang(13).'</a>':'').'</div>';}function
error(){return
error();}function
editRowPrint($R,array$n,$J,$Ll,$G='',$bl=''){echo($G!=""?"<p><code class='jush-".JUSH."'>".h(str_replace("\n"," ",$G))."</code> <span class='time'>($bl)</span>\n":"");}function
editFunctions(array$m){$I=($m["null"]?"NULL/":"");$ue=isset($_GET["select"])||where($_GET);foreach(array(driver()->insertFunctions,driver()->editFunctions)as$x=>$ae){if(!$x||(!isset($_GET["call"])&&$ue)){foreach($ae
as$si=>$X){if(!$si||preg_match("~$si~",$m["type"]))$I
.="/$X";}}if($x&&$ae&&!preg_match('~set|bool~',$m["type"])&&!is_blob($m))$I
.="/SQL";}if($m["auto_increment"]&&!$ue)$I=lang(58);return
explode("/",$I);}function
editInput($R,array$m,$c,$Y){if($m["type"]=="enum")return(isset($_GET["select"])?"<label><input type='radio'$c value='orig' checked><i>".lang(11)."</i></label> ":"").enum_input("radio",$c,$m,$Y,"NULL");return"";}function
editHint($R,array$m,$Y){return"";}function
processInput(array$m,$Y,$r=""){if($r=="SQL")return$Y;$C=$m["field"];$I=q($Y);if(preg_match('~^(now|getdate|uuid)$~',$r))$I="$r()";elseif(preg_match('~^current_(date|timestamp)$~',$r))$I=$r;elseif(preg_match('~^([+-]|\|\|)$~',$r))$I=idf_escape($C)." $r $I";elseif(preg_match('~^[+-] interval$~',$r))$I=idf_escape($C)." $r ".(preg_match("~^(\\d+|'[0-9.: -]') [A-Z_]+\$~i",$Y)&&JUSH!="pgsql"?$Y:$I);elseif(preg_match('~^(addtime|subtime|concat)$~',$r))$I="$r(".idf_escape($C).", $I)";elseif(preg_match('~^(md5|sha1|password|encrypt)$~',$r))$I="$r($I)";return
unconvert_field($m,$I);}function
dumpOutput(){$I=array('text'=>lang(72),'file'=>lang(73));if(function_exists('gzencode'))$I['gz']='gzip';return$I;}function
dumpFormat(){return(support("dump")?array('sql'=>'SQL'):array())+array('csv'=>'CSV,','csv;'=>'CSV;','tsv'=>'TSV');}function
dumpPrint(){}function
dumpDatabase($j){}function
dumpTable($R,$vk,$uf=0){if($_POST["format"]!="sql"){echo"\xef\xbb\xbf";if($vk)dump_csv(array_keys(fields($R)));}else{if($uf==2){$n=array();foreach(fields($R)as$C=>$m)$n[]=idf_escape($C)." $m[full_type]";$h="CREATE TABLE ".table($R)." (".implode(", ",$n).")";}else$h=create_sql($R,$_POST["auto_increment"],$vk);set_utf8mb4($h);if($vk&&$h){if(($vk=="DROP+CREATE"&&!function_exists('Adminer\drop_sql'))||$uf==1)echo"DROP ".($uf==2?"VIEW":"TABLE")." IF EXISTS ".table($R).";\n";if($uf==1)$h=remove_definer($h);echo"$h;\n\n";}}}function
dumpData($R,$vk,$G,array$M=array(),array$Z=array(),array$ge=array(),array$Ih=array()){if($vk){$mg=(JUSH=="sqlite"?0:1048576);$n=array();$Ke=false;if($_POST["format"]=="sql"){if($vk=="TRUNCATE+INSERT"&&!function_exists('Adminer\truncate_all_sql'))echo
truncate_sql($R).";\n";$n=fields($R);if(JUSH=="mssql"){foreach($n
as$m){if($m["auto_increment"]){echo"SET IDENTITY_INSERT ".table($R)." ON;\n";$Ke=true;break;}}}}$H=($G!=""?connection()->query($G,1):driver()->select($R,($M?:array("*")),$Z,$ge,$Ih,0));if($H){$gf="";$Xa="";$Af=array();$be=array();$xk="";$xd=($R!=''?'fetch_assoc':'fetch_row');$Sb=0;while($J=$H->$xd()){if(!$Af){$em=array();foreach($J
as$X){$m=$H->fetch_field();if(idx($n[$m->name],'generated')){$be[$m->name]=true;continue;}$Af[]=$m->name;$x=idf_escape($m->name);$em[]="$x = VALUES($x)";}$xk=($vk=="INSERT+UPDATE"?"\nON DUPLICATE KEY UPDATE ".implode(", ",$em):"").";\n";}if($_POST["format"]!="sql"){if($vk=="table"){dump_csv($Af);$vk="INSERT";}dump_csv($J);}else{if(!$gf)$gf="INSERT INTO ".table($R)." (".implode(", ",array_map('Adminer\idf_escape',$Af)).") VALUES";foreach($J
as$x=>$X){if($be[$x]){unset($J[$x]);continue;}$m=$n[$x];$J[$x]=($X===null?"NULL":($X===false?0:unconvert_field($m,preg_match(number_type(),$m["type"])&&!preg_match('~\[~',$m["full_type"])&&is_numeric($X)?$X:(!is_blob($m)||is_utf8($X)?q($X):driver()->quoteBinary($X)))));}$zj=($mg?"\n":" ")."(".implode(",\t",$J).")";if(!$Xa)$Xa=$gf.$zj;elseif(JUSH=='mssql'?$Sb%1000!=0:strlen($Xa)+4+strlen($zj)+strlen($xk)<$mg)$Xa
.=",$zj";else{echo$Xa.$xk;$Xa=$gf.$zj;}}$Sb++;}if($Xa)echo$Xa.$xk;}elseif($_POST["format"]=="sql")echo"-- ".str_replace("\n"," ",connection()->error)."\n";if($Ke)echo"SET IDENTITY_INSERT ".table($R)." OFF;\n";}}function
dumpFilename($Ie){return
friendly_url($Ie!=""?$Ie:(SERVER?:"localhost"));}function
dumpHeaders($Ie,$Ng=false){$Wh=$_POST["output"];$pd=(preg_match('~sql~',$_POST["format"])?"sql":($Ng?"tar":"csv"));header("Content-Type: ".($Wh=="gz"?"application/x-gzip":($pd=="tar"?"application/x-tar":($pd=="sql"||$Wh!="file"?"text/plain":"text/csv")."; charset=utf-8")));if($Wh=="gz"){ob_start(function($Q){return
gzencode($Q);},1e6);}return$pd;}function
dumpFooter(){if($_POST["format"]=="sql")echo"-- ".gmdate("Y-m-d H:i:s e")."\n";}function
importServerPath(){return"adminer.sql";}function
importPrint(){}function
importProcess(){return
false;}function
homepage(){echo'<p class="links">'.($_GET["ns"]==""&&support("database")?'<a href="'.h(ME).'database=">'.lang(74)."</a>\n":""),(support("scheme")?"<a href='".h(ME)."scheme='>".($_GET["ns"]!=""?lang(75):lang(76))."</a>\n":""),($_GET["ns"]!==""?'<a href="'.h(ME).'schema=">'.lang(77)."</a>\n":""),(support("privileges")?"<a href='".h(ME)."privileges='>".lang(78)."</a>\n":"");if($_GET["ns"]!=="")echo(support("routine")?"<a href='#routines'>".lang(79)."</a>\n":""),(support("sequence")?"<a href='#sequences'>".lang(80)."</a>\n":""),(support("type")?"<a href='#user-types'>".lang(7)."</a>\n":""),(support("event")?"<a href='#events'>".lang(81)."</a>\n":"");return
true;}function
navigation($Hg){echo"<h1>".adminer()->name()." <span class='version'>".VERSION;$ch=$_COOKIE["adminer_version"];echo" <a href='https://www.adminer.org/#download'".target_blank()." id='version'>".(version_compare(VERSION,$ch)<0?h($ch):"").version_iframe()."</a>","</span></h1>\n";switch_lang();if($Hg=="auth"){$Wh="";foreach((array)$_SESSION["pwds"]as$gm=>$Tj){foreach($Tj
as$N=>$Yl){$C=h(get_setting("vendor-$gm-$N")?:get_driver($gm));foreach($Yl
as$V=>$E){if($C&&$E!==null){$ic=$_SESSION["db"][$gm][$N][$V];foreach(($ic?array_keys($ic):array(""))as$j)$Wh
.="<li><a href='".h(auth_url($gm,$N,$V,$j))."'>($C) ".h("$V@").($N!=""?adminer()->serverName($N):"").h($j!=""?" - $j":"")."</a>\n";}}}}if($Wh)echo"<ul id='logins'".on('mouseover','menuOver').on('mouseout','menuOut').">\n$Wh</ul>\n";}else{$T=array();if($_GET["ns"]!==""&&!$Hg&&DB!=""){connection()->select_db(DB);$T=table_status('',true);}adminer()->syntaxHighlighting($T);adminer()->databasesPrint($Hg);$la=array();if(DB==""||!$Hg){if(support("sql")){$la['sql']="<a href='".h(ME)."sql='".bold(isset($_GET["sql"])&&!isset($_GET["import"])).">".lang(71)."</a>";$la['import']="<a href='".h(ME)."import='".bold(isset($_GET["import"])).">".lang(82)."</a>";}$la['dump']="<a href='".h(ME)."dump=".url_escape(isset($_GET["table"])?$_GET["table"]:$_GET["select"])."' id='dump'".bold(isset($_GET["dump"])).">".lang(83)."</a>";}$Pe=$_GET["ns"]!==""&&!$Hg&&DB!="";if($Pe&&function_exists('Adminer\alter_table'))$la['create']='<a href="'.h(ME).'create="'.bold($_GET["create"]==="").">".lang(84)."</a>";$la=adminer()->menuActions($la,$Hg);echo($la?"<p class='links'>\n".implode("\n",$la)."\n":"");if($Pe){if($T)adminer()->tablesPrint($T);else
echo"<p class='message'>".lang(12)."</p>\n";}}}function
syntaxHighlighting(array$T){echo
script_src(preg_replace("~\\?.*~","",ME)."?file=jush.js&version=6.0.1",true);$Jg=preg_replace('~<(?=/script)~i','<\\',Driver::jushModule());echo($Jg?script("addEventListener('DOMContentLoaded', () => {\n$Jg\n});"):"");if(support("sql")){echo"<script".nonce().">\n";if($T){$Wf=array();foreach($T
as$R=>$U)$Wf[]=js_escape_re($R);echo"var jushLinks = { ".JUSH.":";json_row(js_escape(ME).(support("table")?"table":"select").'=$&','/\b(?<!\$)('.implode('|',$Wf).')(?!\$)\b/g',false);$nk=array("sql","check","event","procedure","trigger","view","type","table","processlist");if(support("routine")&&array_intersect_key($_GET,array_flip($nk))){foreach(routines()as$J)json_row(js_escape(ME).'function='.url_escape($J["SPECIFIC_NAME"]).'&name=$&','/\b'.js_escape_re($J["ROUTINE_NAME"]).'(?=["`\]]?\()/g',false);}json_row('');echo"};\n";foreach(array("bac","bra","sqlite_quo","mssql_bra")as$X)echo"jushLinks.$X = jushLinks.".JUSH.";\n";if(isset($_GET["sql"])||isset($_GET["trigger"])||isset($_GET["check"])){$rk=(isset($_GET["trigger"])?array('INSERT INTO','UPDATE','DELETE FROM'):(isset($_GET["check"])?array():null));$Ia=Driver::jushAutocomplete($T,$rk);echo($Ia?"addEventListener('DOMContentLoaded', () => { autocompleter = $Ia; });\n":"");}}echo"</script>\n";}echo
script("syntaxHighlighting('".(preg_match('~^\d\.?\d~',connection()->server_info,$A)?$A[0]:"")."', '".connection()->flavor."');");}function
databasesPrint($Hg){if(support("single_db"))return;$i=adminer()->databases();if(DB&&$i&&!in_array(DB,$i))array_unshift($i,DB);echo"<form action=''>\n<p id='dbs'>\n";hidden_fields_get();$gc=on('mousedown','dbMouseDown').on('change','dbChange');echo"<label title='".lang(42)."'>".lang(85).": ".($i?html_select("db",array(""=>"")+$i,DB,$gc):"<input name='db' value='".h(DB)."' autocapitalize='off' size='19'>\n")."</label>","<input type='submit' value='".lang(24)."'".($i?" class='hidden'":"").">\n";if(support("scheme")){if($Hg!="db"&&DB!=""&&connection()->select_db(DB)){echo"<br><label>".lang(86).": ".html_select("ns",array(""=>"")+adminer()->schemas(),$_GET["ns"],$gc)."</label>";if($_GET["ns"]!="")set_schema($_GET["ns"]);}}foreach(array("import","sql","schema","dump","privileges")as$X){if(isset($_GET[$X])){echo
input_hidden($X);break;}}echo"</p></form>\n";}function
menuActions(array$la,$Hg){return$la;}function
tablesPrint(array$T){echo"<ul id='tables'".on('mouseover','menuOver').on('mouseout','menuOut').">";foreach($T
as$R=>$P){$R="$R";$C=adminer()->tableName($P);if($C!=""&&!$P["partition"])echo'<li><a href="'.h(ME).'select='.url_escape($R).'"'.bold($_GET["select"]==$R||$_GET["edit"]==$R,"select hover")." title='".lang(48)."'>".lang(87)."</a> ",(support("table")||support("indexes")?'<a href="'.h(ME).'table='.url_escape($R).'"'.bold(in_array($R,array($_GET["table"],$_GET["create"],$_GET["indexes"],$_GET["foreign"],$_GET["trigger"],$_GET["check"],$_GET["view"])),(is_view($P)?"view":"structure"))." title='".lang(49)."'>$C</a>":"<span>$C</span>")."\n";}echo"</ul>\n";}function
showVariables(){return
show_variables();}function
showStatus(){return
show_status();}function
processList(){return
process_list();}function
killProcess($t){return
kill_process($t);}}class
Plugins{private
static$append=array('dumpFormat'=>true,'dumpOutput'=>true,'editRowPrint'=>true,'editFunctions'=>true,'config'=>true);var$plugins;var$drivers=array();var$driverFiles=array();var$error='';private$hooks=array();function
__construct($zi){$Gc=SqlDriver::$drivers;$_e=" href='https://www.adminer.org/plugins/#use'".target_blank();if($zi===null){$zi=array();$Qa="adminer-plugins";if(is_dir($Qa)){foreach(glob("$Qa/*.php")as$o){$Cd=SqlDriver::$drivers;$this->includeOnce($o);foreach(array_diff_key(SqlDriver::$drivers,$Cd)as$t=>$C)$this->driverFiles[$t]=$o;}}if(file_exists("$Qa.php")){$Re=$this->includeOnce("$Qa.php");if(is_array($Re)){foreach($Re
as$x=>$wi)$zi[is_object($wi)?get_class($wi):$x]=$wi;}else$this->error
.=lang(88,"<b>$Qa.php</b>",$_e)."<br>";}foreach(get_declared_classes()as$pb){if(!$zi[$pb]&&(preg_match('~^Adminer\w~i',$pb)||is_subclass_of($pb,'Adminer\Plugin'))){$fj=new
\ReflectionClass($pb);$Kb=$fj->getConstructor();if($Kb&&$Kb->getNumberOfRequiredParameters())$this->error
.=lang(89,$_e,"<b>$pb</b>","<b>$Qa.php</b>")."<br>";else$zi[$pb]=new$pb;}}}$lf=array_filter($zi,function($wi){return!is_object($wi);});if($lf){$this->error
.=lang(90,$_e)."<br>";$zi=array_diff_key($zi,$lf);}$this->drivers=array_diff_key(SqlDriver::$drivers,$Gc);$this->plugins=$zi;$pa=new
Adminer;$zi[]=$pa;$fj=new
\ReflectionObject($pa);foreach($fj->getMethods()as$Eg){foreach($zi
as$wi){$C=$Eg->getName();if(method_exists($wi,$C))$this->hooks[$C][]=$wi;}}}function
includeOnce($o){return
include_once"./$o";}static
function
checksum($o){$Bd=str_replace("\r","",file_get_contents($o));$Bd=preg_replace('~\n\tprotected \$translations = array\(.*?\n\t\);~s','',$Bd);return
dechex(crc32($Bd));}function
checksums(){$Dd=array_values($this->driverFiles);foreach($this->plugins
as$wi){$fj=new
\ReflectionObject($wi);$Dd[]=$fj->getFileName();}$I=array();foreach($Dd
as$o)$I[basename($o,'.php')]=self::checksum($o);return$I;}static
function
officialChecksums(){return
array('adminer.js'=>'a0599090','backward-keys'=>'ed1ef78f','before-unload'=>'2a613523','config'=>'722eb4af','dark-switcher'=>'3d490dea','database-hide'=>'e304a899','designs'=>'d1515f34','dump-alter'=>'896b579e','dump-bz2'=>'f0d0e336','dump-date'=>'adc7f1c7','dump-json'=>'767dd321','dump-xml'=>'4fc3cd60','dump-zip'=>'93817d96','edit-foreign'=>'72ad1562','edit-textarea'=>'a24c3cc','editor-setup'=>'a7dc3a37','editor-views'=>'5c12b185','enum-option'=>'96ee8718','file-upload'=>'10add0e8','foreign-system'=>'ebb4c654','frames'=>'b0e1d11a','highlight-codemirror'=>'f4baf411','highlight-monaco'=>'edd1b0af','highlight-prism'=>'267948e5','import-csv'=>'d429c77','login-ip'=>'4d174fea','login-otp'=>'5b5a68af','login-passkey'=>'f69f2f06','login-password-less'=>'e150daac','login-reverse-proxy'=>'24558ea2','login-servers'=>'19c42e45','login-ssl'=>'6ed147bc','login-table'=>'811f8cef','menu-links'=>'7f3d5020','remote-color'=>'86a39047','row-numbers'=>'eec8698c','select-email'=>'f84fbd2c','select-image'=>'f55c0231','slugify'=>'dec64713','sql-gemini'=>'c60ab309','sql-log'=>'8e435000','table-indexes-structure'=>'a90cc0c9','table-structure'=>'a8458e02','tables-filter'=>'ec2bcd6e','timeout'=>'97321caf','version-github'=>'627cadf9','version-noverify'=>'966937e9','clickhouse'=>'b0f6631c','elastic'=>'27503b8b','firebird'=>'5499d1a','igdb'=>'59055fd3','imap'=>'ac143217','mongo'=>'c3b8f5a4','redis'=>'ba56e72e','simpledb'=>'92f050ad',);}function
__call($C,array$ci){$Aa=array();foreach($ci
as$x=>$X)$Aa[]=&$ci[$x];$I=null;foreach($this->hooks[$C]as$wi){$Y=call_user_func_array(array($wi,$C),$Aa);if($Y!==null){if(!self::$append[$C])return$Y;$I=$Y+(array)$I;}}return$I;}}abstract
class
Plugin{protected$translations=array();function
description(){return$this->lang('');}function
screenshot(){return"";}protected
function
lang($u,$ih=null){$Aa=func_get_args();$Aa[0]=idx($this->translations[LANG],$u)?:$u;return
call_user_func_array('Adminer\lang_format',$Aa);}}class
Password{private$password_hash;private$password_matches=null;function
__construct($oi){$this->password_hash=$oi;}function
description(){return
lang(91);}function
credentials(){$E=get_password();return
array(SERVER,$_GET["username"],($this->passwordMatches($E)&&!password_required()?"":$E));}function
login($bg,$E){if($this->passwordMatches($E))return
true;}protected
function
passwordMatches($E){if($this->password_matches===null)$this->password_matches=(function_exists('password_verify')&&password_verify(strval($E),$this->password_hash));return$this->password_matches;}}Adminer::$instance=(function_exists('adminer_object')?adminer_object():(is_dir("adminer-plugins")||file_exists("adminer-plugins.php")?new
Plugins(null):new
Adminer));SqlDriver::$drivers=array("server"=>"MySQL / MariaDB")+SqlDriver::$drivers;if(!defined('Adminer\DRIVER')){define('Adminer\DRIVER',"server");if(extension_loaded("mysqli")&&$_GET["ext"]!="pdo"){class
Db
extends
\mysqli{static$instance;var$extension="MySQLi",$flavor='';function
__construct(){parent::init();}function
attach($N,$V,$E){mysqli_report(MYSQLI_REPORT_OFF);list($Ee,$_i)=host_port($N);$pk=adminer()->connectSsl();$Ul=($pk&&($pk['key']||$pk['cert']||$pk['ca']||isset($pk['verify'])));if($Ul)$this->ssl_set($pk['key'],$pk['cert'],$pk['ca'],'','');$I=@$this->real_connect(($N!=""?$Ee:ini_get("mysqli.default_host")),($N.$V!=""?$V:ini_get("mysqli.default_user")),($N.$V.$E!=""?$E:ini_get("mysqli.default_pw")),null,(is_numeric($_i)?intval($_i):ini_get("mysqli.default_port")),(is_numeric($_i)?null:$_i),($Ul?($pk['verify']!==false?MYSQLI_CLIENT_SSL:64):0));$this->options(MYSQLI_OPT_LOCAL_INFILE,0);return($I?'':$this->error);}function
set_charset($fb){if(parent::set_charset($fb))return
true;parent::set_charset('utf8');return$this->query("SET NAMES $fb");}function
next_result(){return
self::more_results()&&parent::next_result();}function
quote($Q){return"'".$this->escape_string($Q)."'";}function
inTransaction(){return
false;}}}elseif(extension_loaded("mysql")&&!((ini_bool("sql.safe_mode")||ini_bool("mysql.allow_local_infile"))&&extension_loaded("pdo_mysql"))){class
Db
extends
SqlDb{private$link;function
attach($N,$V,$E){if(ini_bool("mysql.allow_local_infile"))return
lang(92,"'mysql.allow_local_infile'","MySQLi","PDO_MySQL");$this->link=@mysql_connect(($N!=""?$N:ini_get("mysql.default_host")),($N.$V!=""?$V:ini_get("mysql.default_user")),($N.$V.$E!=""?$E:ini_get("mysql.default_password")),true,131072);if(!$this->link)return
mysql_error();$this->server_info=mysql_get_server_info($this->link);return'';}function
set_charset($fb){return
mysql_set_charset($fb,$this->link)||mysql_set_charset('utf8',$this->link);}function
quote($Q){return"'".mysql_real_escape_string($Q,$this->link)."'";}function
select_db($fc){return
mysql_select_db($fc,$this->link);}function
query($G,$Cl=false){$H=@($Cl?mysql_unbuffered_query($G,$this->link):mysql_query($G,$this->link));$this->error="";if(!$H){$this->errno=mysql_errno($this->link);$this->error=mysql_error($this->link);return
false;}if($H===true){$this->affected_rows=mysql_affected_rows($this->link);$this->info=mysql_info($this->link);return
true;}return
new
Result($H);}}class
Result{var$num_rows;private$result;private$offset=0;function
__construct($H){$this->result=$H;$this->num_rows=mysql_num_rows($H);}function
fetch_assoc(){return
mysql_fetch_assoc($this->result);}function
fetch_row(){return
mysql_fetch_row($this->result);}function
fetch_field(){$I=mysql_fetch_field($this->result,$this->offset++);$I->orgtable=$I->table;$I->charsetnr=($I->blob?63:0);return$I;}}}elseif(extension_loaded("pdo_mysql")){class
Db
extends
PdoDb{var$extension="PDO_MySQL";function
attach($N,$V,$E){$Gh=array(\PDO::MYSQL_ATTR_LOCAL_INFILE=>false);if(isset($_GET["select"]))$Gh[\PDO::MYSQL_ATTR_MULTI_STATEMENTS]=false;$pk=adminer()->connectSsl();if($pk){if($pk['key'])$Gh[\PDO::MYSQL_ATTR_SSL_KEY]=$pk['key'];if($pk['cert'])$Gh[\PDO::MYSQL_ATTR_SSL_CERT]=$pk['cert'];if($pk['ca'])$Gh[\PDO::MYSQL_ATTR_SSL_CA]=$pk['ca'];if(isset($pk['verify']))$Gh[\PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT]=$pk['verify'];}list($Ee,$_i)=host_port($N);return$this->dsn("mysql:charset=utf8".($Ee!=""?";host=$Ee":'').($_i?(is_numeric($_i)?";port=":";unix_socket=").$_i:""),$V,$E,$Gh);}function
set_charset($fb){return$this->query("SET NAMES $fb");}function
select_db($fc){return$this->query("USE ".idf_escape($fc));}function
query($G,$Cl=false){$this->pdo->setAttribute(\PDO::MYSQL_ATTR_USE_BUFFERED_QUERY,!$Cl);return
parent::query($G,$Cl);}}}class
Driver
extends
SqlDriver{static$extensions=array("MySQLi","MySQL","PDO_MySQL");static$jush="sql";var$unsigned=array("unsigned","zerofill","unsigned zerofill");var$operators=array("=","<",">","<=",">=","!=","LIKE","LIKE %%","REGEXP","IN","FIND_IN_SET","IS NULL","NOT LIKE","NOT REGEXP","NOT IN","IS NOT NULL","SQL");var$functions=array("char_length","date","from_unixtime","lower","round","floor","ceil","sec_to_time","time_to_sec","upper");var$grouping=array("avg","count","count distinct","group_concat","max","min","sum");var$partitionBy=array("HASH","LINEAR HASH","KEY","LINEAR KEY","RANGE","LIST");static
function
connect($N,$V,$E){$f=parent::connect($N,$V,$E);if(is_string($f)){if(function_exists('iconv')&&!is_utf8($f)&&strlen($zj=iconv("windows-1252","utf-8//IGNORE",$f))>strlen($f))$f=$zj;return$f;}$f->set_charset(charset($f));$f->query("SET sql_quote_show_create = 1, autocommit = 1");$f->flavor=(preg_match('~MariaDB~',$f->server_info)?'maria':'mysql');add_driver(DRIVER,($f->flavor=='maria'?"MariaDB":"MySQL"));return$f;}function
__construct(Db$f){parent::__construct($f);$this->types=array(lang(28)=>array("tinyint"=>3,"smallint"=>5,"mediumint"=>8,"int"=>10,"bigint"=>20,"decimal"=>66,"float"=>12,"double"=>21),lang(29)=>array("date"=>10,"datetime"=>19,"timestamp"=>19,"time"=>10,"year"=>4),lang(30)=>array("char"=>255,"varchar"=>65535,"tinytext"=>255,"text"=>65535,"mediumtext"=>16777215,"longtext"=>4294967295),lang(93)=>array("enum"=>65535,"set"=>64),lang(31)=>array("bit"=>20,"binary"=>255,"varbinary"=>65535,"tinyblob"=>255,"blob"=>65535,"mediumblob"=>16777215,"longblob"=>4294967295),lang(33)=>array("geometry"=>0,"point"=>0,"linestring"=>0,"polygon"=>0,"multipoint"=>0,"multilinestring"=>0,"multipolygon"=>0,"geometrycollection"=>0),);$this->insertFunctions=array("char"=>"md5/sha1/password/encrypt/uuid","binary"=>"md5/sha1","date|time"=>"now",);$this->editFunctions=array(number_type()=>"+/-","date"=>"+ interval/- interval","time"=>"addtime/subtime","char|text"=>"concat",);if(min_version('5.7.8',10.2,$f))$this->types[lang(30)]["json"]=4294967295;if(min_version('',10.7,$f)){$this->types[lang(30)]["uuid"]=128;$this->insertFunctions['uuid']='uuid';}if(min_version('',10.5,$f)){$this->types[lang(32)]["inet6"]=39;if(min_version('','10.10',$f))$this->types[lang(32)]["inet4"]=15;}if(min_version(9,11.7,$f))$this->types[lang(28)]["vector"]=16383;if(min_version(5.7,10.2,$f))$this->generated=array("STORED","VIRTUAL");}function
unconvertFunction(array$m){return(preg_match("~binary~",$m["type"])?"<code class='jush-sql'>UNHEX</code>":($m["type"]=="bit"?doc_link(array('sql'=>'bit-value-literals.html'),"<code>b''</code>"):($m["type"]=="vector"?"<code class='jush-sql'>".($this->conn->flavor=='maria'?"VEC_FromText":"STRING_TO_VECTOR")."</code>":(preg_match("~geom|point|linestring|polygon~",$m["type"])?"<code class='jush-sql'>GeomFromText</code>":""))));}function
insert($R,array$O){return($O?parent::insert($R,$O):queries("INSERT INTO ".table($R)." ()\nVALUES ()"));}function
insertUpdate($R,array$K,array$Ki){$e=array_keys(reset($K));$Gi="INSERT INTO ".table($R)." (".implode(", ",$e).") VALUES\n";$em=array();foreach($e
as$x)$em[$x]="$x = VALUES($x)";$xk="\nON DUPLICATE KEY UPDATE ".implode(", ",$em);$em=array();$y=0;foreach($K
as$O){$Y="(".implode(", ",$O).")";if($em&&(strlen($Gi)+$y+strlen($Y)+strlen($xk)>1e6)){if(!queries($Gi.implode(",\n",$em).$xk))return
false;$em=array();$y=0;}$em[]=$Y;$y+=strlen($Y)+2;}return
queries($Gi.implode(",\n",$em).$xk);}function
slowQuery($G,$cl){if(min_version('5.7.8','10.1.2')){if($this->conn->flavor=='maria')return"SET STATEMENT max_statement_time=$cl FOR $G";elseif(preg_match('~^(SELECT\b)(.+)~is',$G,$A))return"$A[1] /*+ MAX_EXECUTION_TIME(".($cl*1000).") */ $A[2]";}}function
convertColumn($u,array$m){if(preg_match("~binary~",$m["type"]))return"HEX($u)";if($m["type"]=="bit")return"BIN($u + 0)";if($m["type"]=="vector")return($this->conn->flavor=='maria'?"VEC_ToText":"VECTOR_TO_STRING")."($u)";if(preg_match("~geom|point|linestring|polygon~",$m["type"]))return(min_version(8)?"ST_":"")."AsWKT($u)";return"";}function
convertSearch($u,array$X,array$m){return($this->convertColumn($u,$m)?:(preg_match('~'.text_type().'~',$m["type"])&&!preg_match("~^utf8~",$m["collation"])&&preg_match('~[\x80-\xFF]~',$X['val'])?"CONVERT($u USING ".charset($this->conn).")":$u));}function
typeName(\stdClass$m){$Bl=array("decimal","tinyint","smallint","int","float","double",7=>"timestamp","bigint","mediumint","date","time","datetime","year",15=>"varchar","bit",242=>"vector",245=>"json","decimal","enum","set","tinytext","mediumtext","longtext","text","varchar","char","geometry",);$I=idx($Bl,$m->type,"");return
parent::typeName($m)?:($m->charsetnr==63?str_replace(array("text","varchar","char"),array("blob","varbinary","binary"),$I):$I);}function
quoteBinary($zj){return"X".q(bin2hex($zj));}function
warnings(){$H=$this->conn->query("SHOW WARNINGS");if($H&&$H->num_rows){ob_start();print_select_result($H);return
ob_get_clean();}}function
tableHelp($C,$uf=false){$dg=($this->conn->flavor=='maria');if(information_schema(DB))return
strtolower(str_replace("_","-",DB)."-".($dg?"$C-table/":str_replace("_","-",$C)."-table.html"));if(DB=="sys")return($dg?"sys-schema/":strtolower("sys-".str_replace("_","-",preg_replace('~^x\$~','',$C)).".html"));if(DB=="mysql")return($dg?"mysql$C-table/":"system-schema.html");}function
partitionsInfo($R){$Wd="FROM information_schema.PARTITIONS WHERE TABLE_SCHEMA = ".q(DB)." AND TABLE_NAME = ".q($R);$H=$this->conn->query("SELECT PARTITION_METHOD, PARTITION_EXPRESSION, PARTITION_ORDINAL_POSITION $Wd ORDER BY PARTITION_ORDINAL_POSITION DESC LIMIT 1");$J=($H?$H->fetch_row():null);if(!$J)return
array();$I=array();list($I["partition_by"],$I["partition"],$I["partitions"])=$J;$li=get_key_vals("SELECT PARTITION_NAME, PARTITION_DESCRIPTION $Wd AND PARTITION_NAME != '' ORDER BY PARTITION_ORDINAL_POSITION");$I["partition_names"]=array_keys($li);$I["partition_values"]=array_values($li);return$I;}function
hasCStyleEscapes(){static$ab;if($ab===null){$mk=get_val("SHOW VARIABLES LIKE 'sql_mode'",1,$this->conn);$ab=(strpos($mk,'NO_BACKSLASH_ESCAPES')===false);}return$ab;}function
lineComment(){return"#|-- ";}function
engines(){$I=array();foreach(get_rows("SHOW ENGINES")as$J){if(preg_match("~YES|DEFAULT~",$J["Support"]))$I[]=$J["Engine"];}return$I;}function
indexAlgorithms(array$Ck){return(preg_match('~^(MEMORY|NDB)$~',$Ck["Engine"])?array("HASH","BTREE"):array());}}function
idf_escape($u){return"`".str_replace("`","``",$u)."`";}function
table($u){return
idf_escape($u);}function
get_databases($Nd){$I=get_session("dbs");if($I===null){$G="SELECT SCHEMA_NAME FROM information_schema.SCHEMATA ORDER BY SCHEMA_NAME";$qk=microtime(true);$I=($Nd?slow_query($G):get_vals($G));if(microtime(true)-$qk>0.1){restart_session();set_session("dbs",$I);stop_session();}}return$I;}function
limit($G,$Z,$z,$ph=0,$Lj=" "){return" $G$Z".($z?$Lj."LIMIT $z".($ph?" OFFSET $ph":""):"");}function
limit1($R,$G,$Z,$Lj="\n"){return
limit($G,$Z,1,0,$Lj);}function
db_collation($j,array$vb){$I=null;$h=get_val("SHOW CREATE DATABASE ".idf_escape($j),1);if(preg_match('~ COLLATE ([^ ]+)~',$h,$A))$I=$A[1];elseif(preg_match('~ CHARACTER SET ([^ ]+)~',$h,$A))$I=$vb[$A[1]][-1];return$I;}function
logged_user(){return
get_val("SELECT USER()");}function
tables_list(){return
get_key_vals("SELECT TABLE_NAME, TABLE_TYPE FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() ORDER BY TABLE_NAME");}function
count_tables(array$i){$I=array();foreach($i
as$j)$I[$j]=count(get_vals("SHOW TABLES IN ".idf_escape($j)));return$I;}function
table_status($C="",$vd=false){$I=array();foreach(get_rows($vd?"SELECT TABLE_NAME AS Name, ENGINE AS Engine, TABLE_COMMENT AS Comment FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() ".($C!=""?"AND TABLE_NAME = ".q($C):"ORDER BY Name"):"SHOW TABLE STATUS".($C!=""?" LIKE ".q(addcslashes($C,"%_\\")):""))as$J){if($J["Engine"]=="InnoDB")$J["Comment"]=preg_replace('~(?:(.+); )?InnoDB free: .*~','\1',$J["Comment"]);if(!isset($J["Engine"]))$J["Comment"]="";if($C!="")$J["Name"]=$C;$I[$J["Name"]]=$J;}return$I;}function
is_view(array$S){return$S["Engine"]===null;}function
fk_support(array$S){return
preg_match('~InnoDB|IBMDB2I'.(min_version(5.6)?'|NDB':'').'~i',$S["Engine"]);}function
parse_type($Yd){preg_match('~^([^( ]+)(?:\((.+)\))?( unsigned)?( zerofill)?$~',$Yd,$A);return
array($A[1],$A[2],ltrim($A[3].$A[4]));}function
fields($R){$dg=(connection()->flavor=='maria');$I=array();foreach(get_rows("SELECT * FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ".q($R)." ORDER BY ORDINAL_POSITION")as$J){$m=$J["COLUMN_NAME"];$U=$J["COLUMN_TYPE"];$ce=$J["GENERATION_EXPRESSION"];$sd=$J["EXTRA"];preg_match('~^(VIRTUAL|PERSISTENT|STORED)~',$sd,$be);list($Al,$y,$Jl)=parse_type($U);$k=$J["COLUMN_DEFAULT"];if($k!=""){$tf=preg_match('~text|json~',$Al);if(!$dg&&$tf)$k=preg_replace("~^(_\w+)?('.*')$~",'\2',stripslashes($k));if($dg||$tf){$k=($k=="NULL"?null:preg_replace_callback("~^'(.*)'$~",function($A){return
stripslashes(str_replace("''","'",$A[1]));},$k));}if(!$dg&&preg_match('~binary~',$Al)&&preg_match('~^0x(\w*)$~',$k,$A))$k=pack("H*",$A[1]);}$I[$m]=array("field"=>$m,"full_type"=>$U,"type"=>$Al,"length"=>$y,"unsigned"=>$Jl,"default"=>($be?($dg?$ce:stripslashes($ce)):$k),"null"=>($J["IS_NULLABLE"]=="YES"),"auto_increment"=>($sd=="auto_increment"),"on_update"=>(preg_match('~\bon update (\w+)~i',$sd,$A)?$A[1]:""),"collation"=>$J["COLLATION_NAME"],"privileges"=>array_flip(explode(",","$J[PRIVILEGES],where,order")),"comment"=>$J["COLUMN_COMMENT"],"primary"=>($J["COLUMN_KEY"]=="PRI"),"generated"=>($be[1]=="PERSISTENT"?"STORED":$be[1]),);}return$I;}function
indexes($R,$g=null){$I=array();foreach(get_rows("SHOW INDEX FROM ".table($R),$g)as$J){$C=$J["Key_name"];$I[$C]["type"]=($C=="PRIMARY"?"PRIMARY":($J["Index_type"]=="FULLTEXT"?"FULLTEXT":($J["Non_unique"]?(preg_match('~^(SPATIAL|VECTOR)$~',$J["Index_type"])?$J["Index_type"]:"INDEX"):"UNIQUE")));$I[$C]["columns"][]=$J["Column_name"];$I[$C]["lengths"][]=($J["Index_type"]=="SPATIAL"?null:$J["Sub_part"]);$I[$C]["descs"][]=null;$I[$C]["algorithm"]=$J["Index_type"];}return$I;}function
foreign_keys($R){static$si='(?:`(?:[^`]|``)+`|"(?:[^"]|"")+")';$I=array();$Tb=get_val("SHOW CREATE TABLE ".table($R),1);if($Tb){preg_match_all("~CONSTRAINT ($si) FOREIGN KEY ?\\(((?:$si,? ?)+)\\) REFERENCES ($si)(?:\\.($si))? \\(((?:$si,? ?)+)\\)(?: ON DELETE (".driver()->onActions."))?(?: ON UPDATE (".driver()->onActions."))?~",$Tb,$gg,PREG_SET_ORDER);foreach($gg
as$A){preg_match_all("~$si~",$A[2],$fk);preg_match_all("~$si~",$A[5],$Sk);$I[idf_unescape($A[1])]=array("db"=>idf_unescape($A[4]!=""?$A[3]:$A[4]),"table"=>idf_unescape($A[4]!=""?$A[4]:$A[3]),"source"=>array_map('Adminer\idf_unescape',$fk[0]),"target"=>array_map('Adminer\idf_unescape',$Sk[0]),"on_delete"=>($A[6]?:"RESTRICT"),"on_update"=>($A[7]?:"RESTRICT"),);}}return$I;}function
view($C){return
array("select"=>preg_replace('~^(?:[^`]|`[^`]*`)*\s+AS\s+~isU','',get_val("SHOW CREATE VIEW ".table($C),1)));}function
collations(){$I=array();foreach(get_rows("SHOW COLLATION")as$J){if($J["Default"])$I[$J["Charset"]][-1]=$J["Collation"];else$I[$J["Charset"]][]=$J["Collation"];}ksort($I);foreach($I
as$x=>$X)sort($I[$x]);return$I;}function
information_schema($j,$L=""){return($j=="information_schema")||(min_version(5.5)&&$j=="performance_schema");}function
error(){return
h(preg_replace('~^You have an error.*syntax to use~U',"Syntax error",connection()->error));}function
create_database($j,$ub){return
queries("CREATE DATABASE ".idf_escape($j).($ub?" COLLATE ".q($ub):""));}function
drop_databases(array$i){$I=apply_queries("DROP DATABASE",$i,'Adminer\idf_escape');restart_session();set_session("dbs",null);return$I;}function
rename_database($C,$ub){$I=false;if(create_database($C,$ub)){$T=array();$jm=array();foreach(tables_list()as$R=>$U){if($U=='VIEW')$jm[]=$R;else$T[]=$R;}$I=(!$T&&!$jm)||move_tables($T,$jm,$C);drop_databases($I?array(DB):array());}return$I;}function
auto_increment(){$Ha=" PRIMARY KEY";if($_GET["create"]!=""&&$_POST["auto_increment_col"]){foreach(indexes($_GET["create"])as$v){if(in_array($_POST["fields"][$_POST["auto_increment_col"]]["orig"],$v["columns"],true)){$Ha="";break;}if($v["type"]=="PRIMARY")$Ha=" UNIQUE";}}return" AUTO_INCREMENT$Ha";}function
alter_table($R,$C,array$n,array$Pd,$_b,$Vc,$ub,$Ga,$ki){$b=array();foreach($n
as$m){if($m[1]){$k=$m[1][3];if(preg_match('~ GENERATED~',$k)){$m[1][3]=(connection()->flavor=='maria'?"":$m[1][2]);$m[1][2]=$k;}$b[]=($R!=""?($m[0]!=""?"CHANGE ".idf_escape($m[0]):"ADD"):" ")." ".implode($m[1]).($R!=""?$m[2]:"");}else$b[]="DROP ".idf_escape($m[0]);}$b=array_merge($b,$Pd);$P=($_b!==null?" COMMENT=".q($_b):"").($Vc?" ENGINE=".q($Vc):"").($ub?" COLLATE ".q($ub):"").($Ga!=""?" AUTO_INCREMENT=$Ga":"");if($ki){$li=array();if($ki["partition_by"]=='RANGE'||$ki["partition_by"]=='LIST'){foreach($ki["partition_names"]as$x=>$X){$Y=$ki["partition_values"][$x];$li[]="\n  PARTITION ".idf_escape($X)." VALUES ".($ki["partition_by"]=='RANGE'?"LESS THAN":"IN").($Y!=""?" ($Y)":" MAXVALUE");}}$P
.="\nPARTITION BY $ki[partition_by]($ki[partition])";if($li)$P
.=" (".implode(",",$li)."\n)";elseif($ki["partitions"])$P
.=" PARTITIONS ".(+$ki["partitions"]);}elseif($ki===null)$P
.="\nREMOVE PARTITIONING";if($R=="")return
queries("CREATE TABLE ".table($C)." (\n".implode(",\n",$b)."\n)$P");if($R!=$C)$b[]="RENAME TO ".table($C);if($P)$b[]=ltrim($P);return($b?queries("ALTER TABLE ".table($R)."\n".implode(",\n",$b)):true);}function
alter_indexes($R,$b){$db=array();foreach($b
as$X)$db[]=($X[2]=="DROP"?"\nDROP INDEX ".idf_escape($X[1]):"\nADD $X[0] ".($X[0]=="PRIMARY"?"KEY ":"").($X[1]!=""?idf_escape($X[1])." ":"")."(".implode(", ",$X[2]).")");return
queries("ALTER TABLE ".table($R).implode(",",$db));}function
truncate_tables(array$T){return
apply_queries("TRUNCATE TABLE",$T);}function
drop_views(array$jm){return
queries("DROP VIEW ".implode(", ",array_map('Adminer\table',$jm)));}function
drop_tables(array$T){return
queries("DROP TABLE ".implode(", ",array_map('Adminer\table',$T)));}function
move_tables(array$T,array$jm,$Sk){$kj=array();foreach($T
as$R)$kj[]=table($R)." TO ".idf_escape($Sk).".".table($R);if(!$kj||queries("RENAME TABLE ".implode(", ",$kj))){$oc=array();foreach($jm
as$R)$oc[table($R)]=view($R);connection()->select_db($Sk);$j=idf_escape(DB);foreach($oc
as$C=>$im){if(!queries("CREATE VIEW $C AS ".str_replace(" $j."," ",$im["select"]))||!queries("DROP VIEW $j.$C"))return
false;}return
true;}return
false;}function
copy_tables(array$T,array$jm,$Sk){queries("SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO'");foreach($T
as$R){$C=($Sk==DB?table("copy_$R"):idf_escape($Sk).".".table($R));if(($_POST["overwrite"]&&!queries("\nDROP TABLE IF EXISTS $C"))||!queries("CREATE TABLE $C LIKE ".table($R))||!queries("INSERT INTO $C SELECT * FROM ".table($R)))return
false;foreach(get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($R,"%_\\")))as$J){$tl=$J["Trigger"];list($ed,$lh)=trigger_event($J);if(!queries("CREATE TRIGGER ".($Sk==DB?idf_escape("copy_$tl"):idf_escape($Sk).".".idf_escape($tl))." $J[Timing] $ed".($lh!=""?" $lh":"")." ON $C FOR EACH ROW\n$J[Statement];"))return
false;}}foreach($jm
as$R){$C=($Sk==DB?table("copy_$R"):idf_escape($Sk).".".table($R));$im=view($R);if(($_POST["overwrite"]&&!queries("DROP VIEW IF EXISTS $C"))||!queries("CREATE VIEW $C AS $im[select]"))return
false;}return
true;}function
trigger_event(array$J){$gd=explode(",",$J["Event"]);$I=array();foreach(array("DELETE","INSERT","UPDATE")as$ed){if(in_array($ed,$gd))$I[]=$ed;}$I=implode(" OR ",$I);if(in_array("UPDATE",$gd)&&min_version('','12.0.1')&&preg_match('~\s(?:BEFORE|AFTER)\s+(.+?)\s+ON\s~is',get_val("SHOW CREATE TRIGGER ".idf_escape($J["Trigger"]),2),$A)&&preg_match('~\bOF\s+(.+)~is',$A[1],$lh))return
array("$I OF",$lh[1]);return
array($I,"");}function
trigger($C,$R){if($C=="")return
array();$K=get_rows("SHOW TRIGGERS WHERE `Trigger` = ".q($C));$I=reset($K);if($I)list($I["Event"],$I["Of"])=trigger_event($I);return$I;}function
triggers($R){$I=array();foreach(get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($R,"%_\\")))as$J){list($ed)=trigger_event($J);$I[$J["Trigger"]]=array($J["Timing"],$ed);}return$I;}function
trigger_options(){return
array("Timing"=>array("BEFORE","AFTER"),"Event"=>(min_version('','12.0.1')?array("INSERT","UPDATE","UPDATE OF","DELETE","INSERT OR UPDATE","INSERT OR UPDATE OF","DELETE OR INSERT","DELETE OR UPDATE","DELETE OR UPDATE OF","DELETE OR INSERT OR UPDATE","DELETE OR INSERT OR UPDATE OF",):array("INSERT","UPDATE","DELETE")),"Type"=>array("FOR EACH ROW"),);}function
routine($C,$U){$K=get_rows("SELECT PARAMETER_NAME, DTD_IDENTIFIER, PARAMETER_MODE, COLLATION_NAME
FROM information_schema.PARAMETERS
WHERE SPECIFIC_SCHEMA = DATABASE() AND ROUTINE_TYPE = '$U' AND SPECIFIC_NAME = ".q($C)."
ORDER BY ORDINAL_POSITION");$n=array();foreach($K
as$J){$Yd=$J["DTD_IDENTIFIER"];list($Al,$y,$Jl)=parse_type($Yd);$n[]=array("field"=>$J["PARAMETER_NAME"],"type"=>$Al,"length"=>$y,"unsigned"=>$Jl,"null"=>true,"full_type"=>$Yd,"inout"=>($U=="FUNCTION"?"":$J["PARAMETER_MODE"]),"collation"=>$J["COLLATION_NAME"],);}$I=connection()->query("SELECT
	ROUTINE_COMMENT comment,
	CONCAT(IF(IS_DETERMINISTIC = 'YES', 'DETERMINISTIC\\n', ''), IF(SQL_DATA_ACCESS != 'CONTAINS SQL', CONCAT(SQL_DATA_ACCESS, '\\n'), ''), ROUTINE_DEFINITION) definition,
	'SQL' language
FROM information_schema.ROUTINES
WHERE ROUTINE_SCHEMA = DATABASE() AND ROUTINE_TYPE = '$U' AND ROUTINE_NAME = ".q($C))->fetch_assoc();if($n&&$n[0]['field']=='')$I['returns']=array_shift($n);$I['fields']=$n;return$I;}function
routines(){return
get_rows("SELECT SPECIFIC_NAME, ROUTINE_NAME, ROUTINE_TYPE, DTD_IDENTIFIER FROM information_schema.ROUTINES WHERE ROUTINE_SCHEMA = DATABASE()");}function
routine_languages(){return
array();}function
routine_id($C,array$J){return
idf_escape($C);}function
last_id($H){return
get_val("SELECT LAST_INSERT_ID()");}function
explain(Db$f,$G){return$f->query("EXPLAIN ".(min_version(5.7)?"":"PARTITIONS ").$G);}function
found_rows(array$S,array$Z){return($Z||$S["Engine"]!="InnoDB"?null:$S["Rows"]);}function
create_sql($R,$Ga,$vk){$I=get_val("SHOW CREATE TABLE ".table($R),1);if(!$Ga)$I=preg_replace('~(\n\)[^\n]*?) AUTO_INCREMENT=\d+~','\1',$I);return$I;}function
truncate_sql($R){return"TRUNCATE ".table($R);}function
use_sql($fc,$vk=""){$C=idf_escape($fc);$I="";if(preg_match('~CREATE~',$vk)&&($h=get_val("SHOW CREATE DATABASE $C",1))){set_utf8mb4($h);if($vk=="DROP+CREATE")$I="DROP DATABASE IF EXISTS $C;\n";$I
.="$h;\n";}return$I."USE $C";}function
trigger_sql($R){$I="";foreach(get_rows("SHOW TRIGGERS LIKE ".q(addcslashes($R,"%_\\")),null,"-- ")as$J){list($J["Event"],$J["Of"])=trigger_event($J);$I
.="\n".create_trigger(" ON ".table($J["Table"]),$J+array("Type"=>"FOR EACH ROW")).";\n";}return$I;}function
show_variables(){return
get_rows("SHOW VARIABLES");}function
show_status(){return
get_rows("SHOW STATUS");}function
process_list(){return
get_rows("SHOW FULL PROCESSLIST");}function
convert_field(array$m){return
driver()->convertColumn(idf_escape($m["field"]),$m);}function
unconvert_field(array$m,$I){if(preg_match("~binary~",$m["type"]))$I="UNHEX($I)";if($m["type"]=="bit")$I="CONVERT(b$I, UNSIGNED)";if($m["type"]=="vector")$I=(connection()->flavor=='maria'?"VEC_FromText":"STRING_TO_VECTOR")."($I)";if(preg_match("~geom|point|linestring|polygon~",$m["type"])){$Gi=(min_version(8)?"ST_":"");$I=$Gi."GeomFromText($I, $Gi"."SRID($m[field]))";}return$I;}function
support($wd){return
preg_match('~^(comment|columns|copy|database|drop_col|dump|event|indexes|kill|privileges|move_col|procedure|processlist|routine|sql|status|table|trigger|variables|view'.(min_version(8)?'|descidx':'').(min_version('8.0.16','10.2.1')?'|check':'').(min_version(8,99)?'|fast_status':'').')$~',$wd);}function
kill_process($t){return
queries("KILL ".number($t));}function
connection_id(){return"SELECT CONNECTION_ID()";}function
max_connections(){return
get_val("SELECT @@max_connections");}function
types($rd=false){return
array();}function
type_values($t){return"";}function
type_definition($t){return
array("kind"=>"","definition"=>"");}function
schemas(){return
array();}function
get_schema(){return"";}function
set_schema($L,$g=null){return
true;}}define('Adminer\JUSH',Driver::$jush);define('Adminer\SERVER',"".$_GET[DRIVER]);define('Adminer\DB',"$_GET[db]");define('Adminer\ME',preg_replace('~\?.*~','',relative_uri()).'?'.(sid()?SID.'&':'').($_GET["ext"]?"ext=".url_escape($_GET["ext"]).'&':'').(isset($_GET[DRIVER])?DRIVER."=".url_escape(SERVER).'&':'').(isset($_GET["username"])?"username=".url_escape($_GET["username"]).'&':'').(isset($_GET["db"])?'db='.url_escape(DB).'&'.(isset($_GET["ns"])?"ns=".url_escape($_GET["ns"])."&":""):''));function
page_header($el,$l="",$Wa=array(),$fl=""){page_headers();if(is_ajax()&&$l){page_messages($l);exit;}if(!ob_get_level())ob_start('ob_gzhandler',4096);$gl=$el.($fl!=""?": $fl":"");$hl=strip_tags($gl.(SERVER!=""&&SERVER!="localhost"?h(" - ".SERVER):"")." - ".adminer()->name());echo'<!DOCTYPE html>
<html lang=\'',LANG,'\' dir=\'',lang(94),'\' class=\'',lang(94),' nojs\'>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="robots" content="noindex">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>',$hl,'</title>
<link rel="stylesheet" href="',h(preg_replace("~\\?.*~","",ME)."?file=default.css&version=6.0.1"),'">
';$Xb=adminer()->css();if(is_int(key($Xb)))$Xb=array_fill_keys($Xb,'light');$re=in_array('light',$Xb)||in_array('',$Xb);$pe=in_array('dark',$Xb)||in_array('',$Xb);$bc=($re?($pe?null:false):($pe?:null));$vg=" media='(prefers-color-scheme: dark)'";if($bc!==false)echo"<link rel='stylesheet'".($bc?"":$vg)." href='".h(preg_replace("~\\?.*~","",ME)."?file=dark.css&version=6.0.1")."'>\n";echo"<meta name='color-scheme' content='".($bc===null?"light dark":($bc?"dark":"light"))."'>\n",script_src(preg_replace("~\\?.*~","",ME)."?file=functions.js&version=6.0.1");if(adminer()->head($bc))echo"<link rel='icon' href='data:image/gif;base64,"."R0lGODlhEAAQAJEAAAQCBPz+/PwCBAROZCH5BAEAAAAALAAAAAAQABAAAAI2hI+pGO1rmghihiUdvUBnZ3XBQA7f05mOak1RWXrNq5nQWHMKvuoJ37BhVEEfYxQzHjWQ5qIAADs='>\n","<link rel='apple-touch-icon' href='".h(preg_replace("~\\?.*~","",ME)."?file=logo.png&version=6.0.1")."'>\n";foreach($Xb
as$Pl=>$Ig){$c=($Ig=='dark'&&!$bc?$vg:($Ig=='light'&&$pe?" media='(prefers-color-scheme: light)'":""));echo"<link rel='stylesheet'$c href='".h($Pl)."'>\n";}echo"\n<body class='";adminer()->bodyClass();echo"'>\n",script((isset($_COOKIE["adminer_version"])||!adminer()->verifyVersion()?"":"onload = partial(verifyVersion, '".VERSION."');\n")."
const offlineMessage = '".js_escape(lang(95))."';
const thousandsSeparator = '".js_escape(lang(5))."';
const urlSeparators = '".js_escape(ini_get("arg_separator.input"))."';"),"<div id='help' class='jush-".JUSH." jsonly hidden'".on('mouseover','helpKeep').on('mouseout','helpMouseout')."></div>\n","<div id='content'>\n","<span id='menuopen' class='jsonly'".on('click','menuToggle')."><button title='".lang(96)."' class='icon icon-move' aria-expanded='false'></button></span>\n";if($Wa!==null){$_=substr(preg_replace('~\b(username|db|ns)=[^&]*&~','',ME),0,-1);echo'<p id="breadcrumb"><a href="'.h($_?:".").'">'.get_driver(DRIVER).'</a> » ';$_=substr(preg_replace('~\b(db|ns)=[^&]*&~','',ME),0,-1);$N=adminer()->serverName(SERVER);$N=($N!=""?$N:lang(38));if($Wa===false)echo"$N\n";else{echo"<a href='".h($_.(DB!=""&&support("single_db")?"&db=":""))."' accesskey='1' title='Alt+Shift+1'>$N</a> » ";if($_GET["ns"]!=""||(DB!=""&&is_array($Wa)))echo'<a href="'.h($_."&db=".url_escape(DB).(support("scheme")?"&ns=":"").(support("single_table")?"&select=":"")).'">'.h(DB).'</a> » ';if(is_array($Wa)){if($_GET["ns"]!="")echo'<a href="'.h(substr(ME,0,-1)).'">'.h($_GET["ns"]).'</a> » ';foreach($Wa
as$x=>$X){$qc=(is_array($X)?$X[1]:h($X));if($qc!="")echo"<a href='".h(ME."$x=").url_escape(is_array($X)?$X[0]:$X)."'>$qc</a> » ";}}echo"$el\n";}}echo"<h2>$gl</h2>\n","<div id='ajaxstatus' role='status' class='jsonly'></div>\n";restart_session();page_messages($l);$i=&get_session("dbs");if(DB!=""&&$i&&!in_array(DB,$i,true))$i=null;stop_session();define('Adminer\PAGE_HEADER',1);ob_flush();flush();}function
page_headers(){header("Content-Type: text/html; charset=utf-8");header("Cache-Control: no-cache");header("X-Frame-Options: deny");header("X-XSS-Protection: 0");header("X-Content-Type-Options: nosniff");header("Referrer-Policy: origin-when-cross-origin");foreach(adminer()->csp(csp())as$Wb){$we=array();foreach($Wb
as$x=>$X)$we[]="$x $X";header("Content-Security-Policy: ".implode("; ",$we));}adminer()->headers();}function
csp(){return
array(array("script-src"=>"'self' 'unsafe-inline' 'nonce-".get_nonce()."' 'strict-dynamic'","connect-src"=>"'self' https://www.adminer.org","frame-src"=>"https://www.adminer.org","object-src"=>"'none'","base-uri"=>"'none'","form-action"=>"'self'",),);}function
design_checksums(){$Vl=array();foreach(array_keys(adminer()->css())as$Pl)$Vl[preg_replace('~\?.*~','',$Pl)]=true;$I=array();foreach(array("adminer.css","adminer-dark.css")as$o){if($Vl[$o]&&file_exists($o)){preg_match('~^/\* Adminer design ([-\w]+) \*/~',file_get_contents($o),$A);$I[$o]=array((string)$A[1],Plugins::checksum($o));}}return$I;}function
official_design_checksums(){return
array('adminer-border/adminer-dark.css'=>'b2527e3','adminer-border/adminer.css'=>'430977ad','adminer-dark/adminer-dark.css'=>'a26bcd7b','brade/adminer.css'=>'be4161f0','bueltge/adminer.css'=>'1a8f00b4','dracula/adminer-dark.css'=>'cfaf61dd','esterka/adminer.css'=>'1f805f36','flat/adminer.css'=>'49a61af9','galkaev/adminer-dark.css'=>'16c46f94','haeckel/adminer.css'=>'147a3565','hever/adminer.css'=>'1f626deb','konya/adminer.css'=>'2b409696','lavender-light/adminer.css'=>'bf03f5d7','lucas-sandery/adminer.css'=>'6596353','mancave/adminer-dark.css'=>'e1ac813d','mvt/adminer.css'=>'ebd3afdc','nette/adminer.css'=>'5ab360e7','ng9/adminer.css'=>'488583cf','nicu/adminer.css'=>'ecb9bd1e','pappu687/adminer.css'=>'b58d128c','paranoiq/adminer.css'=>'64d27e5','pepa-linha/adminer.css'=>'baf25f0','pokorny/adminer.css'=>'ee9eea6d','price/adminer.css'=>'81be9a85','rmsoft/adminer.css'=>'6cd4a237','rmsoft_blue-dark/adminer.css'=>'32102a8','rmsoft_blue/adminer.css'=>'7d8d5b18','win98/adminer.css'=>'e82d63c3',);}function
version_iframe(){return(isset($_COOKIE["adminer_version"])||!adminer()->verifyVersion()?"":"<noscript><iframe sandbox src='https://www.adminer.org/version/?current=".VERSION."&amp;noscript=1'></iframe></noscript>");}function
get_nonce(){static$eh;if(!$eh)$eh=base64_encode(rand_string());return$eh;}function
page_messages($l){$Ol=preg_replace('~^[^?]*~','',$_SERVER["REQUEST_URI"]);$Ag=idx($_SESSION["messages"],$Ol);if($Ag){echo"<div class='message'>".implode("</div>\n<div class='message'>",$Ag)."</div>".script("messagesPrint();");unset($_SESSION["messages"][$Ol]);}if($l)echo"<div class='error'>$l</div>\n";if(adminer()->error)echo"<div class='error'>".adminer()->error."</div>\n";}function
page_footer($Hg=""){echo"</div>\n\n<div id='foot' class='foot'>\n<div id='menu'>\n";adminer()->navigation($Hg);echo"</div>\n";if($Hg!="auth")echo'<form action="" method="post">
<p class="logout">
<span title="',lang(40),'">',h($_GET["username"])."\n",'</span>
<input type=\'submit\' name=\'logout\' value=\'',lang(97),'\' id=\'logout\'>
',input_token(),'</form>
';echo"</div>\n\n",script("setupSubmitHighlight(document);");}function
int32($Pg){while($Pg>=2147483648)$Pg-=4294967296;while($Pg<=-2147483649)$Pg+=4294967296;return(int)$Pg;}function
long2str(array$W,$lm){$zj='';foreach($W
as$X)$zj
.=pack('V',$X);if($lm)return
substr($zj,0,end($W));return$zj;}function
str2long($zj,$lm){$W=array_values(unpack('V*',str_pad($zj,4*ceil(strlen($zj)/4),"\0")));if($lm)$W[]=strlen($zj);return$W;}function
xxtea_mx($wm,$vm,$yk,$zf){return
int32((($wm>>5&0x7FFFFFF)^$vm<<2)+(($vm>>3&0x1FFFFFFF)^$wm<<4))^int32(($yk^$vm)+($zf^$wm));}function
encrypt_string($tk,$x){if($tk=="")return"";$x=array_values(unpack("V*",pack("H*",md5($x))));$W=str2long($tk,true);$Pg=count($W)-1;$wm=$W[$Pg];$vm=$W[0];$Ti=floor(6+52/($Pg+1));$yk=0;while($Ti-->0){$yk=int32($yk+0x9E3779B9);$Nc=$yk>>2&3;for($Yh=0;$Yh<$Pg;$Yh++){$vm=$W[$Yh+1];$Og=xxtea_mx($wm,$vm,$yk,$x[$Yh&3^$Nc]);$wm=int32($W[$Yh]+$Og);$W[$Yh]=$wm;}$vm=$W[0];$Og=xxtea_mx($wm,$vm,$yk,$x[$Yh&3^$Nc]);$wm=int32($W[$Pg]+$Og);$W[$Pg]=$wm;}return
long2str($W,false);}function
decrypt_string($tk,$x){if($tk=="")return"";if(!$x)return
false;$x=array_values(unpack("V*",pack("H*",md5($x))));$W=str2long($tk,false);$Pg=count($W)-1;$wm=$W[$Pg];$vm=$W[0];$Ti=floor(6+52/($Pg+1));$yk=int32($Ti*0x9E3779B9);while($yk){$Nc=$yk>>2&3;for($Yh=$Pg;$Yh>0;$Yh--){$wm=$W[$Yh-1];$Og=xxtea_mx($wm,$vm,$yk,$x[$Yh&3^$Nc]);$vm=int32($W[$Yh]-$Og);$W[$Yh]=$vm;}$wm=$W[$Pg];$Og=xxtea_mx($wm,$vm,$yk,$x[$Yh&3^$Nc]);$vm=int32($W[0]-$Og);$W[0]=$vm;$yk=int32($yk-0x9E3779B9);}return
long2str($W,true);}$ui=array();if($_COOKIE["adminer_permanent"]){foreach(explode(" ",$_COOKIE["adminer_permanent"])as$X){list($x)=explode(":",$X);$ui[$x]=$X;}}function
add_invalid_login(){$Oa=get_temp_dir()."/adminer-invalid";foreach(glob("$Oa*")?:array($Oa)as$o){$q=file_open_lock($o);if($q)break;}if(!$q)$q=file_open_lock("$Oa-".rand_string());if(!$q)return;$nf=json_decode(stream_get_contents($q),true);$bl=time();if($nf){foreach($nf
as$of=>$X){if($X[0]<$bl)unset($nf[$of]);}}$lf=&$nf[adminer()->bruteForceKey()];if(!$lf)$lf=array($bl+30*60,0);$lf[1]++;file_write_unlock($q,json_encode($nf));}function
check_invalid_login(array&$ui){$nf=array();foreach(glob(get_temp_dir()."/adminer-invalid*")as$o){$q=file_open_lock($o);if($q){$nf=json_decode(stream_get_contents($q),true);file_unlock($q);break;}}$x=adminer()->bruteForceKey();$lf=idx($nf,$x,array());$dh=($lf[1]>29?$lf[0]-time():0);if($dh>0){$l=lang(98,ceil($dh/60));if($_SERVER["HTTP_X_FORWARDED_FOR"]!=""&&$x==$_SERVER["REMOTE_ADDR"])$l
.='<br>'.lang(99,'<b>login-reverse-proxy</b>'," href='https://www.adminer.org/plugins/?version=".VERSION."'".target_blank());auth_error($l,$ui,false);}}function
password_required(){static$I;if($I===null){$I=(bool)get_session("password_required");if(!$I){$Vb=adminer()->credentials();$I=!is_object(Driver::connect($Vb[0],$Vb[1],""));if($I)set_session("password_required",true);}}return$I;}function
require_password_link($E){$Kg="<a href='https://www.adminer.org/password/'".target_blank().">".lang(100)."</a>";if(!function_exists('password_hash'))return" $Kg";$xi=($E!==null?$E:base64_encode(substr(pack("H*",rand_string()),0,12)));$ve=password_hash($xi,PASSWORD_DEFAULT);$o="adminer-plugins.php";$ld=file_exists("adminer-plugins.php");if($ld)$jf=($E!==null?lang(101,"<b>$o</b>"):lang(102,"<b>$o</b>","<b>$xi</b>"));else{$o="<button name='password_less' value='".h($ve)."' class='link'>$o</button>";$jf=($E!==null?lang(103,$o):lang(104,$o,"<b>$xi</b>"));}$Uf="\t<a>new</a> Adminer\\Password(<span class='jush-apo'>'".h($ve)."'</span>),";$I="<p>$jf
<pre><code class='jush'>".($ld?$Uf:"&lt;?php\n<a>return</a> <a>array</a>(\n$Uf\n);")."</code></pre>
<p>$Kg
";return" <a href='#password-less' class='toggle'>".lang(105)."</a>
<div id='password-less' class='hidden'>".($ld?$I:"<form action='' method='post'>\n".$I.input_token()."</form>")."</div>";}if(preg_match('~^[-\w$./]+$~',$_POST["password_less"])&&verify_token()){header("Content-Type: application/octet-stream");header("Content-Disposition: attachment; filename=adminer-plugins.php");echo"<?php\nreturn array(\n\tnew Adminer\\Password('$_POST[password_less]'),\n);\n";exit;}$Fa=$_POST["auth"];if($Fa&&verify_token()){session_regenerate_id();$gm=$Fa["driver"];$N=$Fa["server"];$V=$Fa["username"];$E=(string)$Fa["password"];$j=$Fa["db"];set_password($gm,$N,$V,$E);$_SESSION["db"][$gm][$N][$V][$j]=true;if($Fa["permanent"]){$x=implode("-",array_map('base64_encode',array($gm,$N,$V,$j)));$Ni=adminer()->permanentLogin(true);$ui[$x]="$x:".base64_encode($Ni?encrypt_string($E,$Ni):"");cookie("adminer_permanent",implode(" ",$ui));}if(!array_diff(array_keys($_POST),array("auth","token"))||$gm!=DRIVER||$N!=SERVER||$V!==$_GET["username"]||$j!=DB)redirect(auth_url($gm,$N,$V,$j));}elseif($_POST["logout"]&&(!$_SESSION["token"]||verify_token())){foreach(array("pwds","db","dbs","queries")as$x)set_session($x,null);unset_permanent($ui);redirect(substr(preg_replace('~\b(username|db|ns)=[^&]*&~','',ME),0,-1),lang(106).' '.lang(107));}elseif($ui&&!$_SESSION["pwds"]){session_regenerate_id();$Ni=adminer()->permanentLogin();foreach($ui
as$x=>$X){list(,$ob)=explode(":",$X);list($gm,$N,$V,$j)=array_map('base64_decode',explode("-",$x));set_password($gm,$N,$V,decrypt_string(base64_decode($ob),$Ni));$_SESSION["db"][$gm][$N][$V][$j]=true;}}function
unset_permanent(array&$ui){foreach($ui
as$x=>$X){list($gm,$N,$V,$j)=array_map('base64_decode',explode("-",$x));if($gm==DRIVER&&$N==SERVER&&$V==$_GET["username"]&&$j==DB)unset($ui[$x]);}cookie("adminer_permanent",implode(" ",$ui));}function
auth_error($l,array&$ui,$mf=true){$Uj=session_name();if(isset($_GET["username"])){header("HTTP/1.1 403 Forbidden");if(($_COOKIE[$Uj]||$_GET[$Uj])&&!$_SESSION["token"])$l=lang(108);elseif($mf&&($E=get_password())!==null){restart_session();add_invalid_login();if($E===false)$l
.=($l?'<br>':'').lang(109,target_blank(),'<code>permanentLogin()</code>');set_password(DRIVER,SERVER,$_GET["username"],null);unset_permanent($ui);}}if(!$_COOKIE[$Uj]&&$_GET[$Uj]&&ini_bool("session.use_only_cookies"))$l=lang(110);$ci=session_get_cookie_params();cookie("adminer_key",($_COOKIE["adminer_key"]?:rand_string()),$ci["lifetime"]);if(!$_SESSION["token"])$_SESSION["token"]=rand(1,1e6);page_header(lang(43),$l,null);echo"<form action='' method='post'>\n","<div>";if(hidden_fields($_POST,array("auth")))echo"<p class='message'>".lang(111)."\n";echo
input_token(),"</div>\n";adminer()->loginForm();echo"</form>\n";page_footer("auth");exit;}if(isset($_GET["username"])&&!class_exists('Adminer\Db')){unset($_SESSION["pwds"][DRIVER]);unset_permanent($ui);page_header(lang(112),lang(113,implode(", ",Driver::$extensions)),false);page_footer("auth");exit;}$f='';if(isset($_GET["username"])&&is_string(get_password())){check_invalid_login($ui);$Vb=adminer()->credentials();$f=Driver::connect($Vb[0],$Vb[1],$Vb[2]);if(is_object($f)){Db::$instance=$f;Driver::$instance=new
Driver($f);if($f->flavor)save_settings(array("vendor-".DRIVER."-".SERVER=>get_driver(DRIVER)));}}$bg=null;if(!is_object($f)||($bg=adminer()->login($_GET["username"],get_password()))!==true){$l=(is_string($f)?nl_br(h($f)):(is_string($bg)?$bg:lang(114))).(preg_match('~^ | $~',get_password())?'<br>'.lang(115):'');auth_error($l,$ui);}if($_POST["logout"]&&$_SESSION["token"]&&!verify_token()){page_header(lang(97),lang(116));page_footer("db");exit;}if(!$_SESSION["token"])$_SESSION["token"]=rand(1,1e6);stop_session(true);if($Fa&&$_POST["token"])$_POST["token"]=get_token();$l='';if($_POST){if(!verify_token()){header("HTTP/1.1 403 Forbidden");$l=lang(116).' '.lang(117);}}elseif($_SERVER["REQUEST_METHOD"]=="POST"){header("HTTP/1.1 413 Content Too Large");$l=lang(118,"<b>post_max_size</b>");if(isset($_GET["sql"]))$l
.=' '.lang(119);}function
print_select_result($H,$g=null,array$Mh=array(),&$z=0){$Wf=array();$w=array();$e=array();$Ua=array();$Bl=array();$I=array();for($s=0;(!$z||$s<$z)&&($J=$H->fetch_row());$s++){if(!$s){echo"<div class='scrollable'>\n","<table class='nowrap odds'>\n","<thead><tr>";for($wf=0;$wf<count($J);$wf++){$m=$H->fetch_field();$C=$m->name;$Lh=(isset($m->orgtable)?$m->orgtable:"");$Kh=(isset($m->orgname)?$m->orgname:$C);if($Mh&&JUSH=="sql")$Wf[$wf]=($C=="table"?"table=":($C=="possible_keys"?"indexes=":null));elseif($Lh!=""){if(isset($m->table))$I[$m->table]=$Lh;if(!isset($w[$Lh])){$w[$Lh]=array();foreach(indexes($Lh,$g)as$v){if($v["type"]=="PRIMARY"){$w[$Lh]=array_flip($v["columns"]);break;}}$e[$Lh]=$w[$Lh];}if(isset($e[$Lh][$Kh])){unset($e[$Lh][$Kh]);$w[$Lh][$Kh]=$wf;$Wf[$wf]=$Lh;}}if($m->charsetnr==63)$Ua[$wf]=true;$Bl[$wf]=$m->type;echo"<th title='".h(trim(($Lh!=""?"$Lh.$Kh":($m->name!=$Kh?$Kh:""))." ".driver()->typeName($m)))."'>".h($C).($Mh?doc_link(array('sql'=>"explain-output.html#explain_".strtolower($C),'mariadb'=>"explain/#the-columns-in-explain-select",)):"");}echo"<tbody>\n";}echo"<tr>";foreach($J
as$x=>$X){$_="";if(isset($Wf[$x])&&!$e[$Wf[$x]]){if($Mh&&JUSH=="sql"){$R=$J[array_search("table=",$Wf)];$_=ME.$Wf[$x].url_escape($Mh[$R]!=""?$Mh[$R]:$R);}else{$_=ME."edit=".url_escape($Wf[$x]);foreach($w[$Wf[$x]]as$sb=>$wf){if($J[$wf]===null){$_="";break;}$_
.="&where[".url_escape(bracket_escape($sb))."]=".url_escape($J[$wf]);}}}$m=array('type'=>($Ua[$x]?'blob':($Bl[$x]==254?'char':'')),);$X=select_value($X,$_,$m,null);echo"<td".($Bl[$x]<=9||$Bl[$x]==246?" class='number'":"").">$X";}}$z=$s;echo($s?"</table>\n</div>":"<p class='message'>".lang(15))."\n";return$I;}function
textarea($C,$Y,$K=10,$wb=80){echo"<textarea name='".h($C)."' rows='$K' cols='$wb' class='sqlarea jush-".JUSH."' spellcheck='false' wrap='off'>";if(is_array($Y)){foreach($Y
as$X)echo
h($X[0])."\n\n\n";}else
echo
h($Y);echo"</textarea>";}function
select_input($c,array$Gh,$Y="",$vi=""){if($Gh&&$Y!=""&&!isset($Gh[$Y]))$Gh=array($Y=>$Y)+$Gh;$Rk=($Gh?"select":"input");return"<$Rk$c".($Gh?"><option value=''>$vi".optionlist($Gh,$Y,true)."</select>":" size='10' value='".h($Y)."' placeholder='$vi'>");}function
json_row($x,$X=null,$dd=true){static$Hd=true;if($Hd)echo"{";if($x!=""){echo($Hd?"":",")."\n\t\"".addcslashes($x,"\r\n\t\"\\/").'": '.($X!==null?($dd?'"'.addcslashes($X,"\r\n\"\\/").'"':$X):'null');$Hd=false;}else{echo"\n}\n";$Hd=true;}}function
flat_collations(){$vb=collations();return(is_array(reset($vb))?call_user_func_array('array_merge',array_values($vb)):$vb);}function
edit_type($x,array$m,array$vb,array$Rd=array(),array$td=array()){$U=(string)$m["type"];echo"<td><select name='".h($x)."[type]' class='type' aria-labelledby='label-type'".on_help_value().">";if($U&&!array_key_exists($U,driver()->types())&&!isset($Rd[$U])&&!in_array($U,$td))$td[]=$U;$uk=driver()->structuredTypes();if($Rd)$uk[lang(120)]=$Rd;echo
optionlist(array_merge($td,$uk),$U),"</select><td>","<input name='".h($x)."[length]' value='".h($m["length"])."' size='3'".(!$m["length"]&&preg_match('~var(char|binary)$~',$U)?" class='required'":"")." aria-labelledby='label-length'>","<td class='options'>",($vb?"<input list='collations' name='".h($x)."[collation]'".option_types($U,'('.text_type().')$')." value='".h($m["collation"])."' placeholder='(".lang(121).")'>":''),(driver()->unsigned?"<select name='".h($x)."[unsigned]'".option_types($U,'^$|'.number_type()).'><option>'.optionlist(driver()->unsigned,$m["unsigned"]).'</select>':''),(isset($m['on_update'])?"<select name='".h($x)."[on_update]'".option_types($U,'timestamp|datetime').'>'.optionlist(array(""=>"(".lang(122).")","CURRENT_TIMESTAMP"),(preg_match('~^CURRENT_TIMESTAMP~i',$m["on_update"])?"CURRENT_TIMESTAMP":$m["on_update"])).'</select>':''),($Rd?"<select name='".h($x)."[on_delete]'".option_types($U,'`')."><option value=''>(".lang(123).")".optionlist(explode("|",driver()->onActions),$m["on_delete"])."</select> ":" ");}function
option_types($U,$Bl){return" data-types='".h($Bl)."'".(preg_match("~$Bl~",$U)?"":" class='hidden'");}function
process_length($y){$Yc=driver()->enumLength;return(preg_match("~^\\s*\\(?\\s*$Yc(?:\\s*,\\s*$Yc)*+\\s*\\)?\\s*\$~",$y)&&preg_match_all("~$Yc~",$y,$gg)?"(".implode(",",$gg[0]).")":preg_replace('~^[0-9].*~','(\0)',preg_replace('~[^-0-9,+()[\]]~','',$y)));}function
process_in($X){$Yc=driver()->enumLength;if(preg_match("~^\\s*\\(?\\s*$Yc(?:\\s*,\\s*$Yc)*+\\s*\\)?\\s*\$~",$X)&&preg_match_all("~$Yc~",$X,$gg))return"(".implode(", ",$gg[0]).")";$I=array();foreach(explode(",",$X)as$vf)$I[]=q(trim($vf));return"(".implode(", ",$I).")";}function
process_type(array$m,$tb="COLLATE"){return" $m[type]".process_length($m["length"]).(preg_match(number_type(),$m["type"])&&in_array($m["unsigned"],driver()->unsigned)?" $m[unsigned]":"").(preg_match('~'.text_type().'~',$m["type"])&&$m["collation"]?" $tb ".(JUSH=="mssql"?$m["collation"]:q($m["collation"])):"");}function
process_field(array$m,array$_l){if($m["on_update"])$m["on_update"]=str_ireplace("current_timestamp()","CURRENT_TIMESTAMP",$m["on_update"]);return
array(idf_escape(trim($m["field"])),process_type($_l),($m["null"]?" NULL":" NOT NULL"),default_value($m),(preg_match('~timestamp|datetime~',$m["type"])&&$m["on_update"]?" ON UPDATE $m[on_update]":""),(support("comment")&&$m["comment"]!=""?" COMMENT ".q($m["comment"]):""),($m["auto_increment"]?auto_increment():null),);}function
default_value(array$m){if($m["default"]===null)return"";$k=str_replace("\r","",$m["default"]);$be=$m["generated"];return(in_array($be,driver()->generated)?(JUSH=="mssql"?" AS ($k)".($be=="VIRTUAL"?"":" $be"):" GENERATED ALWAYS AS ($k) $be"):(preg_match('~^GENERATED ~i',$k)?" $k":" DEFAULT ".(preg_match('~char|binary|text|json|enum|set|String~',$m["type"])||preg_match('~^(?![a-z])~i',$k)?(JUSH=="sql"&&preg_match('~text|json~',$m["type"])?"(".q($k).")":q($k)):str_ireplace("current_timestamp()","CURRENT_TIMESTAMP",(JUSH=="sqlite"?"($k)":$k)))));}function
edit_fields(array$n,array$vb,$U="TABLE",array$Rd=array()){$n=array_values($n);$lc=(($_POST?$_POST["defaults"]:get_setting("defaults"))?"":" class='hidden'");$Ab=(($_POST?$_POST["comments"]:get_setting("comments"))?"":" class='hidden'");echo"<thead><tr>\n",($U=="PROCEDURE"?"<td>":""),"<th id='label-name'>".($U=="TABLE"?lang(124):lang(125)),"<td id='label-type'>".lang(56)."<textarea id='enum-edit' rows='4' cols='12' wrap='off' hidden></textarea>".script("qs('#enum-edit').onblur = editingLengthBlur;"),"<td id='label-length'>".lang(126),"<td>".lang(127);if($U=="TABLE")echo"<td id='label-null'>NULL\n","<td><input type='radio' name='auto_increment_col' value=''><abbr id='label-ai' title='".lang(58)."'>AI</abbr>",doc_link(array('sql'=>"example-auto-increment.html",'mariadb'=>"auto_increment/",'sqlite'=>"autoinc.html",'pgsql'=>"datatype-numeric.html#DATATYPE-SERIAL",'mssql'=>"t-sql/statements/create-table-transact-sql-identity-property",)),"<td id='label-default'$lc>".lang(59),(support("comment")?"<td id='label-comment'$Ab>".lang(57):"");$Kf=!support("move_col");echo"<td>".icon("plus","add[".($Kf?count($n):0)."]","+",lang(128),($Kf?on('click','editingAddLastRow'):"")),"<tbody".on('click','editingClick').on('input','editingInput').on('keydown','editingKeydown').">\n";foreach($n
as$s=>$m){$s++;$Nh=$m[($_POST?"orig":"field")];$xc=(isset($_POST["add"][$s-1])||(isset($m["field"])&&!idx($_POST["drop_col"],$s)))&&(support("drop_col")||$Nh=="");echo"<tr".($xc?"":" hidden").">\n",($U=="PROCEDURE"?"<td>".html_select("fields[$s][inout]",explode("|",driver()->inout),$m["inout"]):"")."<th>",(support("move_col")?icon("move","","↕",lang(129))." ":"");if($xc)echo"<input name='fields[$s][field]' value='".h($m["field"])."' data-maxlength='64' autocapitalize='off' aria-labelledby='label-name'".(isset($_POST["add"][$s-1])?" autofocus":"").">";echo
input_hidden("fields[$s][orig]",$Nh);edit_type("fields[$s]",$m,$vb,$Rd);if($U=="TABLE"){echo"<td><label class='block'>".checkbox("fields[$s][null]",1,$m["null"],"","","","label-null")."</label>","<td><label class='block'><input type='radio' name='auto_increment_col' value='$s'".($m["auto_increment"]?" checked":"")." aria-labelledby='label-ai'></label>","<td$lc>".(driver()->generated?html_select("fields[$s][generated]",array_merge(array("","DEFAULT"),driver()->generated),$m["generated"])." ":checkbox("fields[$s][generated]",1,$m["generated"],"","","","label-default"));$c=" name='fields[$s][default]' aria-labelledby='label-default'";$Y=h($m["default"]);echo(preg_match('~\n~',$m["default"])?"<textarea$c rows='2' cols='30' style='vertical-align: bottom;'>\n$Y</textarea>":"<input$c value='$Y'>");if(support("comment")){$c=" name='fields[$s][comment]' data-maxlength='".(min_version(5.5)?1024:255)."' aria-labelledby='label-comment'";echo"<td$Ab>".adminer()->commentInput('COLUMN',$c,$m["comment"]);}}echo"<td>",(support("move_col")?icon("plus","add[$s]","+",lang(128))." ":""),($Nh==""||support("drop_col")?icon("cross","drop_col[$s]","x",lang(130)):"");}}function
process_fields(array&$n){if($_POST["add"]){$n=array_values($n);array_splice($n,key($_POST["add"]),0,array(array()));}return$_POST["add"]||$_POST["drop_col"];}function
drop_create($Hc,$h,$Jc,$Xk,$Lc,$ag,$_g,$yg,$zg,$uh,$Yg){if($_POST["drop"])query_redirect($Hc,$ag,$_g);elseif($uh=="")query_redirect($h,$ag,$zg);elseif(support("transaction_ddl")){driver()->begin();queries_redirect($ag,$yg,queries($Hc)&&queries($h)&&driver()->commit());driver()->rollback();}elseif($uh!=$Yg){$Ub=queries($h);queries_redirect($ag,$yg,$Ub&&queries($Hc));if($Ub)queries($Jc);}else
queries_redirect($ag,$yg,queries($Xk)&&queries($Lc)&&queries($Hc)&&queries($h));}function
create_trigger($xh,array$J){$dl=" $J[Timing] $J[Event]".(preg_match('~ OF~',$J["Event"])?" $J[Of]":"");return"CREATE TRIGGER ".idf_escape($J["Trigger"]).(JUSH=="mssql"?$xh.$dl:$dl.$xh).rtrim(" $J[Type]\n$J[Statement]",";").";";}function
q_dollar($Q){$pc='$$';while(strpos($Q.$pc,$pc)!=strlen($Q))$pc='$_'.substr($pc,1);return$pc.$Q.$pc;}function
routine_collate($ub){static$gb=array();if($ub&&!$gb){foreach(collations()as$fb=>$dm){foreach((array)$dm
as$X)$gb[$X]=$fb;}}return($gb[$ub]?"CHARACTER SET ".q($gb[$ub])." ":"")."COLLATE";}function
create_routine($uj,array$J){$O=array();$n=(array)$J["fields"];ksort($n);foreach($n
as$m){if($m["field"]!="")$O[]=(preg_match("~^(".driver()->inout.")\$~",$m["inout"])?"$m[inout] ":"").idf_escape($m["field"]).process_type($m,routine_collate($m["collation"]));}$nc=rtrim($J["definition"],";");return"CREATE $uj ".idf_escape(trim($J["name"]))." (".implode(", ",$O).")".($uj=="FUNCTION"?" RETURNS".process_type($J["returns"],routine_collate($J["returns"]["collation"])):"").($J["language"]?" LANGUAGE $J[language]":"").(JUSH=="pgsql"?" AS ".q_dollar("\n".trim($nc)."\n"):"\n$nc;");}function
remove_definer($G){return
preg_replace('~^([A-Z =]+) DEFINER=`'.preg_replace('~@(.*)~','`@`(%|\1)',logged_user()).'`~','\1',$G);}function
format_foreign_key(array$p){$j=$p["db"];$fh=$p["ns"];return" FOREIGN KEY (".implode(", ",array_map('Adminer\idf_escape',$p["source"])).") REFERENCES ".($j!=""&&$j!=$_GET["db"]?idf_escape($j).".":"").($fh!=""&&$fh!=$_GET["ns"]?idf_escape($fh).".":"").idf_escape($p["table"])." (".implode(", ",array_map('Adminer\idf_escape',$p["target"])).")".(preg_match("~^(".driver()->onActions.")\$~",$p["on_delete"])?" ON DELETE $p[on_delete]":"").(preg_match("~^(".driver()->onActions.")\$~",$p["on_update"])?" ON UPDATE $p[on_update]":"").($p["deferrable"]?" $p[deferrable]":"");}function
tar_file($o,$il){$I=pack("a100a8a8a8a12a12",$o,644,0,0,decoct($il->size),decoct(time()));$mb=8*32;for($s=0;$s<strlen($I);$s++)$mb+=ord($I[$s]);$I
.=sprintf("%06o",$mb)."\0 ";echo$I,str_repeat("\0",512-strlen($I));$il->send();echo
str_repeat("\0",511-($il->size+511)%512);}function
doc_link(array$ri,$Yk="<sup>?</sup>"){$Sj=connection()->server_info;$hm=preg_replace('~^(\d\.?\d).*~s','\1',$Sj);$Ql=array('sql'=>"https://dev.mysql.com/doc/refman/$hm/en/",'sqlite'=>"https://www.sqlite.org/",'pgsql'=>"https://www.postgresql.org/docs/".(connection()->flavor=='cockroach'?"current":$hm)."/",'mssql'=>"https://learn.microsoft.com/en-us/sql/",'oracle'=>"https://www.oracle.com/pls/topic/lookup?ctx=db".preg_replace('~^.* (\d+)\.(\d+)\.\d+\.\d+\.\d+.*~s','\1\2',$Sj)."&id=",);if(connection()->flavor=='maria'){$Ql['sql']="https://mariadb.com/kb/en/";$ri['sql']=(isset($ri['mariadb'])?$ri['mariadb']:str_replace(".html","/",$ri['sql']));}return($ri[JUSH]?"<a href='".h($Ql[JUSH].$ri[JUSH].(JUSH=='mssql'?"?view=sql-server-ver$hm":""))."'".target_blank().">$Yk</a>":"");}function
db_size($j){if(!connection()->select_db($j))return"?";$I=0;foreach(table_status()as$S)$I+=$S["Data_length"]+$S["Index_length"];return
format_number($I);}function
set_utf8mb4($h){static$O=false;if(!$O&&preg_match('~\butf8mb4~i',$h)){$O=true;echo"SET NAMES ".charset(connection()).";\n\n";}}if(isset($_GET["status"]))$_GET["variables"]=$_GET["status"];if(isset($_GET["import"]))$_GET["sql"]=$_GET["import"];if(DB==""&&isset($_GET["ns"]))redirect(remove_from_uri('ns'));if(!(DB!=""?connection()->select_db(DB):isset($_GET["sql"])||isset($_GET["dump"])||isset($_GET["database"])||isset($_GET["processlist"])||isset($_GET["privileges"])||isset($_GET["user"])||isset($_GET["variables"])||$_GET["script"]=="connect"||$_GET["script"]=="kill")){if(DB!=""||$_GET["refresh"]){restart_session();set_session("dbs",null);}if(DB!=""){header("HTTP/1.1 404 Not Found");page_header(lang(42).": ".h(DB),lang(131),true);}else{if(!isset($_GET["db"])&&support("single_db")){$i=adminer()->databases();if($i)redirect(ME."db=".url_escape($i[0]));}if($_POST["db"]&&!$l)queries_redirect(substr(ME,0,-1),lang(132),drop_databases($_POST["db"]));page_header(lang(133),$l,false);echo"<p class='links'>\n";foreach(array('database'=>lang(134),'privileges'=>lang(78),'processlist'=>lang(135),'variables'=>lang(136),'status'=>lang(137),)as$x=>$X){if(support($x))echo"<a href='".h(ME)."$x='>$X</a>\n";}echo"<p>".lang(138,get_driver(DRIVER),"<b>".h(connection()->server_info)."</b>","<b>".connection()->extension."</b>")."\n","<p>".lang(139,"<b>".h(logged_user())."</b>")."\n";$i=adminer()->databases();if($i){$Bj=support("scheme");$vb=collations();echo"<form action='' method='post'>\n","<table class='checkable odds'".on('click','tableClick').on('dblclick','tableClick').">\n","<thead><tr>".(support("database")?"<td class='hover'>":"")."<th".(JUSH!='mssql'?" aria-sort='ascending'":"").">".lang(42).(get_session("dbs")!==null?" - <a href='".h(ME)."refresh=1'>".lang(140)."</a>":"")."<td>".lang(141)."<td>".lang(142)."<td>".lang(143)." - <a href='".h(ME)."dbsize=1'".on('click','ajaxSetHtml',ME."script=connect").">".lang(144)."</a>"."<tbody>\n";$i=($_GET["dbsize"]?count_tables($i):array_flip($i));foreach($i
as$j=>$T){$tj=h(preg_replace('~&db=[^&]*~','',ME))."db=".url_escape($j);$t=h("Db-".$j);echo"<tr>".(support("database")?"<td class='hover'>".checkbox("db[]",$j,in_array($j,(array)$_POST["db"]),"","","",$t):""),"<th><a href='$tj' id='$t'>".h($j)."</a>";$ub=h(db_collation($j,$vb));echo"<td>".(support("database")?"<a href='$tj".($Bj?"&amp;ns=":"")."&amp;database=' title='".lang(74)."'>$ub</a>":$ub),"<td align='right'><a href='$tj&amp;schema=' id='tables-".h($j)."' title='".lang(77)."'>".($_GET["dbsize"]?$T:"?")."</a>","<td align='right' id='size-".h($j)."'>".($_GET["dbsize"]?db_size($j):"?"),"\n";}echo"</table>\n",(support("database")?"<div class='footer'><div>\n"."<fieldset><legend>".lang(145)." <span id='selected'></span></legend><div>\n"."<input type='hidden' name='all' value=''".on('click','countDbs').">\n"."<input type='submit' name='drop' value='".lang(146)."'".confirm().">\n"."</div></fieldset>\n"."</div></div>\n":""),input_token(),"</form>\n",script("tableCheck();");}$pa=adminer();$zi=($pa
instanceof
Plugins?$pa->plugins:array());$Gc=($pa
instanceof
Plugins?$pa->drivers:array());$uc=design_checksums();if($zi||$Gc||$uc){$nb=($pa
instanceof
Plugins?$pa->checksums():array());$mh=Plugins::officialChecksums();$Ml=function($Pl){return" (<a href='$Pl'".target_blank()." class='update'>".VERSION."</a>)";};$yi=function($Bd)use($nb,$mh,$Ml){return($nb[$Bd]&&$mh[$Bd]&&$nb[$Bd]!==$mh[$Bd]?$Ml("https://www.adminer.org/plugins/?version=".VERSION):"");};echo"<div class='plugins'>\n","<h3>".lang(147)."</h3>\n<ul>\n";foreach($zi
as$wi){$fj=new
\ReflectionObject($wi);$rc=(method_exists($wi,'description')?$wi->description():"");if(!$rc){if(preg_match('~^/[\s*]+(.+)~',$fj->getDocComment(),$A))$rc=$A[1];}$Cj=(method_exists($wi,'screenshot')?$wi->screenshot():"");echo"<li><b>".get_class($wi)."</b>".h($rc?": $rc":"").($Cj?" (<a href='".h($Cj)."'".target_blank().">".lang(148)."</a>)":"").$yi(basename((string)$fj->getFileName(),'.php'))."\n";}foreach($Gc
as$t=>$C)echo"<li><b>".h($t)."</b>: ".h($C).$yi(basename((string)$pa->driverFiles[$t],'.php'))."\n";if($uc){$oh=official_design_checksums();foreach($uc
as$o=>$tc){list($C,$mb)=$tc;$nh=$oh["$C/$o"];echo"<li><b>".h($o)."</b>".h($C?": $C":"").($nh&&$nh!==$mb?$Ml("https://www.adminer.org/?version=".VERSION."#extras"):"")."\n";}}echo"</ul>\n";adminer()->pluginsLinks();echo"</div>\n";}}page_footer("db");exit;}if(support("scheme")){if(DB!=""&&$_GET["ns"]!==""){if(!isset($_GET["ns"]))redirect(preg_replace('~&db=[^&]+~','\0&ns='.url_escape(get_schema()),relative_uri()));if(!set_schema($_GET["ns"])){header("HTTP/1.1 404 Not Found");page_header(lang(86).h(": $_GET[ns]"),lang(149),true);page_footer("ns");exit;}}}adminer()->afterConnect();class
TmpFile{private$handler;var$size=0;function
__construct(){$this->handler=tmpfile();}function
write($Mb){$this->size+=strlen($Mb);fwrite($this->handler,$Mb);}function
send(){fseek($this->handler,0);fpassthru($this->handler);fclose($this->handler);}}if($_GET["select"]!=""&&($_POST["edit"]||$_POST["clone"])&&!$_POST["save"])$_GET["edit"]=$_GET["select"];if(isset($_GET["callf"]))$_GET["call"]=$_GET["callf"];if(isset($_GET["function"]))$_GET["procedure"]=$_GET["function"];if(isset($_GET["download"])){$a=$_GET["download"];$n=fields($a);header("Content-Type: application/octet-stream");header("Content-Disposition: attachment; filename=".friendly_url("$a-".implode("_",$_GET["where"])).".".friendly_url($_GET["field"]));$M=array(idf_escape($_GET["field"]));$H=driver()->select($a,$M,array(where($_GET,$n)),$M);$J=($H?$H->fetch_row():array());echo
driver()->value($J[0],$n[$_GET["field"]]);exit;}elseif(isset($_GET["table"])){$a=$_GET["table"];$n=fields($a);if(!$n)$l=adminer()->error()?:lang(12);$S=table_status1($a);$C=adminer()->tableName($S);page_header(($n&&is_view($S)?$S['Engine']=='materialized view'?lang(150):lang(151):lang(152)).": ".($C!=""?$C:h($a)),$l);$sj=array();foreach($n
as$x=>$m)$sj+=$m["privileges"];adminer()->selectLinks($S,(isset($sj["insert"])||!support("table")?"":null));$_b=$S["Comment"];if($_b!="")echo"<p class='nowrap'>".lang(57).": ".adminer()->commentValue('TABLE',$_b)."\n";if($n)adminer()->tableStructurePrint($n,$S);function
tables_links(array$T){echo"<ul>\n";foreach($T
as$J){$_=preg_replace('~ns=[^&]*~',"ns=".url_escape($J["ns"]),ME);echo"<li><a href='".h($_."table=".url_escape($J["table"]))."'>".($J["ns"]!=$_GET["ns"]?"<b>".h($J["ns"])."</b>.":"").h($J["table"])."</a>";}echo"</ul>\n";}$cf=driver()->inheritsFrom($a);if($cf){echo"<h3>".lang(153)."</h3>\n";tables_links($cf);}if(support("indexes")&&driver()->supportsIndex($S)){echo"<div>\n","<h3 id='indexes'>".lang(154)."</h3>\n";$w=indexes($a);if($w)adminer()->tableIndexesPrint($w,$S);if(driver()->supportsAlterIndex($S))echo'<p class="links hover"><a href="'.h(ME).'indexes='.url_escape($a).'">'.lang(155)."</a>\n";echo"</div>\n";}if(!is_view($S)){if(fk_support($S)){echo"<div>\n","<h3 id='foreign-keys'>".lang(120)."</h3>\n";$Rd=foreign_keys($a);if($Rd){echo"<table>\n","<thead><tr><th>".lang(156)."<td>".lang(157)."<td>".lang(123)."<td>".lang(122)."<td class='hover'><tbody>\n";foreach($Rd
as$C=>$p){echo"<tr title='".h($C)."'>","<th><i>".implode("</i>, <i>",array_map('Adminer\h',$p["source"]))."</i>";$_=($p["db"]!=""?preg_replace('~db=[^&]*~',"db=".url_escape($p["db"]),ME):($p["ns"]!=""?preg_replace('~ns=[^&]*~',"ns=".url_escape($p["ns"]),ME):ME));echo"<td><a href='".h($_."table=".url_escape($p["table"]))."'>".($p["db"]!=""&&$p["db"]!=DB?"<b>".h($p["db"])."</b>.":"").($p["ns"]!=""&&$p["ns"]!=$_GET["ns"]?"<b>".h($p["ns"])."</b>.":"").h($p["table"])."</a>","(<i>".implode("</i>, <i>",array_map('Adminer\h',$p["target"]))."</i>)","<td>".h($p["on_delete"]),"<td>".h($p["on_update"]),'<td class="hover"><a href="'.h(ME.'foreign='.url_escape($a).'&name='.url_escape($C)).'">'.lang(158).'</a>',"\n";}echo"</table>\n";}echo'<p class="links hover"><a href="'.h(ME).'foreign='.url_escape($a).'">'.lang(159)."</a>\n","</div>\n";}if(support("check")){echo"<div>\n","<h3 id='checks'>".lang(160)."</h3>\n";$ib=driver()->checkConstraints($a);if($ib){echo"<table>\n";foreach($ib
as$x=>$X)echo"<tr title='".h($x)."'>","<td><code class='jush-".JUSH."'>".shorten_utf8(preg_replace('~\s+~',' ',ltrim($X)),80,"</code>"),"<td class='hover'><a href='".h(ME.'check='.url_escape($a).'&name='.url_escape($x))."'>".lang(158)."</a>","\n";echo"</table>\n";}echo'<p class="links hover"><a href="'.h(ME).'check='.url_escape($a).'">'.lang(161)."</a>\n","</div>\n";}}if(support(is_view($S)?"view_trigger":"trigger")){echo"<div>\n","<h3 id='triggers'>".lang(162)."</h3>\n";$xl=triggers($a);if($xl){echo"<table>\n";foreach($xl
as$x=>$X)echo"<tr valign='top'><td>".h($X[0])."<td>".h($X[1])."<th>".h($x)."<td class='hover'><a href='".h(ME.'trigger='.url_escape($a).'&name='.url_escape($x))."'>".lang(158)."</a>\n";echo"</table>\n";}echo'<p class="links hover"><a href="'.h(ME).'trigger='.url_escape($a).'">'.lang(163)."</a>\n","</div>\n";}$bf=driver()->inheritedTables($a);if($bf){echo"<h3 id='partitions'>".lang(164)."</h3>\n";$gi=driver()->partitionsInfo($a);if($gi)echo"<p><code class='jush-".JUSH."'>BY ".h("$gi[partition_by]($gi[partition])")."</code>\n";tables_links($bf);}}elseif(isset($_GET["schema"])){page_header(lang(77),"",array(),h(DB.($_GET["ns"]?".$_GET[ns]":"")));function
schema_column($R,array$ej,array&$e){if(!isset($e[$R])){$e[$R]=0;foreach((array)idx($ej,$R)as$C=>$gj){if($C!=$R)$e[$R]=max($e[$R],schema_column($C,$ej,$e)+1);}}return$e[$R];}function
type_class($U){foreach(array('char'=>'text','date'=>'time|year','binary'=>'blob','enum'=>'set',)as$x=>$X){if(preg_match("~$x|$X~",$U))return" class='$x'";}}$Ik=array();$Kk=array();$Jk=array();$zd=array();$da=($_GET["schema"]?:$_COOKIE["adminer_schema-".str_replace(".","_",DB)]);preg_match_all('~([^:]+):([-0-9.]+)x([-0-9.]+)(_|$)~',$da,$gg,PREG_SET_ORDER);foreach($gg
as$s=>$A){$Ik[$A[1]]=array((float)$A[2],(float)$A[3]);$Kk[]="\n\t'".js_escape($A[1])."': [ $A[2], $A[3] ]";}$L=array();$ej=array();$Rd=array();$wa=driver()->allFields();$Ae=array();$Lk=array();foreach(table_status('',true)as$R=>$S){if(!is_view($S)){if(adminer()->tableName($S)!="")$Lk[$R]=$S;else$Ae[$R]=true;}}foreach($Lk
as$R=>$S){$F=0;$L[$R]["fields"]=array();foreach($wa[$R]as$m){$F+=1.25;$zd[$R][$m["field"]]=$F;$L[$R]["fields"][$m["field"]]=$m;}foreach(adminer()->foreignKeys($R)as$X){if($X["db"]==""&&$X["ns"]==""&&!$Ae[$X["table"]]){$Rd[$R][]=$X;$ej[$X["table"]][$R]=array();}}}$e=array();$fe=array();$um=array();$le=array();foreach(array_keys($L)as$C)schema_column($C,$ej,$e);arsort($e);foreach($e
as$C=>$d){$Fg=null;foreach((array)idx($Rd,$C)as$X){if($X["table"]!=$C&&$L[$X["table"]])$Fg=($Fg===null?$e[$X["table"]]:min($Fg,$e[$X["table"]]));}$e[$C]=max($d,(int)$Fg-1);}foreach($L
as$C=>$R){$d=$e[$C];$fe[$d][]=$C;$al=.75*strlen($C);foreach($R["fields"]as$m)$al=max($al,.65*strlen($m["field"]));$um[$d]=max(idx($um,$d,0),ceil($al)+1);}foreach($Rd
as$C=>$dm){foreach($dm
as$X){$ke=$e[$C]+(idx($e,$X["table"],$e[$C])>$e[$C]?1:0);$le[$ke]=idx($le,$ke,0)+1;}}ksort($fe);$ze=0;$tm=0;$xb=0;$Ji=null;$Ek=array();$Nk=array();foreach($fe
as$d=>$T){if($Ji!==null){$xb=round($xb+$um[$Ji]+1.7+idx($le,$d,0)*.1,1);$Ih=array();foreach($T
as$C){$yk=0;$Sb=0;$Ug=array_keys((array)idx($ej,$C));foreach((array)idx($Rd,$C)as$X)$Ug[]=$X["table"];foreach($Ug
as$Qg){if($L[$Qg]&&$e[$Qg]<$d){$yk+=$L[$Qg]["pos"][0];$Sb++;}}$Ih[$C]=($Sb?$yk/$Sb:$ze);}asort($Ih);$T=array_keys($Ih);}$ll=0;foreach($T
as$C){$F=1.25*count($L[$C]["fields"]);$L[$C]["pos"]=($Ik[$C]?:array($ll,$xb));$Ek[$C]=$L[$C]["pos"][1];$Nk[$C]=$um[$d];$ll+=2.5+$F;$ze=max($ze,$L[$C]["pos"][0]+2.5+$F);$tm=max($tm,round($L[$C]["pos"][1]+$um[$d],1));if(!$Ik[$C])$Jk[]="\n\t'".js_escape($C)."': [ ".$L[$C]["pos"][0].", ".$L[$C]["pos"][1]." ]";}$Ji=$d;}$Of=array();$Pa=array();foreach($Rd
as$C=>$dm){foreach($dm
as$X){$Tk=idx($Ek,$X["table"],$Ek[$C]);$gk=$Ek[$C]+$Nk[$C];$rj=($Tk-1>$gk);$Mf=($rj?$gk+1:min($Ek[$C],$Tk)-1);$Oa=idx($Pa,(string)$Mf,0);$Pa[(string)$Mf]=$Oa+1;$Mf=round($rj?min($Mf+$Oa*.1,$Tk-1):$Mf-$Oa*.1,1);while($Of[(string)$Mf])$Mf-=.0001;$L[$C]["references"][$X["table"]][(string)$Mf]=array($X["source"],$X["target"]);$ej[$X["table"]][$C][(string)$Mf]=$X["target"];$Of[(string)$Mf]=true;}}echo'<div id="schema" style="height: ',$ze,'em; width: ',$tm,'em;">
<script',nonce(),'>
const tablePos = {',implode(",",$Kk)."\n",'};
const tablePosDefault = {',implode(",",$Jk)."\n",'};
const em = qs(\'#schema\').offsetHeight / ',$ze,';
document.onmousemove = schemaMousemove;
document.onmouseup = event => schemaMouseup(event, \'',js_escape(DB),'\');
</script>
';foreach($L
as$C=>$R){echo"<div class='table'".on('mousedown','schemaMousedown')." style='top: ".$R["pos"][0]."em; left: ".$R["pos"][1]."em; width: ".$Nk[$C]."em;'>",'<a href="'.h(ME).'table='.url_escape($C).'"><b>'.h($C)."</b></a>";foreach($R["fields"]as$m){$X='<span'.type_class($m["type"]).' title="'.h($m["type"].($m["length"]?"($m[length])":"").($m["null"]?" NULL":'')).'">'.h($m["field"]).'</span>';echo"<br>".($m["primary"]?"<i>$X</i>":$X);}foreach((array)$R["references"]as$Uk=>$gj){foreach($gj
as$Mf=>$bj){$Nf=$Mf-$R["pos"][1];$vk=($Nf>0?"left: 100%; width: calc($Nf"."em - 100%)":"left: $Nf"."em");$tm=($Nf>0?"100%":(-$Nf)."em");$s=0;foreach($bj[0]as$fk)echo"\n<div class='references' title='".h($Uk)."' id='refs$Mf-".($s++)."' style='$vk"."; top: ".$zd[$C][$fk]."em; padding-top: .5em;'>"."<div style='border-top: 1px solid gray; width: $tm;'></div></div>";}}foreach((array)$ej[$C]as$Uk=>$gj){foreach($gj
as$Mf=>$Vk){$Nf=$Mf-$R["pos"][1];$s=0;foreach($Vk
as$Sk)echo"\n<div class='references arrow' title='".h($Uk)."' id='refd$Mf-".($s++)."' style='left: $Nf"."em; top: ".$zd[$C][$Sk]."em;'>"."<div style='height: .5em; border-bottom: 1px solid gray; width: ".(-$Nf)."em;'></div>"."</div>";}}echo"\n</div>\n";}foreach($L
as$C=>$R){foreach((array)$R["references"]as$Uk=>$gj){if($L[$Uk]){foreach($gj
as$Mf=>$bj){$Gg=$ze;$og=-10;foreach($bj[0]as$x=>$fk){$Ai=$R["pos"][0]+$zd[$C][$fk];$Bi=$L[$Uk]["pos"][0]+$zd[$Uk][$bj[1][$x]];$Gg=min($Gg,$Ai,$Bi);$og=max($og,$Ai,$Bi);}echo"<div class='references' id='refl$Mf' style='left: $Mf"."em; top: $Gg"."em; padding: .5em 0;'><div style='border-right: 1px solid gray; margin-top: 1px; height: ".($og-$Gg)."em;'></div></div>\n";}}}}echo'</div>
<p class="links"><a href="',h(ME."schema=".url_escape($da)),'" id="schema-link">',lang(165),'</a>
';}elseif(isset($_GET["dump"])){$a=$_GET["dump"];if($_POST&&!$l){$k=array("auto_increment"=>'');foreach(array("type","routine","event","trigger")as$_k){if(support($_k))$k[$_k."s"]='';}save_settings(array_intersect_key($_POST+$k,array_flip(array("output","format","db_style","table_style","data_style"))+$k),"adminer_export");$T=array_flip((array)$_POST["tables"])+array_flip((array)$_POST["data"]);$pd=dump_headers((count($T)==1?key($T):DB),(DB==""||$_GET["ns"]===""||count($T)>1));$sf=preg_match('~sql~',$_POST["format"]);if($sf){echo"-- Adminer ".VERSION." ".get_driver(DRIVER)." ".str_replace("\n"," ",connection()->server_info)." dump\n\n";if(JUSH=="sql"){echo"SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
".($_POST["data_style"]?"SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';
":"")."
";connection()->query("SET time_zone = '+00:00'");connection()->query("SET sql_mode = ''");}}$vk=$_POST["db_style"];$i=array(DB);if(DB==""){$i=$_POST["databases"];if(is_string($i))$i=explode("\n",rtrim(str_replace("\r","",$i),"\n"));}foreach((array)$i
as$j){adminer()->dumpDatabase($j);if(connection()->select_db($j)){if($sf&&$vk)echo
use_sql($j,$vk).";\n\n";foreach(($_GET["ns"]===""?(array)$_POST["schemas"]:(DB!=""||!support("scheme")?array(""):adminer()->schemas()))as$L){if($L!=""){if(DB==""&&information_schema(DB,$L))continue;set_schema($L);}$sk=($_POST["table_style"]||$_POST["data_style"]?table_status('',true):array());$od=array();$ec=array();foreach($sk
as$C=>$S){if(DB==""||$_GET["ns"]===""||in_array($C,(array)$_POST["tables"]))$od[$C]=$S;if(DB==""||$_GET["ns"]===""||in_array($C,(array)$_POST["data"]))$ec[$C]=$S;}if($sf){if($_POST["table_style"]=="DROP+CREATE"&&function_exists('Adminer\drop_sql'))echo
drop_sql($od);if($_POST["data_style"]=="TRUNCATE+INSERT"&&function_exists('Adminer\truncate_all_sql')){$yl=array();foreach($ec
as$C=>$S){if(!is_view($S)&&!($_POST["table_style"]=="DROP+CREATE"&&isset($od[$C])))$yl[]=$C;}echo
truncate_all_sql($yl);}$Vh="";if($_POST["types"]){foreach(types()as$t=>$U){$nc=type_definition($t);$jh=($nc["kind"]=='d'?"DOMAIN":"TYPE");if($nc["definition"])$Vh
.=($vk!='DROP+CREATE'?"DROP $jh IF EXISTS ".idf_escape($U).";;\n":"")."CREATE $jh ".idf_escape($U)." $nc[definition];\n\n";else$Vh
.="-- Could not export type $U\n\n";}}if($_POST["routines"]){foreach(routines()as$J){$C=$J["ROUTINE_NAME"];$uj=$J["ROUTINE_TYPE"];$h=create_routine($uj,array("name"=>$C)+routine($J["SPECIFIC_NAME"],$uj));set_utf8mb4($h);$Vh
.=($vk!='DROP+CREATE'?"DROP $uj IF EXISTS ".idf_escape($C).";;\n":"")."$h;\n\n";}}if($_POST["events"]){foreach(get_rows("SHOW EVENTS",null,"-- ")as$J){$h=remove_definer(get_val("SHOW CREATE EVENT ".idf_escape($J["Name"]),3));set_utf8mb4($h);$Vh
.=($vk!='DROP+CREATE'?"DROP EVENT IF EXISTS ".idf_escape($J["Name"]).";;\n":"")."$h;;\n\n";}}echo($Vh&&JUSH=='sql'?"DELIMITER ;;\n\n$Vh"."DELIMITER ;\n\n":$Vh);}if($_POST["table_style"]||$_POST["data_style"]){$jm=array();foreach($sk
as$C=>$S){$R=array_key_exists($C,$od);$cc=array_key_exists($C,$ec);if($R||$cc){$il=null;if($pd=="tar"){$il=new
TmpFile;ob_start(array($il,'write'),1e5);}adminer()->dumpTable($C,($R?$_POST["table_style"]:""),(is_view($S)?2:0));if(is_view($S))$jm[]=$C;elseif($cc){$n=fields($C);$M=array("*");$Pb=convert_fields($n,$n);if($Pb)$M[]=substr($Pb,2);adminer()->dumpData($C,$_POST["data_style"],"",$M);}if($sf&&$_POST["triggers"]&&$R&&($xl=trigger_sql($C)))echo"\nDELIMITER ;;\n$xl\nDELIMITER ;\n";if($pd=="tar"){ob_end_flush();tar_file((DB!=""?"":"$j/")."$C.csv",$il);}elseif($sf)echo"\n";}}if($sf&&$_POST["table_style"]&&function_exists('Adminer\foreign_keys_sql')){foreach($od
as$C=>$S){if(!is_view($S))echo
foreign_keys_sql($C);}}if($sf){foreach($jm
as$im)adminer()->dumpTable($im,$_POST["table_style"],1);}if($pd=="tar")echo
pack("x1024");}}}}adminer()->dumpFooter();exit;}page_header(lang(83),$l,($_GET["export"]!=""?array("table"=>$_GET["export"]):array()),h(DB));echo'
<form action="" method="post">
<table class="layout">
';$hc=array('','USE','DROP+CREATE','CREATE');$Mk=array('','DROP+CREATE','CREATE');$dc=array('','TRUNCATE+INSERT','INSERT');if(JUSH=="sql")$dc[]='INSERT+UPDATE';$J=get_settings("adminer_export");if(!$J)$J=array("output"=>"text","format"=>"sql","db_style"=>(DB!=""?"":"CREATE"),"table_style"=>"DROP+CREATE","data_style"=>"INSERT");echo"<tr><th>".lang(166)."<td>".html_radios("output",adminer()->dumpOutput(),$J["output"])."\n","<tr><th>".lang(167)."<td>".html_radios("format",adminer()->dumpFormat(),$J["format"])."\n",(JUSH=="sqlite"?"":"<tr><th>".lang(42)."<td>".html_select('db_style',$hc,$J["db_style"]).(support("type")?checkbox("types",1,$J["types"],lang(7)):"").(support("routine")?checkbox("routines",1,$J["routines"],lang(79)):"").(support("event")?checkbox("events",1,$J["events"],lang(81)):"")),"<tr><th>".lang(142)."<td>".html_select('table_style',$Mk,$J["table_style"]).checkbox("auto_increment",1,$J["auto_increment"],lang(58)).(support("trigger")?checkbox("triggers",1,$J["triggers"],lang(162)):""),"<tr><th>".lang(168)."<td>".html_select('data_style',$dc,$J["data_style"]),'</table>
';adminer()->dumpPrint();echo'<p><input type=\'submit\' value=\'',lang(83),'\'>
',input_token(),'
<table',on('click','dumpClick'),'>
';$Hi=array();if($_GET["ns"]===""){echo"<thead><tr><th style='text-align: left;'>","<label class='block'><input type='checkbox' id='check-schemas' checked class='jsonly' title='".lang(169)."'".on('click','formCheck','^schemas\[').">".lang(86)."</label>","<tbody>\n";foreach(adminer()->schemas()as$L){if(!information_schema(DB,$L))echo"<tr><td>".checkbox("schemas[]",$L,true,$L,"","block")."\n";}}elseif(DB!=""){$kb=($a!=""?"":" checked");echo"<thead><tr>","<th style='text-align: left;'><label class='block'><input type='checkbox' id='check-tables'$kb class='jsonly' title='".lang(169)."'".on('click','formCheck','^tables\[').">".lang(152)."</label>","<th style='text-align: right;'><label class='block'>".lang(168)."<input type='checkbox' id='check-data'$kb class='jsonly' title='".lang(169)."'".on('click','formCheck','^data\[')."></label>","<tbody>\n";$jm="";$Pk=tables_list();foreach($Pk
as$C=>$U){$Gi=preg_replace('~_.*~','',$C);$kb=($a==""||$a==(substr($a,-1)=="%"?"$Gi%":$C));$Mi="<tr><td>".checkbox("tables[]",$C,$kb,$C,"","block");if($U!==null&&!preg_match('~table~i',$U))$jm
.="$Mi\n";else
echo"$Mi<td align='right'><label class='block'><span id='Rows-".h($C)."'></span>".checkbox("data[]",$C,$kb)."</label>\n";$Hi[$Gi]++;}echo$jm;if($Pk)echo
script("ajaxSetHtml('".js_escape(ME)."script=db');");}else{$i=adminer()->databases();echo"<thead><tr><th style='text-align: left;'>","<label class='block'>".($i?"<input type='checkbox' id='check-databases'".($a==""?" checked":"")." class='jsonly' title='".lang(169)."'".on('click','formCheck','^databases\[').">":"").lang(42)."</label>","<tbody>\n";if($i){foreach($i
as$j){if(!information_schema($j)){$Gi=preg_replace('~_.*~','',$j);echo"<tr><td>".checkbox("databases[]",$j,$a==""||$a=="$Gi%",$j,"","block")."\n";$Hi[$Gi]++;}}}else
echo"<tr><td><textarea name='databases' rows='10' cols='20'></textarea>";}echo'</table>
</form>
';$Hd=true;foreach($Hi
as$x=>$X){if($x!=""&&$X>1){echo($Hd?"<p>":" ")."<a href='".h(ME)."dump=".url_escape("$x%")."'>".h($x)."</a>";$Hd=false;}}}elseif(isset($_GET["privileges"])){page_header(lang(78));echo'<p class="links"><a href="'.h(ME).'user=">'.lang(170)."</a>";$H=connection()->query("SELECT User, Host FROM mysql.".(DB==""?"user":"db WHERE ".q(DB)." LIKE Db")." ORDER BY Host, User");$de=$H;if(!$H)$H=connection()->query("SELECT SUBSTRING_INDEX(CURRENT_USER, '@', 1) AS User, SUBSTRING_INDEX(CURRENT_USER, '@', -1) AS Host");echo"<form action=''><p>\n";hidden_fields_get();echo
input_hidden("db",DB),($de?"":input_hidden("grant")),"<table class='odds'>\n","<thead><tr><th>".lang(40)."<th>".lang(38)."<td class='hover'><tbody>\n";while($J=$H->fetch_assoc())echo'<tr><td>'.h($J["User"]),"<td>".h($J["Host"]),'<td class="hover"><a href="'.h(ME.'user='.url_escape($J["User"]).'&host='.url_escape($J["Host"])).'">'.lang(13)."</a>\n";if(!$de||DB!="")echo"<tr><td><input name='user' autocapitalize='off'>","<td><input name='host' value='localhost' autocapitalize='off'>","<td class='hover'><input type='submit' value='".lang(13)."'>\n";echo"</table>\n","</form>\n";}elseif(isset($_GET["sql"])){if(!$l&&$_POST["export"]){save_settings(array("output"=>$_POST["output"],"format"=>$_POST["format"]),"adminer_import");dump_headers("sql");if($_POST["format"]=="sql")echo"$_POST[query]\n";else{adminer()->dumpTable("","");adminer()->dumpData("","table",$_POST["query"]);adminer()->dumpFooter();}exit;}restart_session();$Ce=&get_session("queries");$Be=&$Ce[DB];if(!$l&&$_POST["clear"]){$Be=array();redirect(remove_from_uri("history"));}stop_session();$qa=get_settings("adminer_import");if($_POST&&$qa)save_settings($qa,"adminer_import");page_header((isset($_GET["import"])?lang(82):lang(71)),$l);$Vf=driver()->lineComment();if(!$l&&$_POST&&!(isset($_GET["import"])&&adminer()->importProcess())){$pc=driver()->delimiter;$q=false;if(!isset($_GET["import"]))$G=$_POST["query"];elseif($_POST["webfile"]){$kk=adminer()->importServerPath();$q=@fopen((file_exists($kk)?$kk:"compress.zlib://$kk.gz"),"rb");$G=($q?fread($q,1e6):false);}else$G=get_file("sql_file",true,$pc);if(is_string($G)){if(($wg=ini_bytes("memory_limit"))!="-1")ini_set("memory_limit",max($wg,strval(2*strlen($G)+memory_get_usage()+8e6)));if($G!=""&&strlen($G)<1e6){$Ti=$G.(preg_match("~$pc\\s*\$~",$G)?"":$pc);if(!$Be||first(end($Be))!=$Ti){restart_session();$Be[]=array($Ti,time());set_session("queries",$Ce);stop_session();}}$hk="(?:\\s|/\\*[\s\S]*?\\*/|(?:$Vf)[^\n]*\n?|--\r?\n)";$ph=0;$Uc=true;$Rb=false;$g=connect();if($g&&DB!=""){$g->select_db(DB);if($_GET["ns"]!="")set_schema($_GET["ns"],$g);}$zb=0;$bd=array();$di='[\'"'.(JUSH=="sql"?'`':(JUSH=="sqlite"?'`[':(JUSH=="mssql"?'[':''))).']|/\*|'.$Vf.'|$'.(JUSH=="pgsql"?'|\$([a-zA-Z]\w*)?\$':'');$ml=microtime(true);while($G!=""){if(!$ph&&preg_match("~^$hk*+DELIMITER\\s+(\\S+)~i",$G,$A)){$pc=preg_quote($A[1]);$G=substr($G,strlen($A[0]));}elseif(!$ph&&JUSH=='pgsql'&&preg_match("~^($hk*+COPY\\s+)[^;]+\\s+FROM\\s+stdin;~i",$G,$A)){$pc="\n\\\\\\.\r?\n";$Rb=true;$ph=strlen($A[0]);}else{preg_match("($pc\\s*|$di)",$G,$A,PREG_OFFSET_CAPTURE,$ph);list($Td,$F)=$A[0];if(!$Td&&$q&&!feof($q))$G
.=fread($q,1e5);else{if(!$Td&&rtrim($G)=="")break;$ph=$F+strlen($Td);if($Td&&!preg_match("(^$pc)",$Td)){$bb=driver()->hasCStyleEscapes()||(JUSH=="pgsql"&&($F>0&&strtolower($G[$F-1])=="e"));$si=($Td=='/*'?'\*/':($Td=='['?']':(preg_match("~^(?:$Vf)~",$Td)?"\n":preg_quote($Td).($bb?'|\\\\.':''))));while(preg_match("($si|\$)s",$G,$A,PREG_OFFSET_CAPTURE,$ph)){$zj=$A[0][0];if(!$zj&&$q&&!feof($q))$G
.=fread($q,1e5);else{$ph=$A[0][1]+strlen($zj);if(!$zj||$zj[0]!="\\")break;}}}else{$Uc=false;$Ti=substr($G,0,$F+($Rb?3:0));$zb++;$Mi="<pre id='sql-$zb'><code class='jush-".JUSH."'>".adminer()->sqlCommandQuery($Ti)."</code></pre>\n";if(JUSH=="sqlite"&&preg_match("~^$hk*+(ATTACH|VACUUM\\b.*\\bINTO)\\b~is",$Ti,$A)!==0){echo$Mi,"<p class='error'>".lang(171,preg_match('~ATTACH~i',$A[1])?'ATTACH':'VACUUM INTO')."\n";$bd[]=" <a href='#sql-$zb'>$zb</a>";if($_POST["error_stops"])break;}else{if(!$_POST["only_errors"]){echo$Mi;ob_flush();flush();}$qk=microtime(true);if(connection()->multi_query($Ti)&&$g&&preg_match("~^$hk*+USE\\b~i",$Ti))$g->query($Ti);do{$H=connection()->store_result();if(connection()->error){echo($_POST["only_errors"]?$Mi:""),"<p class='error'>".lang(172).(connection()->errno?" (".connection()->errno.")":"").": ".adminer()->error()."\n";$bd[]=" <a href='#sql-$zb'>$zb</a>";if($_POST["error_stops"])break
2;}else{$_=ME."sql=".url_escape(trim($Ti));$bl=" <span class='time'>(".format_time($qk).")</span>".(strlen($_)<1900?" <a href='".h($_)."'>".lang(13)."</a>":"");$sa=connection()->affected_rows;$nm=($_POST["only_errors"]?"":driver()->warnings());$om="warnings-$zb";if($nm)$bl
.=", <a href='#$om' class='toggle'>".lang(53)."</a>";$md=null;$Mh=null;$nd="explain-$zb";if(is_object($H)){$z=$_POST["limit"];$hh=$z;$Mh=print_select_result($H,$g,array(),$hh);if(!$_POST["only_errors"]){echo"<form action='' method='post'>\n";$hh=max($H->num_rows,$hh);echo"<p class='sql-footer'>".($hh?($z&&$hh>$z?lang(173,$z):"").lang(174,$hh):""),$bl;if($g&&preg_match("~^($hk|\\()*+SELECT\\b~i",$Ti)&&($md=explain($g,$Ti)))echo", <a href='#$nd' class='toggle'>Explain</a>";$t="export-$zb";echo", <a href='#$t' class='toggle'>".lang(83)."</a><span id='$t' class='hidden'>: ".html_select("output",adminer()->dumpOutput(),$qa["output"])." ".html_select("format",adminer()->dumpFormat(),$qa["format"]).input_hidden("query",$Ti)."<input type='submit' name='export' value='".lang(83)."'".($z?"":on('click','sqlExport')).">".input_token()."</span>\n"."</form>\n";}}else{if(preg_match("~^$hk*+(CREATE|DROP|ALTER)$hk++(DATABASE|SCHEMA)\\b~i",$Ti)){restart_session();set_session("dbs",null);stop_session();}if(!$_POST["only_errors"])echo"<p class='message' title='".h(connection()->info)."'>".lang(175,$sa)."$bl\n";}echo($nm?"<div id='$om' class='hidden'>\n$nm</div>\n":"");if($md){echo"<div id='$nd' class='hidden explain'>\n";print_select_result($md,$g,$Mh);echo"</div>\n";}}$qk=microtime(true);}while(connection()->next_result());}$G=substr($G,$ph);$ph=0;if($Rb){$pc=driver()->delimiter;$Rb=false;}}}}}if($Uc)echo"<p class='message'>".lang(176)."\n";else{$Qe=connection()->inTransaction();driver()->rollback();if($Qe)echo"<pre><code class='jush-".JUSH."'>ROLLBACK -- Adminer</code></pre>\n";if($_POST["only_errors"])echo"<p class='message'>".lang(177,$zb-count($bd))," <span class='time'>(".format_time($ml).")</span>\n";elseif($bd&&$zb>1)echo"<p class='error'>".lang(172).": ".implode("",$bd)."\n";}}else
echo"<p class='error'>".upload_error($G)."\n";}echo'
<form action="" method="post" enctype="multipart/form-data" id="form"';$Nl="";if(!isset($_GET["import"]))echo
on('submit','sqlSubmit',remove_from_uri("sql|limit|error_stops|only_errors|history"));else
echo
on_upload_progress($Nl);echo'>
';$jd="<input type='submit' value='".lang(178)."' title='Ctrl+Enter'>";if(!isset($_GET["import"])){$Ti=$_GET["sql"];if($_POST)$Ti=$_POST["query"];elseif($_GET["history"]=="all")$Ti=$Be;elseif($_GET["history"]!="")$Ti=idx($Be[$_GET["history"]],0);echo"<p>";textarea("query",$Ti,20);echo($_POST?"":script("qs('textarea').focus();")),"<p>";adminer()->sqlPrintAfter();echo"$jd\n",lang(179).": <input type='number' name='limit' class='size' value='".h($_POST?$_POST["limit"]:$_GET["limit"])."'>\n";}else{$me=(extension_loaded("zlib")?"[.gz]":"");echo"<fieldset><legend>".lang(180)."</legend><div>",($Nl?input_hidden(ini_get("session.upload_progress.name"),$Nl):""),"SQL$me: ".file_input(" name='sql_file[]' multiple","\n$jd"),($Nl?" <progress class='jsonly hidden' max='1' value='0'></progress>":""),"</div></fieldset>\n";$Ne=adminer()->importServerPath();if($Ne)echo"<fieldset><legend>".lang(181)."</legend><div>",lang(182,"<code>".h($Ne)."$me</code>")," <input type='submit' name='webfile' value='".lang(183)."'>","</div></fieldset>\n";adminer()->importPrint();echo"<p>";}echo
checkbox("error_stops",1,($_POST?$_POST["error_stops"]:isset($_GET["import"])||$_GET["error_stops"]),lang(184))."\n",checkbox("only_errors",1,($_POST?$_POST["only_errors"]:isset($_GET["import"])||$_GET["only_errors"]),lang(185))."\n",input_token();if(!isset($_GET["import"])&&$Be){print_fieldset("history",lang(186),$_GET["history"]!="");for($X=end($Be);$X;$X=prev($Be)){$x=key($Be);list($Ti,$bl,$Qc)=$X;echo'<div><a href="'.h(ME."sql=&history=$x").'" class="hover">'.lang(13)."</a>"." <span class='time' title='".@date('Y-m-d',$bl)."'>".@date("H:i:s",$bl)."</span>"." <code class='jush-".JUSH."'>".shorten_utf8(preg_replace('~\s+~',' ',ltrim(preg_replace("~^(?:$Vf).*~m",'',$Ti))),80,"</code>").($Qc?" <span class='time'>($Qc)</span>":"")."</div>\n";}echo"<input type='submit' name='clear' value='".lang(187)."'>\n","<a href='".h(ME."sql=&history=all")."'>".lang(188)."</a>\n","</div></fieldset>\n";}echo'</form>
';}elseif(isset($_GET["edit"])){$a=$_GET["edit"];$n=fields($a);$Z=(isset($_GET["select"])?($_POST["check"]&&count($_POST["check"])==1?where_check($_POST["check"][0],$n):""):where($_GET,$n));$Ll=(isset($_GET["select"])?$_POST["edit"]:$Z);foreach($n
as$C=>$m){if((!$Ll&&!isset($m["privileges"]["insert"]))||adminer()->fieldName($m)=="")unset($n[$C]);}if($_POST&&!$l&&!isset($_GET["select"])){$ag=relative_uri((string)$_POST["referer"]);if($_POST["insert"])$ag=($Ll?null:relative_uri());elseif(!preg_match('~^.+&select=.+$~',$ag))$ag=ME."select=".url_escape($a);$w=indexes($a);$Fl=unique_array($_GET["where"],$w);$Wi="\nWHERE $Z";if(isset($_POST["delete"]))queries_redirect($ag,lang(189),driver()->delete($a,$Wi,$Fl?0:1));else{$O=array();foreach($n
as$C=>$m){$X=process_input($m);if($X!==false&&$X!==null)$O[idf_escape($C)]=$X;}if($Ll){if(!$O)redirect($ag);queries_redirect($ag,lang(190),driver()->update($a,$O,$Wi,$Fl?0:1));if(is_ajax()){page_headers();page_messages($l);exit;}}else{$H=driver()->insert($a,$O);$Lf=($H?last_id($H):0);queries_redirect($ag,lang(191,($Lf?" $Lf":"")),$H);}}}$J=null;$G="";$bl="";if($Z){$M=array();$Hj=array("*");foreach($n
as$C=>$m){if(isset($m["privileges"]["select"])){$Ca=($_POST["clone"]&&$m["auto_increment"]?"''":convert_field($m));$d=($Ca?"$Ca AS ":"").idf_escape($C);$M[]=$d;if($Ca)$Hj[]=$d;}}$J=array();if(!support("table")){$M=array("*");$Hj=$M;}if($M){$qk=microtime(true);$H=driver()->select($a,$M,array($Z),$M,array(),(isset($_GET["select"])?2:1));$G=str_replace("SELECT ".implode(", ",$M),"SELECT ".implode(", ",$Hj),driver()->query);$bl=format_time($qk);if(!$H)$l=adminer()->error();else{$J=$H->fetch_assoc();if(!$J)$J=false;}if(isset($_GET["select"])&&(!$J||$H->fetch_assoc()))$J=null;}}if(!$n&&driver()->primary!=""){if(!$Z){$H=driver()->select($a,array("*"),array(),array("*"));$J=($H?$H->fetch_assoc():false);if(!$J)$J=array(driver()->primary=>"");}if($J){foreach($J
as$x=>$X){if(!$Z)$J[$x]=null;$n[$x]=array("field"=>$x,"null"=>($x!=driver()->primary),"auto_increment"=>($x==driver()->primary));}}}if($_POST["save"]){$Ci=array();foreach((array)$_POST["fields"]as$x=>$X)$Ci[bracket_escape($x,true)]=$X;$J=$Ci+($J?$J:array());}edit_form($a,$n,$J,$Ll,$l,$G,$bl);}elseif(isset($_GET["create"])){function
referencable_primary($Jj){$I=array();foreach(table_status('',true)as$Gk=>$R){if($Gk!=$Jj&&fk_support($R)){foreach(fields($Gk)as$m){if($m["primary"]){if($I[$Gk]){unset($I[$Gk]);break;}$I[$Gk]=$m;}}}}return$I;}$a=$_GET["create"];$ii=driver()->partitionBy;$mi=($ii&&$a!=""?driver()->partitionsInfo($a):array());$dj=referencable_primary($a);$Rd=array();foreach($dj
as$Gk=>$m)$Rd[str_replace("`","``",$Gk)."`".str_replace("`","``",$m["field"])]=$Gk;$Ph=array();$S=array();if($a!=""){$Ph=fields($a);$S=table_status1($a);if(count($S)<2)$l=lang(12);}$J=$_POST;$J["fields"]=(array)$J["fields"];if($J["auto_increment_col"])$J["fields"][$J["auto_increment_col"]]["auto_increment"]=true;if($_POST&&!$l)save_settings(array("comments"=>$_POST["comments"],"defaults"=>$_POST["defaults"]));if($_POST&&!process_fields($J["fields"])&&!$l){if($_POST["drop"])queries_redirect(substr(ME,0,-1),lang(192),drop_tables(array($a)));else{$n=array();$wa=array();$Rl=false;$Pd=array();$Oh=reset($Ph);$ua=" FIRST";foreach($J["fields"]as$x=>$m){$p=$Rd[$m["type"]];$_l=($p!==null?$dj[$p]:$m);if($m["field"]!=""){if(!$m["generated"])$m["default"]=null;$Ri=process_field($m,$_l);$wa[]=array($m["orig"],$Ri,$ua);if(!$Oh||$Ri!==process_field($Oh,$Oh)){$n[]=array($m["orig"],$Ri,$ua);if($m["orig"]!=""||$ua)$Rl=true;}if($p!==null)$Pd[idf_escape($m["field"])]=($a!=""&&JUSH!="sqlite"?"ADD":" ").format_foreign_key(array('table'=>$Rd[$m["type"]],'source'=>array($m["field"]),'target'=>array($_l["field"]),'on_delete'=>$m["on_delete"],));$ua=" AFTER ".idf_escape($m["field"]);}elseif($m["orig"]!=""){$Rl=true;$n[]=array($m["orig"]);}if($m["orig"]!=""){$Oh=next($Ph);if(!$Oh)$ua="";}}$ki=array();if(in_array($J["partition_by"],$ii)){foreach($J
as$x=>$X){if(preg_match('~^partition~',$x))$ki[$x]=$X;}foreach($ki["partition_names"]as$x=>$C){if($C==""){unset($ki["partition_names"][$x]);unset($ki["partition_values"][$x]);}}$ki["partition_names"]=array_values($ki["partition_names"]);$ki["partition_values"]=array_values($ki["partition_values"]);if($ki==$mi)$ki=array();}elseif(preg_match("~partitioned~",$S["Create_options"]))$ki=null;$B=lang(193);if($a==""){cookie("adminer_engine",$J["Engine"]);$B=lang(194);}$C=trim($J["name"]);$ag=ME.(support("table")?"table=":"select=").url_escape($C);$H=alter_table($a,$C,(JUSH=="sqlite"&&($Rl||$Pd)?$wa:$n),$Pd,($J["Comment"]!=$S["Comment"]?$J["Comment"]:null),($J["Engine"]&&$J["Engine"]!=$S["Engine"]?$J["Engine"]:""),($J["Collation"]&&$J["Collation"]!=$S["Collation"]?$J["Collation"]:""),($J["Auto_increment"]!=""?number($J["Auto_increment"]):""),$ki);if($H&&!Queries::$queries&&$a!=""&&!$n&&!$Pd)redirect($ag);queries_redirect($ag,$B,$H);}}page_header(($a!=""?lang(51):lang(84)),$l,array("table"=>$a),h($a));if(!$_POST){$Bl=driver()->types();$J=array("Engine"=>$_COOKIE["adminer_engine"],"fields"=>array(array("field"=>"","type"=>(isset($Bl["int"])?"int":(isset($Bl["integer"])?"integer":"")),"on_update"=>"")),"partition_names"=>array(""),);if($a!=""){$J=$S;$J["name"]=$a;$J["fields"]=array();if(!$_GET["auto_increment"])$J["Auto_increment"]="";foreach($Ph
as$m){if($m["generated"])$m["default"]=ltrim($m["default"]);$m["generated"]=$m["generated"]?:(isset($m["default"])?"DEFAULT":"");$J["fields"][]=$m;}if($ii){$J+=$mi;$J["partition_names"][]="";$J["partition_values"][]="";}}}$vb=flat_collations();$Wc=driver()->engines();foreach($Wc
as$Vc){if(!strcasecmp($Vc,$J["Engine"])){$J["Engine"]=$Vc;break;}}$jg=max_input_vars(12,20);if($jg){$Ae=(count($J["fields"])>$jg?"":" hidden");echo"<p".($Ae?" id='max-fields' data-columns='$jg'":"")." class='error$Ae'>".max_input_vars_error()."\n";}echo'
<form action="" method="post" id="form">
<p>
';if(support("columns")||$a==""){echo
lang(195).": <input name='name'".($a==""&&!$_POST?" autofocus":"")." data-maxlength='64' value='".h($J["name"])."' autocapitalize='off'>\n",($Wc?html_select("Engine",array(""=>"(".lang(196).")")+$Wc,$J["Engine"],on('change','helpClose').on_help_value())."\n":"");if($vb)echo"<datalist id='collations'>".optionlist($vb)."</datalist>\n",(preg_match("~sqlite|mssql~",JUSH)?"":"<input list='collations' name='Collation' value='".h($J["Collation"])."' placeholder='(".lang(121).")'>\n");echo"<input type='submit' value='".lang(17)."'>\n";}if(support("columns")){echo"<div class='scrollable'>\n","<table id='edit-fields' class='nowrap'>\n";edit_fields($J["fields"],$vb,"TABLE",$Rd);echo"</table>\n",script("editFields();"),"</div>\n<p>\n",lang(58).": <input type='number' name='Auto_increment' class='size' value='".h($J["Auto_increment"])."'>\n",checkbox("defaults",1,($_POST?$_POST["defaults"]:get_setting("defaults")),lang(197),on('click','columnShowClick',5),"jsonly");$Bb=($_POST?$_POST["comments"]:get_setting("comments"));if(support("comment")){echo
checkbox("comments",1,$Bb,lang(57),on('click','editingCommentsClick',true),"jsonly").' ';$c=" name='Comment' data-maxlength='".(min_version(5.5)?2048:60)."'".($Bb?"":" class='hidden'");echo
adminer()->commentInput('TABLE',$c,$J["Comment"]);}echo'<p>
<input type=\'submit\' value=\'',lang(17),'\'>
';}echo'
';if($a!="")echo'<input type=\'submit\' name=\'drop\' value=\'',lang(146),'\'',confirm(lang(198,$a)),'>
';if($ii&&(JUSH=='sql'||$a=="")){$ji=preg_match('~RANGE|LIST~',$J["partition_by"]);print_fieldset("partition",lang(199),$J["partition_by"]);echo"<p>".html_select("partition_by",array_merge(array(""),$ii),$J["partition_by"],on('change','partitionByChange').on_help_value('.','PARTITION BY $&'))."\n","(<input name='partition' value='".h($J["partition"])."'>)\n",lang(200).": <input type='number' name='partitions' class='size".($ji||!$J["partition_by"]?" hidden":"")."' value='".h($J["partitions"])."'>\n","<table id='partition-table'".($ji?"":" class='hidden'").">\n","<thead><tr><th>".lang(201)."<th>".lang(202)."<tbody>\n";foreach($J["partition_names"]as$x=>$X)echo'<tr>','<td><input name="partition_names[]" value="'.h($X).'" autocapitalize="off"'.($x==count($J["partition_names"])-1?on('input','partitionNameChange'):'').'>','<td><input name="partition_values[]" value="'.h(idx($J["partition_values"],$x)).'">';echo"</table>\n</div></fieldset>\n";}echo
input_token(),'</form>
';}elseif(isset($_GET["indexes"])){$a=$_GET["indexes"];$We=array("PRIMARY","UNIQUE","INDEX");$S=table_status1($a,true);$Te=driver()->indexAlgorithms($S);if(preg_match('~MyISAM|M?aria'.(min_version(5.6,'10.0.5')?'|InnoDB':'').'~i',$S["Engine"]))$We[]="FULLTEXT";if(preg_match('~MyISAM|M?aria'.(min_version(5.7,'10.2.2')?'|InnoDB':'').'~i',$S["Engine"]))$We[]="SPATIAL";if(min_version('',11.7)&&preg_match('~MyISAM|InnoDB~i',$S["Engine"]))$We[]="VECTOR";$w=indexes($a);$n=fields($a);$Ki=array();if(JUSH=="mongo"){$Ki=$w["_id_"];unset($We[0]);unset($w["_id_"]);}$J=$_POST;if($J)save_settings(array("index_options"=>$J["options"]));if($_POST&&!$l&&!$_POST["add"]&&!$_POST["drop_col"]){$b=array();foreach($J["indexes"]as$v){$C=$v["name"];if(in_array($v["type"],$We)){$e=array();$Sf=array();$sc=array();$Bh=array();$Ue=(support("partial_indexes")?$v["partial"]:"");$Se=(in_array($v["algorithm"],$Te)?$v["algorithm"]:"");$O=array();ksort($v["columns"]);foreach($v["columns"]as$x=>$d){if($d!=""){$y=idx($v["lengths"],$x);$qc=idx($v["descs"],$x);$Ah=idx($v["opclasses"],$x);$O[]=($n[$d]?idf_escape($d):$d).($y?"(".(+$y).")":"").($Ah!=""?" ".idf_escape($Ah):"").($qc?" DESC":"");$e[]=$d;$Sf[]=($y?:null);$sc[]=$qc;$Bh[]="$Ah";}}$kd=$w[$C];if($kd){ksort($kd["columns"]);ksort($kd["lengths"]);ksort($kd["descs"]);if($v["type"]==$kd["type"]&&array_values($kd["columns"])===$e&&(!$kd["lengths"]||array_values($kd["lengths"])===$Sf)&&array_values($kd["descs"])===$sc&&(!$kd["opclasses"]||array_values($kd["opclasses"])===$Bh)&&$kd["partial"]==$Ue&&(!$Te||$kd["algorithm"]==$Se)){unset($w[$C]);continue;}}if($e)$b[]=array($v["type"],$C,$O,$Se,$Ue);}}foreach($w
as$C=>$kd)$b[]=array($kd["type"],$C,"DROP");if(!$b)redirect(ME."table=".url_escape($a));queries_redirect(ME."table=".url_escape($a),lang(203),alter_indexes($a,$b));}page_header(lang(154),$l,array("table"=>$a),h($a));$Ad=array_keys($n);if($_POST["add"]){foreach($J["indexes"]as$x=>$v){if($v["columns"][count($v["columns"])]!="")$J["indexes"][$x]["columns"][]="";}$v=end($J["indexes"]);if($v["type"]||array_filter($v["columns"],'strlen'))$J["indexes"][]=array("columns"=>array(1=>""));}if(!$J){foreach($w
as$x=>$v){$w[$x]["name"]=$x;$w[$x]["columns"][]="";}$w[]=array("columns"=>array(1=>""));$J["indexes"]=$w;}$Sf=(JUSH=="sql"||JUSH=="mssql");$Bh=driver()->indexOpclasses();$Xj=($_POST?$_POST["options"]:get_setting("index_options"));echo'
<form action="" method="post">
<div class="scrollable">
<table class="nowrap odds">
<thead><tr>
<th id="label-type">',lang(204);$Le=" class='idxopts".($Xj?"":" hidden")."'";if($Te)echo"<th id='label-algorithm'$Le>".lang(205).doc_link(array('sql'=>'create-index.html#create-index-storage-engine-index-types','mariadb'=>'storage-engine-index-types/','pgsql'=>'indexes-types.html',));echo'<th><input type="submit" hidden>',lang(206).($Sf?"<span$Le> (".lang(207).")</span>":"");if($Sf||support("descidx"))echo
checkbox("options",1,$Xj,lang(127),on('click','indexOptionsShow'),"jsonly")."\n";echo'<th id="label-name">',lang(208);if(support("partial_indexes"))echo"<th id='label-condition'$Le>".lang(209);echo'<th><noscript>',icon("plus","add[0]","+",lang(128)),'</noscript>
<tbody>
';if($Ki){echo"<tr><td>PRIMARY<td>";foreach($Ki["columns"]as$x=>$d)echo
select_input(" disabled",array_combine($Ad,$Ad),$d),"<label><input disabled type='checkbox'>".lang(66)."</label> ";echo"<td><td>\n";}$wf=1;foreach($J["indexes"]as$v){if(!$_POST["drop_col"]||$wf!=key($_POST["drop_col"])){echo"<tr><td>".html_select("indexes[$wf][type]",array(-1=>"")+$We,$v["type"],($wf==count($J["indexes"])?on('change','indexesAddRow'):""),"label-type");if($Te)echo"<td$Le>".html_select("indexes[$wf][algorithm]",array_merge(array(""),$Te),$v['algorithm'],"","label-algorithm");echo"<td>";ksort($v["columns"]);$s=1;foreach($v["columns"]as$x=>$d){echo"<span>".select_input(" name='indexes[$wf][columns][$s]' title='".lang(55)."'".on('change','indexesChangeColumn',(JUSH=="sql"?"":$_GET["indexes"]."_")),($n&&($d==""||$n[$d])?array_combine($Ad,$Ad):array()),$d)," <span$Le>",($Sf?"<input type='number' name='indexes[$wf][lengths][$s]' class='size' value='".h(idx($v["lengths"],$x))."' title='".lang(126)."'>":"");if($Bh){$Ah=idx($v["opclasses"],$x);echo
html_select("indexes[$wf][opclasses][$s]",array(""=>"(".lang(210).")")+array_combine($Bh,$Bh)+($Ah!=""?array($Ah=>$Ah):array()),$Ah),doc_link(array('pgsql'=>'indexes-opclass.html'));}echo(support("descidx")?checkbox("indexes[$wf][descs][$s]",1,idx($v["descs"],$x),lang(66)):""),"<br>","</span></span>";$s++;}echo"<td><input name='indexes[$wf][name]' value='".h($v["name"])."' autocapitalize='off' aria-labelledby='label-name'>\n";if(support("partial_indexes"))echo"<td$Le><input name='indexes[$wf][partial]' value='".h($v["partial"])."' autocapitalize='off' aria-labelledby='label-condition'>\n";echo"<td>".icon("cross","drop_col[$wf]","x",lang(130),on('click','editingRemoveRow','indexes$1[type]'));}$wf++;}echo'</table>
</div>
<p>
<input type=\'submit\' value=\'',lang(17),'\'>
',input_token(),'</form>
';}elseif(isset($_GET["database"])){$J=$_POST;if($_POST&&!$l&&!$_POST["add"]){$C=trim($J["name"]);if($_POST["drop"]){$_GET["db"]="";queries_redirect(remove_from_uri("db|database"),lang(211),drop_databases(array(DB)));}elseif($C!==DB){if(DB!=""){$_GET["db"]=$C;queries_redirect(preg_replace('~\bdb=[^&]*&~','',ME)."db=".url_escape($C),lang(212),rename_database($C,(string)$J["collation"]));}else{$i=explode("\n",str_replace("\r","",$C));$wk=true;$Jf="";foreach($i
as$j){if(count($i)==1||$j!=""){if(!create_database($j,(string)$J["collation"]))$wk=false;$Jf=$j;}}restart_session();set_session("dbs",null);queries_redirect(preg_replace('~&db=[^&]*~','',ME)."db=".url_escape($Jf),lang(213),$wk);}}else{if(!$J["collation"])redirect(substr(ME,0,-1));query_redirect("ALTER DATABASE ".idf_escape($C).(preg_match('~^[a-z0-9_]+$~i',$J["collation"])?" COLLATE $J[collation]":""),substr(ME,0,-1),lang(214));}}page_header(DB!=""?lang(74):lang(134),$l,array(),h(DB));$vb=collations();$C=DB;if($_POST)$C=$J["name"];elseif(DB!="")$J["collation"]=db_collation(DB,$vb);elseif(JUSH=="sql"){foreach(get_vals("SHOW GRANTS")as$de){if(preg_match('~ ON (`(([^\\\\`]|``|\\\\.)*)%`\.\*)?~',$de,$A)&&$A[1]){$C=stripcslashes(idf_unescape("`$A[2]`"));break;}}}echo'
<form action="" method="post">
<p>
',($_POST["add"]||strpos($C,"\n")?'<textarea autofocus name="name" rows="10" cols="40">'.h($C).'</textarea><br>':'<input name="name" autofocus value="'.h($C).'" data-maxlength="64" autocapitalize="off">')."\n",($vb?html_select("collation",array(""=>"(".lang(121).")")+$vb,$J["collation"]).doc_link(array('sql'=>"charset-charsets.html",'mariadb'=>"supported-character-sets-and-collations/",'mssql'=>"relational-databases/system-functions/sys-fn-helpcollations-transact-sql",)):"")."\n",'<input type=\'submit\' value=\'',lang(17),'\'>
';if(DB!="")echo"<input type='submit' name='drop' value='".lang(146)."'".confirm(lang(198,DB)).">\n";elseif(!$_POST["add"]&&$_GET["db"]=="")echo
icon("plus","add[0]","+",lang(128))."\n";echo
input_token(),'</form>
';}elseif(isset($_GET["scheme"])){$J=$_POST;if($_POST&&!$l){$_=preg_replace('~ns=[^&]*&~','',ME)."ns=";if($_POST["drop"])query_redirect("DROP SCHEMA ".idf_escape($_GET["ns"]),$_,lang(215));else{$C=trim($J["name"]);$_
.=url_escape($C);if($_GET["ns"]=="")query_redirect("CREATE SCHEMA ".idf_escape($C),$_,lang(216));elseif($_GET["ns"]!=$C)query_redirect("ALTER SCHEMA ".idf_escape($_GET["ns"])." RENAME TO ".idf_escape($C),$_,lang(217));else
redirect($_);}}page_header($_GET["ns"]!=""?lang(75):lang(76),$l);if(!$J)$J["name"]=$_GET["ns"];echo'
<form action="" method="post">
<p><input name="name" autofocus value="',h($J["name"]),'" autocapitalize="off">
<input type=\'submit\' value=\'',lang(17),'\'>
';if($_GET["ns"]!="")echo"<input type='submit' name='drop' value='".lang(146)."'".confirm(lang(198,$_GET["ns"])).">\n";echo
input_token(),'</form>
';}elseif(isset($_GET["call"])){$ca=($_GET["name"]?:$_GET["call"]);page_header(lang(218).": ".h($ca),$l);$wj=(isset($_GET["callf"])?"FUNCTION":"PROCEDURE");$uj=routine($_GET["call"],$wj);$Oe=array();$Vh=array();foreach($uj["fields"]as$s=>$m){if(substr($m["inout"],-3)=="OUT"&&JUSH=='sql')$Vh[$s]="@".idf_escape($m["field"])." AS ".idf_escape($m["field"]);if(!$m["inout"]||substr($m["inout"],0,2)=="IN")$Oe[]=$s;}if(!$l&&$_POST){$cb=array();foreach($uj["fields"]as$x=>$m){$X="";if(in_array($x,$Oe)){$X=process_input($m);if($X===false)$X="''";if(isset($Vh[$x]))connection()->query("SET @".idf_escape($m["field"])." = $X");}if(isset($Vh[$x]))$cb[]="@".idf_escape($m["field"]);elseif(in_array($x,$Oe))$cb[]=$X;}$G=(isset($_GET["callf"])?"SELECT ":"CALL ").(idx($uj["returns"],"type")=="record"?"* FROM ":"").table($ca)."(".implode(", ",$cb).")";$qk=microtime(true);$H=connection()->multi_query($G);$sa=connection()->affected_rows;echo
adminer()->selectQuery($G,$qk,!$H);if(!$H)echo"<p class='error'>".adminer()->error()."\n";else{$g=connect();if($g)$g->select_db(DB);do{$H=connection()->store_result();if(is_object($H))print_select_result($H,$g);else
echo"<p class='message'>".lang(219,$sa)." <span class='time'>".@date("H:i:s")."</span>\n";}while(connection()->next_result());if($Vh)print_select_result(connection()->query("SELECT ".implode(", ",$Vh)));}}echo'
<form action="" method="post">
';if($Oe){echo"<table class='layout'>\n";foreach($Oe
as$x){$m=$uj["fields"][$x];$C=$m["field"];echo"<tr><th>".adminer()->fieldName($m);$Y=idx($_POST["fields"],$C);if($Y!=""){if($m["type"]=="set")$Y=implode(",",$Y);}input($m,$Y,idx($_POST["function"],$C,""));echo"\n";}echo"</table>\n";}echo'<p>
<input type=\'submit\' value=\'',lang(218),'\'>
',input_token(),'</form>

',adminer()->commentValue($wj,$uj['comment']);}elseif(isset($_GET["foreign"])){$a=$_GET["foreign"];$C=$_GET["name"];$J=$_POST;if($_POST&&!$l&&!$_POST["add"]&&!$_POST["change"]&&!$_POST["change-js"]){if(!$_POST["drop"]){$J["source"]=array_filter($J["source"],'strlen');ksort($J["source"]);$Sk=array();foreach($J["source"]as$x=>$X)$Sk[$x]=$J["target"][$x];$J["target"]=$Sk;}if(JUSH=="sqlite")$H=recreate_table($a,$a,array(),array(),array(" $C"=>($J["drop"]?"":" ".format_foreign_key($J))));else{$b="ALTER TABLE ".table($a);$H=($C==""||queries("$b DROP ".(JUSH=="sql"?"FOREIGN KEY ":"CONSTRAINT ").idf_escape($C)));if(!$J["drop"])$H=queries("$b ADD".format_foreign_key($J));}queries_redirect(ME."table=".url_escape($a),($J["drop"]?lang(220):($C!=""?lang(221):lang(222))),$H);if(!$J["drop"])$l=lang(223);}page_header(($C!=""?lang(224):lang(159)),$l,array("table"=>$a),h($C!=""?$C:$a));if($_POST){ksort($J["source"]);if($_POST["change"]||$_POST["change-js"])$J["target"]=array();else$J["source"][]="";}elseif($C!=""){$Rd=foreign_keys($a);$J=$Rd[$C];$J["source"][]="";}else{$J["table"]=$a;$J["source"]=array("");}echo'
<form action="" method="post">
';$fk=array_keys(fields($a));if($J["db"]!="")connection()->select_db($J["db"]);if($J["ns"]!=""){$Qh=get_schema();set_schema($J["ns"]);}$cj=array_keys(array_filter(table_status('',true),'Adminer\fk_support'));$Sk=array_keys(fields(in_array($J["table"],$cj)?$J["table"]:reset($cj)));$c=on('change','foreignChange');echo"<p><label>".lang(225).": ".html_select("table",$cj,$J["table"],$c)."</label>\n";if(support("scheme")){$Aj=array_filter(adminer()->schemas(),function($L){return!information_schema(DB,$L);});echo"<label>".lang(86).": ".html_select("ns",$Aj,$J["ns"]!=""?$J["ns"]:$_GET["ns"],$c)."</label>";if($J["ns"]!="")set_schema($Qh);}elseif(JUSH!="sqlite"){$ic=array();foreach(adminer()->databases()as$j){if(!information_schema($j))$ic[]=$j;}echo"<label>".lang(85).": ".html_select("db",$ic,$J["db"]!=""?$J["db"]:$_GET["db"],$c)."</label>";}echo
input_hidden("change-js"),'<noscript><p><input type=\'submit\' name=\'change\' value=\'',lang(226),'\'></noscript>
<table>
<thead><tr><th id="label-source">',lang(156),'<th id="label-target">',lang(157),'<tbody>
';$wf=0;foreach($J["source"]as$x=>$X){echo"<tr>","<td>".html_select("source[".(+$x)."]",array(-1=>"")+$fk,$X,($wf==count($J["source"])-1?on('change','foreignAddRow'):""),"label-source"),"<td>".html_select("target[".(+$x)."]",$Sk,idx($J["target"],$x),"","label-target");$wf++;}echo'</table>
<p>
<label>',lang(123),': ',html_select("on_delete",array(-1=>"")+explode("|",driver()->onActions),$J["on_delete"]),'</label>
<label>',lang(122),': ',html_select("on_update",array(-1=>"")+explode("|",driver()->onActions),$J["on_update"]),'</label>
',(support("deferrable")?html_select("deferrable",array('NOT DEFERRABLE','DEFERRABLE','DEFERRABLE INITIALLY DEFERRED'),$J["deferrable"]).' ':''),doc_link(array('sql'=>"innodb-foreign-key-constraints.html",'mariadb'=>"foreign-keys/",'pgsql'=>"sql-createtable.html#SQL-CREATETABLE-PARMS-REFERENCES",'mssql'=>"t-sql/statements/create-table-transact-sql",'oracle'=>"SQLRF01111",)),'<p>
<input type=\'submit\' value=\'',lang(17),'\'>
<noscript><p><input type=\'submit\' name=\'add\' value=\'',lang(227),'\'></noscript>
';if($C!="")echo'<input type=\'submit\' name=\'drop\' value=\'',lang(146),'\'',confirm(lang(198,$C)),'>
';echo
input_token(),'</form>
';}elseif(isset($_GET["view"])){$a=$_GET["view"];$J=$_POST;$Rh="VIEW";if(JUSH=="pgsql"&&$a!=""){$P=table_status1($a);$Rh=strtoupper($P["Engine"]);}if($_POST&&!$l){$C=trim($J["name"]);$Ca=" AS\n$J[select]";$ag=ME."table=".url_escape($C);$B=lang(228);$U=($_POST["materialized"]?"MATERIALIZED VIEW":"VIEW");if(!$_POST["drop"]&&$a==$C&&JUSH!="sqlite"&&$U=="VIEW"&&$Rh=="VIEW")query_redirect((JUSH=="mssql"?"ALTER":"CREATE OR REPLACE")." VIEW ".table($C).$Ca,$ag,$B);else{$Wk="adminer_".uniqid();drop_create("DROP $Rh ".table($a),"CREATE $U ".table($C).$Ca,"DROP $U ".table($C),"CREATE $U ".table($Wk).$Ca,"DROP $U ".table($Wk),($_POST["drop"]?substr(ME,0,-1):$ag),lang(229),$B,lang(230),$a,$C);}}if(!$_POST&&$a!=""){$J=view($a);$J["name"]=$a;$J["materialized"]=($Rh!="VIEW");if(!$l)$l=adminer()->error();}page_header(($a!=""?lang(50):lang(231)),$l,array("table"=>$a),h($a));echo'
<form action="" method="post">
<p>',lang(208),': <input name="name" value="',h($J["name"]),'" data-maxlength="64" autocapitalize="off">
',(support("materializedview")?" ".checkbox("materialized",1,$J["materialized"],lang(150)):""),'<p>';textarea("select",$J["select"]);echo'<p>
<input type=\'submit\' value=\'',lang(17),'\'>
';if($a!="")echo'<input type=\'submit\' name=\'drop\' value=\'',lang(146),'\'',confirm(lang(198,$a)),'>
';echo
input_token(),'</form>
';}elseif(isset($_GET["event"])){$aa=$_GET["event"];$kf=array("YEAR","QUARTER","MONTH","DAY","HOUR","MINUTE","WEEK","SECOND","YEAR_MONTH","DAY_HOUR","DAY_MINUTE","DAY_SECOND","HOUR_MINUTE","HOUR_SECOND","MINUTE_SECOND");$sk=array("ENABLED"=>"ENABLE","DISABLED"=>"DISABLE","SLAVESIDE_DISABLED"=>"DISABLE ON SLAVE");$J=$_POST;if($_POST&&!$l){if($_POST["drop"])query_redirect("DROP EVENT ".idf_escape($aa),substr(ME,0,-1),lang(232));elseif(in_array($J["INTERVAL_FIELD"],$kf)&&isset($sk[$J["STATUS"]])){$_j="\nON SCHEDULE ".($J["INTERVAL_VALUE"]?"EVERY ".q($J["INTERVAL_VALUE"])." $J[INTERVAL_FIELD]".($J["STARTS"]?" STARTS ".q($J["STARTS"]):"").($J["ENDS"]?" ENDS ".q($J["ENDS"]):""):"AT ".q($J["STARTS"]))." ON COMPLETION".($J["ON_COMPLETION"]?"":" NOT")." PRESERVE";queries_redirect(substr(ME,0,-1),($aa!=""?lang(233):lang(234)),queries(($aa!=""?"ALTER EVENT ".idf_escape($aa).$_j.($aa!=$J["EVENT_NAME"]?"\nRENAME TO ".idf_escape($J["EVENT_NAME"]):""):"CREATE EVENT ".idf_escape($J["EVENT_NAME"]).$_j)."\n".$sk[$J["STATUS"]]." COMMENT ".q($J["EVENT_COMMENT"]).rtrim(" DO\n$J[EVENT_DEFINITION]",";").";"));}}page_header(($aa!=""?lang(235).": ".h($aa):lang(236)),$l);if(!$J&&$aa!=""){$K=get_rows("SELECT * FROM information_schema.EVENTS WHERE EVENT_SCHEMA = ".q(DB)." AND EVENT_NAME = ".q($aa));$J=reset($K);}echo'
<form action="" method="post">
<table class="layout">
<tr><th>',lang(208),'<td><input name="EVENT_NAME" value="',h($J["EVENT_NAME"]),'" data-maxlength="64" autocapitalize="off">
<tr><th title="datetime">',lang(237),'<td><input name="STARTS" value="',h("$J[EXECUTE_AT]$J[STARTS]"),'">
<tr><th title="datetime">',lang(238),'<td><input name="ENDS" value="',h($J["ENDS"]),'">
<tr><th>',lang(239),'<td><input type="number" name="INTERVAL_VALUE" value="',h($J["INTERVAL_VALUE"]),'" class="size"> ',html_select("INTERVAL_FIELD",$kf,$J["INTERVAL_FIELD"]),'<tr><th>',lang(137),'<td>',html_select("STATUS",$sk,$J["STATUS"]),'<tr><th>',lang(57),'<td><input name="EVENT_COMMENT" value="',h($J["EVENT_COMMENT"]),'" data-maxlength="64">
<tr><th><td>',checkbox("ON_COMPLETION","PRESERVE",$J["ON_COMPLETION"]=="PRESERVE",lang(240)),'</table>
<p>';textarea("EVENT_DEFINITION",$J["EVENT_DEFINITION"]);echo'<p>
<input type=\'submit\' value=\'',lang(17),'\'>
';if($aa!="")echo'<input type=\'submit\' name=\'drop\' value=\'',lang(146),'\'',confirm(lang(198,$aa)),'>
';echo
input_token(),'</form>
';}elseif(isset($_GET["procedure"])){$ca=($_GET["name"]?:$_GET["procedure"]);$uj=(isset($_GET["function"])?"FUNCTION":"PROCEDURE");$J=$_POST;$J["fields"]=(array)$J["fields"];if($_POST&&!process_fields($J["fields"])&&!$l){foreach($J["fields"]as$x=>$m){if($m["field"]=="")unset($J["fields"][$x]);}$th=routine_id($ca,routine($_GET["procedure"],$uj));$Xg=routine_id($J["name"],$J);$h=create_routine($uj,$J);$ag=substr(ME,0,-1);$B=lang(241);if(!$_POST["drop"]&&$th==$Xg&&connection()->flavor!="mysql")query_redirect(substr_replace($h,' OR REPLACE',6,0),$ag,$B);else{$Wk="adminer_".uniqid();drop_create("DROP $uj $th",$h,"DROP $uj $Xg",create_routine($uj,array("name"=>$Wk)+$J),"DROP $uj ".routine_id($Wk,$J),$ag,lang(242),$B,lang(243),$ca,$J["name"]);}}page_header(($ca!=""?(isset($_GET["function"])?lang(244):lang(245)).": ".h($ca):(isset($_GET["function"])?lang(246):lang(247))),$l);if(!$_POST){if($ca=="")$J["language"]="sql";else{$J=routine($_GET["procedure"],$uj);$J["name"]=$ca;}}$vb=(JUSH=="sql"?flat_collations():array());$vj=routine_languages();echo($vb?"<datalist id='collations'>".optionlist($vb)."</datalist>":""),'
<form action="" method="post" id="form">
<p>',lang(208),': <input name="name" value="',h($J["name"]),'" data-maxlength="64" autocapitalize="off">
',($vj?"<label>".lang(23).": ".html_select("language",$vj,$J["language"])."</label>\n":""),'<input type=\'submit\' value=\'',lang(17),'\'>
<div class="scrollable">
<table id="edit-fields" class="nowrap">
';edit_fields($J["fields"],$vb,$uj);if(isset($_GET["function"])){echo"<tr><td>".lang(248);edit_type("returns",(array)$J["returns"],$vb,array(),(JUSH=="pgsql"?array("void","trigger"):array()));}echo'</table>
',script("editFields();"),'</div>
<p>';textarea("definition",$J["definition"],20);echo'<p>
<input type=\'submit\' value=\'',lang(17),'\'>
';if($ca!="")echo'<input type=\'submit\' name=\'drop\' value=\'',lang(146),'\'',confirm(lang(198,$ca)),'>
';echo
input_token(),'</form>
';}elseif(isset($_GET["sequence"])){$ea=$_GET["sequence"];$J=$_POST;if($_POST&&!$l){$_=substr(ME,0,-1);$C=trim($J["name"]);if($_POST["drop"])query_redirect("DROP SEQUENCE ".idf_escape($ea),$_,lang(249));elseif($ea=="")query_redirect("CREATE SEQUENCE ".idf_escape($C),$_,lang(250));elseif($ea!=$C)query_redirect("ALTER SEQUENCE ".idf_escape($ea)." RENAME TO ".idf_escape($C),$_,lang(251));else
redirect($_);}page_header($ea!=""?lang(252).": ".h($ea):lang(253),$l);if(!$J)$J["name"]=$ea;echo'
<form action="" method="post">
<p><input name="name" value="',h($J["name"]),'" autocapitalize="off">
<input type=\'submit\' value=\'',lang(17),'\'>
';if($ea!="")echo"<input type='submit' name='drop' value='".lang(146)."'".confirm(lang(198,$ea)).">\n";echo
input_token(),'</form>
';}elseif(isset($_GET["type"])){function
enum_values($nc){$Y="'(?:[^']|'')*'";if(!preg_match('~^AS\s+ENUM\s*\(\s*('.$Y.'(?:\s*,\s*'.$Y.')*)\s*\)$~i',$nc,$A))return
null;preg_match_all('~'.$Y.'~',$A[1],$gg);return$gg[0];}function
add_enum_values($U,$rh,$Vg){$wh=enum_values($rh);$bh=enum_values($Vg);if($wh===null||$bh===null)return
null;$I=array();$s=0;foreach($bh
as$Y){if($Y===idx($wh,$s))$s++;else$I[]="ALTER TYPE ".idf_escape($U)." ADD VALUE $Y".($s<count($wh)?" BEFORE ".$wh[$s]:"");}return($s==count($wh)?$I:null);}$fa=$_GET["type"];$J=$_POST;$U=($fa!=""?type_definition(+array_search($fa,types(true))):array());$jh=($U["kind"]=='d'?"DOMAIN":"TYPE");if($_POST&&!$l){$_=substr(ME,0,-1);$C=trim($J["name"]);$Ca=trim(str_replace("\r","",$J["as"]));$Zg=(preg_match('~^AS\s+(?!ENUM\b|RANGE\b|\()~i',$Ca)?"DOMAIN":"TYPE");$B=lang(254);$b=(!$_POST["drop"]&&$fa!=""&&$Zg==$jh?($Ca==$U["definition"]?array():add_enum_values($fa,$U["definition"],$Ca)):null);if($b!==null){if($fa!=$C)$b[]="ALTER $jh ".idf_escape($fa)." RENAME TO ".idf_escape($C);if(!$b)redirect($_);$ud=false;foreach($b
as$G){if(!queries($G)){$ud=true;break;}}queries_redirect($_,$B,!$ud);}else
drop_create("DROP $jh ".idf_escape($fa),"CREATE $Zg ".idf_escape($C)." $Ca","","","",$_,lang(255),$B,lang(256),$fa,$C);}page_header($fa!=""?lang(257).": ".h($fa):lang(258),$l);if(!$J){$J["name"]=$fa;$J["as"]=($fa!=""?$U["definition"]:"AS ");}echo'
<form action="" method="post">
<p>
',lang(208).": <input name='name' value='".h($J['name'])."' autocapitalize='off'>\n",doc_link(array('pgsql'=>"sql-createtype.html",),"?");textarea("as",$J["as"]);echo"<p><input type='submit' value='".lang(17)."'>\n";if($fa!="")echo"<input type='submit' name='drop' value='".lang(146)."'".confirm(lang(198,$fa)).">\n";echo
input_token(),'</form>
';}elseif(isset($_GET["check"])){$a=$_GET["check"];$C=$_GET["name"];$J=$_POST;if($J&&!$l){if(JUSH=="sqlite")$H=recreate_table($a,$a,array(),array(),array(),"",array(),"$C",($J["drop"]?"":$J["clause"]));else{$H=($C==""||queries("ALTER TABLE ".table($a)." DROP CONSTRAINT ".idf_escape($C)));if(!$J["drop"])$H=queries("ALTER TABLE ".table($a)." ADD".($J["name"]!=""?" CONSTRAINT ".idf_escape($J["name"]):"")." CHECK ($J[clause])");}queries_redirect(ME."table=".url_escape($a),($J["drop"]?lang(259):($C!=""?lang(260):lang(261))),$H);}page_header(($C!=""?lang(262):lang(161)),$l,array("table"=>$a),h($C!=""?$C:$a));if(!$J){$lb=driver()->checkConstraints($a);$J=array("name"=>$C,"clause"=>$lb[$C]);}echo'
<form action="" method="post">
<p>';if(JUSH!="sqlite")echo
lang(208).': <input name="name" value="'.h($J["name"]).'" data-maxlength="64" autocapitalize="off"> ';echo
doc_link(array('sql'=>"create-table-check-constraints.html",'mariadb'=>"constraint/",'pgsql'=>"ddl-constraints.html#DDL-CONSTRAINTS-CHECK-CONSTRAINTS",'mssql'=>"relational-databases/tables/create-check-constraints",'sqlite'=>"lang_createtable.html#check_constraints",),"?"),'<p>';textarea("clause",$J["clause"]);echo'<p><input type=\'submit\' value=\'',lang(17),'\'>
';if($C!="")echo'<input type=\'submit\' name=\'drop\' value=\'',lang(146),'\'',confirm(lang(198,$C)),'>
';echo
input_token(),'</form>
';}elseif(isset($_GET["trigger"])){$a=$_GET["trigger"];$C="$_GET[name]";$wl=trigger_options();$J=(array)trigger($C,$a)+array("Trigger"=>$a."_bi");if($_POST){if(!$l&&in_array($_POST["Timing"],$wl["Timing"])&&in_array($_POST["Event"],$wl["Event"])&&in_array($_POST["Type"],$wl["Type"])){$xh=" ON ".table($a);$Hc="DROP TRIGGER ".idf_escape($C).(JUSH=="pgsql"?$xh:"");$ag=ME."table=".url_escape($a);if($_POST["drop"])query_redirect($Hc,$ag,lang(263));else{if($C!="")queries($Hc);queries_redirect($ag,($C!=""?lang(264):lang(265)),queries(create_trigger($xh,$_POST)));if($C!="")queries(create_trigger($xh,$J+array("Type"=>reset($wl["Type"]))));}}$J=$_POST;}page_header(($C!=""?lang(266):lang(163)),$l,array("table"=>$a),h($C!=""?$C:$a));$ul=on('change','triggerChange',"^".preg_quote($a,"/")."_[ba][iud]$",$a);echo'
<form action="" method="post" id="form">
<table class="layout">
<tr><th>',lang(267),'<td>',html_select("Timing",$wl["Timing"],$J["Timing"],$ul),'<tr><th>',lang(268),'<td>',html_select("Event",$wl["Event"],$J["Event"],$ul),(in_array("UPDATE OF",$wl["Event"])?" <input name='Of' value='".h($J["Of"])."' class='hidden'>":""),'<tr><th>',lang(56),'<td>',html_select("Type",$wl["Type"],$J["Type"]),'<tr><th>',lang(208),'<td><input name="Trigger" value="',h($J["Trigger"]),'" data-maxlength="64" autocapitalize="off">
</table>
',script("fire(qs('#form')['Timing'], 'change');"),'<p>';textarea("Statement",$J["Statement"]);echo'<p>
<input type=\'submit\' value=\'',lang(17),'\'>
';if($C!="")echo'<input type=\'submit\' name=\'drop\' value=\'',lang(146),'\'',confirm(lang(198,$C)),'>
';echo
input_token(),'</form>
';}elseif(isset($_GET["user"])){function
grant($de,array$Pi,$e,$xh){if(!$Pi)return
true;if($Pi==array("ALL PRIVILEGES","GRANT OPTION"))return($de=="GRANT"?queries("$de ALL PRIVILEGES$xh WITH GRANT OPTION"):queries("$de ALL PRIVILEGES$xh")&&queries("$de GRANT OPTION$xh"));return
queries("$de ".preg_replace('~(GRANT OPTION)\([^)]*\)~','\1',implode("$e, ",$Pi).$e).$xh);}$ga=$_GET["user"];$Pi=array(""=>array("All privileges"=>""));foreach(get_rows("SHOW PRIVILEGES")as$J){foreach(explode(",",($J["Privilege"]=="Grant option"?"":$J["Context"]))as$Nb)$Pi[$Nb=="File access on server"?"Server Admin":$Nb][$J["Privilege"]]=$J["Comment"];}unset($Pi["Server Admin"]["Usage"]);foreach($Pi["Tables"]as$x=>$X)unset($Pi["Databases"][$x]);$Wg=array();if($_POST){foreach($_POST["objects"]as$x=>$X)$Wg[$X]=(array)$Wg[$X]+idx($_POST["grants"],$x,array());}$ee=array();if(isset($_GET["host"])&&($H=connection()->query("SHOW GRANTS FOR ".q($ga)."@".q($_GET["host"])))){while($J=$H->fetch_row()){if(preg_match('~GRANT (.*) ON (.*) TO ~',$J[0],$A)&&preg_match_all('~ *([^(,]*[^ ,(])( *\([^)]+\))?~',$A[1],$gg,PREG_SET_ORDER)){foreach($gg
as$X){if($X[1]!="USAGE")$ee["$A[2]$X[2]"][$X[1]]=true;if(preg_match('~ WITH GRANT OPTION~',$J[0]))$ee["$A[2]$X[2]"]["GRANT OPTION"]=true;}}}}if($_POST&&!$l){$vh=(isset($_GET["host"])?q($ga)."@".q($_GET["host"]):"''");if($_POST["drop"])query_redirect("DROP USER $vh",ME."privileges=",lang(269));else{$ah=q($_POST["user"])."@".q($_POST["host"]);$ni=$_POST["pass"];$Ub=false;$H=true;if($vh!=$ah){$Ub=queries("CREATE USER $ah IDENTIFIED BY ".($_POST["hashed"]?"PASSWORD ":"").q($ni));$H=$Ub;}elseif($ni!="")$H=queries("SET PASSWORD FOR $ah = ".(min_version(8,99)||$_POST["hashed"]?q($ni):"PASSWORD(".q($ni).")"));if($H){$qj=array();foreach($Wg
as$jh=>$de){if(isset($_GET["grant"]))$de=array_filter($de);$de=array_keys($de);if(isset($_GET["grant"]))$qj=array_diff(array_keys(array_filter($Wg[$jh],'strlen')),$de);elseif($vh==$ah){$sh=array_keys((array)$ee[$jh]);$qj=array_diff($sh,$de);$de=array_diff($de,$sh);unset($ee[$jh]);}if(preg_match('~^(.+)\s*(\(.*\))?$~U',$jh,$A)&&(!grant("REVOKE",$qj,$A[2]," ON $A[1] FROM $ah")||!grant("GRANT",$de,$A[2]," ON $A[1] TO $ah"))){$H=false;break;}}}if($H&&isset($_GET["host"])){if($vh!=$ah)queries("DROP USER $vh");elseif(!isset($_GET["grant"])){foreach($ee
as$jh=>$qj){if(preg_match('~^(.+)(\(.*\))?$~U',$jh,$A))grant("REVOKE",array_keys($qj),$A[2]," ON $A[1] FROM $ah");}}}if($H&&!Queries::$queries)redirect(ME."privileges=");queries_redirect(ME."privileges=",(isset($_GET["host"])?lang(270):lang(271)),$H);if($Ub)connection()->query("DROP USER $ah");}}page_header((isset($_GET["host"])?lang(40).": ".h("$ga@$_GET[host]"):lang(170)),$l,array("privileges"=>array('',lang(78))));$J=$_POST;if($J)$ee=$Wg;else{$J=$_GET+array("host"=>get_val("SELECT SUBSTRING_INDEX(CURRENT_USER, '@', -1)"));$ee[(DB==""||$ee?"":idf_escape(addcslashes(DB,"%_\\"))).".*"]=array();}echo'<form action="" method="post">
<table class="layout">
<tr><th>',lang(38),'<td><input name="host" data-maxlength="60" value="',h($J["host"]),'" autocapitalize="off">
<tr><th>',lang(40),'<td><input name="user" data-maxlength="80" value="',h($J["user"]),'" autocapitalize="off">
<tr><th>',lang(41),'<td><input name="pass" id="pass" value="',h($J["pass"]),'" autocomplete="new-password">
',($J["hashed"]?"":script("typePassword(qs('#pass'));")),(min_version(8,99)?"":checkbox("hashed",1,$J["hashed"],lang(272),on('click','hashedClick'))),'</table>

',"<table class='odds'>\n","<thead><tr><th colspan='2'>".lang(78).doc_link(array('sql'=>"grant.html#priv_level"));$s=0;foreach($ee
as$jh=>$de){echo'<th>'.($jh!="*.*"?"<input name='objects[$s]' value='".h($jh)."' size='10' autocapitalize='off'>":input_hidden("objects[$s]","*.*")."*.*");$s++;}echo"<tbody>\n";foreach(array(""=>"","Server Admin"=>lang(38),"Databases"=>lang(42),"Tables"=>lang(152),"Procedures"=>lang(273),)as$Nb=>$qc){foreach((array)$Pi[$Nb]as$Oi=>$_b){echo"<tr><td".($qc?">$qc<td":" colspan='2'").' lang="en" title="'.h($_b).'">'.h($Oi);$s=0;foreach($ee
as$jh=>$de){$C="'grants[$s][".h(strtoupper($Oi))."]'";$Y=$de[strtoupper($Oi)];if($Nb=="Server Admin"&&$jh!=(isset($ee["*.*"])?"*.*":".*"))echo"<td>";elseif(isset($_GET["grant"]))echo"<td><select name=$C><option><option value='1'".($Y?" selected":"").">".lang(274)."<option value='0'".($Y=="0"?" selected":"").">".lang(275)."</select>";else
echo"<td align='center'><label class='block'>","<input type='checkbox' name=$C value='1'".($Y?" checked":"").($Oi=="All privileges"?" id='grants-$s-all'":($Oi=="Grant option"?"":on('click','grantsClick',"grants-$s-all"))).">","</label>";$s++;}}}echo"</table>\n",'<p>
<input type=\'submit\' value=\'',lang(17),'\'>
';if(isset($_GET["host"]))echo'<input type=\'submit\' name=\'drop\' value=\'',lang(146),'\'',confirm(lang(198,"$ga@$_GET[host]")),'>
';echo
input_token(),'</form>
';}elseif(isset($_GET["processlist"])){if(support("kill")){if($_POST&&!$l){$Cf=0;foreach((array)$_POST["kill"]as$X){if(adminer()->killProcess($X))$Cf++;}queries_redirect(ME."processlist=",lang(276,$Cf),$Cf||!$_POST["kill"]);}}page_header(lang(135),$l);echo'
<form action="" method="post">
<div class="scrollable">
<table class="nowrap checkable odds"',on('click','tableClick').on('dblclick','tableClick'),'>
';$s=-1;foreach(adminer()->processList()as$s=>$J){if(!$s){echo"<thead><tr lang='en'>".(support("kill")?"<td class='hover'>":"");foreach($J
as$x=>$X)echo"<th>$x".doc_link(array('sql'=>"show-processlist.html#processlist_".strtolower($x),'pgsql'=>"monitoring-stats.html#PG-STAT-ACTIVITY-VIEW",'oracle'=>"REFRN30223",));echo"<tbody>\n";}echo"<tr>".(support("kill")?"<td class='hover'>".checkbox("kill[]",$J[JUSH=="sql"?"Id":"pid"],0):"");foreach($J
as$x=>$X)echo"<td>".($X!=""&&((JUSH=="sql"&&$x=="Info"&&preg_match("~Query|Killed~",$J["Command"]))||(JUSH=="pgsql"&&$x=="query")||(JUSH=="oracle"&&$x=="sql_text"))?"<code class='jush-".JUSH."' data-full='".h($X)."'>".shorten_utf8($X,100,"</code>").' <a href="'.h(($J["db"]!=""?preg_replace('~&db=[^&]*~','',ME)."db=".url_escape($J["db"])."&":ME)."sql=".url_escape($X)).'">'.lang(277).'</a>'.' '.copy_icon():h($X));echo"\n";}echo'</table>
</div>
<p>
',script("copyCode(qsl('table'));");if(support("kill"))echo($s+1)."/".lang(278,max_connections()),"<p><input type='submit' value='".lang(279)."'>\n";echo
input_token(),'</form>
',script("tableCheck();");}elseif($_GET["select"]!=""){$a=$_GET["select"];$S=table_status1($a);$w=indexes($a);$n=fields($a);$Rd=column_foreign_keys($a);$qh=$S["Oid"];$ra=get_settings("adminer_import");$sj=array();$e=array();$Ej=array();$Jh=array();$Zk=null;foreach($n
as$x=>$m){$C=adminer()->fieldName($m);$Rg=html_entity_decode(strip_tags($C),ENT_QUOTES);if(isset($m["privileges"]["select"])&&$C!=""){$e[$x]=$Rg;if(is_shortable($m))$Zk=adminer()->selectLengthProcess();}if(isset($m["privileges"]["where"])&&$C!="")$Ej[$x]=$Rg;if(isset($m["privileges"]["order"])&&$C!="")$Jh[$x]=$Rg;$sj+=$m["privileges"];}list($M,$ge)=adminer()->selectColumnsProcess($e,$w);$M=array_unique($M);$ge=array_unique($ge);$qf=count($ge)<count($M);$Z=adminer()->selectSearchProcess($n,$w);$Ih=adminer()->selectOrderProcess($n,$w);$z=adminer()->selectLimitProcess();if($_GET["val"]&&is_ajax()){header("Content-Type: text/plain; charset=utf-8");foreach($_GET["val"]as$Gl=>$J){$Ca=convert_field($n[key($J)]);$M=array($Ca?:idf_escape(key($J)));$Z[]=where_check(bracket_escape($Gl,true),$n);$I=driver()->select($a,$M,$Z,$M);if($I)echo
first($I->fetch_row());}exit;}$Ki=$Il=array();foreach($w
as$v){if($v["type"]=="PRIMARY"){$Ki=array_flip($v["columns"]);$Il=($M?$Ki:array());foreach($Il
as$x=>$X){if(in_array(idf_escape($x),$M))unset($Il[$x]);}break;}}if($qh&&!$Ki){$Ki=$Il=array($qh=>0);$w[]=array("type"=>"PRIMARY","columns"=>array($qh));}if($_POST&&!$l){$qm=$Z;if(!$_POST["all"]&&is_array($_POST["check"])){$lb=array();foreach($_POST["check"]as$hb)$lb[]=where_check($hb,$n);$qm[]="((".implode(") OR (",$lb)."))";}$sm=$qm;$qm=($qm?"\nWHERE ".implode(" AND ",$qm):"");if($_POST["export"]){save_settings(array("output"=>$_POST["output"],"format"=>$_POST["format"]),"adminer_import");dump_headers($a);adminer()->dumpTable($a,"");$Gj=($M?:array("*"));$Pb=convert_fields($e,$n,$M);if($Pb)$Gj[]=substr($Pb,2);$G="";if(is_array($_POST["check"])&&!$Ki){$Wd=implode(", ",$Gj)."\nFROM ".table($a);$ie=($ge&&$qf?"\nGROUP BY ".implode(", ",$ge):"").($Ih?"\nORDER BY ".implode(", ",$Ih):"");$El=array();foreach($_POST["check"]as$X)$El[]="(SELECT".limit($Wd,"\nWHERE ".($Z?implode(" AND ",$Z)." AND ":"").where_check($X,$n).$ie,1).")";$G=implode(" UNION ALL ",$El);}adminer()->dumpData($a,"table",$G,$Gj,$sm,($qf?$ge:array()),$Ih);adminer()->dumpFooter();exit;}if(!adminer()->selectEmailProcess($Z,$Rd)){if($_POST["save"]||$_POST["delete"]){$H=true;$sa=0;$Ra=false;$O=array();if(!$_POST["delete"]){foreach($n
as$C=>$X){$u=bracket_escape($C);if(isset($_POST["fields"][$u])||$_FILES["fields-$u"]){$X=process_input($n[$C]);if($X!==null&&($_POST["clone"]||$X!==false))$O[idf_escape($C)]=($X!==false?$X:idf_escape($C));}}}if($_POST["delete"]||$O){$G=($_POST["clone"]?"INTO ".table($a)." (".implode(", ",array_keys($O)).")\nSELECT ".implode(", ",$O)."\nFROM ".table($a):"");if($_POST["all"]||($Ki&&is_array($_POST["check"]))||$qf){$H=($_POST["delete"]?driver()->delete($a,$qm):($_POST["clone"]?queries("INSERT $G$qm".driver()->insertReturning($a)):driver()->update($a,$O,$qm)));$sa=connection()->affected_rows;if(is_object($H))$sa+=$H->num_rows;}else{$Ra=count((array)$_POST["check"])>1&&driver()->begin();foreach((array)$_POST["check"]as$X){$pm="\nWHERE ".($Z?implode(" AND ",$Z)." AND ":"").where_check($X,$n);$H=($_POST["delete"]?driver()->delete($a,$pm,1):($_POST["clone"]?queries("INSERT".limit1($a,$G,$pm)):driver()->update($a,$O,$pm,1)));if(!$H)break;$sa+=connection()->affected_rows;}if($Ra&&$H&&!driver()->commit())$H=false;}}$B=lang(280,$sa);if($_POST["clone"]&&$H&&$sa==1){$Lf=last_id($H);if($Lf)$B=lang(191," $Lf");}queries_redirect(remove_from_uri($_POST["all"]&&$_POST["delete"]?"page|next":""),$B,$H);if($Ra)driver()->rollback();if(!$_POST["delete"]){$Ci=(array)$_POST["fields"];edit_form($a,array_intersect_key($n,$Ci),$Ci,!$_POST["clone"],$l);page_footer();exit;}}elseif(!$_POST["import"]){$H=true;$sa=0;$Ra=count((array)$_POST["val"])>1&&driver()->begin();foreach((array)$_POST["val"]as$Gl=>$J){$O=array();foreach($J
as$x=>$X){$x=bracket_escape($x,true);$O[idf_escape($x)]=(preg_match('~char|text~',$n[$x]["type"])||$X!=""?adminer()->processInput($n[$x],$X):"NULL");}$H=driver()->update($a,$O," WHERE ".($Z?implode(" AND ",$Z)." AND ":"").where_check(bracket_escape($Gl,true),$n),($qf||$Ki?0:1)," ");if(!$H)break;$sa+=connection()->affected_rows;}if($Ra)$H=$H&&driver()->commit();queries_redirect(remove_from_uri(),lang(280,$sa),$H);if($Ra)driver()->rollback();}elseif(!is_string($Bd=get_file("csv_file",true)))$l=upload_error($Bd);elseif(!preg_match('~~u',$Bd))$l=lang(281);else{save_settings(array("output"=>$ra["output"],"format"=>$_POST["separator"]),"adminer_import");$wb=array_keys($n);$Lj=($_POST["separator"]=="csv"?",":($_POST["separator"]=="tsv"?"\t":";"));$Yb=parse_csv($Bd,$Lj);$sa=count($Yb);driver()->begin();$K=array();foreach($Yb
as$x=>$em){if(!$x&&!array_diff($em,$wb)){$wb=$em;$sa--;}else{$O=array();foreach($em
as$s=>$sb)$O[idf_escape($wb[$s])]=($sb==""&&$n[$wb[$s]]["null"]?"NULL":q(csv_value($sb)));$K[]=$O;}}$H=(!$K||driver()->insertUpdate($a,$K,$Ki));if($H)driver()->commit();queries_redirect(remove_from_uri("page|next"),lang(282,$sa),$H);driver()->rollback();}}}$Gk=adminer()->tableName($S);if(is_ajax()){page_headers();ob_start();}else
page_header(lang(60).": $Gk",$l);$O=null;if(isset($sj["insert"])||!support("table")){$O="";foreach((array)$_GET["where"]as$X){$Y=$X["val"];if(is_array($Y))$Y=(count($Y)==1&&preg_match('~^val-(.*)~s',reset($Y),$A)?$A[1]:"");if($X["col"]!=""&&$Y!=""&&($X["op"]=="="||(!$X["op"]&&(is_array($X["val"])||!preg_match('~[_%]~',$Y)))))$O
.="&set[".url_escape(bracket_escape($X["col"]))."]=".url_escape($Y);}}adminer()->selectLinks($S,$O);if(!$e&&support("table"))echo"<p class='error'>".lang(283).($n?".":": ".adminer()->error())."\n";else{echo"<form action='' id='form'>\n","<div hidden>";hidden_fields_get();echo(DB!=""?input_hidden("db",DB).(isset($_GET["ns"])?input_hidden("ns",$_GET["ns"]):""):""),input_hidden("select",$a),"</div>\n";adminer()->selectColumnsPrint($M,$e);adminer()->selectSearchPrint($Z,$Ej,$w);adminer()->selectOrderPrint($Ih,$Jh,$w);adminer()->selectLimitPrint($z);if($Zk!==null)adminer()->selectLengthPrint($Zk);adminer()->selectActionPrint($w);echo"</form>\n";foreach((array)$_GET["where"]as$X){if($X["op"]=="SQL"&&!in_array($_SERVER["HTTP_SEC_FETCH_SITE"],array("","same-origin"))){echo"<p class='error'>".lang(116).' '.lang(117)."\n";page_footer();exit;}}$D=$_GET["page"];$Ud=null;if($D=="last"){$Ud=get_val(count_rows($a,$Z,$qf,$ge));$D=floor(max(0,intval($Ud)-1)/$z);}$Fj=$M;$he=$ge;if(!$Fj){$Fj[]="*";$Pb=convert_fields($e,$n,$M);if($Pb)$Fj[]=substr($Pb,2);}foreach($M
as$x=>$X){$m=$n[idf_unescape($X)];if($m&&($Ca=convert_field($m)))$Fj[$x]="$Ca AS $X";}if(JUSH=="pgsql"||JUSH=="mssql"){foreach((array)$_GET["columns"]as$x=>$X){if(isset($Fj[$x])&&$X["fun"])$Fj[$x].=" AS ".idf_escape(apply_sql_function($X["fun"],($X["col"]!=""?$X["col"]:"*")));}}if(!$qf&&$Il){foreach($Il
as$x=>$X){$Fj[]=idf_escape($x);if($he)$he[]=idf_escape($x);}}$H=driver()->select($a,$Fj,$Z,$he,$Ih,$z,$D,true);if(!is_object($H))echo"<p class='error'>".(adminer()->error()?:lang(25))."\n";else{if(JUSH=="mssql"&&$D)$H->seek($z*$D);$Tc=array();$K=array();while($J=$H->fetch_assoc()){if($D&&JUSH=="oracle")unset($J["RNUM"]);$K[]=$J;}$se=($z&&(support("cursor")?$_GET["next"]!="":count($K)>=$z));if(is_ajax()&&$se)header("X-Next-Page: ".pagination_href($D+1));if($_GET["modify"]&&$K){$pg=max_input_vars(count($K[0])+1,20);echo($pg&&count($K)>$pg?"<p class='error'>".max_input_vars_error()."\n":"");}echo"<form action='' method='post' enctype='multipart/form-data'".on_upload_progress($Nl).">\n";if($_GET["page"]!="last"&&$z&&$ge&&$qf&&JUSH=="sql")$Ud=get_val(" SELECT FOUND_ROWS()");if(!$K)echo"<p class='message'>".lang(15)."\n";else{$Na=adminer()->backwardKeys($a,$Gk);echo"<div class='scrollable'>","<table id='table' class='nowrap checkable odds'".on('click','tableClick').on('dblclick','tableClick').on('keydown','editingKeydown').">\n","<thead><tr>".(!$ge&&$M?"":"<td class='hover check'><input type='checkbox' id='all-page' class='jsonly' title='".lang(284)."'".on('click','formCheck','^check').">");$Sg=array();$ae=array();reset($M);$Zi=1;foreach($K[0]as$x=>$X){if(!isset($Il[$x])){$X=idx($_GET["columns"],key($M))?:array();$m=$n[$M?($X?$X["col"]:current($M)):$x];$C=($m?adminer()->fieldName($m,$Zi):($X["fun"]?"*":h($x)));if($C!=""){$Zi++;$Sg[$x]=$C;$d=idf_escape($x);$Fe=remove_from_uri('(order|desc)[^=]*|page|next').'&order[0]='.url_escape($x);$qc="&desc[0]=1";$ck=preg_replace('~ DESC( NULLS LAST)?$~','',$Ih[0]);$ek=($ck==$d||$ck==$x);echo"<th id='th[".h(bracket_escape($x))."]'".($ek?" aria-sort='".($ck==$Ih[0]?"ascending":"descending")."'":"").">";$Zd=apply_sql_function($X["fun"],$C);$dk=isset($m["privileges"]["order"])||$Zd!=$C;echo($dk?"<a href='".h($Fe.($ek&&$ck==$Ih[0]?$qc:''))."'>$Zd</a>":$Zd);$xg=($dk?"<a href='".h($Fe.$qc)."' title='".lang(66)."' class='text'> ↓</a>":'');if(!$X["fun"]&&isset($m["privileges"]["where"]))$xg
.="<a href='#fieldset-search' title='".lang(63)."' class='text jsonly'".on('click','selectSearch',$x)."> =</a>";echo($xg?"<span class='column'>$xg</span>":"");}$ae[$x]=$X["fun"];next($M);}}$Sf=array();if($_GET["modify"]){foreach($K
as$J){foreach($J
as$x=>$X)$Sf[$x]=max($Sf[$x],min(40,strlen(utf8_decode($X))));}}echo($Na?"<th>".lang(285):"")."<tbody>\n";if(is_ajax())ob_end_clean();foreach(adminer()->rowDescriptions($K,$Rd)as$Pg=>$J){$Fl=unique_array($K[$Pg],$w);if(!$Fl){$Fl=array();reset($M);foreach($K[$Pg]as$x=>$X){if(!preg_match('~^(COUNT|AVG|GROUP_CONCAT|MAX|MIN|SUM)\(~',current($M)))$Fl[$x]=$X;next($M);}}$Gl="";foreach($Fl
as$x=>$X){$m=(array)$n[$x];$pf=is_blob($m);if((JUSH=="sql"||JUSH=="pgsql")&&($pf||preg_match('~'.text_type().'~',$m["type"]))&&strlen($X)>64){$x=(strpos($x,'(')?$x:idf_escape($x));$x="MD5(".($pf||JUSH!='sql'||preg_match("~^utf8~",$m["collation"])?$x:"CONVERT($x USING ".charset(connection()).")").")";$X=md5($pf?(string)driver()->value($X,$m):$X);}$Gl
.="&".($X!==null?"where[".url_escape(bracket_escape($x))."]=".url_escape($X===false?"f":$X):"null[]=".url_escape($x));}echo"<tr>".(!$ge&&$M?"":"<td class='hover check'>".($qf||information_schema(DB)?"":"<a href='".h(ME."edit=".url_escape($a).$Gl)."' class='edit'>".lang(286)."</a> ").checkbox("check[]",substr($Gl,1),in_array(substr($Gl,1),(array)$_POST["check"])));reset($M);foreach($J
as$x=>$X){if(isset($Sg[$x])){$d=current($M);$m=(array)$n[$x];if($X!=""&&(!isset($Tc[$x])||$Tc[$x]!=""))$Tc[$x]=(is_mail($X)?$Sg[$x]:"");$_="";if(is_blob($m)&&$X!="")$_=ME.'download='.url_escape($a).'&field='.url_escape($x).$Gl;if(!$_&&$X!==null){foreach((array)$Rd[$x]as$p){if(count($Rd[$x])==1||end($p["source"])==$x){$_="";foreach($p["source"]as$s=>$fk)$_
.=where_link($s,$p["target"][$s],$K[$Pg][$fk]);$_=($p["db"]!=""?preg_replace('~([?&]db=)[^&]+~','\1'.url_escape($p["db"]),ME):ME).'select='.url_escape($p["table"]).$_;if($p["ns"])$_=preg_replace('~([?&]ns=)[^&]+~','\1'.url_escape($p["ns"]),$_);if(count($p["source"])==1)break;}}}if($d=="COUNT(*)"){$_=ME."select=".url_escape($a);$s=0;foreach((array)$_GET["where"]as$W){if(!array_key_exists($W["col"],$Fl))$_
.=where_link($s++,$W["col"],$W["val"],$W["op"]);}foreach($Fl
as$zf=>$W)$_
.=where_link($s++,$zf,$W);}$Ge=select_value($X,$_,$m,$Zk);$u=bracket_escape($Gl);$t=h("val[$u][".bracket_escape($x)."]");$Ei=idx(idx($_POST["val"],$u),bracket_escape($x));$Ll=idx($m["privileges"],"update");$Pc=!is_array($J[$x])&&!is_blob($m)&&is_utf8($X)&&$K[$Pg][$x]==$X&&!$ae[$x]&&!$m["generated"]&&$Ll;$U=(preg_match('~^(AVG|MIN|MAX)\((.+)\)~',$d,$A)?$n[idf_unescape($A[2])]["type"]:$m["type"]);$Yk=preg_match('~text|json|lob~',$U);$rf=preg_match(number_type(),$U)||preg_match('~^(CHAR_LENGTH|ROUND|FLOOR|CEIL|TIME_TO_SEC|COUNT|SUM)\(~',$d);echo"<td id='$t'".($rf&&($X===null||is_numeric(strip_tags($Ge))||$U=="money")?" class='number'":"");if(($_GET["modify"]&&$Pc&&$X!==null)||$Ei!==null){$ne=h($Ei!==null?$Ei:$X);echo">".($Yk?"<textarea name='$t' cols='30' rows='".(substr_count($X,"\n")+1)."'>$ne</textarea>":"<input name='$t' value='$ne' size='$Sf[$x]'>");}else{$cg=strpos($Ge,"<i>…</i>");echo($Ll?" data-text='".($cg?2:($Yk?1:0))."'".($Pc?"":" data-warning='".lang(287)."'"):"").">$Ge";}}next($M);}if($Na)echo"<td>";adminer()->backwardKeysPrint($Na,$K[$Pg]);echo"</tr>\n";}if(is_ajax())exit;echo"</table>\n","</div>\n";}if(!is_ajax()){if($K||$D||$se){$id=true;if($_GET["page"]!="last"){if(!$z||(count($K)<$z&&($K||!$D)))$Ud=($D?$D*$z:0)+count($K);elseif(JUSH!="sql"||!$qf){$Ud=($qf?false:found_rows($S,$Z));if(intval($Ud)<max(1e4,2*($D+1)*$z))$Ud=first(slow_query(count_rows($a,$Z,$qf,$ge)));elseif(JUSH=='sql'||JUSH=='pgsql')$id=false;}}if(!support("cursor"))$se=(($Ud===false?count($K)+1:$Ud-$D*$z)>$z);$ai=($z&&($se||$D));if($ai)echo($se?'<p><a href="'.h(pagination_href($D+1)).'" class="loadmore"'.on('click','selectLoadMore',lang(288)).'>'.lang(289).'</a>':''),"\n";echo"<div class='footer'><div>\n";if($ai){$ng=($Ud===false?$D+($K?(count($K)>=$z?2:1):0):floor(($Ud-1)/$z));echo"<fieldset><legend>".lang(290)."</legend>";if(!support("cursor")){echo
pagination(0,$D).($D>5?" …":"");for($s=max(1,$D-4);$s<min($ng,$D+5);$s++)echo
pagination($s,$D);if($ng>0)echo($D+5<$ng?" …":""),($id&&$Ud!==false?pagination($ng,$D):" <a href='".h(remove_from_uri("page")."&page=last")."' title='~$ng'>".lang(291)."</a>");}else
echo
pagination(0,$D).($D>1?" …":""),($D?pagination($D,$D):""),($se?pagination($D+1,$D)." …":"");echo"</fieldset>\n";}echo"<fieldset>","<legend>".lang(292)."</legend>";$yc=($id?"":"~ ").$Ud;$Ff=($Ud!==false?($id?"":"~ ").lang(174,$Ud):"");echo
checkbox("all",1,0,$Ff,on('click','countRows',$yc))."\n","</fieldset>\n";if(adminer()->selectCommandPrint())echo'<fieldset',($_GET["modify"]?'':" title='".lang(293)."'"),'>
<legend><a href=\'',h($_GET["modify"]?remove_from_uri("modify"):relative_uri()."&modify=1"),'\'>',lang(294),'</a></legend><div>
<input type=\'submit\' id=\'save\' value=\'',lang(17),'\'',($_GET["modify"]?'':" class='jsonly' disabled"),'>
</div></fieldset>

<fieldset><legend>',lang(145),' <span id="selected"></span></legend><div>
<input type=\'submit\' name=\'edit\' value=\'',lang(13),'\'>
<input type=\'submit\' name=\'clone\' value=\'',lang(277),'\'>
<input type=\'submit\' name=\'delete\' value=\'',lang(21),'\'',confirm(),'>
</div></fieldset>
';$Sd=adminer()->dumpFormat();foreach((array)$_GET["columns"]as$d){if($d["fun"]){unset($Sd['sql']);break;}}if($Sd){print_fieldset("export",lang(83)." <span id='selected2'></span>");$Wh=adminer()->dumpOutput();echo($Wh?html_select("output",$Wh,$ra["output"])." ":""),html_select("format",$Sd,$ra["format"])," <input type='submit' name='export' value='".lang(83)."'>\n","</div></fieldset>\n";}adminer()->selectEmailPrint(array_filter($Tc,'strlen'),$e);echo"</div></div>\n";}if(adminer()->selectImportPrint())echo"<p>","<a href='#import' class='toggle'>".lang(82)."</a>","<span id='import'".($_POST["import"]?"":" class='hidden'").">: ",($Nl?input_hidden(ini_get("session.upload_progress.name"),$Nl):""),file_input(" name='csv_file'"," ".html_select("separator",array("csv"=>"CSV,","csv;"=>"CSV;","tsv"=>"TSV"),$ra["format"])." <input type='submit' name='import' value='".lang(82)."'>".($Nl?" <progress class='jsonly hidden' max='1' value='0'></progress>":"")),"</span>";echo
input_token(),"</form>\n",(!$ge&&$M?"":script("tableCheck();"));}}}if(is_ajax()){ob_end_clean();exit;}}elseif(isset($_GET["variables"])){$P=isset($_GET["status"]);page_header($P?lang(137):lang(136));$fm=($P?adminer()->showStatus():adminer()->showVariables());if(!$fm)echo"<p class='message'>".lang(15)."\n";else{echo"<table>\n";foreach($fm
as$J){echo"<tr>";$x=array_shift($J);echo"<th><code class='jush-".JUSH.($P?"status":"set")."'>".h($x)."</code>";foreach($J
as$X)echo"<td>".nl_br(h($X));}echo"</table>\n";}}elseif(isset($_GET["script"])){header("Content-Type: application/json; charset=utf-8");if($_GET["script"]=="db"){$zk=array("Data_length"=>0,"Index_length"=>0,"Data_free"=>0);foreach(table_status()as$C=>$S){json_row("Comment-$C",h($S["Comment"]));if(!is_view($S)||preg_match('~materialized~i',$S["Engine"])){foreach(array("Engine","Collation")as$x)json_row("$x-$C",h($S[$x]));foreach(array_keys($zk+array("Auto_increment"=>0,"Rows"=>0))as$x){if(array_key_exists($x,$S))json_row("$x-$C",format_status($S,$x));if($S[$x]!=""&&isset($zk[$x]))$zk[$x]+=($S["Engine"]!="InnoDB"||$x!="Data_free"?$S[$x]:0);}}}if(function_exists('Adminer\db_status'))$zk=db_status();foreach($zk
as$x=>$X)json_row("sum-$x",format_number($X));json_row("");}elseif($_GET["script"]=="kill"){if(!$l)connection()->query("KILL ".number($_POST["kill"]));}else{foreach(count_tables(adminer()->databases(false))as$j=>$X){json_row("tables-$j",$X);json_row("size-$j",db_size($j));}json_row("");}exit;}else{if(!isset($_GET["select"])&&support("single_table")){$T=tables_list();if($T)redirect(ME.(support("table")?"table=":"select=").url_escape(key($T)));}$ug=ME.(isset($_GET["select"])?"select=&":"");$Qk=array_merge((array)$_POST["tables"],(array)$_POST["views"]);if($Qk&&!$l&&!$_POST["search"]){$H=true;$B="";if(JUSH=="sql"&&$_POST["tables"]&&count($_POST["tables"])>1&&($_POST["drop"]||$_POST["truncate"]||$_POST["copy"]))queries("SET foreign_key_checks = 0");if($_POST["truncate"]){if($_POST["tables"])$H=truncate_tables($_POST["tables"]);$B=lang(295);}elseif($_POST["move"]){$H=move_tables((array)$_POST["tables"],(array)$_POST["views"],$_POST["target"]);$B=lang(296);}elseif($_POST["copy"]){$H=copy_tables((array)$_POST["tables"],(array)$_POST["views"],$_POST["target"]);$B=lang(297);}elseif($_POST["drop"]){if($_POST["views"])$H=drop_views($_POST["views"]);if($H&&$_POST["tables"])$H=drop_tables($_POST["tables"]);$B=lang(298);}elseif(JUSH=="sqlite"&&$_POST["check"]){foreach((array)$_POST["tables"]as$R){foreach(get_rows("PRAGMA integrity_check(".q($R).")")as$J)$B
.="<b>".h($R)."</b>: ".h($J["integrity_check"])."<br>";}}elseif(JUSH!="sql"){$H=(JUSH=="sqlite"?queries("VACUUM"):apply_queries("VACUUM".($_POST["optimize"]?" ANALYZE":""),(array)$_POST["tables"]));$B=lang(299);}elseif(!$_POST["tables"])$B=lang(12);elseif($H=queries(($_POST["optimize"]?"OPTIMIZE":($_POST["check"]?"CHECK":($_POST["repair"]?"REPAIR":"ANALYZE")))." TABLE ".implode(", ",array_map('Adminer\idf_escape',$_POST["tables"])))){while($J=$H->fetch_assoc())$B
.="<b>".h($J["Table"])."</b>: ".h($J["Msg_text"])."<br>";}queries_redirect(relative_uri(),$B,$H);}page_header(($_GET["ns"]==""?lang(42).": ".h(DB):lang(86).": ".h($_GET["ns"])),$l,true);if(adminer()->homepage()){if($_GET["ns"]!==""){$Ih=$_GET["order"];$Xd=($Ih||support("fast_status"));echo"<div>\n","<h3 id='tables-views'>".lang(300)."</h3>\n";$Pk=($Xd?table_status():tables_list());if(!$Pk)echo"<p class='message'>".lang(12)."\n";else{echo"<form action='' method='post'>\n";if(support("table")){echo"<fieldset><legend>".lang(301)." <span id='selected2'></span></legend><div>",html_select("op",adminer()->operators(),idx($_POST,"op",JUSH=="elastic"?"should":"LIKE %%"))," <input type='search' name='query' value='".h($_POST["query"])."'".on('keydown','submitKeydown','search').">"," <input type='submit' name='search' value='".lang(63)."'>\n","</div></fieldset>\n";if(!$l&&$_POST["search"]&&$_POST["query"]!=""){$_GET["where"][0]["op"]=$_POST["op"];search_tables();}}echo"<div class='scrollable'>\n","<table class='nowrap checkable odds'".on('click','tableClick').on('dblclick','tableClick').">\n",'<thead><tr class="wrap">','<td class="hover"><input id="check-all" type="checkbox" class="jsonly" title="'.lang(169).'"'.on('click','formCheck','^(tables|views)\[').'>','<th'.(!$Ih&&JUSH!='sqlite'?" aria-sort='ascending'":'').'><a href="'.h(substr($ug,0,-1)).'">'.lang(152).'</a>';$e=array("Engine"=>array(lang(302).doc_link(array('sql'=>'storage-engines.html'))));if(collations())$e["Collation"]=array(lang(141).doc_link(array('sql'=>'charset-charsets.html','mariadb'=>'supported-character-sets-and-collations/')));if(function_exists('Adminer\alter_table'))$e["Data_length"]=array(lang(303).doc_link(array('sql'=>'show-table-status.html','pgsql'=>'functions-admin.html#FUNCTIONS-ADMIN-DBOBJECT','oracle'=>'REFRN20286')),"create",lang(51),);if(support("indexes"))$e["Index_length"]=array(lang(304).doc_link(array('sql'=>'show-table-status.html','pgsql'=>'functions-admin.html#FUNCTIONS-ADMIN-DBOBJECT')),"indexes",lang(155),);$e["Data_free"]=array(lang(305).doc_link(array('sql'=>'show-table-status.html')),"edit",lang(52));if(function_exists('Adminer\alter_table'))$e["Auto_increment"]=array(lang(58).doc_link(array('sql'=>'example-auto-increment.html','mariadb'=>'auto_increment/')),"auto_increment=1&create",lang(51),);$e["Rows"]=array(lang(306).doc_link(array('sql'=>'show-table-status.html','pgsql'=>'catalog-pg-class.html#CATALOG-PG-CLASS','oracle'=>'REFRN20286')),"select",lang(48),);if(support("comment"))$e["Comment"]=array(lang(57).doc_link(array('sql'=>'show-table-status.html','pgsql'=>'functions-info.html#FUNCTIONS-INFO-COMMENT-TABLE')));$Da=array('Engine','Collation','Comment');foreach($e
as$x=>$d)echo"<th".($Ih==$x?" aria-sort='".(in_array($x,$Da)?"ascending":"descending")."'":"")."><a href='".h($ug)."order=$x'>$d[0]</a>";echo"<tbody>\n";if($Ih){uasort($Pk,function($ja,$Ka)use($Ih,$Da){$I=($ja[$Ih]<$Ka[$Ih]?-1:($ja[$Ih]>$Ka[$Ih]?1:0));return(in_array($Ih,$Da)?$I:-$I);});}$T=0;$zk=array("Data_length"=>0,"Index_length"=>0,"Data_free"=>0);foreach($Pk
as$C=>$P){$im=($Xd?is_view($P):$P!==null&&!preg_match('~table|sequence~i',$P));$P=($Xd?$P:array('Engine'=>$P));$t=h("Table-".$C);echo'<tr><td class="hover">'.checkbox(($im?"views[]":"tables[]"),$C,in_array("$C",$Qk,true),"","","",$t),'<th>'.(support("table")||support("indexes")?"<a href='".h(ME)."table=".url_escape($C)."' title='".lang(49)."' id='$t'>".h($C).'</a>':h($C));if($im&&!preg_match('~materialized~i',$P['Engine'])){$el=lang(151);echo'<td colspan="'.(count($e)-(support("comment")?2:1)).'">'.(support("view")?"<a href='".h(ME)."view=".url_escape($C)."' title='".lang(50)."'>$el</a>":$el),"<td align='right'><a href='".h(ME)."select=".url_escape($C)."' title='".lang(48)."'>?</a>";if(support("comment"))echo'<td>'.h($P['Comment']);}else{if($Xd){foreach(array_keys($zk)as$x)$zk[$x]+=($P["Engine"]!="InnoDB"||$x!="Data_free"?idx($P,$x):0);}foreach($e
as$x=>$d){$t=" id='$x-".h($C)."'";echo($d[1]?"<td align='right'><a href='".h(ME."$d[1]=").url_escape($C)."'$t title='$d[2]'>".format_status($P,$x)."</a>":"<td$t>".h(idx($P,$x,'?')));}$T++;}echo"\n";}echo"<tr><td class='hover'><th>".lang(278,count($Pk)),"<td>".h(JUSH=="sql"?get_val("SELECT @@default_storage_engine"):""),(collations()?"<td>".h(db_collation(DB,collations())):'');if($Xd&&function_exists('Adminer\db_status'))$zk=db_status();foreach($zk
as$x=>$yk)echo($e[$x]?"<td align='right' id='sum-$x'>".($Xd?format_number($yk):""):"");echo"\n","</table>\n",($Xd?'':script("ajaxSetHtml('".js_escape(ME)."script=db');")),"</div>\n";if(!information_schema(DB)){$am="<input type='submit' value='".lang(307)."'".on_help("VACUUM")."> ";$Eh="<input type='submit' name='optimize' value='".lang(308)."'".on_help(JUSH=="sql"?"OPTIMIZE TABLE":"VACUUM ANALYZE")."> ";$Mi=(JUSH=="sqlite"?$am."<input type='submit' name='check' value='".lang(309)."'".on_help("PRAGMA integrity_check")."> ":(JUSH=="pgsql"?$am.$Eh:(JUSH=="sql"?"<input type='submit' value='".lang(310)."'".on_help("ANALYZE TABLE")."> ".$Eh."<input type='submit' name='check' value='".lang(309)."'".on_help("CHECK TABLE")."> "."<input type='submit' name='repair' value='".lang(311)."'".on_help("REPAIR TABLE")."> ":""))).(function_exists('Adminer\truncate_tables')?"<input type='submit' name='truncate' value='".lang(312)."'".confirm().on_help(JUSH=="sqlite"?"DELETE":"TRUNCATE".(JUSH=="pgsql"?"":" TABLE"))."> ":"").(function_exists('Adminer\drop_tables')?"<input type='submit' name='drop' value='".lang(146)."'".confirm().on_help("DROP TABLE").">":"");echo($Mi?"<div class='footer'><div>\n<fieldset><legend>".lang(145)." <span id='selected'></span></legend><div>$Mi\n</div></fieldset>\n":"");$i=(support("scheme")?adminer()->schemas():adminer()->databases());if(count($i)!=1&&function_exists('Adminer\move_tables')){echo"<fieldset><legend>".lang(313)." <span id='selected3'></span></legend><div>";$j=(isset($_POST["target"])?$_POST["target"]:(support("scheme")?$_GET["ns"]:DB));echo($i?html_select("target",$i,$j):'<input name="target" value="'.h($j).'" autocapitalize="off">'),"</label> <input type='submit' name='move' value='".lang(129)."'>",(support("copy")?" <input type='submit' name='copy' value='".lang(22)."'> ".checkbox("overwrite",1,$_POST["overwrite"],lang(314)):""),"</div></fieldset>\n";}echo"<input type='hidden' name='all' value=''".on('click','countTables',$T).">\n",input_token(),"</div></div>\n";}echo"</form>\n",script("tableCheck();");}echo(function_exists('Adminer\alter_table')?"<p class='links hover'><a href='".h(ME)."create='>".lang(84)."</a>\n":''),(support("view")?"<a href='".h(ME)."view='>".lang(231)."</a>\n":""),"</div>\n";if(support("routine")){echo"<div>\n","<h3 id='routines'>".lang(79)."</h3>\n";$xj=routines();if($xj){echo"<table class='odds'>\n",'<thead><tr><th>'.lang(208).'<td>'.lang(56).'<td>'.lang(248)."<td class='hover'><tbody>\n";foreach($xj
as$J){$C=($J["SPECIFIC_NAME"]==$J["ROUTINE_NAME"]?"":"&name=".url_escape($J["ROUTINE_NAME"]));echo'<tr>','<th><a href="'.h(ME.($J["ROUTINE_TYPE"]!="PROCEDURE"?'callf=':'call=').url_escape($J["SPECIFIC_NAME"]).$C).'" title="'.lang(218).'">'.h($J["ROUTINE_NAME"]).'</a>','<td>'.h($J["ROUTINE_TYPE"]),'<td>'.h($J["DTD_IDENTIFIER"]),'<td class="hover"><a href="'.h(ME.($J["ROUTINE_TYPE"]!="PROCEDURE"?'function=':'procedure=').url_escape($J["SPECIFIC_NAME"]).$C).'">'.lang(158)."</a>";}echo"</table>\n";}echo'<p class="links hover">'.(support("procedure")?'<a href="'.h(ME).'procedure=">'.lang(247).'</a>':'').'<a href="'.h(ME).'function=">'.lang(246)."</a>\n","</div>\n";}if(support("sequence")){echo"<div>\n","<h3 id='sequences'>".lang(80)."</h3>\n";$Pj=get_vals("SELECT relname FROM pg_class WHERE relkind = 'S' AND relnamespace = ".driver()->nsOid." ORDER BY relname");if($Pj){echo"<table class='odds'>\n","<thead><tr><th>".lang(208)."<tbody>\n";foreach($Pj
as$X)echo"<tr><th><a href='".h(ME)."sequence=".url_escape($X)."'>".h($X)."</a>\n";echo"</table>\n";}echo"<p class='links hover'><a href='".h(ME)."sequence='>".lang(253)."</a>\n","</div>\n";}if(support("type")){echo"<div>\n","<h3 id='user-types'>".lang(7)."</h3>\n";$Xl=types();if($Xl){echo"<table class='odds'>\n","<thead><tr><th>".lang(208)."<tbody>\n";foreach($Xl
as$X)echo"<tr><th><a href='".h(ME)."type=".url_escape($X)."'>".h($X)."</a>\n";echo"</table>\n";}echo"<p class='links hover'><a href='".h(ME)."type='>".lang(258)."</a>\n","</div>\n";}if(support("event")){echo"<div>\n","<h3 id='events'>".lang(81)."</h3>\n";$K=get_rows("SHOW EVENTS");if($K){echo"<table>\n","<thead><tr><th>".lang(208)."<td>".lang(315)."<td>".lang(237)."<td>".lang(238)."<td class='hover'><tbody>\n";foreach($K
as$J)echo"<tr>","<th>".h($J["Name"]),"<td>".($J["Execute at"]?lang(316)."<td>".h($J["Execute at"]):lang(239)." ".h($J["Interval value"])." ".h($J["Interval field"])."<td>".h($J["Starts"])),"<td>".h($J["Ends"]),'<td class="hover"><a href="'.h(ME).'event='.url_escape($J["Name"]).'">'.lang(158).'</a>';echo"</table>\n";$fd=get_val("SELECT @@event_scheduler");if($fd&&$fd!="ON")echo"<p class='error'><code class='jush-sqlset'>event_scheduler</code>: ".h($fd)."\n";}echo'<p class="links hover"><a href="'.h(ME).'event=">'.lang(236)."</a>\n","</div>\n";}}}}page_footer();