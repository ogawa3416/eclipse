package exercise;

import lib.Input;

public class Pass09_01 {

	public static void main(String[] args) {
		String str;
		
		while((str = Input.getString()) != null) {
			System.out.println("<" + str.length() + ">" + str);
		}

	}

}
