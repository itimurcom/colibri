<?php
// ================ CRC ================
// version: 1.35.03
// hash: 04ac0d2c10deb8e2bb73df9ea6e0541d7c869c6c9c8cbf32f30ab6612999df7a
// date: 10 March 2021  9:27
// ================ CRC ================
//..............................................................................
// декодирует строку
//..............................................................................
function simple_decrypt($text)
	{
	return decrypt_ssl($text);
	}
	
function decrypt_ssl($ciphertext='')
	{
	$c = base64_decode($ciphertext);
	$ivlen = openssl_cipher_iv_length($cipher="AES-256-CBC");
	$iv = substr($c, 0, $ivlen);
	$hmac = substr($c, $ivlen, $sha2len=32);
	$ciphertext_raw = substr($c, $ivlen+$sha2len);
	$original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, ENCRYPT_PHRASE, $options=OPENSSL_RAW_DATA, $iv);
	return $original_plaintext;
	}
?>