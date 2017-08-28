package com.asiainfo.cs.ocs.core.service.impl;

import java.security.Key;
import java.text.SimpleDateFormat;
import java.util.Date;

import javax.crypto.BadPaddingException;
import javax.crypto.Cipher;
import javax.crypto.SecretKeyFactory;
import javax.crypto.spec.DESedeKeySpec;
import javax.crypto.spec.IvParameterSpec;

import sun.misc.BASE64Decoder;
import sun.misc.BASE64Encoder;


public class DesUtil {
	private final static String encoding = "utf-8";
	
	private static String getIVToday(){
		return new SimpleDateFormat("yyyyMMdd").format(new Date());
	}
	
	private static String getIVYesterday(){
		return new SimpleDateFormat("yyyyMMdd").format(new Date(new Date().getTime()-86400000));
	}
	
	private static String getIVTomorrow(){
		return new SimpleDateFormat("yyyyMMdd").format(new Date(new Date().getTime()+86400000));
	}
	
	private static String getSecretKeyToday(){
		return "cmcc_"+new SimpleDateFormat("yyyyMMdd").format(new Date())+"_asiainfo_ocs";
	}
	
	private static String getSecretKeyYesterday(){
		return "cmcc_"+new SimpleDateFormat("yyyyMMdd").format(new Date(new Date().getTime()-86400000))+"_asiainfo_ocs";
	}
	
	private static String getSecretKeyTomorrow(){
		return "cmcc_"+new SimpleDateFormat("yyyyMMdd").format(new Date(new Date().getTime()+86400000))+"_asiainfo_ocs";
	}
	
	public static String encode(String plainText) {
		Key deskey = null;
		byte[] encryptData = null;
		String secretKey = getSecretKeyToday();
		System.out.println(secretKey);
		try {
			DESedeKeySpec spec = new DESedeKeySpec(secretKey.getBytes());
			SecretKeyFactory keyfactory = SecretKeyFactory.getInstance("desede");
			deskey = keyfactory.generateSecret(spec);

			Cipher cipher = Cipher.getInstance("desede/CBC/PKCS5Padding");
			IvParameterSpec ips = new IvParameterSpec(getIVToday().getBytes());
			cipher.init(Cipher.ENCRYPT_MODE, deskey, ips);
			encryptData = cipher.doFinal(plainText.getBytes(encoding));
			
			BASE64Encoder base64Encoder = new BASE64Encoder();
	        return base64Encoder.encode(encryptData);
		} catch (Exception e) {
			e.printStackTrace();
		}
		return "";
	}
	
}
