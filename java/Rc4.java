public class RC4 {

	public static byte[] encrypt(byte[] plaintext, byte[] key) {
		byte[] ciphertext = new byte[plaintext.length];
		int[] s = new int[256];
		int i, j, k;
		for(i = 0; i < 256; i++) {
			s[i] = i;
		}

		int tmp;
		int key_len = key.length;
		if ((key_len < 1) || (key_len > 256)) {
			throw new IllegalArgumentException("key must be between 1 and 256 bytes");
		}
		j = 0;
		for(i = 0; i < 256; i++) {
			j = (j + s[i] + (key[(i % key_len)] & 0xff)) % 256;
			tmp = s[i];
			s[i] = s[j];
			s[j] = tmp;
		}

		i = j = k = 0;
		for(; k < plaintext.length; k++) {
			i = (i + 1) % 256;
			j = (j + s[i]) % 256;

			tmp = s[i];
			s[i] = s[j];
			s[j] = tmp;
			ciphertext[k] = (byte)(plaintext[k] ^ (byte)(s[((s[i] + s[j]) % 256)] & 0xff));
		}
		return ciphertext;
	}

	public static byte[] decrypt(byte[] ciphertext, byte[] key) {
		return encrypt(ciphertext, key);
	}
