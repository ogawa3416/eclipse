package exercise;

import lib.Input;

public class Ex10_05_1 {

	public static void main(String[] args) {
		int data, num1 =0, num2 =0;
		
		while((data = Input.getInt()) !=0) {
			if(data%3 == 0) {
				num1++;
			}else {
				num2++;
			}
		}
		
		System.out.println("3の倍数=" + num1);
		System.out.println("その他=" + num2);

	}

}
