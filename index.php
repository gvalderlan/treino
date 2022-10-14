<?php
error_reporting(E_ALL);
ini_set('error_reporting', E_ALL);
ini_set('display_errors',1);
require_once 'class/AdConn.php';
//require_once 'class/DbConfig.php';

function expiraSenha(){
    $ldapconn = ldap_connect("sede.mpe");
    $email = USUARIO;
    $domain = '@mpmt.mp.br';
    $senha = SENHA;
    		
    
	ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);  //Set the LDAP Protocol used by your AD service
	ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);         //This was necessary for my AD to do anything
    $teste = ldap_bind($ldapconn, $email.$domain, $senha);
	/*if (@ldap_bind($ldapconn, $email.$domain, $senha) === false) {
	    echo "Senha inválida.";
		return;
	}*/
    var_dump($teste);
    if ($teste) {
        echo "LDAP bind anonymous successful...";
    } else {
        echo "LDAP bind anonymous failed...";
    }

    $dn = "ou=AD, dc=sede, dc=mpe"; 
	$filter="(&(samaccountname=$email)(objectClass=user))"; 
	$justthese = array("samaccountname", "displayname", "mail","pwdLastSet", "msDS-UserPasswordExpiryTimeComputed"); 
	$sr=ldap_search($ldapconn, $dn, $filter, $justthese); 
	$info = ldap_get_entries($ldapconn, $sr);
    
    var_dump($info);

    //print $info[0]['pwdlastset'][0];

    //$fileTime = $info[0]['pwdlastset'][0];
    $fileTime = $info[0]['msds-userpasswordexpirytimecomputed'][0];
    $winSecs       = (int)($fileTime / 10000000); // divide by 10 000 000 to get seconds
    $unixTimestamp = ($winSecs - 11644473600); // 1.1.1600 -> 1.1.1970 difference in seconds
    echo date("Y-m-d", $unixTimestamp);

}

expiraSenha();