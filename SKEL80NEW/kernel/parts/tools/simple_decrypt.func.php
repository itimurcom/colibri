<?
//..............................................................................
// декодирует строку
//..............................................................................
function simple_decrypt($text) {
	return decrypt_ssl($text);
	}
	
function decrypt_ssl($ciphertext='') {
	$c = base64_decode($ciphertext);
	$ivlen = openssl_cipher_iv_length($cipher="AES-256-CBC");
	$iv = substr($c, 0, $ivlen);
	$hmac = substr($c, $ivlen, $sha2len=32);
	$ciphertext_raw = substr($c, $ivlen+$sha2len);
	try {
		$original_plaintext = @openssl_decrypt($ciphertext_raw, $cipher, ENCRYPT_PHRASE, $options=OPENSSL_RAW_DATA, $iv); 
		return $original_plaintext;
		} catch (Exception $e) {
			return $ciphertext;
		}
	}
?>