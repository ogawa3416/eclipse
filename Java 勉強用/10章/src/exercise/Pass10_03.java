package exercise;

import lib.Input;

public class Pass10_03 {

	public static void main(String[] args) {
		int month;
		
		while((month = Input.getInt()) != 0) {
			if(month==12 || month <= 2) {
				System.out.println("冬");
			}else if(month <= 5) {
				System.out.println("春");
			}else if(month <= 8) {
				System.out.println("夏");
			}else if(month <= 11) {
				System.out.println("秋");
			}else {
				System.out.println("?");
			}
		}

	}

}
