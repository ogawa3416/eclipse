package exercise;

import lib.Input;

public class Ex10_01_1 {

	public static void main(String[] args) {
		int year = Input.getInt("歴年");
		if((year%4==0 && year%100!=0) || year%400==0) {
			System.out.println("うるう年です");
		}else {
			System.out.println("うるう年ではありません");
		}

	}

}
