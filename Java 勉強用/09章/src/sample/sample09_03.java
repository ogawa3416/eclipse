package sample;

import lib.Input;

public class sample09_03 {

	public static void main(String[] args) {
		String str;
		while((str = Input.getString()) != null) {
			System.out.println(str);
		}
	}

}
