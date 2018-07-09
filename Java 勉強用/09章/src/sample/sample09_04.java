package sample;

import lib.Input;

public class sample09_04 {

	public static void main(String[] args) {
		int value, total = 0;
		while((value = Input.getInt()) != 0) {
			total += value;
		}
		
		System.out.println("合計=" + total);

	}

}
