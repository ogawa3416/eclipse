package exercise;

import lib.Input;

public class Ex09_03_1 {

	public static void main(String[] args) {
		int value, total = 0;
		int kensu = 0;
		
		while((value = Input.getInt()) != 0) {
			total += value;
			kensu++;
		}
		
		System.out.println("合計=" + total);
		System.out.println("件数=" + kensu);

	}

}
