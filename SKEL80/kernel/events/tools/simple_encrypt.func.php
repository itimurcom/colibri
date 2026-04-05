<?php
// ================ CRC ================
// version: 1.35.03
// hash: 97612d13c42968bd5f170b2a8dc9004b543611797bcbfa263f73033acec10762
// date: 10 March 2021  9:27
// ================ CRC ================
//..............................................................................
// кодирует строку
//..............................................................................
function simple_encrypt($text)
	{
	return encrypt_ssl($text);
	}

function encrypt_ssl($plaintext='')
	{
	$ivlen = openssl_cipher_iv_length($cipher="AES-256-CBC");
	$iv = openssl_random_pseudo_bytes($ivlen);
	$ciphertext_raw = openssl_encrypt($plaintext, $cipher, ENCRYPT_PHRASE, $options=OPENSSL_RAW_DATA, $iv);
	$hmac = hash_hmac('sha256', $ciphertext_raw, ENCRYPT_PHRASE, $as_binary=true);
	return base64_encode( $iv.$hmac.$ciphertext_raw );
	}
?>